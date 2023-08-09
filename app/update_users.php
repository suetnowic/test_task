<?php
include 'connect.php';
if (isset($_POST['update'])) {
    $id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    updateUser($connect, $id, $username, $phone, $email, $newPassword);
}

function updateUser($connect, $id, $username, $phone, $email, $newPassword) {
    $query = $connect->prepare("UPDATE users SET username=:username, phone=:phone, email=:email, password=:password WHERE id=:id");
    $query->bindParam(':id', $id);
    $query->bindParam(':username', $username);
    $query->bindParam(':phone', $phone);
    $query->bindParam(':email', $email);
    $sharedPassword = sharedPassword($newPassword);
    $query->bindParam(':password', $sharedPassword);
    try {
        $query->execute();
        echo "Изменения упешно сохранены";
    } catch (PDOException $e) {
        echo "Ошибка при сохранении изменений" . $e->getMessage();
    }
}

function sharedPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}