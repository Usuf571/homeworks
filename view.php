<?php
require 'config.php';

if (!isset($_SESSION['auth'])) {
    http_response_code(403);
    die("Доступ запрещён");
}

$id = (int)($_GET['id'] ?? 0);
if ($id < 1) die("Неверный запрос");

$stmt = $pdo->prepare("SELECT filename FROM assignments WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) die("Файл не найден");

$filename = $row['filename'];
$filepath = $upload_dir . $filename;

if (!file_exists($filepath)) {
    die("Файл физически отсутствует");
}

$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if ($ext === 'svg') {
    header('Content-Type: image/svg+xml');
    header('Content-Disposition: inline; filename="' . basename($filename) . '"');
    readfile($filepath);
    exit;
}

// Для всех остальных — скачивание
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;