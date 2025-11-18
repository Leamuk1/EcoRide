<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

// Traitement du formulaire
if ($_POST) {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $date_naissance = $_POST['date_naissance'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Validation
    if ($password !== $password_confirm) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header('Location: inscription.php');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email invalide.";
        header('Location: inscription.php');
        exit;
    }

    // Vérifier l'acceptation des CGU
    if (!isset($_POST['terms'])) {
        $_SESSION['error'] = "Vous devez accepter les conditions d'utilisation.";
        header('Location: inscription.php');
        exit;
    }
    
    // Connexion BDD
    $db = new Database();
    $pdo = $db->connect();
    
    // Vérifier si l'email existe déjà
    $sql = "SELECT id_utilisateur FROM utilisateur WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Cet email est déjà utilisé.";
        header('Location: inscription.php');
        exit;
    }
    
    // Hasher le mot de passe
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
    // Générer un pseudo unique
    $pseudo = substr($prenom, 0, 1) . $nom;
    
    // Insérer l'utilisateur
    $sql = "INSERT INTO utilisateur (nom, prenom, pseudo, email, password, date_naissance, credits) 
            VALUES (:nom, :prenom, :pseudo, :email, :password, :date_naissance, 20)";
    
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'pseudo' => $pseudo,
        'email' => $email,
        'password' => $password_hash,
        'date_naissance' => $date_naissance
    ]);
    
    if ($success) {
        // Récupérer l'ID du nouvel utilisateur
        $user_id = $pdo->lastInsertId();
        
        // Connecter automatiquement l'utilisateur
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_pseudo'] = $pseudo;
        $_SESSION['user_prenom'] = $prenom;
        $_SESSION['user_credits'] = 20; // IMPORTANT : Initialiser les crédits
        $_SESSION['user_photo'] = null;
        
        $_SESSION['success'] = "Bienvenue sur EcoRide ! Vous avez reçu 20 crédits de bienvenue.";
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de l'inscription.";
        header('Location: inscription.php');
        exit;
    }
}

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/inscription.php';
include __DIR__ . '/../src/View/layout/footer.php';