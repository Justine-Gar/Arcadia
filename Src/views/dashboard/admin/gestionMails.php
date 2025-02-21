<div class="dash_container">
  <?php include __DIR__ . '/../../partials/_sideAdmin.php'; ?>

  <div class="main_content">
    <div class="header-container">
        <h1>Boite Mail</h1>
        <div class="mail-stats">
            <span class="total-mails">Total : <?= count($mails) ?></span>
            <span class="unread-mails">Non lus : <?= count(array_filter($mails, fn($mail) => $mail['status'] === 'unread')) ?></span>
        </div>
    </div>

    <?php if (empty($mails)): ?>
        <div class="empty-state">
            <p>Aucun message dans la boîte mail</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="mail-table">
                <thead>
                    <tr>
                        <th class="status-col">Statut</th>
                        <th class="title-col">Titre</th>
                        <th class="email-col">Email</th>
                        <th class="date-col">Date</th>
                        <th class="message-col">Message</th>
                        <th class="actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mails as $mail): ?>
                        <tr class="<?= $mail['status'] === 'unread' ? 'unread-row' : '' ?>">
                            <td class="status-col">
                                <span class="status-indicator <?= $mail['status'] === 'unread' ? 'unread' : 'read' ?>"></span>
                            </td>
                            <td class="title-col"><?= htmlspecialchars($mail['title']) ?></td>
                            <td class="email-col"><?= htmlspecialchars($mail['email']) ?></td>
                            <td class="date-col">
                                <?= (new DateTime($mail['date']))->format('d/m/Y H:i') ?>
                            </td>
                            <td class="message-col">
                                <div class="message-preview">
                                    <?= htmlspecialchars(substr($mail['description'], 0, 50)) ?>
                                    <?= strlen($mail['description']) > 50 ? '...' : '' ?>
                                </div>
                                <div class="message-full" style="display: none;">
                                    <?= nl2br(htmlspecialchars($mail['description'])) ?>
                                </div>
                            </td>
                            <td class="actions-col">
                                <div class="action-buttons">
                                    <?php if ($mail['status'] === 'unread'): ?>
                                        <button onclick="markAsRead('<?= $mail['id'] ?>')" class="btn-action btn-read" title="Marquer comme lu">
                                            <i class="fas fa-envelope-open"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button onclick="viewMessage(this)" class="btn-action btn-view" title="Voir le message">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="deleteMail('<?= $mail['id'] ?>')" class="btn-action btn-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
  </div>

  <!-- Modal pour afficher le message complet -->
  <div id="messageModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modalTitle"></h2>
        <div id="modalEmail"></div>
        <div id="modalMessage"></div>
    </div>
  </div>

</div>