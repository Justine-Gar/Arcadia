<!--====  START FOOTER  ====-->
<footer class="footer">

  <div class="footer_container">
    <div class="footer_contact_nav">
      <div class="footer_texte">

        <div class="footer_contact">
          <h2>Contact</h2>
          <p>123 Allée de Chênes</p>
          <p>Brocéliande</p>
          <p>56430</p>
          <p>FRANCE</p>
          <p>+33 02 99 99 99 99</p>
          
        </div>
      </div>

      <nav class="footer_nav">
        <a href="/">Accueil</a>
        <a href="/services">Services</a>
        <a href="/habitats">Habitats</a>
        <a href="/faq">FAQ</a>
      </nav>

    </div>

    <div class="footer_heures_img">

      <div class="footer_horaire">
        <h2>Horaires</h2>
        <?php if (isset($timetables) && !empty($timetables)): ?>
          <?php foreach ($timetables as $timetable): ?>
            <div class="footer_jours_heures">
                <div class="jours_footer">
                    <p><?= htmlspecialchars($timetable->getDays()) ?></p>
                </div>
                <div class="heures_footer">
                    <div class="heures_ouverture">
                        <p><?= htmlspecialchars($timetable->getOpenHours())?>-</p>
                    </div>
                    <div class="heures_fermeture">
                        <p><?= htmlspecialchars($timetable->getCloseHours()) ?></p>
                    </div>
                </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="footer_img">
        <div class="footer_logo">
          <img src="/assets/image/Logo.png" alt="Logo Arcadia" />
        </div>

        <div class="footer_reseaux">
          <div class="footer_icon">
            <a href="https://www.facebook.com/?locale=fr_FR"><img src="/assets/icon/fb.png"
                alt="Logo Facebook" /></a>
          </div>
          <div class="footer_icon">
            <a href="https://www.youtube.com/?app=desktop&hl=fr"><img src="/assets/icon/youtube.png"
                alt="Logo Youtube" /></a>
          </div>
          <div class="footer_icon">
            <a href="https://www.instagram.com/"><img src="/assets/icon/insta.png" alt="Logo instagram" /></a>
          </div>
          <div class="footer_icon">
            <a href="https://x.com/?lang=fr"><img src="assets/icon/x.png" alt="Logo X" /></a>
          </div>
        </div>

      </div>

    </div>
  </div>

  <div class="footer_mention">
    <p>© 2025 espace zoologique</p>
    <p>tous droits réservés <a href="">mention légale</a></p>
  </div>
</footer>
<!--==== END FOOTER ====-->