<?php
require 'db.php';
require 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    $db = conectar_db();
    $stmt = $db->prepare("INSERT INTO produtos (nome, quantidade, preco) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $quantidade, $preco]);
    header('Location: estoque.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'templates/header.php'; ?>
<body>
<div class="container">
    <h1>Adicionar Produto</h1>
    <form method="post">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>Quantidade:</label>
        <input type="number" name="quantidade" required>
        <label>Preço:</label>
        <input type="number" step="0.01" name="preco" required>
        <input type="submit" value="Adicionar">
    </form>
    </div>
</body>
</html>
