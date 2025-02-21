// Ajoute Event quand contenue est charger, il exécute la function
document.addEventListener('DOMContentLoaded', () => {

  /**_____**____SLIDER____**______**/

  //Sélectionne élément HTML de clases slider et mis dans variable
  const slider = document.querySelector('.slides');
  //Sélectionne élement dans slider et mis dans variables
  const slides = slider.querySelectorAll('img');

  //Initialise de la variable qui servira pour l'image affiché
  let currentSlide = 0;

  //Récupere l'index currentSlide pour passer à la slide suivante
  function nextSlide() {
     //incrément l'index de current, modulo (reste dans le nbr d'images) le nbr total d'img
    currentSlide = (currentSlide + 1) % slides.length;
    updateSlider();
  }
  
  //une mise à jours de l'image via css avec un tranforme
  function updateSlider() {

    //let decalage = currentSlide * 100;
    //slider.style.transforme = 'translateX(- ' + decalage + '%)';
    slider.style.transform = `translateX(-${currentSlide * 100}%)`;
    //déplace le slider de 100% pour chaque image en prenannt en compte l 'index du currentSlide
    //si currentSlide =1; le slider déplacer de -100%
    //si currentSlide =2 ; le slider déplacer de -200% ...
  }
  
  setInterval(nextSlide, 5000);




  /**_____**____HABITATS____**______**/

  // Sélectionne éléments HTML des classe habitats_ et mis dans variables
  const habitats = document.querySelectorAll('.home_habitat_savane, .home_habitat_marais, .home_habitat_jungle');

  // Parcour chaque élément habitat
  habitats.forEach(habitat => {

    // Ajoute événement 'mouseenter' (souris entrant)
    habitat.addEventListener('mouseenter', () => {

      // Modfication de css : flex
      habitat.style.flex = '4';

      // Sélectionne élément avec les classes à l'intérieur du habitat
      const txtHover = habitat.querySelector('.home_savane_txt_hover, .home_savane_mobile, .home_marais_txt_hover, .home_jungle_mobile, .home_jungle_txt_hover');
      const mobileA = habitat.querySelector('.home_savane_mobile, .home_marais_mobile, .home_jungle_mobile');
      const animals = habitat.querySelectorAll('.home_savane_animaux, .home_marais_animaux, .home_jungle_animaux');

      // Si txtHover est trouvé
      if (txtHover) {
        // Ajoute: transition de délai de 0.3s, devient opaque et visible
        txtHover.style.transitionDelay = '0.3s';
        txtHover.style.opacity = '1';
        txtHover.style.visibility = 'visible';
      }

      // Si mobileA est trouvé
      if (mobileA) {
        // Ajoute: ransition de délai de 0.3s, devient opaque et visible
        mobileA.style.transitionDelay = '0.3s';
        mobileA.style.opacity = '1';
        mobileA.style.visibility = 'visible';
      }

      // Parcour chaque élément animal
      animals.forEach(animal => {
        // modifie css : visible et opaque, une transition de délai de 0.7s
        animal.style.opacity = '1';
        animal.style.transitionDelay = '0.7s';
        animal.style.visibility = 'visible';
      });

    });

    // Ajoute évènement 'mouseleave' (souris sortant)
    habitat.addEventListener('mouseleave', () => {

      //Modfication de css : flex
      habitat.style.flex = '1';

      //Sélectionne élément avec les classes à l'intérieur du habitat
      const txtHover = habitat.querySelector('.home_savane_txt_hover, .home_savane_mobile, .home_marais_txt_hover, .home_jungle_txt_hover');
      const mobileA = habitat.querySelector('.home_savane_mobile, .home_marais_mobile, .home_jungle_mobile');
      const animals = habitat.querySelectorAll('.home_savane_animaux, .home_marais_animaux, .home_jungle_animaux');

      // Ajoute une classe de visibilité à txtHover
      txtHover.classList.add('home_visibility');

      // Si txtHover est trouvé
      if (txtHover) {
        // Supprime les styles
        txtHover.style.transitionDelay = '0s';
        txtHover.style.opacity = '0';
        txtHover.style.visibility = 'hidden';
      }

      // Si mobileA est trouvé
      if (mobileA) {
        // Supprime les styles 
        mobileA.style.transitionDelay = '0s';
        mobileA.style.opacity = '0';
        mobileA.style.visibility = 'hidden';
      }

      // Parcourt chaque élément animal
      animals.forEach(animal => {
        // Rend chaque animal caché et transparent sans délai de transition
        animal.style.opacity = '0';
        animal.style.transitionDelay = '0s';
        animal.style.visibility = 'hidden';
      });
    });

  });

   // Sélectionne leimages d'animaux avec les classes
  //const animalImages = document.querySelectorAll('.savane_girafe, .savane_fenec, .savane_rhinoceros, .marais_caiman, .marais_renette, .marais_salamandre, .jungle_paresseux, .jungle_toucan, .jungle_jaguar');

  // Parcourt chaque animalImage
  /*animalImages.forEach(animal => {
    // Ajoute évènement 'mouseenter'
    animal.addEventListener('mouseenter', () => {
      // Ajoute css
      animal.style.boxShadow = '15px 15px 30px #254027, -15px -15px 30px #25402788';

      
    });

    // Ajoute évènement 'mouseleave'
    animal.addEventListener('mouseleave', () => {
      // Supprime css
      animal.style.boxShadow = 'none';
    });
  });*/
});




