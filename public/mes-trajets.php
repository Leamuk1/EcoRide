<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour voir vos trajets.";
    header('Location: connexion.php');
    exit;
}

$db = new Database();
$pdo = $db->connect();
$id_utilisateur = $_SESSION['user_id'];

// Récupérer les trajets à venir ET en cours
$sql_avenir = "SELECT 
                c.*,
                v.modele,
                m.libelle as nom_marque,
                v.energie,
                COUNT(DISTINCT p.id_participe) as nb_reservations,
                COALESCE(SUM(p.nb_places_reservees), 0) as places_reservees,
                (c.nb_place - COALESCE(SUM(p.nb_places_reservees), 0)) as places_restantes,
                CONCAT(c.date_depart, ' ', c.heure_depart) as datetime_depart
            FROM covoiturage c
            INNER JOIN voiture v ON c.id_voiture = v.id_voiture
            INNER JOIN marque m ON v.id_marque = m.id_marque
            LEFT JOIN participe p ON c.id_covoiturage = p.id_covoiturage AND p.statut = 'confirmee'
            WHERE c.id_utilisateur = :id_user
            AND c.statut IN ('en_attente', 'en_cours')
            AND CONCAT(c.date_depart, ' ', c.heure_depart) >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY c.id_covoiturage
            ORDER BY c.date_depart ASC, c.heure_depart ASC";

$stmt_avenir = $pdo->prepare($sql_avenir);
$stmt_avenir->execute(['id_user' => $id_utilisateur]);
$trajets_avenir = $stmt_avenir->fetchAll(PDO::FETCH_ASSOC);

// Récupérer l'historique
$sql_historique = "SELECT 
                    c.*,
                    v.modele,
                    m.libelle as nom_marque,
                    COUNT(DISTINCT p.id_participe) as nb_reservations,
                    COALESCE(SUM(p.nb_places_reservees), 0) as places_reservees,
                    CONCAT(c.date_depart, ' ', c.heure_depart) as datetime_depart
                FROM covoiturage c
                INNER JOIN voiture v ON c.id_voiture = v.id_voiture
                INNER JOIN marque m ON v.id_marque = m.id_marque
                LEFT JOIN participe p ON c.id_covoiturage = p.id_covoiturage AND p.statut = 'confirmee'
                WHERE c.id_utilisateur = :id_user
                AND (
                    c.statut = 'annule'
                    OR CONCAT(c.date_depart, ' ', c.heure_depart) < NOW()
                )
                GROUP BY c.id_covoiturage
                ORDER BY c.date_depart DESC, c.heure_depart DESC";

$stmt_historique = $pdo->prepare($sql_historique);
$stmt_historique->execute(['id_user' => $id_utilisateur]);
$historique = $stmt_historique->fetchAll(PDO::FETCH_ASSOC);

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/mes-trajets.php';
include __DIR__ . '/../src/View/layout/footer.php';