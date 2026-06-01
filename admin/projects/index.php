<?php 
require_once '../../fonctions.php' ; 
 require_once '../../config/connexion.php' ; 
verifierAuthentification();

$projects=$db->query("SELECT * FROM projects ORDER BY date_creation DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de projets</title>
</head>
<body>
    <h2>Liste de projets</h2>
    <a href="ajouter.php">Ajouter un projet</a>
    <table>
        <tr><th>Image</th><th>titre</th><th>Technologie</th><th>Action</th></tr>
        <?php foreach($projects as $project): ?>
            <tr>
                <td><img src="../../images/projects/<?php echo e($project['image']); ?>"  width="80"></td>
                <td><?php echo e($project['titre']); ?></td>
                <td><?php echo e($project['technologie']); ?></td>
                <td>
                    <a href="modifier.php?id=<?php echo $project['id']; ?>">Modifier</a>
                    <form action="supprimer.php" method="POST" style="display: inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer ce projet ?')">
                        <input type="hidden" name="crsf_token" value="<?= genererCSRFToken() ?>">
                        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>