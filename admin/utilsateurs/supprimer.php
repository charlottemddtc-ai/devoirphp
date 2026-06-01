<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifierAuthentification();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifierTokenCSRF($_POST['csrf_token'] ?? '');
    
    $id_a_supprimer = intval($_POST['id'] ?? 0);

    // Règle stricte : interdiction de se supprimer soi-même 
    if ($id_a_supprimer === intval($_SESSION['admin_id'])) {
        die("Erreur : Vous ne pouvez pas supprimer votre propre compte administrateur.");
    }

    $stmt = $db->prepare("DELETE FROM administrateurs WHERE id = ?");
    $stmt->execute([$id_a_supprimer]);
}
header('Location: index.php');
exit();