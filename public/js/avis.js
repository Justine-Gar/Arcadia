document.addEventListener('DOMContentLoaded', () => {
  /**
   * AVIS 
   */

  const btnAvisForm = document.getElementById('btnAvisForm');
  const avisForm = document.getElementById('avis_form');
  const avisContainer = document.getElementById('avis_container_text');

  const maxAvis = 6;

  
  //cache le formulaire
  avisForm.style.display = 'none';

  //gérer l'affichage du formulaire
  btnAvisForm.addEventListener('click', () => {

    if (avisForm.style.display === 'none') {
      avisForm.style.display = 'block';
      btnAvisForm.textContent = 'Fermer';
    } 
    else {
      avisForm.style.display = 'none';
      btnAvisForm.textContent = 'un avis ?';
    }
  });
  
//Evenement au submit
  avisForm.addEventListener('submit', function(event) {
    event.preventDefault()

    //les valeur du form envoyer au submit
    const clientName = document.getElementById('clientName').value.trim();
    const clientRating = parseInt(document.getElementById('clientRating').value, 10);
    const clientText = document.getElementById('clientText').value.trim();

    //validtion de titre
    if (clientName.length === 0 || clientName.length > 100) {
      alert('Le nom du client doit contenir entre 1 et 100 caractères.');
      return;
    }
    //validation de la note
    if (isNaN(clientRating) || clientRating < 1 || clientRating > 5) {
      alert('La note doit être comprise entre 1 et 5.');
      return;
    }
    //validation du champs text
    if (clientText.length === 0 || clientText.length > 500) {
      alert('L\'avis doit contenir entre 1 et 500 caractères.');
      return;
    }

    addAvis(clientName, clientRating, clientText);

    //réinitialiser le form
    this.reset();
    avisForm.style.display ='none';
    btnAvisForm.textContent = 'un avis ?';
  });

    //ajouter un avis html

  function addAvis (name, rating, text) {
    const avisItem = createAvisElement(name, rating, text);

    //ajouter un nouvel avis au debut
    avisContainer.insertAdjacentElement('beforeend', avisItem);
    
    //verifier le ndr d'avis
    const avis = avisContainer.getElementsByClassName('avis_item');
    while (avis.length > maxAvis) {
      avisContainer.removeChild(avis[avis.length - 1]);
    }

    currentIndex = 0;
    updateSlider();
  }
  
    //Ici on pas creer des éléments html a chque ajout d'un avis

  function createAvisElement(name, rating, text) {
    const avisItem = document.createElement('div');
    avisItem.className = 'avis_item';

    const avisHeader = document.createElement('div');
    avisHeader.className = 'avis_header';

    const nameTitle = document.createElement('h5');
    nameTitle.textContent = espaceHTML(name);

    const starElement = document.createElement('div');
    starElement.className = 'rating';
    starElement.innerHTML = generationStars(rating);

    const avisText = document.createElement('div');
    avisText.className = 'avis_text';
    
    const avisPara = document.createElement('p');
    avisPara.textContent = espaceHTML(text);
    
    //qui est parent de qui ?
    
    avisHeader.appendChild(nameTitle);
    avisHeader.appendChild(starElement);
    avisItem.appendChild(avisHeader);
    avisItem.appendChild(avisText);
    avisText.appendChild(avisPara);

    return avisItem;
  }

    //generation des étoiles

  function generationStars(rating) {
    return Array.from({length: 5}, (_, i) => 
      `<span class="star ${i < rating ? 'filled' : ''}">★</span>`
    ).join('');
  }

    //échapper du contenue fourni par user

  function espaceHTML(str) {
      return str.replace(/&<>'"]/g,
        tag => ({
          '&': '&amp;',
          '<': '$lt;',
          '>': '$gt;',
          "'": '$#39;',
          '"': '$quot;'
        }[tag] || tag)
      );
  }
  
  
});