<!-- Page de profil utilisateur -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-2">Mon profil</h2>
                    <p class="text-center text-muted subtitle-inscription mb-4">
                        <i class="fas fa-user-circle" style="color: var(--color-green-logo);"></i>
                        Gérez vos informations personnelles
                    </p>

                    <?php if (isset($_SESSION['success_profil'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['success_profil']) ?>
                            <?php unset($_SESSION['success_profil']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error_profil'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['error_profil']) ?>
                            <?php unset($_SESSION['error_profil']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Avatar -->
                    <div class="avatar-container">
                        <div class="avatar-circle">
                            <?php if (!empty($user['photo_profil']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $user['photo_profil'])): ?>
                                <img src="uploads/avatars/<?= htmlspecialchars($user['photo_profil']) ?>" alt="Photo de profil">
                            <?php else: ?>
                                <?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?>
                            <?php endif; ?>
                        </div>
                        
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

                    <!-- Informations du compte -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fas fa-coins me-2"></i>
                        <span>Solde de crédits : <strong><?= htmlspecialchars($user['credits']) ?> crédits</strong></span>
                    </div>

                    <!-- Formulaire de modification -->
                    <form method="POST" action="profil.php">
                        <h5 class="mb-3"><i class="fas fa-info-circle me-2" style="color: var(--color-green-logo);"></i>Informations personnelles</h5>
                        
                        <!-- Pseudo (lecture seule) -->
                        <div class="mb-3">
                            <label class="form-label">Pseudo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-at"></i>
                                </span>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user['pseudo']) ?>" readonly>
                            </div>
                            <small class="text-muted">Le pseudo ne peut pas être modifié</small>
                        </div>

                        <!-- Nom -->
                        <div class="mb-3">
                            <label class="form-label">Nom *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                            </div>
                        </div>

                        <!-- Prénom -->
                        <div class="mb-3">
                            <label class="form-label">Prénom *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                        </div>

                        <!-- Date de naissance -->
                        <div class="mb-3">
                            <label class="form-label">Date de naissance *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input type="date" class="form-control" name="date_naissance" value="<?= htmlspecialchars($user['date_naissance']) ?>" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="fas fa-lock me-2" style="color: var(--color-green-logo);"></i>Modifier le mot de passe (optionnel)</h5>

                        <!-- Nouveau mot de passe -->
                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" name="new_password" minlength="8">
                            </div>
                            <small class="text-muted">Laissez vide si vous ne voulez pas changer de mot de passe</small>
                        </div>

                        <!-- Confirmation nouveau mot de passe -->
                        <div class="mb-3">
                            <label class="form-label">Confirmer le nouveau mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" name="new_password_confirm" minlength="8">
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>