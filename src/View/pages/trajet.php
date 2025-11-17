<!-- Page détails du trajet -->
<div class="container py-5">
    <!-- Fil d'Ariane -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item"><a href="recherche.php">Recherche</a></li>
            <li class="breadcrumb-item active">Détails du trajet</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Carte trajet -->
            <div class="card shadow-sm mb-4 trajet-detail-card">
                <div class="card-body">
                    <h2 class="mb-4">
                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                        <?= htmlspecialchars($trajet['lieu_depart']) ?>
                        <i class="fas fa-arrow-right mx-3" style="color: var(--color-green-logo);"></i>
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        <?= htmlspecialchars($trajet['lieu_arrivee']) ?>
                    </h2>

                    <!-- Informations principales -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-calendar me-2" style="color: var(--color-green-logo);"></i>Date et horaires</h5>
                            <p class="mb-2">
                                <strong>Départ :</strong> 
                                <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?> 
                                à <?= date('H:i', strtotime($trajet['heure_depart'])) ?>
                            </p>
                            <p class="mb-0">
                                <strong>Arrivée estimée :</strong> 
                                <?= date('H:i', strtotime($trajet['heure_arrivee'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-car me-2" style="color: var(--color-green-logo);"></i>Véhicule</h5>
                            <p class="mb-2">
                                <strong>Marque :</strong> 
                                <?= htmlspecialchars($trajet['nom_marque']) ?> 
                                <?= htmlspecialchars($trajet['modele']) ?>
                            </p>
                            <p class="mb-2">
                                <strong>Couleur :</strong> <?= htmlspecialchars($trajet['couleur']) ?>
                            </p>
                            <p class="mb-0">
    <strong>Énergie :</strong> 
    <span class="badge bg-success"><?= ucfirst($trajet['energie']) ?></span>
    <?php if (in_array($trajet['energie'], ['electrique', 'hybride'])): ?>
        <span class="badge bg-success ms-2">
            <i class="fas fa-leaf me-1"></i>Écologique
        </span>
    <?php endif; ?>
</p>
                        </div>
                    </div>

                    <!-- Distance -->
                    <?php if ($trajet['distance_km']): ?>
                        <div class="mb-4">
                            <h5><i class="fas fa-road me-2" style="color: var(--color-green-logo);"></i>Distance</h5>
                            <p class="mb-0"><?= $trajet['distance_km'] ?> km</p>
                        </div>
                    <?php endif; ?>

                    <!-- Préférences -->
                    <?php if (count($preferences) > 0): ?>
                        <div class="mb-4">
                            <h5><i class="fas fa-star me-2" style="color: var(--color-green-logo);"></i>Préférences du conducteur</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($preferences as $pref): ?>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas <?= $pref['icone'] ?> me-1"></i>
                                        <?= htmlspecialchars($pref['libelle']) ?>
                                        <?php if ($pref['preference_autre']): ?>
                                            : <?= htmlspecialchars($pref['preference_autre']) ?>
                                        <?php endif; ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="col-lg-4">
            <!-- Carte conducteur -->
            <div class="card shadow-sm mb-4 trajet-detail-card">
                <div class="card-body text-center">
                    <h5 class="mb-3">Conducteur</h5>
                    
                    <!-- Avatar -->
                    <?php if (!empty($trajet['photo_profil']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $trajet['photo_profil'])): ?>
                        <img src="uploads/avatars/<?= htmlspecialchars($trajet['photo_profil']) ?>" 
                             alt="Photo de profil" class="avatar-trajet-detail mb-3">
                    <?php else: ?>
                        <div class="avatar-trajet-detail-initials mb-3">
                            <?= strtoupper(substr($trajet['prenom'], 0, 1) . substr($trajet['nom'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>

                    <h4><?= htmlspecialchars($trajet['pseudo']) ?></h4>
                </div>
            </div>

            <!-- Carte réservation -->
            <div class="card shadow-sm trajet-detail-card">
                <div class="card-body">
                    <h3 class="text-center mb-3" style="color: var(--color-green-logo);">
                        <i class="fas fa-coins me-2"></i>
                        <?= $trajet['prix_credit'] ?> crédits
                    </h3>
                    
                    <p class="text-center mb-3">
                        <i class="fas fa-users me-2"></i>
                        <strong><?= $trajet['places_restantes'] ?></strong> 
                        place<?= $trajet['places_restantes'] > 1 ? 's' : '' ?> disponible<?= $trajet['places_restantes'] > 1 ? 's' : '' ?>
                    </p>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($trajet['places_restantes'] > 0): ?>
                            <?php if ($trajet['id_utilisateur'] != $_SESSION['user_id']): ?>
                                <form method="POST" action="reserver.php">
                                    <input type="hidden" name="id_trajet" value="<?= $trajet['id_covoiturage'] ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nombre de places</label>
                                        <select class="form-select" name="nb_places" required>
                                            <?php for ($i = 1; $i <= min(3, $trajet['places_restantes']); $i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?> place<?= $i > 1 ? 's' : '' ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-check-circle me-2"></i>Réserver ce trajet
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    C'est votre trajet
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Complet
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="connexion.php" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Connexion
                        </a>
                        <a href="inscription.php" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Inscription
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>