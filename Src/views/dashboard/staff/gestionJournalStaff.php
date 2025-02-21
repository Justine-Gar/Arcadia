<div class="dash_container">
  <?php include __DIR__ . '/../../partials/_sideStaff.php'; ?>

  <div class="main_content">
    <h1>Gestion des Rapports</h1>

    <div class="dash_overview">
      
      <form action="/staff/journal/filtrer" method="GET" class="filter-form">
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

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php
            // Lien précédent
            if ($pageActuelle > 1): ?>
                <a href="?page=<?= $pageActuelle - 1 ?><?= 
                    // Préserver les paramètres de filtrage
                    (isset($_GET['animal_id']) ? '&animal_id=' . htmlspecialchars($_GET['animal_id']) : '') . 
                    (isset($_GET['start']) ? '&start=' . htmlspecialchars($_GET['start']) : '') 
                ?>" class="pagination-button">&laquo; Précédent</a>
            <?php endif; ?>

            <!-- Afficher le total -->
            <span class="pagination-info">
                Page <?= $pageActuelle ?> sur <?= $totalPages ?>
            </span>

            <?php
            // Lien suivant
            if ($pageActuelle < $totalPages): ?>
                <a href="?page=<?= $pageActuelle + 1 ?><?= 
                    // Préserver les paramètres de filtrage
                    (isset($_GET['animal_id']) ? '&animal_id=' . htmlspecialchars($_GET['animal_id']) : '') . 
                    (isset($_GET['start']) ? '&start=' . htmlspecialchars($_GET['start']) : '') 
                ?>" class="pagination-button">Suivant &raquo;</a>
            <?php endif; ?>
        </div>
      <?php endif; ?>

    </div>

    

  </div>

  
</div>
