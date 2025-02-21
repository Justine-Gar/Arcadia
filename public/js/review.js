document.addEventListener('DOMContentLoaded', function () {
    // Gestion des onglets
    const statusTabs = document.querySelectorAll('.status_tab');
    const reviewRows = document.querySelectorAll('.review-row');

    // Par défaut, montrer les avis en attente
    filterReviews('En attente');

    statusTabs.forEach(button => {
        button.addEventListener('click', () => {
            // Retirer la classe active de tous les boutons
            statusTabs.forEach(btn => btn.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            button.classList.add('active');

            // Filtrer les avis selon le statut
            const status = button.dataset.status;
            filterReviews(status);
        });
    });

    function filterReviews(status) {
        reviewRows.forEach(row => {
            if (status === 'all' || row.dataset.reviewStatus === status) {
                row.style.display = '';  // Affiche la ligne
            } else {
                row.style.display = 'none';  // Cache la ligne
            }
        });
    }

    // Ajouter les écouteurs pour les boutons d'action
    document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function () {
            const btnId = this.id;
            const reviewId = btnId.split('_')[1];
            let status, message;

            if (btnId.startsWith('approveBtn')) {
                status = 'Approuvé';
                message = 'Voulez-vous approuver cet avis ?';
            } else if (btnId.startsWith('rejectBtn')) {
                status = 'Rejeté';
                message = 'Voulez-vous rejeter cet avis ?';
            } else if (btnId.startsWith('deleteBtn')) {
                status = 'Supprimer';
                message = 'Voulez-vous supprimer cet avis ?';
            }

            if (confirm(message)) {
                updateReviewStatus(reviewId, status);
            }
        });
    });

    async function updateReviewStatus(reviewId, newStatus) {
        try {
            const response = await fetch('/staff/avis/modifier', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id_review: reviewId,
                    status: newStatus
                })
            });

            const data = await response.json();

            if (data.success) {
                alert('Action effectuée avec succès !');
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }

        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de l\'action');
        }
    }
});