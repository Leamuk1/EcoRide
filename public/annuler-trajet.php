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
    // Démarrer une transaction
    $pdo->beginTransaction();
    
    // 1. Vérifier que c'est bien son trajet
    $sql_check = "SELECT * FROM covoiturage 
                  WHERE id_covoiturage = :id_trajet 
                  AND id_utilisateur = :id_user
                  AND statut = 'en_attente'";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([
        'id_trajet' => $id_trajet,
        'id_user' => $id_utilisateur
    ]);
    $trajet = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if (!$trajet) {
        throw new Exception("Trajet introuvable ou déjà annulé.");
    }
    
    // 2. Récupérer toutes les réservations confirmées
    $sql_reservations = "SELECT 
                            p.*,
                            u.pseudo
                        FROM participe p
                        INNER JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur
                        WHERE p.id_covoiturage = :id_trajet
                        AND p.statut = 'confirmee'";
    $stmt_reservations = $pdo->prepare($sql_reservations);
    $stmt_reservations->execute(['id_trajet' => $id_trajet]);
    $reservations = $stmt_reservations->fetchAll(PDO::FETCH_ASSOC);
    
    $total_rembourse = 0;
    
    // 3. Rembourser tous les passagers
    foreach ($reservations as $reservation) {
        $montant_remboursement = $trajet['prix_credit'] * $reservation['nb_places_reservees'];
        
        // Rembourser le passager
        $sql_remb = "UPDATE utilisateur 
                     SET credits = credits + :montant 
                     WHERE id_utilisateur = :id";
        $stmt_remb = $pdo->prepare($sql_remb);
        $stmt_remb->execute([
            'montant' => $montant_remboursement,
            'id' => $reservation['id_utilisateur']
        ]);
        
        // Mettre à jour la réservation
        $sql_update_participe = "UPDATE participe 
                                 SET statut = 'annulee',
                                     date_annulation = NOW(),
                                     montant_rembourse = :montant
                                 WHERE id_participe = :id";
        $stmt_update_participe = $pdo->prepare($sql_update_participe);
        $stmt_update_participe->execute([
            'montant' => $montant_remboursement,
            'id' => $reservation['id_participe']
        ]);
        
        $total_rembourse += $montant_remboursement;
    }
    
    // 4. Retirer les crédits au conducteur
    if ($total_rembourse > 0) {
        $sql_retrait = "UPDATE utilisateur 
                        SET credits = credits - :montant 
                        WHERE id_utilisateur = :id";
        $stmt_retrait = $pdo->prepare($sql_retrait);
        $stmt_retrait->execute([
            'montant' => $total_rembourse,
            'id' => $id_utilisateur
        ]);
        
        // Mettre à jour la session
        $_SESSION['user_credits'] = $_SESSION['user_credits'] - $total_rembourse;
    }
    
    // 5. Marquer le trajet comme annulé
    $sql_annuler = "UPDATE covoiturage 
                    SET statut = 'annule' 
                    WHERE id_covoiturage = :id";
    $stmt_annuler = $pdo->prepare($sql_annuler);
    $stmt_annuler->execute(['id' => $id_trajet]);
    
    // Valider la transaction
    $pdo->commit();
    
    $nb_passagers = count($reservations);
    $_SESSION['success'] = "Trajet annulé. {$nb_passagers} passager" . ($nb_passagers > 1 ? 's' : '') . " remboursé" . ($nb_passagers > 1 ? 's' : '') . " ({$total_rembourse} crédits).";
    header('Location: mes-trajets.php');
    exit;
    
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = $e->getMessage();
    header('Location: mes-trajets.php');
    exit;
}