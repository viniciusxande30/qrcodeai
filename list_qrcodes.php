<?php
session_start();

if (!isset($_SESSION['user_index'])) {
    header('Location: login.php');
    exit();
}

$qrcodeDir = 'qrcodes';

$dataFile = 'data/users.json';
$users = [];

if (file_exists($dataFile)) {
    $json = file_get_contents($dataFile);
    $users = json_decode($json, true);
}

$user = $users[$_SESSION['user_index']];
$username = $user['username'];

$qrcodes = [];
if (is_dir($qrcodeDir)) {
    $files = scandir($qrcodeDir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'svg' && strpos($file, $username) !== false) {
            $qrcodes[] = $file;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Seus QR Codes</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important;
        }
        .container {
            margin-top: 50px;
        }
        .table th {
            background-color: #007bff;
            color: #ffffff;
        }
        .table td {
            vertical-align: middle;
        }
        .qr-thumbnail {
            width: 50px;
            height: 50px;
        }
        .alert-info {
            background-color: #e2e3e5;
            color: #383d41;
        }
    </style>
</head>
<body>













    <?php

$version = '5.3.5';

if (version_compare(phpversion(), '5.4', '<')) {
    exit("QRcdr requires at least PHP version 5.4.");
}

// https://stackoverflow.com/questions/11920026/replace-file-get-contents-with-curl
if (!ini_get('allow_url_fopen')) {
    exit("Please enable <code>allow_url_fopen<code>");
}
if (!function_exists('mime_content_type')) {
    exit("Please enable the <code>fileinfo</code> extension");
}
// Update this path if you have a custom relative path inside config.php
require dirname(__FILE__)."/lib/functions.php";

if (qrcdr()->getConfig('debug_mode')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL ^ E_NOTICE);
}
$relative = qrcdr()->relativePath();
require dirname(__FILE__).'/'.$relative.'include/head.php';
?>
<!doctype html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $rtl['dir']; ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title><?php echo qrcdr()->getString('title'); ?></title>
        <meta name="description" content="<?php echo qrcdr()->getString('description'); ?>">
        <meta name="keywords" content="<?php echo qrcdr()->getString('tags'); ?>">
        <link rel="shortcut icon" href="<?php echo $relative; ?>images/favicon.ico">
        <link href="<?php echo $relative; ?>bootstrap/css/bootstrap<?php echo $rtl['css']; ?>.min.css" rel="stylesheet">
        <link href="<?php echo $relative; ?>css/font-awesome.min.css" rel="stylesheet">
        <script src="<?php echo $relative; ?>js/jquery-3.5.1.min.js"></script>
        <?php
        $custom_page = false;
        $body_class = '';
        if (isset($_GET['p'])) {
            $load_page = dirname(__FILE__).'/'.$relative.'template/'.$_GET['p'].'.html';
            if (file_exists($load_page)) {
                $custom_page = file_get_contents($load_page);
            }
        }
        qrcdr()->loadQRcdrCSS($version);
        if (!$custom_page) {
            $body_class = 'qrcdr';
            qrcdr()->loadPluginsCss();
        }
        qrcdr()->setMainColor(qrcdr()->getConfig('color_primary'));
        ?>
    </head>
    <body class="<?php echo $body_class; ?>">


    <nav class="navbar bg-primary m-0 navbar-expand-sm navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#qrcdrNavbar" aria-controls="qrcdrNavbar" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="qrcdrNavbar">
		<ul class="navbar-nav ms-auto">
<!--
		<li class="nav-item">
			<a class="nav-link" href="#">Link 1</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#">Link 2</a>
		</li>
-->
			<li style="margin-top:5px; margin-right:15px;">
                <a href="profile.php"  style="color:white;text-decoration:none;font-weight:bolder">Área de Login</a>
            </li>
                <li class="nav-item">
                    <a class="nav-link"  style="color:white;text-decoration:none;font-weight:bolder" href="list_qrcodes.php">Lista de QR Codes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"  style="color:white;text-decoration:none;font-weight:bolder" href="logout.php">Sair</a>
                </li>
			<?php echo qrcdr()->langMenu('menu'); ?>
		</ul>
	</div>
</nav>



















    <div class="container">
        <h2 class="text-center mb-5">Seus QR Codes</h2>
        <?php if (!empty($qrcodes)): ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>QR Code</th>
                        <th>Imagem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($qrcodes as $qrcode): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($username); ?></td>
                            <td><a href="<?php echo $qrcodeDir . '/' . $qrcode; ?>" target="_blank"><?php echo htmlspecialchars($qrcode); ?></a></td>
                            <td><img src="<?php echo $qrcodeDir . '/' . $qrcode; ?>" alt="QR Code" class="qr-thumbnail"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">Nenhum QR Code encontrado para o seu usuário.</div>
        <?php endif; ?>
    </div>
</body>
</html>