<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
//protection contre les attaques XSS
function e(string $valeur): string {
    return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');  
}
//protection contre les attaques CSRF
function csrf_token(): string {
    if(empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token']; 
}
//generer un token
function genererTokenCSRF() {
   if (session_status() === PHP_SESSION_NONE) {
      session_start();
   }
   if(empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
   }
   return $_SESSION['csrf_token'];
}
//verification du token CSRF
function verifierTokenCSRF(string $token): bool {
   if(!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
      die('Token CSRF invalide');
   }
   return true;
}
//journalisation des visites
function enregistrerVisite($db,$nom_page){
   $ip = $_SERVER['REMOTE_ADDR'];
   if(!empty($_SERVEUR['HTTP_X_FORWARDED_FOR'])) {
      $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
   }
   $query=$db->prepare("INSERT INTO visites (adresse_ip, page) VALUES (?, ?)");
   $query->execute([$ip, $nom_page]);
}
//verification de la session admin
function verifierAuthentification(){
   if(!isset($_SESSION['admin_id']) ) {
      header('Location: admin/connexion.php ');
      exit;
   }
}
//validation des champs de formulaire
 function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
 }

 function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur));
 }


?>


