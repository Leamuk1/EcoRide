<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour réserver un trajet.";
    header('Location: connexion.php');
    exit;
}

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: recherche.php');
    exit;
}

$db = new Database();
$pdo = $db->connect();

// Récupérer les données du formulaire
$id_trajet = (int)($_POST['id_trajet'] ?? 0);
$nb_places = (int)($_POST['nb_places'] ?? 0);
$id_utilisateur = $_SESSION['user_id'];

// Validation des données
if ($id_trajet === 0 || $nb_places === 0 || $nb_places > 3) {
    $_SESSION['error'] = "Données invalides.";
    header('Location: recherche.php');
    exit;
}

try {
    // Démarrer une transaction
    $pdo->beginTransaction();
    
    // 1. Récupérer les infos du trajet
    $sql_trajet = "SELECT 
                    c.*,
                    u.prenom as conducteur_prenom,
                    u.nom as conducteur_nom,
                    u.pseudo as conducteur_pseudo,
                    (c.nb_place - COALESCE(SUM(p.nb_places_reservees), 0)) as places_restantes
                FROM covoiturage c
                INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                LEFT JOIN participe p ON c.id_covoiturage = p.id_covoiturage
                WHERE c.id_covoiturage = :id_trajet
                GROUP BY c.id_covoiturage";
    
    $stmt = $pdo->prepare($sql_trajet);
    $stmt->execute(['id_trajet' => $id_trajet]);
    $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$trajet) {
        throw new Exception("Trajet introuvable.");
    }
    
    // 2. Vérifier que ce n'est pas son propre trajet
    if ($trajet['id_utilisateur'] == $id_utilisateur) {
        throw new Exception("Vous ne pouvez pas réserver votre propre trajet.");
    }
    
    // 3. Vérifier qu'il y a assez de places
    if ($trajet['places_restantes'] < $nb_places) {
        throw new Exception("Il n'y a pas assez de places disponibles.");
    }
    
    // 4. Vérifier que l'utilisateur n'a pas déjà réservé ce trajet
    $sql_check = "SELECT COUNT(*) as nb FROM participe 
                  WHERE id_utilisateur = :id_user 
                  AND id_covoiturage = :id_trajet
                  AND statut = 'confirmee'";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([
        'id_user' => $id_utilisateur,
        'id_trajet' => $id_trajet
    ]);
    $check = $stmt_check->fetch();
    
    if ($check['nb'] > 0) {
        throw new Exception("Vous avez déjà réservé ce trajet.");
    }
    
    // 5. Récupérer les crédits de l'utilisateur
    $sql_credits = "SELECT credits FROM utilisateur WHERE id_utilisateur = :id";
    $stmt_credits = $pdo->prepare($sql_credits);
    $stmt_credits->execute(['id' => $id_utilisateur]);
    $user = $stmt_credits->fetch();
    
    $cout_total = $trajet['prix_credit'] * $nb_places;
    
    // 6. Vérifier que l'utilisateur a assez de crédits
    if ($user['credits'] < $cout_total) {
        throw new Exception("Vous n'avez pas assez de crédits. Il vous faut {$cout_total} crédits, vous en avez {$user['credits']}.");
    }
    
    // 7. Déduire les crédits du passager
    $sql_deduct = "UPDATE utilisateur 
                   SET credits = credits - :cout 
                   WHERE id_utilisateur = :id";
    $stmt_deduct = $pdo->prepare($sql_deduct);
    $stmt_deduct->execute([
        'cout' => $cout_total,
        'id' => $id_utilisateur
    ]);
    
    // 8. Ajouter les crédits au conducteur
    $sql_add = "UPDATE utilisateur 
                SET credits = credits + :gain 
                WHERE id_utilisateur = :id";
    $stmt_add = $pdo->prepare($sql_add);
    $stmt_add->execute([
        'gain' => $cout_total,
        'id' => $trajet['id_utilisateur']
    ]);
    
    // 9. Créer la réservation
    $sql_reserve = "INSERT INTO participe (id_utilisateur, id_covoiturage, nb_places_reservees, statut, date_confirmation)
                    VALUES (:id_user, :id_trajet, :nb_places, 'confirmee', NOW())";
    $stmt_reserve = $pdo->prepare($sql_reserve);
    $stmt_reserve->execute([
        'id_user' => $id_utilisateur,
        'id_trajet' => $id_trajet,
        'nb_places' => $nb_places
    ]);
    
    // 10. Mettre à jour les crédits en session
    $_SESSION['user_credits'] = $user['credits'] - $cout_total;
    
    // Valider la transaction
    $pdo->commit();
    
    // Message de succès
    $_SESSION['success'] = "Réservation confirmée ! {$nb_places} place(s) réservée(s) pour {$cout_total} crédits.";
    $_SESSION['reservation_details'] = [
        'trajet' => $trajet,
        'nb_places' => $nb_places,
        'cout' => $cout_total
    ];
    
    header('Location: confirmation-reservation.php');
    exit;
    
} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    
    $_SESSION['error'] = $e->getMessage();
    header('Location: trajet.php?id=' . $id_trajet);
    exit;
}