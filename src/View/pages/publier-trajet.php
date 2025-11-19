<!-- Page publier un trajet -->
<div class="container py-5">
    <h2 class="text-center mb-2">
        <i class="fas fa-plus-circle me-2" style="color: var(--color-green-logo);"></i>
        Publier un trajet
    </h2>
    <p class="text-center text-muted subtitle-inscription mb-5">
        Proposez un covoiturage et gagnez des crédits
    </p>

    <!-- Affichage des erreurs -->
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Erreurs :</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="publier-trajet.php">
                        
                        <!-- Sélection du véhicule -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-car me-2"></i>
                                Véhicule <span class="text-danger">*</span>
                            </label>
                            
                            <?php if (count($vehicules) > 0): ?>
                                <select class="form-select" name="id_voiture" required>
                                    <option value="">Sélectionnez un véhicule</option>
                                    <?php foreach ($vehicules as $vehicule): ?>
                                        <option value="<?= $vehicule['id_voiture'] ?>">
                                            <?= htmlspecialchars($vehicule['nom_marque']) ?> 
                                            <?= htmlspecialchars($vehicule['modele']) ?> 
                                            - <?= htmlspecialchars($vehicule['couleur']) ?>
                                            (<?= $vehicule['nb_places'] ?> places)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Vous devez d'abord ajouter un véhicule.
                                    <a href="mes-vehicules.php" class="alert-link">Ajouter un véhicule</a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Trajet -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    Lieu de départ <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       name="lieu_depart" 
                                       placeholder="Ex: Paris" 
                                       value="<?= htmlspecialchars($_POST['lieu_depart'] ?? '') ?>"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    Lieu d'arrivée <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       name="lieu_arrivee" 
                                       placeholder="Ex: Lyon" 
                                       value="<?= htmlspecialchars($_POST['lieu_arrivee'] ?? '') ?>"
                                       required>
                            </div>
                        </div>

                        <!-- Date et heure -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-calendar me-2"></i>
                                    Date de départ <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       name="date_depart" 
                                       min="<?= date('Y-m-d') ?>"
                                       value="<?= htmlspecialchars($_POST['date_depart'] ?? '') ?>"
                                       required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-clock me-2"></i>
                                    Heure de départ <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control" 
                                       name="heure_depart" 
                                       value="<?= htmlspecialchars($_POST['heure_depart'] ?? '') ?>"
                                       required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-clock me-2"></i>
                                    Heure d'arrivée estimée
                                </label>
                                <input type="time" 
                                       class="form-control" 
                                       name="heure_arrivee" 
                                       value="<?= htmlspecialchars($_POST['heure_arrivee'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Nombre de places et prix -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-users me-2"></i>
                                    Places disponibles <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       name="nb_place" 
                                       min="1" 
                                       max="8" 
                                       value="<?= htmlspecialchars($_POST['nb_place'] ?? '3') ?>"
                                       required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-coins me-2"></i>
                                    Prix par place (crédits) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       name="prix_credit" 
                                       min="1" 
                                       max="100"
                                       value="<?= htmlspecialchars($_POST['prix_credit'] ?? '') ?>"
                                       required>
                                <small class="text-muted">Prix recommandé : 5-20 crédits</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-road me-2"></i>
                                    Distance (km)
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       name="distance_km" 
                                       min="0"
                                       value="<?= htmlspecialchars($_POST['distance_km'] ?? '') ?>"
                                       placeholder="Ex: 465">
                            </div>
                        </div>

                        <!-- Préférences -->
<div class="mb-4">
    <label class="form-label">
        <i class="fas fa-star me-2"></i>
        Vos préférences
    </label>
    <div class="row">
        <?php foreach ($types_preferences as $pref): ?>
            <div class="col-md-6 mb-2">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="preferences[]" 
                           value="<?= $pref['id_type_preference'] ?>"
                           id="pref<?= $pref['id_type_preference'] ?>">
                    <label class="form-check-label" for="pref<?= $pref['id_type_preference'] ?>">
                        <i class="fas <?= $pref['icone'] ?> me-2"></i>
                        <?= htmlspecialchars($pref['libelle']) ?>
                    </label>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <small class="text-muted">
        <i class="fas fa-info-circle me-1"></i>
        Ces préférences seront visibles par les passagers avant réservation
    </small>
</div>

                        <!-- Info importante -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>À savoir :</strong>
                            <ul class="mb-0 mt-2">
                                <li>Vous gagnerez des crédits pour chaque place réservée</li>
                                <li>Vous pouvez annuler le trajet à tout moment</li>
                                <li>En cas d'annulation, les passagers seront remboursés intégralement</li>
                            </ul>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="mes-trajets.php" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" <?= count($vehicules) === 0 ? 'disabled' : '' ?>>
                                <i class="fas fa-check-circle me-2"></i>Publier le trajet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>