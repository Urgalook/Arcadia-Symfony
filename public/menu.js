const btnMenu = document.querySelector('.btn-menu');
const dropdown = document.querySelector('.dropdown');
const dropdownMenu = document.querySelector('.dropdown-menu');
const aria = document.querySelector('.aria-expanded');

btnMenu.addEventListener('click', () => {
  dropdown.classList.toggle('show');
  dropdownMenu.classList.toggle('show');
  aria.classList.toggle('true');
});

