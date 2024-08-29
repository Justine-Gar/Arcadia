
<div class="dash_container">
    <div class="dash_sidebar">
      <h2>dashboard</h2>
      <ul>
        <li><a href="/admin">Home</a></li>
        <li><a href="/admin/habitats" class="active">habitats</a></li>
        <li><a href="/admin/services">services</a></li>
        <li><a href="/admin/animaux">animaux</a></li>
        <li><a href="/admin/comptes">compte</a></li>
        <li><a href="/admin/staff">staff</a></li>
        <li><a href="/admin/veto">veto</a></li>
      </ul>
    </div>

    <div class="main_content">
      <h1>Gestion des Habitats</h1>
      <div class="dash_overview">

      <h2>Liste des Habitats</h2>
            <button class="btn btn-add" onclick="location.href='/admin/habitats/ajouter'">Ajouter un habitat</button>
            <table>
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                  <?php foreach ($habitats as $habitat): /*var_dump($habitat)*/ ?>
                    <tr>
                        <td><?= htmlspecialchars($habitat->getIdHabitat()) ?></td>
                        <td><?= htmlspecialchars($habitat->getNameHabitat()) ?></td>
                        <td><?= htmlspecialchars($habitat->getDescriptionHabitat()) ?></td>
                        <td>
                            <button class="btn btn-edit" onclick="location.href='/admin/habitats/modifier/<?= $habitat->getIdHabitat() ?>'">Modifier</button>
                            <button class="btn btn-delete" onclick="confirmerSuppression(<?= $habitat->getIdHabitat() ?>)">Supprimer</button>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
            </table>
      </div>
    </div>

  </div>
