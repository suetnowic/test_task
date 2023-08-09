<?php
include 'connect.php';

if (isset($_POST['register'])) {

    $errors = [];

    $username = $_POST['username'];
    $phone = $_POST['user_phone'];
    $email = $_POST['user_email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($username) || empty($phone) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = "Все поля обязательны для заполнения";
    } else {
        $usernameIsExist = checkUserExist($connect, 'username', $username);
        $phoneIsExist = checkUserExist($connect, "phone", $phone);
        $emailIsExist = checkUserExist($connect, "email", $email);

        if ($usernameIsExist) $errors[] = "Пользователь с таким именем уже существует<br>";
        if ($phoneIsExist) $errors[] = "Пользователь с таким номером уже существует<br>";
        if ($emailIsExist) $errors[] = "Пользователь с таким email уже существует";
    }

    if (!$errors) {
        $result = matchPassword($password, $confirmPassword);
        if (!$result) {
            echo "Пароли не совпадают";
        } else {
            registerUser($connect, $username, $phone, $email, $password);
        }
    } else {
        echo '<pre>';
        print_r($errors);
        echo '</pre>';
    }
}

function registerUser($connect, $username, $phone, $email, $password)
{
    $sql = "INSERT INTO users(username, phone, email, password) VALUES (:username, :phone, :email, :password)";
    $query = $connect->prepare($sql);
    $query->bindParam(':username', $username);
    $query->bindParam(':phone', $phone);
    $query->bindParam(':email', $email);
    $query->bindParam(':password', sharedPassword($password));
    try {
        $query->execute();
        echo "Регистрация успешна";
    } catch (PDOException $e) {
        echo "Ошибка при регистрации" . $e->getMessage();
    }
}

function sharedPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function matchPassword($password, $confirmPassword)
{
    return $password == $confirmPassword;
}

function checkUserExist($connect, $field, $val)
{
    $query = $connect->prepare("SELECT * FROM users WHERE $field=:val");
    $query->bindParam(':val', $val);
    $query->execute();
    return $query->rowCount();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
<h1>Регистрация</h1>
<form action="registration.php" method="post" name="registerForm">
    <p>
        <label>Имя пользователя:
            <br>
            <input name="username" size="30" type="text" value="">
        </label>
    </p>
    <p>
        <label>Телефон:
            <br>
            <input name="user_phone" size="30" type="tel" value="">
        </label>
    </p>
    <p>
        <label>Email:
            <br>
            <input name="user_email" size="30" type="email" value="">
        </label>
    </p>
    <p>
        <label>Пароль:
            <br>
            <input name="password" size="30" type="password" value="">
        </label>
    </p>
    <p>
        <label>Подтверждение пароля:
            <br>
            <input name="confirm_password" size="30" type="password" value="">
        </label>
    </p>
    <p>
        <input name="register" type="submit" value="Зарегистрироваться">
    </p>
    <p>
        <a href="login.php">У меня уже есть аккаунт</a>
    </p>
</form>
</body>
</html>