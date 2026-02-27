TREYAK - Plataforma de Gestión y Comunicación
===============================================

Treyak es una aplicación web desarrollada en PHP que proporciona un espacio privado para usuarios autenticados, con funcionalidades de gestión de usuarios, mensajería instantánea, foro de discusión, centro de ayuda y configuración personalizable. Ideal para comunidades o equipos que necesitan un entorno centralizado de comunicación y administración.

CARACTERÍSTICAS PRINCIPALES
----------------------------
- Sistema de autenticación seguro: Registro e inicio de sesión con protección CSRF y manejo de sesiones.
- Panel de inicio personalizado: Resumen de cuenta, actividad reciente, progreso, cursos y notificaciones.
- Gestión de usuarios: Listado, edición y eliminación de usuarios (solo administradores o según permisos).
- Mensajería en tiempo real: Chat entre usuarios con envío de mensajes de texto y documentos.
- Foro de discusión: Creación de hilos, respuestas, fijación y cierre de temas.
- Centro de ayuda: Preguntas frecuentes, guías y formulario de contacto.
- Configuración de cuenta: Actualización de información personal, cambio de contraseña, preferencias de notificaciones y foto de perfil.
- Diseño responsive: Interfaz adaptable a dispositivos móviles y de escritorio.

TECNOLOGÍAS UTILIZADAS
-----------------------
- Backend: PHP 7/8
- Base de datos: MySQL
- Frontend: HTML5, CSS3, JavaScript (nativo)
- Íconos: Boxicons
- Seguridad: Sesiones, CSRF tokens, validación de entrada, prevención de XSS con htmlspecialchars

REQUISITOS PREVIOS
-------------------
- Servidor web (Apache / Nginx) con PHP 7.4 o superior
- MySQL 5.7 o superior
- Extensión mysqli habilitada en PHP
- Navegador web moderno

INSTALACIÓN Y CONFIGURACIÓN
----------------------------

1. Clonar el repositorio
   git clone https://github.com/tuusuario/treyak.git
   cd treyak

2. Configurar la base de datos
   - Crear una base de datos en MySQL (ejemplo: treyak_db).
   - Importar el archivo database.sql (si se proporciona) para crear las tablas necesarias.
   - Si no existe, deberás crear las tablas manualmente según la estructura de los archivos PHP.

3. Configurar la conexión
   Editar el archivo php/pagina_general/conexion_be.php con tus credenciales de base de datos:
   <?php
   $conexion = mysqli_connect("localhost", "usuario", "contraseña", "treyak_db");
   if (!$conexion) {
       die("Error de conexión: " . mysqli_connect_error());
   }
   ?>

4. Configurar el servidor web
   - Asegúrate de que el servidor apunte a la carpeta raíz del proyecto.
   - Habilita el módulo de reescritura si usas Apache (para URLs amigables, aunque no es obligatorio).

5. Establecer permisos
   - La carpeta uploads/ debe tener permisos de escritura para que los usuarios puedan subir archivos.
   chmod 755 uploads

6. Acceder a la aplicación
   - Abre el navegador y ve a http://localhost/treyak/
   - Regístrate como nuevo usuario o inicia sesión si ya tienes una cuenta.

   Nota: El primer usuario registrado no obtiene automáticamente permisos de administrador. Puedes asignar el rol directamente en la base de datos si es necesario.

ESTRUCTURA DEL PROYECTO
------------------------
treyak/
│
├── css/                      # Hojas de estilo organizadas por módulos
│   ├── pagina_general/       # Estilos comunes (sidebar, footer)
│   ├── index/                # Estilos de la página de inicio de sesión
│   ├── inicio/               # Estilos del panel de inicio
│   ├── Usuarios/             # Estilos para gestión de usuarios
│   └── ...                   # Otros estilos específicos
│
├── js/                       # Scripts JavaScript
│   └── index/                # Animaciones de la página de login
│
├── images/                   # Imágenes (avatares, etc.)
│   └── index/                # Avatares por defecto
│
├── php/                       # Lógica del backend
│   ├── index/                 # Procesos de login y registro
│   ├── pagina_general/        # Conexión, cierre de sesión
│   ├── Usuarios/              # Eliminar usuarios
│   └── ...                    # Otros procesos (mensajes, foro, etc.)
│
├── uploads/                   # Archivos subidos por usuarios
│
├── index.php                  # Página de inicio de sesión / registro
├── Inicio.php                 # Panel principal después del login
├── Usuarios.php               # Lista de usuarios
├── editarUsuario.php          # Editar un usuario específico
├── Mensajes.php               # Sistema de mensajería
├── Foro.php                   # Foro de discusión
├── verHilo.php                # Ver un hilo del foro con respuestas
├── Configuracion.php          # Configuración de la cuenta
├── Ayuda.php                  # Centro de ayuda
└── README.md                  # Este archivo (originalmente en markdown)

CAPTURAS DE PANTALLA
---------------------
(Agrega aquí imágenes si deseas mostrar la interfaz)

USO
----

Acceso
- Regístrate completando el formulario y eligiendo un avatar.
- Inicia sesión con usuario y contraseña.

Navegación
- El sidebar lateral permite acceder a todas las secciones.
- Desde Inicio puedes ver un resumen personalizado.
- En Usuarios puedes administrar otros usuarios (editar/eliminar).
- Mensajes te permite chatear en tiempo real con otros usuarios y enviar archivos.
- El Foro es un espacio de discusión donde puedes crear hilos y responder.
- En Configuración puedes modificar tus datos, contraseña, notificaciones y foto de perfil.
- Ayuda contiene preguntas frecuentes y un formulario de contacto.

Mensajería
- Para enviar un mensaje, selecciona un contacto de la lista de conversaciones.
- Escribe tu mensaje o adjunta un archivo (imagen, documento, etc.).
- Los mensajes se muestran con marcas de tiempo y estado de enviado.

Foro
- Crea un nuevo hilo con título y contenido.
- Responde a hilos existentes.
- Los hilos pueden ser fijados o cerrados (funcionalidad de administrador).

CONTRIBUCIONES
---------------
Las contribuciones son bienvenidas. Si deseas mejorar el proyecto:

1. Haz un fork del repositorio.
2. Crea una rama con tu función: git checkout -b feature/nueva-funcion
3. Haz commit de tus cambios: git commit -m 'Agrega nueva función'
4. Haz push a la rama: git push origin feature/nueva-funcion
5. Abre un Pull Request.

LICENCIA
---------
Este proyecto está bajo la licencia MIT. Consulta el archivo LICENSE para más detalles.

--- 
Desarrollado con ❤️ por Abel Arriagada y colaboradores.
