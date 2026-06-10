<?php
session_start();
require 'conexion_be.php';

if (!isset($_SESSION['id'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

$documentId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$fileName = isset($_GET['file']) ? basename($_GET['file']) : '';
$userId = intval($_SESSION['id']);

if ($documentId <= 0 || $fileName === '') {
    header('HTTP/1.1 400 Bad Request');
    exit();
}

$query = "SELECT m.* FROM messages m WHERE m.id = ? AND (m.sender_id = ? OR m.receiver_id = ?) AND m.is_document = 1";
$stmt = $conexion->prepare($query);
$stmt->bind_param("iii", $documentId, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('HTTP/1.1 403 Forbidden');
    exit();
}

$documentData = $result->fetch_assoc();

try {
    $fileInfo = json_decode($documentData['content'], true, 512, JSON_THROW_ON_ERROR);
    if (($fileInfo['fileName'] ?? '') !== $fileName) {
        throw new Exception('Nombre de archivo no coincide');
    }
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    exit();
}

$filePath = __DIR__ . '/uploads/' . $fileName;
if (!is_file($filePath)) {
    header('HTTP/1.1 404 Not Found');
    exit();
}

$updateQuery = "UPDATE messages SET download_count = download_count + 1 WHERE id = ?";
$updateStmt = $conexion->prepare($updateQuery);
$updateStmt->bind_param("i", $documentId);
$updateStmt->execute();

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filePath) ?: 'application/octet-stream';
finfo_close($finfo);

header('Content-Description: File Transfer');
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . basename($fileInfo['originalName'] ?? $fileName) . '"');
header('Content-Length: ' . filesize($filePath));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

while (ob_get_level() > 0) {
    ob_end_clean();
}

readfile($filePath);
exit;
?>
