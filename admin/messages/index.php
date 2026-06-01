<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifierAuthentification();

$messages = $db->query("SELECT * FROM messages_contact ORDER BY date_envoi DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages reçus</title>
</head>
<body>
    <p><a href="../dashboard.php">← Retour au Dashboard</a></p>
    <h2>Messages de contact</h2>

    <table  cellpadding="5">
        <tr>
            <th>Statut</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($messages as $m): ?>
            <tr style="<?= $m['lu'] == 0 ? 'font-weight:bold; background-color:#f9f9f9;' : '' ?>">
                <td><?= $m['lu'] == 1 ? 'Lu' : 'Non lu' ?></td>
                <td><?= htmlspecialchars($m['nom']) ?></td>
                <td><?= htmlspecialchars($m['email']) ?></td>
                <td><?= $m['date_envoi'] ?></td>
                <td><a href="voir.php?id=<?= $m['id'] ?>">Ouvrir / Lire</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>