<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour voir vos réservations.";
    header('Location: connexion.php');
    exit;
}

$db = new Database();
$pdo = $db->connect();
$id_utilisateur = $_SESSION['user_id'];

// Récupérer les réservations à venir
$sql_avenir = "SELECT 
                p.id_participe,
                p.nb_places_reservees,
                p.statut,
                c.id_covoiturage,
                c.lieu_depart,
                c.lieu_arrivee,
                c.date_depart,
                c.heure_depart,
                c.prix_credit,
                c.distance_km,
                u.pseudo as conducteur_pseudo,
                u.photo_profil as conducteur_photo,
                v.modele,
                m.libelle as nom_marque,
                CONCAT(c.date_depart, ' ', c.heure_depart) as datetime_depart
            FROM participe p
            INNER JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage
            INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            INNER JOIN voiture v ON c.id_voiture = v.id_voiture
            INNER JOIN marque m ON v.id_marque = m.id_marque
            WHERE p.id_utilisateur = :id_user
            AND p.statut = 'confirmee'
            AND CONCAT(c.date_depart, ' ', c.heure_depart) >= NOW()
            ORDER BY c.date_depart ASC, c.heure_depart ASC";

$stmt_avenir = $pdo->prepare($sql_avenir);
$stmt_avenir->execute(['id_user' => $id_utilisateur]);
$reservations_avenir = $stmt_avenir->fetchAll(PDO::FETCH_ASSOC);

// Récupérer l'historique (passées et annulées)
$sql_historique = "SELECT 
                    p.id_participe,
                    p.nb_places_reservees,
                    p.statut,
                    c.id_covoiturage,
                    c.lieu_depart,
                    c.lieu_arrivee,
                    c.date_depart,
                    c.heure_depart,
                    c.prix_credit,
                    c.distance_km,
                    u.pseudo as conducteur_pseudo,
                    u.photo_profil as conducteur_photo,
                    v.modele,
                    m.libelle as nom_marque,
                    CONCAT(c.date_depart, ' ', c.heure_depart) as datetime_depart
                FROM participe p
                INNER JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage
                INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                INNER JOIN voiture v ON c.id_voiture = v.id_voiture
                INNER JOIN marque m ON v.id_marque = m.id_marque
                WHERE p.id_utilisateur = :id_user
                AND (
                    p.statut = 'annulee' 
                    OR CONCAT(c.date_depart, ' ', c.heure_depart) < NOW()
                )
                ORDER BY c.date_depart DESC, c.heure_depart DESC";

$stmt_historique = $pdo->prepare($sql_historique);
$stmt_historique->execute(['id_user' => $id_utilisateur]);
$historique = $stmt_historique->fetchAll(PDO::FETCH_ASSOC);

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/mes-reservations.php';
include __DIR__ . '/../src/View/layout/footer.php';