<div class="dash_container">
    <div class="dash_sidebar">
      <h2>dashboard</h2>
      <ul>
        <li><a href="/admin">Home</a></li>
        <li><a href="/admin/habitats">habitats</a></li>
        <li><a href="/admin/services">services</a></li>
        <li><a href="/admin/animaux">animaux</a></li>
        <li><a href="/admin/journal" class="active">journal</a></li>        
        <li><a href="/admin/staff">staff</a></li>
        <li><a href="/admin/veto">veto</a></li>
      </ul>
    </div>

    <div class="main_content">
      <h1>Gestion des Rapports</h1>
      <div class="dash_overview">

      <h2>Liste des Services</h2>
            <button class="btn btn-add" onclick="location.href='/admin/services/ajouter'">Ajouter un service</button>
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
                  <?php foreach ($rewiews as $review):?>
                    <tr>
                        <td><?= htmlspecialchars($service->getIdService()) ?></td>
                        <td><?= htmlspecialchars($service->getNameService()) ?></td>
                        <td><?= htmlspecialchars($service->getDescriptionService()) ?></td>
                        <td>
                            <button class="btn btn-edit" onclick="location.href='/admin/services/modifier/<?= $service->getIdService() ?>'">Modifier</button>
                            <button class="btn btn-delete" onclick="confirmerSuppression(<?= $service->getIdService() ?>)">Supprimer</button>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
            </table>
      </div>
    </div>

  </div>
