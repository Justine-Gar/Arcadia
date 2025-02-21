document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalEditHoraire');
    const editBtn = document.getElementById('editHorairesBtn');
    const closeBtn = modal.querySelector('.close');
    const editForm = document.getElementById('formEditHoraire');
    const daySelect = document.getElementById('select_day');

    // Ajout de la définition des horaires existants
    const existingTimetables = JSON.parse(document.getElementById('timetablesData').value);

    editBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
    });

    daySelect.addEventListener('change', function () {
        const selectedDay = this.value;
        if (selectedDay && existingTimetables[selectedDay]) {
            document.getElementById('edit_open').value = existingTimetables[selectedDay].open;
            document.getElementById('edit_close').value = existingTimetables[selectedDay].close;
        }
    });

    editForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const selectedDay = daySelect.value;
        const timetableId = existingTimetables[selectedDay]?.id;

        if (!timetableId) {
            showMessage('Jour non trouvé', 'error');
            return;
        }

        try {
            const response = await fetch('/admin/horaires/modifier', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: timetableId,
                    modify_days: selectedDay,
                    modify_open_hours: document.getElementById('edit_open').value,
                    modify_close_hours: document.getElementById('edit_close').value
                })
            });

            const data = await response.json();

            if (data.success) {
                showMessage('Horaire modifié avec succès', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showMessage(data.message, 'error');
            }

        } catch (error) {
            console.error('Erreur:', error);
            showMessage('Une erreur est survenue', 'error');
        }
    });

    function showMessage(message, type = 'error') {
        // Supprime tout message existant
        const existingMessage = document.getElementById('modalMessage');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Crée le nouvel élément de message
        const messageDiv = document.createElement('div');
        messageDiv.id = 'modalMessage';
        messageDiv.className = `modal-message ${type}`;
        messageDiv.textContent = message;

        // Styles pour le message
        Object.assign(messageDiv.style, {
            padding: '10px 15px',
            marginBottom: '15px',
            borderRadius: '4px',
            textAlign: 'center',
            fontWeight: '500'
        });

        // Styles spécifiques selon le type de message
        if (type === 'success') {
            Object.assign(messageDiv.style, {
                backgroundColor: '#dff0d8',
                color: '#3c763d',
                border: '1px solid #d6e9c6'
            });
        } else {
            Object.assign(messageDiv.style, {
                backgroundColor: '#f2dede',
                color: '#a94442',
                border: '1px solid #ebccd1'
            });
        }

        // Insère le message au début du formulaire
        const form = document.getElementById('formEditHoraire');
        form.insertBefore(messageDiv, form.firstChild);

        // Si c'est un succès, ferme la modal et recharge la page après un délai
        if (type === 'success') {
            setTimeout(() => {
                document.getElementById('modalEditHoraire').style.display = 'none';
                location.reload();
            }, 2000);
        }
    }
});