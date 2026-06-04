<?php 
session_start();
require_once 'fonctions.php' ; 
 require_once 'config/connexion.php' ; 
enregistrerVisite($db,basename($_SERVER['PHP_SELF']));

?>
<?php 
//tableau qui contiendra les erreurs
$erreurs =[];
//recuperation des valeurs envoyées,s'il y'a pas de valeur on met rien
$nom=$_POST['nom'] ?? '';
$email=$_POST['email'] ?? '';
$message=$_POST['message'] ?? '';
//variable pour savoir si l'envoie est reussi
$sucess=false;
//le traitement commence apres l'envoie
if($_SERVER['REQUEST_METHOD']=='POST'){
   
    
//nettoyage
    $nom=nettoyer($_POST['nom']);
     
    $email=nettoyer($_POST['email'] );
    $message=nettoyer($_POST['message'] );
//verification des champs s'ils sont vide ou pas
    if(!champ_requis($nom)){
        $erreurs['nom']="Nom obligatoire";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erreurs['email']="email invalide";
    }
    if(!champ_requis($message)){
        $erreurs['message']="Message obligatoire obligatoire";
    }
    
//s'il y'a pas d'erreurs
    if(empty($erreurs)){
        $sql="INSERT INTO messages_contact (nom,email,message,lu,date_envoi) VALUES (?,?,?,?,NOW())";
        $stmt =$db->prepare($sql);
        
        $stmt->execute([$nom,$email,$message,0]);
        
        $sucess=true;
        $nom="";
        $email="";
        $message="";
    }

}










