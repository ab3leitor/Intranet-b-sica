<?php
session_start();
require 'conexion_be.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$senderId = intval($_SESSION['id']);
$receiverId = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
$content = trim($_POST['content'] ?? '');

if ($content === '' || $receiverId <= 0 || $receiverId === $senderId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit();
}

$userQuery = "SELECT id FROM usuario WHERE id = ? LIMIT 1";
$userStmt = $conexion->prepare($userQuery);
$userStmt->bind_param("i", $receiverId);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'El usuario de destino no existe']);
    exit();
}

$query = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param("iis", $senderId, $receiverId, $content);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Mensaje enviado']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje']);
}

$userStmt->close();
$stmt->close();
$conexion->close();
?>
