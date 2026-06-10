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
$contactId = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;

if ($contactId <= 0 || $contactId === $userId) {
    http_response_code(400);
    echo json_encode(['error' => 'Contacto no válido']);
    exit;
}

$query = "
    SELECT m.*,
           u1.usuario AS sender_name,
           u2.usuario AS receiver_name
    FROM messages m
    JOIN usuario u1 ON m.sender_id = u1.id
    JOIN usuario u2 ON m.receiver_id = u2.id
    WHERE (m.sender_id = ? AND m.receiver_id = ?)
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.created_at ASC
";

$stmt = $conexion->prepare($query);
$stmt->bind_param("iiii", $userId, $contactId, $contactId, $userId);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

try {
    $columnResult = $conexion->query("SHOW COLUMNS FROM messages LIKE 'is_read'");
    if ($columnResult && $columnResult->num_rows === 0) {
        $conexion->query("ALTER TABLE messages ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0 AFTER is_document");
    }

    $readStmt = $conexion->prepare("UPDATE messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ?");
    $readStmt->bind_param("ii", $userId, $contactId);
    $readStmt->execute();
    $readStmt->close();
} catch (Throwable $e) {
    error_log("No se pudieron marcar mensajes como leidos: " . $e->getMessage());
}

echo json_encode($messages);
$stmt->close();
$conexion->close();
?>
