<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-lg-6">
                <div class="card search-card p-4">
                    <div class ="card-body">
                      <h3 class="mb-4">Rechercher un trajet</h3>
                      <form method="GET" action="recherche.php">
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fa-solid fa-record-vinyl input-icon"></i>
                                </span>
                                <input type="text" class="form-control" name="depart" placeholder="Ville de départ" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                </span>
                                <input type="text" class="form-control" name="arrivee" placeholder="Ville d'arrivée" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt input-icon"></i>
                                </span>
                            <input type="date" class="form-control" name="date" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fa-solid fa-user input-icon"></i>
                                </span>
                            <input type="number" class="form-control" name="passagers" placeholder="Nombre de passagers" min="1" value="1" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-search">Rechercher</button>
                      </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h1 class="hero-title">Ensemble,<br>Roulez pour la Planète</h1>
                <p class="hero-text">Rejoignez la révolution du covoiturage avec EcoRide, votre plateforme dédiée à des déplacements écologiques, économiques et conviviaux.</p>
            </div>
        </div>
    </div>
</section>

<!-- Qui sommes-nous -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2>Qui sommes-nous ?</h2>
                <p>EcoRide est une jeune startup française qui vise à transformer le covoiturage en France en offrant une solution plus écologique et économique. 
                    Leur plateforme met en relation conducteurs et passagers partageant le même itinéraire afin de réduire l'impact carbone et partager les frais. </p>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/about.jpg" alt="Qui sommes-nous" class="img-fluid rounded" style="height: 300px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- Un covoiturage écologique -->
<section class="py-5 bg-light-green">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <h2>Un covoiturage écologique</h2>
                <p>EcoRide vise à réduire l’empreinte carbone des déplacements en promouvant le covoiturage écologique, 
                    ce qui diminue les émissions de gaz à effet de serre et la pollution atmosphérique. En partageant un véhicule, 
                    on préserve les ressources naturelles et réduisons la consommation de carburant et la dépendance aux énergies fossiles. 
                    Cela permet aussi d’économiser de l’argent et de favoriser une mobilité durable.</p>
            </div>
            <div class="col-lg-6 order-lg-1">
                <img src="assets/images/eco.jpg" alt="Écologique" class="img-fluid rounded" style="height: 300px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- Voyagez en toute sécurité -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2>Voyagez en toute sécurité</h2>
                <p>EcoRide est la solution idéale pour un covoiturage à la fois économique et écologique. 
                    Rejoignez notre communauté de voyageurs responsables et agissez pour la planète !</p>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/secure.jpg" alt="Sécurité" class="img-fluid rounded" style="height: 300px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</section>