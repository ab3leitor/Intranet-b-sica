# TREYAK - Plataforma Web de Gestión y Comunicación

TREYAK es una aplicación web desarrollada en **PHP y MySQL** que funciona como una plataforma privada para usuarios registrados. El sistema permite gestionar cuentas, comunicarse mediante mensajería interna, participar en un foro de discusión y administrar información personal desde un panel centralizado.

Este proyecto fue creado como práctica de desarrollo web full stack, integrando frontend, backend, base de datos y medidas básicas de seguridad.

## Características principales

* Registro e inicio de sesión de usuarios.
* Manejo de sesiones en PHP.
* Protección mediante tokens CSRF.
* Panel principal para usuarios autenticados.
* Gestión de usuarios.
* Edición y eliminación de cuentas.
* Sistema de mensajería entre usuarios.
* Foro de discusión con hilos y respuestas.
* Centro de ayuda.
* Configuración de perfil.
* Cambio de contraseña.
* Subida de archivos e imágenes.
* Diseño responsive adaptable a escritorio y móviles.

## Tecnologías utilizadas

* **PHP**
* **MySQL**
* **HTML5**
* **CSS3**
* **JavaScript**
* **Boxicons**
* **XAMPP / Apache**
* **phpMyAdmin**

## Estructura del proyecto

```bash
MyPage/
├── css/
│   ├── index/
│   ├── inicio/
│   ├── Usuarios/
│   └── pagina_general/
├── js/
│   └── index/
├── images/
│   └── index/
├── php/
│   ├── index/
│   ├── pagina_general/
│   └── Usuarios/
├── uploads/
├── index.php
├── Inicio.php
├── Usuarios.php
├── editarUsuario.php
├── Mensajes.php
├── Foro.php
├── verHilo.php
├── Configuracion.php
└── Ayuda.php
```

## Requisitos previos

Para ejecutar el proyecto necesitas tener instalado:

* PHP 7.4 o superior.
* MySQL 5.7 o superior.
* Servidor local como XAMPP, WAMP o Laragon.
* Navegador web moderno.
* Extensión `mysqli` habilitada en PHP.

## Instalación

1. Clona el repositorio:

```bash
git clone https://github.com/ab3leitor/MyPage.git
```

2. Mueve la carpeta del proyecto a la ruta de tu servidor local.

En XAMPP, por ejemplo:

```bash
C:/xampp/htdocs/MyPage
```

3. Inicia Apache y MySQL desde XAMPP.

4. Crea una base de datos en phpMyAdmin.

Ejemplo:

```sql
CREATE DATABASE treyak_db;
```

5. Configura la conexión a la base de datos en el archivo correspondiente del proyecto:

```php
$conexion = mysqli_connect("localhost", "root", "", "treyak_db");
```

6. Accede al proyecto desde el navegador:

```bash
http://localhost/MyPage/
```

## Uso

1. Registra una nueva cuenta de usuario.
2. Inicia sesión con tus credenciales.
3. Accede al panel principal.
4. Navega por las secciones disponibles:

   * Inicio
   * Usuarios
   * Mensajes
   * Foro
   * Configuración
   * Ayuda

## Funcionalidades destacadas

### Autenticación

El sistema permite registrar usuarios e iniciar sesión mediante formularios conectados a una base de datos MySQL.

### Gestión de usuarios

Incluye una sección para visualizar, editar y eliminar usuarios registrados.

### Mensajería

Permite la comunicación entre usuarios mediante mensajes internos y envío de archivos.

### Foro

Los usuarios pueden crear hilos de discusión, responder publicaciones y participar en conversaciones dentro de la plataforma.

### Configuración de cuenta

Cada usuario puede modificar datos personales, contraseña, preferencias y foto de perfil.

## Seguridad

El proyecto implementa medidas básicas de seguridad como:

* Manejo de sesiones.
* Validación de formularios.
* Protección CSRF.
* Sanitización de datos.
* Prevención básica de XSS mediante `htmlspecialchars`.

## Mejoras futuras

* Agregar roles de usuario más completos.
* Mejorar el panel de administración.
* Implementar recuperación de contraseña por correo.
* Agregar paginación en usuarios, mensajes y foro.
* Mejorar validaciones del lado servidor.
* Implementar notificaciones en tiempo real.
* Añadir documentación de la base de datos.
* Crear archivo SQL de instalación automática.
* Mejorar la estructura MVC del proyecto.
* Agregar modo oscuro/claro.

## Estado del proyecto

Proyecto en desarrollo y mejora continua.

Actualmente funciona como una plataforma web educativa para practicar conceptos de desarrollo backend, frontend, autenticación y manejo de base de datos.

## Autor

Desarrollado por **Abel Arriagada**.

* GitHub: [@ab3leitor](https://github.com/ab3leitor)

## Licencia

Este proyecto se distribuye bajo licencia MIT.
Puedes usarlo, modificarlo y adaptarlo con fines educativos.
