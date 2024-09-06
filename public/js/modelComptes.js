document.addEventListener('DOMContentLoaded', function () {
  // Sélection des éléments du DOM
  const toggleFormBtn = document.getElementById('toggleFormBtn');
  const addUserForm = document.getElementById('addUserForm');
  const btnModifier = document.querySelector('button[name="action"][value="modifier"]');
  const btnSupprimer = document.querySelector('button[name="action"][value="supprimer"]');
  const modalModifierUser = document.getElementById('modalModifierUser');
  const closeBtn = modalModifierUser.querySelector('.close');
  const formModifierUser = document.getElementById('formModifierUser');

  // Fonction pour afficher/cacher le formulaire d'ajout d'utilisateur
  toggleFormBtn.addEventListener('click', function() {
    if (addUserForm.style.display === 'none') {
      addUserForm.style.display = 'block';
      this.textContent = 'Cacher le formulaire';
    } else {
      addUserForm.style.display = 'none';
      this.textContent = 'Ajouter un compte';
    }
  });

  // Fonction pour ouvrir la modal de modification
  function openModal(e) {
    e.preventDefault();
    const selectedUsers = document.querySelectorAll('input[name="selected_users[]"]:checked');
    if (selectedUsers.length !== 1) {
      alert('Veuillez sélectionner un seul utilisateur à modifier.');
      return;
    }
    const userId = selectedUsers[0].value;
    document.getElementById('userId').value = userId;
    
    // Charger les données de l'utilisateur
    fetch(`/admin/comptes/modifier/${userId}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Remplir le formulaire avec les données de l'utilisateur
        formModifierUser.username.value = data.user.username;
        formModifierUser.email.value = data.user.email;
        formModifierUser.role.value = data.user.role;
        modalModifierUser.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      } else {
        alert('Erreur lors du chargement des données de l\'utilisateur');
      }
    })
    .catch(error => {
      console.error('Erreur:', error);
      alert('Une erreur est survenue lors du chargement des données.');
    });
  }

  // Fonction pour fermer la modal
  function closeModal() {
    modalModifierUser.style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  // Ajouter les écouteurs d'événements pour la modal
  btnModifier.addEventListener('click', openModal);
  closeBtn.addEventListener('click', closeModal);
  window.addEventListener('click', (event) => {
    if (event.target === modalModifierUser) {
      closeModal();
    }
  });
  modalModifierUser.querySelector('.modal_container').addEventListener('click', (event) => {
    event.stopPropagation();
  });

  // Gestion de la soumission du formulaire de modification
  formModifierUser.addEventListener('submit', function (e) {
    e.preventDefault();

    const userId = this.userId.value;
    const username = this.username.value.trim();
    const email = this.email.value.trim();
    const role = this.role.value;

    if (!username || !email || !role) {
      alert('Veuillez remplir tous les champs.');
      return;
    }

    fetch(`/admin/comptes/modifier/${userId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ username, email, role })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log('Utilisateur modifié avec succès');
        closeModal();
        location.reload();
      } else {
        alert('Erreur lors de la modification : ' + data.message);
      }
    })
    .catch(error => {
      console.error('Erreur:', error);
      alert('Une erreur est survenue lors de la modification.');
    });
  });

  // Gestion de la suppression d'utilisateurs
  btnSupprimer.addEventListener('click', function(e) {
    e.preventDefault();
    //selectionne toute les cases cocher
    const selectedUsers = document.querySelectorAll('input[name="selected_users[]"]:checked');
    //verification si un user et bien selectionner
    if (selectedUsers.length === 0) {
      alert('Veuillez sélectionner au moins un utilisateur à supprimer.');
      return;
    }
    //demande de confirmation
    if (confirm(`Êtes-vous sûr de vouloir supprimer ${selectedUsers.length} utilisateur(s) ?`)) {
      //si confirme selectionne l'id user et supprime
      const userIds = Array.from(selectedUsers).map(checkbox => checkbox.value);
      //requete envoyer au serveux -> via route/ method/content-type
      fetch('/admin/comptes/supprimer', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        //stringify prend obj js et transforme en chaine de caract au format json
        body: JSON.stringify({ userIds: userIds })
      })
      //prend la requet fetch et la convertit en Json
      .then(response => response.json())
      //traite les donnée json extraites de la reponse => data est obj Js
      .then(data => {
        //verifie si opéaration réussis 
        if (data.success) {
          //affiche message alerte
          alert(data.message);
          //recharge la page
          location.reload();
        } else {
          alert('Erreur lors de la suppression : ' + data.message);
        }
      })
      //gere les erreurs du process fetch ou traitement des données
      .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la suppression.');
      });
    }
  });
});