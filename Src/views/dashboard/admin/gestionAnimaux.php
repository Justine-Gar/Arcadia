<div class="dash_container">
  <div class="dash_sidebar">
    <h2>dashboard</h2>
    <ul>
      <li><a href="/admin">Home</a></li>
      <li><a href="/admin/habitats">habitats</a></li>
      <li><a href="/admin/services">services</a></li>
      <li><a href="/admin/animaux" class="active">animaux</a></li>
      <li><a href="/admin/journal">journal</a></li>
      <li><a href="/admin/staff">staff</a></li>
      <li><a href="/admin/veto">veto</a></li>
    </ul>
  </div>

  <div class="main_content">
    <h1>Gestion des Animaux</h1>
    <div class="dash_overview">

      <h2>Liste des Animaux</h2>
      <button class="btn btn-add" onclick="location.href='/admin/animaux/ajouter'">Ajouter un animal</button>
      <table>
        <thead>
          <tr>
            <th>Numero</th>
            <th>Nom</th>
            <th>Genre</th>
            <th>Species</th>
            <th>Diet</th>
            <th>Reproduction</th>
            <th>Habitat</th>
          </tr>
        </thead>
        <tbody>
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
                <button class="btn btn-edit" onclick="location.href='/admin/animaux/modifier/<?= $animal->getIdAnimal() ?>'">Modifier</button>
                <button class="btn btn-delete" onclick="confirmerSuppression(<?= $animal->getIdAnimal() ?>)">Supprimer</button>
              </td>

            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>