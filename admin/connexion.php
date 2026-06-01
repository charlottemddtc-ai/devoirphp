<?php 
require_once 'fonctions.php' ; 
 require_once 'config/connexion.php' ; 
enregistrerVisite($db,basename($_SERVER['PHP_SELF']));
if(isset($_SESSION['admin_id'])) {
   header('Location: admin/index.php');
   exit();
}

$erreur=null;
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifierTokenCSRF($_POST['csrf_token']);
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $db->prepare('SELECT * FROM administrateurs WHERE email = ?');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    //MESSAGE D'ERREUR GENERIQUE UNIFIE POUR NE PAS AIDER LES HACKERS
    if($admin && password_verify($password, $admin['mot_de_passe'])) {
        session_regenerate_id(true);
        //stocke id et le prenom
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
<html lang="en">        
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Connexion</title>

</head>
<body>
    <h2>Connexion Administrateur</h2>
    <?php if($erreur): ?>
        <p style="color: red;"><?php echo $erreur; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo genererTokenCSRF(); ?>">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>