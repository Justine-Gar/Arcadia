<!--START SECTION BIENVENUE-->
<section id="bienvenue">

  <div class="slider_container">
    <div class="slides">
        <img src="<?= $allFileA[10]; ?>" alt="Girafe" />
        <img src="<?= $allFileA[33]; ?>" alt="Jaguar" />
        <img src="<?= $allFileA[22]; ?>" alt="Caiman" />
        <img src="<?= $allFileA[10]; ?>" alt="Girafe2" />
      
    </div>
  </div>

  <div class="ligne_bienvenue"></div>

  <div class="bienvenue_container">

    <div class="bienvenue_img">
      <img src="<?= $allFileA[9] ;?>" alt="Melman notre girafe fais la pause" />
    </div>

    <div class="bienvenue_text">
      <h1>bienvenue !</h1>
      <p>
        Au zoo Arcadia, chaque détails est pensé pour vous offrir une
        visite agréable et enrichissante. Nous sommes impatients de vous
        acceuillir et de partager avec vous notre passions pour les
        animaux et la conservation.
      </p>
      <p>Venez explorer, apprendre et vous amuser en famille ou entre amis!</p>
    </div>

  </div>
</section>
<!--END SECTION BIENVENUE-->

<!--START SECTION SERVICES-->
<section id="home_services">

  <div class="home_services_container">
    <h3>Services</h3>
    <div class="home_services_groupe">
      <div class="home_services_text">
        <p>Bienvenue au Zoo Arcadi ! <br />
          Profitez de nos aires de restauration rapide et de pique-nique
          pour une pause gourmande. Enrichissez votre visite avec nos
          guides experts qui vous feront découvrir les secrets des
          animaux. Pour une expérience unique, embarquez à bord de notre
          petit train touristique et explorez le zoo sans effort. Venez
          vivre une aventure inoubliable en famille ou entre amis !
        </p>
      </div>
      <div class="home_services_button">

        <div class="home_button_r">
          <a href="/services#restauration" data-text="restauration">restauration</a>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </div>
        <div class="home_button_v">
          <a href="/services#visite" data-text="visite">visite</a>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </div>
        <div class="home_button_t">
          <a href="/services#train" data-text="train">train</a>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </div>

      </div>
    </div>
  </div>

</section>
<!--END SECTION SERVICES-->

