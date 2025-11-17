<!-- Page de recherche de trajets -->
<div class="container py-5">
    <h2 class="text-center mb-2">
        <i class="fas fa-search me-2" style="color: var(--color-green-logo);"></i>
        Recherche de trajets
    </h2>
    <p class="text-center text-muted subtitle-inscription mb-5">
        Trouvez le covoiturage qui vous convient
    </p>

    <!-- Formulaire de recherche -->
    <div class="card shadow mb-5">
        <div class="card-body p-4">
            <h5 class="mb-3">Affiner votre recherche</h5>
            <form method="GET" action="recherche.php">
                <div class="row">
                    <!-- Ville de départ -->
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white">
                                <i class="fa-solid fa-record-vinyl"></i>
                            </span>
                            <input type="text" class="form-control" name="depart" 
                                   placeholder="Ville de départ" 
                                   value="<?= htmlspecialchars($ville_depart) ?>" required>
                        </div>
                    </div>
                    
                    <!-- Ville d'arrivée -->
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" class="form-control" name="arrivee" 
                                   placeholder="Ville d'arrivée" 
                                   value="<?= htmlspecialchars($ville_arrivee) ?>" required>
                        </div>
                    </div>
                    
                    <!-- Date -->
                    <div class="col-md-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="date" 
                                   value="<?= htmlspecialchars($date_trajet) ?>" required>
                        </div>
                    </div>
                    
                    <!-- Passagers -->
                    <div class="col-md-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="number" class="form-control" name="passagers" 
                                   min="1" max="8" value="<?= $nb_passagers ?>" required>
                        </div>
                    </div>
                    
                    <!-- Bouton recherche -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>

                <!-- Filtres avancés (repliables) -->
                <div class="accordion mb-3" id="filtresAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#filtresContent">
                                <i class="fas fa-filter me-2"></i>Filtres avancés
                            </button>
                        </h2>
                        <div id="filtresContent" class="accordion-collapse collapse" 
                             data-bs-parent="#filtresAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <!-- Filtre prix max -->
                                    <div class="col-md-3">
                                        <label class="form-label">Prix maximum (crédits)</label>
                                        <input type="number" class="form-control" name="prix_max" 
                                               placeholder="Ex: 50" 
                                               value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>">
                                    </div>
                                    
                                    <!-- Filtre distance max -->
                                    <div class="col-md-3">
                                        <label class="form-label">Distance maximum (km)</label>
                                        <input type="number" class="form-control" name="distance_max" 
                                               placeholder="Ex: 500" 
                                               value="<?= htmlspecialchars($_GET['distance_max'] ?? '') ?>">
                                    </div>
                                    
                                    <!-- Filtre véhicules écologiques -->
                                    <div class="col-md-3">
                                        <label class="form-label">Type de véhicule</label>
                                        <select class="form-select" name="ecologique">
                                            <option value="">Tous les véhicules</option>
                                            <option value="1" <?= ($_GET['ecologique'] ?? '') == '1' ? 'selected' : '' ?>>
                                                🍃 Écologiques uniquement
                                            </option>
                                        </select>
                                    </div>

                                     <!-- Filtre préférences -->
        <div class="col-md-3 mb-3">
            <label class="form-label">Préférences</label>
            <select class="form-select" name="preference">
                <option value="">Toutes préférences</option>
                <option value="2" <?= ($_GET['preference'] ?? '') == '2' ? 'selected' : '' ?>>
                    🐾 Animaux acceptés
                </option>
                <option value="7" <?= ($_GET['preference'] ?? '') == '7' ? 'selected' : '' ?>>
                    ❄️ Climatisation
                </option>
                <option value="1" <?= ($_GET['preference'] ?? '') == '1' ? 'selected' : '' ?>>
                    🚬 Fumeur accepté
                </option>
                <option value="4" <?= ($_GET['preference'] ?? '') == '4' ? 'selected' : '' ?>>
                    🎵 Musique
                </option>
                <option value="6" <?= ($_GET['preference'] ?? '') == '6' ? 'selected' : '' ?>>
                    🔇 Silence préféré
                </option>
            </select>
        </div>
                                    
                                    <!-- Bouton réinitialiser -->
                                    <div class="col-md-3 d-flex align-items-end">
                                        <a href="recherche.php" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-redo me-2"></i>Réinitialiser
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    <?php if (!empty($ville_depart) && !empty($ville_arrivee) && !empty($date_trajet)): ?>
        
        <h4 class="mb-4">
            <?= count($trajets) ?> trajet<?= count($trajets) > 1 ? 's' : '' ?> trouvé<?= count($trajets) > 1 ? 's' : '' ?>
            <?php if (count($trajets) > 0): ?>
                <small class="text-muted">
                    (<?= htmlspecialchars($ville_depart) ?> → <?= htmlspecialchars($ville_arrivee) ?>)
                </small>
            <?php endif; ?>
        </h4>

        <?php if (count($trajets) > 0): ?>
            <!-- Liste des trajets -->
            <div class="row">
                <?php foreach ($trajets as $trajet): ?>
                    <div class="col-md-12 mb-4">
                        <div class="card trajet-card shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- Avatar conducteur -->
                                    <div class="col-md-1 text-center">
                                        <?php if (!empty($trajet['photo_profil']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $trajet['photo_profil'])): ?>
                                            <img src="uploads/avatars/<?= htmlspecialchars($trajet['photo_profil']) ?>" 
                                                 alt="Photo de profil" class="avatar-trajet">
                                        <?php else: ?>
                                            <div class="avatar-trajet-initials">
                                                <?= strtoupper(substr($trajet['prenom'], 0, 1) . substr($trajet['nom'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <small class="d-block mt-2"><?= htmlspecialchars($trajet['pseudo']) ?></small>
                                    </div>

                                    <!-- Infos trajet -->
                                    <div class="col-md-7">
                                        <h5 class="mb-2">
                                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                                            <?= htmlspecialchars($trajet['lieu_depart']) ?>
                                            <i class="fas fa-arrow-right mx-2" style="color: var(--color-green-logo);"></i>
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                            <?= htmlspecialchars($trajet['lieu_arrivee']) ?>
                                        </h5>
                                        <p class="mb-1">
                                            <i class="fas fa-calendar me-2"></i>
                                            <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?>
                                            à <?= date('H:i', strtotime($trajet['heure_depart'])) ?>
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-car me-2"></i>
                                            <?= htmlspecialchars($trajet['nom_marque']) ?> 
                                            <?= htmlspecialchars($trajet['modele']) ?>
                                            <?php if (in_array($trajet['energie'], ['electrique', 'hybride'])): ?>
                                                <span class="badge bg-success ms-2">
                                                    <i class="fas fa-leaf me-1"></i>Écologique
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-users me-2"></i>
                                            <?= $trajet['places_restantes'] ?> place<?= $trajet['places_restantes'] > 1 ? 's' : '' ?> disponible<?= $trajet['places_restantes'] > 1 ? 's' : '' ?>
                                        </p>
                                    </div>

                                    <!-- Prix et action -->
                                    <div class="col-md-4 text-end">
                                        <h3 class="mb-3" style="color: var(--color-green-logo);">
                                            <i class="fas fa-coins me-2"></i>
                                            <?= $trajet['prix_credit'] ?> crédits
                                        </h3>
                                        <a href="trajet.php?id=<?= $trajet['id_covoiturage'] ?>" 
                                           class="btn btn-primary">
                                            <i class="fas fa-info-circle me-2"></i>Voir les détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Aucun résultat exact -->
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                Aucun trajet trouvé pour cette recherche exacte.
            </div>
            
            <!-- Suggestions de trajets similaires -->
            <?php if (isset($suggestions) && count($suggestions) > 0): ?>
                <div class="mt-4">
                    <h4 class="mb-3">
                        <i class="fas fa-lightbulb me-2" style="color: var(--color-green-logo);"></i>
                        Trajets similaires qui pourraient vous intéresser
                    </h4>
                    <p class="text-muted mb-4">
                        Nous n'avons pas trouvé de trajet exact, mais voici des alternatives proches de votre recherche :
                    </p>
                    
                    <!-- Liste des suggestions -->
                    <div class="row">
                        <?php foreach ($suggestions as $trajet): ?>
                            <div class="col-md-12 mb-4">
                                <div class="card trajet-card shadow-sm suggestion-card">
                                    <!-- Badge suggestion -->
<div class="suggestion-badge">
    <?php if (stripos($trajet['lieu_depart'], $ville_depart) !== false && stripos($trajet['lieu_arrivee'], $ville_arrivee) !== false): ?>
        <!-- Même trajet, date différente : pas de badge -->
    <?php elseif (stripos($trajet['lieu_depart'], $ville_depart) !== false): ?>
        <span class="badge bg-success">
            <i class="fas fa-map-marker-alt me-1"></i>
            Même départ
        </span>
    <?php elseif (stripos($trajet['lieu_arrivee'], $ville_arrivee) !== false): ?>
        <span class="badge bg-primary">
            <i class="fas fa-flag-checkered me-1"></i>
            Même destination
        </span>
    <?php endif; ?>
</div>
                                    
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <!-- Avatar conducteur -->
                                            <div class="col-md-1 text-center">
                                                <?php if (!empty($trajet['photo_profil']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $trajet['photo_profil'])): ?>
                                                    <img src="uploads/avatars/<?= htmlspecialchars($trajet['photo_profil']) ?>" 
                                                         alt="Photo de profil" class="avatar-trajet">
                                                <?php else: ?>
                                                    <div class="avatar-trajet-initials">
                                                        <?= strtoupper(substr($trajet['prenom'], 0, 1) . substr($trajet['nom'], 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                                <small class="d-block mt-2"><?= htmlspecialchars($trajet['pseudo']) ?></small>
                                            </div>

                                            <!-- Infos trajet -->
                                            <div class="col-md-7">
                                                <h5 class="mb-2">
                                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                                    <?= htmlspecialchars($trajet['lieu_depart']) ?>
                                                    <i class="fas fa-arrow-right mx-2" style="color: var(--color-green-logo);"></i>
                                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                                    <?= htmlspecialchars($trajet['lieu_arrivee']) ?>
                                                </h5>
                                                <p class="mb-1">
                                                    <i class="fas fa-calendar me-2"></i>
                                                    <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?>
                                                    à <?= date('H:i', strtotime($trajet['heure_depart'])) ?>
                                                </p>
                                                <p class="mb-1">
                                                    <i class="fas fa-car me-2"></i>
                                                    <?= htmlspecialchars($trajet['nom_marque']) ?> 
                                                    <?= htmlspecialchars($trajet['modele']) ?>
                                                    <?php if (in_array($trajet['energie'], ['electrique', 'hybride'])): ?>
                                                        <span class="badge bg-success ms-2">
                                                            <i class="fas fa-leaf me-1"></i>Écologique
                                                        </span>
                                                    <?php endif; ?>
                                                </p>
                                                <p class="mb-0">
                                                    <i class="fas fa-users me-2"></i>
                                                    <?= $trajet['places_restantes'] ?> place<?= $trajet['places_restantes'] > 1 ? 's' : '' ?> disponible<?= $trajet['places_restantes'] > 1 ? 's' : '' ?>
                                                </p>
                                            </div>

                                            <!-- Prix et action -->
                                            <div class="col-md-4 text-end">
                                                <h3 class="mb-3" style="color: var(--color-green-logo);">
                                                    <i class="fas fa-coins me-2"></i>
                                                    <?= $trajet['prix_credit'] ?> crédits
                                                </h3>
                                                <a href="trajet.php?id=<?= $trajet['id_covoiturage'] ?>" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-info-circle me-2"></i>Voir les détails
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Vraiment aucun trajet -->
                <div class="alert alert-secondary text-center mt-4">
                    <i class="fas fa-sad-tear fa-2x mb-3 d-block"></i>
                    <p class="mb-0">Aucun trajet similaire trouvé. Essayez avec d'autres critères ou d'autres dates.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    <?php else: ?>
        <!-- Invite à rechercher -->
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x mb-3" style="color: var(--color-green-logo);"></i>
            <p class="lead">Utilisez le formulaire ci-dessus pour rechercher un trajet</p>
        </div>
    <?php endif; ?>
</div>