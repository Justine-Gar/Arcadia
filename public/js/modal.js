document.addEventListener('DOMContentLoaded', function () {
  // Sélection des éléments
  const btnLogin = document.getElementById('btnLogin');
  const modalLogin = document.getElementById('modalLogin');
  const closeBtn = modalLogin.querySelector('.close');
  const form = document.getElementById('formLogin');

  // Ouverture de la modal
  function openModal(e) {
    e.preventDefault(); // Empêche le comportement par défaut du lien
    modalLogin.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    // Change l'URL sans recharger la page
    previousUrl = window.location.href;
    history.pushState(null, '', '/login');
  }

  // Fermeture de la modal
  function closeModal() {
    modalLogin.style.display = 'none';
    document.body.style.overflow = 'auto';
    // Retourne à l'URL précédente
    history.pushState(null, '', previousUrl);
  }

  // Ajout des événements aux boutons
  btnLogin.addEventListener('click', openModal);
  closeBtn.addEventListener('click', closeModal);

  // Fermeture de la modal si on clique en dehors
  window.addEventListener('click', (event) => {
    if (event.target === modalLogin) {
      closeModal();
    }
  });

  // Empêche la fermeture si clic intérieur modal
  modalLogin.querySelector('.modal_container').addEventListener('click', (event) => {
    event.stopPropagation();
  });



  // Gestion de la soumission du formulaire
  form.addEventListener('submit', function (e) {
    e.preventDefault(); // Empêche la soumission par défaut du formulaire

    // Récupération et nettoyage des valeurs du formulaire
    const email = this.emailuser.value.trim();
    const password = this.password.value.trim();

    // Validation basique côté client
    if (!email || !password) {
      alert('Veuillez remplir tous les champs.');
      return;
    }

    if (!isValidEmail(email)) {
      alert('Veuillez entrer une adresse email valide.');
      return;
    }

    //Soumission Fomr
    console.log('Email:', email);
    console.log('Mot de passe:', password);

    fetch('/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `emailuser=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
      .then(response => response.text())
      .then(data => {
        console.log('Réponse du serveur:', data);
        // Traitez la réponse ici (par exemple, redirection si connexion réussie)
        try {
          const jsonData = JSON.parse(data);
          console.log('Données JSON parsées:', jsonData);
          if (jsonData.success) {
            //si réussis on redirige
            location.pathname = jsonData.redirect;

          } else {
            //sinon
          }
          // Traitez jsonData comme avant
        } catch (error) {
          console.error('Erreur de parsing JSON:', error);
          alert('Le serveur a renvoyé une réponse invalide.');
        }
      })
      .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la connexion.');
      });
  });

  // Fonction pour valider le format de l'email
  function isValidEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
  }

  // Gestion de la navigation avec les boutons précédent/suivant du navigateur
  window.addEventListener('popstate', function (event) {
    if (window.location.pathname !== '/login') {
      closeModal();
    }
  });

});



