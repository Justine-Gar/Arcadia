<div class="dash_container">
  <?php include __DIR__ . '/../../partials/_sideAdmin.php'; ?>

  <div class="main_content">
    <h1>Gestion des Animaux</h1>

    <div class="dash_overview">
      <h2>Liste des Animaux</h2>
      <div class="tableau_actions">
        <button class="tab_btn btn-add" onclick="location.href='/admin/animaux/ajouter'">Ajouter un animal</button>
        <div>
          <button type="submit" name="action" value="modifier" class="tab_btn btn-edit">Modifier</button>
          <button type="submit" name="action" value="supprimer" class="tab_btn btn-delete">Supprimer</button>
        </div>
      </div>
      <table class="tableau">

        <thead class="tableau_name">
          <tr>
            <th>Numero</th>
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
              <td><?= htmlspecialchars($animal->getIdAnimal()) ?></td>
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
      <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?page=<?= $i ?>" class="<?= $i == $pageActuelle ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>

      
    </div>
  </div>

</div>