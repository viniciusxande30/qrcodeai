<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $dataFile = 'data/users.json';
        $users = [];

        if (file_exists($dataFile)) {
            $json = file_get_contents($dataFile);
            $users = json_decode($json, true);
        }

        $users[] = [
            'username' => $username,
            'password' => $hashedPassword
        ];

        file_put_contents($dataFile, json_encode($users));

        header('Location: login.php');
        exit();
    } else {
        $error = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mt-5 text-center">Cadastro</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="post" action="register.php">
                    <div class="form-group">
                        <label for="username">Nome de Usuário</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="login.php">Já tem uma conta? Faça login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>