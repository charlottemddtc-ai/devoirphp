<?php require 'fonctions.php' ;

$projects= [
    [
        'titre' =>'E-Commerce Platform',
        'description'=> 'Boutique en ligne avec payement intégre',
        'technologies' =>['React','Node'],
         'image' =>'images/image0 (11).jpeg',
    ],
    [
        'titre' =>'platforme pour algriculteur',
        'description'=> 'Aide à savoir quand est ce que yaura la pluie',
        'technologies' =>['CSS','HTML'],
         'image' =>'images/image1 (1).jpeg',
    ],
    [
        'titre' =>'Aplication pour un startup',
        'description'=> 'Application personnalisé au gout du client',
        'technologies' =>['React','Tailwind'],
         'image' =>'images/image2.jpeg',
    ],
     [
        'titre' =>'Requete client',
        'description'=> 'Une technologie basée sur des requetes client-serveur',
        'technologies' =>['SQL','C'],
         'image' =>'images/image4.jpeg',

    ]
    
 
];
$mot_cle=trim($_GET['q'] ?? "");
$resultats=[];
if($mot_cle !==""){
    foreach($projects as $p){
        if(stripos($p['titre'], $mot_cle) !==false || 
           stripos($p['description'], $mot_cle) !==false ){
            $resultats[]=$p;
           }
    }
}
$demande=$_POST ??[];
$erreurs=[];
$sucess=false;
 $demande=[
    'nom'=>"",
    'email'=>"",
    'titre_projet'=>"",
    'description'=>"",
    'type_de_projet'=>""
 ];
 if($_SERVER['REQUEST_METHOD']=='POST'){

    $demande['nom']=nettoyer($_POST['nom'] ?? "");
    $demande['email']=nettoyer($_POST['email'] ?? "");
    $demande['titre_projet']=nettoyer($_POST['titre_projet'] ?? "");
     $demande['description']=nettoyer($_POST['description'] ?? "");
      $demande['type_de_projet']=nettoyer($_POST['type_de_projet'] ?? "");
    if(!champ_requis($demande['nom'])){
        $erreurs['nom']="Nom obligatoire";
    }
    if(!filter_var($demande['email'], FILTER_VALIDATE_EMAIL)){
        $erreurs['email']="email invalige";
    }
    if(!champ_requis($demande['titre_projet'])){
        $erreurs['titre_projet']="Titre  obligatoire";
    }
    if(!champ_requis($demande['description'])){
        $erreurs['description']="description  obligatoire";
    }
    if(!champ_requis($demande['type_de_projet'])){
        $erreurs['type_de_projet']="type  obligatoire";
    }
    if(empty($erreurs)){
        $sucess=true;
    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Projects</title>
    <meta name="viewport" content="width=device-witdh,initial-scale=1.0 ">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        :root {
            --bg: #0f172a;
            --card: #1e293b;
            --text: #fff;
            --accent: #ff7a00;
        }

        body {
            background: var(--bg);
            color: var(--text);
        }

        nav {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background: #111;
        }

        nav a {
            color: white;
            margin: 0 10px;
            text-decoration:none;
        }

        .section {
            padding: 60px;
            text-align: center;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .project {
            background: var(--card);
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
        }

        .project:hover {
            transform: translateY(-10px) scale(1.02);
        }

        .project img {
            width: 100%;
        }

        .project .content {
            padding: 15px;
        }

        .btn {
            padding: 8px 15px;
            background: var(--accent);
            border: none;
            border-radius: 20px;
            margin-top: 10px;
        }

        /* BACKGROUND SECTION */
        .projects-section {
            padding: 60px;
            text-align: center;
            background-color: #111;
        }

        /* GRID */
        .projects {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            max-width:1100px;
            margin:4px auto;
            
            gap: 25px;
        }

        @media(max-width:768px) {
            .projects {
                grid-template-columns: 1fr;
            }
        }

        /* GLASS CARD */
        .project-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            overflow: hidden;
            transition: 0.4s ease;
        }

        /* IMAGE */
        .project-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .actif {
            color : #00f;
            font-weight: bold;
            border-bottom: 2px solid #00f;
        }
        /* CONTENT */
        .project-content {
            padding: 15px;
            text-align: left;
        }

        .project-content p {
            color: #39ff14;
            font-size: 14px;
        }

        /* TECH */
        .tech {
            margin: 10px 0;
        }

        .tech span {
            background: rgba(255, 255, 255, 0.08);
            padding: 5px 10px;
            border-radius: 20px;
            margin-right: 5px;
            font-size: 12px;
        }

        /* BUTTONS */
        .buttons {
            display: flex;
            gap: 10px;
        }

        .btn-dark {
            flex: 1;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            padding: 10px;
            border-radius: 8px;
            color: white;
        }

        .btn-orange {
            flex: 1;
            background: #7c3aed;
            border: none;
            padding: 10px;
            border-radius: 8px;
            color: white;
        }

        /* 🔥 HOVER PREMIUM */
        .project-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        .project-card:hover img {
            transform: scale(1.08);
        }

        .project-card img {
            transition: 0.4s;
        }
        .search-bar {
            display: flex;
            justify-content: center;
            margin: 50px auto;
            width: 100%;
            max-width: 400px;
        }

        .search-bar input {
            flex: 1;
            padding: 12px;
            border-radius: 25px 0 0 25px;
            border: 2px solid #8a2be2;
            outline: none;
            background: transparent;
            color: white;
        }

        .search-bar button {
            padding: 12px 20px;
            border-radius: 0 25px 25px 0;
            border: 2px solid #8a2be2;
            background: #8a2be2;
            color: white;
            cursor: pointer;
        }
        .form input,
        .form textarea,
        .form select {
            margin: 10px 0;
            padding: 12px;
            border: 1px solid #333;
            background: #1e1e1e;
            color: white;
            border-radius: 5px;
        }

        .form textarea {
            height: 120px;
            resize: none;
        }

        
    .contact-section {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 100px 20px;
        background: #111;
        min-height:100vh;
        padding-bottom:100px;
    }
    .body{
       background: #111; 
    }
    /* CARD */
    .contact-container {
        width: 100%;
        max-width: 550px;
        background:  #1e293b;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        border: 1px solid rgba(205, 61, 61, 0.05);
    }

    /* TITRE */
    .contact-container h2 {
        text-align: center;
        font-size: 30px;
        margin-bottom: 10px;
    }

    .subtitle {
        text-align: center;
        color: #94a3b8;
        margin-bottom: 30px;
    }

    /* INPUTS */
    .input-group {
        margin-bottom: 20px;
    }

    .contact-form input,
    .contact-form textarea,
    .contact-form select {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.08);
        background: #020617;
        color: white;
        outline: none;
        transition: 0.3s;
    }

    /* FOCUS */
    .contact-form input:focus,
    .contact-form textarea:focus,
    .contact-form select:focus {
        border-color: #00f;
        box-shadow: 0 0 15px rgba(99,102,241,0.3);
    }

    /* TEXTAREA */
    .contact-form textarea {
        min-height: 120px;
        resize: none;
    }

    /* BOTTOM */
    .form-bottom {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    /* SELECT */
    .form-bottom select {
        flex: 1;
    }

    /* BUTTON */
    .contact-form button {
        padding: 14px 20px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #00f, #39ff14);
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .contact-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    }

    /* ERROR */
    .error {
        font-size: 12px;
        color: #ef4444;
        margin-top: 5px;
    }
/* ===== FOOTER BASE ===== */
.footer {
    background: #0b0b0f;
    color: #fff;
    padding: 60px 20px 20px;
    border-top: 2px solid #7f5af0;
    box-shadow: 0 -5px 20px rgba(127, 90, 240, 0.2);
}

/* Container */
.footer-container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    justify-content: space-between;
    gap: 40px;
    flex-wrap: wrap;
}

/* Sections */
.footer-section {
    flex: 1;
    min-width: 250px;
}

/* Titres */
.footer-section h2 {
    color: #7f5af0;
    margin-bottom: 10px;
}

.footer-section h3 {
    color: #2ecc71;
    margin-bottom: 15px;
}

/* Texte */
.footer-section p {
    color: #ccc;
    line-height: 1.6;
}

/* Liste */
.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

/* Liens */
.footer-section ul li a {
    text-decoration: none;
    color: #ccc;
    transition: 0.3s;
}

.footer-section ul li a:hover {
    color: #2ecc71;
    padding-left: 5px;
}

/* Bas du footer */
.footer-bottom {
    text-align: center;
    margin-top: 40px;
    border-top: 1px solid #222;
    padding-top: 15px;
    color: #888;
    font-size: 14px;
}

/* ===== RESPONSIVE ===== */

/* 📱 Mobile */
@media (max-width: 768px) {
    .footer-container {
        flex-direction: column;
        text-align: center;
        gap: 30px;
    }

    .footer-section {
        min-width: 100%;
    }

    .footer-section ul {
        padding: 0;
    }
}

/* 📲 Très petits écrans */
@media (max-width: 480px) {
    .footer {
        padding: 40px 15px 15px;
    }

    .footer-section h2 {
        font-size: 20px;
    }

    .footer-section h3 {
        font-size: 16px;
    }

    .footer-bottom {
        font-size: 12px;
    }
}

/* 💻 Tablette */
@media (max-width: 992px) {
    .footer-container {
        justify-content: center;
    }
}



    </style>
</head>

<body>

    <?php require 'composants/navigation.php'; ?>
    <section class="projects-section">
        <h2>Tous les Projets</h2>

         <section class="section">
        <form class="search-bar" action="" method="GET">
            <input type="text" name="q" placeholder="Rechercher un projet..." value=" <?= $mot_cle ?>">
            <button type="submit">🔍</button>
        </form>
    
        <?php if(!$mot_cle !=="") ;?>
          <h2>resultats pour:<?= htmlspecialchars($mot_cle) ?> </h2>
          <div class="projects">
             <?php foreach ($resultats as $p ) :?> 

                 <div class="project-card">
                      <img src="<?= htmlspecialchars($p['image'] ?? "") ?>">
                       <div class="project-content">
                        <h3><?= htmlspecialchars($p['titre']) ?? "" ?></h3>
                        <p><?= htmlspecialchars($p['description']) ?? "" ?></p>
                        <div class="buttons">
                            <button class="btn-dark">Code</button>
                            <button class="btn-orange">Demo</button>
                        </div>
                    </div>
        
                 </div>

                        
             <?php endforeach ;?>
         </div>
        
           
        
        
            

  
    </section>
        <div class="projects">

            <!-- PROJECT 1 -->
            
                
         
           <?php foreach ($projects as $projet):?>
                   
                <div class="project-card">
                    <img src="<?= htmlspecialchars($projet['image'] ?? "") ?>">
                    <div class="project-content">
                        <h3><?= htmlspecialchars($projet['titre']) ?? "" ?></h3>
                        <p><?= htmlspecialchars($projet['description']) ?? "" ?></p>
                          
                        <div class="tech">
                            <?php foreach ($projet['technologies'] as $tech): ?>
                            <span><?= htmlspecialchars($tech) ?></span>
                            <?php endforeach ; ?>
                        </div>

                        <div class="buttons">
                            <button class="btn-dark">Code</button>
                            <button class="btn-orange">Demo</button>
                        </div>
                    </div>
                </div>
            <?php endforeach ; ?>
            
           

           

        </div>
    </section>
    <section class="contact-section">
      <div class="contact-container">
        <h2>Demande de projet</h2>
         <p class="subtitle">Tu as une idée ? Décris ton projet </p>
        <form class="contact-form" method="POST">

         <div class="input-group">
            <input type="text" placeholder="Nom:" name="nom" value=" <?=htmlspecialchars($demande['nom'])  ?> " >
            <p class="error"><?= $erreurs['nom']  ?? "" ?></p>
         </div>
         <div class="input-group">
            <input type="email" placeholder= "Email:" name="email" value=" <?= $demande['email'] ?? ''?>" >
            <p class="error"><?= $erreurs['email']  ?? "" ?></p>
         </div>
         <div class="input-group">
            <input type="text" placeholder=" Titre de Project :" name="titre_projet" value=" <?= htmlspecialchars(trim($demande['titre_projet'] ?? '')) ?>" >
            <p class="error"><?= $erreurs['titre_projet']  ?? "" ?></p>
         </div>
         <div class="input-group">
            <textarea placeholder="Decris ton projet (features, budget, durée...)" name="description"><?=htmlspecialchars($demande['description'] ?? '') ?></textarea>
             <p class="error"><?= $erreurs['description']  ?? "" ?></p>
          </div>
        <div class="form-bottom">
            <select  name="type_de_projet">
                <option value=""> Type de Projet </option>
                <option value="website" <?= ($demande['type_de_projet'] ?? '' )==='website'? 'selected' :''?>>Website</option>
                <option value="mobile app" <?= ($demande['type_de_projet']?? '' )==='mobile app'? 'selected' :''?>>>Mobile App</option>
                <option value="Backend API" <?=( $demande['type_de_projet']  ?? '')==='Backend API'? 'selected' :''?>>>Backend API</option>
            </select>
          

            <button type="submit">Envoyer la requete</button>
            </div>
         </form>
         <?php if ($sucess): ?>
            <p class="success">Demande envoyé</p>
            <p><strong>Nom:</strong><?= $demande['nom']?> </p>
            <p><strong>email:</strong><?= $demande['email']?> </p>
            <p><strong>Titre:</strong><?= $demande['titre_projet']?> </p>
            <p><strong>Description:</strong><?= $demande['description']?> </p>
            <p><strong>type:</strong><?= $demande['type_de_projet']?> </p>
            <?php endif; ?>
     </div>

    </section>

    <?php require 'composants/piedpage.php'; ?>

</body>

</html>