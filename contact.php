<?php require 'fonctions.php' ; 

$erreurs =[];
$sucess=false;

if($_SERVER['REQUEST_METHOD']=='POST'){

    $nom=nettoyer($_POST['nom'] ?? "");
    $email=nettoyer($_POST['email'] ?? "");
    $message=nettoyer($_POST['message'] ?? "");
    if(!champ_requis($nom)){
        $erreurs['nom']="Nom obligatoire";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erreurs['email']="email invalige";
    }
    if(!champ_requis($message)){
        $erreurs['message']="Message obligatoire obligatoire";
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
    <title>Contact</title>
    <meta name="viewport" content="width=device-witdh,initial-scale=1.0 ">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        :root {
            --bg: #111;
            --card: #1e293b;
            --text: #fff;
            --accent: #ff7a00;
        }

        body {
            background: var(--bg);
            overflow-x: hidden;
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

        form {
            max-width: 500px;
            width: 100%;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input,
        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 10px;
            background: var(--card);
            color: white;
        }

        button {
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: var(--accent);
            color: white;
            cursor: pointer;
        }

        button:hover {
            box-shadow: 0 0 15px var(--accent);
        }

        .contact-section {
            background: #0f0f0f;
            color: white;
            padding: 60px 20px;
            font-family: Arial;
        }

        .checkbox {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .checkbox p {
            max-width: 100%;
            font-size: 12px;
            word-break: break-word;
        }

        /* NAVBAR */
        .contact-nav {
            display: flex;
            justify-content: center;
            gap: 30px;
            background: #1a1a1a;
            padding: 10px 25px;
            border-radius: 50px;
            width: fit-content;
            margin: auto;
            margin-bottom: 50px;
        }

        .contact-nav a {
            color: #aaa;
            text-decoration: none;
        }

        .contact-nav .active {
            background: #39ff14;
            color: black;
            padding: 6px 18px;
            border-radius: 20px;
        }

        /* LAYOUT */
        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            max-width: 1100px;
            margin: auto;
        }

        /* LEFT */
        .contact-left h1 {
            color: #39ff14;
            font-size: 40px;
        }

        .contact-left p {
            color: #bbb;
            margin-top: 10px;
        }

        /* ENVELOPE */
        .envelope {
            margin-top: 40px;
            width: 250px;
            height: 160px;
            background: linear-gradient(135deg, #6c2bd9, #4f46e5);
            border-radius: 20px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .actif {
            color : #00f;
            font-weight: bold;
            border-bottom: 2px solid #00f;
        }
        .card {
            position: absolute;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 14px;
        }

        .purple {
            background: #7c3aed;
            top: 20px;
            left: 20px;
        }

        .green {
            background: #39ff14;
            color: black;
            bottom: 20px;
            right: 20px;
        }

        /* RIGHT FORM */
        .contact-right {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 20px;
        }

        .contact-right input,
        .contact-right textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            background: transparent;
            border: 1px solid #333;
            border-radius: 8px;
            color: white;
        }

        .contact-right textarea {
            height: 120px;
        }

        /* CHECKBOX */
        .checkbox {
            display: flex;
            gap: 10px;
            font-size: 12px;
            color: #aaa;
            margin-bottom: 20px;
        }

        /* BUTTON */
        .contact-right button {
            width: 100%;
            padding: 12px;
            background: #39ff14;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
        }

        /* FOOTER */
        .contact-footer {
            margin-top: 60px;
            background: #1a1a1a;
            padding: 20px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .contact-footer a {
            color: #aaa;
            margin-right: 15px;
            text-decoration: none;
        }

        .socials span {
            margin-left: 10px;
            background: #333;
            padding: 8px;
            border-radius: 50%;
        }

        /* CREDIT */
        .credit {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
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


    <section class="contact-section container">


        <div class="contact-container">

            <!-- LEFT -->
            <div class="contact-left">
                <h1>Get In Touch</h1>
                <p>
                    En recherche de la derniere piece manquante pour perfectionner votre equipe?
                    Let’s chat — Je suis peut etre la bonne!
                </p>

                <div class="envelope">
                    <div class="card purple">Interview</div>
                    <div class="card green">Offre d'emploi</div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="contact-right">
                <form>

                    <input type="text" placeholder="Name">
                    <input type="email" placeholder="Email">
                    <textarea placeholder="Message"></textarea>

                    <div class="checkbox">
                        <input type="checkbox">
                        <span>
                            En cliquant "Submit", tu me donnes la permission de stocker tes infos et de te conctacter.
                        </span>
                    </div>

                    <button>Submit</button>

                </form>
            </div>

        </div>

        

        

    </section>
    <?php require 'composants/piedpage.php'; ?>

</body>

</html>