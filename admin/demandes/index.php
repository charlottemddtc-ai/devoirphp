<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifierAuthentification();

$demandes = $db->query("SELECT * FROM demandes_projet ORDER BY date_demande DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes de projet</title>
</head>
<body>
    <p><a href="../dashboard.php">← Retour au Dashboard</a></p>
    <h2>Demandes de devis / projets</h2>

    <table  cellpadding="5">
        <tr>
            <th>Statut</th>
            <th>Client</th>
            <th>Type de Projet</th>
            <th>Budget</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($demandes as $d): ?>
            <tr style="<?= $d['lu'] == 0 ? 'font-weight:bold;' : '' ?>">
                <td><?= $d['lu'] == 1 ? 'Traité' : 'Nouveau' ?></td>
                <td><?= htmlspecialchars($d['nom']) ?></td>
                <td><?= htmlspecialchars($d['type_projet']) ?></td>
                <td><?= htmlspecialchars($d['budget']) ?> FCFA</td>
                <td><?= $d['date_demande'] ?></td>
                <td><a href="voir.php?id=<?= $d['id'] ?>">Consulter</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>