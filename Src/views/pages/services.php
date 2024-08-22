<!--START SECTION COVER-->
<section id="cover_services">
   <div class="cover_services_container">
      <div class="cover_services_img"></div>
      <div class="cover_services_cercle">
         <img src="assets/svg/EllipseVF.svg" alt="image d'un cercle en svg">
      </div>
      <div class="cover_services_title">
         <h1>nos services</h1>
         <p>Retrouvez tous no conseil et astuces pour vivre une expérience inoubliable au Zoo Arcadia</p>
      </div>
   </div>
</section>
<!--END SECTION COVER-->

<!--START SECTION SERVICES-->
<section class="services_container">

   <div class="restauration">
      <div class="restauration_text">
         <h2><?php echo isset($services[0]) ? htmlspecialchars($services[0]->getNameService()) : 'Restauration et Pique-nique'; ?></h2>
         <?php if (isset($services[0])):
            $paragraphs = explode("\n", $services[0]->getDescriptionService());
            foreach ($paragraphs as $paragraph): ?>
               <p><?php echo htmlspecialchars($paragraph); ?></p>
            <?php endforeach;
         else: ?>
            <p>Pour combler toutes vos envies, le Zoo Arcadia offre plusieurs options de restauration. Notre service de restauration rapide propose une variété de plats savoureux, allant des hamburgers aux salades fraîches, en passant par des encas et des desserts gourmands.</p>
            <p>Si vous préférez apporter votre propre repas, des aires de pique-nique ombragées sont à votre disposition dans le parc. Détendez-vous en famille ou entre amis dans l'un de nos 3 cadres naturels et conviviaux, tout en profitant de la beauté de notre environnement.</p>
         <?php endif; ?>
      </div>

      <div class="restauration_img">
         <img src="assets/img/zoo/adobestock_702047442.avif" alt="un banc en bois dans un parc verdoyant">
      </div>
   </div>

   <div class="guide">
      <div class="guide_text">
         <h2><?php echo isset($services[1]) ? htmlspecialchars($services[1]->getNameService()) : 'Visite guidée'; ?></h2>
         <?php if (isset($services[1])):
            $paragraphs = explode("\n", $services[1]->getDescriptionService());
            foreach ($paragraphs as $paragraph): ?>
               <p><?php echo htmlspecialchars($paragraph); ?></p>
            <?php endforeach;
         else: ?>
            <p>Pour une expérience encore plus enrichissante, optez pour une visite guidée. Nos guides experts vous accompagneront à travers le zoo, partageant des anecdotes fascinantes et des informations détaillées sur les différents animaux que nous abritons.</p>
            <p>Apprenez-en davantage sur les habitudes des fennecs, les caractéristiques des caimans et les efforts de conservation que nous mettons en place pour protéger les espèces menacées. Nos visites guidées sont parfaites pour les familles, les groupes scolaires, et tous ceux qui souhaitent en savoir plus sur le monde animal.</p>
         <?php endif; ?>
      </div>

      <div class="guide_img">
         <img src="assets/img/zoo/adobestock_677717349.avif" alt="Des enfants écoutent le guide dans la zone jungle">
      </div>
   </div>

   <div class="train">
      <div class="train_text">
         <h2><?php echo isset($services[2]) ? htmlspecialchars($services[2]->getNameService()) : 'Visite en petit train'; ?></h2>
         <?php if (isset($services[2])):
            $paragraphs = explode("\n", $services[2]->getDescriptionService());
            foreach ($paragraphs as $paragraph): ?>
               <p><?php echo htmlspecialchars($paragraph); ?></p>
            <?php endforeach;
         else: ?>
            <p>Pour une aventure ludique et confortable, embarquez à bord de nos petits trains touristiques. Ce service unique vous permet de parcourir le zoo, tout en profitant d'une vue panoramique sur les enclos et les habitats des animaux.</p>
            <p>Le petit train est particulièrement apprécié des enfants et des personnes ayant des difficultés à se déplacer à pied. C'est aussi une excellente manière de découvrir le zoo d'un autre point de vue, avec des arrêts aux principales attractions pour que vous ne manquiez rien.</p>
         <?php endif; ?>
      </div>

      <div class="train_img">
         <img src="assets/img/zoo/adobestock_294848755.avif" alt="des petits trains pour visiter l'environnement">
      </div>
   </div>

</section>
<!--END SECTION SERVICES-->