<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  echo '
      <script>
        alert("Por favor debes iniciar sesion");
        window.location = "index.php";
      </script>
      ';
  session_destroy();
  die();
}

include("php/conexion_be.php");

$usuario_actual_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;
$sql = "SELECT * FROM usuario WHERE id != $usuario_actual_id";
$resultado = mysqli_query($conexion, $sql);
$usuarios = [];

if ($resultado) {
  while ($fila = mysqli_fetch_assoc($resultado)) {
    $usuarios[] = $fila;
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Lista de usuarios | Treyak</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/sideBar.css">
  <link rel="stylesheet" href="css/HomeContenido.css">
  <link rel="stylesheet" href="css/FooterStyle.css">
  <link rel="stylesheet" href="css/UsuariosStyle.css">
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
  <script>
    function confirmar() {
      return confirm('Seguro que deseas eliminar este usuario? Esta accion no se puede deshacer.');
    }
  </script>
</head>

<body>
  <div class="sidebar">
    <div class="logo_content">
      <div class="logo">
        <i class="bx bx-joystick-alt"></i>
        <div class="logo_name">Treyak</div>
      </div>
      <i class="bx bx-menu" id="btn"></i>
    </div>

    <ul>
      <li>
        <i class="bx bx-search"></i>
        <input type="text" placeholder="Search...">
        <span class="tooltipSearch">Search</span>
      </li>
      <div class="divider"></div>
      <li><a href="Inicio.php"><i class="bx bxs-home-smile"></i><span class="links_name">Inicio</span></a><span class="tooltip">Inicio</span></li>
      <li><a href="Usuarios.php"><i class="bx bxs-user"></i><span class="links_name">User</span></a><span class="tooltip">Usuarios</span></li>
      <li><a href="Mensajes.php"><i class="bx bx-conversation"></i><span class="links_name">Mensajes</span></a><span class="tooltip">Mensaje</span></li>
      <li><a href="Foro.php"><i class="bx bxs-folder-open"></i><span class="links_name">Archivos</span></a><span class="tooltip">Foro</span></li>
      <li><a href="Configuracion.php"><i class="bx bxs-cog"></i><span class="links_name">Configuracion</span></a><span class="tooltip">Configuracion</span></li>
      <li><a href="Ayuda.php"><i class="bx bxs-help-circle"></i><span class="links_name">Ayuda</span></a><span class="tooltip">Ayuda</span></li>
    </ul>

    <div class="perfil_contenido">
      <div class="perfil">
        <div class="perfil_detalles">
          <img src="images/perfil.webp" alt="">
          <div class="name_job">
            <div class="name">Abel Arriagada</div>
            <div class="job">Programador</div>
            <div class="log_out"></div>
          </div>
        </div>
        <div>
          <a href="php/cerrar_sesion.php"><i class="bx bx-log-out" id="log_out"></i></a>
        </div>
      </div>
    </div>
  </div>

  <div class="home_contenido">
    <section class="users-page">
      <header class="users-header">
        <div>
          <p class="section-kicker">Administracion</p>
          <h1>Gestion de usuarios</h1>
        </div>
        <div class="users-summary">
          <div>
            <strong><?php echo count($usuarios); ?></strong>
            <span>usuarios visibles</span>
          </div>
          <div>
            <strong><?php echo htmlspecialchars((string) $usuario_actual_id); ?></strong>
            <span>tu ID</span>
          </div>
        </div>
      </header>

      <div class="users-toolbar">
        <label class="users-search" for="userSearch">
          <i class="bx bx-search"></i>
          <input type="search" id="userSearch" placeholder="Buscar por nombre, usuario o correo">
        </label>
        <button class="clear-search" id="clearUserSearch" type="button" title="Limpiar busqueda">
          <i class="bx bx-x"></i>
        </button>
      </div>

      <div class="users-list" id="usersList">
        <?php foreach ($usuarios as $fila): ?>
          <?php
            $id = (int) ($fila['id'] ?? 0);
            $nombre = htmlspecialchars($fila['nombreCompleto'] ?? '', ENT_QUOTES, 'UTF-8');
            $usuario = htmlspecialchars($fila['usuario'] ?? '', ENT_QUOTES, 'UTF-8');
            $correo = htmlspecialchars($fila['correoElectronico'] ?? '', ENT_QUOTES, 'UTF-8');
            $searchText = htmlspecialchars(strtolower(($fila['nombreCompleto'] ?? '') . ' ' . ($fila['usuario'] ?? '') . ' ' . ($fila['correoElectronico'] ?? '')), ENT_QUOTES, 'UTF-8');
          ?>
          <article class="user-row" data-search="<?php echo $searchText; ?>">
            <div class="user-main">
              <span class="user-avatar" aria-hidden="true"><img src="images/perfil.webp" alt=""></span>
              <div class="user-copy">
                <div class="user-title-line">
                  <h2><?php echo $nombre; ?></h2>
                  <span class="user-badge">ID <?php echo $id; ?></span>
                </div>
                <p>@<?php echo $usuario; ?></p>
              </div>
            </div>

            <div class="user-meta">
              <span><i class="bx bx-envelope"></i><?php echo $correo; ?></span>
              <span><i class="bx bx-user"></i><?php echo $usuario; ?></span>
            </div>

            <div class="user-actions">
              <button
                type="button"
                class="btn-action btn-edit js-open-edit-modal"
                data-id="<?php echo $id; ?>"
                data-nombre="<?php echo $nombre; ?>"
                data-usuario="<?php echo $usuario; ?>"
                data-correo="<?php echo $correo; ?>">
                <i class="bx bx-edit"></i> Editar
              </button>
              <a href="php/eliminarUsuario.php?id=<?php echo $id; ?>" class="btn-action btn-delete" onclick="return confirmar()">
                <i class="bx bx-trash"></i> Eliminar
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="empty-users is-hidden" id="emptyUsers">
        <i class="bx bx-user-x"></i>
        <p>No se encontraron usuarios con esa busqueda.</p>
      </div>

      <div class="user-modal" id="editUserModal" aria-hidden="true">
        <div class="user-modal__backdrop" data-close-edit-modal></div>
        <section class="user-modal__panel" role="dialog" aria-modal="true" aria-labelledby="editUserModalTitle">
          <header class="user-modal__header">
            <div>
              <p class="section-kicker">Edicion rapida</p>
              <h2 id="editUserModalTitle">Modificar usuario</h2>
            </div>
            <button type="button" class="user-modal__close" data-close-edit-modal title="Cerrar">
              <i class="bx bx-x"></i>
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
              <span>Correo electronico</span>
              <input type="email" name="correo" id="editUserEmail" required>
            </label>

            <div class="user-modal__actions">
              <button type="button" class="btn-modal-secondary" data-close-edit-modal>Cancelar</button>
              <button type="submit" class="btn-modal-primary"><i class="bx bx-save"></i> Guardar cambios</button>
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
        <p class="footer-copyright">2023 NombreApp. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>

  <script>
    let btn = document.querySelector("#btn");
    let sidebar = document.querySelector(".sidebar");
    let searchBtn = document.querySelector(".bx-search");
    const userSearch = document.getElementById("userSearch");
    const clearUserSearch = document.getElementById("clearUserSearch");
    const userRows = Array.from(document.querySelectorAll(".user-row"));
    const emptyUsers = document.getElementById("emptyUsers");

    btn.onclick = function() {
      sidebar.classList.toggle("active");
    };

    searchBtn.onclick = function() {
      sidebar.classList.toggle("active");
    };

    const filterUsers = () => {
      const value = (userSearch?.value || "").trim().toLowerCase();
      let visibleCount = 0;

      userRows.forEach((row) => {
        const isVisible = !value || row.dataset.search.includes(value);
        row.classList.toggle("is-hidden", !isVisible);
        if (isVisible) visibleCount += 1;
      });

      clearUserSearch?.classList.toggle("is-visible", value.length > 0);
      emptyUsers?.classList.toggle("is-hidden", visibleCount > 0);
    };

    userSearch?.addEventListener("input", filterUsers);
    clearUserSearch?.addEventListener("click", () => {
      userSearch.value = "";
      filterUsers();
      userSearch.focus();
    });

    const editUserModal = document.getElementById("editUserModal");
    const editUserId = document.getElementById("editUserId");
    const editUserName = document.getElementById("editUserName");
    const editUserUsername = document.getElementById("editUserUsername");
    const editUserEmail = document.getElementById("editUserEmail");

    const openEditModal = (button) => {
      editUserId.value = button.dataset.id || "";
      editUserName.value = button.dataset.nombre || "";
      editUserUsername.value = button.dataset.usuario || "";
      editUserEmail.value = button.dataset.correo || "";
      editUserModal.classList.add("is-open");
      editUserModal.setAttribute("aria-hidden", "false");
      document.body.classList.add("modal-open");
      editUserName.focus();
    };

    const closeEditModal = () => {
      editUserModal.classList.remove("is-open");
      editUserModal.setAttribute("aria-hidden", "true");
      document.body.classList.remove("modal-open");
    };

    document.querySelectorAll(".js-open-edit-modal").forEach((button) => {
      button.addEventListener("click", () => openEditModal(button));
    });

    document.querySelectorAll("[data-close-edit-modal]").forEach((button) => {
      button.addEventListener("click", closeEditModal);
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && editUserModal.classList.contains("is-open")) {
        closeEditModal();
      }
    });
  </script>
</body>

</html>
