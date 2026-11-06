<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>BibliOS - Salvando Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "db_tbiblios"; 


$conexao = new mysqli($host, $usuario, $senha, $banco);


if ($conexao->connect_error) {
    die("<div class='caixa-sucesso'><h2 style='color:#e74c3c;'>❌ Erro ao conectar ao banco de dados:</h2> " . $conexao->connect_error . "</div>");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $titulo = $_POST['titulo'] ?? '';
    $autor  = $_POST['autor'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $status = $_POST['status'] ?? 'ativo';

    
    $status_formatado = ($status == 'ativo') ? 'Ativo' : 'Inativo';

 
    $nome_capa = "";
    
    if (isset($_FILES['capa']) && $_FILES['capa']['error'] == 0) {
        $diretorio_destino = "uploads/";

        
        if (!is_dir($diretorio_destino)) {
            mkdir($diretorio_destino, 0777, true);
        }

        
        $nome_ficheiro = time() . "_" . basename($_FILES['capa']['name']);
        $caminho_final = $diretorio_destino . $nome_ficheiro;

      
        if (move_uploaded_file($_FILES['capa']['tmp_name'], $caminho_final)) {
            $nome_capa = $caminho_final;
        }
    }

    
    if (empty($nome_capa)) {
        echo "<div class='caixa-sucesso'>";
        echo "    <h2 style='color:#e74c3c;'>❌ Erro no cadastro!</h2>";
        echo "    <p class='info-grupo'>Não foi possível carregar o arquivo de imagem da capa.</p>";
        echo "    <a href='javascript:history.back()' class='botao-voltar'>Voltar ao formulário</a>";
        echo "</div>";
        exit;
    }

   
     $sql = "INSERT INTO `tb_livros` (`titulo`, `autor`, `genero`, `capa_livro`, `status_livro`) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conexao->prepare($sql);
    
    if ($stmt === false) {
        die("<div class='caixa-sucesso'><h2 style='color:#e74c3c;'>❌ Erro na preparação do banco:</h2> " . $conexao->error . "</div>");
    }
    
    // Vincula as variáveis ao comando SQL (5 strings = "sssss")
    $stmt->bind_param("sssss", $titulo, $autor, $genero, $nome_capa, $status_formatado);

   
    if ($stmt->execute()) {
        

        echo '<div class="caixa-sucesso">';
        echo '    <h2 class="mensagem-sucesso">✅ Livro cadastrado com sucesso!</h2>';
        
        echo '    <div class="info-grupo"><strong>Título:</strong> ' . htmlspecialchars($titulo) . '</div>';
        echo '    <div class="info-grupo"><strong>Autor:</strong> ' . htmlspecialchars($autor) . '</div>';
        echo '    <div class="info-grupo"><strong>Gênero:</strong> ' . htmlspecialchars($genero) . '</div>';
        echo '    <div class="info-grupo"><strong>Status:</strong> ' . htmlspecialchars($status_formatado) . '</div>';
        
        echo '    <div class="capa-container">';
        echo '        <p>Capa enviada:</p>';
        echo '        <img src="' . $nome_capa . '" alt="Capa do Livro" class="imagem-capa">';
        echo '    </div>';

        echo '    <a href="cadastro-livros.html" class="botao-voltar">Cadastrar outro livro</a>';
        echo '</div>';

    } else {
        echo "<div class='caixa-sucesso'><h2 style='color:#e74c3c;'>❌ Erro ao gravar dados:</h2> " . $stmt->error . "</div>";
    }

    
    $stmt->close();

} else {
    echo "<div class='caixa-formulario' style='text-align:center; margin-top:40px;'>Acesso inválido.</div>";
}

$conexao->close();
?>

</body>
</html>
