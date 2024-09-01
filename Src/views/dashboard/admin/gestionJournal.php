<div class="dash_container">
  <div class="dash_sidebar">
    <h2>dashboard</h2>
    <ul>
      <li><a href="/admin">Home</a></li>
      <li><a href="/admin/habitats">habitats</a></li>
      <li><a href="/admin/services">services</a></li>
      <li><a href="/admin/animaux">animaux</a></li>
      <li><a href="/admin/journal" class="active">journal</a></li>
      <li><a href="/admin/comptes">comptes</a></li>
    </ul>
  </div>
 
  <div class="main_content">
    <h1>Gestion des Rapports</h1>
    <button class="btn btn-add" onclick="location.href='/admin/journal/ajouter'">Ajouter un service</button>
    <div class="dash_overview">

      <form action="/admin/journal/filtrer" method="GET" class="filter-form">
        <select name="animal_id">
          <option value="">Tous les Animaux </option>
            <?php foreach ($animals as $animal): ?>
              <option value="<?= $animal->getIdAnimal() ?>" <?= $selectedAnimalId ==$animal->getIdAnimal() ? 'selecte' : '' ?>>
                <?= htmlspecialchars($animal->getFirstname()) ?>
              </option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="start" value="<?= $startDate ?? '' ?>" placeholder="Date">
        <button type="submit">Filtrer</button>
      </form>

      <table>
        <thead>
          <tr>
            <th>Animal</th>
            <th>Santé</th>
            <th>Passage</th>
            <th>Prescription</th>
            <th>Quantity</th>
            <th>Condition Habitat</th>
            <th>User</th>
          </tr>
        </thead>
<!--jointure pour trouver le nom de l'animal et non son id meme chose pour user-->
        <tbody>
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
                <button class="btn btn-edit" onclick="location.href='/admin/journal/modifier/<?= $report->getIdReport() ?>'">Modifier</button>
                <button class="btn btn-delete" onclick="confirmerSuppression(<?= $report->getIdReport() ?>)">Supprimer</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>