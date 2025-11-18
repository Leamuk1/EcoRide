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
    header('Location: mes-reservations.php');
    exit;
}

$db = new Database();
$pdo = $db->connect();

$id_participe = (int)($_POST['id_participe'] ?? 0);
$id_utilisateur = $_SESSION['user_id'];

if ($id_participe === 0) {
    $_SESSION['error'] = "Données invalides.";
    header('Location: mes-reservations.php');
    exit;
}

try {
    // Démarrer une transaction
    $pdo->beginTransaction();
    
    // 1. Récupérer les infos de la réservation
    $sql = "SELECT 
                p.*,
                c.date_depart,
                c.heure_depart,
                c.prix_credit,
                c.id_utilisateur as id_conducteur,
                CONCAT(c.date_depart, ' ', c.heure_depart) as datetime_depart
            FROM participe p
            INNER JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage
            WHERE p.id_participe = :id_participe
            AND p.id_utilisateur = :id_user
            AND p.statut = 'confirmee'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id_participe' => $id_participe,
        'id_user' => $id_utilisateur
    ]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$reservation) {
        throw new Exception("Réservation introuvable ou déjà annulée.");
    }
    
    // 2. Calculer le remboursement
    $datetime_depart = new DateTime($reservation['datetime_depart']);
    $now = new DateTime();
    $interval = $now->diff($datetime_depart);
    $heures_restantes = ($interval->days * 24) + $interval->h;
    
    $cout_total = $reservation['prix_credit'] * $reservation['nb_places_reservees'];
    
    if ($heures_restantes > 24) {
        $remboursement = $cout_total; // 100%
        $pourcentage = 100;
    } elseif ($heures_restantes > 0) {
        $remboursement = $cout_total * 0.5; // 50%
        $pourcentage = 50;
    } else {
        throw new Exception("Impossible d'annuler un trajet déjà commencé.");
    }
    
    // 3. Mettre à jour le statut de la réservation
    $sql_update = "UPDATE participe 
                   SET statut = 'annulee', 
                       date_annulation = NOW(),
                       montant_rembourse = :remboursement
                   WHERE id_participe = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        'remboursement' => $remboursement,
        'id' => $id_participe
    ]);
    
    // 4. Rembourser le passager
    $sql_remb_passager = "UPDATE utilisateur 
                          SET credits = credits + :remboursement 
                          WHERE id_utilisateur = :id";
    $stmt_remb_passager = $pdo->prepare($sql_remb_passager);
    $stmt_remb_passager->execute([
        'remboursement' => $remboursement,
        'id' => $id_utilisateur
    ]);
    
    // 5. Retirer les crédits au conducteur
    $sql_retrait_conducteur = "UPDATE utilisateur 
                               SET credits = credits - :montant 
                               WHERE id_utilisateur = :id";
    $stmt_retrait_conducteur = $pdo->prepare($sql_retrait_conducteur);
    $stmt_retrait_conducteur->execute([
        'montant' => $remboursement,
        'id' => $reservation['id_conducteur']
    ]);
    
    // 6. Mettre à jour les crédits en session
    $_SESSION['user_credits'] = $_SESSION['user_credits'] + $remboursement;
    
    // Valider la transaction
    $pdo->commit();
    
    $_SESSION['success'] = "Réservation annulée. Vous avez été remboursé de {$remboursement} crédits ({$pourcentage}%).";
    header('Location: mes-reservations.php');
    exit;
    
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = $e->getMessage();
    header('Location: mes-reservations.php');
    exit;
}