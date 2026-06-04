<?php
session_start();
require_once '../config/connexion.php';
require_once '../fonctions.php';

// Sécurité admin : vérifie si l'utilisateur est bien connecté

verifierAuthentification();

try {
    // --- RÉCUPÉRATION DES STATISTIQUES POUR LES CARTES ---
    // 1. Nombre total de projets
    $total_projets = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();

    // 2. Messages de contact non lus
    $messages_non_lus = $db->query("SELECT COUNT(*) FROM messages_contact WHERE lu = 0")->fetchColumn();

    // 3. Demandes de projet non lues
   $demandes_non_lues = $db->query("SELECT COUNT(*) FROM demandes_projet WHERE lu = 0")->fetchColumn();

    // --- RÉCUPÉRATION DES 5 DERNIÈRES VISITES (Exigence de traçabilité) ---
    $visites = $db->query("SELECT * FROM visites ORDER BY date_visite DESC LIMIT 5")->fetchAll();

    $dernieres_demandes = $db->query("SELECT * FROM demandes_projet ORDER BY date_demande DESC LIMIT 5")->fetchAll();

} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Une erreur est survenue lors du chargement des données du tableau de bord.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Administration Portfolio</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- CORE STYLES --- */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* Fond gris très clair ultra moderne */
            color: #0f172a;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR (BARRE LATÉRALE) --- */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #94a3b8;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding-top: 30px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 100;
        }
        
        .sidebar-brand {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding-bottom: 25px;
            border-bottom: 1px solid #334155;
            margin: 0 20px 25px 20px;
            color: #38bdf8; /* Couleur d'accentuation bleue */
        }
        
        .sidebar-brand i {
            margin-right: 8px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            padding: 0 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: #94a3b8;
            padding: 14px 20px;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .sidebar-menu a i {
            margin-right: 12px;
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
            font-weight: 600;
        }

        .sidebar-menu a.active {
            background-color: #38bdf8;
            color: #0f172a;
        }

        .sidebar-menu a.logout {
            color: #f87171;
            margin-top: 50px;
            border: 1px solid transparent;
        }

        .sidebar-menu a.logout:hover {
            background-color: rgba(248, 113, 113, 0.1);
            color: #f87171;
            border-color: rgba(248, 113, 113, 0.2);
        }

        /* --- MAIN CONTENT WINDOW --- */
        .main-content {
            margin-left: 260px; /* Évite le chevauchement avec la barre fixe */
            padding: 40px;
            width: calc(100% - 260px);
            box-sizing: border-box;
        }

        /* En-tête de bienvenue */
        .welcome-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            background: white;
            padding: 24px 30px;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.04);
            border: 1px solid #f1f5f9;
        }

        .welcome-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }

        .welcome-header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #22c55e;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        /* --- STATS GRID & CARDS --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #f1f5f9;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.05);
        }

        .card-data h4 {
            margin: 0 0 6px 0;
            color: #64748b;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        .card-data .number {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* Thèmes de couleur individuels des blocs de stats */
        .card.blue .card-icon { background-color: #e0f2fe; color: #0284c7; }
        .card.orange .card-icon { background-color: #ffedd5; color: #ea580c; }
        .card.green .card-icon { background-color: #dcfce7; color: #16a34a; }

        /* --- PANEL TABLEAU --- */
        .panel {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
        }

        .panel h3 {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            display: flex;
            align-items: center;
        }

        .panel h3 i {
            margin-right: 10px;
            color: #64748b;
        }

        /* --- TABLE STYLE --- */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 12px;
            padding: 14px 20px;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px 20px;
            font-size: 14px;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:last-child td {
            border-bottom: none; /* Supprime la bordure sur la dernière ligne */
        }

        tr:hover td {
            background-color: #f8fafc; /* Effet de survol discret sur les lignes */
        }

        /* Badge IP */
        .ip-badge {
            background-color: #f1f5f9;
            color: #334155;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Courier New', Courier, monospace;
            border: 1px solid #e2e8f0;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-user-shield"></i> Administration
        </div>
        <nav class="sidebar-menu">
            <a href="dashboard.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a href="projects/index.php"><i class="fa-solid fa-folder-open"></i> Gérer les Projets</a>
            <a href="messages/index.php"><i class="fa-solid fa-envelope"></i> Messages Reçus</a>
            <a href="utilsateurs/index.php"><i class="fa-solid fa-users"></i> Gérer les Utilisateurs</a>
            <a href="demandes/index.php"><i class="fa-solid fa-handshake"></i> Demandes Projet</a>
            <a href="deconnexion.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
        </nav>
    </div>

    <div class="main-content">
        
        <div class="welcome-header">
            <div>
                <h1>Tableau de bord général</h1>
                <p><span class="status-dot"></span> Connecté en tant que <strong>Monsieur Diouf / Admin</strong></p>
            </div>
            <div style="color: #94a3b8; font-size: 14px;">
                <i class="fa-regular fa-calendar-days"></i> <?php echo date('d/m/Y'); ?>
            </div>
        </div>

        <div class="stats-grid">
            
            <div class="card blue">
                <div class="card-data">
                    <h4>Total Projets</h4>
                    <div class="number"><?= intval($total_projets) ?></div>
                </div>
                <div class="card-icon">
                    <i class="fa-solid fa-diagram-project"></i>
                </div>
            </div>
            
            <div class="card orange">
                <div class="card-data">
                    <h4>Messages Non Lus</h4>
                    <div class="number"><?= intval($messages_non_lus) ?></div>
                </div>
                <div class="card-icon">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
            </div>

            <div class="card green">
                <div class="card-data">
                    <h4>Demandes non lues</h4>
                    <div class="number"><?= intval($demandes_non_lues) ?></div>
                </div>
                <div class="card-icon">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
            </div>
            
        </div>

        <div class="panels-container" style="display: flex; flex-direction: column; gap: 30px;">
            
            <div class="panel">
                <h3><i class="fa-solid fa-handshake"></i> Les 5 dernières demandes de projet</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nom Client</th>
                            <th>Type de Projet</th>
                            <th>Budget Estimé</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dernieres_demandes as $d): ?>
                            <tr>
                                <td><strong><?= e($d['nom_client'] ?? $d['nom'] ?? 'Client') ?></strong></td>
                                <td><?= e($d['type_projet'] ?? 'Non spécifié') ?></td>
                                <td><span class="ip-badge"><?= e($d['budget'] ?? 'Non défini') ?></span></td>
                                <td><i class="fa-regular fa-calendar" style="color:#94a3b8; margin-right:6px;"></i> <?= e($d['date_demande'] ?? $d['date_creation'] ?? '') ?></td>
                                <td>
                                    <?php if (isset($d['lu']) && $d['lu'] == 0): ?>
                                        <span class="status-badge unread">Non lu</span>
                                    <?php else: ?>
                                        <span class="status-badge read">Lu</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($dernieres_demandes)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #94a3b8; font-style: italic; padding: 20px;">Aucune demande reçue.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <h3><i class="fa-solid fa-chart-line"></i> Historique des 5 dernières visites</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Adresse IP</th>
                            <th>Page Consultée</th>
                            <th>Date & Heure de passage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visites as $v): ?>
                            <tr>
                                <td><span class="ip-badge"><?= e($v['adresse_ip']) ?></span></td>
                                <td><i class="fa-solid fa-link" style="color:#94a3b8; margin-right:8px;"></i><?= e($v['page']) ?></td>
                                <td><i class="fa-regular fa-clock" style="color:#94a3b8; margin-right:8px;"></i><?= e($v['date_visite']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($visites)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #94a3b8; font-style: italic; padding: 20px;">Aucune visite enregistrée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
        
    </div>

</body>
</html>