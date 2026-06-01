<?php 
require_once 'fonctions.php' ; 
 require_once 'config/connexion.php' ; 
verifierAuthentification();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    verifietTokenCSRF($_POST['crsf_token']);
    $id=intval($_POST['id']);
    $stmt=$db->prepare("SELECT * FROM projects WHERE id=?");
    $stmt->execute([$id]);
    $project=$stmt->fetch();
    if($project && $project['image'] && file_exists('../../images/projects/'.$project['image'])){
        unlink('../../images/projects/'.$project['image']);
    
    }
    $stmt=$db->prepare("DELETE FROM projects WHERE id=?");
    $stmt->execute([$id]);
}
header('Location: index.php');
exit();