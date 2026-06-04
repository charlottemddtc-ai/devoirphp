<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifierAuthentification();

$id = intval($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM administrateurs WHERE id = ?");
$stmt->execute([$id]);
$admin = $stmt->fetch();

if (!$admin) {
    die("Administrateur introuvable.");
}

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifierTokenCSRF($_POST['csrf_token'] ?? '');
    
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nouveau_password = $_POST['mot_de_passe'] ?? '';

    if (!empty($prenom) && !empty($nom) && !empty($email)) {
        // Logique du mot de passe imposée par le cahier des charges
        if (!empty($nouveau_password)) {
            $password_hash = password_hash($nouveau_password, PASSWORD_BCRYPT);
        } else {
            $password_hash = $admin['mot_de_passe']; // On garde l'ancien hash si le champ est vide
        }

        $stmt = $db->prepare("UPDATE administrateurs SET prenom = ?, nom = ?, email = ?, mot_de_passe = ? WHERE id = ?");
        $stmt->execute([$prenom, $nom, $email, $password_hash, $id]);
        header('Location: index.php');
        exit();
    } else {
        $erreur = "Les champs Prénom, Nom et Email sont obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Administrateur</title>
    
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
            max-width: 550px;
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
        input[type="email"],
        input[type="password"] {
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

        /* Focus neutre, sobre et propre */
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #000000;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.05);
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
        <a href="index.php" class="back-link">← Retour à la liste</a>
        
        <h2>Modifier l'administrateur : <?= htmlspecialchars($admin['prenom']) ?></h2>

        <?php if($erreur): ?>
            <div class="error-box">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">
            
            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($admin['prenom']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($admin['nom']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Adresse email *</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <div class="helper-text">Laissez ce champ vide pour ne pas modifier le mot de passe actuel.</div>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">Enregistrer les modifications</button>
            </div>
        </form>
    </div>

</body>
</html>