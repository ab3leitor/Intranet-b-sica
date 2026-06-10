<?php
session_start();
require 'conexion_be.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message_count' => 0]);
    exit();
}

$userId = intval($_SESSION['id']);

try {
    $columnResult = $conexion->query("SHOW COLUMNS FROM messages LIKE 'is_read'");
    if ($columnResult && $columnResult->num_rows === 0) {
        $conexion->query("ALTER TABLE messages ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0 AFTER is_document");
    }
} catch (Throwable $e) {
    // Si la columna no se puede crear, caemos a conteo general de mensajes recibidos.
}

$count = 0;

try {
    $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM messages WHERE receiver_id = ? AND COALESCE(is_read, 0) = 0");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = intval($row['total'] ?? 0);
    $stmt->close();
} catch (Throwable $e) {
    $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM messages WHERE receiver_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = intval($row['total'] ?? 0);
    $stmt->close();
}

echo json_encode([
    'success' => true,
    'message_count' => $count
]);

$conexion->close();
?>
