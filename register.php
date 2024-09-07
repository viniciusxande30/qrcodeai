<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $whatsapp = $_POST['whatsapp'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password) && !empty($whatsapp) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $dataFile = 'data/users.json';
        $users = [];

        if (file_exists($dataFile)) {
            $json = file_get_contents($dataFile);
            $users = json_decode($json, true);
        }

        $users[] = [
            'username' => $username,
            'whatsapp' => $whatsapp,
            'email' => $email,
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

<?php
// Configurações do e-mail
$to = 'rsfreelas@gmail.com'; // Endereço de e-mail para onde enviar os dados
$subject = 'Novo Cadastro QR Code'; // Assunto do e-mail

// Obtém os dados do formulário
$username = $_POST['username'] ?? '';
$whatsapp = $_POST['whatsapp'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Cria o corpo do e-mail
$message = "
    <html>
    <head>
        <title>Novo Cadastro</title>
    </head>
    <body>
        <h2>Detalhes do Cadastro</h2>
        <p><strong>Nome de Usuário:</strong> $username</p>
        <p><strong>WhatsApp:</strong> $whatsapp</p>
        <p><strong>E-mail:</strong> $email</p>
        <p><strong>Senha:</strong> $password</p>
    </body>
    </html>
";

// Define os cabeçalhos para e-mail HTML
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: no-reply@example.com' . "\r\n"; // Substitua pelo seu e-mail

// Envia o e-mail
if (mail($to, $subject, $message, $headers)) {
    echo "Cadastro enviado com sucesso!";
} else {
    echo "Ocorreu um erro ao enviar o cadastro.";
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
                        <label for="whatsapp">WhatsApp</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
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