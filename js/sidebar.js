const sidebarToggle = document.querySelector('#btn');
const sidebarSearch = document.querySelector('.bx-search');
const sidebar = document.querySelector('.sidebar');

const toggleSidebar = () => sidebar?.classList.toggle('active');

sidebarToggle?.addEventListener('click', toggleSidebar);
sidebarSearch?.addEventListener('click', toggleSidebar);
