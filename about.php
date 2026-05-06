<?php require 'fonctions.php' ; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me</title>

    <style>
        /* GLOBAL */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #030303;
            color: white;
            overflow-x: hidden;
        }

        p,
        h1,
        h2,
        h3,
        span,
        a,
        button {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* IMPORTANT pour flex (évite débordement) */
        .about-text,
        .project h3,
        .checkbox p,
        .contact-form,
        .about-card {
            min-width: 0;
        }

        /* About texte */
        .about-text {
            max-width: 100%;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
        }

        /* SECTION */
        /* SECTION */
        .contact.about {
            padding: 80px 10%;
            background: #0a0a0a;
        }

        /* LAYOUT */
        .contact-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 50px;
            flex-wrap: wrap;
        }

        /* LEFT */
        .contact-left h1 {
            font-size: 48px;
            color: #39ff14;
            margin-bottom: 20px;
        }

        .contact-left p {
            color: #aaa;
            max-width: 400px;
            line-height: 1.6;
        }

        /* STATS */
        .stats {
            display: flex;
            gap: 20px;
            margin: 30px 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
            min-width: 100px;
        }

        .stat-card h3 {
            color: #39ff14;
            font-size: 22px;
        }

        .stat-card span {
            color: #888;
            font-size: 14px;
        }

        /* BUTTON (exact Submit) */
        .btn {
            background: #7c3aed;
            color: black;
            border: none;
            padding: 14px 30px;
            border-radius: 12px;
            font-weight: bold;
            cursor: pointer;
        }

        /* RIGHT BOX = même style que form */
        .about-box {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #aaa;
            max-width: 400px;
        }

        /* 🔥 EXPERIENCE SECTION */
        .experience {
            padding: 60px 10%;
            display: flex;
            justify-content: center;
            background-color: #0a0a0a;
        }

        /* GRID */
        .exp-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            max-width: 900px;
            width: 100%;
        }

        /* COLUMN */
        .exp-column h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* CARD */
        .exp-card {
            background: linear-gradient(135deg, #1a0f2e, #2b124c);
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        /* HOVER */
        .exp-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(128, 0, 255, 0.4);
        }

        /* TEXT */
        .exp-card span {
            font-size: 12px;
            color: #bbb;
        }

        .exp-card h3 {
            margin: 5px 0;
        }

        .exp-card p {
            font-size: 13px;
            color: #aaa;
        }

        /* RESPONSIVE */
        @media(max-width:768px) {
            .exp-grid {
                grid-template-columns: 1fr;
            }

            .about-container {
                flex-direction: column;
                text-align: center;
            }
        }

        /* ANIMATION BASE */
        .hidden {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.6s ease;
        }

        .show {
            opacity: 1;
            transform: translateY(0);
        }

        /* TIMELINE */
        .exp-column {
            position: relative;
            padding-left: 20px;
        }

        /* ligne verticale */
        .exp-column::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(#ff7a00, #8b5cf6);
        }

        /* points */
        .exp-card {
            position: relative;
        }

        .exp-card::before {
            content: "";
            position: absolute;
            left: -28px;
            top: 15px;
            width: 12px;
            height: 12px;
            background: #ff7a00;
            border-radius: 50%;
            box-shadow: 0 0 10px #ff7a00;
        }
.actif {
            color : #00f;
            font-weight: bold;
            border-bottom: 2px solid #00f;
        }
        /* NAVBAR */
        

       
    

         nav {
            display: flex;
            justify-content: space-between;
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
        nav a:hover,
        nav a.active {
            color: #7c3aed;
            text-decoration: none;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
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
    <header class="navbar">
        
       <?php require 'composants/navigation.php'; ?>
    </header>
    <!-- 🔥 ABOUT -->
    <section class="contact about container">
        <div class="contact-container">

            <!-- LEFT -->
            <div class="contact-left">
                <h1>About Me</h1>

                <p>
                    Je suis une fervante software engineering student ,passionnée de construire des modern
                    and user-friendly applications.J'aime realiser des idées en vrai produit digital utilisant
                    clean code and creative design. Always learning, always improving.
                </p>

                <!-- STATS -->
                <div class="stats">
                    <div class="stat-card">
                        <h3>5+</h3>
                        <span>Education</span>
                    </div>

                    <div class="stat-card">
                        <h3>1+</h3>
                        <span>Experience</span>
                    </div>

                    <div class="stat-card">
                        <h3>10+</h3>
                        <span>Projects</span>
                    </div>
                </div>

                <button class="btn">Learn More</button>
            </div>

            <!-- RIGHT (comme form box) -->
            <div class="contact-right">
                <div class="about-box">
                    <p>

                        Always learning and exploring new technologies.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- 🔥 EXPERIENCE + EDUCATION -->
    <section class="experience">

        <div class="exp-grid">

            <!-- LEFT -->
            <div class="exp-column">
                <h2>💼 Mon Experience</h2>

                <div class="exp-card hidden">
                    <span>2025 - Present</span>
                    <h3>Software Developer</h3>
                    <p> applications web et UI design</p>
                </div>

                <div class="exp-card hidden">
                    <span> avril 2025 - juin 2025</span>
                    <h3>Frontend Developer</h3>
                    <p> responsive websites utilisant React</p>
                </div>

                <div class="exp-card hidden">
                    <span> fevrier 2025 - mars 2025</span>
                    <h3>UI Designer</h3>
                    <p>Designé des interfaces modernes </p>
                </div>

                <div class="exp-card hidden">
                    <span> juillet 2024 - janvier 2025</span>
                    <h3>Junior Developer</h3>
                    <p>Fait des petits projets</p>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="exp-column">
                <h2>🎓 Mon Education</h2>

                <div class="exp-card hidden">
                    <span>2025 - 2026</span>
                    <h3>Licence 2</h3>
                    <p>ESTM </p>
                </div>

                <div class="exp-card hidden">
                    <span>2024 - 2025</span>
                    <h3>Licence 1</h3>
                    <p>ESTM</p>
                </div>

                <div class="exp-card hidden">
                    <span>2019 - 2024</span>
                    <h3>Eleve</h3>
                    <p>Institution Sainte Jeanne d'arc</p>
                </div>

                <div class="exp-card hidden">
                    <span>2013 - 2019</span>
                    <h3>Eleve en primaire</h3>
                    <p>Sainte Abraham</p>
                </div>

            </div>

        </div>

    </section>
    <?php require 'composants/piedpage.php'; ?>
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("show");
                }
            });
        });

        document.querySelectorAll(".hidden").forEach(el => {
            observer.observe(el);
        });
    </script>
</body>

</html>