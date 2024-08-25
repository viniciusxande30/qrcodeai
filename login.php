<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $dataFile = 'data/users.json';
    $users = [];

    if (file_exists($dataFile)) {
        $json = file_get_contents($dataFile);
        $users = json_decode($json, true);
    }

    foreach ($users as $index => $user) {
        if ($user['username'] == $username && password_verify($password, $user['password'])) {
            $_SESSION['user_index'] = $index;
            header('Location: profile.php'); // Atualizado
            exit();
        }
    }

    $error = "Nome de usuário ou senha inválidos!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mt-5 text-center">Login</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="post" action="login.php">
                    <div class="form-group">
                        <label for="username">Nome de Usuário</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="register.php">Ainda não tem uma conta? Cadastre-se</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>




