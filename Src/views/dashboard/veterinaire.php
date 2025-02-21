<div class="dash_container">
  <?php include __DIR__ . '/../partials/_sideVeto.php'; ?>

  <div class="main_content">
    <h1>Tableau de Board</h1>
    <div class="dash_overview">

      <div class="dash_Animal">
        <h2>Animaux populaire</h2>
        <ul>
          <li>Ici sera mis les animaux les plus populaire(au click)</li>
        </ul>
      </div>

      <div class="dash_card">
        <div class="dash_cardIn">
          <h2>Les avis à valider</h2>
          <div class="rapport_container">
            <table class="rapport_table">
              <thead>
                <tr>
                  <th>Animal</th>
                  <th>État de santé</th>
                  <th>Prescription</th>
                  <th>Heure</th>
                  <th>Vétérinaire</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // $todayReports est passé depuis le contrôleur
                if (!empty($todayReports)):
                  foreach ($todayReports as $report):
                    $animal = $report->getAnimalReport();
                    $user = $report->getUserReport();
                ?>
                    <tr>
                      <td><?= htmlspecialchars($animal->getFirstname()) ?></td>
                      <td>
                        <span class="health-status <?= strtolower($report->getHealthStatus()->value) ?>">
                          <?= htmlspecialchars($report->getHealthStatus()->value) ?>
                        </span>
                      </td>
                      <td><?= $report->getPrescription() ? htmlspecialchars($report->getPrescription()) : '-' ?></td>
                      <td><?= $report->getPassage()->format('H:i') ?></td>
                      <td><?= htmlspecialchars($user->getUsername()) ?></td>
                    </tr>
                  <?php
                  endforeach;
                else:
                  ?>
                  <tr>
                    <td colspan="4" class="no-data">Aucun rapport pour aujourd'hui</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>


      </div>

    </div>




  </div>
</div>