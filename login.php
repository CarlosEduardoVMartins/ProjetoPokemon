<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $senha = $_POST['senha'];

    $db = conectar_db();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['username'] = $username;
        header('Location: estoque.php');
        exit();
    } else {
        $erro = "Nome de usuário ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/header.php'; ?>
<body>
<div class="container">
    <h1>Login</h1>
    <form method="post">
        <label>Usuário:</label>
        <input type="text" name="username" required> <br>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <input type="submit" value="Entrar">
    </form>
    <?php if (isset($erro)): ?>
        <p><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
    <p class="register-link">Não tem uma conta? <a href="register.php">Registre-se aqui</a>.</p>
    </div>
</body>
</html>
