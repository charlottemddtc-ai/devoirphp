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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Administrateurs</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- CORE STYLES --- */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR IDENTIQUE ET COHÉRENTE --- */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #94a3b8;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            padding-top: 30px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 100;
        }
        .sidebar-brand {
            text-align: center; font-size: 18px; font-weight: 700; letter-spacing: 0.5px;
            padding-bottom: 25px; border-bottom: 1px solid #334155; margin: 0 20px 25px 20px; color: #38bdf8;
        }
        .sidebar-brand i { margin-right: 8px; }
        .sidebar-menu { display: flex; flex-direction: column; padding: 0 10px; }
        .sidebar-menu a {
            display: flex; align-items: center; color: #94a3b8; padding: 14px 20px;
            text-decoration: none; font-weight: 500; font-size: 15px; margin-bottom: 5px; border-radius: 8px; transition: all 0.2s ease;
        }
        .sidebar-menu a i { margin-right: 12px; font-size: 16px; width: 20px; text-align: center; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: rgba(56, 189, 248, 0.1); color: #38bdf8; font-weight: 600; }
        .sidebar-menu a.active { background-color: #38bdf8; color: #0f172a; }
        .sidebar-menu a.logout { color: #f87171; margin-top: 50px; }
        .sidebar-menu a.logout:hover { background-color: rgba(248, 113, 113, 0.1); color: #f87171; }

        /* --- ZONE DE CONTENU PRINCIPALE --- */
        .main-content {
            margin-left: 260px;
            padding: 40px;
            width: calc(100% - 260px);
            box-sizing: border-box;
        }

        .welcome-header {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;
            background: white; padding: 24px 30px; border-radius: 16px; border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .welcome-header h1 { margin: 0; font-size: 24px; font-weight: 700; color: #0f172a; }

        /* Bouton Ajouter Neutre Noir Mat */
        .btn-ajouter {
            background-color: #111111;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.15s ease;
        }
        .btn-ajouter:hover {
            background-color: #222222;
        }

        /* --- TABLEAU DES ADMINISTRATEURS --- */
        .panel {
            background: white; padding: 30px; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { background-color: #f8fafc; color: #475569; font-weight: 600; font-size: 12px; padding: 14px 20px; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 16px 20px; font-size: 14px; color: #334155; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #f8fafc; }

        /* Actions et boutons du tableau */
        .btn-modifier { color: #16a34a; text-decoration: none; font-weight: 600; margin-right: 15px; font-size: 14px; }
        .btn-modifier:hover { text-decoration: underline; }
        
        .btn-supprimer {
            background: none; color: #ef4444; border: none; padding: 0; cursor: pointer; font-weight: 600; font-family: inherit; font-size: 14px;
        }
        .btn-supprimer:hover { text-decoration: underline; }
        
        .self-text {
            font-size: 13px; color: #94a3b8; font-style: italic; font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-user-shield"></i> Administration
        </div>
        <nav class="sidebar-menu">
            <a href="../dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a href="../projects/index.php"><i class="fa-solid fa-folder-open"></i> Gérer les Projets</a>
            <a href="index.php" class="active"><i class="fa-solid fa-users"></i> Gérer les Utilisateurs</a>
            <a href="../messages/index.php"><i class="fa-solid fa-envelope"></i> Messages Reçus</a>
            <a href="../demandes/index.php"><i class="fa-solid fa-handshake"></i> Demandes Projet</a>
            <a href="../deconnexion.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
        </nav>
    </div>

    <div class="main-content">
        
        <div class="welcome-header">
            <div>
                <h1>Liste des Administrateurs</h1>
                <p style="margin: 5px 0 0 0; color: #64748b; font-size: 14px;">Gérez les accès et les comptes disposant des droits d'administration</p>
            </div>
            <a href="ajouter.php" class="btn-ajouter"><i class="fa-solid fa-user-plus"></i> Ajouter un administrateur</a>
        </div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date de création</th>
                        <th style="width: 150px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['prenom']) ?></td>
                            <td><?= htmlspecialchars($a['nom']) ?></td>
                            <td><?= htmlspecialchars($a['email']) ?></td>
                            <td><?= htmlspecialchars($a['date_creation']) ?></td>
                            <td style="text-align: center;">
                                <a href="modifier.php?id=<?= $a['id'] ?>" class="btn-modifier">Modifier</a>
                                
                                <?php if (intval($a['id']) !== intval($_SESSION['admin_id'])): ?>
                                    <form action="supprimer.php" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet administrateur ?');">
                                        <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">
                                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                        <button type="submit" class="btn-supprimer">Supprimer</button>
                                    </form>
                                <?php else: ?>
                                    <span class="self-text">(Vous-même)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
    </div>

</body>
</html>