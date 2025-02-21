document.addEventListener('DOMContentLoaded', () => {
  const btnAvisForm = document.getElementById('btnAvisForm');
  const avisForm = document.getElementById('avis_form');
  const avisContainer = document.getElementById('avis_container_text');

  // Cache le formulaire
  avisForm.style.display = 'none';

  // Gestion de l'affichage du formulaire
  btnAvisForm.addEventListener('click', () => {
    if (avisForm.style.display === 'none') {
      avisForm.style.display = 'block';
      btnAvisForm.textContent = 'Fermer';
    } else {
      avisForm.style.display = 'none';
      btnAvisForm.textContent = 'un avis ?';
    }
  });

  // Gestion de l'envoi du formulaire
  avisForm.addEventListener('submit', async function (event) {
    event.preventDefault();

    const clientName = document.getElementById('clientName').value.trim();
    const clientRating = parseInt(document.getElementById('clientRating').value, 10);
    const clientText = document.getElementById('clientText').value.trim();

    // Validation
    if (clientName.length === 0 || clientName.length > 50) {
      alert('Le nom du client doit contenir entre 1 et 50 caractères.');
      return;
    }
    if (isNaN(clientRating) || clientRating < 1 || clientRating > 5) {
      alert('La note doit être comprise entre 1 et 5.');
      return;
    }
    if (clientText.length === 0) {
      alert('L\'avis ne peut pas être vide.');
      return;
    }

    try {
      const response = await fetch('/ajouter-avis', {  // Assurez-vous que cette route correspond à votre configuration
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          name: clientName,           // Modifié pour correspondre à la structure de la BD
          description: clientText,    // Modifié pour correspondre à la structure de la BD
          score: clientRating
        })
      });

      const data = await response.json();

      if (data.success) {
        alert('Merci ! Votre avis a été soumis et sera publié après modération.');
        // Réinitialiser le formulaire
        this.reset();
        // Fermer le formulaire
        avisForm.style.display = 'none';
        btnAvisForm.textContent = 'un avis ?';
      } else {
        alert(data.message || 'Une erreur est survenue lors de l\'envoi de votre avis.');
      }

    } catch (error) {
      console.error('Erreur:', error);
      alert('Une erreur est survenue lors de l\'envoi de votre avis.');
    }
  });

  // Fonction pour échapper le HTML
  function escapeHTML(str) {
    return str.replace(/[&<>'"]/g,
      tag => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;'
      }[tag] || tag)
    );
  }

  // Fonction pour générer les étoiles
  function generateStars(rating) {
    return Array.from({ length: 5 }, (_, i) =>
      `<span class="star ${i < rating ? 'filled' : ''}">★</span>`
    ).join('');
  }
});