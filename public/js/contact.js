document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const messageStatus = document.getElementById('message-status');
  
  // Désactiver le bouton pendant l'envoi
  const submitButton = this.querySelector('button[type="submit"]');
  submitButton.disabled = true;
  
  fetch('/contact/submit', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      // Afficher le message
      messageStatus.style.display = 'block';
      if (data.success) {
          messageStatus.className = 'message-status success';
          messageStatus.textContent = "✓ " + data.success;
          // Réinitialiser le formulaire en cas de succès
          document.getElementById('contactForm').reset();
      } else {
          messageStatus.className = 'message-status error';
          messageStatus.textContent = "✗ " + (data.error || "Une erreur est survenue");
      }
      
      // Faire disparaître le message après 5 secondes
      setTimeout(() => {
          messageStatus.classList.add('fade-out');
          setTimeout(() => {
              messageStatus.style.display = 'none';
              messageStatus.classList.remove('fade-out');
          }, 500);
      }, 5000);
  })
  .catch(error => {
      messageStatus.style.display = 'block';
      messageStatus.className = 'message-status error';
      messageStatus.textContent = "✗ Une erreur est survenue lors de l'envoi";
  })
  .finally(() => {
      // Réactiver le bouton
      submitButton.disabled = false;
  });
});

function markAsRead(id) {
  fetch(`/admin/mails/read/${id}`, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
      }
  })
  .then(response => {
      if (!response.ok) {
          throw new Error('Erreur réseau');
      }
      return response.json();
  })
  .then(data => {
      if (data.success) {
          location.reload();
      } else {
          throw new Error(data.error || 'Erreur inconnue');
      }
  })
  .catch(error => {
      console.error('Erreur:', error);
      alert('Une erreur est survenue: ' + error.message);
  });
}

// Fonction pour supprimer
function deleteMail(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
      fetch(`/admin/mails/delete/${id}`, {
          method: 'DELETE',
          headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
          }
      })
      .then(response => {
          if (!response.ok) {
              throw new Error('Erreur réseau');
          }
          return response.json();
      })
      .then(data => {
          if (data.success) {
              location.reload();
          } else {
              throw new Error(data.error || 'Erreur inconnue');
          }
      })
      .catch(error => {
          console.error('Erreur:', error);
          alert('Une erreur est survenue: ' + error.message);
      });
  }
}

function viewMessage(button) {
  const row = button.closest('tr');
  const title = row.querySelector('.title-col').textContent;
  const email = row.querySelector('.email-col').textContent;
  const message = row.querySelector('.message-full').innerHTML;

  document.getElementById('modalTitle').textContent = title;
  document.getElementById('modalEmail').innerHTML = `<strong>De:</strong> ${email}`;
  document.getElementById('modalMessage').innerHTML = message;
  document.getElementById('messageModal').style.display = 'block';
}

// Fermer la modal
document.querySelector('.close').onclick = function() {
  document.getElementById('messageModal').style.display = 'none';
}

window.onclick = function(event) {
  const modal = document.getElementById('messageModal');
  if (event.target == modal) {
      modal.style.display = 'none';
  }
}