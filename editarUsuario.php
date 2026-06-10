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

if (isset($_POST['enviar'])) {
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $correo = trim($_POST['correo'] ?? '');

    if ($id <= 0 || $nombre === '' || $usuario === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
      header("Location: Usuarios.php?status=edit_invalid");
      exit();
    }

    $duplicateStmt = $conexion->prepare("SELECT id FROM usuario WHERE (usuario = ? OR correoElectronico = ?) AND id != ? LIMIT 1");
    $duplicateStmt->bind_param("ssi", $usuario, $correo, $id);
    $duplicateStmt->execute();
    $duplicateResult = $duplicateStmt->get_result();

    if ($duplicateResult->num_rows > 0) {
      $duplicateStmt->close();
      mysqli_close($conexion);
      header("Location: Usuarios.php?status=edit_duplicate");
      exit();
    }

    $duplicateStmt->close();

    $stmt = $conexion->prepare("UPDATE usuario SET nombreCompleto = ?, usuario = ?, correoElectronico = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nombre, $usuario, $correo, $id);
    $resultado = $stmt->execute();
    
    if ($resultado) {
      header("Location: Usuarios.php?status=updated");
    } else {
      header("Location: Usuarios.php?status=edit_error");
    }
    $stmt->close();
    mysqli_close($conexion);
    exit();
} else {
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
      header("Location: Usuarios.php?status=edit_invalid");
      exit();
    }

    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $fila = mysqli_fetch_assoc($resultado);
    if (!$fila) {
      header("Location: Usuarios.php?status=not_found");
      exit();
    }

    $nombre = $fila["nombreCompleto"];
    $usuario = $fila["usuario"];
    $correo = $fila["correoElectronico"];

    $stmt->close();
    mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Editar usuario | Treyak</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Enlaces CSS -->
  <link rel="stylesheet" href="css/sideBar.css">
  <link rel="stylesheet" href="css/HomeContenido.css">
  <link rel="stylesheet" href="css/FooterStyle.css">
  <link rel="stylesheet" href="css/EditarUsuario.css">
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <!-- Sidebar (se mantiene igual) -->
  <div class="sidebar">
    <!--Div que contiene la parte del logo-->
    <div class="logo_content">
      <div class="logo">
        <!--Icono de la empresa-->
        <i class='bx bx-joystick-alt'></i>
        <div class="logo_name">Treyak</div>
      </div>
      <!--Icono del menu-->
      <i class='bx bx-menu' id="btn"></i>
    </div>
    <!--Lista no ordenada-->
    <ul>
      <!--Items de la Lista-->
      <li>
        <!--Buscar-->
        <!--Icono del item-->
        <i class='bx bx-search'></i>
        <!--Icono del item-->
        <input type="text" placeholder="Search..." name="" value="">
        <span class="tooltipSearch">Search</span>
      </li>
      <div class="divider"></div>
      <!--Items de la Lista-->
      <li>
        <!--Inicio-->
        <a href="Inicio.php">
          <!--Icono del item-->
          <i class='bx bxs-home-smile'></i>
          <!--Resalta y ocupa un espacio segun el texto-->
          <span class="links_name">Inicio</span>
        </a>
        <span class="tooltip">Inicio</span>
      </li>
      <li>
        <!--User-->
        <a href="Usuarios.php">
          <!--Icono del item-->
          <i class='bx bxs-user'></i>
          <!--Resalta y ocupa un espacio segun el texto-->
          <span class="links_name">Usuarios</span>
        </a>
        <span class="tooltip">Usuarios</span>
      </li>
      <!--Mensajes-->
      <li>
        <!--Redirección a otra página-->
        <a href="Mensajes.php">
          <!--Icono del item-->
          <i class='bx bx-conversation'></i>
          <!--Resalta y ocupa un espacio segun el texto-->
          <span class="links_name">Mensajes</span>
        </a>
        <span class="tooltip">Mensaje</span>
      </li>
      <!--Administrador de archivos-->
      <li>
        <!--Redirección a otra página-->
        <a href="Foro.php">
          <!--Icono del item-->
          <i class='bx bxs-folder-open'></i>
          <!--Resalta y ocupa un espacio segun el texto-->
          <span class="links_name">Foro</span>
        </a>
        <span class="tooltip">Foro</span>
      </li>
      <!--Items de la Lista-->
      <li>
        <!--Configuración-->
        <a href="Configuracion.php">
          <!--Icono del item-->
          <i class='bx bxs-cog'></i>
          <!--Resalta y ocupa un espacio segun el texto-->
          <span class="links_name">Configuración</span>
        </a>
        <span class="tooltip">Configuración</span>
      </li>
      <!--Items de la Lista-->
      <li>
        <!--Ayuda-->
        <a href="Ayuda.php">
          <!--Icono del item-->
          <i class='bx bxs-help-circle'></i>
          <!--Resalta y ocupa un espacio segun el texto-->
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
            <div class="name">Abel Arriagada</div>
            <div class="job">Programador</div>
            <div class="log_out"></div>
          </div>
        </div>
        <!--Icono del item-->
        <div>
          <a href="php/cerrar_sesion.php"><i class='bx bx-log-out' id="log_out"></i></a>

        </div>
      </div>
    </div>
  </div>

  <!-- Contenido principal -->
  <div class="home_contenido">
    <div class="Gestion">
    <h1>Editar Usuario</h1>
    </div>
    
    <div class="edit-form">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group">
          <label for="nombre">Nombre Completo:</label>
          <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="usuario">Nombre de Usuario:</label>
          <input type="text" name="usuario" id="usuario" value="<?php echo htmlspecialchars($usuario); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="correo">Correo Electrónico:</label>
          <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($correo); ?>" required>
        </div>
        
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        
        <div class="form-actions">
          <a href="Usuarios.php" class="btn btn-cancel">Cancelar</a>
          <button type="submit" name="enviar" class="btn btn-submit">Guardar Cambios</button>
        </div>
      </form>
    </div>
    <footer class="user-footer">
      <div class="footer-content">
        <div class="footer-links">
          <a href="#" class="footer-link">Términos</a>
          <a href="#" class="footer-link">Privacidad</a>
          <a href="#" class="footer-link">Contacto</a>
        </div>

        <div class="footer-social">
          <a href="https://www.facebook.com/abel.arriagadaurriola" class="social-icon" title="Facebook">
            <i class='bx bxl-facebook' style='color:#fffafa'></i>
          </a>
          <a href="#" class="social-icon" title="Twitter">
            <i class='bx bxl-twitter' style='color:#fffafa'></i>
          </a>
          <a href="https://www.instagram.com/abelardoahhaaha/" class="social-icon" title="Instagram">
            <i class='bx bxl-instagram' style='color:#fffafa'></i>
          </a>
          <a href="https://cl.linkedin.com/in/abel-arriagada-urriola-9aaa19287" class="social-icon" title="LinkedIn">
            <i class='bx bxl-linkedin' style='color:#fffafa'></i>
          </a>
          <a href="https://wa.me/<+56956025318>?text=<Hola muy buenas, vengo a saludar>" class="social-icon" title="Whatsapp">
            <i class='bx bxl-whatsapp' style='color:#fffafa'></i>
          </a>
        </div>

        <p class="footer-copyright">© 2026 Treyak. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>

  <script>
    // Script para el sidebar (se mantiene igual)
    let btn = document.querySelector("#btn");
    let sidebar = document.querySelector(".sidebar");
    let searchBtn = document.querySelector(".bx-search");

    btn.onclick = function() {
      sidebar.classList.toggle("active");
    }
    
    searchBtn.onclick = function() {
      sidebar.classList.toggle("active");
    }
  </script>
  <script src="js/sidebarNotifications.js"></script>
</body>
</html>
<?php } ?>
