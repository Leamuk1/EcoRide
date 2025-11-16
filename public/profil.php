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
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (in_array($mime_type, $allowed_types)) {
            // Vérifier la taille (max 2MB)
            if ($file['size'] <= 2 * 1024 * 1024) {
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
                    //Mettre à jour la session
                    $_SESSION['user_photo'] = $filename;
                    $_SESSION['success_profil'] = "Photo de profil mise à jour avec succès !";
                } else {
                    $_SESSION['error_profil'] = "Erreur lors de l'upload de la photo.";
                }
            } else {
                $_SESSION['error_profil'] = "La photo ne doit pas dépasser 2 MB.";
            }
        } else {
            $_SESSION['error_profil'] = "Format de fichier non autorisé. Utilisez JPG ou PNG.";
        }
    } else {
        $_SESSION['error_profil'] = "Erreur lors de l'upload du fichier.";
    }
    
    header('Location: profil.php');
    exit;
}

// Traitement du formulaire de modification
if ($_POST && !isset($_POST['upload_photo'])) {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $date_naissance = $_POST['date_naissance'];
    $new_password = $_POST['new_password'] ?? '';
    $new_password_confirm = $_POST['new_password_confirm'] ?? '';
    
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
    
    // Préparer la requête de mise à jour
    $sql_update = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email, date_naissance = :date_naissance";
    $params = [
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'date_naissance' => $date_naissance,
        'id' => $_SESSION['user_id']
    ];
    
    // Si modification du mot de passe
    if (!empty($new_password)) {
        if ($new_password !== $new_password_confirm) {
            $_SESSION['error_profil'] = "Les mots de passe ne correspondent pas.";
            header('Location: profil.php');
            exit;
        }
        
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $sql_update .= ", password = :password";
        $params['password'] = $password_hash;
    }
    
    $sql_update .= " WHERE id_utilisateur = :id";
    
    // Exécuter la mise à jour
    $stmt_update = $pdo->prepare($sql_update);
    $success = $stmt_update->execute($params);
    
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

// Affichage de la page
include __DIR__ . '/../src/View/layout/header.php';
include __DIR__ . '/../src/View/pages/profil.php';
include __DIR__ . '/../src/View/layout/footer.php';