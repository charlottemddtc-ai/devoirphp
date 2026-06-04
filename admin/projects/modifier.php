<?php
session_start();
require_once '../../config/connexion.php';
require_once '../../fonctions.php';

// 1. VÉRIFICATION DE SÉCURITÉ
verifierAuthentification();

$erreur = null;
$succes = null;

// 2. RÉCUPÉRATION DU PROJET À MODIFIER
$id = intval($_GET['id'] ?? 0);

// CORRECTION : ciblage de la table en anglais "projects"
$stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]); // CORRECTION : Ajout du $ manquant devant id
$projet = $stmt->fetch();

// Si le projet n'existe pas en BDD
if (!$projet) {
    die("Projet introuvable.");
}

// 3. TRAITEMENT DU FORMULAIRE ENVOYÉ (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifierTokenCSRF($_POST['csrf_token'] ?? '');

    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $technologies = trim($_POST['technologies'] ?? ''); // Bien au pluriel
    $lien = trim($_POST['lien'] ?? '');
    
    // Par défaut, on garde l'ancien nom d'image stocké en BDD
    $nom_image = $projet['image']; 

    if (!empty($titre) && !empty($description) && !empty($technologies)) {
        
        // GESTION DU NOUVEL UPLOAD D'IMAGE (Optionnel)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                // On génère un nouveau nom unique
                $nom_image = bin2hex(random_bytes(8)) . '.' . $ext;
                $destination = '../../images/projets/' . $nom_image;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    // Suppression de l'ancienne image physique du serveur si elle existe
                    if (!empty($projet['image']) && file_exists('../../images/projets/' . $projet['image'])) {
                        unlink('../../images/projets/' . $projet['image']);
                    }
                }
            } else {
                $erreur = "Format d'image non valide (JPG, PNG, WEBP uniquement).";
            }
        }

        // Si aucune erreur d'image, on applique la mise à jour SQL
        if (!$erreur) {
            // CORRECTION : Table "projects" et colonne "technologies" alignées
            $stmt = $db->prepare("UPDATE projects SET titre = ?, description = ?, technologies = ?, image = ?, lien = ? WHERE id = ?");
            $stmt->execute([$titre, $description, $technologies, $nom_image, $lien, $id]);
            
            // Redirection vers la liste des projets avec un message de succès
            header('Location: index.php?msg=modifier_succes');
            exit();
        }
    } else {
        $erreur = "Veuillez remplir tous les champs obligatoires (*).";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Projet</title>
    
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
            max-width: 650px;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
            box-sizing: border-box;
        }

        .back-link {
            display: inline-block;
            font-size: 14px;
            color: #666666;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .back-link:hover {
            color: #111111;
            text-decoration: underline;
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

        .helper-text {
            font-size: 12px;
            color: #666666;
            margin-top: -4px;
            margin-bottom: 6px;
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

        input[type="text"]:focus,
        input[type="url"]:focus,
        textarea:focus {
            border-color: #000000;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.05);
        }

        /* Aperçu de l'image existante */
        .preview-box {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            margin-top: 4px;
        }

        .preview-img {
            max-width: 120px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
        }

        .no-image-text {
            font-size: 13px;
            color: #64748b;
            font-style: italic;
            margin: 0;
        }

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
            background-color: #111111; /* Bouton noir mat pro */
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
        <a href="index.php" class="back-link">← Retour à la liste des projets</a>
        
        <h2>Modifier le projet : <?= e($projet['titre']) ?></h2>

        <?php if ($erreur): ?>
            <div class="error-box">
                <?= e($erreur) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">
            
            <div class="form-group">
                <label for="titre">Titre du projet *</label>
                <input type="text" id="titre" name="titre" value="<?= e($projet['titre']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="6" required><?= e($projet['description']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="technologies">Technologies *</label>
                <div class="helper-text">Séparez les différentes technologies par des virgules (ex: PHP, MySQL, JavaScript)</div>
                <input type="text" id="technologies" name="technologies" value="<?= e($projet['technologies']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Image actuelle</label>
                <div class="preview-box">
                    <?php if (!empty($projet['image'])): ?>
                        <img src="../../images/projects/<?= e($projet['image']) ?>" class="preview-img" alt="Aperçu">
                        <div style="font-size: 12px; color: #64748b;">Fichier actuel :<br><span style="font-family: monospace; color:#334155;"><?= e($projet['image']) ?></span></div>
                    <?php else: ?>
                        <p class="no-image-text">Aucune image enregistrée pour ce projet.</p>
                    <?php endif; ?>
                </div>
                
                <label for="image" style="margin-top: 15px;">Remplacer l'image (Optionnel)</label>
                <input type="file" id="image" name="image">
            </div>
            
            <div class="form-group">
                <label for="lien">Lien du projet (URL de démo ou GitHub)</label>
                <input type="url" id="lien" name="lien" value="<?= e($projet['lien']) ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">Enregistrer les modifications</button>
            </div>
        </form>
    </div>

</body>
</html>