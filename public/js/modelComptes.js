document.getElementById('toggleFormBtn').addEventListener('click', function() {
  var form = document.getElementById('addUserForm');
  if (form.style.display === 'none') {
    form.style.display = 'block';
    this.textContent = 'Cacher le formulaire';
  } else {
    form.style.display = 'none';
    this.textContent = 'Ajouter un compte';
  }
});