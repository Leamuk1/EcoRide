<!-- Page de profil utilisateur améliorée -->
<div class="container py-5">
    <!-- En-tête profil -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Avatar -->
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <div class="avatar-container">
                                <?php if (!empty($user['photo_profil']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $user['photo_profil'])): ?>
                                    <div class="avatar-circle">
                                        <img src="uploads/avatars/<?= htmlspecialchars($user['photo_profil']) ?>" alt="Photo de profil">
                                    </div>
                                <?php else: ?>
                                    <div class="avatar-circle">
                                        <?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Upload photo -->
                                <form method="POST" action="profil.php" enctype="multipart/form-data" id="avatar-form">
                                    <div class="avatar-upload-btn">
                                        <label for="photo_profil">
                                            <i class="fas fa-camera me-1"></i>
                                            <?= !empty($user['photo_profil']) ? 'Changer la photo' : 'Ajouter une photo' ?>
                                        </label>
                                        <input type="file" name="photo_profil" id="photo_profil" accept="image/jpeg,image/png,image/jpg" onchange="this.form.submit()">
                                        <input type="hidden" name="upload_photo" value="1">
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Informations principales -->
                        <div class="col-md-6">
                            <h2 class="mb-2">
                                <?= htmlspecialchars($user['prenom']) ?> 
                                <?= htmlspecialchars($user['nom']) ?>
                            </h2>
                            <p class="text-muted mb-2">
                                <i class="fas fa-user me-2"></i>
                                @<?= htmlspecialchars($user['pseudo']) ?>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-envelope me-2"></i>
                                <?= htmlspecialchars($user['email']) ?>
                            </p>
                            <?php if (!empty($user['telephone'])): ?>
                                <p class="mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    <?= htmlspecialchars($user['telephone']) ?>
                                </p>
                            <?php endif; ?>
                            <p class="mb-0">
                                <i class="fas fa-calendar me-2"></i>
                                <?php if (isset($user['date_inscription']) && !empty($user['date_inscription'])): ?>
    Membre depuis <?= date('F Y', strtotime($user['date_inscription'])) ?>
<?php else: ?>
    Membre depuis récemment
<?php endif; ?>
                            </p>
                        </div>
                        
                        <!-- Actions -->
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <h3 class="mb-0" style="color: var(--color-green-logo);">
                                    <i class="fas fa-coins me-2"></i>
                                    <?= $user['credits'] ?>
                                </h3>
                                <small class="text-muted">crédits</small>
                            </div>
                            
                            <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalModifierProfil">
                                <i class="fas fa-edit me-2"></i>Modifier le profil
                            </button>
                            <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#modalChangerMdp">
                                <i class="fas fa-key me-2"></i>Changer le mot de passe
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <?php if (isset($_SESSION['success_profil'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['success_profil']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_profil']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_profil'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($_SESSION['error_profil']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_profil']); ?>
    <?php endif; ?>

    <!-- Statistiques -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-3">
            <i class="fas fa-chart-bar me-2" style="color: var(--color-green-logo);"></i>
            Mes statistiques
        </h4>
    </div>
    
    <!-- Trajets publiés -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-car fa-3x mb-3" style="color: var(--color-green-logo);"></i>
                <h3 class="mb-0"><?= $stats_trajets_publies ?></h3>
                <p class="text-muted mb-0">Trajet<?= $stats_trajets_publies > 1 ? 's' : '' ?> publié<?= $stats_trajets_publies > 1 ? 's' : '' ?></p>
            </div>
        </div>
    </div>
    
    <!-- Trajets réservés -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-ticket-alt fa-3x mb-3" style="color: var(--color-green-logo);"></i>
                <h3 class="mb-0"><?= $stats_trajets_reserves ?></h3>
                <p class="text-muted mb-0">Trajet<?= $stats_trajets_reserves > 1 ? 's' : '' ?> réservé<?= $stats_trajets_reserves > 1 ? 's' : '' ?></p>
            </div>
        </div>
    </div>
    
    <!-- Kilomètres -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-road fa-3x mb-3" style="color: var(--color-green-logo);"></i>
                <h3 class="mb-0"><?= number_format($stats_km_parcourus, 0, ',', ' ') ?></h3>
                <p class="text-muted mb-0">Km parcourus</p>
            </div>
        </div>
    </div>
    
    <!-- CO2 économisé -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm h-100" style="border-left: 4px solid #28a745;">
            <div class="card-body text-center">
                <i class="fas fa-leaf fa-3x mb-3 text-success"></i>
                <h3 class="mb-0 text-success"><?= number_format($stats_co2_economise, 0, ',', ' ') ?></h3>
                <p class="text-muted mb-0">kg CO₂ économisé</p>
            </div>
        </div>
    </div>
    
    <!-- Crédits gagnés -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm h-100" style="border-left: 4px solid var(--color-green-logo);">
            <div class="card-body text-center">
                <i class="fas fa-arrow-up fa-3x mb-3" style="color: var(--color-green-logo);"></i>
                <h3 class="mb-0" style="color: var(--color-green-logo);">+<?= $stats_credits_gagnes ?></h3>
                <p class="text-muted mb-0">Crédits gagnés</p>
            </div>
        </div>
    </div>
    
    <!-- Crédits dépensés -->
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card shadow-sm h-100" style="border-left: 4px solid #dc3545;">
            <div class="card-body text-center">
                <i class="fas fa-arrow-down fa-3x mb-3 text-danger"></i>
                <h3 class="mb-0 text-danger">-<?= $stats_credits_depenses ?></h3>
                <p class="text-muted mb-0">Crédits dépensés</p>
            </div>
        </div>
    </div>
</div>

    <!-- Raccourcis -->
    <div class="row">
        <div class="col-12">
            <h4 class="mb-3">
                <i class="fas fa-bolt me-2" style="color: var(--color-green-logo);"></i>
                Accès rapides
            </h4>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="recherche.php" class="card shadow-sm text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-search fa-2x mb-2" style="color: var(--color-green-logo);"></i>
                    <p class="mb-0">Rechercher un trajet</p>
                </div>
            </a>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="publier-trajet.php" class="card shadow-sm text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-plus-circle fa-2x mb-2" style="color: var(--color-green-logo);"></i>
                    <p class="mb-0">Publier un trajet</p>
                </div>
            </a>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="mes-reservations.php" class="card shadow-sm text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-ticket-alt fa-2x mb-2" style="color: var(--color-green-logo);"></i>
                    <p class="mb-0">Mes réservations</p>
                </div>
            </a>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="mes-trajets.php" class="card shadow-sm text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-car fa-2x mb-2" style="color: var(--color-green-logo);"></i>
                    <p class="mb-0">Mes trajets</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Modal Modifier profil -->
<div class="modal fade" id="modalModifierProfil" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Modifier mon profil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="profil.php">
                <div class="modal-body">
                    <div class="row">
                        <!-- Pseudo (lecture seule) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pseudo</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['pseudo']) ?>" readonly>
                            <small class="text-muted">Le pseudo ne peut pas être modifié</small>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>

                        <!-- Nom -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                        </div>

                        <!-- Prénom -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" placeholder="06 12 34 56 78">
                        </div>

                        <!-- Date de naissance -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date_naissance" value="<?= htmlspecialchars($user['date_naissance']) ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Changer mot de passe -->
<div class="modal fade" id="modalChangerMdp" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key me-2"></i>Changer le mot de passe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="profil.php">
                <input type="hidden" name="change_password" value="1">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password" minlength="8" required>
                        <small class="text-muted">Minimum 8 caractères</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password_confirm" minlength="8" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Changer le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>