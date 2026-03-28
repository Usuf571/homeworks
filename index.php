<?php 
require 'config.php';

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM assignments ORDER BY uploaded_at DESC");
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Домашние задания</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <header>
    <h1>Список домашних заданий</h1>
    <p>
      <a href="upload.php">+ Загрузить новое задание</a>  
      <span style="margin: 0 10px;">|</span>
      <a href="logout.php">Выйти</a>
    </p>
  </header>

  <?php if (empty($assignments)): ?>
    <div style="text-align:center; padding: 3rem; background:white; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.06);">
      <p style="font-size:1.1rem; color:#718096;">Пока нет загруженных заданий.</p>
      <p><a href="upload.php">Загрузить первое задание →</a></p>
    </div>
  <?php else: ?>
    <div class="list">
      <?php foreach ($assignments as $item): 
        $ext = strtolower(pathinfo($item['filename'], PATHINFO_EXTENSION));
      ?>
        <div class="card">
          <h3><?= htmlspecialchars($item['title']) ?></h3>
          <p class="date">Загружено: <?= date('d.m.Y H:i', strtotime($item['uploaded_at'])) ?></p>
          
          <?php if (!empty($item['description'])): ?>
            <p class="description"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
          <?php endif; ?>

          <?php if ($ext === 'svg'): ?>
            <div class="svg-preview-container">
              <div class="svg-preview-wrapper">
                <object 
                  class="svg-preview"
                  type="image/svg+xml"
                  data="view.php?id=<?= $item['id'] ?>">
                  Ваш браузер не поддерживает просмотр SVG.
                </object>
              </div>
            </div>
          <?php endif; ?>

          <div class="actions">
            <a href="view.php?id=<?= $item['id'] ?>" target="_blank">
              <?= ($ext === 'svg') ? 'Открыть в новой вкладке / Скачать' : 'Скачать файл (' . strtoupper($ext) . ')' ?>
            </a>
            <span style="margin: 0 8px; color:#cbd5e0;">|</span>
            <a href="edit.php?id=<?= $item['id'] ?>">Редактировать</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

</body>
</html>