<!--START SECTION COVER HABITAT-->
<section id="cover_habitat">
  <div class="cover_habitat_container">
    <div class="cover_habitat_img"></div>
    <div class="cover_habitat_cercle">
      <img src="./assets/svg/EllipseVF.svg" alt="image d'un cercle en svg">
    </div>
    <div class="cover_habitat_title">
      <h1>nos habitats</h1>
      <p>Au sein des 35 hectars du Zoo de Brocéliande, découvrez nos territoires conçus dans le bien être de nos animaux.</p>
    </div>
  </div>
</section>
<!--END SECTION COVER HABITAT-->

<!--START SECTION ONGLET-->
<section id="onglet">
  <div class="tab_container">
    <!--Voici les onglets-->
    <div class="tabs">

      <!--====START  ONGLET SAVANE ==== -->
      <div class="onglet_savane">
        
        <button class="tab_button active" data-tab="savane">savane</button>

        <!--Ici le contenue de onglet savane-->
        <div id="savane" class="content active">

          <div class="onglet_savane_img">
          </div>

          <!--CONTENUE TEXTE IMG SAVANE-->
          <div class="onglet_savane_txt">
            <?php
              if (isset($habitats[0])) {
                $description = explode('(delimiteur)', $habitats[0]->getDescriptionHabitat());
                $savanePart1 = htmlspecialchars($description[0]);
                $savanePart2 = htmlspecialchars($description[1] ?? '');
              }
            ;?>
            <div class="img_texte_savane1">
              <p><?php echo $savanePart1 ;?></p>
              <div class="img_savane_1">
                <img src="<?= $allFileH[3]; ?>" alt="une route dans la savane">
              </div>
            </div>

            <div class="img_texte_savane2">
              <p><?php echo $savanePart2 ;?></p>
              <div class="img_savane_2">
                <img src="<?= $allFileH[1]; ?>" alt="un paysage de savane avec un arbre fin">
              </div>
            </div>

          </div>

          <!--CONTENUE ANIMAL-->
          <div class="card_animal_savane">
            <h2>Ils y Habitent</h2>
            <div class="card_container">
              <div class="card_carousel">
              
                <!--BTN CAROUSSEL SAVANE-->
                <button class="btn_carousel_savane" id="prev">&#10096;</button>
                <button class="btn_carousel_savane" id="next">&#10097;</button>
              
                <!--card fennec-->
                <div id="fennec_one" class="slide_savane active2">
                  <div class="card_fennec">
                    <img src="<?= $allFileA[2];?>" alt="un fennec qui regarde au loin">
                    <h3>le fennec</h3>

                    <!--donnée fenec-->
                    <div class="card_text_savane">
                      <div class="card_flex_savane">

                        <div class="card_flex_savane_component">
                          <div class="card_composant_animal">
                            <h4>prenom:</h4>
                            <p>TATOUINE</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>genre:</h4>
                            <p>voici le genre</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>sexe:</h4>
                            <p>voici le sexe</p>
                          </div>
                          <div class="card_composant">
                            <h4>Régime alimentaire:</h4>
                            <p>Voici le régime alimentaire d'un fennec</p>
                          </div>
                        </div>

                        <div class="card_flex_savane_component">
                          <div class="card_composant">
                            <h4>Reproduction:</h4>
                            <p>Voici la methodes de reproduction d'un fennec</p>
                          </div>
                          <div class="card_composant">
                            <h4>Passage du médecin:</h4>
                            <p>Etat de l'animal</p>
                            <p>Voici la date de paassage du medecin</p>
                          </div>
                          <div class="card_composant">
                            <h4>Quantité de nourriture:</h4>
                            <p>voici le grammage</p>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

                <!--card girafe-->
                <div id="girafe_one" class="slide_savane">
                  <div class="card_girafe">
                    <img src="<?= $allFileA[9];?>" alt="Une girafe qui nous regarde">
                    <h3>la girafe</h3>

                    <!--donnée girafe-->
                    <div class="card_text_savane">
                      <div class="card_flex_savane">

                        <div class="card_flex_savane_component">
                          <div class="card_composant_animal">
                            <h4>prenom:</h4>
                            <p>Melman</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>genre:</h4>
                            <p>voici le genre</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>sexe:</h4>
                            <p>voici le sexe</p>
                          </div>
                          <div class="card_composant">
                            <h4>Régime alimentaire:</h4>
                            <p>Voici le régime alimentaire d'un fennec</p>
                          </div>
                        </div>

                        <div class="card_flex_savane_component">
                          <div class="card_composant">
                            <h4>Reproduction:</h4>
                            <p>Voici la methodes de reproduction d'un fennec</p>
                          </div>
                          <div class="card_composant">
                            <h4>Passage du médecin:</h4>
                            <p>Etat de l'animal</p>
                            <p>Voici la date de paassage du medecin</p>
                          </div>
                          <div class="card_composant">
                            <h4>Quantité de nourriture:</h4>
                            <p>voici le grammage</p>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

                <!--card rhinoceros-->
                <div id="rhinoceros_one" class="slide_savane">
                  <div class="card_rhinoceros">
                    <img src="<?= $allFileA[15];?>" alt="Un rhinoceros qui nous regarde">
                    <h3>le rhinocers</h3>

                    <!--donnée rhinoceros-->
                    <div class="card_text_savane">
                      <div class="card_flex_savane">

                        <div class="card_flex_savane_component">
                          <div class="card_composant_animal">
                            <h4>prenom:</h4>
                            <p>Brutus</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>genre:</h4>
                            <p>voici le genre</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>sexe:</h4>
                            <p>voici le sexe</p>
                          </div>
                          <div class="card_composant">
                            <h4>Régime alimentaire:</h4>
                            <p>Voici le régime alimentaire d'un fennec</p>
                          </div>
                        </div>

                        <div class="card_flex_savane_component">
                          <div class="card_composant">
                            <h4>Reproduction:</h4>
                            <p>Voici la methodes de reproduction d'un fennec</p>
                          </div>
                          <div class="card_composant">
                            <h4>Passage du médecin:</h4>
                            <p>Etat de l'animal</p>
                            <p>Voici la date de paassage du medecin</p>
                          </div>
                          <div class="card_composant">
                            <h4>Quantité de nourriture:</h4>
                            <p>voici le grammage</p>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

                <!--card zebre-->
                <div id="zebre_one" class="slide_savane">
                  <div class="card_zebre">
                    <img src="<?= $allFileA[5];?>" alt="Un zebre qui nous regarde">
                    <h3>le zebre</h3>

                    <!--donnée zebre-->
                    <div class="card_text_savane">
                      <div class="card_flex_savane">

                        <div class="card_flex_savane_component">
                          <div class="card_composant_animal">
                            <h4>prenom:</h4>
                            <p>Marty</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>genre:</h4>
                            <p>voici le genre</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>sexe:</h4>
                            <p>voici le sexe</p>
                          </div>
                          <div class="card_composant">
                            <h4>Régime alimentaire:</h4>
                            <p>Voici le régime alimentaire d'un fennec</p>
                          </div>
                        </div>

                        <div class="card_flex_savane_component">
                          <div class="card_composant">
                            <h4>Reproduction:</h4>
                            <p>Voici la methodes de reproduction d'un fennec</p>
                          </div>
                          <div class="card_composant">
                            <h4>Passage du médecin:</h4>
                            <p>Etat de l'animal</p>
                            <p>Voici la date de paassage du medecin</p>
                          </div>
                          <div class="card_composant">
                            <h4>Quantité de nourriture:</h4>
                            <p>voici le grammage</p>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

                <!--card servale-->
                <div id="serval_one" class="slide_savane">
                  <div class="card_serval">
                    <img src="<?= $allFileA[19];?>" alt="Un zebre qui nous regarde">
                    <h3>le serval</h3>

                    <!--donnée serval-->
                    <div class="card_text_savane">
                      <div class="card_flex_savane">

                        <div class="card_flex_savane_component">
                          <div class="card_composant_animal">
                            <h4>prenom:</h4>
                            <p>Nyota</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>genre:</h4>
                            <p>voici le genre</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>sexe:</h4>
                            <p>voici le sexe</p>
                          </div>
                          <div class="card_composant">
                            <h4>Régime alimentaire:</h4>
                            <p>Voici le régime alimentaire d'un fennec</p>
                          </div>
                        </div>

                        <div class="card_flex_savane_component">
                          <div class="card_composant">
                            <h4>Reproduction:</h4>
                            <p>Voici la methodes de reproduction d'un fennec</p>
                          </div>
                          <div class="card_composant">
                            <h4>Passage du médecin:</h4>
                            <p>Etat de l'animal</p>
                            <p>Voici la date de paassage du medecin</p>
                          </div>
                          <div class="card_composant">
                            <h4>Quantité de nourriture:</h4>
                            <p>voici le grammage</p>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              
              </div>
            </div>
          </div>
          <!-- FIN CONTENUE ANIMAL-->
        </div>

      </div>
      <!--====END  ONGLET MARAIS ==== -->
      <!--====STRAT  ONGLET MARAIS ==== -->
      <div class="onglet_marais">
        <button class="tab_button" data-tab="marais">marais</button>

        <!--Ici le contenue de onglet marais-->
        <div id="marais" class="content">

          <div class="onglet_marais_img"></div>

          <!--CONTENUE TEXTE IMG MARAIS-->
          <div class="onglet_marais_txt">
            <?php
              if (isset($habitats[1])) {
                $description = explode('(delimiteur)', $habitats[0]->getDescriptionHabitat());
                $maraisPart1 = htmlspecialchars($description[0]);
                $maraisPart2 = htmlspecialchars($description[1] ?? '');
              }
            ;?>
            <div class="img_texte_marais1">
              <p><?= $maraisPart1 ;?></p>

              <div class="img_marais_1">
                <img class="img_mar1" src="<?= $allFileH[6];?>" alt="une route dans la savane">
              </div>
            </div>
            <div class="img_texte_marais2">
              <p><?= $maraisPart2 ;?></p>
              <div class="img_marais_2">
                <img class="img_mar2" src="<?= $allFileH[4];?>" alt="un paysage de savane avec un arbre fin">
              </div>
            </div>

          </div>

          <!--CONTENUE ANIMAL-->
          <div class="card_animal_marais">
            <h2>Ils y Habitent</h2>
            <div class="card_container">
              <div class="card_carousel">

                <!--BTN CAROUSSEL SAVANE-->
                <button class="btn_carousel_marais" id="prev2">&#10096;</button>
                <button class="btn_carousel_marais" id="next2">&#10097;</button>
              <?php foreach ($animals as $animal) : ?>
                <?php if ($animal->getIdHabitat() === 2) : ?>
                    <!--card caiman-->
                  <?php if($animal->getIdAnimal() === 7) : ?>
                    <div id="caiman_one" class="slide_marais active2">
                      <div class="card_caiman">
                        <div class="img_titre">
                          <img src="<?= $allFileA[21];?>" alt="Un caiman qui attrape sa nourriture">
                          <h3>le caiman</h3>
                        </div>

                        <!--donnée caiman-->
                        <div class="card_text_marais">
                          <div class="card_flex_marais">
                            <div class="card_flex_marais_component">
                              <div class="card_composant_animal">
                                <h4>prenom:</h4>
                                <p><?= htmlspecialchars($animal->getFirstname()) ?></p>
                              </div>
                              <div class="card_composant_animal">
                                <h4>genre:</h4>
                                <p><?= htmlspecialchars($animal->getGender()) ?></p>
                              </div>
                              <div class="card_composant_animal">
                                <h4>espèce:</h4>
                                <p><?= htmlspecialchars($animal->getSpecies()) ?></p>
                              </div>
                              <div class="card_composant">
                                <h4>Régime alimentaire:</h4>
                                <p><?= htmlspecialchars($animal->getDiet()) ?></p>
                              </div>
                            </div>
                            <div class="card_flex_marais_component">
                              <div class="card_composant">
                                <h4>Reproduction:</h4>
                                <p><?= htmlspecialchars($animal->getReproduction()) ?></p>
                              </div>
                              <div class="card_composant">
                                <h4>Passage du médecin:</h4>
                                <p>Etat: </p>
                                <p>Date: </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                    <!--card rainette-->
                  <?php if($animal->getIdAnimal() === 8) : ?>
                    <div id="rainette_one" class="slide_marais">
                      <div class="card_rainette">
                        <img src="<?= $allFileA[25];?>" alt="Une girafe qui nous regarde">
                        <h3>la rainette</h3>

                        <!--donnée rainette-->
                        <div class="card_text_marais">
                          <div class="card_flex_marais">
                            <div class="card_flex_marais_component">
                              <div class="card_composant_animal">
                                <h4>prenom:</h4>
                                <p><?= htmlspecialchars($animal->getFirstname()) ?></p>
                              </div>
                              <div class="card_composant_animal">
                                <h4>genre:</h4>
                                <p><?= htmlspecialchars($animal->getGender()) ?></p>
                              </div>
                              <div class="card_composant_animal">
                                <h4>espèce:</h4>
                                <p><?= htmlspecialchars($animal->getSpecies()) ?></p>
                              </div>
                              <div class="card_composant">
                                <h4>Régime alimentaire:</h4>
                                <p><?= htmlspecialchars($animal->getDiet()) ?></p>
                              </div>
                            </div>
                            <div class="card_flex_marais_component">
                              <div class="card_composant">
                                <h4>Reproduction:</h4>
                                <p><?= htmlspecialchars($animal->getReproduction()) ?></p>
                              </div>
                              <div class="card_composant">
                                <h4>Passage du médecin:</h4>
                                <p>Etat de l'animal</p>
                                <p>Voici la date de paassage du medecin</p>
                              </div>
                              <div class="card_composant">
                                <h4>Quantité de nourriture:</h4>
                                <p>voici le grammage</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                    <!--card salamandre-->
                  <?php if($animal->getIdAnimal() === 9) : ?>
                    <div id="salamandre_one" class="slide_marais">
                      <div class="card_salamandre">
                        <img src="<?= $allFileA[27];?>" alt="Un rhinoceros qui nous regarde">
                        <h3>la salamandre</h3>

                        <!--donnée rhinoceros-->
                        <div class="card_text_marais">
                          <div class="card_flex_marais">
                            <div class="card_flex_marais_component">
                              <div class="card_composant_animal">
                                <h4>prenom:</h4>
                                <p><?= htmlspecialchars($animal->getFirstname()) ?></p>
                              </div>
                              <div class="card_composant_animal">
                                <h4>genre:</h4>
                                <p><?= htmlspecialchars($animal->getGender()) ?></p>
                              </div>
                              <div class="card_composant_animal">
                                <h4>espèce:</h4>
                                <p><?= htmlspecialchars($animal->getSpecies()) ?></p>
                              </div>
                              <div class="card_composant">
                                <h4>Régime alimentaire:</h4>
                                <p><?= htmlspecialchars($animal->getDiet()) ?></p>
                              </div>
                            </div>
                            <div class="card_flex_marais_component">
                              <div class="card_composant">
                                <h4>Reproduction:</h4>
                                <p><?= htmlspecialchars($animal->getReproduction()) ?></p>
                              </div>
                              <div class="card_composant">
                                <h4>Passage du médecin:</h4>
                                <p>Etat de l'animal</p>
                                <p>Voici la date de paassage du medecin</p>
                              </div>
                              <div class="card_composant">
                                <h4>Quantité de nourriture:</h4>
                                <p>voici le grammage</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              <?php endforeach; ?>
              </div>
            </div>
          </div>
          <!-- FIN CONTENUE ANIMAL-->
        </div>

      </div>
      <!--====END  ONGLET MARAIS ==== -->
      <!--====START  ONGLET JUNGLE ==== -->
      <div class="onglet_jungle">

        <button class="tab_button" data-tab="jungle">jungle</button>

        <!--Ici le contenu de onglet Jungle-->
        <div id="jungle" class="content">

          <div class="onglet_jungle_img"></div>

          <!--CONTENUE TEXTE IMG JUNGLE-->
          <div class="onglet_jungle_txt">
            <?php
              if (isset($habitats[2])) {
                $description = explode('(delimiteur)', $habitats[0]->getDescriptionHabitat());
                $junglePart1 = htmlspecialchars($description[0]);
                $junglePart2 = htmlspecialchars($description[1] ?? '');
              }
            ;?>
            <div class="img_texte_jungle1">
              <p><?= $junglePart1 ;?></p>
              <div class="img_jungle_1">
                <img src="<?= $allFileH[7] ;?>" alt="" class="img_jung1">
              </div>
            </div>

            <div class="img_texte_jungle2">
              <p><?= $junglePart2 ;?></p>
              <div class="img_jungle_2">
                <img src="<?= $allFileH[8] ;?>" alt="" class="img_jung2">
              </div>
            </div>


          </div>

          <!--CONTENUE ANIMAL-->
          <div class="card_animal_jungle">
            <h2>Ils y Habitent</h2>
            <div class="card_container">
              <!-- START CAROUSEL-->
              <div class="card_carousel">

                <!--BTN CAROUSSEL JUNGLE-->
                <button class="btn_carousel_jungle" id="prev3">&#10096;</button>
                <button class="btn_carousel_jungle" id="next3">&#10097;</button>

                <!--card tapir-->
                <div id="tapir_one" class="slide_jungle active2">
                  <div class="card_tapir">
                    <img src="<?= $allFileA[42];?>" alt="Un caiman qui attrape sa nourriture">
                    <h3>le tapir</h3>

                    <!--donnée tapir-->
                    <div class="card_text_jungle">
                      <div class="card_flex_jungle">

                        <div class="card_flex_jungle_component">
                          <div class="card_composant_animal">
                            <h4>prenom:</h4>
                            <p>TAPI</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>genre:</h4>
                            <p>voici le genre</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>sexe:</h4>
                            <p>voici le sexe</p>
                          </div>
                          <div class="card_composant">
                            <h4>Régime alimentaire:</h4>
                            <p>Voici le régime alimentaire d'un fennec</p>
                          </div>
                        </div>

                        <div class="card_flex_jungle_component">
                          <div class="card_composant">
                            <h4>Reproduction:</h4>
                            <p>Voici la methodes de reproduction d'un fennec</p>
                          </div>
                          <div class="card_composant">
                            <h4>Passage du médecin:</h4>
                            <p>Etat de l'animal</p>
                            <p>Voici la date de paassage du medecin</p>
                          </div>
                          <div class="card_composant">
                            <h4>Quantité de nourriture:</h4>
                            <p>voici le grammage</p>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

                <!--card jaguar-->
                <div id="jaguar_one" class="slide_jungle">
                  <div class="card_jaguar">
                    <img src="<?= $allFileA[34];?>" alt="Une girafe qui nous regarde">
                    <h3>le jaguar</h3>

                    <!--donnée jaguar-->
                    <div class="card_text_jungle">
                      <div class="card_flex_jungle">

                        <div class="card_flex_jungle_component">
                          <div class="card_composant_animal">
                            <h4>prenom:</h4>
                            <p>JAGO</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>genre:</h4>
                            <p>voici le genre</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>sexe:</h4>
                            <p>voici le sexe</p>
                          </div>
                          <div class="card_composant">
                            <h4>Régime alimentaire:</h4>
                            <p>Voici le régime alimentaire d'un fennec</p>
                          </div>
                        </div>

                        <div class="card_flex_jungle_component">
                          <div class="card_composant">
                            <h4>Reproduction:</h4>
                            <p>Voici la methodes de reproduction d'un fennec</p>
                          </div>
                          <div class="card_composant">
                            <h4>Passage du médecin:</h4>
                            <p>Etat de l'animal</p>
                            <p>Voici la date de paassage du medecin</p>
                          </div>
                          <div class="card_composant">
                            <h4>Quantité de nourriture:</h4>
                            <p>voici le grammage</p>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

                <!--card toucan-->
                <div id="toucan_one" class="slide_jungle">
                  <div class="card_toucan">
                    <img src="<?= $allFileA[45];?>" alt="Un rhinoceros qui nous regarde">
                    <h3>le toucan toco</h3>

                    <!--donnée toucan-->
                    <div class="card_text_jungle">
                      <div class="card_flex_jungle">

                        <div class="card_flex_jungle_component">
                          <div class="card_composant_animal">
                            <h4>prenom:</h4>
                            <p>TOCO</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>genre:</h4>
                            <p>voici le genre</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>sexe:</h4>
                            <p>voici le sexe</p>
                          </div>
                          <div class="card_composant">
                            <h4>Régime alimentaire:</h4>
                            <p>Voici le régime alimentaire d'un fennec</p>
                          </div>
                        </div>

                        <div class="card_flex_jungle_component">
                          <div class="card_composant">
                            <h4>Reproduction:</h4>
                            <p>Voici la methodes de reproduction d'un fennec</p>
                          </div>
                          <div class="card_composant">
                            <h4>Passage du médecin:</h4>
                            <p>Etat de l'animal</p>
                            <p>Voici la date de paassage du medecin</p>
                          </div>
                          <div class="card_composant">
                            <h4>Quantité de nourriture:</h4>
                            <p>voici le grammage</p>
                          </div>
                        </div>

                      </div>

                    </div>
                  </div>
                </div>

                <!--card ara rouge-->
                <div id="ara_one" class="slide_jungle">
                  <div class="card_ara">
                    <img src="<?= $allFileA[31];?>" alt="Un rhinoceros qui nous regarde">
                    <h3>l'ara rouge</h3>

                    <!--donnée toucan-->
                    <div class="card_text_jungle">
                      <div class="card_flex_jungle">

                        <div class="card_flex_jungle_component">
                          <div class="card_composant_animal">
                            <h4>prenom:</h4>
                            <p>RIO</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>genre:</h4>
                            <p>voici le genre</p>
                          </div>
                          <div class="card_composant_animal">
                            <h4>sexe:</h4>
                            <p>voici le sexe</p>
                          </div>
                          <div class="card_composant">
                            <h4>Régime alimentaire:</h4>
                            <p>Voici le régime alimentaire d'un fennec</p>
                          </div>
                        </div>

                        <div class="card_flex_jungle_component">
                          <div class="card_composant">
                            <h4>Reproduction:</h4>
                            <p>Voici la methodes de reproduction d'un fennec</p>
                          </div>
                          <div class="card_composant">
                            <h4>Passage du médecin:</h4>
                            <p>Etat de l'animal</p>
                            <p>Voici la date de paassage du medecin</p>
                          </div>
                          <div class="card_composant">
                            <h4>Quantité de nourriture:</h4>
                            <p>voici le grammage</p>
                          </div>
                        </div>

                      </div>

                    </div>
                  </div>
                </div>

              </div>
              <!--END CONTENUE ANIMAL-->
            </div>
          </div>
          <!-- FIN CONTENUE ANIMAL-->
        </div>

      </div>
      <!--====END ONGLET JUNGLE ==== -->
    </div>






  </div>


</section>
<!--END SECTION ONGLET-->