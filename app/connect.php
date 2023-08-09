<?php
$servername = "localhost";
$database = "only_db";
$username = "root";
$password = "";

try {
    $connect = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
