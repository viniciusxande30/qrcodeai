<?php
session_start();

$valid_username = 'admin';
$valid_password = 'teste';

if ($_POST['username'] === $valid_username && $_POST['password'] === $valid_password) {
    $_SESSION['loggedin'] = true;
    header("location: ../admin.php");
} else {
    $_SESSION['error'] = "Login ou senha inválidos.";
    header("location: login.php");
}
?>