<div class="dash_container">
  <?php include __DIR__ . '/../../partials/_sideAdmin.php'; ?>

  <div class="main_content">
    <h1>Gestion des Rapports</h1>

    <div class="dash_overview">

      <div class="tableau_actions">
        <button class="tab_btn btn-add" onclick="location.href='/admin/services/ajouter'">Ajouter un rapport</button>
        <div>
          <button type="submit" name="action" value="modifier" class="tab_btn btn-edit">Modifier</button>
          <button type="submit" name="action" value="supprimer" class="tab_btn btn-delete">Supprimer</button>
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
              <td><?= htmlspecialchars($report->getAnimal() ? $report->getAnimal()->getFirstname() : 'N/A') ?></td>
              <td><?= htmlspecialchars($report->getHealthStatus()->value) ?></td>
              <td><?= $report->getPassage()->format('Y-m-d H:i') ?></td>
              <td><?= htmlspecialchars($report->getPrescription() ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($report->getQuantity() ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($report->getHabitatCondition() ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($report->getUser() ? $report->getUser()->getUsername() : 'N/A') ?></td>
              <td>
                <input type="checkbox" name="reports[]" value="<?= $report->getIdReport() ?>">
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="pagination">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&animal_id=<?= $animalId ?>&start=<?= $startDate ?>" 
            class="<?= $i == $pageActuelle ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
      </div>
    </div>
  </div>

</div>