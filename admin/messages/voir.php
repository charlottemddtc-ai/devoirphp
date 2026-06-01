<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifierAuthentification();

$id = intval($_GET['id'] ?? 0);

// Marquer automatiquement le message comme LU
$update = $db->prepare("UPDATE messages_contact SET lu = 1 WHERE id = ?");
$update->execute([$id]);

// Récupérer le contenu
$stmt = $db->prepare("SELECT * FROM messages_contact WHERE id = ?");
$stmt->execute([$id]);
$message = $stmt->fetch();

if (!$message) { die("Message introuvable."); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Message de <?= htmlspecialchars($message['nom']) ?></title>
</head>
<body>
    <p><a href="index.php">← Retour à la liste</a></p>
    <h2>Détail du message</h2>
    <p><strong>De :</strong> <?= htmlspecialchars($message['nom']) ?> (<?= htmlspecialchars($message['email']) ?>)</p>
    <p><strong>Date :</strong> <?= $message['date_envoi'] ?></p>
    <hr>
    <h3>Message :</h3>
    <p style="background:#f4f4f4; padding:15px; white-space:pre-wrap;"><?= htmlspecialchars($message['message']) ?></p>
</body>
</html>