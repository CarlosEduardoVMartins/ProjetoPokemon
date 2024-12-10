<?php
require 'db.php';
require 'auth.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $db = conectar_db();
    $stmt = $db->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: estoque.php');
exit();
?>
