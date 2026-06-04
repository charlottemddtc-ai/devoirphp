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

if (!$demande) { 
    die("Demande introuvable."); 
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de <?= htmlspecialchars($demande['nom']) ?></title>
    
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

        .container {
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

        /* --- BLOC DES INFOS PROJET & CLIENT --- */
        .meta-grid {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #334155;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .meta-item {
            display: flex;
            align-items: baseline;
            line-height: 1.5;
        }

        .meta-label {
            font-weight: 600;
            color: #0f172a;
            width: 180px;
            flex-shrink: 0;
        }

        .meta-value {
            color: #111111;
        }

        .budget-tag {
            font-weight: 600;
            color: #0f172a;
        }

        h3 {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 12px;
        }

        /* --- CORPS DE LA DESCRIPTION / CAHIER DES CHARGES --- */
        .description-body {
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

    <div class="container">
        <a href="index.php" class="back-link">← Retour à la liste</a>
        
        <h2>Détail de la demande de projet</h2>

        <div class="meta-grid">
            <div class="meta-item">
                <span class="meta-label">Nom du client :</span>
                <span class="meta-value"><strong><?= htmlspecialchars($demande['nom']) ?></strong></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Adresse email :</span>
                <span class="meta-value" style="color: #64748b;"><?= htmlspecialchars($demande['email']) ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Type de projet demandé :</span>
                <span class="meta-value"><?= htmlspecialchars($demande['type_projet']) ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Budget estimé :</span>
                <span class="meta-value class budget-tag"><?= htmlspecialchars($demande['budget']) ?> FCFA</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Date de la demande :</span>
                <span class="meta-value"><?= htmlspecialchars($demande['date_demande']) ?></span>
            </div>
        </div>
        
        <h3>Cahier des charges / Description :</h3>
        <p class="description-body"><?= htmlspecialchars($demande['description']) ?></p>
    </div>

</body>
</html>