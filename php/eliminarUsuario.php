<?php
session_start();
include("conexion_be.php");

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Usuarios.php?status=delete_invalid");
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$csrf = $_POST['csrf'] ?? '';
$currentUserId = intval($_SESSION['id']);
$sessionToken = $_SESSION['csrf_delete_user'] ?? '';

if ($id <= 0 || $id === $currentUserId || $csrf === '' || !hash_equals($sessionToken, $csrf)) {
    header("Location: ../Usuarios.php?status=delete_invalid");
    exit();
}

$stmt = $conexion->prepare("DELETE FROM usuario WHERE id = ?");
$stmt->bind_param("i", $id);
$resultado = $stmt->execute();
$stmt->close();
$conexion->close();

if ($resultado) {
    header("Location: ../Usuarios.php?status=deleted");
} else {
    header("Location: ../Usuarios.php?status=delete_error");
}
exit();
?>
