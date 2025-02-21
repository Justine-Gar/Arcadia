document.addEventListener('DOMContentLoaded', function() {

//-------Selection des éléments et variables-------//

  const modals = {
    //config de la modal user
    user: {
      btn: document.querySelector('button[data-type="user"]'),
      modal: document.getElementById('modalAddUser'),
      form: document.getElementById('formAddUser')
    },
    //config de la modal report
    report: {
      btn : document.querySelector('button[data-type="report"]'),
      modal: document.getElementById('modalAddReport'),
      form: document.getElementById('formAddReport')
    },
    //config de la modal animal
    animal: {
      btn: document.querySelector('button[data-type="animal"]'),
      modal: document.getElementById('modalAddAnimal'),
      form: document.getElementById('formAddAnimal')
    },
    //config de la modal service
    service: {
      btn: document.querySelector('button[data-type="service"]'),
      modal: document.getElementById('modalAddService'),
      form: document.getElementById('formAddService')
    },
    //config de la modal habitat
    habitat: {
      btn: document.querySelector('button[data-type="habitat"]'),
      modal: document.getElementById('modalAddHabitat'),
      form: document.getElementById('formAddHabitat')
    }
  };


//-------Function de validation-------//

  // - Validtion des champs USER
  const validatorUser = {
    //nom de l'user
    add_username: (value) => {
      if (value.length < 3) return 'Le nom d\'utilisateur doit contenir au moin 3 caractères';
      if (value.length > 50) return 'le nom d\'utilisateur ne peut dépasser 50 caractères';
      return null; //pas d'erreur
    },
    //email
    add_email: (value) => {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) return 'Veuillez entrer une adresse email valide';
      return null;
    },
    //mot de passe
    add_password: (value) => {
      if (value.length < 6) return 'Le mot de passe doit contenir au moins 6 caractères';
      if (!/\d/.test(value)) return 'Le mot de passe doit contenir au moins un chiffre';
      return null;
    }
  };
  // - Valiation des champs REPORT
  const validatorReport = {
    //id animal
    add_id_animal: (value) => {
      if (!value) return 'Veuillez selectionner un animal';
      return null;
    },
    //santé
    add_health_status: (value) => {
      const validStatus = ['Bonne santé',
        'Convalescence',
        'Sous traitement Médical',
        'Sous observation',
        'Enrichissement environnemental',
        'En quarantaine',
        'En gestation',
        'En période de mue',
        'En réhabilitation',
        'Vieillesse'];
      if (!validStatus.includes(value)) return 'Status de santé invalide';
      return null;
    },
    //prescription
    add_prescription: (value) => {
      if (!value || value.trim() === '') return null;
      if (value.length > 10000) return 'La prescription ne doit pas dépasser 10000 caractères';
      return null;
    },
    //quantity
    add_quantity: (value) => {
      if (!value || value.trim() === '') return null;
      if (value.length > 50) return 'Le régime alimentaire ne doit pas dépasser 50 caractères';
      return null;
    },
    //habitat description
    add_habitat_condition: (value) => {
      if (!value || value.trim() === '') return null;
      if (value.length > 10000) return 'La condition des habitats ne doit pas dépasser 10000 caractères';
      return null;
    }

  };
  // - Validation des champs ANIMAL
  const validatorAnimal = {
    //habitat
    add_id_habitat: (value) => {
      if (!value) return 'Veuillez sélectionner un habitat';
      return null;
    },
    //prénom
    add_firstname: (value) => {
      if (!value) return 'Le nom de l\'animal est requis';
      if (value.length < 2) return 'Le nom doit contenir au moins 2 caractères';
      if (value.length > 50) return 'Le nom ne peut pas dépasser 50 caractères';
      return null;
    },
    //genre
    add_gender: (value) => {
      if (!value) return 'Le genre est requis';
      if (value.length > 50) return 'Le genre ne peut pas dépasser 50 caractères';
      return null;
    },
    //espèces
    add_species: (value) => {
      if (!value) return 'L\'espèce est requise';
      if (value.length > 200) return 'L\'espèce ne doit pas dépasser 200 caractères';
      return null;
    },
    //diet
    add_diet: (value) => {
      if (!value) return 'Le régime alimentaire est requis';
      if (value.length > 10000) return 'Le régime alimentaire ne doit pas dépasser 10000 caractères';
      return null;
    },
    //reproduction
    add_reproduction: (value) => {
      if (!value) return 'Les information de reproduction sont requise';
      if (value.length > 10000) return 'La reproduction ne doit pas dépasser 10000 caractères';
      return null;
    }
  };
  // - Validation des champs SERVICE
  const validatorService = {
    //nom
    add_name: (value) => {
      if (!value) return 'Le nom du service est requis';
      if (value.length > 50) return 'Le nom ne doit pas dépasser 50 caractères';
      return null;
    },
    //description
    add_description: (value) => {
      if (!value) return 'Une description est requise';
      if (value.length > 10000) return 'La description ne doit pas depasser plus de 10000 caractères';
      return null;
    }
  };
  // - Validation des champs HABITAT
  const validatorHabitat = {
    add_name: (value) => {
      if (!value) return 'Le nom de l\'habitat est requis';
      if (value.length > 50) return 'Le nom ne doit pas dépasser 50 caractères';
      return null;
    },
    add_description: (value) => {
        if (!value) return 'La description est requise';
        if (value.length > 10000) return 'La description ne doit pas dépasser 10000 caractères';
        return null;
    }
  };


//-------Function Utilisation-------//

  // - Affiche un message
  function showMessage(message, type = 'error') {

    //si alerte présente on la supp
    const existingAlert = document.querySelector('.alert');
    if (existingAlert) existingAlert.remove();

    //on créer la modal d'alerte
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    //config les style selon le type
    const styles = {
      success: {
        backgroundColor: '#dff0d8',
        color: '#3c763d',
        border: '1px solid #d6e9c6'
      },
      error: {
        backgroundColor: '#f2dede',
        color: '#a94442',
        border: '1px solid #ebccd1'
      }
    };
    
    //applique les styles
    Object.assign(alertDiv.style, {
      padding: '15px',
      marginBottom: '20px',
      borderRadius: '4px',
      ...styles[type]
    });

    // Insertion du message et auto-suppression après 5 secondes
    const mainContent = document.querySelector('.main_content');
    if (mainContent) {
      mainContent.insertBefore(alertDiv, mainContent.firstChild);
      setTimeout(() => alertDiv.remove(), 5000);
    }
  }
  // - Femeture modal et réinitialisation
  function closeModal(modal) {
    modal.style.display = 'none';
    //reinitialise le scroll du body
    document.body.style.overflow = 'auto';
    // réinitialise le formulaire s'il existe
    const form = modal.querySelector('form');
    if (form) form.reset();
  }
  // - Validation des champs du form
  function validateForm(formData) {
    for(const [key, value] of formData.entries()) {
      if (validatorUser[key]) {
        const error = validatorUser[key](value);
        if(error) return error;
      }
      if (validatorReport[key]) {
        const error = validatorReport[key](value);
        if(error) return error;
      }
      if (validatorAnimal[key]){
        const error = validatorAnimal[key](value);
        if(error) return error;
      }
      if (validatorService[key]){
        const error = validatorService[key](value);
        if(error) return error;
      }
      if (validatorHabitat[key]){
        const error = validatorHabitat[key](value);
        if(error) return error;
      }
    }
    return null; //message erreur ou null si tout est valide
  }


//-------Gestion d'evenement de la modal-------//

  // - Setup pour tout les evenement de la modal ouverture fermeture etc
  function setupModal(modalInfo) {
    if (!modalInfo.btn || !modalInfo.modal) return;

    const closeBtn = modalInfo.modal.querySelector('.close');

    // -- ouverture de la modal
    modalInfo.btn.addEventListener('click', (e) => {
      e.preventDefault();
      modalInfo.modal.style.display = 'block';
    });

    // -- fermeture par le btn close
    closeBtn.addEventListener('click', () => closeModal(modalInfo.modal));

    // -- fermeture en cliquand dehors
    window.addEventListener('click', (e) => {
      if (e.target === modalInfo.modal) closeModal(modalInfo.modal);
    });
  }
  

//--------Gestion du formulaire---------//

  /**-Form add User */
  async function setupUserForm() {
    // - verifie si form existe dans DOM
    if (!modals.user.form) return; //si non, sort de la function pour eviter error

    // - evenement sur la soumission du form
    modals.user.form.addEventListener('submit', async function(e) {
      // -- empeche le rechargement de la page
      e.preventDefault();

      // -- etape 1 collecte les donnéés
        // --- Création de l'OBJ avec donnée form (this ref au formulaire)
      const formData = new FormData(this);
        // --- Valide les données avec les funtions
      const error = validateForm(formData);
        // --- si error affiche message
      if (error) {
        showMessage(error);
        return;
      }

      // -- etape 2 preparation interface user
        // --- recupere le btn submit
      const submitButton = this.querySelector('button[type="submit"]');
        // --- desactiv btn sa évite les doublon
      submitButton.disabled = true;
        // --- change le texte pour indiquer un chargement
      submitButton.textContent = 'Création en cours...';

      // -- etape 3 envoi et traitment des donnée
      try {
        // --- envoie une requet http au serveur de maniere async // await permet d'attendre la reponse serveur
        const response = await fetch('/admin/comptes/ajouter', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json'},
          // ---- Convertir FormData en obj puis en JSON
          body: JSON.stringify(Object.fromEntries(formData))
        });

        // --- verifie si requet à réussi envoie (status 200-209)
        if (!response.ok) {
          //si non récup le message erreur serveur
          const error = await response.json();
          //lance erreur avec message du serveur ou un message par defaut
          throw new error(error.message || 'Erreur lors de la création');
        }

        // --- Parse la réponse JSON du serveur
        const data = await response.json();
        // --- verifie si opération à réussis avec succes
        if (data.success) {
          // ---- affiche le message de réussite
          showMessage(data.message, 'success');
          // ---- ferme la modal
          closeModal(modals.user.modal);
          // ---- Recharge la fenetre dans 4 secondes
          setTimeout(() => location.reload(), 4000);
        } else {
          // ---- si erreur serveur lance une exception
          throw new Error(data.message);
        }
      }
      catch (error) {
        // --- affiche tout les messages d'erreurs
        showMessage(error.message);
      }
      finally {
        // --- ce bloc s'exécute toujours, que la requête réussisse ou échoue
          // ---- réactive le bouton
          submitButton.disabled = false;
          // ---- restaure le texte original
          submitButton.textContent = 'Ajouter';
      }
    });
  }
  /**-Form add Journal */
  async function setupReportForm() {

    if (!modals.report.form) return;

    modals.report.form.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);

      //ajoute automatiquement la date et heure d'aujourd'hui
      const now = new Date();
      const formattedDate = now.toISOString().slice(0, 19).replace('T', ' ');
      formData.append('add_passage', formattedDate);

      const error = validateForm(formData);
      if(error) {
        showMessage(error);
        return;
      }

      const reportData = Object.fromEntries(formData);

      //nettoyage des champs vide pour qui voient null en bdd
      if (!reportData.add_prescription?.trim()) delete reportData.add_prescription;
      if (!reportData.add_quantity?.trim()) delete reportData.add_quantity;
      if (!reportData.add_habitat_condition?.trim()) delete reportData.add_habitat_condition;

      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Création en cours...';

      const isVeto = this.getAttribute('data-role') === 'veto';
      const url = isVeto ? '/veto/journal/ajouter' : '/admin/journal/ajouter';
      
      try {

        const response = await fetch(url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(reportData)
        });

        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.message || 'Erreur lors de lcréation du rapport');
        }

        const data = await response.json()
        if (data.success) {
          showMessage(data.message, 'success');
          closeModal(modals.report.modal);
          setTimeout(() => location.reload(), 4000);
        } else {
          throw new Error(data.message);
        }
      }
      catch (error) {
        showMessage(error.message);
      }
      finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Ajouter';
      }
    });
  }
  /**-Form add Animal */
  async function setupAnimalForm() {
    if (!modals.animal.form) return;

    modals.animal.form.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const error = validateForm(formData);

      if (error) {
        showMessage(error);
        return;
      }

      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Création en cours...';

      try {
        const formDataObj = Object.fromEntries(formData);
        console.log('Données envoyées:', formDataObj);

        const response = await fetch('/admin/animaux/ajouter', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(formDataObj)
        });

        const data = await response.json();
        console.log('Réponse du serveur:', data);

        if (!response.ok) {
          throw new Error(data.message || 'Erreur lors de la création de l\'animal');
        }

        if (data.success) {
          showMessage(data.message, 'success');
          closeModal(modals.animal.modal);
          setTimeout(() => location.reload(), 4000);
        } else {
          throw new Error(data.message || 'Erreur lors de la création de l\'animal');
        }
      }
      catch (error) {
        showMessage(error.message || 'Erreur lors de la création de l\'animal');
        console.error('Erreur:', error);
      }
      finally{
        submitButton.disabled = false;
        submitButton.textContent = 'Ajouter';
      }
    });
  }
  /**-Form add Service */
  async function setupServiceForm() {
    if (!modals.service.form) return;

    modals.service.form.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const error = validateForm(formData);

      if (error) {
        showMessage(error);
        return;
      }

      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Création en cours...';

      //Determine Url en function du role
      const isStaff = this.getAttribute('data-role') === 'staff';
      const url = isStaff ? '/staff/services/ajouter' : '/admin/services/ajouter';

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(Object.fromEntries(formData))
        });

        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.message || 'Erreur lors de la création du service');
        }

        const data = await response.json();

        if (data.success) {
          showMessage(data.message, 'success');
          closeModal(modals.service.modal);
          setTimeout(() => location.reload(), 4000);
        } else {
          throw new Error(data.message);
        }
      }
      catch (error) {
        showMessage(error.message);
      }
      finally{
        submitButton.disabled = false;
        submitButton.textContent = 'Ajouter';
      }
    });
  }
  /**-Form add Habitat */
  async function setupHabitatForm() {
    if (!modals.habitat.form) return;

    modals.habitat.form.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const error = validateForm(formData);

      if (error) {
        showMessage(error);
        return;
      }

      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Création en cours...';

      try {
        const response = await fetch('/admin/habitats/ajouter', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(Object.fromEntries(formData))
        });

        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.message || 'Erreur lors de la création de l\'habitat');
        }

        const data = await response.json();

        if (data.success) {
          showMessage(data.message, 'success');
          closeModal(modals.habitat.modal);
          setTimeout(() => location.reload(), 4000);
        } else {
          throw new Error(data.message);
        }
      }
      catch (error) {
        showMessage(error.message);
      }
      finally{
        submitButton.disabled = false;
        submitButton.textContent = 'Ajouter';
      }
    });
  }


//--------Initialisation---------//

  // -- Initialiser les modales pour chaque type
  Object.values(modals).forEach(modal => {
    if (modal.btn && modal.modal) {
      setupModal(modal);
    }
  });

  // -- Initialiser les formulaires
  setupUserForm();
  setupReportForm();
  setupAnimalForm();
  setupServiceForm();
  setupHabitatForm();
});