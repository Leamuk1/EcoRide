<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$db = new Database();
$pdo = $db->connect();

$id_voiture = (int)($_GET['id'] ?? 0);
$id_utilisateur = $_SESSION['user_id'];

if ($id_voiture === 0) {
    $_SESSION['error'] = "ID véhicule invalide.";
    header('Location: mes-vehicules.php');
    exit;
}

try {
    // Vérifier que le véhicule appartient à l'utilisateur
    $sql_check = "SELECT * FROM voiture WHERE id_voiture = :id AND id_utilisateur = :id_user";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['id' => $id_voiture, 'id_user' => $id_utilisateur]);
    
    if (!$stmt_check->fetch()) {
        throw new Exception("Ce véhicule ne vous appartient pas.");
    }
    
    // Vérifier qu'il n'y a pas de trajets actifs avec ce véhicule
    $sql_trajets = "SELECT COUNT(*) as nb FROM covoiturage 
                    WHERE id_voiture = :id 
                    AND statut = 'en_attente'
                    AND CONCAT(date_depart, ' ', heure_depart) >= NOW()";
    $stmt_trajets = $pdo->prepare($sql_trajets);
    $stmt_trajets->execute(['id' => $id_voiture]);
    $result = $stmt_trajets->fetch();
    
    if ($result['nb'] > 0) {
        throw new Exception("Impossible de supprimer ce véhicule car il est utilisé dans des trajets à venir.");
    }
    
    // Supprimer le véhicule
    $sql_delete = "DELETE FROM voiture WHERE id_voiture = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute(['id' => $id_voiture]);
    
    $_SESSION['success'] = "Véhicule supprimé avec succès.";
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header('Location: mes-vehicules.php');
exit;