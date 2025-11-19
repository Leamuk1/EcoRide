<!-- Page Mes véhicules -->
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-2">
                <i class="fas fa-car me-2" style="color: var(--color-green-logo);"></i>
                Mes véhicules
            </h2>
            <p class="text-muted subtitle-inscription mb-0">
                Gérez vos véhicules pour vos covoiturages
            </p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjoutVehicule">
            <i class="fas fa-plus-circle me-2"></i>Ajouter un véhicule
        </button>
    </div>

    <!-- Affichage des erreurs -->
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Erreurs :</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <!-- Liste des véhicules -->
    <?php if (count($vehicules) > 0): ?>
        <div class="row">
            <?php foreach ($vehicules as $vehicule): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-car me-2" style="color: var(--color-green-logo);"></i>
                                    <?= htmlspecialchars($vehicule['nom_marque']) ?> 
                                    <?= htmlspecialchars($vehicule['modele']) ?>
                                </h5>
                                <?php if (in_array($vehicule['energie'], ['electrique', 'hybride'])): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-leaf me-1"></i>Écologique
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Couleur</small>
                                    <p class="mb-0"><strong><?= htmlspecialchars($vehicule['couleur']) ?></strong></p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Places</small>
                                    <p class="mb-0"><strong><?= $vehicule['nb_places'] ?></strong></p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Énergie</small>
                                    <p class="mb-0"><strong><?= ucfirst($vehicule['energie']) ?></strong></p>
                                </div>
                                <?php if (!empty($vehicule['immatriculation'])): ?>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Immatriculation</small>
                                        <p class="mb-0"><strong><?= htmlspecialchars($vehicule['immatriculation']) ?></strong></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmerSuppression(<?= $vehicule['id_voiture'] ?>)">
                                    <i class="fas fa-trash me-1"></i>Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Vous n'avez pas encore ajouté de véhicule.
            <button type="button" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalAjoutVehicule">
                Ajouter mon premier véhicule
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Ajout véhicule -->
<div class="modal fade" id="modalAjoutVehicule" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un véhicule
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="mes-vehicules.php">
                <input type="hidden" name="action" value="ajouter">
                <div class="modal-body">
                    <div class="row">
                       <!-- Marque -->
<div class="col-md-6 mb-3">
    <label class="form-label">
        <i class="fas fa-industry me-2"></i>
        Marque <span class="text-danger">*</span>
    </label>
    <select class="form-select" name="id_marque" id="id_marque" onchange="toggleMarquePersonnalisee()" required>
        <option value="">Sélectionnez une marque</option>
        <option value="autre">🔧 Autre marque (saisir manuellement)</option>
        <optgroup label="Marques populaires">
            <?php foreach ($marques as $marque): ?>
                <?php if ($marque['libelle'] !== 'Autre'): // Exclure "Autre" de la liste ?>
                    <option value="<?= $marque['id_marque'] ?>">
                        <?= htmlspecialchars($marque['libelle']) ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </optgroup>
    </select>
    
    <!-- Champ marque personnalisée (caché par défaut) -->
    <input type="text" 
           class="form-control mt-2 d-none" 
           name="marque_personnalisee" 
           id="marque_personnalisee" 
           placeholder="Entrez la marque de votre véhicule"
           maxlength="100">
    <small class="text-muted d-none" id="hint_marque_perso">
        💡 La marque sera ajoutée automatiquement à la liste
    </small>
</div>

                        <!-- Modèle -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-car me-2"></i>
                                Modèle <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="modele" 
                                   placeholder="Ex: 308, Clio, Golf..." 
                                   required>
                        </div>

                        <!-- Couleur -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-palette me-2"></i>
                                Couleur <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="couleur" 
                                   placeholder="Ex: Noir, Blanc, Gris..." 
                                   required>
                        </div>

                        <!-- Nombre de places -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-users me-2"></i>
                                Nombre de places <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="nb_places" required>
                                <option value="">Sélectionnez</option>
                                <option value="2">2 places</option>
                                <option value="4">4 places</option>
                                <option value="5" selected>5 places</option>
                                <option value="6">6 places</option>
                                <option value="7">7 places</option>
                                <option value="8">8 places</option>
                                <option value="9">9 places</option>
                            </select>
                        </div>

                        <!-- Énergie -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-gas-pump me-2"></i>
                                Type d'énergie <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="energie" required>
                                <option value="">Sélectionnez</option>
                                <option value="essence">Essence</option>
                                <option value="diesel">Diesel</option>
                                <option value="electrique">Électrique ⚡</option>
                                <option value="hybride">Hybride 🌿</option>
                                <option value="gpl">GPL</option>
                            </select>
                        </div>

                        <!-- Immatriculation (optionnel) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-id-card me-2"></i>
                                Immatriculation (optionnel)
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="immatriculation" 
                                   placeholder="Ex: AB-123-CD">
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Conseil :</strong> Les véhicules électriques et hybrides sont mis en avant avec un badge "Écologique" !
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle me-2"></i>Ajouter le véhicule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleMarquePersonnalisee() {
    const select = document.getElementById('id_marque');
    const inputPerso = document.getElementById('marque_personnalisee');
    const hint = document.getElementById('hint_marque_perso');
    
    if (select.value === 'autre') {
        inputPerso.classList.remove('d-none');
        hint.classList.remove('d-none');
        inputPerso.required = true;
        inputPerso.focus();
        select.required = false;
    } else {
        inputPerso.classList.add('d-none');
        hint.classList.add('d-none');
        inputPerso.required = false;
        inputPerso.value = '';
        select.required = true;
    }
}

function confirmerSuppression(idVoiture) {
    if (confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce véhicule ?\n\nCette action est irréversible.')) {
        window.location.href = 'supprimer-vehicule.php?id=' + idVoiture;
    }
}

// Réouvrir le modal en cas d'erreur
<?php if (isset($_SESSION['errors'])): ?>
    window.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('modalAjoutVehicule'));
        modal.show();
    });
<?php endif; ?>
</script>