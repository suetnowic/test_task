<?php
session_start();
include 'connect.php';
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $query = $connect->prepare("SELECT * FROM users WHERE id=:id");
    $query->bindParam(':id', $id);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: ../web/index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
</head>
<body>


<h3>Информация о пользователе</h3>
<p>Здесь вы можете изменить личную информацию</p>

<form action="update_users.php" method="post" name="update">
    <p>
        <label>Имя пользователя:
            <br>
            <input name="username" value="<?= $user['username'] ?>">
        </label>
    </p>
    <p>
        <label>Телефон:
            <br>
            <input name="phone" value="<?= $user['phone'] ?>">
        </label>
    </p>
    <p>
        <label>Email:
            <br>
            <input name="email" value="<?= $user['email'] ?>">
        </label>
    </p>
    <p>
        <label>Новый пароль:
            <br>
            <input name="new_password" value="">
        </label>
    </p>
    <p>
        <input name="update" type="submit" value="Сохранить">
    </p>
    <p><a href="logout.php">Выйти</a></p>
</form>
</body>
</html>
