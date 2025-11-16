<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

// Traitement du formulaire
if ($_POST) {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    
    // Connexion BDD
    $db = new Database();
    $pdo = $db->connect();
    
    // Rechercher l'utilisateur
    $sql = "SELECT * FROM utilisateur WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Vérifier le mot de passe
    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie
        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_pseudo'] = $user['pseudo'];
        $_SESSION['is_driver'] = $user['is_driver'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        // Redirection vers l'accueil
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['error'] = "Email ou mot de passe incorrect.";
        header('Location: connexion.php');
        exit;
    }
}

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/connexion.php';
include __DIR__ . '/../src/View/layout/footer.php';