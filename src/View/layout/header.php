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

  <?php if (isset($_SESSION['user_id'])): ?>
  
    
    <!-- Menu déroulant Profil -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" 
           href="#" 
           id="navbarDropdownProfil" 
           role="button" 
           data-bs-toggle="dropdown" 
           aria-expanded="false">

           


            <!-- Avatar utilisateur -->
            <?php if (!empty($_SESSION['user_photo']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $_SESSION['user_photo'])): ?>
                <img src="uploads/avatars/<?= htmlspecialchars($_SESSION['user_photo']) ?>" 
                     alt="Photo de profil" 
                     class="rounded-circle me-2" 
                     style="width: 32px; height: 32px; object-fit: cover; border: 2px solid var(--color-green-logo);">
            <?php else: ?>
                 <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-2" 
                     style="width: 32px; height: 32px; background-color: var(--color-green-logo); color: white; font-size: 0.9rem; font-weight: bold;">
                    <?= strtoupper(substr($_SESSION['user_prenom'], 0, 1) . substr($_SESSION['user_nom'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            <span><?= htmlspecialchars($_SESSION['user_prenom']) ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownProfil">
            <li>
                <a class="dropdown-item" href="profil.php">
                    <i class="fas fa-user me-2"></i>Mon profil
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="messages.php">
                    <i class="fas fa-envelope me-2"></i>Messages
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="mes-trajets.php">
                    <i class="fas fa-route me-2"></i>Mes trajets
                </a>
            </li>
            <li>
    <a class="dropdown-item" href="mes-vehicules.php">
        <i class="fas fa-car me-2"></i>Mes véhicules
    </a>
</li>
            <li>
                <a class="dropdown-item" href="mes-reservations.php">
                    <i class="fas fa-ticket-alt me-2"></i>Mes réservations
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="paiement-remboursement.php">
                    <i class="fas fa-wallet me-2"></i>Paiement & Remboursement
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="parametres.php">
                    <i class="fas fa-cog me-2"></i>Paramètres
                </a>
            </li>
            <li>
                <a class="dropdown-item " href="deconnexion.php">
                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                </a>
            </li>
        </ul>
    </li>
    
      <!-- Notifications -->
    <li class="nav-item">
        <a class="nav-link" href="notifications.php">
            <i class="fas fa-bell"></i>
        </a>
    </li>

    <!-- Solde de crédits -->
    <li class="nav-item">
        <a class="nav-link" href="paiement-remboursement.php">
            <i class="fas fa-coins me-1" style="color: var(--color-green-logo);"></i>
            <strong><?= $_SESSION['user_credits'] ?? 0 ?></strong> crédits
        </a>
    </li>
<?php else: ?>
    <!-- Utilisateur non connecté -->
    <li class="nav-item">
        <a class="nav-link" href="connexion.php">
            <i class="fas fa-sign-in-alt me-1"></i>Connexion
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link btn btn-primary text-white" href="inscription.php">
            <i class="fas fa-user-plus me-1"></i>Inscription
        </a>
    </li>
<?php endif; ?>
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