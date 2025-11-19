<!-- Page Mes trajets (conducteur) -->
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="text-center">
            <h2 class="mb-2">
                <i class="fas fa-car me-2" style="color: var(--color-green-logo);"></i>
                Mes trajets
            </h2>
            <p class="text-muted subtitle-inscription mb-0">
                Gérez vos trajets publiés
            </p>
        </div>
        <a href="publier-trajet.php" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Publier un trajet
        </a>
    </div>

    <!-- Trajets à venir -->
    <div class="mb-5">
        <h4 class="mb-4">
            <i class="fas fa-calendar-check me-2" style="color: var(--color-green-logo);"></i>
            Trajets à venir (<?= count($trajets_avenir) ?>)
        </h4>

        <?php if (count($trajets_avenir) > 0): ?>
            <div class="row">
                <?php foreach ($trajets_avenir as $trajet): ?>
                    <?php
                    // Calculer le temps restant
                    $datetime_depart = new DateTime($trajet['datetime_depart']);
                    $now = new DateTime();
                    
                    if ($datetime_depart > $now) {
                        $interval = $now->diff($datetime_depart);
                        $jours_restants = $interval->days;
                        $heures_restantes = $interval->h;
                    } else {
                        $jours_restants = 0;
                        $heures_restantes = 0;
                    }
                    
                    // Calculer les gains
                    $gains_total = $trajet['prix_credit'] * $trajet['places_reservees'];
                    ?>
                    
                    <div class="col-md-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- Badge statut -->
<?php if ($trajet['statut'] == 'en_cours'): ?>
    <span class="badge bg-success mb-2">
        <i class="fas fa-spinner fa-spin me-1"></i>En cours
    </span>
