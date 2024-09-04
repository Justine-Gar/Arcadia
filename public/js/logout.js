document.addEventListener('DOMContentLoaded', function() {
  const btnLogout = document.getElementById('btnLogout');

  if (btnLogout) {
      btnLogout.addEventListener('click', function(e) {
          e.preventDefault();

          fetch('/logout', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/x-www-form-urlencoded',
              },
          })
          .then(response => response.text())
          .then(data => {
              console.log('Réponse du serveur:', data);
              try {
                  const jsonData = JSON.parse(data);
                  console.log('Données JSON parsées:', jsonData);
                  if (jsonData.success) {
                      // Si réussi, on redirige
                      window.location.href = jsonData.redirect || '/';
                  } else {
                      // Sinon, on affiche un message d'erreur
                      alert(jsonData.message || 'Erreur lors de la déconnexion');
                  }
              } catch (error) {
                  console.error('Erreur de parsing JSON:', error);
                  alert('Le serveur a renvoyé une réponse invalide.');
              }
          })
          .catch(error => {
              console.error('Erreur:', error);
              alert('Une erreur est survenue lors de la déconnexion.');
          });
      });
  }
});