<div class="dash_container">
  <?php include __DIR__ . '/../../partials/_sideStaff.php'; ?>

  <div class="main_content">
    <h1>Gestion des Avis</h1>

    <div class="dash_overview">

      <?php
      $statusCounts = [
        'all' => count($reviews),
        'En attente' => 0,
        'Approuvé' => 0,
        'Rejeté' => 0,
        'Supprimer' => 0
      ];

      foreach ($reviews as $review) {
        $status = $review->getStatus();
        $statusCounts[$status]++;
      }
      ?>
      <div class="tableau_actions">
        <div>
          <button class="status_tab" data-status="all" data-count="<?= $statusCounts['all'] ?>">Tous les Avis</button>
          <button class="status_tab active" data-status="En attente" data-count="<?= $statusCounts['En attente'] ?>">En attente</button>
          <button class="status_tab" data-status="Approuvé" data-count="<?= $statusCounts['Approuvé'] ?>">Approuvés</button>
          <button class="status_tab" data-status="Rejeté" data-count="<?= $statusCounts['Rejeté'] ?>">Rejetés</button>
          <button class="status_tab" data-status="Supprimer" data-count="<?= $statusCounts['Supprimer'] ?>">Supprimés</button>
        </div>
      </div>
      <table class="tableau">
        <thead class="tableau_name">
          <tr>
            <th>Client</th>
            <th>Note</th>
            <th>Avis</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="tableau_donnee">
          <?php foreach ($reviews as $review): ?>
            <tr id="review_row_<?= $review->getIdReview() ?>"
            class="review-row"
            data-review-status="<?= htmlspecialchars($review->getStatus()) ?>">
              <td><?= htmlspecialchars($review->getNameReview()) ?></td>
              <td>
                <div class="rating">
                  <?php
                  $score = $review->getScore();
                  for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $score) {
                      echo '<span class="star filled">★</span>';
                    } else {
                      echo '<span class="star">★</span>';
                    }
                  }
                  ?>
                </div>
              </td>
              <td><?= htmlspecialchars($review->getDescriptionReview()) ?></td>
              <td>
                <span class="status-badge status-<?= strtolower($review->getStatus()) ?>">
                  <?= htmlspecialchars($review->getStatus()) ?>
                </span>
              </td>
              <td class="actions">
                <?php if ($review->getStatus() === "En attente"): ?>
                  <button id="approveBtn_<?= $review->getIdReview() ?>"
                    class="action-btn approve-btn" type="button">
                    Approuver
                  </button>
                  <button id="rejectBtn_<?= $review->getIdReview() ?>"
                    class="action-btn reject-btn" type="button">
                    Rejeter
                  </button>
                <?php endif; ?>

                <?php if ($review->getStatus() !== "Supprimer"): ?>
                  <button id="deleteBtn_<?= $review->getIdReview() ?>"
                    class="action-btn delete-btn" type="button">
                    Supprimer
                  </button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>


    </div>

  </div>
</div>