<?php endif; ?>
                                    <!-- Infos trajet -->
                                    <div class="col-md-6">
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
                                            <i class="fas fa-coins me-2"></i>
                                            <?= $trajet['prix_credit'] ?> crédits/place
                                        </p>
                                    </div>

                                    <!-- Statistiques -->
                                    <div class="col-md-3 card-trajet-stats">
                                        <div class="text-center mb-2">
                                            <small class="text-muted d-block">Réservations</small>
                                            <h4 class="mb-0" style="color: var(--color-green-logo);">
                                                <?= $trajet['nb_reservations'] ?>
                                            </h4>
                                        </div>
                                        <div class="text-center mb-2">
                                            <small class="text-muted d-block">Places</small>
                                            <p class="mb-0">
                                                <span class="badge bg-secondary">
                                                    <?= $trajet['places_reservees'] ?> / <?= $trajet['nb_place'] ?>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">Gains</small>
                                            <h5 class="mb-0" style="color: var(--color-green-logo);">
                                                <?= $gains_total ?> crédits
                                            </h5>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-md-3 text-center">
                                        <?php if ($jours_restants > 0 || $heures_restantes > 0): ?>
                                            <div class="alert alert-info mb-3">
                                                <small>
                                                    <i class="fas fa-clock me-1"></i>
                                                    Dans <?= $jours_restants ?> jour<?= $jours_restants > 1 ? 's' : '' ?>
                                                    <?php if ($heures_restantes > 0): ?>
                                                        et <?= $heures_restantes ?>h
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <a href="trajet-conducteur.php?id=<?= $trajet['id_covoiturage'] ?>" 
                                           class="btn btn-outline-primary w-100 mb-2">
                                            <i class="fas fa-eye me-2"></i>Voir les passagers
                                        </a>
                                        
                                        <?php if ($trajet['nb_reservations'] > 0): ?>
                                            <button type="button" class="btn btn-outline-danger w-100" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalAnnulation<?= $trajet['id_covoiturage'] ?>">
                                                <i class="fas fa-times-circle me-2"></i>Annuler le trajet
                                            </button>
                                        <?php else: ?>
                                            <form method="POST" action="supprimer-trajet.php" class="d-inline w-100">
                                                <input type="hidden" name="id_trajet" value="<?= $trajet['id_covoiturage'] ?>">
                                                <button type="submit" class="btn btn-outline-danger w-100" 
                                                        onclick="return confirm('Supprimer ce trajet ?')">
                                                    <i class="fas fa-trash me-2"></i>Supprimer
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de confirmation d'annulation -->
                    <?php if ($trajet['nb_reservations'] > 0): ?>
                        <div class="modal fade" id="modalAnnulation<?= $trajet['id_covoiturage'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h5 class="modal-title">Confirmer l'annulation du trajet</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>Attention !</strong> Ce trajet a <?= $trajet['nb_reservations'] ?> réservation<?= $trajet['nb_reservations'] > 1 ? 's' : '' ?>.
                                        </div>
                                        <h6 class="mb-3">En annulant ce trajet :</h6>
                                        <ul>
                                            <li>Tous les passagers seront <strong>remboursés intégralement.</strong></li>
                                            <li>Vous perdrez <strong><?= $gains_total ?> crédits.</strong></li>
                                            <li>Le trajet sera marqué comme annulé.</li>
                                        </ul>
                                        <div class="alert alert-info mx-auto" style="max-width: 500px;">
        <div class="mb-2">
            <i class="fas fa-route me-2"></i>
            <strong>Trajet :</strong> <?= htmlspecialchars($trajet['lieu_depart']) ?> → <?= htmlspecialchars($trajet['lieu_arrivee']) ?>
        </div>
        <div class="mb-2">
            <i class="fas fa-calendar me-2"></i>
            <strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($trajet['datetime_depart'])) ?>
        </div>
        <div class="mb-0">
            <i class="fas fa-users me-2"></i>
            <strong>Passagers :</strong> <?= $trajet['places_reservees'] ?> place<?= $trajet['places_reservees'] > 1 ? 's' : '' ?> réservée<?= $trajet['places_reservees'] > 1 ? 's' : '' ?>
        </div>
    </div>
                                        <p class="text-danger mb-0 text-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Cette action est irréversible.</strong></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, conserver</button>
                                        <form method="POST" action="annuler-trajet.php" class="d-inline">
                                            <input type="hidden" name="id_trajet" value="<?= $trajet['id_covoiturage'] ?>">
                                            <button type="submit" class="btn btn-danger">Oui, annuler le trajet</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-secondary text-center">
                <i class="fas fa-info-circle me-2"></i>
                Aucun trajet à venir.
                <a href="publier-trajet.php" class="alert-link">Publier un trajet</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Historique -->
    <div class="mb-5">
        <h4 class="mb-4">
            <i class="fas fa-history me-2"></i>
            Historique (<?= count($historique) ?>)
        </h4>

        <?php if (count($historique) > 0): ?>
            <div class="row">
                <?php foreach ($historique as $trajet): ?>
                    <div class="col-md-12 mb-3">
                        <div class="card shadow-sm" style="opacity: 0.8;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-1">
                                            <?= htmlspecialchars($trajet['lieu_depart']) ?>
                                            <i class="fas fa-arrow-right mx-2"></i>
                                            <?= htmlspecialchars($trajet['lieu_arrivee']) ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?> 
                                            - <?= $trajet['places_reservees'] ?> place<?= $trajet['places_reservees'] > 1 ? 's' : '' ?> réservée<?= $trajet['places_reservees'] > 1 ? 's' : '' ?>
                                            - <?= ($trajet['prix_credit'] * $trajet['places_reservees']) ?> crédits gagnés
                                        </small>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <?php if ($trajet['statut'] == 'annule'): ?>
                                            <span class="badge bg-danger">Annulé</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Terminé</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-secondary text-center">
                <i class="fas fa-info-circle me-2"></i>
                Aucun historique.
            </div>
        <?php endif; ?>
    </div>
</div>