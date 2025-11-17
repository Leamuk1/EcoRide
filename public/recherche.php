<?php
session_start();

require_once __DIR__ . '/../src/Config/Database.php';

$db = new Database();
$pdo = $db->connect();

// Récupérer les paramètres de recherche
$ville_depart = htmlspecialchars(trim($_GET['depart'] ?? ''));
$ville_arrivee = htmlspecialchars(trim($_GET['arrivee'] ?? ''));
$date_trajet = $_GET['date'] ?? '';
$nb_passagers = (int)($_GET['passagers'] ?? 1);

$trajets = [];

// Si recherche effectuée
if (!empty($ville_depart) && !empty($ville_arrivee) && !empty($date_trajet)) {
    
    // Récupérer les filtres avancés
    $prix_max = (int)($_GET['prix_max'] ?? 0);
    $distance_max = (int)($_GET['distance_max'] ?? 0);
    $ecologique = $_GET['ecologique'] ?? '';
    $preference = (int)($_GET['preference'] ?? 0);

    // Construire la requête avec les filtres
    $sql = "SELECT 
                c.*,
                u.prenom,
                u.nom,
                u.pseudo,
                u.photo_profil,
                v.modele,
                v.energie,
                m.libelle as nom_marque,
                (c.nb_place - COALESCE(SUM(p.nb_places_reservees), 0)) as places_restantes
            FROM covoiturage c
            INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            INNER JOIN voiture v ON c.id_voiture = v.id_voiture
            INNER JOIN marque m ON v.id_marque = m.id_marque
            LEFT JOIN participe p ON c.id_covoiturage = p.id_covoiturage";

    // Ajouter une jointure si filtre préférence
    if ($preference > 0) {
        $sql .= " INNER JOIN covoiturage_preference cp ON c.id_covoiturage = cp.id_covoiturage
                  AND cp.id_type_preference = :preference";
    }

    // WHERE principal (une seule fois !)
    $sql .= " WHERE c.lieu_depart LIKE :depart
            AND c.lieu_arrivee LIKE :arrivee
            AND c.date_depart = :date
            AND c.statut = 'en_attente'";

    // Ajouter les filtres conditionnels
    $params = [
        'depart' => '%' . $ville_depart . '%',
        'arrivee' => '%' . $ville_arrivee . '%',
        'date' => $date_trajet,
        'nb_passagers' => $nb_passagers
    ];

    if ($prix_max > 0) {
        $sql .= " AND c.prix_credit <= :prix_max";
        $params['prix_max'] = $prix_max;
    }

    if ($distance_max > 0) {
        $sql .= " AND c.distance_km <= :distance_max";
        $params['distance_max'] = $distance_max;
    }

    if ($ecologique == '1') {
        $sql .= " AND v.energie IN ('electrique', 'hybride')";
    }

    if ($preference > 0) {
        $params['preference'] = $preference;
    }

    $sql .= " GROUP BY c.id_covoiturage
             HAVING places_restantes >= :nb_passagers
             ORDER BY c.heure_depart ASC";
    
    // EXÉCUTER LA REQUÊTE
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si aucun trajet trouvé, chercher des suggestions
    $suggestions = [];
    if (count($trajets) == 0) {
        // Recherche de trajets similaires (dates proches ±3 jours, mêmes villes)
        $sql_suggestions = "SELECT 
                    c.*,
                    u.prenom,
                    u.nom,
                    u.pseudo,
                    u.photo_profil,
                    v.modele,
                    v.energie,
                    m.libelle as nom_marque,
                    (c.nb_place - COALESCE(SUM(p.nb_places_reservees), 0)) as places_restantes,
                    DATEDIFF(c.date_depart, :date) as ecart_jours
                FROM covoiturage c
                INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                INNER JOIN voiture v ON c.id_voiture = v.id_voiture
                INNER JOIN marque m ON v.id_marque = m.id_marque
                LEFT JOIN participe p ON c.id_covoiturage = p.id_covoiturage
                WHERE (
                    -- Même trajet mais dates différentes
                    (c.lieu_depart LIKE :depart AND c.lieu_arrivee LIKE :arrivee 
                     AND c.date_depart BETWEEN DATE_SUB(:date, INTERVAL 3 DAY) 
                                           AND DATE_ADD(:date, INTERVAL 3 DAY)
                     AND c.date_depart != :date)
                    OR
                    -- Même ville de départ, destination proche
                    (c.lieu_depart LIKE :depart AND c.date_depart = :date)
                    OR
                    -- Même destination, départ proche
                    (c.lieu_arrivee LIKE :arrivee AND c.date_depart = :date)
                )
                AND c.statut = 'en_attente'
                GROUP BY c.id_covoiturage
                HAVING places_restantes >= :nb_passagers
                ORDER BY ABS(ecart_jours) ASC, c.heure_depart ASC
                LIMIT 10";
        
        $stmt_sugg = $pdo->prepare($sql_suggestions);
        $stmt_sugg->execute([
            'depart' => '%' . $ville_depart . '%',
            'arrivee' => '%' . $ville_arrivee . '%',
            'date' => $date_trajet,
            'nb_passagers' => $nb_passagers
        ]);
        $suggestions = $stmt_sugg->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/recherche.php';
include __DIR__ . '/../src/View/layout/footer.php';