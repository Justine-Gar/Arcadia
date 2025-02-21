<!--START SECTION COVER-->
<section id="cover_contact">
  <div class="cover_contact_container">

    <div class="cover_contact_img"></div>

    <div class="cover_title_form">

      <!--TITRE-->
      <div class="cover_title_cercle">
        <div class="cover_contact_cercle">
          <img src="assets/svg/EllipseVF.svg" alt="image d'un cercle en svg">
        </div>
        <div class="cover_contact_title">
          <h1>Contact</h1>
          <p>Une question ? Nous sommes à votre écoute !</p>
        </div>
      </div>
      <!--Message succès/erreur-->
      <div id="message-status" class="message-status" ></div>
      <!--FORM-->
      <div class="contact_form_container">
        <form action="#" method="post" id="contactForm">
          <!--action ici /api/contact-->
          <input type="text" id="title_contact" name="title_contact" required placeholder="Titre de votre demande ..">
          <input type="email" name="mail_contact" id="mail_contact" required placeholder="Votre email ...">
          <textarea name="description_contact" id="description_contact" cols="35" rows="10" placeholder="Votre demande ..."></textarea>
          <button type="submit" class="button_contact">Envoyer</button>
        </form>
      </div>

    </div>

  </div>
</section>
<!--END SECTION COVER-->