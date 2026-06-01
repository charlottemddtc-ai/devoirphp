<?php 
require_once 'fonctions.php' ; 
 require_once 'config/connexion.php' ; 
verifierAuthentification();
$id=intval($_GET['id'] ?? 0);
$stmt=$db->prepare("SELECT * FROM administrateurs WHERE id=?");
$stmt->execute([$id]);
$admin=$stmt->fetch();
if(!$admin){
    die("Administrateur non trouvé");
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    verifietTokenCSRF($_POST['crsf_token']);
    $nom=trim($_POST['nom'] ?? '');
    $email=trim($_POST['email'] ?? '');
    $nouveau_password=$_POST['password'] ?? '';

    if(!empty($nouveau_password)){
        $password_hash=password_hash($nouveau_password,PASSWORD_BCRYPT);
        
}else{
    $password_hash=$admin['password'];
}
    $stmt=$db->prepare("UPDATE administrateurs SET nom=?, email=?, password=? WHERE id=?");
    $stmt->execute([$nom, $email, $password_hash, $id]);
    header('Location: index.php');
    exit();
}
?>