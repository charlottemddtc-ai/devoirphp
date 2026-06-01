
<?php 
require_once 'fonctions.php' ; 
 require_once 'config/connexion.php' ; 
verifierAuthentification();

//recuperation des compteurs
$totalProjets = $db->query('SELECT COUNT(*) FROM projets')->fetchColumn();
$messagesNonLus = $db->query('SELECT COUNT(*) FROM messages_contact WHERE lu = 0')->fetchColumn();
$demandesNonLues = $db->query('SELECT COUNT(*) FROM demandes_projet WHERE lu = 0')->fetchColumn();

//les 5 derniers visites
$dernieresVisites = $db->query('SELECT * FROM visites ORDER BY date_visite DESC LIMIT 5')->fetchAll();

//les 5 dernieres demandes
$dernieresDemandes = $db->query('SELECT * FROM demandes_projet ORDER BY date_demande DESC LIMIT 5')->fetchAll();


?>
<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>
</head>
<body>
    <h1>Tableau de bord</h1>
    <p>Bienvenue, <?php echo e($_SESSION['admin_prenom']); ?> !</p>
     <p><a href="deconnexion.php">Se déconnecter</a></p>
     <nav>
        <a href="projects/index.php">Gérer les projets</a> |
        <a href="utilisateurs/index.php">Gestion des utilisateurs</a>
        <a href="messages/index.php">Gérer les messages</a> |
        <a href="demandes/index.php">Gérer les demandes de projet</a>
     </nav>
        <h2>Statistiques</h2>
    <ul>
        <li>Total de projets : <?php echo $totalProjets; ?></li>
        <li>Messages non lus : <?php echo $messagesNonLus; ?></li>
        <li>Demandes de projet non lues : <?php echo $demandesNonLues; ?></li>
    </ul>

    <h2>Dernières visites</h2>
    <ul>
        <?php foreach($dernieresVisites as $visite): ?>
            <li><?php echo e($visite['adresse_ip']); ?> - <?php echo e($visite['page']); ?> - <?php echo e($visite['date_visite']); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Dernières demandes de projet</h2>
    <ul>
        <?php foreach($dernieresDemandes as $demande): ?>
            <li><?php echo e($demande['nom']); ?> - <?php echo e($demande['email']); ?> - <?php echo e($demande['date_demande']); ?></li>
        <?php endforeach; ?>
    </ul>