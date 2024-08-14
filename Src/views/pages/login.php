<div class="modal" id="modalLogin">
    <div class="modal_container">
      <div class="model_title">
        <span class="close"><i class="ri-close-large-line"></i></span>
        <h2>Connexion</h2>
      </div>
      <form action="login.php" method="post" id="formLogin">
        <div class="form_group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form_group">
          <label for="password">Mot de passe:</label>
          <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" id="btnSubmitLogin">se connecter</button>
      </form>
    </div>
  </div>