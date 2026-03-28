<?php 
require 'config.php';

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit;
}
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $message = "Название — обязательно!";
    } elseif (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $message = "Выберите файл";
    } else {
        $file = $_FILES['file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $message = "Недопустимый формат (разрешено: " . implode(", ", $allowed_ext) . ")";
        } else {
            $newname = date("Ymd-His") . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", $file['name']);
            $target = $upload_dir . $newname;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                $stmt = $pdo->prepare("INSERT INTO assignments (title, description, filename) VALUES (?, ?, ?)");
                $stmt->execute([$title, $desc, $newname]);
                $message = "Задание успешно добавлено!";
            } else {
                $message = "Не удалось сохранить файл";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Загрузка задания</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Новое домашнее задание</h2>

<?php if ($message): ?>
  <p style="color: <?= strpos($message, 'успешно') !== false ? 'green' : 'red' ?>"><?= $message ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <label>Название задания:<br>
    <input type="text" name="title" required style="width:100%; max-width:500px;">
  </label><br><br>

  <label>Описание / комментарий:<br>
    <textarea name="description" rows="6" style="width:100%; max-width:500px;"></textarea>
  </label><br><br>

  <label>Файл (svg, cdr, pdf, изображение...):<br>
    <input type="file" name="file" required>
  </label><br><br>

  <button type="submit">Загрузить</button>
</form>

<p><a href="index.php">← Назад</a></p>

</body>
</html>