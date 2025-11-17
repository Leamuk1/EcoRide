<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

$db = new Database();
$pdo = $db->connect();

// Récupérer les paramètres de recherche
$ville_depart = htmlspecialchars(trim($_GET['depart'] ?? ''));
$ville_arrivee = htmlspecialchars(trim($_GET['arrivee'] ?? ''));
$date_trajet = $_GET['date'] ?? '';
$nb_passagers = (int)($_GET['passagers'] ?? 1);

$trajets = [];

// Si recherche effectuée
if (!empty($ville_depart) && !empty($ville_arrivee) && !empty($date_trajet)) {
    
    // Requête de recherche
        $sql = "SELECT 
            c.*,
            u.prenom,
            u.nom,
            u.pseudo,
            u.photo_profil,
            v.modele,
            m.libelle as nom_marque,
            (c.nb_place - COALESCE(SUM(p.nb_places_reservees), 0)) as places_restantes
        FROM covoiturage c
        INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        INNER JOIN voiture v ON c.id_voiture = v.id_voiture
        INNER JOIN marque m ON v.id_marque = m.id_marque
        LEFT JOIN participe p ON c.id_covoiturage = p.id_covoiturage
        WHERE c.lieu_depart LIKE :depart
        AND c.lieu_arrivee LIKE :arrivee
        AND c.date_depart = :date
        AND c.statut = 'en_attente'
        GROUP BY c.id_covoiturage
        HAVING places_restantes >= :nb_passagers
        ORDER BY c.heure_depart ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'depart' => '%' . $ville_depart . '%',
        'arrivee' => '%' . $ville_arrivee . '%',
        'date' => $date_trajet,
        'nb_passagers' => $nb_passagers
    ]);
    
    $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/recherche.php';
include __DIR__ . '/../src/View/layout/footer.php';