<!--START SECTION HABITATS-->
<section id="home_habitats">

  <div class="home_card">
    <!--______SAVANE______-->
    <div class="home_habitat_savane">
      <div class="home_title">
        <p>savane</p>
      </div>
      <img src="<?= $allFileH[3]; ?>" alt="Image de Savane">

      <div class="home_savane_txt_hover">
        <p class="home_savane_txt">
          La savane est un écosystème vaste et ouvert, caractérisé par des prairies étendues parsemé de quelques arbres et arbustre.
          Ce biome se trouve principalement en Afrique, mais aussi en Amérique du Sud, en Australie et en Inde ...
        </p>
        <div class="home_savane_button">
          <a href="/habitats#savane">En savoir plus</a>
        </div>
      </div>
      <div class="home_savane_mobile">
        <p>Venez voir les animaux de la savane</p>
        <div class="home_mobile_button">
          <a href="">nos animaux</a>
        </div>
      </div>
      <div class="home_savane_animaux">

        <div class="home_savane_girafe">
          <div class="home_img_girafe">
            <img src="<?= $allFileA[8]; ?>" alt="Image de girafe">
          </div>
          <div class="home_girafe_button">
            <a href="">La Girafe</a>
          </div>
        </div>
        <div class="home_savane_fenec">
          <div class="home_img_fenec">
            <img src="<?= $allFileA[3]; ?>" alt="Image de fenec">
          </div>
          <div class="home_fenec_button">
            <a href="">Le Fenec</a>
          </div>
        </div>
        <div class="home_savane_rhinoceros">
          <div class="home_img_rhinoceros">
            <img src="<?= $allFileA[14]; ?>" alt="Image de rhinocéros">
          </div>
          <div class="home_rhinoceros_button">
            <a href="">Le Rhinoceros</a>
          </div>
        </div>
      </div>
    </div>
    <!--______MARAIS______-->
    <div class="home_habitat_marais">
      <div class="home_title">
        <p>marais</p>
      </div>
      <img src="<?= $allFileH[6]; ?>" alt="Image de Marais">

      <div class="home_marais_txt_hover">
        <p class="home_marais_txt">
          Les marais sont des écosystèmes humides et complexes, avec des terres basses et des sols saturés d'eau.
          Ils se trouvent dans diverses régions du monde, des côtes aux terres intérieures, se formant où l'eau s'accumule,
          comme le long des cours d'eau, deltas, estuaires et plaines inondables.
        </p>
        <div class="home_marais_button">
          <a href="/habitats#marais">En savoir plus</a>
        </div>
      </div>
      <div class="home_marais_mobile">
        <p>Venez voir les animaux de la savane</p>
        <div class="home_mobile_button">
          <a href="">nos animaux</a>
        </div>
      </div>
      <div class="home_marais_animaux">

        <div class="home_marais_caiman">
          <div class="home_img_caiman">
            <img src="<?= $allFileA[21]; ?>" alt="Image de caiman">
          </div>
          <div class="home_caiman_button">
            <a href="">Le Caiman</a>
          </div>
        </div>
        <div class="home_marais_renette">
          <div class="home_img_renette">
            <img src="<?= $allFileA[24]; ?>" alt="Image de renette verte">
          </div>
          <div class="home_renette_button">
            <a href="">La Renette</a>
          </div>
        </div>
        <div class="home_marais_salamandre">
          <div class="home_img_salamandre">
            <img src="<?= $allFileA[27]; ?>" alt="Image de salamandre">
          </div>
          <div class="home_salamanbre_button">
            <a href="">La Salamandre</a>
          </div>
        </div>
      </div>
    </div>
    <!--______JUNGLE______-->
    <div class="home_habitat_jungle">
      <div class="home_title">
        <p>jungle</p>
      </div>
      <img src="<?= $allFileH[8]; ?>" alt="Image de Jungle">
      <!--_Jungle_txt_-->
      <div class="home_jungle_txt_hover">

        <p class="home_jungle_txt">
          La jungle est un environnement dense et luxuriant, caractérisé par une végétation tropicale épaisse et
          une biodiversité exceptionnelle. Les jungles sont marquées par un climat chaud et humide avec des précipitations abondantes tout au long
          de l'année, créant des conditions idéales pour une végétation dense et variée...
        </p>
        <div class="home_jungle_button">
          <a href="/habitats#jungle">En savoir plus</a>
        </div>
      </div>
      <!--_Jungle_mobile_-->
      <div class="home_jungle_mobile">
        <p>Venez voir les animaux de la savane</p>
        <div class="home_mobile_button">
          <a href="">nos animaux</a>
        </div>
      </div>
      <!--_Jungle_animaux_-->
      <div class="home_jungle_animaux">
        <div class="home_jungle_paresseux">
          <div class="home_img_paresseux">
            <img src="<?= $allFileA[40]; ?>" alt="Image de paresseux">
          </div>
          <div class="home_paresseux_button">
            <a href="">Le Paresseux</a>
          </div>
        </div>

        <div class="home_jungle_toucan">
          <div class="home_img_toucan">
            <img src="<?= $allFileA[44]; ?>" alt="Image de toucanToco">
          </div>
          <div class="home_toucan_button">
            <a href="">Le Toucan</a>
          </div>
        </div>

        <div class="home_jungle_jaguar">
          <div class="home_img_jaguar">
            <img src="<?= $allFileA[34]; ?>" alt="Image de jaguar">
          </div>
          <div class="home_jaguar_button">
            <a href="">Le Jaguar</a>
          </div>
        </div>


      </div>

    </div>
  </div>

</section>
<!--END SECTION HABITATS-->

<!--START SECTION AVIS-->
<section id="avis">
  <div class="avis_container">
    <h2>les avis</h2>
    <!--Slider -->
    <div class="avis_slider">
  
      <div id="avis_container_text">
        <!--Les card avis-->
        <?php if(!empty($reviews)): ?>
          <?php foreach ($reviews as $review): ?>
        <div class="avis_item">
          <div class="avis_header">
            <h5><?= htmlspecialchars($review->getNameReview()) ?></h5>
            <div class="rating">
              <?php 
                $score = $review->getScore();
                for($i = 1; $i <= 5; $i++) {
                  if($i <= $score) {
                    echo '<span class="star filled">★</span>';
                  } else {
                    echo '<span class="star">★</span>';
                  }
                }
              ?>
            </div>
          </div>
          <div class="avis_text">
            <p><?= htmlspecialchars($review->getDescriptionReview()) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
      <?php else: ?>
        <div class="avis_item">
          <div class="avis_text">
            <p>Aucun avis pour le moment.</p>
          </div>
        </div>
      <?php endif; ?>
      </div>
    </div>

    <div id="btnAvisForm">un avis ?</div>

    <form id="avis_form" action="#" method="post">
      <input type="text" name="clientName" id="clientName" placeholder="Nom du client" required>
      <select id="clientRating" name="clientRating" required>
        <option value="">Choisir une note</option>
        <option value="1">1 étoile</option>
        <option value="2">2 étoiles</option>
        <option value="3">3 étoiles</option>
        <option value="4">4 étoiles</option>
        <option value="5">5 étoiles</option>
      </select>
      <textarea id="clientText" name="clientText" placeholder="Votre avis ..." required></textarea>
      <button class="clientBtn" name="submit" type="submit">Envoyer l'avis</button>
    </form>
  </div>

  <div class="ligne_avis"></div>
  <div class="avis_background"></div>
</section>
<!--END SECTION AVIS-->

