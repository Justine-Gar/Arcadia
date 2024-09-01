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
    <h1>Gestion des Comptes</h1>

    <?php if (!empty($message)): ?>
      <div class="alert alert-<?= $messageType ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <div class="dash_overview">
      <div class="tableau_actions">
        <button class="tab_btn btn-add" id="toggleFormBtn">Ajouter un compte</button>
        <div>
          <button type="submit" name="action" value="modifier" class="tab_btn btn-edit">Modifier</button>
          <button type="submit" name="action" value="supprimer" class="tab_btn btn-delete">Supprimer</button>
        </div>
      </div>
      <!-- Formulaire d'ajout de compte (initialement caché) -->
      <div id="addUserForm">
        <h2>Ajouter un nouveau compte</h2>
        <form action="/admin/comptes" method="POST">
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
              <?php foreach ($roles as $role): ?>
                <option value="<?= $role->value ?>"><?= $role->value ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
      </div>

      <table class="tableau">
        <thead class="tableau_name">
          <tr>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="tableau_donnee">
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user->getUsername()) ?></td>
              <td><?= htmlspecialchars($user->getEmailUser()) ?></td>
              <td><?= htmlspecialchars($user->getRole()->value) ?></td>
              <td>
                <input type="checkbox" name="comptes[]" value="<?= $user->getIdUser() ?>">
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>


    </div>
  </div>

</div>

