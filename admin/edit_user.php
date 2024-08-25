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

$user = $users[$index];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username)) {
        $users[$index]['username'] = $username;
    }

    if (!empty($password)) {
        $users[$index]['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    file_put_contents($dataFile, json_encode($users));
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Editar Usuário</h2>
        <form method="post" action="edit_user.php?index=<?php echo $index; ?>">
            <div class="form-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Nova Senha</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-muted">Deixe em branco se não quiser alterar a senha.</small>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</body>
</html>