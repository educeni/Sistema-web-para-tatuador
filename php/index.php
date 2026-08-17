<?php
session_start();
require_once("conexao.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

$erro = '';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $sql = "SELECT * FROM usuario WHERE nome = :nome AND senha = :senha";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':senha' => $senha
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nome'] = $usuario['nome'];
        header('Location: dashboard.php');
        exit;
    } else {
        $erro = "Nome de usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - TattooManager</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="login-wrapper">
    
    <div class="login-header">
        <i class="fa-solid fa-pen-nib"></i> <h1>Entrar na TattooManager</h1>
    </div>

    <?php if (!empty($erro)): ?>
        <div class="error-msg">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>

    <div class="login-box">
        <form method="post" action="" autocomplete="off">
            
            <div class="input-group">
                <label>Nome de Usuário</label>
                <input type="text" name="nome" placeholder="user" required autocomplete="off">
            </div>

            <div class="input-group">
                <div class="label-header">
                    <label>Senha</label>
                    <a href="#" class="forgot-pass">Esqueceu a senha?</a>
                </div>
                <input type="password" name="senha" placeholder="••••••••" required autocomplete="new-password">
            </div>
            
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>

    <div class="create-account-box">
        Novo na TattooManager? <a href="#">Crie uma conta</a>.
    </div>

</div>

<footer class="site-footer">
    <ul class="footer-links">
        <li><a href="#">Termos</a></li>
        <li><a href="#">Privacidade</a></li>
        <li><a href="#">Documentação</a></li>
        <li><a href="#">Suporte ao Tatuador</a></li>
        <li><a href="#">Não compartilhe minhas informações</a></li>
    </ul>
</footer>

</body>
</html>