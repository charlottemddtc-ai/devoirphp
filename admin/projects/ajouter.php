<?php 
require_once 'fonctions.php' ; 
 require_once 'config/connexion.php' ; 
verifierAuthentification();
$erreur=null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    verifietTokenCSRF($_POST['crsf_token']);
    $titre=trim($_POST['titre']);
    $description=trim($_POST['description']);
    $technologie=trim($_POST['technologie']);
    $lien=trim($_POST['lien']);

    $nom_image=null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $exresions_autorisees=['jpg','jpeg','png','gif'];
        $info_fichier=pathinfo($_FILES['image']['name']);
        $extension=strtolower($info_fichier['extension']);
        if(in_array($extension,$exresions_autorisees)){
            $nom_image=bin2hex(random_bytes(10)).'.'.$extension;
            $dossier_destination='../../images/projects/'.$nom_image;
            move_uploaded_file($_FILES['image']['tmp_name'],$dossier_destination);
        }else{
            $erreur="Format d'image non autorisé. Seuls les formats jpg, jpeg, png et gif sont autorisés.";
    }
    }
    if(empty($erreur)&& !empty($titre) && !empty($description) && !empty($technologie)) {
        $stmt=$db->prepare("INSERT INTO projects (titre, description, technologie, lien, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $technologie, $lien, $nom_image]);
        header('Location: index.php');
        exit();
    }
}
?>
 <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="crsf_token" value="<?= genererCSRFToken() ?>">
    <input type="text" name="titre" placeholder="Titre du projet" required>
    <textarea name="description" placeholder="Description du projet" required></textarea>
    <input type="text" name="technologie" placeholder="Technologies utilisées" required>
    <input type="url" name="lien" placeholder="Lien du projet">
    <input type="file" name="image" >
    <button type="submit">Ajouter le projet</button>
</form>