
// Attend que le DOM soit complètement chargé avant d'exécuter le code
document.addEventListener('DOMContentLoaded', function() {
  
  // Sélectionne tous les boutons d'onglets
  const tabButtons = document.querySelectorAll('.tab_button');
  // Sélectionne tous les contenus d'onglets
  const tabContents = document.querySelectorAll('.content');

  // Ajoute un écouteur d'événements à chaque bouton d'onglet
  tabButtons.forEach(button => {
    button.addEventListener('click', () => {
      // Récupère le nom de l'onglet à partir de l'attribut data-tab
      const tabName = button.getAttribute('data-tab');
      
      // Retire la classe 'active' de tous les boutons et contenus
      tabButtons.forEach(btn => btn.classList.remove('active'));
      tabContents.forEach(content => content.classList.remove('active'));
      
      // Ajoute la classe 'active' au bouton cliqué et au contenu correspondant
      button.classList.add('active');
      document.getElementById(tabName).classList.add('active');
    });
  });


  
  // Fonction pour configurer le carrousel et l'affichage des détails
  function setupCarousel(containerSelector, buttonSelector, slideSelector) {
    // Sélectionne le conteneur du carrousel
    const container = document.querySelector(containerSelector);
    // Sélectionne les boutons de navigation du carrousel
    const buttons = container.querySelectorAll(buttonSelector);
    // Sélectionne toutes les slides du carrousel
    const slides = container.querySelectorAll(slideSelector);
    // Variable pour suivre si le carrousel est actif (mode mobile)
    let isCarouselActive = 'false';

    // Fonction pour changer de slide
    function changeSlide(direction) {
      // Ne fait rien si le carrousel n'est pas actif (mode desktop)
      if (!isCarouselActive) return;
      // Trouve la slide active actuelle
      const activeSlide = container.querySelector(`${slideSelector}.active2`) || slides[0];
      // Calcule l'index de la nouvelle slide
      let newIndex = [...slides].indexOf(activeSlide) + direction;
      // Gère le dépassement d'index (boucle)
      if (newIndex < 0) newIndex = slides.length - 1;
      if (newIndex >= slides.length) newIndex = 0;
      // Désactive la slide actuelle et active la nouvelle
      activeSlide.classList.remove('active2');
      slides[newIndex].classList.add('active2');

      slides.forEach((slide, index) => {
        slide.style.display = index === newIndex ? 'block' : 'none';

      });
    }

    // Fonction pour basculer entre le mode carrousel et le mode grille
    function toggleCarouselMode(isDesktop) {
      // Active le carrousel en mode mobile, désactive en mode desktop
      isCarouselActive = !isDesktop;
      // Affiche ou cache les boutons de navigation
      buttons.forEach(button => {
        button.style.display = isDesktop ? 'none' : 'block';
      });
      
      // Ajuste l'affichage des slides selon le mode
      if (isDesktop) {
        slides.forEach(slide => {
          slide.classList.add('active2');
          slide.style.display = 'block';
        });
      } else {
        slides.forEach((slide, index) => {
          if (index === 0) {
            slide.classList.add('active2');
            slide.style.display = 'block';
          } else {
            slide.classList.remove('active2');
            slide.style.display = 'none';
          }
        });
      }
    }

    let openCard = null;
    const DESKTOP_BREAKPOINT = 768;
      // Fonction pour gérer le click sur les tetes de animaux
    function clickCard(event) {
      //si window >=769px on va cherche la card_text_marais
      if (window.innerWidth >= DESKTOP_BREAKPOINT) { //vérification de largeur d'écran

        const clickedCard = event.currentTarget;
        const cardText = clickedCard.querySelector('.card_text_marais, .card_text_savane, .card_text_jungle');
        
        if (cardText) {
          //ferme la card précédemment ouverte
          if (openCard && openCard !== clickedCard) {
            //card précedentew
            const previousCardText = openCard.querySelector('.card_text_marais, .card_text_savane, .card_text_jungle');
            if (previousCardText) {
              previousCardText.style.display = 'none';
            }
          }

          if (cardText.style.display === 'none' || cardText.style.display === '') {

            cardText.style.display = 'block';
            //met à jour la carte
            openCard = clickedCard;
          } else {
            cardText.style.display = 'none';
            //reinitialise la carte
            openCard = null;
          }
        }
      }
    }

    // Ajout d'un écouteur pour le click à chaque slide
    document.querySelectorAll('.slide_marais, .slide_savane, .slide_jungle').forEach(slide => {
      slide.addEventListener('click', clickCard);
    });

    // Ajoute des écouteurs de clic aux boutons de navigation
    buttons.forEach(button => {
      button.addEventListener("click", (event) => {
        // Détermine la direction (suivant ou précédent)
        const direction = event.target.id.includes("next") ? 1 : -1;
        // Change la slide dans la direction indiquée
        changeSlide(direction);
      });
    });

     // Fonction pour gérer le redimensionnement de la fenêtre
    function handleResize() {
      // Détermine si on est en mode desktop (largeur > 768px)
      const isDesktop = window.innerWidth > DESKTOP_BREAKPOINT;
      // Bascule le mode en fonction de la taille de l'écran
      toggleCarouselMode(isDesktop);

      //affichage de card_txt_marais
      if(isDesktop) {
        document.querySelectorAll('.card_text_marais, .card_text_savane, .card_text_jungle').forEach(cardText => {
          cardText.style.display = 'none';
        });
        openCard = null;
      } else {
        //mode mobile
        document.querySelectorAll('.card_text_marais, .card_text_savane, .card_text_jungle').forEach(cardText => {
          cardText.style.removeProperty('display');
        });
      }
    }

    // Ajoute un écouteur pour le redimensionnement de la fenêtre
    window.addEventListener('resize', handleResize);
    // Appelle handleResize immédiatement pour configurer l'affichage initial
    handleResize();

  }

  // Configure les carrousels pour chaque section
  setupCarousel('.onglet_marais', '.btn_carousel_marais', '.slide_marais');
  setupCarousel('.onglet_savane', '.btn_carousel_savane', '.slide_savane');
  setupCarousel('.onglet_jungle', '.btn_carousel_jungle', '.slide_jungle');

});