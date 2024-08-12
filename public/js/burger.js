const burgerMenu = document.getElementById('burger_menu');
const overlay = document.querySelector('.navbar');
const menuItems = document.querySelector('.menu_items');
const body = document.body;


burgerMenu.addEventListener('click', function () {
  this.classList.toggle("close");
  overlay.classList.toggle("overlay");
  overlay.classList.toggle("mobile_open");
  body.classList.toggle("menu_open");
});