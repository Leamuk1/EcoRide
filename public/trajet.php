<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

$db = new Database();
$pdo = $db->connect();

// Récupérer l'ID du trajet
$id_trajet = (int)($_GET['id'] ?? 0);

if ($id_trajet === 0) {
    header('Location: recherche.php');
    exit;
}

// Requête pour récupérer le trajet avec toutes les infos
$sql = "SELECT 
            c.*,
            u.prenom,
            u.nom,
            u.pseudo,
            u.photo_profil,
            v.modele,
            v.couleur,
            v.energie,
            v.nb_places as nb_places_voiture,
            m.libelle as nom_marque,
            (c.nb_place - COALESCE(SUM(p.nb_places_reservees), 0)) as places_restantes
        FROM covoiturage c
        INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        INNER JOIN voiture v ON c.id_voiture = v.id_voiture
        INNER JOIN marque m ON v.id_marque = m.id_marque
        LEFT JOIN participe p ON c.id_covoiturage = p.id_covoiturage
        WHERE c.id_covoiturage = :id
        GROUP BY c.id_covoiturage";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_trajet]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);

// Si trajet introuvable
if (!$trajet) {
    $_SESSION['error'] = "Trajet introuvable.";
    header('Location: recherche.php');
    exit;
}

// Récupérer les préférences du trajet
$sql_pref = "SELECT 
                tp.libelle,
                tp.icone,
                cp.preference_autre
            FROM covoiturage_preference cp
            LEFT JOIN type_preference tp ON cp.id_type_preference = tp.id_type_preference
            WHERE cp.id_covoiturage = :id";

$stmt_pref = $pdo->prepare($sql_pref);
$stmt_pref->execute(['id' => $id_trajet]);
$preferences = $stmt_pref->fetchAll(PDO::FETCH_ASSOC);

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/trajet.php';
include __DIR__ . '/../src/View/layout/footer.php';