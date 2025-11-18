<!-- Page Mes réservations -->
<div class="container py-5">
    <h2 class="text-center mb-2">
        <i class="fas fa-ticket-alt me-2" style="color: var(--color-green-logo);"></i>
        Mes réservations
    </h2>
    <p class="text-center text-muted subtitle-inscription mb-5">
        Gérez vos trajets réservés
    </p>

    <!-- Réservations à venir -->
    <div class="mb-5">
        <h4 class="mb-4">
            <i class="fas fa-calendar-check me-2" style="color: var(--color-green-logo);"></i>
            Réservations à venir (<?= count($reservations_avenir) ?>)
        </h4>

        <?php if (count($reservations_avenir) > 0): ?>
            <div class="row">
                <?php foreach ($reservations_avenir as $reservation): ?>
                    <?php
                    // Calculer le temps restant
$datetime_depart = new DateTime($reservation['datetime_depart']);
$now = new DateTime();
$interval = $now->diff($datetime_depart);

// Vérifier si le trajet est dans le futur
if ($datetime_depart > $now) {
    $heures_restantes = ($interval->days * 24) + $interval->h;
} else {
    $heures_restantes = 0;
}
                    
                    // Calculer le remboursement possible
                    $cout_total = $reservation['prix_credit'] * $reservation['nb_places_reservees'];
                    if ($heures_restantes > 24) {
                        $remboursement = $cout_total;
                        $pourcentage = 100;
                    } elseif ($heures_restantes > 0) {
                        $remboursement = $cout_total * 0.5;
                        $pourcentage = 50;
                    } else {
                        $remboursement = 0;
                        $pourcentage = 0;
                    }
                    ?>
                    
                    <div class="col-md-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- Avatar conducteur -->
                                    <div class="col-md-1 text-center">
                                        <?php if (!empty($reservation['conducteur_photo']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $reservation['conducteur_photo'])): ?>
                                            <img src="uploads/avatars/<?= htmlspecialchars($reservation['conducteur_photo']) ?>" 
                                                 alt="Photo" class="avatar-trajet">
                                        <?php else: ?>
                                            <div class="avatar-trajet-initials">
                                                <?= strtoupper(substr($reservation['conducteur_pseudo'], 0, 2)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <small class="d-block mt-2"><?= htmlspecialchars($reservation['conducteur_pseudo']) ?></small>
                                    </div>

                                    <!-- Infos trajet -->
                                    <div class="col-md-7">
                                        <h5 class="mb-2">
                                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                                            <?= htmlspecialchars($reservation['lieu_depart']) ?>
                                            <i class="fas fa-arrow-right mx-2" style="color: var(--color-green-logo);"></i>
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                            <?= htmlspecialchars($reservation['lieu_arrivee']) ?>
                                        </h5>
                                        <p class="mb-1">
                                            <i class="fas fa-calendar me-2"></i>
                                            <?= date('d/m/Y', strtotime($reservation['date_depart'])) ?>
                                            à <?= date('H:i', strtotime($reservation['heure_depart'])) ?>
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-users me-2"></i>
                                            <?= $reservation['nb_places_reservees'] ?> place<?= $reservation['nb_places_reservees'] > 1 ? 's' : '' ?> réservée<?= $reservation['nb_places_reservees'] > 1 ? 's' : '' ?>
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-coins me-2"></i>
                                            <?= $cout_total ?> crédits
                                        </p>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-md-4 text-end details-reservation">
                                        <?php if ($heures_restantes > 0): ?>
                                            <div class="alert alert-info mb-3 card-alert-info">
                                                <small class="text-alert-info">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Départ dans <?= $interval->days ?> jour<?= $interval->days > 1 ? 's' : '' ?> 
                                                    et <?= $interval->h ?>h
                                                </small>
                                            </div>
                                            
                                            <button type="button" class="btn btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalAnnulation<?= $reservation['id_participe'] ?>">
                                                <i class="fas fa-times-circle me-2"></i>Annuler
                                            </button>
                                            
                                            <small class="text-muted d-block mt-2 remboursement-info">
                                                Remboursement : <?= $pourcentage ?>% (<?= $remboursement ?> crédits)
                                            </small>
                                        <?php else: ?>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Trajet en cours
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de confirmation d'annulation -->
                    <div class="modal fade" id="modalAnnulation<?= $reservation['id_participe'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmer l'annulation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir annuler cette réservation ?</p>
                                    <div class="alert alert-info">
                                        <p class="mb-1"><strong>Trajet :</strong> <?= htmlspecialchars($reservation['lieu_depart']) ?> → <?= htmlspecialchars($reservation['lieu_arrivee']) ?></p>
                                        <p class="mb-1"><strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($reservation['datetime_depart'])) ?></p>
                                        <p class="mb-1"><strong>Places :</strong> <?= $reservation['nb_places_reservees'] ?></p>
                                        <p class="mb-0"><strong>Remboursement :</strong> <?= $remboursement ?> crédits (<?= $pourcentage ?>%)</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, conserver</button>
                                    <form method="POST" action="annuler-reservation.php" class="d-inline">
                                        <input type="hidden" name="id_participe" value="<?= $reservation['id_participe'] ?>">
                                        <button type="submit" class="btn btn-danger">Oui, annuler</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-secondary text-center">
                <i class="fas fa-info-circle me-2"></i>
                Aucune réservation à venir.
                <a href="recherche.php" class="alert-link">Rechercher un trajet</a>
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
                <?php foreach ($historique as $reservation): ?>
                    <div class="col-md-12 mb-3">
                        <div class="card shadow-sm" style="opacity: 0.8;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <small class="d-block"><?= htmlspecialchars($reservation['conducteur_pseudo']) ?></small>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="mb-1">
                                            <?= htmlspecialchars($reservation['lieu_depart']) ?>
                                            <i class="fas fa-arrow-right mx-2"></i>
                                            <?= htmlspecialchars($reservation['lieu_arrivee']) ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($reservation['date_depart'])) ?> 
                                            - <?= $reservation['nb_places_reservees'] ?> place<?= $reservation['nb_places_reservees'] > 1 ? 's' : '' ?>
                                        </small>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <?php if ($reservation['statut'] == 'annulee'): ?>
                                            <span class="badge bg-danger">Annulée</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Terminée</span>
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