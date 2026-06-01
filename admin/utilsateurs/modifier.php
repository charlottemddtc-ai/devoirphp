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
    <title>Modifier l'Administrateur</title>
</head>
<body>
    <p><a href="index.php">← Retour</a></p>
    <h2>Modifier l'administrateur : <?= htmlspecialchars($admin['prenom']) ?></h2>

    <?php if($erreur): ?><p style="color:red;"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>

    <form action="" method="POST">
        <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">
        
        <label>Prénom :</label><br>
        <input type="text" name="prenom" value="<?= htmlspecialchars($admin['prenom']) ?>" required><br><br>
        
        <label>Nom :</label><br>
        <input type="text" name="nom" value="<?= htmlspecialchars($admin['nom']) ?>" required><br><br>
        
        <label>Email :</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required><br><br>
        
        <label>Mot de passe (Laissez vide pour ne pas modifier) :</label><br>
        <input type="password" name="mot_de_passe"><br><br>
        
        <button type="submit">Enregistrer les modifications</button>
    </form>
</body>
</html>