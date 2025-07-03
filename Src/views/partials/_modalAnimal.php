<!-- Modal Animal -->
<div id="animalModal" class="modal-animal-overlay">
  <div class="modal-animal">
    <span class="close" onclick="closeAnimalModal()">&times;</span>

    <div class="modal-animal-content">
      <!-- Section Carrousel -->
      <div class="carousel-section">
        <div class="carousel-container">
          <div class="carousel-slides" id="carouselSlides">
            <!-- Les images seront ajoutées dynamiquement -->
          </div>

          <button class="carousel-btn prev" onclick="changeSlide(-1)">&#10094;</button>
          <button class="carousel-btn next" onclick="changeSlide(1)">&#10095;</button>

          <div class="carousel-indicators" id="carouselIndicators">
            <!-- Les indicateurs seront ajoutés dynamiquement -->
          </div>
        </div>
      </div>

      <!-- Section Informations -->
      <div class="info-section">
        <div class="animal-header">
          <h1 class="animal-name" id="animalName">Nom de l'Animal</h1>
          <p class="animal-species" id="animalSpecies">Espèce</p>
        </div>

        <div class="info-group">
          <h3>Informations Générales</h3>
          <div class="info-item">
            <span class="info-label">Prénom:</span>
            <span class="info-value" id="animalFirstname">-</span>
          </div>
          <div class="info-item">
            <span class="info-label">Genre:</span>
            <span class="info-value" id="animalGender">-</span>
          </div>
          <div class="info-item">
            <span class="info-label">Habitat:</span>
            <span class="info-value" id="animalHabitat">-</span>
          </div>
        </div>

        <div class="info-group">
          <h3>Alimentation</h3>
          <div class="info-item">
            <span class="info-label">Régime:</span>
            <span class="info-value" id="animalDiet">-</span>
          </div>
        </div>

        <div class="info-group">
          <h3>Reproduction</h3>
          <div class="info-item">
            <span class="info-value" id="animalReproduction">-</span>
          </div>
        </div>

        <div class="info-group health-status" id="healthStatus">
          <h3>État de Santé</h3>
          <div class="info-item">
            <span class="info-label">État:</span>
            <span class="info-value" id="animalHealth">-</span>
          </div>
          <div class="info-item">
            <span class="info-label">Nourriture:</span>
            <span class="info-value" id="animalFood">-</span>
          </div>
          <div class="info-item">
            <span class="info-label">Grammage:</span>
            <span class="info-value" id="animalFoodAmount">-</span>
          </div>
          <div class="info-item">
            <span class="info-label">Dernier passage:</span>
            <span class="info-value" id="lastVisit">-</span>
          </div>
          <div class="info-item">
            <span class="info-label">Détails:</span>
            <span class="info-value" id="healthDetails">-</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>