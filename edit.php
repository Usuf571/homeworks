<?php 
require 'config.php';

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id < 1) die("Неверный ID");

$stmt = $pdo->prepare("SELECT * FROM assignments WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) die("Задание не найдено");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $message = "Название обязательно";
    } else {
        $stmt = $pdo->prepare("UPDATE assignments SET title = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $desc, $id]);
        $message = "Изменения сохранены";
        // обновляем данные для отображения
        $item['title'] = $title;
        $item['description'] = $desc;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Редактировать задание</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Редактировать: <?= htmlspecialchars($item['title']) ?></h2>

<?php if ($message): ?>
  <p style="color:green"><?= $message ?></p>
<?php endif; ?>

<form method="post">
  <label>Название:<br>
    <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required style="width:100%; max-width:500px;">
  </label><br><br>

  <label>Описание:<br>
    <textarea name="description" rows="8" style="width:100%; max-width:500px;"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
  </label><br><br>

  <button type="submit">Сохранить</button>
</form>

<p><a href="index.php">← Назад к списку</a></p>

</body>
</html>