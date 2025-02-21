document.addEventListener('DOMContentLoaded', function() {

//-------Selection des éléments et variables-------//

  const modals = {
    //config de la modal habitats
    habitat: {
      btn: document.querySelector('button[data-type="modhabitat"]'),
      modal: document.getElementById('modalModifyHabitat'),
      form: document.getElementById('formModifyHabitat')
    },
    service: {
      btn: document.querySelector('button[data-type="modservice"]'),
      modal: document.getElementById('modalModifyService'),
      form: document.getElementById('formModifyService')
    },
    animal: {
      btn: document.querySelector('button[data-type="modanimal"]'),
      modal: document.getElementById('modalModifyAnimal'),
      form: document.getElementById('formModifyAnimal')
    },
    report: {
      btn : document.querySelector('button[data-type="modreport"]'),
      modal: document.getElementById('modalModifyReport'),
      form: document.getElementById('formModifyReport')
    },
    user: {
      btn: document.querySelector('button[data-type="moduser"]'),
      modal: document.getElementById('modalModifyUser'),
      form: document.getElementById('formModifyUser')
    }
  }

//-------SFunction de validation-------//
  const validatorHabitat = {
    modify_name: (value) => {
      if (!value) return 'Le nom de l\'habitat est requis';
      if (value.length > 50) return 'Le nom ne doit pas dépasser 50 caractères';
      return null;
    },
    modify_description: (value) => {
        if (!value) return 'La description est requise';
        if (value.length > 10000) return 'La description ne doit pas dépasser 10000 caractères';
        return null;
    }
  };
  const validatorService = {
    modify_name: (value) => {
      if (!value) return 'Le nom de l\'habitat est requis';
      if (value.length > 50) return 'Le nom ne doit pas dépasser 50 caractères';
      return null;
    },
    modify_description: (value) => {
      if (!value) return 'La description est requise';
      if (value.length > 10000) return 'La description ne doit pas dépasser 10000 caractères';
      return null;
    }
  };
  const validatorAnimal = {
    //habitat
    modify_id_habitat: (value) => {
      if (!value) return 'Veuillez sélectionner un habitat';
      return null;
    },
    //prénom
    modify_firstname: (value) => {
      if (!value) return 'Le nom de l\'animal est requis';
      if (value.length < 2) return 'Le nom doit contenir au moins 2 caractères';
      if (value.length > 50) return 'Le nom ne peut pas dépasser 50 caractères';
      return null;
    },
    //genre
    modify_gender: (value) => {
      if (!value) return 'Le genre est requis';
      if (value.length > 50) return 'Le genre ne peut pas dépasser 50 caractères';
      return null;
    },
    //espèces
    modify_species: (value) => {
      if (!value) return 'L\'espèce est requise';
      if (value.length > 200) return 'L\'espèce ne doit pas dépasser 200 caractères';
      return null;
    },
    //diet
    modify_diet: (value) => {
      if (!value) return 'Le régime alimentaire est requis';
      if (value.length > 10000) return 'Le régime alimentaire ne doit pas dépasser 10000 caractères';
      return null;
    },
    //reproduction
    modify_reproduction: (value) => {
      if (!value) return 'Les information de reproduction sont requise';
      if (value.length > 10000) return 'La reproduction ne doit pas dépasser 10000 caractères';
      return null;
    }
  };
  const validatorReport = {
    //santé
    modify_health_status: (value) => {
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
    modify_prescription: (value) => {
      if (!value || value.trim() === '') return null;
      if (value.length > 10000) return 'La prescription ne doit pas dépasser 10000 caractères';
      return null;
    },
    //quantity
    modify_quantity: (value) => {
      if (!value || value.trim() === '') return null;
      if (value.length > 50) return 'Le régime alimentaire ne doit pas dépasser 50 caractères';
      return null;
    },
    //habitat description
    modify_habitat_condition: (value) => {
      if (!value || value.trim() === '') return null;
      if (value.length > 10000) return 'La condition des habitats ne doit pas dépasser 10000 caractères';
      return null;
    }
  };
  const validatorUser = {
    modify_username: (value) => {
      if (!value) return "Le nom d'utilisateur est requis";
      if (value.length < 3) return "Le nom d'utilisateur doit avoir au moins 3 caractères";
      if (value.length > 50) return "Le nom d'utilisateur ne doit pas dépasser 50 caractères";
      return null;
    },
    modify_email: (value) => {
      if (!value) return "L'email est requis";
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) return "Format d'email invalide";
      return null;
    },
    modify_role: (value) => {
      const validRoles = ['Admin', 'Staff', 'Veto'];
      if (!validRoles.includes(value)) return 'Rôle invalide';
      return null;
    },
    modify_password: (value) => {
      if (value && value.length < 6) return "Le mot de passe doit avoir au moins 6 caractères";
      return null; // On permet un mot de passe vide pour ne pas forcer sa modification
    }
  }
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
      if (validatorHabitat[key]){
        const error = validatorHabitat[key](value);
        if(error) return error;
      }
      if (validatorService[key]){
        const error = validatorService[key](value);
        if(error) return error;
      }
      if(validatorAnimal[key]){
        const error = validatorAnimal[key](value);
        if(error) return error;
      }
      if(validatorReport[key]){
        const error = validatorReport[key](value);
        if(error) return error;
      }
      if(validatorUser[key]){
        const error = validatorUser[key](value);
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
      // Détermine le type de modal en fonction de l'ID
      const modalId = modalInfo.modal.id;

      // Vérifie si un habitat est sélectionné avant d'ouvrir la modal
      if (modalId === 'modalModifyHabitat') {
        const selectedHabitat = document.querySelector('input[name="habitats[]"]:checked');
        if (!selectedHabitat) {
            showMessage('Veuillez sélectionner un habitat à modifier');
            return;
        }
      } else if (modalId === 'modalModifyService') {
          const selectedService = document.querySelector('input[name="services[]"]:checked');
          if (!selectedService) {
              showMessage('Veuillez sélectionner un service à modifier');
              return;
          }
      } else if (modalId === 'modalModifyAnimal') {
          const selectedAnimal = document.querySelector('input[name="animals[]"]:checked');
          if (!selectedAnimal) {
              showMessage('Veuillez sélectionner un animal à modifier');
              return;
          }
      } else if (modalId === 'modalModifyReport') {
        const selectedReport = document.querySelector('input[name="reports[]"]:checked');
        if (!selectedReport) {
            showMessage('Veuillez sélectionner un rapport à modifier');
            return;
        }
      }else if (modalId === 'modalModifyUser') {
        const selectedUser = document.querySelector('input[name="selected_users[]"]:checked');
        if (!selectedUser) {
            showMessage('Veuillez sélectionner un utilisateur à modifier');
            return;
        }
      }
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

  async function setupModifyHabitatForm() {

    const { btn, modal, form } = modals.habitat;

    if (!btn || !modal || !form) {
      //console.error('Éléments manquants:', { btn, modal, form });
      return;
    }

    // Gestion des checkboxes
    const checkboxesModify = document.querySelectorAll('input[name="habitats[]"]');
    const modifyBtn = document.querySelector('button[data-type="modhabitat"]');
    const deleteBtn = document.querySelector('button[data-type="delhabitat"]');
    checkboxesModify.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
          // Compte le nombre de cases cochées
          const checkedBoxes = document.querySelectorAll('input[name="habitats[]"]:checked');
          const checkedCount = checkedBoxes.length;

          // Gestion du bouton modifier
          if (modifyBtn) {
              modifyBtn.disabled = checkedCount !== 1;
              if (checkedCount > 1) {
                  showMessage('Veuillez sélectionner un seul habitat pour la modification');
              }
          }

          // Gestion du bouton supprimer
          if (deleteBtn) {
              deleteBtn.disabled = checkedCount === 0;
          }
      });
    });

    modals.habitat.btn.addEventListener('click', (e) => {
        e.preventDefault();

         // Vérifie la sélection
        const selectedHabitat = document.querySelector('input[name="habitats[]"]:checked');
        const checkedCount = document.querySelectorAll('input[name="habitats[]"]:checked').length;
 
        if(!selectedHabitat || checkedCount > 1) {
            showMessage('Veuillez sélectionner un seul habitat à modifier');
            return;
        }

        // Récupère les données dans le tableau 
        const row = selectedHabitat.closest('tr');
        
        // Remplissage du formulaire
        form.elements['modify_name'].value = row.children[1].textContent.trim();
        form.elements['modify_description'].value = row.children[2].textContent.trim();
        form.dataset.habitatId = selectedHabitat.value;
        
        // Affichage de la modal
        modal.style.display = 'block';
    });
    
    modals.habitat.form.addEventListener('submit', async function(e) {
      e.preventDefault();
      //console.log('Soumission du formulaire');

      const formData = new FormData(this);
      const error = validateForm(formData);

      if (error) {
          showMessage(error);
          return;
      }

      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Modification en cours...';

      try {
          const habitatId = this.dataset.habitatId;
          if (!habitatId) {
              throw new Error('ID de l\'habitat manquant');
          }

          const data = {
              id: parseInt(habitatId),
              modify_name: formData.get('modify_name'),
              modify_description: formData.get('modify_description')
          };

          //console.log('Données à envoyer:', data);

          const response = await fetch('/admin/habitats/modifier', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify(data)
          });

          const result = await response.json();
          //console.log('Réponse du serveur:', result);

          if (result.success) {
              showMessage(result.message, 'success');
              closeModal(modals.habitat.modal);
              setTimeout(() => location.reload(), 4000);
          } else {
              throw new Error(result.message || 'Erreur lors de la modification');
          }
      }
      catch (error) {
          //console.error('Erreur:', error);
          showMessage(error.message);
      }
      finally {
          submitButton.disabled = false;
          submitButton.textContent = 'Modifier';
      }
    });
    
  }

  async function setupModifyServiceForm() {

    const { btn, modal, form } = modals.service;

    if (!btn || !modal || !form) {
      //console.error('Éléments manquants:', { btn, modal, form });
      return;
    }

    // Gestion des checkboxes
    const checkboxesModify = document.querySelectorAll('input[name="services[]"]');
    const modifyBtn = document.querySelector('button[data-type="modservice"]');
    const deleteBtn = document.querySelector('button[data-type="delservice"]');
    checkboxesModify.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
          // Compte le nombre de cases cochées
          const checkedBoxes = document.querySelectorAll('input[name="services[]"]:checked');
          const checkedCount = checkedBoxes.length;

          // Gestion du bouton modifier
          if (modifyBtn) {
              modifyBtn.disabled = checkedCount !== 1;
              if (checkedCount > 1) {
                  showMessage('Veuillez sélectionner un seul service pour la modification');
              }
          }

          // Gestion du bouton supprimer
          if (deleteBtn) {
              deleteBtn.disabled = checkedCount === 0;
          }
      });
    });


    modals.service.btn.addEventListener('click', (e) => {
        e.preventDefault();

        // Vérifie la sélection
        const selectedService = document.querySelector('input[name="services[]"]:checked');
        const checkedCount = document.querySelectorAll('input[name="services[]"]:checked').length;

        if(!selectedService || checkedCount > 1) {
            showMessage('Veuillez sélectionner un seul service à modifier');
            return;
        }

        // Récupère les données dans le tableau 
        const row = selectedService.closest('tr');
        
        // Remplissage du formulaire
        form.elements['modify_name'].value = row.children[1].textContent.trim();
        form.elements['modify_description'].value = row.children[2].textContent.trim();
        form.dataset.serviceId = selectedService.value;
        
        // Affichage de la modal
        modal.style.display = 'block';
    });
    
    modals.service.form.addEventListener('submit', async function(e) {
      e.preventDefault();
      //console.log('Soumission du formulaire');

      const formData = new FormData(this);
      const error = validateForm(formData);

      if (error) {
          showMessage(error);
          return;
      }

      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Modification en cours...';

      try {
          const serviceId = this.dataset.serviceId;
          if (!serviceId) {
              throw new Error('ID du service manquant');
          }

          const data = {
              id: parseInt(serviceId),
              modify_name: formData.get('modify_name'),
              modify_description: formData.get('modify_description')
          };

          //console.log('Données à envoyer:', data);

          const isStaff = this.getAttribute('data-role') === 'staff';
          const url = isStaff ? '/staff/services/modifier' : '/admin/services/modifier';

          const response = await fetch(url, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify(data)
          });

          const result = await response.json();
          //console.log('Réponse du serveur:', result);

          if (result.success) {
              showMessage(result.message, 'success');
              closeModal(modals.service.modal);
              setTimeout(() => location.reload(), 4000);
          } else {
              throw new Error(result.message || 'Erreur lors de la modification');
          }
      }
      catch (error) {
          //console.error('Erreur:', error);
          showMessage(error.message);
      }
      finally {
          submitButton.disabled = false;
          submitButton.textContent = 'Modifier';
      }
    });
    
  }

  async function setupModifyAnimalForm() {
    const { btn, modal, form } = modals.animal;

    if (!btn || !modal || !form) {
      //console.error('Éléments manquants:', { btn, modal, form });
      return;
    }

    // Gestion des checkboxes
    const checkboxesModify = document.querySelectorAll('input[name="animals[]"]');
    const modifyBtn = document.querySelector('button[data-type="modanimal"]');
    const deleteBtn = document.querySelector('button[data-type="delanimal"]');
    checkboxesModify.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
          // Compte le nombre de cases cochées
          const checkedBoxes = document.querySelectorAll('input[name="animals[]"]:checked');
          const checkedCount = checkedBoxes.length;

          // Gestion du bouton modifier
          if (modifyBtn) {
              modifyBtn.disabled = checkedCount !== 1;
              if (checkedCount > 1) {
                  showMessage('Veuillez sélectionner un seul animal pour la modification');
              }
          }

          // Gestion du bouton supprimer
          if (deleteBtn) {
              deleteBtn.disabled = checkedCount === 0;
          }
      });
    });

    modals.animal.btn.addEventListener('click', (e) => {
        e.preventDefault();

      // Vérifie la sélection
        const selectedAnimal = document.querySelector('input[name="animals[]"]:checked');
        const checkedCount = document.querySelectorAll('input[name="animals[]"]:checked').length;

        if(!selectedAnimal || checkedCount > 1) {
            showMessage('Veuillez sélectionner un seul animal à modifier');
            return;
        }

        // Récupère les données dans le tableau 
        const row = selectedAnimal.closest('tr');
      

        // Remplissage du formulaire
        form.elements['modify_firstname'].value = row.children[1].textContent.trim();
        form.elements['modify_gender'].value = row.children[2].textContent.trim();
        form.elements['modify_species'].value = row.children[3].textContent.trim();
        form.elements['modify_diet'].value = row.children[4].textContent.trim();
        form.elements['modify_reproduction'].value = row.children[5].textContent.trim();
        form.elements['modify_id_habitat'].value = row.children[6].textContent.trim();

        form.dataset.animalId = selectedAnimal.value;
        
        // Affichage de la modal
        modal.style.display = 'block';
    });
    
    modals.animal.form.addEventListener('submit', async function(e) {
      e.preventDefault();
      //console.log('Soumission du formulaire');

      const formData = new FormData(this);
      const error = validateForm(formData);

      if (error) {
          showMessage(error);
          return;
      }

      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Modification en cours...';

      try {
          const animalId = this.dataset.animalId;
          if (!animalId) {
              throw new Error('ID de l\'animal manquant');
          }

          const data = {
              id: parseInt(animalId),
              modify_firstname: formData.get('modify_firstname'),
              modify_gender: formData.get('modify_gender'),
              modify_species: formData.get('modify_species'),
              modify_diet: formData.get('modify_diet'),
              modify_reproduction: formData.get('modify_reproduction'),
              modify_id_habitat: formData.get('modify_id_habitat')
          };

          //console.log('Données à envoyer:', data);

          const response = await fetch('/admin/animaux/modifier', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify(data)
          });

          const result = await response.json();
          //console.log('Réponse du serveur:', result);

          if (result.success) {
              showMessage(result.message, 'success');
              closeModal(modals.animal.modal);
              setTimeout(() => location.reload(), 4000);
          } else {
              throw new Error(result.message || 'Erreur lors de la modification');
          }
      }
      catch (error) {
          //console.error('Erreur:', error);
          showMessage(error.message);
      }
      finally {
          submitButton.disabled = false;
          submitButton.textContent = 'Modifier';
      }
    });
  }

  async function setupModifyReportForm() {

    const { btn, modal, form } = modals.report;

    if (!btn || !modal || !form) {
      //console.error('Éléments manquants:', { btn, modal, form });
      return;
    }
    // Gestion des checkboxes
    const checkboxesModify = document.querySelectorAll('input[name="reports[]"]');
    const modifyBtn = document.querySelector('button[data-type="modreport"]');
    const deleteBtn = document.querySelector('button[data-type="delreport"]');

    checkboxesModify.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            // Compte le nombre de cases cochées
            const checkedBoxes = document.querySelectorAll('input[name="reports[]"]:checked');
            const checkedCount = checkedBoxes.length;

            // Gestion du bouton modifier
            if (modifyBtn) {
                modifyBtn.disabled = checkedCount !== 1;
                if (checkedCount > 1) {
                    showMessage('Veuillez sélectionner un seul rapport pour la modification');
                }
            }

            // Gestion du bouton supprimer
            if (deleteBtn) {
                deleteBtn.disabled = checkedCount === 0;
            }
        });
    });
    

    modals.report.btn.addEventListener('click', (e) => {
      e.preventDefault();

      const selectedReport = document.querySelector('input[name="reports[]"]:checked');
      const checkedCount = document.querySelectorAll('input[name="reports[]"]:checked').length;

      if(!selectedReport || checkedCount > 1) {
          showMessage('Veuillez sélectionner un seul rapport à modifier');
          return;
      }

      // Récupère les données dans le tableau 
      const row = selectedReport.closest('tr');
    
      // Remplissage du formulaire
      form.elements['modify_id_animal'].value = row.children[0].textContent.trim();
      form.elements['modify_health_status'].value = row.children[1].textContent.trim();
      form.elements['modify_prescription'].value = row.children[3].textContent.trim();
      form.elements['modify_quantity'].value = row.children[4].textContent.trim();
      form.elements['modify_habitat_condition'].value = row.children[5].textContent.trim();

      form.dataset.reportId = selectedReport.value;
      
      // Affichage de la modal
      modal.style.display = 'block';

    });

    modals.report.form.addEventListener('submit', async function(e) {
      e.preventDefault();
      //console.log('Soumission du formulaire');

      const formData = new FormData(this);
      const error = validateForm(formData);

      if (error) {
          showMessage(error);
          return;
      }

      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Modification en cours...';

      try {
          const reportId = this.dataset.reportId;
          if (!reportId) {
              throw new Error('ID du rapport est manquant');
          }

          const data = {
              id: parseInt(reportId),
              modify_health_status: formData.get('modify_health_status'),
              modify_prescription: formData.get('modify_prescription'),
              modify_quantity: formData.get('modify_quantity'),
              modify_habitat_condition: formData.get('modify_habitat_condition')
          };

          //console.log('Données à envoyer:', data);
          const isVeto = this.getAttribute('data-role') === 'veto';
          const url = isVeto ? '/veto/journal/modifier' : '/admin/journal/modifier';

          const response = await fetch(url, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify(data)
          });

          const result = await response.json();
          //console.log('Réponse du serveur:', result);

          if (result.success) {
              showMessage(result.message, 'success');
              // Mettre à jour la date de passage dans le tableau si nécessaire
              const row = document.querySelector(`input[value="${reportId}"]`).closest('tr');
              const passageCell = row.children[2]; // Cellule contenant la date de passage
              passageCell.textContent = result.date_passage;
              closeModal(modals.report.modal);
              setTimeout(() => location.reload(), 4000);
          } else {
              throw new Error(result.message || 'Erreur lors de la modification');
          }
      }
      catch (error) {
          //console.error('Erreur:', error);
          showMessage(error.message);
      }
      finally {
          submitButton.disabled = false;
          submitButton.textContent = 'Modifier';
      }
    });


  }

  async function setupModifyCompteForm() {
    //console.log("Initialisation du formulaire de modification de compte");
    const { btn, modal, form } = modals.user;
    //console.log("Éléments récupérés:", { btn, modal, form });
    if (!btn || !modal || !form) {
      //console.error('Éléments manquants:', { btn, modal, form });
      return;
    }

    // Gestion des checkboxes
    const checkboxesModify = document.querySelectorAll('input[name="selected_users[]"]');
    const modifyBtn = document.querySelector('button[data-type="moduser"]');
    const deleteBtn = document.querySelector('button[data-type="deluser"]');

    checkboxesModify.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            // Compte le nombre de cases cochées
            const checkedBoxes = document.querySelectorAll('input[name="selected_users[]"]:checked');
            const checkedCount = checkedBoxes.length;

            // Gestion du bouton modifier
            if (modifyBtn) {
                modifyBtn.disabled = checkedCount !== 1;
                if (checkedCount > 1) {
                    showMessage('Veuillez sélectionner un seul utilisateur pour la modification');
                }
            }

            // Gestion du bouton supprimer
            if (deleteBtn) {
                deleteBtn.disabled = checkedCount === 0;
            }
        });
    });

    modals.user.btn.addEventListener('click', async (e) => {
      e.preventDefault();

      const selectedUser = document.querySelector('input[name="selected_users[]"]:checked');
      const checkedCount = document.querySelectorAll('input[name="selected_users[]"]:checked').length;

      if(!selectedUser || checkedCount > 1) {
          showMessage('Veuillez sélectionner un seul utilisateur à modifier');
          return;
      }
      
        // bug - affiche l'ID
      //console.log("ID de l'utilisateur sélectionné:", selectedUser.value);
      //console.log("URL complète:", `/admin/comptes/modifier/${selectedUser.value}`);

      try {
        // Récupération des données de l'utilisateur depuis le serveur
        const response = await fetch(`/admin/comptes/modifier/${selectedUser.value}`, {
          method: 'GET',
          headers: {
              'Accept': 'application/json'
          }
        });
        // bug - verif la réponse
        //console.log("Status de la réponse:", response.status);

        const result = await response.json();
        //console.log("Données reçues:", result);

        // Si la récupération a réussi, on remplit le formulaire
        if (result.success) {
          form.elements['modify_username'].value = result.user.username;
          form.elements['modify_email'].value = result.user.email;
          form.elements['modify_role'].value = result.user.role;
          form.elements['modify_password'].value = ''; // Champ mot de passe vide par défaut
          form.dataset.userId = result.user.id;
          modal.style.display = 'block';
        } else {
          // Si le serveur renvoie une erreur
          throw new Error(result.message);
        }
      } catch (error) {
        // Gestion de toutes les erreurs possibles
        //console.error("Erreur complète:", error);
        showMessage(error.message);
      }

    });

    modals.user.form.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const error = validateForm(formData);

      // Validation des données avant envoi
      if (error) {
        showMessage(error);
        return;
      }

      // Gestion du bouton pendant la soumission
      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Modification en cours...';

      // Encore un try/catch car on fait un appel réseau
      try {
        const userId = this.dataset.userId;
        if (!userId) {
          throw new Error('ID utilisateur manquant');
        }

        // Préparation des données à envoyer
        const data = {
          id: parseInt(userId),
          modify_username: formData.get('modify_username'),
          modify_email: formData.get('modify_email'),
          modify_role: formData.get('modify_role'),
          modify_password: formData.get('modify_password') || null // null si pas de nouveau mot de passe
        };

        // Envoi des données au serveur
        const response = await fetch('/admin/comptes/modifier', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        // Traitement de la réponse
        if (result.success) {
          showMessage(result.message, 'success');
          // Message spécial si un nouveau mot de passe a été défini
          if (data.modify_password) {
            showMessage('Un email avec le nouveau mot de passe a été envoyé à l\'utilisateur', 'success');
          }
          closeModal(modals.user.modal);
          setTimeout(() => location.reload(), 4000);
        } else {
          throw new Error(result.message || 'Erreur lors de la modification');
        }
      } catch (error) {
        // Gestion des erreurs lors de l'envoi ou du traitement
        showMessage(error.message);
      } finally {
        // Réinitialisation du bouton quoi qu'il arrive
        submitButton.disabled = false;
        submitButton.textContent = 'Modifier';
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
  setupModifyHabitatForm();
  setupModifyServiceForm();
  setupModifyAnimalForm();
  setupModifyReportForm();
  setupModifyCompteForm();
});
