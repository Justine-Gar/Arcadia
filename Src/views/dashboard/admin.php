<div class="dash_container">
    <?php include __DIR__ . '/../partials/_sideAdmin.php'; ?>

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
                    <h2>Compte-rendu du Jour</h2>
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

                <div class="dash_cardIn">
                    <h2>Gestion des horaires</h2>
                    <div class="horaires-container">
                        <button class="tab_btn btn-edit" id="editHorairesBtn">Modifier les horaires</button>

                        <table class="horaires-table">
                            <tbody>
                                <?php foreach ($timetables as $timetable): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($timetable->getDays()) ?></td>
                                        <td><?= htmlspecialchars($timetable->getOpenHours()) ?> - <?= htmlspecialchars($timetable->getCloseHours()) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Ajout de l'input caché pour les données -->
                        <input type="hidden" id="timetablesData" value='<?php echo json_encode(array_reduce($timetables, function ($carry, $item) {
                                                                            $carry[$item->getDays()] = [
                                                                                'id' => $item->getIdTimetable(),
                                                                                'open' => $item->getOpenHours(),
                                                                                'close' => $item->getCloseHours()
                                                                            ];
                                                                            return $carry;
                                                                        }, [])); ?>'>
                    </div>
                </div> 
            </div>
            
        </div>



        <!-- Modal Edition Horaire -->
        <div id="modalEditHoraire" class="modal">
            <div class="modal_container">
                <div class="model_title">
                    <span class="close">&times;</span>
                    <h2>Modifier les horaires</h2>
                </div>
                <div id="modalMessage" class="modal-message"></div>
                <form id="formEditHoraire">
                    <div class="form-group">
                        <label for="select_day">Jour à modifier:</label>
                        <select id="select_day" name="select_day" required>
                            <option value="">Sélectionnez un jour</option>
                            <option value="Lundi">Lundi</option>
                            <option value="Mardi">Mardi</option>
                            <option value="Mercredi">Mercredi</option>
                            <option value="Jeudi">Jeudi</option>
                            <option value="Vendredi">Vendredi</option>
                            <option value="Samedi">Samedi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_open">Heure d'ouverture:</label>
                        <select id="edit_open" name="edit_open" required>
                            <option value="">Sélectionnez une heure</option>
                            <?php
                            // matin
                            for ($h = 6; $h < 13; $h++) {
                                printf('<option value="%02d:00">%02d:00</option>', $h, $h);
                                printf('<option value="%02d:30">%02d:30</option>', $h, $h);
                            }
                            // Ajout de fermeture
                            echo '<option value="Fermé">Fermé</option>';
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_close">Heure de fermeture:</label>
                        <select id="edit_close" name="edit_close" required>
                            <option value="">Sélectionnez une heure</option>
                            <?php
                            // Aprem
                            for ($h = 13; $h < 22; $h++) {
                                printf('<option value="%02d:00">%02d:00</option>', $h, $h);
                                printf('<option value="%02d:30">%02d:30</option>', $h, $h);
                            }
                            // Ajout de fermeture
                            echo '<option value="Fermé">Fermé</option>';
                            echo '<option value="-">-</option>';
                            ?>
                        </select>
                    </div>
                    <button type="submit" id="btnSubmitEdit" class="btn-submit">Enregistrer</button>
                </form>

            </div>
        </div>
    </div>
</div>