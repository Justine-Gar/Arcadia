document.addEventListener('DOMContentLoaded', function () {
    //console.log('DOM chargé');
    const MAX_FILE_SIZE = 10 * 1024 * 1024; //10mo
    // Gestionnaire pour le bouton d'ouverture de la modal d'upload
    const openImageUploadBtn = document.getElementById('openImageUpload');
    if (openImageUploadBtn) {
        openImageUploadBtn.addEventListener('click', function () {
            const modal = document.getElementById('modalAddImage');
            const fileNameInput = document.getElementById('file_name');
            const animalSelect = document.getElementById('file_id_animal');
            const serviceSelect = document.getElementById('file_id_service');
            const habitatSelect = document.getElementById('file_id_habitat');

            if (modal) {
                modal.style.display = 'block';

                // Mettre à jour le nom de fichier en fonction de l'animal sélectionné
                if (animalSelect && fileNameInput) {
                    const selectedOption = animalSelect.options[animalSelect.selectedIndex];
                    fileNameInput.value = selectedOption.text + "_image";

                    // Mettre à jour le nom du fichier quand la sélection change
                    animalSelect.addEventListener('change', function () {
                        const selectedOption = this.options[this.selectedIndex];
                        fileNameInput.value = selectedOption.text + "_image";
                    });
                }
                if (serviceSelect && fileNameInput) {
                    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                    fileNameInput.value = selectedOption.text + "_image";

                    // Mettre à jour le nom du fichier quand la sélection change
                    serviceSelect.addEventListener('change', function () {
                        const selectedOption = this.options[this.selectedIndex];
                        fileNameInput.value = selectedOption.text + "_image";
                    });
                }
                if (habitatSelect && fileNameInput) {
                    const selectedOption = habitatSelect.options[habitatSelect.selectedIndex];
                    fileNameInput.value = selectedOption.text + "_image";

                    // Mettre à jour le nom du fichier quand la sélection change
                    habitatSelect.addEventListener('change', function () {
                        const selectedOption = this.options[this.selectedIndex];
                        fileNameInput.value = selectedOption.text + "_image";
                    });
                }
            }
        });
    }

    // Fermeture des modals
    document.querySelectorAll('.close').forEach(close => {
        close.addEventListener('click', function () {
            this.closest('.modal').style.display = 'none';
        });
    });

    // Fermer la modal en cliquant en dehors
    window.addEventListener('click', function (event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });

    // Function qui valide la taille image
    function validateFiles(files) {

        const errors = [];

        for (const file of files) {
            //vérification de la taille
            if (file.size > MAX_FILE_SIZE) {
                errors.push(`Le fichier "${file.name}" est trop volumineux (${(file.size / 1024 / 1024).toFixed(2)}Mo). Maximum autorisé : 5Mo`);
            }

            //vérification du type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/avif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                errors.push(`Le fichier "${file.name}" n'est pas dans un format autorisé (JPG, PNG, GIF, AVIF, WEBP)`);
            }
        }

        return errors;
    }

    function createPreviewDiv() {
        const previewDiv = document.createElement('div');
        previewDiv.id = 'image-preview';
        previewDiv.className = 'image-preview-container';
        document.getElementById('formAddImage').insertBefore(
            previewDiv,
            document.querySelector('#formAddImage button')
        );
        return previewDiv;
    }

    // Configuration de la prévisualisation des images
    function setupImagePreview() {
        document.getElementById('image_file').addEventListener('change', function (e) {

            // Ajouter la validation ici
            const files = Array.from(this.files);
            const errors = validateFiles(files);

            // Créer ou réinitialiser le conteneur d'erreurs
            let errorContainer = document.getElementById('upload-errors');
            if (!errorContainer) {
                errorContainer = document.createElement('div');
                errorContainer.id = 'upload-errors';
                errorContainer.className = 'error-messages';
                this.parentNode.insertBefore(errorContainer, this.nextSibling);
            }
            errorContainer.innerHTML = '';

            // Afficher les erreurs s'il y en a
            if (errors.length > 0) {
                errors.forEach(error => {
                    const errorElement = document.createElement('p');
                    errorElement.className = 'error-message';
                    errorElement.style.color = 'red';
                    errorElement.textContent = error;
                    errorContainer.appendChild(errorElement);
                });
                // Réinitialiser l'input file
                this.value = '';
                return; // Arrêter ici si il y a des erreurs
            }

            const previewDiv = document.getElementById('image-preview') || createPreviewDiv();
            previewDiv.innerHTML = ''; // Clear existing previews

            for (const file of this.files) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.createElement('div');
                    preview.className = 'preview-item';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="max-width: 100px; max-height: 100px; margin: 5px;">
                        <span>${file.name}</span>
                    `;
                    previewDiv.appendChild(preview);
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Gestion de l'upload pour les animaux
    function setupAnimalUpload() {
        const formAddImage = document.getElementById('formAddImage');
        if (formAddImage && document.getElementById('file_id_animal')) {
            formAddImage.addEventListener('submit', function (e) {
                e.preventDefault();

                const files = Array.from(document.getElementById('image_file').files);
                const errors = validateFiles(files);

                if (errors.length > 0) {
                    alert('Erreurs de validation:\n' + errors.join('\n'));
                    return;
                }

                const formData = new FormData(this);
                const totalFiles = document.getElementById('image_file').files.length;
                formData.append('total_files', totalFiles);

                fetch('/admin/upload/image/animaux', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Images ajoutées avec succès');
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de l\'ajout des images');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
            });
            setupImagePreview();
        }
    }

    // Gestion de l'upload pour les services
    function setupServiceUpload() {
        const formAddImage = document.getElementById('formAddImage');
        if (formAddImage && document.getElementById('file_id_service')) {
            formAddImage.addEventListener('submit', function (e) {
                e.preventDefault();

                const files = Array.from(document.getElementById('image_file').files);
                const errors = validateFiles(files);

                if (errors.length > 0) {
                    alert('Erreurs de validation:\n' + errors.join('\n'));
                    return;
                }

                const formData = new FormData(this);
                const totalFiles = document.getElementById('image_file').files.length;
                formData.append('total_files', totalFiles);

                const isStaff = this.getAttribute('data-role') === 'staff';
                const url = isStaff ? '/staff/upload/image/services' : '/admin/upload/image/services';

                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Images ajoutées avec succès');
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de l\'ajout des images');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
            });
            setupImagePreview();
        }
    }

    // Gestion de l'upload pour les habitats
    function setupHabitatUpload() {
        const formAddImage = document.getElementById('formAddImage');
        if (formAddImage && document.getElementById('file_id_habitat')) {
            formAddImage.addEventListener('submit', function (e) {
                e.preventDefault();
    
                // Vérification des fichiers
                const files = Array.from(document.getElementById('image_file').files);
                const errors = validateFiles(files);
                
                if (errors.length > 0) {
                    alert('Erreurs de validation:\n' + errors.join('\n'));
                    return;
                }
    
                // Récupération des valeurs importantes
                const habitatId = document.getElementById('file_id_habitat').value;
                const fileName = document.getElementById('file_name').value;
    
                // Création du FormData
                const formData = new FormData();
                formData.append('file_id_habitat', habitatId);
                formData.append('file_name', fileName);
    
                // Ajout des fichiers
                files.forEach((file, index) => {
                    formData.append(`image[${index}]`, file);
                });
    
                // Ajout du nombre total de fichiers
                formData.append('total_files', files.length);
    
    
                fetch('/admin/upload/image/habitats', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Images ajoutées avec succès');
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Erreur lors de l\'ajout des images');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue: ' + (error.message || 'Erreur inconnue'));
                });
            });
            setupImagePreview();
        }
    }
    /*function setupHabitatUpload() {
        const formAddImage = document.getElementById('formAddImage');
        if (formAddImage && document.getElementById('file_id_habitat')) {
            formAddImage.addEventListener('submit', function (e) {
                e.preventDefault();

                const files = Array.from(document.getElementById('image_file').files);
                const errors = validateFiles(files);

                if (errors.length > 0) {
                    alert('Erreurs de validation:\n' + errors.join('\n'));
                    return;
                }

                const formData = new FormData(this);
                const totalFiles = document.getElementById('image_file').files.length;
                formData.append('total_files', totalFiles);

                fetch('/admin/upload/image/habitats', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Images ajoutées avec succès');
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de l\'ajout des images');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
            });
            setupImagePreview();
        }
    }*/

    // Initialisation des uploads selon le contexte
    setupAnimalUpload();
    setupServiceUpload();
    setupHabitatUpload();
});