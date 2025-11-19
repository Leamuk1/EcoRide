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

// Récupérer toutes les marques triées par popularité puis alphabétiquement
$sql_marques = "SELECT 
                    m.*,
                    COUNT(v.id_voiture) as nb_vehicules
                FROM marque m
                LEFT JOIN voiture v ON m.id_marque = v.id_marque
                WHERE m.libelle != 'Autre'
                GROUP BY m.id_marque
                ORDER BY nb_vehicules DESC, m.libelle ASC";
$stmt_marques = $pdo->query($sql_marques);
$marques = $stmt_marques->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $id_marque = $_POST['id_marque'] ?? '';
    $marque_personnalisee = trim($_POST['marque_personnalisee'] ?? '');
    $modele = htmlspecialchars(trim($_POST['modele'] ?? ''));
    $couleur = htmlspecialchars(trim($_POST['couleur'] ?? ''));
    $immatriculation = htmlspecialchars(trim($_POST['immatriculation'] ?? ''));
    $nb_places = (int)($_POST['nb_places'] ?? 0);
    $energie = $_POST['energie'] ?? '';
    
    $errors = [];
    
    // Gestion de la marque personnalisée
    if ($id_marque === 'autre' && !empty($marque_personnalisee)) {
        // Vérifier si la marque existe déjà
        $sql_check = "SELECT id_marque FROM marque WHERE LOWER(libelle) = LOWER(:libelle)";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute(['libelle' => $marque_personnalisee]);
        $marque_exist = $stmt_check->fetch();
        
        if ($marque_exist) {
            $id_marque = $marque_exist['id_marque'];
        } else {
            // Créer la nouvelle marque
            $sql_insert_marque = "INSERT INTO marque (libelle) VALUES (:libelle)";
            $stmt_insert_marque = $pdo->prepare($sql_insert_marque);
            $stmt_insert_marque->execute(['libelle' => $marque_personnalisee]);
            $id_marque = $pdo->lastInsertId();
        }
    }
    
    // Validation
    if (empty($id_marque) || $id_marque === 'autre') {
        $errors[] = "Veuillez sélectionner ou saisir une marque.";
    }
    
    if (empty($modele)) {
        $errors[] = "Le modèle est requis.";
    }
    
    if (empty($couleur)) {
        $errors[] = "La couleur est requise.";
    }
    
    if ($nb_places < 1 || $nb_places > 9) {
        $errors[] = "Le nombre de places doit être entre 1 et 9.";
    }
    
    if (empty($energie)) {
        $errors[] = "Le type d'énergie est requis.";
    }
    
    // Si pas d'erreurs, insérer
    if (empty($errors)) {
        try {
            $sql_insert = "INSERT INTO voiture 
                          (id_utilisateur, id_marque, modele, couleur, immatriculation, nb_places, energie)
                          VALUES 
                          (:id_user, :id_marque, :modele, :couleur, :immatriculation, :nb_places, :energie)";
            
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([
                'id_user' => $id_utilisateur,
                'id_marque' => $id_marque,
                'modele' => $modele,
                'couleur' => $couleur,
                'immatriculation' => $immatriculation,
                'nb_places' => $nb_places,
                'energie' => $energie
            ]);
            
            $_SESSION['success'] = "Véhicule ajouté avec succès !";
            header('Location: mes-vehicules.php');
            exit;
            
        } catch (Exception $e) {
            $errors[] = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
}

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

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/mes-vehicules.php';
include __DIR__ . '/../src/View/layout/footer.php';