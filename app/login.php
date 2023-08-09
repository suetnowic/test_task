<?php
session_start();
include 'constants.php';

function login()
{
    include 'connect.php';
    if (isset($_POST['login'])) {
        $emailPhone = trim($_POST['emailPhone']);
        $password = trim($_POST['password']);

        $user = loginUser($connect, $emailPhone);

        $verify = passwordVerify($password, $user);

        if ($user && $verify) {
            $_SESSION['auth'] = true;
            $_SESSION['user_id'] = $user['id'];
            header("Location: profile.php");
            exit();
        } else {
            echo "Вы ввели не верный email/телефон или пароль";
        }
    }
}

function passwordVerify($password, $user)
{
    return password_verify($password, $user['password']);
}

function loginUser($connect, $emailPhone)
{

    $sql = "SELECT id, password FROM users WHERE email=:login OR phone=:login";
    $query = $connect->prepare($sql);
    $query->bindParam(':login', $emailPhone);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function check_captcha($token)
{
    $ch = curl_init();
    $args = http_build_query([
        "secret" => SMARTCAPTCHA_SERVER_KEY,
        "token" => $token,
        "ip" => $_SERVER['REMOTE_ADDR'], // Нужно передать IP-адрес пользователя.
        // Способ получения IP-адреса пользователя зависит от вашего прокси.
    ]);
    curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
        echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
        return true;
    }
    $resp = json_decode($server_output);
    return $resp->status === "ok";
}

$token = $_POST['smart-token'];
if (check_captcha($token)) {
    login();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</head>

<body>
<h1>Вход</h1>
<form action="" method="post" name="authForm">
    <p>
        <label>Email / Телефон:
            <br>
            <input name="emailPhone" size="25" type="text" required>
        </label>
    </p>
    <p>
        <label>Пароль:
            <br>
            <input name="password" size="25" type="password" required>
        </label>
    </p>
    <div id="captcha-container" class="smart-captcha"
         data-sitekey=<?= DATA_SITEKEY ?>>
        <input type="hidden" name="smart-token" value="<токен>">
    </div>
    <input name="login" type="submit" value="Войти">
    <p>
        Еще нет аккаунта?
        <br>
        <a href="registration.php">Зарегистрироваться</a>
    </p>
</form>
</body>
</html>