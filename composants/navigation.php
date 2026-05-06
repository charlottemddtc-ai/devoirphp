  <?php $page_courante = basename($_SERVER['PHP_SELF']);
  ?>
 <nav>
        <h3>Portfolio.</h3>
     
        <div class="nav-links">
            <a href="index.php" <?php if ($page_courante == 'index.php') echo 'class="actif"'; ?>
            >Home</a>
            <a href="about.php" <?php if ($page_courante == 'about.php') echo 'class="actif"'; ?>
            >About</a>
            <a href="projects.php"<?php if ($page_courante == 'projects.php') echo 'class="actif"'; ?>
            >Projets</a>
           <a href="contact.php" <?php if ($page_courante == 'contact.php') echo 'class="actif"'; ?>
            >Contact</a>
        </div>
        <span class="toggle" onclick="toggleMode()">🌙</span>
     
    </nav>

    <?php ?>