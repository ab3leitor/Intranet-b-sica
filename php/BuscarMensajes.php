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
$searchTerm = trim($_GET['q'] ?? '');

if ($searchTerm === '') {
    echo json_encode([]);
    exit;
}

$likeTerm = "%$searchTerm%";

$query = "SELECT DISTINCT
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
             ORDER BY created_at DESC LIMIT 1) AS last_is_document
          FROM usuario u
          LEFT JOIN messages m ON (m.sender_id = u.id OR m.receiver_id = u.id)
          WHERE u.id != ?
            AND (
                u.usuario LIKE ?
                OR u.nombreCompleto LIKE ?
                OR (
                    ((m.sender_id = ? AND m.receiver_id = u.id)
                    OR (m.sender_id = u.id AND m.receiver_id = ?))
                    AND m.content LIKE ?
                )
            )
          ORDER BY u.usuario ASC";

$stmt = $conexion->prepare($query);
$stmt->bind_param(
    "iiiiissiis",
    $userId,
    $userId,
    $userId,
    $userId,
    $userId,
    $likeTerm,
    $likeTerm,
    $userId,
    $userId,
    $likeTerm
);
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
