<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifierAuthentification();

$erreur = null;
$succes = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifierTokenCSRF($_POST['csrf_token'] ?? '');

    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['mot_de_passe'] ?? '';

    if (!empty($prenom) && !empty($nom) && !empty($email) && !empty($password)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Hachage du mot de passe
            $hash = password_hash($password, PASSWORD_BCRYPT);

            try {
                $stmt = $db->prepare("INSERT INTO administrateurs (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
                $stmt->execute([$prenom, $nom, $email, $hash]);
                header('Location: index.php');
                exit();
            } catch (PDOException $e) {
                $erreur = "Cette adresse email est déjà utilisée.";
            }
        } else {
            $erreur = "Format de l'email invalide.";
        }
    } else {
        $erreur = "Tous les champs sont obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Administrateur</title>
    
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
        
        <h2>Ajouter un nouvel Administrateur</h2>

        <?php if($erreur): ?>
            <div class="error-box">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" placeholder="Ex: Jean" required>
            </div>
            
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" placeholder="Ex: Dupont" required>
            </div>
            
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" placeholder="adresse@exemple.com" required>
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">Créer l'administrateur</button>
            </div>
        </form>
    </div>

</body>
</html>