<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

require_once __DIR__ . '/../src/Config/Database.php';

$db = new Database();
$pdo = $db->connect();

// Récupérer les informations de l'utilisateur
$sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Traitement de l'upload de photo
if (isset($_POST['upload_photo']) && isset($_FILES['photo_profil'])) {
    $file = $_FILES['photo_profil'];
    
    // Vérifier qu'il n'y a pas d'erreur
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Vérifier le type MIME
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (in_array($mime_type, $allowed_types)) {
            // Vérifier la taille (max 5MB)
            if ($file['size'] <= 5 * 1024 * 1024) {
                // Générer un nom unique
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
                $upload_path = __DIR__ . '/uploads/avatars/' . $filename;
                
                // Créer le dossier s'il n'existe pas
                if (!is_dir(__DIR__ . '/uploads/avatars/')) {
                    mkdir(__DIR__ . '/uploads/avatars/', 0755, true);
                }
                
                // Supprimer l'ancienne photo si elle existe
                if (!empty($user['photo_profil']) && file_exists(__DIR__ . '/uploads/avatars/' . $user['photo_profil'])) {
                    unlink(__DIR__ . '/uploads/avatars/' . $user['photo_profil']);
                }
                
                // Déplacer le fichier
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Mettre à jour la BDD
                    $sql_photo = "UPDATE utilisateur SET photo_profil = :photo WHERE id_utilisateur = :id";
                    $stmt_photo = $pdo->prepare($sql_photo);
                    $stmt_photo->execute(['photo' => $filename, 'id' => $_SESSION['user_id']]);
                    
                    // Mettre à jour la session
                    $_SESSION['user_photo'] = $filename;
                    $_SESSION['success_profil'] = "Photo de profil mise à jour avec succès !";
                } else {
                    $_SESSION['error_profil'] = "Erreur lors de l'upload de la photo.";
                }
            } else {
                $_SESSION['error_profil'] = "La photo ne doit pas dépasser 5 MB.";
            }
        } else {
            $_SESSION['error_profil'] = "Format de fichier non autorisé. Utilisez JPG, PNG ou WEBP.";
        }
    } else {
        $_SESSION['error_profil'] = "Erreur lors de l'upload du fichier.";
    }
    
    header('Location: profil.php');
    exit;
}

// Traitement du changement de mot de passe
if (isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'] ?? '';
    $new_password_confirm = $_POST['new_password_confirm'] ?? '';
    
    if (empty($new_password)) {
        $_SESSION['error_profil'] = "Le nouveau mot de passe est requis.";
    } elseif (strlen($new_password) < 8) {
        $_SESSION['error_profil'] = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif ($new_password !== $new_password_confirm) {
        $_SESSION['error_profil'] = "Les mots de passe ne correspondent pas.";
    } else {
        // Mettre à jour le mot de passe
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $sql_update_pwd = "UPDATE utilisateur SET password = :password WHERE id_utilisateur = :id";
        $stmt_update_pwd = $pdo->prepare($sql_update_pwd);
        $success = $stmt_update_pwd->execute(['password' => $password_hash, 'id' => $_SESSION['user_id']]);
        
        if ($success) {
            $_SESSION['success_profil'] = "Mot de passe modifié avec succès !";
        } else {
            $_SESSION['error_profil'] = "Erreur lors de la modification du mot de passe.";
        }
    }
    
    header('Location: profil.php');
    exit;
}

// Traitement du formulaire de modification du profil
if ($_POST && !isset($_POST['upload_photo']) && !isset($_POST['change_password'])) {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));
    $date_naissance = $_POST['date_naissance'];
    
    // Validation email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_profil'] = "Email invalide.";
        header('Location: profil.php');
        exit;
    }
    
    // Vérifier si l'email est déjà utilisé par un autre utilisateur
    $sql_check = "SELECT id_utilisateur FROM utilisateur WHERE email = :email AND id_utilisateur != :id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['email' => $email, 'id' => $_SESSION['user_id']]);
    
    if ($stmt_check->fetch()) {
        $_SESSION['error_profil'] = "Cet email est déjà utilisé par un autre compte.";
        header('Location: profil.php');
        exit;
    }
    
    // Mettre à jour les informations
    $sql_update = "UPDATE utilisateur 
                   SET nom = :nom, 
                       prenom = :prenom, 
                       email = :email, 
                       telephone = :telephone,
                       date_naissance = :date_naissance
                   WHERE id_utilisateur = :id";
    
    $stmt_update = $pdo->prepare($sql_update);
    $success = $stmt_update->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'telephone' => $telephone,
        'date_naissance' => $date_naissance,
        'id' => $_SESSION['user_id']
    ]);
    
    if ($success) {
        // Mettre à jour la session
        $_SESSION['user_name'] = $prenom . ' ' . $nom;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_prenom'] = $prenom;
        $_SESSION['user_nom'] = $nom;
        
        $_SESSION['success_profil'] = "Vos informations ont été mises à jour avec succès !";
        header('Location: profil.php');
        exit;
    } else {
        $_SESSION['error_profil'] = "Erreur lors de la mise à jour.";
        header('Location: profil.php');
        exit;
    }
}

// ============================================
// STATISTIQUES DÉTAILLÉES
// ============================================

// Statistiques - Trajets publiés (conducteur)
$sql_trajets_conducteur = "SELECT 
                            COUNT(*) as nb_trajets,
                            COALESCE(SUM(distance_km), 0) as km_conducteur,
                            COALESCE(SUM(prix_credit * (
                                SELECT COALESCE(SUM(nb_places_reservees), 0) 
                                FROM participe 
                                WHERE id_covoiturage = covoiturage.id_covoiturage 
                                AND statut = 'confirmee'
                            )), 0) as credits_gagnes
                        FROM covoiturage 
                        WHERE id_utilisateur = :id 
                        AND statut IN ('termine', 'en_cours')";
$stmt_trajets_conducteur = $pdo->prepare($sql_trajets_conducteur);
$stmt_trajets_conducteur->execute(['id' => $_SESSION['user_id']]);
$stats_conducteur = $stmt_trajets_conducteur->fetch(PDO::FETCH_ASSOC);

// Statistiques - Trajets réservés (passager)
$sql_trajets_passager = "SELECT 
                            COUNT(*) as nb_reservations,
                            COALESCE(SUM(c.distance_km * p.nb_places_reservees), 0) as km_passager,
                            COALESCE(SUM(c.prix_credit * p.nb_places_reservees), 0) as credits_depenses
                        FROM participe p
                        INNER JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage
                        WHERE p.id_utilisateur = :id 
                        AND p.statut = 'confirmee'
                        AND c.statut IN ('termine', 'en_cours')";
$stmt_trajets_passager = $pdo->prepare($sql_trajets_passager);
$stmt_trajets_passager->execute(['id' => $_SESSION['user_id']]);
$stats_passager = $stmt_trajets_passager->fetch(PDO::FETCH_ASSOC);

// Calculs totaux
$stats_trajets_publies = $stats_conducteur['nb_trajets'];
$stats_trajets_reserves = $stats_passager['nb_reservations'];
$stats_km_parcourus = $stats_conducteur['km_conducteur'] + $stats_passager['km_passager'];
$stats_credits_gagnes = $stats_conducteur['credits_gagnes'];
$stats_credits_depenses = $stats_passager['credits_depenses'];

// Calcul CO2 économisé (estimation : 0.2 kg CO2 par km par personne)
$stats_co2_economise = round($stats_km_parcourus * 0.2);

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/profil.php';
include __DIR__ . '/../src/View/layout/footer.php';