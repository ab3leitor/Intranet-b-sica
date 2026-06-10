<?php
header('Content-Type: application/json');
require 'conexion_be.php';

session_start();
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$userId = intval($_SESSION['id']);

$query = "SELECT
    u.id AS user_id,
    u.usuario AS name,
    u.nombreCompleto AS full_name,
    (SELECT content FROM messages
     WHERE (sender_id = u.id AND receiver_id = ?)
        OR (sender_id = ? AND receiver_id = u.id)
     ORDER BY created_at DESC LIMIT 1) AS last_message,
    (SELECT is_document FROM messages
     WHERE (sender_id = u.id AND receiver_id = ?)
        OR (sender_id = ? AND receiver_id = u.id)
     ORDER BY created_at DESC LIMIT 1) AS last_is_document,
    (SELECT created_at FROM messages
     WHERE (sender_id = u.id AND receiver_id = ?)
        OR (sender_id = ? AND receiver_id = u.id)
     ORDER BY created_at DESC LIMIT 1) AS last_message_at
FROM usuario u
WHERE u.id != ?
ORDER BY last_message_at IS NULL, last_message_at DESC, u.usuario ASC";

$stmt = $conexion->prepare($query);
$stmt->bind_param("iiiiiii", $userId, $userId, $userId, $userId, $userId, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();

$conversations = [];
while ($row = $result->fetch_assoc()) {
    $conversations[] = $row;
}

echo json_encode($conversations);
$stmt->close();
$conexion->close();
?>
