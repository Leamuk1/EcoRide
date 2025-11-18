<!-- Page Paiement & Remboursement -->
<div class="container py-5">
    <h2 class="text-center mb-2">
        <i class="fas fa-wallet me-2" style="color: var(--color-green-logo);"></i>
        Paiement & Remboursement
    </h2>
    <p class="text-center text-muted subtitle-inscription mb-5">
        Gérez vos transactions et votre solde de crédits
    </p>

    <!-- Solde actuel -->
    <div class="row mb-5">
        <div class="col-md-4 mx-auto">
            <div class="card shadow-sm text-center" style="border-left: 4px solid var(--color-green-logo);">
                <div class="card-body py-4">
                    <h5 class="text-muted mb-3">Solde actuel</h5>
                    <h2 class="mb-0" style="color: var(--color-green-logo);">
                        <i class="fas fa-coins me-2"></i>
                        <?= $_SESSION['user_credits'] ?? 0 ?> crédits
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des transactions -->
    <div class="card shadow-sm">
        <div class="card-header" style="background-color: var(--color-primary); color: white;">
            <h4 class="mb-0">
                <i class="fas fa-history me-2"></i>
                Historique des transactions
            </h4>
        </div>
        <div class="card-body">
            <?php if (count($transactions) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Trajet</th>
                                <th>Places</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($transaction['date_transaction'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($transaction['type'] == 'reservation'): ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-arrow-down me-1"></i>Réservation
                                            </span>
                                        <?php elseif ($transaction['type'] == 'annulation'): ?>
                                            <span class="badge bg-info">
                                                <i class="fas fa-undo me-1"></i>Remboursement
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-up me-1"></i>Gain conducteur
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small>
                                            <?= htmlspecialchars($transaction['lieu_depart']) ?> 
                                            <i class="fas fa-arrow-right mx-1"></i>
                                            <?= htmlspecialchars($transaction['lieu_arrivee']) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small><?= $transaction['nb_places_reservees'] ?> place<?= $transaction['nb_places_reservees'] > 1 ? 's' : '' ?></small>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($transaction['sens'] == 'Débit'): ?>
                                            <strong class="text-danger">
                                                - <?= $transaction['montant'] ?> crédits
                                            </strong>
                                        <?php else: ?>
                                            <strong class="text-success">
                                                + <?= $transaction['montant'] ?> crédits
                                            </strong>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-secondary text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucune transaction pour le moment.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Informations -->
    <div class="alert alert-info mt-4">
        <h6><i class="fas fa-info-circle me-2"></i>Comment fonctionnent les crédits ?</h6>
        <ul class="mb-0">
            <li>Vous recevez <strong>20 crédits gratuits</strong> à l'inscription</li>
            <li>Vous gagnez des crédits en <strong>proposant des trajets</strong></li>
            <li>Vous dépensez des crédits en <strong>réservant des trajets</strong></li>
            <li>En cas d'annulation > 24h : <strong>100% remboursé</strong></li>
            <li>En cas d'annulation < 24h : <strong>50% remboursé</strong></li>
        </ul>
    </div>
</div>