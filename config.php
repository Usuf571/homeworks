<?php
session_start();

// === Настройки базы данных ===
define('DB_HOST', 'sql312.infinityfree.com');
define('DB_NAME', 'if0_41458517_homework');
define('DB_USER', 'if0_41458517');
define('DB_PASS', 'TP6fB2KoXMpE');

// Папка для файлов
$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$allowed_ext = ['svg', 'cdr', 'pdf', 'jpg', 'jpeg', 'png', 'docx', 'doc'];

// Подключение к БД
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}