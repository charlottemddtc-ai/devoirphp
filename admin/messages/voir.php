<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifierAuthentification();

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit();
}

// 1. On récupère d'abord le contenu pour vérifier s'il existe
$stmt = $db->prepare("SELECT * FROM messages_contact WHERE id = ?");
$stmt->execute([$id]);
$message = $stmt->fetch();

// Si le message n'existe pas, on arrête tout de suite
if (!$message) { 
    die("Message introuvable."); 
}

// 2. Si le message existe, ALORS on le marque automatiquement comme LU
// (Seulement s'il ne l'était pas déjà, pour économiser une requête SQL)
if ($message['lu'] == 0) {
    $update = $db->prepare("UPDATE messages_contact SET lu = 1 WHERE id = ?");
    $update->execute([$id]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message de <?= e($message['nom']) ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* --- STYLE GLOBAL ET STRUCTURE --- */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #fafafa;
            color: #111111;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .message-container {
            width: 100%;
            max-width: 650px;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
            box-sizing: border-box;
        }

        .back-link {
            display: inline-block;
            font-size: 14px;
            color: #666666;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .back-link:hover {
            color: #111111;
            text-decoration: underline;
        }

        h2 {
            font-size: 22px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 25px;
            color: #000000;
            letter-spacing: -0.5px;
        }

        /* --- METADONNÉES DU MESSAGE --- */
        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #334155;
            line-height: 1.6;
        }

        .meta-row {
            margin-bottom: 6px;
        }

        .meta-row:last-child {
            margin-bottom: 0;
        }

        .meta-label {
            font-weight: 600;
            color: #0f172a;
            display: inline-block;
            width: 60px;
        }

        .meta-email {
            color: #64748b;
        }

        h3 {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 10px;
        }

        /* --- CORPS DU MESSAGE --- */
        .message-body {
            background: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 20px;
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.6;
            color: #111111;
            font-family: inherit;
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="message-container">
        <a href="index.php" class="back-link">← Retour à la liste</a>
        
        <h2>Détail du message</h2>

        <div class="meta-box">
            <div class="meta-row">
                <span class="meta-label">De :</span>
                <strong><?= e($message['nom']) ?></strong> 
                <span class="meta-email">&lt;<?= e($message['email']) ?>&gt;</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Date :</span>
                <span><?= e($message['date_envoi']) ?></span>
            </div>
        </div>
        
        <h3>Contenu du message :</h3>
        <p class="message-body"><?= e($message['message']) ?></p>
    </div>

</body>
</html>