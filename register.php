<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    $db = conectar_db();
    $stmt = $db->prepare("INSERT INTO usuarios (username, senha) VALUES (?, ?)");

    try {
        $stmt->execute([$username, $senha]);
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        $erro = "Nome de usuário já existe.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/header.php'; ?>
<body>
    <h1>Registro</h1>
    <form method="post">
        <label>Usuário:</label>
        <input type="text" name="username" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <input type="submit" value="Registrar">
    </form>
    <?php if (isset($erro)): ?>
        <p><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
</body>
</html>
