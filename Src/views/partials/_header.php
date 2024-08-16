<?php
// Vérifier si la session est active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
$is_login = isset($_SESSION['user_id']);

?>
<!--====  START HEADER  ====-->
<header>
  <div class="header_container">
    <div class="logo_arcadia">
      <img src="assets/img/Fichier 2.png" alt="Logo Arcadia" />
    </div>
    <div class="navbar">
      <nav id="menu">
        <ul class="menu_items">
          <li><a href="/" data-item="Accueil">accueil</a></li>
          <li><a href="/services" data-item="Services">services</a></li>
          <li><a href="/habitats" data-item="Habitats">habitat</a></li>
          <li><a href="/contact" data-item="Contact">contact</a></li>
          <li>
          <?php if ($is_login): ?>
            <a href="/logout" data-item="Deconnexion">déconnexion</a>
          <?php else: ?>
            <a href="/login" data-item="Connexion">connexion</a>
          <?php endif; ?>
          </li>  
        </ul>
      </nav>
    </div>
    <div id="burger_menu">
      <span></span>
    </div>
  </div>
</header>

<!--==== END HEADER ====-->
