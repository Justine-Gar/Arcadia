document.addEventListener('DOMContentLoaded', function() {
  // Sélection des éléments
  const btnLogin = document.getElementById('btnLogin');
  const modalLogin = document.getElementById('modalLogin');
  const closeBtn = modalLogin.querySelector('.close');
  
  // Ouverture de la modal
  function openModal(e) {
    e.preventDefault(); // Empêche le comportement par défaut du lien
    modalLogin.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
  
  // Fermeture de la modal
  function closeModal() {
    modalLogin.style.display = 'none';
    document.body.style.overflow = 'auto';
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
});