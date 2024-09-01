<div class="dash_container">
  <div class="dash_sidebar">
    <h2>dashboard</h2>
    <ul>
      <li><a href="/admin">Home</a></li>
      <li><a href="/admin/habitats">habitats</a></li>
      <li><a href="/admin/services">services</a></li>
      <li><a href="/admin/animaux">animaux</a></li>
      <li><a href="/admin/journal">journal</a></li>
      <li><a href="/admin/comptes" class="active">comptes</a></li>
    </ul>
  </div>

  <div class="main_content">
    <h1>Gestion des Comptes Arcadia</h1>
    <button class="btn btn-add" onclick="openModal()">Ajouter un compte</button>
    <div class="dash_overview"> 
      <table>
        <thead>
          <tr>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user->getUsername()) ?></td>
              <td><?= htmlspecialchars($user->getEmail()) ?></td>
              <td><?= htmlspecialchars($user->getRole()->value) ?></td>
              <td>
                <button class="btn btn-edit" onclick="location.href='/admin/comptes/modifier/<?= $user->getIdUser() ?>'">Modifier</button>
                <button class="btn btn-delete" onclick="confirmerSuppression(<?= $user->getIdUser() ?>)">Supprimer</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

<!-- Modal pour ajouter un utilisateur -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2>Ajouter un nouveau compte</h2>
      <form id="addUserForm" action="/admin/comptes/ajouter" method="POST">
        <div class="form-group">
          <label for="username">Nom d'utilisateur:</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Mot de passe:</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
          <label for="role">Rôle:</label>
          <select id="role" name="role" required>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
      </form>
    </div>
  </div>
</div>

<script src="./modelComptes.js"></script>
