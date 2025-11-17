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
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" class="form-control" name="date" 
                                   value="<?= htmlspecialchars($date_trajet) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="number" class="form-control" name="passagers" 
                                   min="1" max="8" value="<?= $nb_passagers ?>" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
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
            <!-- Aucun résultat -->
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Aucun trajet trouvé pour cette recherche. Essayez avec d'autres critères.
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Invite à rechercher -->
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x mb-3" style="color: var(--color-green-logo);"></i>
            <p class="lead">Utilisez le formulaire ci-dessus pour rechercher un trajet</p>
        </div>
    <?php endif; ?>
</div>