<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifierAuthentification();

// Récupération de tous les administrateurs
$admins = $db->query("SELECT id, prenom, nom, email, date_creation FROM administrateurs ORDER BY nom ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Administrateurs</title>
</head>
<body>
    <p><a href="../dashboard.php">← Retour au Dashboard</a></p>
    <h2>Liste des Administrateurs</h2>
    <a href="ajouter.php">Ajouter un administrateur</a><br><br>

    <table  cellpadding="5">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['prenom']) ?></td>
                    <td><?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= htmlspecialchars($a['email']) ?></td>
                    <td><?= $a['date_creation'] ?></td>
                    <td>
                        <a href="modifier.php?id=<?= $a['id'] ?>">Modifier</a>
                        
                        <?php if (intval($a['id']) !== intval($_SESSION['admin_id'])): ?>
                            <form action="supprimer.php" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet administrateur ?');">
                                <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">
                                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        <?php else: ?>
                            <small style="color:gray;">(Vous-même)</small>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>