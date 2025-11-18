<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté.";
    header('Location: connexion.php');
    exit;
}

$db = new Database();
$pdo = $db->connect();
$id_utilisateur = $_SESSION['user_id'];

// Récupérer l'historique des transactions
$sql_transactions = "SELECT 
                        'reservation' as type,
                        c.lieu_depart,
                        c.lieu_arrivee,
                        p.nb_places_reservees,
                        (c.prix_credit * p.nb_places_reservees) as montant,
                        p.date_confirmation as date_transaction,
                        'Débit' as sens
                    FROM participe p
                    INNER JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage
                    WHERE p.id_utilisateur = :id_user
                    AND p.statut = 'confirmee'
                    
                    UNION ALL
                    
                    SELECT 
                        'annulation' as type,
                        c.lieu_depart,
                        c.lieu_arrivee,
                        p.nb_places_reservees,
                        p.montant_rembourse as montant,
                        p.date_annulation as date_transaction,
                        'Crédit' as sens
                    FROM participe p
                    INNER JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage
                    WHERE p.id_utilisateur = :id_user
                    AND p.statut = 'annulee'
                    AND p.montant_rembourse > 0
                    
                    UNION ALL
                    
                    SELECT 
                        'gain' as type,
                        c.lieu_depart,
                        c.lieu_arrivee,
                        p.nb_places_reservees,
                        (c.prix_credit * p.nb_places_reservees) as montant,
                        p.date_confirmation as date_transaction,
                        'Crédit' as sens
                    FROM participe p
                    INNER JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage
                    WHERE c.id_utilisateur = :id_user
                    AND p.statut = 'confirmee'
                    
                    ORDER BY date_transaction DESC
                    LIMIT 50";

$stmt = $pdo->prepare($sql_transactions);
$stmt->execute(['id_user' => $id_utilisateur]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/paiement-remboursement.php';
include __DIR__ . '/../src/View/layout/footer.php';