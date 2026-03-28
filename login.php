<?php 
require 'config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $message = "Заполните все поля";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['auth'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // обновляем время последнего входа
            $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")
                ->execute([$user['id']]);

            header("Location: index.php");
            exit;
        } else {
            $message = "Неверное имя пользователя или пароль";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Вход</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <h2>Вход в систему</h2>

  <?php if ($message): ?>
    <p style="color:red;"><?= htmlspecialchars($message) ?></p>
  <?php endif; ?>

  <form method="post">
    <label>Имя пользователя (логин):<br>
      <input type="text" name="username" required autofocus>
    </label><br><br>
    
    <label>Пароль:<br>
      <input type="password" name="password" required>
    </label><br><br>
    
    <button type="submit">Войти</button>
  </form>
</div>

</body>
</html>