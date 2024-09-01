<div class="dash_container">
  <div class="dash_sidebar">
    <h2>dashboard</h2>
    <ul>
      <li><a href="/admin">Home</a></li>
      <li><a href="/admin/habitats" class="active">habitats</a></li>
      <li><a href="/admin/services">services</a></li>
      <li><a href="/admin/animaux">animaux</a></li>
      <li><a href="/admin/journal">journal</a></li>
      <li><a href="/admin/comptes">comptes</a></li>
    </ul>
  </div>

  <div class="main_content">
    <h1>Gestion des Habitats</h1>
    <div class="dash_overview">

      <h2>Liste des Habitats</h2>
      <div class="tableau_actions">
        <button class="tab_btn btn-add" onclick="location.href='/admin/habitats/ajouter'">Ajouter un habitat</button>
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
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="tableau_donnee">
          <?php foreach ($habitats as $habitat): /*var_dump($habitat)*/ ?>
            <tr>
              <td><?= htmlspecialchars($habitat->getIdHabitat()) ?></td>
              <td><?= htmlspecialchars($habitat->getNameHabitat()) ?></td>
              <td><?= htmlspecialchars($habitat->getDescriptionHabitat()) ?></td>
              <td>
                <input type="checkbox" name="habitats[]" value="<?= $habitat->getIdHabitat() ?>">
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </div>

</div>