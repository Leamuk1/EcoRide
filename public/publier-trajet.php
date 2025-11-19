<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour publier un trajet.";
    header('Location: connexion.php');
    exit;
}

$db = new Database();
$pdo = $db->connect();
$id_utilisateur = $_SESSION['user_id'];

// Récupérer les véhicules de l'utilisateur
$sql_vehicules = "SELECT 
                    v.*,
                    m.libelle as nom_marque
                FROM voiture v
                INNER JOIN marque m ON v.id_marque = m.id_marque
                WHERE v.id_utilisateur = :id_user
                ORDER BY v.id_voiture DESC";

$stmt_vehicules = $pdo->prepare($sql_vehicules);
$stmt_vehicules->execute(['id_user' => $id_utilisateur]);
$vehicules = $stmt_vehicules->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les types de préférences
$sql_preferences = "SELECT * FROM type_preference ORDER BY id_type_preference ASC";
$stmt_preferences = $pdo->query($sql_preferences);
$types_preferences = $stmt_preferences->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données
    $id_voiture = (int)($_POST['id_voiture'] ?? 0);
    $lieu_depart = htmlspecialchars(trim($_POST['lieu_depart'] ?? ''));
    $lieu_arrivee = htmlspecialchars(trim($_POST['lieu_arrivee'] ?? ''));
    $date_depart = $_POST['date_depart'] ?? '';
    $heure_depart = $_POST['heure_depart'] ?? '';
    $heure_arrivee = $_POST['heure_arrivee'] ?? '';
    $nb_place = (int)($_POST['nb_place'] ?? 0);
    $prix_credit = (int)($_POST['prix_credit'] ?? 0);
    $distance_km = (int)($_POST['distance_km'] ?? 0);
    $preferences = $_POST['preferences'] ?? [];
    
    // Validation
    $errors = [];
    
    if ($id_voiture === 0) {
        $errors[] = "Veuillez sélectionner un véhicule.";
    }
    
    if (empty($lieu_depart)) {
        $errors[] = "Le lieu de départ est requis.";
    }
    
    if (empty($lieu_arrivee)) {
        $errors[] = "Le lieu d'arrivée est requis.";
    }
    
    if (empty($date_depart)) {
        $errors[] = "La date de départ est requise.";
    }
    
    if (empty($heure_depart)) {
        $errors[] = "L'heure de départ est requise.";
    }
    
    if ($nb_place < 1 || $nb_place > 8) {
        $errors[] = "Le nombre de places doit être entre 1 et 8.";
    }
    
    if ($prix_credit < 1) {
        $errors[] = "Le prix doit être d'au moins 1 crédit.";
    }
    
    // Vérifier que la date n'est pas dans le passé
    $datetime_depart = new DateTime($date_depart . ' ' . $heure_depart);
    $now = new DateTime();
    
    if ($datetime_depart <= $now) {
        $errors[] = "La date et l'heure de départ doivent être dans le futur.";
    }
    
    // Si pas d'erreurs, créer le trajet
    if (empty($errors)) {
        try {
            // Démarrer une transaction
            $pdo->beginTransaction();
            
            // Vérifier que le véhicule appartient bien à l'utilisateur
            $sql_check_voiture = "SELECT * FROM voiture WHERE id_voiture = :id AND id_utilisateur = :id_user";
            $stmt_check = $pdo->prepare($sql_check_voiture);
            $stmt_check->execute([
                'id' => $id_voiture,
                'id_user' => $id_utilisateur
            ]);
            
            if (!$stmt_check->fetch()) {
                throw new Exception("Ce véhicule ne vous appartient pas.");
            }
            
            // Insérer le trajet
            $sql_insert = "INSERT INTO covoiturage 
                          (id_utilisateur, id_voiture, lieu_depart, lieu_arrivee, 
                           date_depart, heure_depart, heure_arrivee, 
                           nb_place, prix_credit, distance_km, statut)
                          VALUES 
                          (:id_user, :id_voiture, :lieu_depart, :lieu_arrivee,
                           :date_depart, :heure_depart, :heure_arrivee,
                           :nb_place, :prix_credit, :distance_km, 'en_attente')";
            
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([
                'id_user' => $id_utilisateur,
                'id_voiture' => $id_voiture,
                'lieu_depart' => $lieu_depart,
                'lieu_arrivee' => $lieu_arrivee,
                'date_depart' => $date_depart,
                'heure_depart' => $heure_depart,
                'heure_arrivee' => $heure_arrivee,
                'nb_place' => $nb_place,
                'prix_credit' => $prix_credit,
                'distance_km' => $distance_km
            ]);
            
            $id_trajet = $pdo->lastInsertId();
            
            // Insérer les préférences
if (!empty($preferences)) {
    $sql_pref = "INSERT INTO covoiturage_preference (id_covoiturage, id_type_preference) 
                 VALUES (:id_trajet, :id_pref)";
    $stmt_pref = $pdo->prepare($sql_pref);
    
    foreach ($preferences as $id_pref) {
        $stmt_pref->execute([
            'id_trajet' => $id_trajet,
            'id_pref' => (int)$id_pref
        ]);
    }
}
            
            // Valider la transaction
            $pdo->commit();
            
            $_SESSION['success'] = "Trajet publié avec succès !";
            header('Location: mes-trajets.php');
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = $e->getMessage();
        }
    }
    
    // Stocker les erreurs en session
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
}

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/publier-trajet.php';
include __DIR__ . '/../src/View/layout/footer.php';