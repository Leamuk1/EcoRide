<!-- Page de confirmation de réservation -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Message de succès -->
            <div class="alert alert-success text-center mb-4">
                <i class="fas fa-check-circle fa-3x mb-3 d-block" style="color: var(--color-green-logo);"></i>
                <h3 class="mb-3">Réservation confirmée !</h3>
                <p class="mb-0" style="text-align: center;">Votre réservation a été enregistrée avec succès.</p>
            </div>

            <!-- Détails de la réservation -->
            <div class="card shadow-sm mb-4 confirmation-card">
                <div class="card-header" style="background-color: var(--color-primary); color: white;">
                    <h4 class="mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>
                        Récapitulatif de votre réservation
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Trajet -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-route me-2" style="color: var(--color-green-logo);"></i>
                            Trajet
                        </h5>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                            <strong>Départ :</strong> <?= htmlspecialchars($trajet['lieu_depart']) ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            <strong>Arrivée :</strong> <?= htmlspecialchars($trajet['lieu_arrivee']) ?>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-calendar me-2"></i>
                            <strong>Date :</strong> 
                            <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?> 
                            à <?= date('H:i', strtotime($trajet['heure_depart'])) ?>
                        </p>
                    </div>

                    <hr>

                    <!-- Conducteur -->
<div class="mb-4">
    <h5 class="mb-3">
        <i class="fas fa-user me-2" style="color: var(--color-green-logo);"></i>
        Conducteur
    </h5>
    
    <div class="d-flex align-items-center mb-3">
        <!-- Avatar du conducteur -->
        <div class="me-3">
            <?php if (!empty($trajet['photo_profil']) && file_exists(__DIR__ . '/../../../public/uploads/avatars/' . $trajet['photo_profil'])): ?>
                <img src="uploads/avatars/<?= htmlspecialchars($trajet['photo_profil']) ?>" 
                     alt="Photo de profil" 
                     class="rounded-circle" 
                     style="width: 55px; height: 55px; object-fit: cover; border: 3px solid var(--color-green-logo);">
            <?php else: ?>
                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                     style="width: 60px; height: 60px; background-color: var(--color-green-logo); color: white; font-size: 1.5rem; font-weight: bold;">
                    <?= strtoupper(substr($trajet['conducteur_prenom'], 0, 1) . substr($trajet['conducteur_nom'], 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Nom du conducteur -->
        <div>
            <p class="mb-0">
                <strong style="font-size: 1.1rem;"><?= htmlspecialchars($trajet['conducteur_pseudo']) ?></strong>
            </p>
        </div>
    </div>
    
    <!-- Préférences du conducteur -->
    <?php if (isset($preferences) && count($preferences) > 0): ?>
        <p class="mb-1"><strong>Préférences :</strong></p>
        <div class="d-flex flex-wrap gap-2">
            <?php foreach ($preferences as $pref): ?>
                <span class="badge bg-light text-dark">
                    <i class="fas <?= $pref['icone'] ?> me-1"></i>
                    <?= htmlspecialchars($pref['libelle']) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-muted mb-0">
            <small><i class="fas fa-info-circle me-1"></i>Aucune préférence particulière</small>
        </p>
    <?php endif; ?>
</div>

                    <hr>

                    <!-- Réservation -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle me-2" style="color: var(--color-green-logo);"></i>
                            Votre réservation
                        </h5>
                        <p class="mb-2">
                            <i class="fas fa-users me-2"></i>
                            <strong>Nombre de places :</strong> <?= $nb_places ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-coins me-2"></i>
                            <strong>Coût total :</strong> 
                            <span style="color: var(--color-green-logo); font-size: 1.2rem; font-weight: 700;">
                                <?= $cout ?> crédits
                            </span>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-wallet me-2"></i>
                            <strong>Crédits restants :</strong> <?= $_SESSION['user_credits'] ?> crédits
                        </p>
                    </div>

                    <hr>

                    <!-- Informations importantes -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-exclamation-circle me-2"></i>Informations importantes</h6>
                        <ul class="mb-0">
                            <li>Rendez-vous à l'heure indiquée au point de départ</li>
                            <li>Le conducteur vous contactera avant le départ</li>
                            <li>En cas d'annulation, prévenez rapidement le conducteur</li>
                        </ul>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="text-center mt-4">
                        <a href="mes-reservations.php" class="btn btn-primary me-2">
                            <i class="fas fa-list me-2"></i>Mes réservations
                        </a>
                        <a href="recherche.php" class="btn btn-outline-secondary">
                            <i class="fas fa-search me-2"></i>Nouvelle recherche
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>