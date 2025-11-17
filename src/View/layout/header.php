<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturage écologique</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Favicon EcoRide -->
    <link rel="icon" type="image/png" href="assets/images/Ecoride Favicon.png">
</head>
<body>
    <?php
// Vérification du timeout de session (30 minutes)
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        // Session expirée
        session_unset();
        session_destroy();
        header('Location: connexion.php');
        exit;
    }
    // Mettre à jour le timestamp
    $_SESSION['last_activity'] = time();
}
?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <span class="logo-eco">ECO|</span><span class="logo-ride">RIDE</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recherche.php">Covoiturages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                <div class="ms-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Utilisateur connecté -->
    <span class="navbar-text me-3 d-flex align-items-center">
    <?php if (!empty($_SESSION['user_photo']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $_SESSION['user_photo'])): ?>
        <img src="uploads/avatars/<?= htmlspecialchars($_SESSION['user_photo']) ?>" alt="Photo de profil" class="avatar-header me-2">
    <?php else: ?>
        <div class="avatar-header-initials me-2">
            <?= strtoupper(substr($_SESSION['user_prenom'] ?? '', 0, 1) . substr($_SESSION['user_nom'] ?? '', 0, 1)) ?>
        </div>
    <?php endif; ?>
    Bonjour, <strong><?= htmlspecialchars($_SESSION['user_pseudo']) ?></strong>
</span>
    <a href="profil.php" class="btn btn-outline-primary me-2">
        <i class="fas fa-user"></i> Mon profil
    </a>
    <a href="deconnexion.php" class="btn btn-primary">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
    </a>
<?php else: ?>
                        <!-- Utilisateur non connecté -->
                    <a href="connexion.php" class="btn btn-outline-primary me-2">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                    <a href="inscription.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Inscription
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>