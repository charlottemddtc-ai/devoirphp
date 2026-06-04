<?php 
// 1. DÉMARRAGE DE LA SESSION (Obligatoire pour les fonctions de sécurité)
session_start(); 

// CORRECTION des chemins : si tu es dans admin/projects/ajouter.php, il faut remonter de deux dossiers
require_once '../../fonctions.php'; 
require_once '../../config/connexion.php'; 

verifierAuthentification();
$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CORRECTION : Orthographe de la fonction et du token (csrf au lieu de crsf)
    verifierTokenCSRF($_POST['csrf_token'] ?? '');
    
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $technologies = trim($_POST['technologies'] ?? ''); // Mis au pluriel pour correspondre à ta BDD
    $lien = trim($_POST['lien'] ?? '');

    $nom_image = null;
    
    // CORRECTION : Utilisation de 0 à la place de UPLOAD_ERR_OK pour éviter l'erreur de constante
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $expressions_autorisees = ['jpg', 'jpeg', 'png', 'gif']; // Correction orthographe
        $info_fichier = pathinfo($_FILES['image']['name']);
        $extension = strtolower($info_fichier['extension']);
        
        if (in_array($extension, $expressions_autorisees)) {
            $nom_image = bin2hex(random_bytes(10)) . '.' . $extension;
            // CORRECTION : Envoi dans le dossier "projets" en anglais ou français selon tes dossiers
            $dossier_destination = '../../images/projets/' . $nom_image;
            move_uploaded_file($_FILES['image']['tmp_name'], $dossier_destination);
        } else {
            $erreur = "Format d'image non autorisé. Seuls les formats jpg, jpeg, png et gif sont autorisés.";
        }
    }

    // Validation et Insertion
    if (empty($erreur) && !empty($titre) && !empty($description) && !empty($technologies)) {
        // CORRECTION : "technologies" au pluriel pour correspondre exactement aux colonnes de ta table "projects"
        $stmt = $db->prepare("INSERT INTO projects (titre, description, technologies, lien, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $technologies, $lien, $nom_image]);
        
        header('Location: index.php');
        exit();
    } elseif (empty($erreur)) {
        $erreur = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un projet</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* --- STYLE GLOBAL ET STRUCTURE --- */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #fafafa;
            color: #111111;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .form-container {
            width: 100%;
            max-width: 600px;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
            box-sizing: border-box;
        }

        h2 {
            font-size: 22px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 25px;
            color: #000000;
            letter-spacing: -0.5px;
        }

        /* --- ÉLÉMENTS DE FORMULAIRE --- */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            color: #333333;
        }

        input[type="text"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            color: #111111;
            font-family: inherit;
        }

        /* Focus neutre et sobre */
        input[type="text"]:focus,
        input[type="url"]:focus,
        textarea:focus {
            border-color: #000000;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.05);
        }

        /* Input fichier spécifique */
        input[type="file"] {
            font-size: 14px;
            color: #555555;
            margin-top: 4px;
        }

        /* --- ACTIONS / BOUTONS --- */
        .form-actions {
            display: flex;
            align-items: center;
            margin-top: 25px;
            gap: 15px;
        }

        .btn-submit {
            padding: 12px 24px;
            background-color: #111111; /* Bouton noir mat très professionnel */
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .btn-submit:hover {
            background-color: #222222;
        }

        .btn-cancel {
            font-size: 14px;
            color: #666666;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-cancel:hover {
            color: #111111;
            text-decoration: underline;
        }

        /* --- BANNIÈRE D'ERREUR --- */
        .error-box {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            padding: 12px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 24px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Ajouter un projet</h2>

        <?php if ($erreur): ?>
            <div class="error-box">
                <?= e($erreur) ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">
            
            <div class="form-group">
                <label for="titre">Titre du projet *</label>
                <input type="text" id="titre" name="titre" placeholder="Ex: Création d'un site E-commerce" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description complète *</label>
                <textarea id="description" name="description" placeholder="Détaillez les fonctionnalités clés du projet..." rows="6" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="technologies">Technologies utilisées *</label>
                <input type="text" id="technologies" name="technologies" placeholder="Ex: PHP, MySQL, HTML5, CSS3" required>
            </div>
            
            <div class="form-group">
                <label for="lien">Lien associé (Optionnel)</label>
                <input type="url" id="lien" name="lien" placeholder="https://github.com/votre-depot">
            </div>
            
            <div class="form-group">
                <label for="image">Image d'illustration</label>
                <input type="file" id="image" name="image">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">Ajouter le projet</button>
                <a href="index.php" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>

</body>
</html>