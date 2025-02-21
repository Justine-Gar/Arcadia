<div class="dash_container">
  <?php include __DIR__ . '/../../partials/_sideAdmin.php'; ?>

  <div class="main_content">
    <h1>Gestion des Animaux</h1>

    <div class="dash_overview">

      <div class="tableau_actions">
        <button type="submit" name="action" value="ajouter" class="tab_btn btn-add" data-type="animal">Ajouter</button>
        <button type="submit" name="action" value="upload" class="tab_btn btn-add" data-type="upload" id="openImageUpload">Ajouter Image</button>
        <div>
          <button type="submit" name="action" value="modifier" class="tab_btn btn-edit" data-type="modanimal">Modifier</button>
          <button type="submit" name="action" value="supprimer" class="tab_btn btn-delete" data-type="delanimal">Supprimer</button>
        </div>
      </div>

      <table class="tableau">

        <thead class="tableau_name">
          <tr>
            <th>File</th>
            <th>Nom</th>
            <th>Genre</th>
            <th>Species</th>
            <th>Diet</th>
            <th>Reproduction</th>
            <th>Habitat</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody class="tableau_donnee">
          <?php foreach ($animals as $animal): ?>
            <tr>
              <td>
                <?php
                $images = $fileARepository->getByAnimalId($animal->getIdAnimal());
                if (!empty($images)) {
                  $firstImage = $images[0];
                  echo '<img src="' . htmlspecialchars($firstImage->getFilePath()) . '" 
                                alt="' . htmlspecialchars($animal->getFirstname()) . '" 
                                class="animal-thumbnail">';
                } else {
                  echo '<img src="/assets/images/no-image.png" alt="Pas d\'image" class="animal-thumbnail">';
                }
                ?>
              </td>
              <td><?= htmlspecialchars($animal->getFirstname()) ?></td>
              <td><?= htmlspecialchars($animal->getGender()) ?></td>
              <td><?= htmlspecialchars($animal->getSpecies()) ?></td>
              <td><?= htmlspecialchars($animal->getDiet()) ?></td>
              <td><?= htmlspecialchars($animal->getReproduction()) ?></td>
              <td><?= htmlspecialchars($animal->getIdHabitat()) ?></td>
              <td>
                <input type="checkbox" name="animals[]" value="<?= $animal->getIdAnimal() ?>">
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>


      </table>
      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div class="pagination">
          <?php
          // Lien précédent
          if ($pageActuelle > 1): ?>
            <a href="?page=<?= $pageActuelle - 1 ?>" class="pagination-button">&laquo; Précédent</a>
          <?php endif; ?>


          <!-- Afficher le total -->
          <span class="pagination-info">
            Page <?= $pageActuelle ?> sur <?= $totalPages ?>
          </span>

          <?php
          // Lien suivant
          if ($pageActuelle < $totalPages): ?>
            <a href="?page=<?= $pageActuelle + 1 ?>" class="pagination-button">Suivant &raquo;</a>
          <?php endif; ?>

        </div>
      <?php endif; ?>


    </div>

    <!--Modal pour ajouter un animal-->
    <div id="modalAddAnimal" class="modal">
      <div class="modal_conter">
        <div class="model_title">
          <span class="close">&times;</span>
          <h2>Ajouter un Animal</h2>
        </div>
        <form id="formAddAnimal" method="POST" enctype="multipart/form-data">

          <div class="form-group">
            <label for="add_id_habitat">Habitat :</label>
            <select name="add_id_habitat" id="add_id_habitat" required>
              <?php foreach ($habitats as $habitat): ?>
                <option value="<?= $habitat->getIdHabitat() ?>">
                  <?= htmlspecialchars($habitat->getNameHabitat()) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="add_firstname">Prénom :</label>
            <input type="text" name="add_firstname" id="add_firstname" required>
          </div>

          <div class="form-group">
            <label for="add_gender">Genre :</label>
            <input type="text" name="add_gender" id="add_gender" required>
          </div>

          <div class="form-group">
            <label for="add_species">Espèce:</label>
            <input type="text" name="add_species" id="add_species" required>
          </div>

          <div class="form-textera">
            <label for="add_diet">La Nourriture :</label>
            <textarea name="add_diet" id="add_diet" rows="4" required></textarea>
          </div>

          <div class="form-textera">
            <label for="add_reproduction">La Reproduction :</label>
            <textarea name="add_reproduction" id="add_reproduction"rows="4" required></textarea>
          </div>

          <button type="submit" id="btnSubmitAdd">Ajouter</button>

        </form>
      </div>
    </div>

    <!--Modal pour modifier un animal-->
    <div id="modalModifyAnimal" class="modal">
      <div class="modal_conter">
        <div class="model_title">
          <span class="close">&times;</span>
          <h2>Modifier un Animal</h2>
        </div>
        <form id="formModifyAnimal" method="POST">

          <div class="form-group">
            <label for="modify_id_habitat">Habitat :</label>
            <select name="modify_id_habitat" id="modify_id_habitat" required>
              <?php foreach ($habitats as $habitat): ?>
                <option value="<?= $habitat->getIdHabitat() ?>">
                  <?= htmlspecialchars($habitat->getNameHabitat()) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="modify_firstname">Prénom :</label>
            <input type="text" name="modify_firstname" id="modify_firstname" required>
          </div>

          <div class="form-group">
            <label for="modify_gender">Genre :</label>
            <input type="text" name="modify_gender" id="modify_gender" required>
          </div>

          <div class="form-group">
            <label for="modify_species">Espèce:</label>
            <input type="text" name="modify_species" id="modify_species" required>
          </div>

          <div class="form-textera">
            <label for="modify_diet">La Nourriture :</label>
            <textarea name="modify_diet" id="modify_diet" rows="4" required></textarea>
          </div>

          <div class="form-textera">
            <label for="modify_reproduction">La Reproduction :</label>
            <textarea name="modify_reproduction" id="modify_reproduction" rows="4" required></textarea>
          </div>

          <button type="submit" id="btnSubmitModify">Modifier</button>

        </form>
      </div>
    </div>

    <!-- Ajoute Image -->
    <div id="modalAddImage" class="modal">
      <div class="modal_petit">
        <div class="model_title">
          <span class="close">&times;</span>
          <h2>Ajouter une image<span id="animalName"></span></h2>
        </div>
        <form id="formAddImage" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="file_id_animal">Selectionner l'animal :</label>
            <select name="file_id_animal" id="file_id_animal">
              <?php foreach ($allAnimals as $allAnimal): ?>
                <option value="<?= $allAnimal->getIdAnimal() ?>">
                  <?= htmlspecialchars($allAnimal->getFirstname()) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-textera">
            <label for="file_name">Nom de l'image:</label>
            <input type="text" id="file_name" name="file_name" required>
          </div>

          <div class="form-textera">
            <label for="image_file">Sélectionner l'image:</label>
            <input type="file" id="image_file" name="image[]" accept="image/*" multiple required>
            <small>Formats acceptés : JPG, PNG, GIF, AVIF, WEBP - Max 10Mo</small>
          </div>

          <button type="submit" class="btn-submit" id="btnSubmitAdd">Ajouter l'image</button>
        </form>
      </div>
    </div>

  </div>

</div>