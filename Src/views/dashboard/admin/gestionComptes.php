<div class="dash_container">
  <?php include __DIR__ . '/../../partials/_sideAdmin.php'; ?>

  <div class="main_content">
    <h1>Gestion des Comptes</h1>

    <div class="dash_overview">

      <div class="tableau_actions">
        <button type="submit" name="action" value="ajouter" class="tab_btn btn-add" data-type="user">Ajouter</button>
        <div>
          <button type="submit" name="action" value="modifier" class="tab_btn btn-edit" data-type="moduser">Modifier</button>
          <button type="submit" name="action" value="supprimer" class="tab_btn btn-delete" data-type="deluser">Supprimer</button>
        </div>
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
                <input type="checkbox" name="selected_users[]" value="<?= $user->getIdUser() ?>">
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      
    </div>

    <!--Modal pour ajouter un User-->
    <div id="modalAddUser" class="modal">
      <div class="modal_container">
        <div class="model_title">
          <span class="close">&times;</span>
          <h2>Ajouter un utilisateur</h2>
        </div>
        <form id="formAddUser">
          <div class="form-group">
            <label for="add_username">Nom d'utilisateur:</label>
            <input type="text" id="add_username" name="add_username" required>
          </div>
          <div class="form-group">
            <label for="add_email">Email:</label>
            <input type="email" id="add_email" name="add_email" required>
          </div>
          <div class="form-group">
            <label for="add_password">Mot de passe:</label>
            <input type="password" id="add_password" name="add_password" autocomplete="new-password" required>
          </div>
          <div class="form-group">
            <label for="add_role">Rôle:</label>
            <select id="add_role" name="add_role" required>
              <?php foreach ($roles as $role): ?>
                <option value="<?= $role->value ?>"><?= $role->value ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" id="btnSubmitAdd">Ajouter</button>
        </form>
      </div>
    </div>

  <!--Modal pour modifier un User-->
    <div id="modalModifyUser" class="modal">
      <div class="modal_container">
        <div class="model_title">
          <span class="close">&times;</span>
          <h2>Modifier un utilisateur</h2>
        </div>
        <form id="formModifyUser">
          <input type="hidden" id="userId" name="userId">
          <div class="form-group">
            <label for="modify_username">Nom d'utilisateur:</label>
            <input type="text" id="modify_username" name="modify_username" required>
          </div>
          <div class="form-group">
            <label for="modify_email">Email:</label>
            <input type="email" id="modify_email" name="modify_email" required>
          </div>
          <div class="form-group">
            <label for="modify_password">Mot de passe:</label>
            <input type="password" id="modify_password" name="modify_password" autocomplete="new-password">
          </div>
          <div class="form-group">
            <label for="modify_role">Rôle:</label>
            <select id="modify_role" name="modify_role" required>
              <?php foreach ($roles as $role): ?>
                <option value="<?= $role->value ?>"><?= $role->value ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" id="btnSubmitModify">Modifier</button>
        </form>
      </div>
    </div>

  </div>
</div>