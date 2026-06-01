<?php
require_once 'config/connexion.php';

$prenom = "El Hadji Ahmadou Chérif"; // 
$nom = "Diouf"; // 
$email = "el.hadji.ahmadou.cherif.diouf@gmail.com"; // 
$mot_de_passe_clair = "ProfEstm2026!"; // Génère un mot de passe robuste 

$hash = password_hash($mot_de_passe_clair, PASSWORD_BCRYPT); // 

try {
    $stmt = $db->prepare("INSERT INTO administrateurs (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
    $stmt->execute([$prenom, $nom, $email, $hash]);
    
    echo "<h3>Compte professeur créé avec succès !</h3>";
    echo "Email : " . $email . "<br>";
    echo "Mot de passe : " . $mot_de_passe_clair . "<br><br>";
    echo "<strong style='color:red;'>ATTENTION : Note ces identifiants et supprime ce fichier immédiatement !</strong>"; // 
} catch (PDOException $e) {
    echo "Erreur (Le compte existe peut-être déjà) : " . $e->getMessage();
}