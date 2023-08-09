<?php
session_start();
include '../app/connect.php';
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $query = $connect->prepare("SELECT * FROM users WHERE id=:id");
    $query->bindParam(':id', $id);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
</head>
<body>

<?php if (isset($user)): ?>

    <p>Привет, <?= ($user['username']) ?></p>

    <p><a href="../app/logout.php">Выйти</a></p>

<?php else: ?>

    <p>Привет, Гость</p>

    <p><a href="../app/login.php">Войти</a> или <a href="../app/registration.php">Зарегистрироваться</a></p>

<?php endif; ?>

</body>
</html>