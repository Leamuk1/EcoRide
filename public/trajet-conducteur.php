<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté.";
    header('Location: connexion.php');
    exit;
}

$db = new Database();
$pdo = $db->connect();

$id_trajet = (int)($_GET['id'] ?? 0);
$id_utilisateur = $_SESSION['user_id'];

if ($id_trajet === 0) {
    header('Location: mes-trajets.php');
    exit;
}

// Récupérer les infos du trajet
$sql_trajet = "SELECT 
                c.*,
                v.modele,
                v.couleur,
                v.energie,
                m.libelle as nom_marque,
                COUNT(DISTINCT p.id_participe) as nb_reservations,
                COALESCE(SUM(p.nb_places_reservees), 0) as places_reservees,
                (c.nb_place - COALESCE(SUM(p.nb_places_reservees), 0)) as places_restantes,
                CONCAT(c.date_depart, ' ', c.heure_depart) as datetime_depart
            FROM covoiturage c
            INNER JOIN voiture v ON c.id_voiture = v.id_voiture
            INNER JOIN marque m ON v.id_marque = m.id_marque
            LEFT JOIN participe p ON c.id_covoiturage = p.id_covoiturage AND p.statut = 'confirmee'
            WHERE c.id_covoiturage = :id_trajet
            AND c.id_utilisateur = :id_user
            GROUP BY c.id_covoiturage";

$stmt_trajet = $pdo->prepare($sql_trajet);
$stmt_trajet->execute([
    'id_trajet' => $id_trajet,
    'id_user' => $id_utilisateur
]);
$trajet = $stmt_trajet->fetch(PDO::FETCH_ASSOC);

// Si le trajet n'existe pas ou n'appartient pas à l'utilisateur
if (!$trajet) {
    $_SESSION['error'] = "Trajet introuvable ou vous n'êtes pas le conducteur.";
    header('Location: mes-trajets.php');
    exit;
}

// Récupérer la liste des passagers
$sql_passagers = "SELECT 
                    p.*,
                    u.pseudo,
                    u.prenom,
                    u.nom,
                    u.email,
                    u.photo_profil
                FROM participe p
                INNER JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
                WHERE p.id_covoiturage = :id_trajet
                AND p.statut = 'confirmee'
                ORDER BY p.date_confirmation ASC";

$stmt_passagers = $pdo->prepare($sql_passagers);
$stmt_passagers->execute(['id_trajet' => $id_trajet]);
$passagers = $stmt_passagers->fetchAll(PDO::FETCH_ASSOC);

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

// Calculer les gains
$gains_total = $trajet['prix_credit'] * $trajet['places_reservees'];

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/trajet-conducteur.php';
include __DIR__ . '/../src/View/layout/footer.php';