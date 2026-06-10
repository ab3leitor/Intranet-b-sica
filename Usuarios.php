<?php
session_start();
if (!isset($_SESSION['id'])) {
  header('Location: index.php');
  exit();
}

include("php/conexion_be.php");

$usuario_actual_id = intval($_SESSION['id']);
if (empty($_SESSION['csrf_delete_user'])) {
  $_SESSION['csrf_delete_user'] = bin2hex(random_bytes(32));
}

$stmt = $conexion->prepare("
  SELECT id, nombreCompleto, correoElectronico, usuario, avatar
  FROM usuario
  ORDER BY nombreCompleto ASC, usuario ASC
");
$stmt->execute();
$resultado = $stmt->get_result();

$usuarios = [];
while ($row = $resultado->fetch_assoc()) {
  $usuarios[] = $row;
}

$stmt->close();
$conexion->close();

$statusMessages = [
  'updated' => ['success', 'Usuario actualizado correctamente.'],
  'deleted' => ['success', 'Usuario eliminado correctamente.'],
  'edit_invalid' => ['error', 'Revisa los datos antes de guardar.'],
  'edit_duplicate' => ['error', 'El correo o nombre de usuario ya esta en uso.'],
  'edit_error' => ['error', 'No se pudo actualizar el usuario.'],
  'delete_invalid' => ['error', 'No puedes eliminar ese usuario.'],
  'delete_error' => ['error', 'No se pudo eliminar el usuario.'],
  'not_found' => ['error', 'El usuario no existe.']
];

$status = $_GET['status'] ?? '';
$statusMessage = $statusMessages[$status] ?? null;
$totalUsuarios = count($usuarios);
$otrosUsuarios = max(0, $totalUsuarios - 1);
$sessionName = $_SESSION['nombreCompleto'] ?? $_SESSION['usuario'] ?? 'Usuario';

function initials($name, $fallback) {
  $source = trim($name ?: $fallback);
  if ($source === '') return 'U';

  $parts = preg_split('/\s+/', $source);
  $first = strtoupper(substr($parts[0], 0, 1));
  $second = isset($parts[1]) ? strtoupper(substr($parts[1], 0, 1)) : '';

  return htmlspecialchars($first . $second);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Usuarios | Treyak</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/sideBar.css">
  <link rel="stylesheet" href="css/HomeContenido.css">
  <link rel="stylesheet" href="css/FooterStyle.css">
  <link rel="stylesheet" href="css/UsuariosStyle.css">
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <div class="sidebar">
    <div class="logo_content">
      <div class="logo">
        <i class='bx bx-joystick-alt'></i>
        <div class="logo_name">Treyak</div>
      </div>
      <i class='bx bx-menu' id="btn"></i>
    </div>

    <ul>
      <li>
        <i class='bx bx-search'></i>
        <input type="text" placeholder="Search..." name="" value="">
        <span class="tooltipSearch">Search</span>
      </li>
      <div class="divider"></div>
      <li>
        <a href="Inicio.php">
          <i class='bx bxs-home-smile'></i>
          <span class="links_name">Inicio</span>
        </a>
        <span class="tooltip">Inicio</span>
      </li>
      <li>
        <a href="Usuarios.php">
          <i class='bx bxs-user'></i>
          <span class="links_name">Usuarios</span>
        </a>
        <span class="tooltip">Usuarios</span>
      </li>
      <li>
        <a href="Mensajes.php">
          <i class='bx bx-conversation'></i>
          <span class="links_name">Mensajes</span>
        </a>
        <span class="tooltip">Mensajes</span>
      </li>
      <li>
        <a href="Foro.php">
          <i class='bx bxs-folder-open'></i>
          <span class="links_name">Foro</span>
        </a>
        <span class="tooltip">Foro</span>
      </li>
      <li>
        <a href="Configuracion.php">
          <i class='bx bxs-cog'></i>
          <span class="links_name">Configuración</span>
        </a>
        <span class="tooltip">Configuración</span>
      </li>
      <li>
        <a href="Ayuda.php">
          <i class='bx bxs-help-circle'></i>
          <span class="links_name">Ayuda</span>
        </a>
        <span class="tooltip">Ayuda</span>
      </li>
    </ul>

    <div class="perfil_contenido">
      <div class="perfil">
        <div class="perfil_detalles">
          <img src="images/mewtwo-inspired-avatar.png" alt="">
          <div class="name_job">
            <div class="name"><?php echo htmlspecialchars($sessionName); ?></div>
            <div class="job">Sesión activa</div>
            <div class="log_out"></div>
          </div>
        </div>
        <div>
          <a href="php/cerrar_sesion.php"><i class='bx bx-log-out' id="log_out"></i></a>
        </div>
      </div>
    </div>
  </div>

  <main class="home_contenido">
    <section class="users-page">
      <header class="users-header">
        <div>
          <p class="section-kicker">Administracion</p>
          <h1>Usuarios</h1>
        </div>
        <div class="users-summary" aria-label="Resumen de usuarios">
          <div>
            <strong><?php echo $totalUsuarios; ?></strong>
            <span>Total</span>
          </div>
          <div>
            <strong><?php echo $otrosUsuarios; ?></strong>
            <span>Gestionables</span>
          </div>
        </div>
      </header>

      <?php if ($statusMessage): ?>
        <div class="status-message <?php echo $statusMessage[0]; ?>" role="status">
          <i class='bx <?php echo $statusMessage[0] === 'success' ? 'bx-check-circle' : 'bx-error-circle'; ?>'></i>
          <span><?php echo htmlspecialchars($statusMessage[1]); ?></span>
        </div>
      <?php endif; ?>

      <section class="users-toolbar" aria-label="Herramientas de usuarios">
        <label class="users-search" for="userSearch">
          <i class='bx bx-search'></i>
          <input type="search" id="userSearch" placeholder="Buscar por nombre, usuario o correo">
        </label>
        <button type="button" class="clear-search" id="clearUserSearch" title="Limpiar búsqueda">
          <i class='bx bx-x'></i>
        </button>
      </section>

      <section class="users-list" id="usersList">
        <?php if (empty($usuarios)): ?>
          <div class="empty-users">
            <i class='bx bx-user-x'></i>
            <p>No hay usuarios registrados.</p>
          </div>
        <?php endif; ?>

        <?php foreach ($usuarios as $usuario): ?>
          <?php $isCurrentUser = intval($usuario['id']) === $usuario_actual_id; ?>
          <article
            class="user-row <?php echo $isCurrentUser ? 'is-current-user' : ''; ?>"
            data-search="<?php echo htmlspecialchars(strtolower($usuario['nombreCompleto'] . ' ' . $usuario['usuario'] . ' ' . $usuario['correoElectronico'])); ?>">
            <div class="user-main">
            <div class="user-avatar" aria-hidden="true">
                <img src="images/mewtwo-inspired-avatar.png" alt="">
            </div>
              <div class="user-copy">
                <div class="user-title-line">
                  <h2><?php echo htmlspecialchars($usuario['nombreCompleto']); ?></h2>
                  <?php if ($isCurrentUser): ?>
                    <span class="user-badge">Tu cuenta</span>
                  <?php endif; ?>
                </div>
                <p>@<?php echo htmlspecialchars($usuario['usuario']); ?></p>
              </div>
            </div>

            <div class="user-meta">
              <span title="Correo electrónico">
                <i class='bx bx-envelope'></i>
                <?php echo htmlspecialchars($usuario['correoElectronico']); ?>
              </span>
              <span title="Identificador interno">
                <i class='bx bx-id-card'></i>
                ID <?php echo htmlspecialchars($usuario['id']); ?>
              </span>
            </div>

            <div class="user-actions">
              <button
                type="button"
                class="btn-action btn-edit js-open-edit-modal"
                title="Modificar usuario"
                data-id="<?php echo intval($usuario['id']); ?>"
                data-nombre="<?php echo htmlspecialchars($usuario['nombreCompleto'], ENT_QUOTES); ?>"
                data-usuario="<?php echo htmlspecialchars($usuario['usuario'], ENT_QUOTES); ?>"
                data-correo="<?php echo htmlspecialchars($usuario['correoElectronico'], ENT_QUOTES); ?>">
                <i class='bx bx-edit'></i>
                <span>Modificar</span>
              </button>

              <?php if ($isCurrentUser): ?>
                <button type="button" class="btn-action btn-delete is-disabled" disabled title="No puedes eliminar tu propia cuenta">
                  <i class='bx bx-lock-alt'></i>
                  <span>Eliminar</span>
                </button>
              <?php else: ?>
                <form action="php/eliminarUsuario.php" method="post" class="delete-user-form">
                  <input type="hidden" name="id" value="<?php echo intval($usuario['id']); ?>">
                  <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_delete_user']); ?>">
                  <button type="submit" class="btn-action btn-delete" title="Eliminar usuario">
                    <i class='bx bx-trash'></i>
                    <span>Eliminar</span>
                  </button>
                </form>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </section>

      <div class="empty-users is-hidden" id="emptySearchState">
        <i class='bx bx-search-alt'></i>
        <p>No encontramos usuarios con esa búsqueda.</p>
      </div>

      <div class="user-modal" id="editUserModal" aria-hidden="true">
        <div class="user-modal__backdrop" data-close-edit-modal></div>
        <section class="user-modal__panel" role="dialog" aria-modal="true" aria-labelledby="editUserModalTitle">
          <header class="user-modal__header">
            <div>
              <p class="section-kicker">Edición rápida</p>
              <h2 id="editUserModalTitle">Modificar usuario</h2>
            </div>
            <button type="button" class="user-modal__close" data-close-edit-modal title="Cerrar">
              <i class='bx bx-x'></i>
            </button>
          </header>

          <form action="editarUsuario.php" method="post" class="user-modal__form" id="editUserForm">
            <input type="hidden" name="id" id="editUserId">
            <input type="hidden" name="enviar" value="1">

            <label class="modal-field" for="editUserName">
              <span>Nombre completo</span>
              <input type="text" name="nombre" id="editUserName" required>
            </label>

            <label class="modal-field" for="editUserUsername">
              <span>Nombre de usuario</span>
              <input type="text" name="usuario" id="editUserUsername" required>
            </label>

            <label class="modal-field" for="editUserEmail">
              <span>Correo electrónico</span>
              <input type="email" name="correo" id="editUserEmail" required>
            </label>

            <div class="user-modal__actions">
              <button type="button" class="btn-modal-secondary" data-close-edit-modal>Cancelar</button>
              <button type="submit" class="btn-modal-primary">
                <i class='bx bx-save'></i>
                Guardar cambios
              </button>
            </div>
          </form>
        </section>
      </div>
    </section>

    <footer class="user-footer">
      <div class="footer-content">
        <div class="footer-links">
          <a href="#" class="footer-link">Terminos</a>
          <a href="#" class="footer-link">Privacidad</a>
          <a href="#" class="footer-link">Contacto</a>
        </div>
        <p class="footer-copyright">© 2026 Treyak. Todos los derechos reservados.</p>
      </div>
    </footer>
  </main>

  <script>
    const btn = document.querySelector("#btn");
    const sidebar = document.querySelector(".sidebar");
    const searchBtn = document.querySelector(".bx-search");

    btn.onclick = function() {
      sidebar.classList.toggle("active");
    };

    searchBtn.onclick = function() {
      sidebar.classList.toggle("active");
    };

    const userSearch = document.getElementById('userSearch');
    const clearUserSearch = document.getElementById('clearUserSearch');
    const userRows = Array.from(document.querySelectorAll('.user-row'));
    const emptySearchState = document.getElementById('emptySearchState');

    const filterUsers = () => {
      const term = userSearch.value.trim().toLowerCase();
      let visibleCount = 0;

      userRows.forEach((row) => {
        const matches = row.dataset.search.includes(term);
        row.classList.toggle('is-hidden', !matches);
        if (matches) visibleCount++;
      });

      emptySearchState.classList.toggle('is-hidden', visibleCount !== 0 || term === '');
      clearUserSearch.classList.toggle('is-visible', term !== '');
    };

    userSearch.addEventListener('input', filterUsers);
    clearUserSearch.addEventListener('click', () => {
      userSearch.value = '';
      filterUsers();
      userSearch.focus();
    });

    const editUserModal = document.getElementById('editUserModal');
    const editUserId = document.getElementById('editUserId');
    const editUserName = document.getElementById('editUserName');
    const editUserUsername = document.getElementById('editUserUsername');
    const editUserEmail = document.getElementById('editUserEmail');

    const openEditModal = (button) => {
      editUserId.value = button.dataset.id || '';
      editUserName.value = button.dataset.nombre || '';
      editUserUsername.value = button.dataset.usuario || '';
      editUserEmail.value = button.dataset.correo || '';
      editUserModal.classList.add('is-open');
      editUserModal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-open');
      editUserName.focus();
    };

    const closeEditModal = () => {
      editUserModal.classList.remove('is-open');
      editUserModal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-open');
    };

    document.querySelectorAll('.js-open-edit-modal').forEach((button) => {
      button.addEventListener('click', () => openEditModal(button));
    });

    document.querySelectorAll('[data-close-edit-modal]').forEach((button) => {
      button.addEventListener('click', closeEditModal);
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && editUserModal.classList.contains('is-open')) {
        closeEditModal();
      }
    });

    document.querySelectorAll('.delete-user-form').forEach((form) => {
      form.addEventListener('submit', (event) => {
        const row = form.closest('.user-row');
        const name = row?.querySelector('h2')?.textContent?.trim() || 'este usuario';
        const confirmed = confirm(`Eliminar a ${name}? Esta acción no se puede deshacer.`);
        if (!confirmed) event.preventDefault();
      });
    });
  </script>
  <script src="js/sidebarNotifications.js"></script>
</body>

</html>
