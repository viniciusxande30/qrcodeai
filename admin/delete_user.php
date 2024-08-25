<?php
$dataFile = '../data/users.json';
$users = [];

if (file_exists($dataFile)) {
    $json = file_get_contents($dataFile);
    $users = json_decode($json, true);
}

$index = $_GET['index'] ?? null;
if ($index === null || !isset($users[$index])) {
    die("Usuário não encontrado.");
}

unset($users[$index]);
$users = array_values($users); // Reindexar array

file_put_contents($dataFile, json_encode($users));
header('Location: index.php');
exit();
?>