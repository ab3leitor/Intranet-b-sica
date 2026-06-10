document.addEventListener('DOMContentLoaded', () => {
  const sidebarLinks = Array.from(document.querySelectorAll('.sidebar ul li a'));
  const currentPage = window.location.pathname.split('/').pop() || 'Inicio.php';

  sidebarLinks.forEach((link) => {
    const targetPage = (link.getAttribute('href') || '').split('/').pop();
    if (targetPage === currentPage) {
      link.classList.add('is-active');
    }
  });

  const messageLink = document.querySelector('.sidebar a[href="Mensajes.php"]');
  if (!messageLink) return;

  const renderBadge = (count) => {
    let badge = messageLink.querySelector('.sidebar-badge');

    if (!count || count <= 0) {
      if (badge) badge.remove();
      messageLink.classList.remove('has-notification');
      return;
    }

    if (!badge) {
      badge = document.createElement('span');
      badge.className = 'sidebar-badge';
      messageLink.appendChild(badge);
    }

    badge.textContent = count > 99 ? '99+' : count;
    messageLink.classList.add('has-notification');
  };

  const loadNotifications = async () => {
    try {
      const response = await fetch('php/ObtenerNotificacionesSidebar.php', {
        cache: 'no-store'
      });
      if (!response.ok) return;

      const data = await response.json();
      renderBadge(Number(data.message_count || 0));
    } catch (error) {
      console.error('Error cargando notificaciones:', error);
    }
  };

  window.refreshSidebarNotifications = loadNotifications;

  loadNotifications();
  setInterval(loadNotifications, 30000);
});
