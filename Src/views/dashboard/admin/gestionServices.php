<div class="dash_container">
  <div class="dash_sidebar">
    <h2>dashboard</h2>
    <ul>
      <li><a href="/admin">Home</a></li>
      <li><a href="/admin/habitats">habitats</a></li>
      <li><a href="/admin/services" class="active">services</a></li>
      <li><a href="/admin/animaux">animaux</a></li>
      <li><a href="/admin/journal">journal</a></li>
      <li><a href="/admin/comptes">comptes</a></li>
    </ul>
  </div>

  <div class="main_content">
    <h1>Gestion des Services</h1>

    <div class="dash_overview">

      <h2>Liste des Services</h2>
      <div class="tableau_actions">
        <button class="tab_btn btn-add" onclick="location.href='/admin/services/ajouter'">Ajouter un service</button>
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
          <?php foreach ($services as $service): ?>
            <tr>
              <td><?= htmlspecialchars($service->getIdService()) ?></td>
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
  </div>

</div>