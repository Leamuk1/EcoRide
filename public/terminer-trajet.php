<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté.";
    header('Location: connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: mes-trajets.php');
    exit;
}

$db = new Database();
$pdo = $db->connect();

$id_trajet = (int)($_POST['id_trajet'] ?? 0);
$id_utilisateur = $_SESSION['user_id'];

if ($id_trajet === 0) {
    $_SESSION['error'] = "Données invalides.";
    header('Location: mes-trajets.php');
    exit;
}

try {
    // Vérifier que c'est bien son trajet
    $sql_check = "SELECT * FROM covoiturage 
                  WHERE id_covoiturage = :id 
                  AND id_utilisateur = :id_user
                  AND statut = 'en_attente'";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['id' => $id_trajet, 'id_user' => $id_utilisateur]);
    $trajet = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if (!$trajet) {
        throw new Exception("Trajet introuvable ou déjà démarré/annulé.");
    }
    
    // Vérifier qu'il y a au moins une réservation
    $sql_count = "SELECT COUNT(*) as nb FROM participe 
                  WHERE id_covoiturage = :id AND statut = 'confirmee'";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute(['id' => $id_trajet]);
    $result = $stmt_count->fetch();
    
    if ($result['nb'] == 0) {
        throw new Exception("Impossible de démarrer un trajet sans passagers.");
    }
    
    // Démarrer le trajet
    $sql_update = "UPDATE covoiturage 
                   SET statut = 'en_cours' 
                   WHERE id_covoiturage = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute(['id' => $id_trajet]);
    
    $_SESSION['success'] = "🚗 Trajet démarré ! Bon voyage !";
    header('Location: trajet-conducteur.php?id=' . $id_trajet);
    exit;
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: trajet-conducteur.php?id=' . $id_trajet);
    exit;
}