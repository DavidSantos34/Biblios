<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leitor = $_POST['leitor'] ?? '';
    $livro = $_POST['livro'] ?? '';
    $data_saida = $_POST['data_saida'] ?? '';
    $data_prevista = $_POST['data_prevista'] ?? '';
    $observacao_saida = $_POST['observacao_saida'] ?? '';
    if (!empty($id_leitor) && !empty($id_livro)) {
        echo "<h2>Empréstimo Confirmado!</h2>";
        echo "<p>Livro ID: $id_livro emprestado ao Leitor ID: $id_leitor até $data_prevista.</p>";
        echo "<a href='index.html'>Voltar</a>";

    } else {
        echo "Erro: Por favor, selecione um leitor e um livro.";
    }
}
?>