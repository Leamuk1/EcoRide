<!-- Formulaire de connexion -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-2">Connexion</h2>
                    <p class="text-center text-muted subtitle-inscription mb-4">
                        <i class="fas fa-sign-in-alt" style="color: var(--color-green-logo);"></i>
                        Accédez à votre espace EcoRide
                    </p>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="connexion.php">
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
                        
                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label class="form-label">Mot de passe *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        
                        <!-- Bouton -->
                        <button type="submit" class="btn btn-primary w-100 mt-3">Se connecter</button>
                    </form>
                    
                    <p class="text-center mt-4 mb-0">
                        Pas encore inscrit ? <a href="inscription.php">Créer un compte</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>