<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Vérifier qu'il y a des détails de réservation
if (!isset($_SESSION['reservation_details'])) {
    header('Location: recherche.php');
    exit;
}

// Récupérer les détails de la réservation
$details = $_SESSION['reservation_details'];
$trajet = $details['trajet'];
$nb_places = $details['nb_places'];
$cout = $details['cout'];

// Récupérer les préférences du trajet
require_once __DIR__ . '/../src/Config/Database.php';
$db = new Database();
$pdo = $db->connect();

$sql_pref = "SELECT 
                tp.libelle,
                tp.icone,
                cp.preference_autre
            FROM covoiturage_preference cp
            LEFT JOIN type_preference tp ON cp.id_type_preference = tp.id_type_preference
            WHERE cp.id_covoiturage = :id";

$stmt_pref = $pdo->prepare($sql_pref);
$stmt_pref->execute(['id' => $trajet['id_covoiturage']]);
$preferences = $stmt_pref->fetchAll(PDO::FETCH_ASSOC);

// IMPORTANT : Supprimer les détails APRÈS avoir tout récupéré
// et AVANT d'inclure la vue
unset($_SESSION['reservation_details']);

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/confirmation-reservation.php';
include __DIR__ . '/../src/View/layout/footer.php';