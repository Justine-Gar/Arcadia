document.addEventListener('DOMContentLoaded', function() {

//-------Selection des éléments-------//
  const deleteButtons = {
    user: {
      btn: document.querySelector('button[data-type="deluser"]')
    },
    report: {
      btn: document.querySelector('button[data-type="delreport"]')
    },
    animal: {
      btn: document.querySelector('button[data-type="delanimal"]')
    },
    service: {
      btn: document.querySelector('button[data-type="delservice"]')
    },
    habitat: {
      btn: document.querySelector('button[data-type="delhabitat"]')
    }
  }

//-------Function Utilisation-------//
  // - Affiche un message
  function showMessage(message, type = 'error') {
    const existingAlert = document.querySelector('.alert');
    if (existingAlert) existingAlert.remove();

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
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
    
    Object.assign(alertDiv.style, {
      padding: '15px',
      marginBottom: '20px',
      borderRadius: '4px',
      ...styles[type]
    });

    const mainContent = document.querySelector('.main_content');
    if (mainContent) {
      mainContent.insertBefore(alertDiv, mainContent.firstChild);
      setTimeout(() => alertDiv.remove(), 5000);
    }
  }

  //Confirmation de suppression
  function showConfirmDialog(message) {
    const dialogContainer = document.createElement('div');
    dialogContainer.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    `;

    const dialog = document.createElement('div');
    dialog.style.cssText = `
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    `;

    dialog.innerHTML = `
      <h3 style="margin-bottom: 15px;">Confirmation de suppression</h3>
      <p style="margin-bottom: 20px;">${message}</p>
      <div style="display: flex; justify-content: center; gap: 10px;">
        <button class="cancel" style="padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; background-color: #6c757d; color: white;">Annuler</button>
        <button class="confirm" style="padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; background-color: #dc3545; color: white;">Confirmer</button>
      </div>
    `;

    return new Promise(resolve => {
      const confirmBtn = dialog.querySelector('.confirm');
      const cancelBtn = dialog.querySelector('.cancel');

      confirmBtn.addEventListener('click', () => {
        dialogContainer.remove();
        resolve(true);
      });

      cancelBtn.addEventListener('click', () => {
        dialogContainer.remove();
        resolve(false);
      });

      dialogContainer.appendChild(dialog);
      document.body.appendChild(dialogContainer);
    });
  }

//-------Gestion de la suppression-------//
  // - Setup suppression User
  async function setupDeleteComptes() {
    const { btn } = deleteButtons.user;

    if(!btn) return;

    const checkboxesDelete = document.querySelectorAll('input[name="selected_users[]"]');
    checkboxesDelete.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        const deleteBtn = document.querySelector('button[data-type="deluser"]');
        if (deleteBtn) {
          deleteBtn.disabled = !document.querySelector('input[name="selected_users[]"]:checked');
        }      
      });
    });

    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      const selectedUsers = document.querySelectorAll('input[name="selected_users[]"]:checked');
      if (selectedUsers.length === 0) {
          showMessage('Veuillez sélectionner au moins un utilisateur à supprimer');
          return;
      }

      // Récupération des noms d'utilisateurs
      const userDetails = Array.from(selectedUsers).map(checkbox => {
          const row = checkbox.closest('tr');
          return {
              id: checkbox.value,
              username: row.cells[0].textContent.trim(),
              email: row.cells[1].textContent.trim(),
              role: row.cells[2].textContent.trim()
          };
      });

      let confirmMessage;
      if (userDetails.length === 1) {
          confirmMessage = `Êtes-vous sûr de vouloir supprimer l'utilisateur "${userDetails[0].username}" ?`;
      } else {
          const usernames = userDetails.map(user => `"${user.username}"`).join(', ');
          confirmMessage = `Êtes-vous sûr de vouloir supprimer les utilisateurs suivants : ${usernames} ?`;
      }

      const confirmed = await showConfirmDialog(confirmMessage);

      if(confirmed) {
          const userIds = userDetails.map(user => user.id);

          try {
              const response = await fetch('/admin/comptes/supprimer', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({ 
                    userIds: userDetails.map(user => user.id),
                    usernames: userDetails.map(user => user.username)
                  })
              });

              const data = await response.json();

              if (data.success) {
                  // Message de succès personnalisé
                  let successMessage;
                  if (userDetails.length === 1) {
                      successMessage = `L'utilisateur "${userDetails[0].username}" a été supprimé avec succès`;
                  } else {
                      const usernames = userDetails.map(user => `"${user.username}"`).join(', ');
                      successMessage = `Les utilisateurs suivants ont été supprimés : ${usernames}`;
                  }
                  showMessage(successMessage, 'success');
                  setTimeout(() => location.reload(), 4000);
              } else {
                  showMessage(data.message);
              }

          } catch(error) {
              console.error('Erreur:', error);
              showMessage('Une erreur est survenue lors de la suppression');
          }
      }
    });
  }
  // - Setup suppression Rapport
  async function setupDeleteReports() {
    const { btn } = deleteButtons.report;

    if(!btn) return;

    const checkboxesDelete = document.querySelectorAll('input[name="reports[]"]');
    checkboxesDelete.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        const deleteBtn = document.querySelector('button[data-type="delreport"]');
        if (deleteBtn) {
          deleteBtn.disabled = !document.querySelector('input[name="reports[]"]:checked');
        }      
      });
    });

    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      const selectedReports = document.querySelectorAll('input[name="reports[]"]:checked');
      if (selectedReports.length === 0) {
        showMessage('Veuillez sélectionner au moins un rapport à supprimer');
        return;
      }

      //recup les données
      const reportDetails = Array.from(selectedReports).map(checkbox => {
        const row = checkbox.closest('tr');
        return {
            id: checkbox.value,
            animalName: row.cells[0].textContent.trim(), // Nom de l'animal
            healthStatus: row.cells[1].textContent.trim(), // Statut de santé
            date: row.cells[2].textContent.trim() // Date du rapport
        };
      });

      //message de confirmation pour supp
      let confirmMessage;
      if (reportDetails.length === 1) {
          confirmMessage = `Êtes-vous sûr de vouloir supprimer le rapport de "${reportDetails[0].animalName}" du ${reportDetails[0].date} ?`;
      } else {
          const reportList = reportDetails.map(report => `"${report.animalName} (${report.date})"`).join(', ');
          confirmMessage = `Êtes-vous sûr de vouloir supprimer les rapports suivants : ${reportList} ?`;
      }
      const confirmed = await showConfirmDialog(confirmMessage);

      if(confirmed) {
        const deleteButton = document.querySelector('button[data-type="delreport"]');
        const isVeto = deleteButton.getAttribute('data-role') === 'veto';
        const url = isVeto ? '/veto/journal/supprimer' : '/admin/journal/supprimer';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    reportIds: reportDetails.map(report => report.id),
                    reportInfos: reportDetails
                })
            });

            const data = await response.json();

            if (data.success) {
                // Message de succès personnalisé
                let successMessage;
                if (reportDetails.length === 1) {
                    successMessage = `Le rapport de "${reportDetails[0].animalName}" du ${reportDetails[0].date} a été supprimé avec succès`;
                } else {
                    const reportList = reportDetails.map(report => 
                        `"${report.animalName} (${report.date})"`
                    ).join(', ');
                    successMessage = `Les rapports suivants ont été supprimés : ${reportList}`;
                }
                showMessage(successMessage, 'success');
                setTimeout(() => location.reload(), 4000);
            } else {
                showMessage(data.message);
            }

        } catch(error) {
            console.error('Erreur:', error);
            showMessage('Une erreur est survenue lors de la suppression');
        }
      }
    });
    
  }
  // - Setup suppression Animal
  async function setupDeleteAnimals() {
    const { btn } = deleteButtons.animal;

    if(!btn) return;

    const checkboxesDelete = document.querySelectorAll('input[name="animals[]"]');
    checkboxesDelete.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        const deleteBtn = document.querySelector('button[data-type="delanimal"]');
        if (deleteBtn) {
          deleteBtn.disabled = !document.querySelector('input[name="animals[]"]:checked');
        }      
      });
    });

    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      const selectedAnimal = document.querySelectorAll('input[name="animals[]"]:checked');
      if (selectedAnimal.length === 0) {
        showMessage('Veuillez sélectionner au moins un animal à supprimer');
        return;
      }

      //recup les données
      const animalDetails = Array.from(selectedAnimal).map(checkbox => {
        const row = checkbox.closest('tr');
        return {
            id: checkbox.value,
            animalName: row.cells[1].textContent.trim()
        };
      });

      //message de confirmation pour supp
      let confirmMessage;
      if (animalDetails.length === 1) {
          confirmMessage = `Êtes-vous sûr de vouloir supprimer l'animal: "${animalDetails[0].animalName}"?`;
      } else {
          const animalList = animalDetails.map(animal => `"${animal.animalName}"`).join(', ');
          confirmMessage = `Êtes-vous sûr de vouloir supprimer les animaux suivants : ${animalList} ?`;
      }
      const confirmed = await showConfirmDialog(confirmMessage);

      if(confirmed) {
        try {
            const response = await fetch('/admin/animaux/supprimer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    animalIds: animalDetails.map(animal => animal.id),
                    animalInfos: animalDetails
                })
            });

            const data = await response.json();

            if (data.success) {
                // Message de succès personnalisé
                let successMessage;
                if (animalDetails.length === 1) {
                    successMessage = `L'animal: "${animalDetails[0].animalName}" a été supprimé avec succès`;
                } else {
                    const animalList = animalDetails.map(animal => 
                        `"${animal.animalName}"`
                    ).join(', ');
                    successMessage = `Les animaux suivants ont été supprimés : ${animalList}`;
                }
                showMessage(successMessage, 'success');
                setTimeout(() => location.reload(), 4000);
            } else {
                showMessage(data.message);
            }

        } catch(error) {
            console.error('Erreur:', error);
            showMessage('Une erreur est survenue lors de la suppression');
        }
      }
    });
    
  }
  // - Setup suppression Service
  async function setupDeleteServices() {
    const { btn } = deleteButtons.service;

    if(!btn) return;

    const checkboxesDelete = document.querySelectorAll('input[name="services[]"]');
    checkboxesDelete.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        const deleteBtn = document.querySelector('button[data-type="delservice"]');
        if (deleteBtn) {
          deleteBtn.disabled = !document.querySelector('input[name="services[]"]:checked');
        }      
      });
    });

    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      const selectedService = document.querySelectorAll('input[name="services[]"]:checked');
      if (selectedService.length === 0) {
        showMessage('Veuillez sélectionner au moins un service à supprimer');
        return;
      }

      //recup les données
      const serviceDetails = Array.from(selectedService).map(checkbox => {
        const row = checkbox.closest('tr');
        return {
            id: checkbox.value,
            serviceName: row.cells[1].textContent.trim()
        };
      });

      //message de confirmation pour supp
      let confirmMessage;
      if (serviceDetails.length === 1) {
          confirmMessage = `Êtes-vous sûr de vouloir supprimer le service: "${serviceDetails[0].serviceName}"?`;
      } else {
          const serviceList = serviceDetails.map(service => `"${service.serviceName}"`).join(', ');
          confirmMessage = `Êtes-vous sûr de vouloir supprimer les services suivants : ${serviceList} ?`;
      }
      const confirmed = await showConfirmDialog(confirmMessage);

      if(confirmed) {
        try {
            const response = await fetch('/admin/services/supprimer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    serviceIds: serviceDetails.map(service => service.id),
                    serviceInfos: serviceDetails
                })
            });

            const data = await response.json();

            if (data.success) {
                // Message de succès personnalisé
                let successMessage;
                if (serviceDetails.length === 1) {
                    successMessage = `Le service: "${serviceDetails[0].serviceName}" a été supprimé avec succès`;
                } else {
                    const serviceList = serviceDetails.map(service => 
                        `"${service.serviceName}"`
                    ).join(', ');
                    successMessage = `Les services suivants ont été supprimés : ${serviceList}`;
                }
                showMessage(successMessage, 'success');
                setTimeout(() => location.reload(), 4000);
            } else {
                showMessage(data.message);
            }

        } catch(error) {
            console.error('Erreur:', error);
            showMessage('Une erreur est survenue lors de la suppression');
        }
      }
    });
    
  }
  // - Setup suppression Habitat
  async function setupDeleteHabitats() {
    const { btn } = deleteButtons.habitat;

    if(!btn) return;

    const checkboxesDelete = document.querySelectorAll('input[name="habitats[]"]');
    checkboxesDelete.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        const deleteBtn = document.querySelector('button[data-type="delhabitat"]');
        if (deleteBtn) {
          deleteBtn.disabled = !document.querySelector('input[name="habitats[]"]:checked');
        }      
      });
    });

    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      const selectedHabitat = document.querySelectorAll('input[name="habitats[]"]:checked');
      if (selectedHabitat.length === 0) {
        showMessage('Veuillez sélectionner au moins un habitat à supprimer');
        return;
      }

      //recup les données
      const habitatDetails = Array.from(selectedHabitat).map(checkbox => {
        const row = checkbox.closest('tr');
        return {
            id: checkbox.value,
            habitatName: row.cells[1].textContent.trim()
        };
      });

      //message de confirmation pour supp
      let confirmMessage;
      if (habitatDetails.length === 1) {
          confirmMessage = `Êtes-vous sûr de vouloir supprimer l'habitat: "${habitatDetails[0].habitatName}"?`;
      } else {
          const habitatList = habitatDetails.map(habitat => `"${habitat.habitatName}"`).join(', ');
          confirmMessage = `Êtes-vous sûr de vouloir supprimer les habitats suivants : ${habitatList} ?`;
      }
      const confirmed = await showConfirmDialog(confirmMessage);

      if(confirmed) {
        try {
            const response = await fetch('/admin/habitats/supprimer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    habitatIds: habitatDetails.map(habitat => habitat.id),
                    habitatInfos: habitatDetails
                })
            });

            const data = await response.json();

            if (data.success) {
                // Message de succès personnalisé
                let successMessage;
                if (habitatDetails.length === 1) {
                    successMessage = `L'habitat: "${habitatDetails[0].habitatName}" a été supprimé avec succès`;
                } else {
                    const habitatList = habitatDetails.map(habitat => 
                        `"${habitat.habitatName}"`
                    ).join(', ');
                    successMessage = `Les habitats suivants ont été supprimés : ${habitatList}`;
                }
                showMessage(successMessage, 'success');
                setTimeout(() => location.reload(), 4000);
            } else {
                showMessage(data.message);
            }

        } catch(error) {
            console.error('Erreur:', error);
            showMessage('Une erreur est survenue lors de la suppression');
        }
      }
    });
    
  }


//-------GInitialisation-------//
  setupDeleteComptes();
  setupDeleteReports();
  setupDeleteAnimals();
  setupDeleteServices();
  setupDeleteHabitats();
})