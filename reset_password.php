<?php
require 'config.php';

$new_plain_password = 'parol2025';  // ← напиши здесь желаемый пароль

$hash = password_hash($new_plain_password, PASSWORD_DEFAULT);

echo "<pre>";
echo "Новый хэш для вставки в базу:\n";
echo $hash . "\n\n";
echo "Готовый SQL-запрос:\n";
echo "UPDATE users SET password = '$hash' WHERE username = 'teacher';";
echo "</pre>";
?>