<!-- Page détails trajet conducteur -->
<div class="container py-5">
    <!-- Fil d'Ariane -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
            <li class="breadcrumb-item"><a href="mes-trajets.php">Mes trajets</a></li>
            <li class="breadcrumb-item active">Détails du trajet</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Carte trajet -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: var(--color-primary); color: white;">
                    <h4 class="mb-0">
                        <i class="fas fa-route me-2"></i>
                        Détails du trajet
                    </h4>
                </div>
                <div class="card-body">
                    <h3 class="mb-4">
                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                        <?= htmlspecialchars($trajet['lieu_depart']) ?>
                        <i class="fas fa-arrow-right mx-3" style="color: var(--color-green-logo);"></i>
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        <?= htmlspecialchars($trajet['lieu_arrivee']) ?>
                    </h3>

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
                            <p class="mb-0">
                                <strong>Couleur :</strong> <?= htmlspecialchars($trajet['couleur']) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Distance et prix -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><i class="fas fa-road me-2" style="color: var(--color-green-logo);"></i>Distance</h5>
                            <p class="mb-0"><?= $trajet['distance_km'] ?> km</p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-coins me-2" style="color: var(--color-green-logo);"></i>Prix</h5>
                            <p class="mb-0"><?= $trajet['prix_credit'] ?> crédits par place</p>
                        </div>
                    </div>

                    <!-- Préférences -->
                    <?php if (count($preferences) > 0): ?>
                        <div class="mb-0">
                            <h5><i class="fas fa-star me-2" style="color: var(--color-green-logo);"></i>Vos préférences</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($preferences as $pref): ?>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas <?= $pref['icone'] ?> me-1"></i>
                                        <?= htmlspecialchars($pref['libelle']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Liste des passagers -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: var(--color-primary); color: white;">
                    <h4 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Passagers (<?= count($passagers) ?>)
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (count($passagers) > 0): ?>
                        <?php foreach ($passagers as $passager): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Avatar -->
                                        <div class="col-md-2 text-center">
                                            <?php if (!empty($passager['photo_profil']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $passager['photo_profil'])): ?>
                                                <img src="uploads/avatars/<?= htmlspecialchars($passager['photo_profil']) ?>" 
                                                     alt="Photo" class="avatar-trajet">
                                            <?php else: ?>
                                                <div class="avatar-trajet-initials mx-auto">
                                                    <?= strtoupper(substr($passager['prenom'], 0, 1) . substr($passager['nom'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Infos passager -->
                                        <div class="col-md-6">
                                            <h5 class="mb-2"><?= htmlspecialchars($passager['pseudo']) ?></h5>
                                            <p class="mb-1">
                                                <i class="fas fa-user me-2"></i>
                                                <?= htmlspecialchars($passager['prenom']) ?> 
                                                <?= htmlspecialchars($passager['nom']) ?>
                                            </p>
                                            <p class="mb-1">
                                                <i class="fas fa-envelope me-2"></i>
                                                <a href="mailto:<?= htmlspecialchars($passager['email']) ?>">
                                                    <?= htmlspecialchars($passager['email']) ?>
                                                </a>
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa-calendar me-2"></i>
                                                Réservé le <?= date('d/m/Y à H:i', strtotime($passager['date_confirmation'])) ?>
                                            </p>
                                        </div>

                                        <!-- Places et prix -->
                                        <div class="col-md-4 text-end">
                                            <div class="mb-2">
                                                <span class="badge bg-primary" style="font-size: 1rem;">
                                                    <i class="fas fa-user me-1"></i>
                                                    <?= $passager['nb_places_reservees'] ?> place<?= $passager['nb_places_reservees'] > 1 ? 's' : '' ?>
                                                </span>
                                            </div>
                                            <p class="mb-0" style="color: var(--color-green-logo); font-size: 1.2rem; font-weight: bold;">
                                                <i class="fas fa-coins me-1"></i>
                                                <?= ($trajet['prix_credit'] * $passager['nb_places_reservees']) ?> crédits
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-secondary text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucun passager pour le moment.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: var(--color-primary); color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistiques
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <small class="text-muted d-block">Réservations</small>
                        <h2 class="mb-0" style="color: var(--color-green-logo);">
                            <?= $trajet['nb_reservations'] ?>
                        </h2>
                    </div>
                    <div class="mb-4">
                        <small class="text-muted d-block">Places</small>
                        <h3 class="mb-0">
                            <span class="badge bg-secondary" style="font-size: 1.5rem;">
                                <?= $trajet['places_reservees'] ?> / <?= $trajet['nb_place'] ?>
                            </span>
                        </h3>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Gains totaux</small>
                        <h3 class="mb-0" style="color: var(--color-green-logo);">
                            <i class="fas fa-coins me-2"></i>
                            <?= $gains_total ?> crédits
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <a href="mes-trajets.php" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-arrow-left me-2"></i>Retour à mes trajets
                    </a>
                    
                    <?php if ($trajet['statut'] == 'en_attente'): ?>
                        <?php if ($trajet['nb_reservations'] > 0): ?>
                            <button type="button" class="btn btn-danger w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalAnnulation">
                                <i class="fas fa-times-circle me-2"></i>Annuler le trajet
                            </button>
                        <?php else: ?>
                            <form method="POST" action="supprimer-trajet.php">
                                <input type="hidden" name="id_trajet" value="<?= $trajet['id_covoiturage'] ?>">
                                <button type="submit" class="btn btn-danger w-100" 
                                        onclick="return confirm('Supprimer ce trajet ?')">
                                    <i class="fas fa-trash me-2"></i>Supprimer le trajet
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
<?php if ($trajet['statut'] == 'en_attente' && $trajet['nb_reservations'] > 0): ?>
    <div class="modal fade" id="modalAnnulation" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer l'annulation du trajet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention !</strong> Ce trajet a <?= $trajet['nb_reservations'] ?> réservation<?= $trajet['nb_reservations'] > 1 ? 's' : '' ?>.
                    </div>
                    
                    <h6 class="mb-3">En annulant ce trajet :</h6>
                    
                    <div class="d-flex flex-column align-items-center gap-2 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span>Tous les passagers seront <strong>remboursés intégralement</strong></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-coins text-warning me-2"></i>
                            <span>Vous perdrez <strong><?= $gains_total ?> crédits</strong></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <span>Le trajet sera marqué comme <strong>annulé</strong></span>
                        </div>
                    </div>
                    
                    <p class="text-danger mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Cette action est irréversible.</strong>
                    </p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Non, conserver
                    </button>
                    <form method="POST" action="annuler-trajet.php" class="d-inline">
                        <input type="hidden" name="id_trajet" value="<?= $trajet['id_covoiturage'] ?>">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-check me-2"></i>Oui, annuler le trajet
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>