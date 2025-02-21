<div class="dash_container">
  <?php include __DIR__ . '/../../partials/_sideAdmin.php'; ?>

  <div class="main_content">
    <h1>Gestion des Rapports</h1>

    <div class="dash_overview">
      
        <div class="tableau_actions">
          <button type="submit" name="action" value="ajouter" class="tab_btn btn-add" data-type="report">Ajouter</button>
          <div>
            <button type="submit" name="action" value="modifier" class="tab_btn btn-edit" data-type="modreport">Modifier</button>
            <button type="submit" name="action" value="supprimer" class="tab_btn btn-delete" data-type="delreport">Supprimer</button>
          </div>
        </div>

      

      <form action="/admin/journal/filtrer" method="GET" class="filter-form">
        <select name="animal_id">
          <option value="">Tous les Animaux </option>
          <?php foreach ($animals as $animal): ?>
            <option value="<?= $animal->getIdAnimal() ?>" <?= $selectedAnimalId == $animal->getIdAnimal() ? 'selecte' : '' ?>>
              <?= htmlspecialchars($animal->getFirstname()) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="date" name="start" value="<?= $startDate ?? '' ?>" placeholder="Date">
        <button type="submit">Filtrer</button>
      </form>

      <table class="tableau">
        <thead class="tableau_name">
          <tr>
            <th>Animal</th>
            <th>Santé</th>
            <th>Passage</th>
            <th>Prescription</th>
            <th>Quantity</th>
            <th>Condition Habitat</th>
            <th>User</th>
            <th>Action</th>
          </tr>
        </thead>
        <!--jointure pour trouver le nom de l'animal et non son id meme chose pour user-->
        <tbody class="tableau_donnee">
          <?php foreach ($reports as $report): ?>
            <tr>
              <td><?= htmlspecialchars($report->getAnimalReport() ? $report->getAnimalReport()->getFirstname() : 'N/A') ?></td>
              <td><?= htmlspecialchars($report->getHealthStatus()->value) ?></td>
              <td><?= $report->getPassage()->format('Y-m-d H:i') ?></td>
              <td><?= htmlspecialchars($report->getPrescription() ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($report->getQuantity() ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($report->getHabitatCondition() ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($report->getUserReport() ? $report->getUserReport()->getUsername() : 'N/A') ?></td>
              <td>
                <input type="checkbox" name="reports[]" value="<?= $report->getIdReport() ?>">
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="pagination">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&animal_id=<?= $animalId ?>&start=<?= $startDate ?>"
              class="<?= $i == $pageActuelle ? 'active' : '' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>

    </div>

    <!--Modal pour ajouter un Report-->
    <div id="modalAddReport" class="modal">
      <div class="modal_conter">
        <div class="model_title">
          <span class="close">&times;</span>
          <h2>Ajouter un Rapport</h2>
        </div>
        <form id="formAddReport" method="POST" data-role="veto">
          <div class="form-group">
            <label for="add_id_animal">Animal :</label>
            <select name="add_id_animal" id="add_id_animal" required>
              <?php foreach ($animals as $animal): ?>
                <option value="<?= $animal->getIdAnimal() ?>"
                  <?= $selectedAnimalId == $animal->getIdAnimal() ? 'selecte' : '' ?>>
                  <?= htmlspecialchars($animal->getFirstname()) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="add_health_status">Santé :</label>
            <select name="add_health_status" id="add_health_status" required>
              <?php foreach ($healthStatus as $health_status): ?>
                <option value="<?= $health_status->value ?>"><?= $health_status->value ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-textera">
            <label for="add_prescription">Prescription :</label>
            <textarea name="add_prescription" id="add_prescription" rows="4"></textarea>
          </div>

          <div class="form-group">
            <label for="add_quantity">Quantité :</label>
            <input type="text" name="add_quantity" id="add_quantity">
          </div>

          <div class="form-textera">
            <label for="add_habitat_condition">Condition de l'habitat :</label>
            <textarea name="add_habitat_condition" id="add_habitat_condition" rows="4"></textarea>
          </div>

          <button type="submit" id="btnSubmitAdd">Ajouter</button>

        </form>
      </div>
    </div>

    <!--Modal pour modifier un Report-->
    <div id="modalModifyReport" class="modal">
      <div class="modal_moyen">
        <div class="model_title">
          <span class="close">&times;</span>
          <h2>Modifier un Rapport</h2>
        </div>
        <form id="formModifyReport" method="POST" data-role="veto">
          <div class="form-group">
            <label for="modify_id_animal">Animal :</label>
            <input type="text" name="modify_id_animal" id="modify_id_animal" readonly>
          </div>

          <div class="form-group">
            <label for="modify_health_status">Santé :</label>
            <select name="modify_health_status" id="modify_health_status" required>
              <?php foreach ($healthStatus as $health_status): ?>
                <option value="<?= $health_status->value ?>"><?= $health_status->value ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-textera">
            <label for="modify_prescription">Prescription :</label>
            <textarea name="modify_prescription" id="modify_prescription"></textarea>
          </div>

          <div class="form-group">
            <label for="modify_quantity">Quantité :</label>
            <input type="text" name="modify_quantity" id="modify_quantity">
          </div>

          <div class="form-textera">
            <label for="modify_habitat_condition">Condition de l'habitat :</label>
            <textarea name="modify_habitat_condition" id="modify_habitat_condition"></textarea>
          </div>

          <button type="submit" id="btnSubmitModify">Modifier</button>

        </form>
      </div>
    </div>

  </div>

  
</div>
