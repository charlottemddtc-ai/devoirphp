<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifierAuthentification();

$id = intval($_GET['id'] ?? 0);

// Passer le statut à lu
$update = $db->prepare("UPDATE demandes_projet SET lu = 1 WHERE id = ?");
$update->execute([$id]);

$stmt = $db->prepare("SELECT * FROM demandes_projet WHERE id = ?");
$stmt->execute([$id]);
$demande = $stmt->fetch();

if (!$demande) { die("Demande introuvable."); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande de <?= htmlspecialchars($demande['nom']) ?></title>
</head>
<body>
    <p><a href="index.php">← Retour</a></p>
    <h2>Détail de la demande de projet</h2>
    <p><strong>Nom du client :</strong> <?= htmlspecialchars($demande['nom']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($demande['email']) ?></p>
    <p><strong>Type de projet demandé :</strong> <?= htmlspecialchars($demande['type_projet']) ?></p>
    <p><strong>Budget estimé :</strong> <?= htmlspecialchars($demande['budget']) ?> FCFA</p>
    <p><strong>Date de la demande :</strong> <?= $demande['date_demande'] ?></p>
    <hr>
    <h3>Cahier des charges / Description :</h3>
    <p style="background:#f4f4f4; padding:15px; white-space:pre-wrap;"><?= htmlspecialchars($demande['description']) ?></p>
</body>
</html>