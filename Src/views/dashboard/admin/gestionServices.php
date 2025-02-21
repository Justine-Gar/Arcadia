<div class="dash_container">
  <?php include __DIR__ . '/../../partials/_sideAdmin.php'; ?>

  <div class="main_content">
    <h1>Gestion des Services</h1>

    <div class="dash_overview">

      
      <div class="tableau_actions">
        <button type="submit" name="action" value="ajouter" class="tab_btn btn-add" data-type="service">Ajouter</button>
        <button type="submit" name="action" value="upload" class="tab_btn btn-add" data-type="upload" id="openImageUpload">Ajouter Image</button>
        <div>
          <button type="submit" name="action" value="modifier" class="tab_btn btn-edit" data-type="modservice">Modifier</button>
          <button type="submit" name="action" value="supprimer" class="tab_btn btn-delete" data-type="delservice">Supprimer</button>
        </div>
      </div>
      <table class="tableau">
        <thead class="tableau_name">
          <tr>
            <th>File</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="tableau_donnee">
          <?php foreach ($services as $service): ?>
            <tr>
              <td>
                <?php
                $images = $fileSRepository->getByServiceId($service->getIdService());
                //error_log("Images pour le service " . $service->getIdService() . ": " . print_r($images, true));
                if (!empty($images)) {
                  echo '<div class="service-images">'; // Conteneur pour les images
                  foreach ($images as $image) {
                      echo '<img src="' . htmlspecialchars($image->getFilePath()) . '" 
                                alt="' . htmlspecialchars($service->getNameService()) . '" 
                                class="animal-thumbnail">';
                  }
                  echo '</div>';
                } else {
                  echo '<img src="/assets/images/no-image.png" alt="Pas d\'image" class="animal-thumbnail">';
                }
                ?>
              </td>
              <td><?= htmlspecialchars($service->getNameService()) ?></td>
              <td><?= htmlspecialchars($service->getDescriptionService()) ?></td>
              <td>
                <input type="checkbox" name="services[]" value="<?= $service->getIdService() ?>">
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>

    <!--Modal pour ajouter un service-->
    <div id="modalAddService" class="modal">
      <div class="modal_container">
        <div class="model_title">
          <span class="close">&times;</span>
          <h2>Ajouter un Service</h2>
        </div>
        <form id="formAddService">
          <div class="form-group">
            <label for="add_name">Name:</label>
            <input type="text" id="add_name" name="add_name" required>
          </div>
          <div class="form-textera">
            <label for="add_description">Description:</label>
            <textarea name="add_description" id="add_description" rows="4" required></textarea>
          </div>
          <button type="submit" id="btnSubmitAdd">Ajouter</button>
        </form>
      </div>
    </div>

    <!--Modal pour modifier un service-->
    <div id="modalModifyService" class="modal">
      <div class="modal_container">
          <div class="model_title">
            <span class="close">&times;</span>
            <h2>Modifier un Service</h2>
          </div>
          <form id="formModifyService">
            <div class="form-group">
              <label for="modify_name">Name:</label>
              <input type="text" id="modify_name" name="modify_name" required>
            </div>
            <div class="form-textera">
              <label for="modify_description">Description:</label>
              <textarea name="modify_description" id="modify_description" rows="5" required></textarea>
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
          <h2>Ajouter une image<span id="serviceName"></span></h2>
        </div>
        <form id="formAddImage" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="file_id_service">Selectionner un service :</label>
            <select name="file_id_service" id="file_id_service">
              <?php foreach ($services as $service): ?>
                <option value="<?= $service->getIdService() ?>">
                  <?= htmlspecialchars($service->getNameService()) ?>
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

          <button type="submit" class="btn-submit" id="btnSubmitAddFile">Ajouter l'image</button>
        </form>
      </div>
    </div>

  </div>

</div>