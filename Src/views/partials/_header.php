
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
            <?php if (isset($_SESSION['id_user'])): ?>
            <a href="/logout" data-item="Deconnexion" id="btnLogout">deconnexion</a>
            <?php else: ?>
            <a href="/login" data-item="Connexion" id="btnLogin">connexion</a>
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
<!--Model de connexion-->
<div class="modal" id="modalLogin">
  <div class="modal_container">
    <div class="model_title">
      <span class="close"><i class="ri-close-large-line"></i></span>
      <h2>Connexion</h2>
    </div>
    <form action="../../controllers/AuthController.php" method="POST" id="formLogin" enctype="multipart/form-data">
      <div class="form_group">
        <label for="emailuser">Email:</label>
        <input type="email" id="emailuser" name="emailuser" required>
      </div>
      <div class="form_group">
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required>
      </div>
      <button type="submit" id="btnSubmitLogin">se connecter</button>
    </form>
  </div>
</div>

