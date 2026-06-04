<?php 
session_start();
require_once '../fonctions.php' ; 
require_once '../config/connexion.php' ; 

enregistrerVisite($db, basename($_SERVER['PHP_SELF']));

if(isset($_SESSION['admin_id'])) {
   header('Location: connexion.php');
   exit();
}

$erreur = null;
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifierTokenCSRF($_POST['csrf_token']);
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $db->prepare('SELECT * FROM administrateurs WHERE email = ?');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    // MESSAGE D'ERREUR GENERIQUE UNIFIE POUR NE PAS AIDER LES HACKERS
    if($admin && password_verify($password, $admin['mot_de_passe'])) {
        session_regenerate_id(true);
        // stocke id et le prenom
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_prenom'] = $admin['prenom'];
        header('Location: dashboard.php');
        exit();
    } else {
        $erreur = 'Email ou mot de passe incorrect';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">        
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Connexion</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- STYLE GLOBAL SANS EFFETS IA SUPERFLUS --- */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #fafafa; /* Fond mat très clair */
            color: #111111;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* --- BLOC CENTRAL DE CONNEXION --- */
        .login-card {
            width: 100%;
            max-width: 390px;
            padding: 40px;
            background: #ffffff;
            border: 1px solid #e5e5e5; /* Bordure fine grise réaliste */
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
            box-sizing: border-box;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h2 {
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 8px 0;
            color: #000000;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 14px;
            color: #666666;
            margin: 0;
        }

        /* --- FORMULAIRE --- */
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
        }

        /* Focus neutre, sobre et propre */
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #000000;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.05);
        }

        /* --- BOUTON SOMBRE PREMIUM --- */
        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #111111; /* Noir mat style outils développeurs */
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.15s ease;
            margin-top: 10px;
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
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="login-card">
        
        <div class="login-header">
            <h2>Connexion Administrateur</h2>
            <p>Accéder aux outils de gestion</p>
        </div>

        <?php if($erreur): ?>
            <div class="error-box">
                <?php echo $erreur; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo genererTokenCSRF(); ?>">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="adresse@exemple.com">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn-submit">Se connecter</button>
        </form>

    </div>

</body>
</html>