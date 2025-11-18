<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté.";
    header('Location: connexion.php');
    exit;
}

// Vérifier que c'est une requête POST
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
    // Vérifier que c'est bien son trajet et qu'il n'y a pas de réservations
    $sql = "SELECT c.*, COUNT(p.id_participe) as nb_reservations
            FROM covoiturage c
            LEFT JOIN participe p ON c.id_covoiturage = p.id_covoiturage AND p.statut = 'confirmee'
            WHERE c.id_covoiturage = :id_trajet 
            AND c.id_utilisateur = :id_user
            AND c.statut = 'en_attente'
            GROUP BY c.id_covoiturage";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id_trajet' => $id_trajet,
        'id_user' => $id_utilisateur
    ]);
    $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$trajet) {
        throw new Exception("Trajet introuvable.");
    }
    
    if ($trajet['nb_reservations'] > 0) {
        throw new Exception("Impossible de supprimer un trajet avec des réservations. Annulez-le plutôt.");
    }
    
    // Supprimer le trajet
    $sql_delete = "DELETE FROM covoiturage WHERE id_covoiturage = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute(['id' => $id_trajet]);
    
    $_SESSION['success'] = "Trajet supprimé avec succès.";
    header('Location: mes-trajets.php');
    exit;
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: mes-trajets.php');
    exit;
}