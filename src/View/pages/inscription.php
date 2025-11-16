<!-- Formulaire d'inscription -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-2">Inscription</h2>
<p class="text-center text-muted subtitle-inscription mb-4">
    <i class="fas fa-leaf" style="color: var(--color-green-logo);"></i>
    Rejoignez la communauté EcoRide
</p>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="inscription.php">
                        <!-- Nom -->
                        <div class="mb-3">
                            <label class="form-label">Nom *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" name="nom" required>
                            </div>
                        </div>
                        
                        <!-- Prénom -->
                        <div class="mb-3">
                            <label class="form-label">Prénom *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" name="prenom" required>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        
                        <!-- Date de naissance -->
                        <div class="mb-3">
                            <label class="form-label">Date de naissance *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input type="date" class="form-control" name="date_naissance" required>
                            </div>
                        </div>
                        
                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label class="form-label">Mot de passe *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" name="password" minlength="8" required>
                            </div>
                            <small class="text-muted">Minimum 8 caractères</small>
                        </div>
                        
                        <!-- Confirmation mot de passe -->
                        <div class="mb-3">
                            <label class="form-label">Confirmer le mot de passe *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" name="password_confirm" minlength="8" required>
                            </div>
                        </div>

                     <!-- Conditions d'utilisation -->
                        <div class="mb-3">
                             <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                     J'accepte les <a href="#" target="_blank">conditions d'utilisation</a> 
                                     et la <a href="#" target="_blank">politique de confidentialité</a> *
                                </label>
                             </div>
                        </div>

                     <!-- Information sur les crédits -->
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-coins me-2"></i>
                            <span>Vous recevrez automatiquement 20 crédits à l'inscription !</span>
                        </div>
                        
                        <!-- Bouton -->
                        <button type="submit" class="btn btn-primary w-100 mt-3">S'inscrire</button>
                    </form>
                    
                    <p class="text-center mt-4 mb-0">
                        Déjà inscrit ? <a href="connexion.php">Se connecter</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>