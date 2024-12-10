<?php
require 'db.php';
require 'auth.php';
// Verifica se o botão foi pressionado para exportar
if (isset($_POST['exportar_csv'])) {
    // Conectar ao banco de dados
    $db = conectar_db();
    $produtos = $db->query("SELECT * FROM produtos")->fetchAll(PDO::FETCH_ASSOC);

    // Nome do arquivo CSV
    $filename = "estoque_produtos.csv";

    // Abrir o arquivo para escrita (o 'php://output' envia diretamente para o navegador)
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Abrir o "arquivo" em modo de escrita
    $output = fopen('php://output', 'w');

    // Escrever o BOM (Byte Order Mark) para garantir a codificação correta no Excel
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Escrever o cabeçalho CSV (títulos das colunas)
    fputcsv($output, ['ID', 'Nome', 'Quantidade', 'Preço']);

    // Escrever os dados dos produtos
    foreach ($produtos as $produto) {
        fputcsv($output, [$produto['id'], $produto['nome'], $produto['quantidade'], $produto['preco']]);
    }

    // Fechar o arquivo
    fclose($output);
    exit;
}

// Carregar os produtos do banco de dados para exibição na tabela
$db = conectar_db();
$produtos = $db->query("SELECT * FROM produtos")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include 'templates/header.php'; ?>
<body>
<div class="container">
    <h1>Estoque de Itens no Laboratório</h1>
    <nav>
        <button> <a href="adicionar.php">Adicionar Item</a></button>
        <br><br>
        <button><a href="logout.php">Logout</a></button>
    </nav>

    <h2>Itens no Inventário</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['id']) ?></td>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td><?= htmlspecialchars($produto['quantidade']) ?></td>
                    <td><?= htmlspecialchars($produto['preco']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $produto['id'] ?>">Editar</a> |
                        <a href="excluir.php?id=<?= $produto['id'] ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Botão de exportação para CSV -->
    <form method="post" action="">
        <button type="submit" name="exportar_csv">Exportar estoque para um arquivo de texto</button>
    </form>
    <br>
    <br>

    <!-- Botão para ler o estoque em áudio -->
    <button id="lerEstoque">Ler Estoque em Áudio
    <img src="acessibilidade.png" height="25px" width="25px"/>
    </button>

    <h2>Gráfico de Quantidade de Produtos</h2>
    <canvas id="graficoProdutos" width="400" height="200"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('graficoProdutos').getContext('2d');
        const produtos = <?= json_encode($produtos) ?>;
        const nomes = produtos.map(p => p.nome);
        const quantidades = produtos.map(p => p.quantidade);

        new Chart(ctx, {
            type: 'bar',  // Tipo de gráfico
            data: {
                labels: nomes,  // Nomes dos produtos como rótulos
                datasets: [{
                    label: 'Quantidade de Produtos',  // Título do gráfico
                    data: quantidades,  // Quantidade de cada produto
                    backgroundColor: '#a82223',  // Cor das barras
                }]
            }
        });

        // Função para ler o estoque em áudio
        document.getElementById('lerEstoque').addEventListener('click', function () {
            const estoque = produtos.map(p => {
                return `Produto: ${p.nome}, Quantidade: ${p.quantidade}, Preço: R$ ${p.preco.toFixed(2)}`;
            }).join('. ');  // Cria uma string com todas as informações do estoque

            // Verifica se a API SpeechSynthesis está disponível
            if ('speechSynthesis' in window) {
                const speech = new SpeechSynthesisUtterance(estoque);
                speech.lang = 'pt-BR';  // Define a língua para português
                window.speechSynthesis.speak(speech);  // Executa a leitura em áudio
            } else {
                alert("A funcionalidade de leitura de áudio não é suportada no seu navegador.");
            }
        });
    </script>
    <br>
    <div>
          <h2>Clique no Gengar para retornar ao menu</h2>
          <a href="index.html" alt="torre">
            <img src="gengar.gif" height="175px" width="175px"/>
          </a>
    </div>
    <br><br>
</body>
</html>
