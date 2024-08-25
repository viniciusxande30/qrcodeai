<?php
session_start();

if (!isset($_SESSION['user_index'])) {
    header('Location: ../login.php');
    exit();
}

$dataFile = '../data/users.json';
$users = [];

if (file_exists($dataFile)) {
    $json = file_get_contents($dataFile);
    $users = json_decode($json, true);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Administração</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Sistema</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../profile.php">Perfil</a> <!-- Atualizado -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Administração</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2 class="mt-5">Painel de Administração</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome de Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td>
                            <a href="edit_user.php?index=<?php echo $index; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="delete_user.php?index=<?php echo $index; ?>" class="btn btn-danger btn-sm">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>