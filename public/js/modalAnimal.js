let currentSlide = 0;
let totalSlides = 0;

// Fonction pour ouvrir la modal
function openAnimalModal(animalData = null) {
  const modal = document.getElementById('animalModal');

  // Si on a des données d'animal, les utiliser
  if (animalData) {
    populateAnimalData(animalData);
  } else {
    // Données de démonstration
    const demoData = {
      name: "Melman",
      species: "Giraffa camelopardalis",
      firstname: "Melman",
      gender: "Mâle",
      habitat: "Savane Africaine",
      diet: "Herbivore - Principalement des feuilles d'acacia",
      reproduction: "Gestation de 14-15 mois, généralement 1 petit par portée",
      health: {
        status: "Bonne santé",
        food: "Feuilles d'acacia",
        amount: "25 kg/jour",
        lastVisit: "2024-01-15",
        details: "Animal en parfaite santé, comportement normal"
      },
      images: [
        "https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=800&h=600&fit=crop",
        "https://images.unsplash.com/photo-1564349683136-77e08dba1ef7?w=800&h=600&fit=crop",
        "https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&h=600&fit=crop"
      ]
    };
    populateAnimalData(demoData);
  }

  modal.classList.add('show');
  document.body.style.overflow = 'hidden';
}

// Fonction pour fermer la modal
function closeAnimalModal() {
  const modal = document.getElementById('animalModal');
  modal.classList.remove('show');
  document.body.style.overflow = 'auto';
}

// Fonction pour peupler les données de l'animal
function populateAnimalData(data) {
  document.getElementById('animalName').textContent = data.name;
  document.getElementById('animalSpecies').textContent = data.species;
  document.getElementById('animalFirstname').textContent = data.firstname;
  document.getElementById('animalGender').textContent = data.gender;
  document.getElementById('animalHabitat').textContent = data.habitat;
  document.getElementById('animalDiet').textContent = data.diet;
  document.getElementById('animalReproduction').textContent = data.reproduction;

  // Informations de santé
  if (data.health) {
    document.getElementById('animalHealth').textContent = data.health.status;
    document.getElementById('animalFood').textContent = data.health.food;
    document.getElementById('animalFoodAmount').textContent = data.health.amount;
    document.getElementById('lastVisit').textContent = data.health.lastVisit;
    document.getElementById('healthDetails').textContent = data.health.details;

    // Couleur selon l'état de santé
    const healthStatus = document.getElementById('healthStatus');
    healthStatus.className = 'info-group health-status';
    if (data.health.status.toLowerCase().includes('malade')) {
      healthStatus.classList.add('critical');
    } else if (data.health.status.toLowerCase().includes('attention')) {
      healthStatus.classList.add('warning');
    }
  }

  // Charger les images
  loadImages(data.images);
}

// Fonction pour charger les images dans le carrousel
function loadImages(images) {
  const slidesContainer = document.getElementById('carouselSlides');
  const indicatorsContainer = document.getElementById('carouselIndicators');

  // Vider les conteneurs
  slidesContainer.innerHTML = '';
  indicatorsContainer.innerHTML = '';

  totalSlides = images.length;
  currentSlide = 0;

  // Créer les slides
  images.forEach((imageSrc, index) => {
    const slide = document.createElement('div');
    slide.className = 'carousel-slide';
    slide.innerHTML = `<img src="${imageSrc}" alt="Image ${index + 1}">`;
    slidesContainer.appendChild(slide);

    // Créer les indicateurs
    const indicator = document.createElement('div');
    indicator.className = 'indicator';
    if (index === 0) indicator.classList.add('active');
    indicator.onclick = () => goToSlide(index);
    indicatorsContainer.appendChild(indicator);
  });

  updateCarousel();
}

// Fonction pour changer de slide
function changeSlide(direction) {
  currentSlide += direction;

  if (currentSlide >= totalSlides) {
    currentSlide = 0;
  } else if (currentSlide < 0) {
    currentSlide = totalSlides - 1;
  }

  updateCarousel();
}

// Fonction pour aller à une slide spécifique
function goToSlide(index) {
  currentSlide = index;
  updateCarousel();
}

// Fonction pour mettre à jour le carrousel
function updateCarousel() {
  const slidesContainer = document.getElementById('carouselSlides');
  const indicators = document.querySelectorAll('.indicator');

  slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;

  // Mettre à jour les indicateurs
  indicators.forEach((indicator, index) => {
    indicator.classList.toggle('active', index === currentSlide);
  });
}

// Fermer la modal en cliquant à l'extérieur
window.onclick = function (event) {
  const modal = document.getElementById('animalModal');
  if (event.target === modal) {
    closeAnimalModal();
  }
}

// Fermer avec la touche Échap
document.addEventListener('keydown', function (event) {
  if (event.key === 'Escape') {
    closeAnimalModal();
  }
});

// Navigation avec les flèches du clavier
document.addEventListener('keydown', function (event) {
  const modal = document.getElementById('animalModal');
  if (modal.classList.contains('show')) {
    if (event.key === 'ArrowLeft') {
      changeSlide(-1);
    } else if (event.key === 'ArrowRight') {
      changeSlide(1);
    }
  }
});
