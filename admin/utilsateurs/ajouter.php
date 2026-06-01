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
    <title>Ajouter un Administrateur</title>
</head>
<body>
    <p><a href="index.php">← Retour à la liste</a></p>
    <h2>Ajouter un nouvel Administrateur</h2>
    
    <?php if($erreur): ?><p style="color:red;"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>

    <form action="" method="POST">
        <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">
        
        <label>Prénom :</label><br>
        <input type="text" name="prenom" required><br><br>
        
        <label>Nom :</label><br>
        <input type="text" name="nom" required><br><br>
        
        <label>Email :</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Mot de passe :</label><br>
        <input type="password" name="mot_de_passe" required><br><br>
        
        <button type="submit">Créer l'administrateur</button>
    </form>
</body>
</html>