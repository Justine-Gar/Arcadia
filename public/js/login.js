document.addEventListener('DOMContentLoaded', function () {
//=== Sélection des éléments et variable globales
  const btnLogin = document.getElementById('btnLogin');
  const modalLogin = document.getElementById('modalLogin');
  const closeBtn = modalLogin.querySelector('.close');
  const form = document.getElementById('formLogin');
  const errorElement = document.getElementById('login-error');
  let previousUrl; // Stockage de l'URL précédente

//=== gestion erreurs

  // - afficher un message d'erreur
  function showError(message) {
    if(errorElement) {
      errorElement.textContent = message;
      errorElement.style.display = 'block';
    }
  }

  // - cache le message d'erreur
  function hideError() {
    if (errorElement) {
      errorElement.style.display = 'none';
    }
  }

//=== Fonction de validation

  // - Fonction pour valider le format de l'email
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // - Fonction pour valider le mot de passe
  function isValidPassword(password) {
    // min 8 caract, une majuscule, un chiffre et un caract spécial
    //const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
    return password.length >= 6 && /\d/.test(password);
  }


//=== Gestion de la Modal

  // - Ouverture de la modal
  function openModal(e) {
    e.preventDefault(); // Empêche le comportement par défaut du lien
    previousUrl = window.location.href; // Change l'URL sans recharger la page
    modalLogin.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    history.pushState(null, '', '/login');
  }

  // - nettoie et réinitialise la modal
  function cleanup() {
    form.reset(); //réinital le form
    hideError(); //cache les message erreurs
  }

  // - Fermeture de la modal
  function closeModal() {
    cleanup();
    modalLogin.style.display = 'none';
    document.body.style.overflow = 'auto'; //Reactivele scroll
    history.pushState(null, '', previousUrl); // Retourne à l'URL précédente
  }


//=== gestion du formulaire

  //gère la soumission du formulaire
  async function handleSubmit(e) {
    e.preventDefault();
    hideError();

    //récupère et nettoie les valeurs
    const email = this.emailuser.value.trim();
    const password = this.passworduser.value.trim();

    // Validation des champs
    if (!email || !password) {
      showError('Veuillez remplir tous les champs.');
      return;
    }

    if (!isValidEmail(email)) {
      showError('Veuillez entrer une adresse email valide.');
      return;
    }

    if (!isValidPassword(password)) {
      showError('Le mot de passe doit contenir au moins 6 caractères et/ou  chiffres.');
      return;
    }

    //gere le button de soumission
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = 'Connexion en cours...';

    try {
      const response = await fetch('/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `emailuser=${encodeURIComponent(email)}&passworduser=${encodeURIComponent(password)}`
      });

      // Vérification de la réponse HTTP
      if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
      }

      const text = await response.text();
      
      try {
        const data = JSON.parse(text);
        
        if (data.success) {
          // Redirection en cas de succès
          window.location.pathname = data.redirect;
        } else {
          // Affichage du message d'erreur du serveur
          showError(data.message || 'Échec de la connexion. Veuillez réessayer.');
        }
      } catch (parseError) {
        console.error('Erreur de parsing JSON:', parseError);
        showError('Une erreur est survenue lors du traitement de la réponse.');
      }
    } catch (error) {
      console.error('Erreur de connexion:', error);
      showError('Impossible de se connecter au serveur. Veuillez réessayer plus tard.');
    } finally {
      // Restauration du bouton
      submitButton.disabled = false;
      submitButton.textContent = 'Se connecter';
    }
  }


//==== évènements

  // - ouverture modal
  btnLogin.addEventListener('click', openModal);
  // - fermeture modal
  closeBtn.addEventListener('click', closeModal);

  // - fermeture de la modal si on clique en dehors
  window.addEventListener('click', (event) => {
    if (event.target === modalLogin) {
      closeModal();
    }
  });
  
  // - Empêche la fermeture si clic intérieur modal
  modalLogin.querySelector('.modal_container').addEventListener('click', (event) => {
    event.stopPropagation();
  });

  // - gestion formulaire
  form.addEventListener('submit', handleSubmit);

  // gestion de la navigation avec les boutons précédent/suivant du navigateur
  window.addEventListener('popstate', function (event) {
    if (window.location.pathname !== '/login') {
      closeModal();
    }
  });

  //gestion clavier(teste) echap
  document.addEventListener('keydown', function(event) {
    if(event.key === 'Escape' && modalLogin.style.display === 'flex') {
      closeModal();
    }
  });

});



