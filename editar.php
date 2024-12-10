<?php
require 'db.php';
require 'auth.php';

$db = conectar_db();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    $stmt = $db->prepare("UPDATE produtos SET nome = ?, quantidade = ?, preco = ? WHERE id = ?");
    $stmt->execute([$nome, $quantidade, $preco, $id]);
    header('Location: estoque.php');
    exit();
}

$stmt = $db->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/header.php'; ?>
<body>
<div class="container">
    <h1>Editar Produto</h1>
    <form method="post">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        <label>Quantidade:</label>
        <input type="number" name="quantidade" value="<?= htmlspecialchars($produto['quantidade']) ?>" required>
        <label>Preço:</label>
        <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required>
        <input type="submit" value="Salvar">
    </form>
    </div>
</body>
</html>