?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-witdh,initial-scale=1.0 ">
    <title>Charlotte Portfolio</title>

    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
            transition: 0.3s;
        }

        /* THEMES */
        :root {
            --bg: #000000;
            --card: #1e293b;
            --text: #fff;
            --accent: #7c3aed;
        }

        .light {
            --bg: #f3f4f6;
            --card: #ffffff;
            --text: #111;
        }

        /* BODY */
        body {
            background: var(--bg);
            color: var(--text);
        }

        /* NAV */
        nav {
            display: flex;
            justify-content: space-between;
            align-items:center;
            padding: 20px 50px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        nav a {
            color: var(--text);
            text-decoration: none;
            margin: 0 10px;
        }

        .toggle {
            cursor: pointer;

        }


        /* HERO */
        .hero {
            display: flex;
            align-items: flex-start;
            text-align: center;
            justify-content: space-between;
            padding: 120px 80px;
            min-height: 100vh;

        }

        .hero-text {
            max-width: 500px;
            margin-top: 40px;
            animation: fadeUp 1s ease;
        }

        .hero h1 {
            font-size: 56px;
            line-height: 1.2;
            margin-bottom: 20px;

        }

        .hero p {
            margin: 15px 0;
            opacity: 0.7;

        }

        .btn {
            padding: 12px 25px;
            border-radius: 30px;
            background: var(--accent);
            color: #fff;
            border: none;
            cursor: pointer;
            margin-right: 10px;
            margin-top: 10px;
            text-decoration: none;

        }

        .btn-outline {
            border: 2px solid var(--accent);
            background: none;
        }

        /* IMAGE */
        .hero img {

            margin-top: 20px;
            width: 320px;
            animation: float 4s infinite ease-in-out;
        }

        @media (max-width: 768px) {

            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero h1 {
                font-size: 28px;
            }

            .hero-btn {
                flex-direction: column;
                gap: 20px;
                width: 100%;
                align-items: center;
            }

            .btn {

                width: 80%;
                text-align: center;
            }

            .hero-btn a {
                width: 80%;
            }

            .hero img {
                width: 80%;
                margin-top: 20px;
            }
        }

        /* ABOUT */
        .section {
            padding: 60px;
        }

        .about {
            display: flex;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .about-box {
            background: var(--card);
            padding: 20px;
            border-radius: 15px;
        }

        /* SKILLS */
        .skills {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .skill {
            background: var(--card);
            padding: 20px;
            border-radius: 15px;
        }

        .bar {
            height: 6px;
            background: #333;
            border-radius: 10px;
            margin-top: 10px;
        }

        .bar span {
            display: block;
            height: 100%;
            background: var(--accent);
        }

        /* PROJECTS */
        .projects {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .project {
            background: #1a1a1a;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.4s;
        }

        .project:hover {
            transform: translateY(-10px);
        }

        /* CONTACT */
        form {
            display: grid;
            gap: 10px;
            max-width: 400px;
        }

        input,
        textarea {
            padding: 10px;
            border: none;
            border-radius: 10px;
            background: var(--card);
            color: var(--text);
        }

        /* ANIMATIONS */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        /* RESPONSIVE */
        @media(max-width:768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            .about {
                flex-direction: column;
            }
        }

        /* SECTION */
        .skills-section {
            padding: 60px;
            text-align: center;
        }

        .subtitle {
            color: #aaa;
            margin-bottom: 30px;
        }

        /* GRID */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        /* CARD */
        .skill-card {
            background: #1e293b;
            padding: 20px;
            border-radius: 15px;
            text-align: left;
            transition: 0.3s;
        }

        .skill-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 0 20px rgba(255, 122, 0, 0.3);
        }

        /* HEADER */
        .skill-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .skill-header img {
            width: 30px;
        }

        /* PROGRESS */
        .progress {
            background: #000000;
            height: 6px;
            border-radius: 10px;
        }

        .bar {
            height: 100%;
            border-radius: 10px;
            animation: load 1.5s ease;
        }

        /* COLORS */
        .laravel {
            width: 95%;
            background: #ff2d20;
        }

        .react {
            width: 85%;
            background: #61dafb;
        }

        .js {
            width: 90%;
            background: #f7df1e;
        }

        .python {
            width: 80%;
            background: #3776ab;
        }

        /* ANIMATION */
        @keyframes load {
            from {
                width: 0;
            }
        }

        /* NOUVELLES BARRES */

        .java {
            width: 75%;
            background: #f89820;
        }

        .html {
            width: 95%;
            background: #e34c26;
        }

        .css {
            width: 90%;
            background: #264de4;
        }

        .tailwind {
            width: 85%;
            background: #38bdf8;
        }

        /* GRID */
        .projects {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        /* CARD */
        .project-card {
            background: #0e1116;
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: 0.4s ease;
        }

        /* IMAGE */
        .project-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        /* CONTENT */
        .project-content {
            padding: 15px;
        }

        .project-content h3 {
            margin-bottom: 5px;
        }

        .project-content p {
            font-size: 13px;
            color: #aaa;
        }

        /* TECH TAGS */
        .tech {
            margin: 10px 0;
        }

        .tech span {
            background: #1e293b;
            padding: 5px 10px;
            border-radius: 20px;
            margin-right: 5px;
            font-size: 12px;
        }

        /* BUTTONS */
        .buttons {
            display: flex;
            justify-content: center;

            gap: 10px;
            margin-top: 10px;
        }

        .btn-dark {
            background: #1e293b;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-orange {
            flex: 1;
            background: #ff7a00;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
        }

        /* 🔥 HOVER PROPRE */
        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
        }

        .project-card:hover img {
            transform: scale(1.05);
        }

        .project-card img {
            transition: 0.4s;
        }

        /* SECTION */
        .about {
            padding: 80px 10%;
        }

        /* LAYOUT */
        .about-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
            flex-wrap: wrap;
        }

        /* IMAGE */
        .about-img img {
            width: 350px;
            border-radius: 20px;
            object-fit: cover;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        /* TEXT */
        .about-text {
            max-width: 500px;
        }

        .about-text h2 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .about-text h2 span {
            color: #ff7a00;
        }

        .about-text p {
            color: #bbb;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        /* STATS */
        .about-stats {
            display: flex;
            gap: 30px;
            margin: 20px 0;
        }

        .about-stats h3 {
            color: #ff7a00;
            font-size: 22px;
        }

        .about-stats span {
            font-size: 12px;
            color: #aaa;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
            }
            
        }

        @media (max-width: 768px) {
            .hero-buttons {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
        }

        .about-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .about-content {
                flex-direction: column;
                text-align: center;
            }

            .about-content img {
                width: 80%;
                margin: auto;
            }
        }

        @media (max-width: 768px) {
            section {
                padding: 40px 20px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }
        }

        /* BUTTON */
        .learn-btn {
            padding: 10px 20px;
            border: 2px solid #ff7a00;
            background: transparent;
            color: #fff;
            border-radius: 25px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .learn-btn:hover {
            background: #ff7a00;
            color: #000;
        }

        .contact-section {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;

            background: #0a0a0a;
        }

        /* CONTAINER */
        .contact-wrapper {
            width: 400px;
            padding: 40px;
            border-radius: 20px;

            background: rgb(0, 0, 0);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);

            border: 1px solid rgba(255, 255, 255, 0.1);

            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.6),
                inset 0 0 20px rgba(255, 255, 255, 0.05);

            text-align: center;
        }

        /* TITLE */
        .contact-wrapper h2 {
            color: white;
            margin-bottom: 20px;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            margin: 10px 0;
            padding: 14px;

            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);

            background: rgba(255, 255, 255, 0.05);
            color: white;

            outline: none;
            transition: 0.3s;
        }
        

        /* FOCUS ANIMATION */
        .contact-form input:focus,
        .contact-form textarea:focus {
            border: 1px solid #39ff14;
            box-shadow: 0 0 10px rgba(57, 255, 20, 0.4);
        }

        .contact-form button {
            width: 100%;
            margin-top: 15px;
            padding: 14px;

            border: none;
            border-radius: 12px;

            background: linear-gradient(90deg, #39ff14, #00ff88);
            color: black;
            font-weight: bold;

            cursor: pointer;
            transition: 0.3s;
        }

        .contact-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px #39ff14;
        }

        .contact-form button {
            width: 100%;
            margin-top: 15px;
            padding: 14px;

            border: none;
            border-radius: 12px;

            background: linear-gradient(90deg, #39ff14, #00ff88);
            color: black;
            font-weight: bold;

            cursor: pointer;
            transition: 0.3s;
        }

        .contact-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px #39ff14;
        }

        .fade-up {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease;
        }

        .fade-up.show {
            opacity: 1;
            transform: translateY(0);
        }

        .section-divider {
            width: 80%;
            height: 2px;
            margin: 80px auto;
            background: linear-gradient(to right, #39ff14, #8a2be2);
            animation: dividerAppear 1.5s ease forwards;
        }
 @keyframes dividerAppear {
            from{
                transform:scaleX(0);
                opacity:0;
            }
            to{
                transform:scaleX(1);
                opacity:1;
            }
        }
        

        h2 {
            margin-bottom: 30px;
        }

        /* FORM */
        .form {
            max-width: 500px;
            margin: auto;
            display: flex;
            flex-direction: column;
        }

        

        /* SEARCH */
        #search {
            width: 60%;
            padding: 10px;
            margin-bottom: 20px;
            background: #111;
            border: 1px solid #333;
            color: white;
        }

        /* PROJECTS */
        .projects {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .project {
            background: #111;
            padding: 20px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .project:hover {
            box-shadow: 0 0 10px #a855f7;
        }

        @media (max-width: 600px) {
            .buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 80%;
                text-align: center;
            }
        }

        
        .actif {
            color : #00f;
            font-weight: bold;
            border-bottom: 2px solid #00f;
        }
        .footer {
    background: #0b0b0f;
    color: #fff;
    padding: 60px 20px 20px;
    border-top: 2px solid #7f5af0;
}

.footer-container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    justify-content: space-between;
    gap: 40px;
    flex-wrap: wrap;
}

.footer-section {
    flex: 1;
    min-width: 250px;
}

.footer-section h2 {
    color: #7f5af0;
    margin-bottom: 10px;
}

.footer-section h3 {
    color: #2ecc71;
    margin-bottom: 15px;
}

.footer-section p {
    color: #ccc;
    line-height: 1.6;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section ul li a {
    text-decoration: none;
    color: #ccc;
    transition: 0.3s;
}

.footer-section ul li a:hover {
    color: #2ecc71;
    padding-left: 5px;
}

.footer-bottom {
    text-align: center;
    margin-top: 40px;
    border-top: 1px solid #222;
    padding-top: 15px;
    color: #888;
    font-size: 14px;
}
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
    

    <section class="hero">
        <div class="hero-text">
            <h1>Coucou, je suis Charlotte</h1>
            <p>Frontend Developer & Designer</p>
            <div class="buttons">
                <a href="files/cv_design_exact.pdf" class="btn btn-outline">Telecharge le CV</a>
                <a href="contact.php" class="btn btn-outline">Contacte moi</a>
            </div>
        </div>
        <img src="images/projects/image0 (14).jpeg">
    </section>
    <div class="section-divider"></div>
    <section class="about">

        <div class="about-container">

            <!-- IMAGE -->
            <div class="about-img">
                <img src="images/projects/image0 (12).jpeg" alt="me">
            </div>

            <!-- TEXT -->
            <div class="about-text">
                <h2>About <span>Me</span></h2>

                <p>
                    Je suis une fervante software engineering student ,passionnée de construire des modern
                    and user-friendly applications.J'aime realiser des idées en vrai produit digital utilisant
                    clean code and creative design. Always learning, always improving.
                </p>

                <!-- STATS -->
                <div class="about-stats">
                    <div>
                        <h3>5+</h3>
                        <span>Education</span>
                    </div>
                    <div>
                        <h3>1+</h3>
                        <span> Experience</span>
                    </div>
                    <div>
                        <h3>100+</h3>
                        <span>Projects </span>
                    </div>
                </div>

                <a href="about.php" class="learn-btn">Apprends plus</a>

            </div>

        </div>

    </section>
    <div class="section-divider"></div>

    <section class="skills-section">
        <h2>Mes Skills</h2>
        <p class="subtitle"> Les technologies que j'utilise</p>

        <div class="skills-grid">

            <!-- Laravel -->
            <div class="skill-card">
                <div class="skill-header">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg">
                    <span>Laravel</span>
                </div>
                <div class="progress">
                    <div class="bar laravel"></div>
                </div>
            </div>

            <!-- React -->
            <div class="skill-card">
                <div class="skill-header">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg">
                    <span>React</span>
                </div>
                <div class="progress">
                    <div class="bar react"></div>
                </div>
            </div>

            <!-- JavaScript -->
            <div class="skill-card">
                <div class="skill-header">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg">
                    <span>JavaScript</span>
                </div>
                <div class="progress">
                    <div class="bar js"></div>
                </div>
            </div>

            <!-- Python -->
            <div class="skill-card">
                <div class="skill-header">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg">
                    <span>Python</span>
                </div>
                <div class="progress">
                    <div class="bar python"></div>
                </div>
            </div>

            <!-- Java -->
            <div class="skill-card">
                <div class="skill-header">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg">
                    <span>Java</span>
                </div>
                <div class="progress">
                    <div class="bar java"></div>
                </div>
            </div>

            <!-- HTML -->
            <div class="skill-card">
                <div class="skill-header">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg">
                    <span>HTML</span>
                </div>
                <div class="progress">
                    <div class="bar html"></div>
                </div>
            </div>

            <!-- CSS -->
            <div class="skill-card">
                <div class="skill-header">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg">
                    <span>CSS</span>
                </div>
                <div class="progress">
                    <div class="bar css"></div>
                </div>
            </div>

            <!-- Tailwind -->
            <div class="skill-card">
                <div class="skill-header">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg">
                    <span>Tailwind</span>
                </div>
                <div class="progress">
                    <div class="bar tailwind"></div>
                </div>
            </div>

        </div>
    </section>
    <div class="section-divider"></div>

    <section class="section">
        <h2>Mes Projets</h2>
        <div class="projects">

            <!-- CARD -->
            <div class="project-card">
                <img src="images/projects/image0 (11).jpeg">

                <div class="project-content">
                    <h3>E-Commerce Platforme</h3>
                    <p>Full-featured e-commerce platforme avec payement.</p>

                    <div class="tech">
                        <span>React</span>
                        <span>Node</span>
                        <span>MongoDB</span>
                    </div>

                    <div class="buttons">
                        <button class="btn-dark">Code</button>
                        <button class="btn-orange">Demo</button>
                    </div>
                </div>
            </div>

            <!-- DUPLIQUE POUR FAIRE 8 -->
            <div class="project-card">
                <img src="images/projects/image1 (1).jpeg">
                <div class="project-content">
                    <h3>Platform pour algriculteur</h3>
                    <p>Aide à savoir quand est ce que y'aura la pluie.</p>
                    <div class="tech">
                        <span>React</span>
                        <span>HTML</span>
                    </div>
                    <div class="buttons">
                        <button class="btn-dark">Code</button>
                        <button class="btn-orange">Demo</button>
                    </div>
                </div>
            </div>

            <div class="project-card">
                <img src="images/projects/image2.jpeg">
                <div class="project-content">
                    <h3>Site pour un startup</h3>
                    <p>Site personnalisé au gout du client.</p>
                    <div class="tech">
                        <span>Tailwind</span>
                        <span>React</span>
                    </div>
                    <div class="buttons">
                        <button class="btn-dark">Code</button>
                        <button class="btn-orange">Demo</button>
                    </div>
                </div>
            </div>

            <div class="project-card">
                <img src="images/projects/image4.jpeg">
                <div class="project-content">
                    <h3>Requete client </h3>
                    <p>Une technologie basée sur des requetes client-serveur.</p>
                    <div class="tech">
                        <span>SQL</span>
                        <span>C</span>
                    </div>
                    <div class="buttons">
                        <button class="btn-dark">Code</button>
                        <button class="btn-orange">Demo</button>
                    </div>
                </div>
            </div>





        </div>
    </section>

    <div class="section-divider"></div>
    <!-- ===== PROJECT SEARCH ===== -->
    
    

    <div class="section-divider"></div>


    <div class="section-divider"></div>

    <section class="contact-section">
        <div class="contact-wrapper">

            <h2>Contact</h2>

            <form class="contact-form" method="POST" action="">
             <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">
                <label for="Nom">Nom:</label>
                <input type="text" name="nom" placeholder="Nom" value=" <?= $nom  ?>">
                <p style="color:red"><?= $erreurs['nom'] ?? ""?></p>
    
                <label for="Email">Email:</label>
                <input type="email"  name="email"   value=" <?=$email ?>">
                <p style="color:red"><?= $erreurs['email'] ?? ""?></p>

                <label for="Message">Message:</label>
                <textarea placeholder="Message" name="message"> <?= $message?></textarea>
                <p style="color:red"><?= $erreurs['message'] ?? ""?></p>

                <button type="submit">envoyer</button>
            </form>
             <?php if ($sucess) : ?>
              <p style="color:green">Message envoyé</p>
              <?php endif; ?>
              
             </div>
    </section>
     
<?php require 'composants/piedpage.php'; ?>
    <script>
        function toggleMode() {
            document.body.classList.toggle("light")
        }
        const elements = document.querySelectorAll('.fade-up');

        window.addEventListener('scroll', () => {
            elements.forEach(el => {
                const top = el.getBoundingClientRect().top;
                if (top < window.innerHeight - 50) { el.classList.add('show'); }
            });
        });
        
    

    </script>

</body>

</html>