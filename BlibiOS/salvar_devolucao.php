<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>BibliOS - Processando Devolução</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// 1. DADOS DE CONEXÃO COM O XAMPP (O básico que você já conhece)
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "db_biblios";

// Abre a conexão com o banco de dados
$conexao = mysqli_connect($host, $usuario, $senha, $banco);

// Verifica se a conexão falhou
if (!$conexao) {
    die("<div class='caixa-sucesso'><h2>❌ Falha na conexão!</h2></div>");
}

// Configura para não dar erro com acentos no banco
mysqli_set_charset($conexao, "utf8mb4");


// 2. PEGAR OS DADOS QUE ENTRARAM DO FORMULÁRIO (Usando o $_POST direto)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data_devolucao       = $_POST['data_devolucao'];
    $observacao_devolucao = $_POST['observacao_devolucao'];

    // 3. COMANDO SQL SIMPLES (UPDATE)
    // Atualiza o último empréstimo pendente inserindo a data e o novo status
    $sql = "UPDATE tb_emprestimos 
            SET data_devolucao_real = '$data_devolucao', status_emprestimo = 'Devolvido' 
            WHERE status_emprestimo = 'Pendente' 
            ORDER BY id_emprestimo DESC LIMIT 1";

    // Executa a linha acima direto no banco de dados usando mysqli_query
    $resultado = mysqli_query($conexao, $sql);


    // 4. MOSTRAR A RESPOSTA NA TELA
    if ($resultado) {
        
        // Se deu certo, mostra o card verde bonito do CSS
        echo '<div class="caixa-sucesso">';
        echo '    <h2 class="mensagem-sucesso">✅ Baixa realizada com sucesso!</h2>';
        echo '    <div class="info-grupo"><strong>Data da Devolução:</strong> ' . $data_devolucao . '</div>';
        echo '    <div class="info-grupo"><strong>Observações:</strong> ' . $observacao_devolucao . '</div>';
        echo '    <a href="index.html" class="botao-voltar">Ir para o Dashboard</a>';
        echo '</div>';

    } else {
        // Se deu erro no banco, avisa
        echo "<div class='caixa-sucesso'><h2>❌ Erro ao dar baixa no banco de dados!</h2></div>";
    }

} else {
    echo "<div class='caixa-formulario'>Acesso inválido.</div>";
}

// Fecha a conexão aberta
mysqli_close($conexao);
?>

</body>
</html>
