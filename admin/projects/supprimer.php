<<?php 
// 1. DÉMARRAGE DE LA SESSION (Obligatoire pour vérifier le jeton CSRF)
session_start(); 

require_once '../../fonctions.php'; 
require_once '../../config/connexion.php'; 

// Sécurité admin
verifierAuthentification();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CORRECTION : Orthographe de la fonction et du paramètre POST
    verifierTokenCSRF($_POST['csrf_token'] ?? '');
    
    $id = intval($_POST['id'] ?? 0);
    
    // 2. RÉCUPÉRATION DU PROJET POUR VÉRIFIER L'IMAGE
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    
    if ($project) {
        // CORRECTION : Utilisation du bon dossier "projets"
        $chemin_image = '../../images/projects/' . $project['image'];
        
        // Si le projet a une image et qu'elle existe physiquement sur le serveur, on la supprime
        if (!empty($project['image']) && file_exists($chemin_image)) {
            unlink($chemin_image);
        }
        
        // 3. SUPPRESSION DU PROJET EN BDD
        $stmt = $db->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// CORRECTION : Redirection avec point-virgule obligatoire
header('Location: index.php');
exit();