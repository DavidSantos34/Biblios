<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>BibliOS - Salvando Leitor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome_completo   = $_POST['nome_completo'] ?? '';
    $email           = $_POST['email'] ?? '';
    $telefone        = $_POST['telefone'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $status          = $_POST['status_leitor'] ?? 'Ativo';

    $status_formatado = (strtolower($status) == 'ativo') ? 'Ativo' : 'Inativo';
    $nome_foto = "";
    $motivo_erro = "Nenhum arquivo selecionado.";

    if (isset($_FILES['foto_leitor'])) {
        if ($_FILES['foto_leitor']['error'] === UPLOAD_ERR_OK) {
            $diretorio_destino = "uploads/";

            if (!is_dir($diretorio_destino)) {
                mkdir($diretorio_destino, 0777, true);
            }

            $extensao = pathinfo($_FILES['foto_leitor']['name'], PATHINFO_EXTENSION);
            $nome_ficheiro = time() . "_" . uniqid() . "." . $extensao;
            $caminho_final = $diretorio_destino . $nome_ficheiro;

            if (move_uploaded_file($_FILES['foto_leitor']['tmp_name'], $caminho_final)) {
                $nome_foto = $caminho_final;
            } else {
                $motivo_erro = "Falha ao mover o arquivo para a pasta uploads/.";
            }
        } elseif ($_FILES['foto_leitor']['error'] === UPLOAD_ERR_INI_SIZE) {
            $motivo_erro = "A foto ultrapassa o limite de 2MB do servidor.";
        } else {
            $motivo_erro = "Código de erro no envio: " . $_FILES['foto_leitor']['error'];
        }
    }

    if (empty($nome_foto)) {
        echo "<div class='caixa-sucesso'>";
        echo "    <h2 style='color:#e74c3c;'>❌ Erro no cadastro do Leitor!</h2>";
        echo "    <p class='info-grupo'><strong>Motivo:</strong> " . $motivo_erro . "</p>";
        echo "    <a href='leitor.php' class='botao-voltar'>Voltar ao formulário</a>";
        echo "</div>";
        exit;
    }

    $sql = "INSERT INTO `tb_leitores` (`nome_completo`, `email`, `telefone`, `data_nascimento`, `foto_leitor`, `status_leitor`) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssss", $nome_completo, $email, $telefone, $data_nascimento, $nome_foto, $status_formatado);

    try {
        if ($stmt->execute()) {
            echo '<div class="caixa-sucesso">';
            echo '    <h2 class="mensagem-sucesso">✅ Leitor cadastrado com sucesso!</h2>';
            echo '    <div class="info-grupo"><strong>Nome Completo:</strong> ' . htmlspecialchars($nome_completo) . '</div>';
            echo '    <div class="info-grupo"><strong>E-mail:</strong> ' . htmlspecialchars($email) . '</div>';
            echo '    <div class="info-grupo"><strong>Telefone:</strong> ' . htmlspecialchars($telefone) . '</div>';
            echo '    <div class="info-grupo"><strong>Data de Nascimento:</strong> ' . date('d/m/Y', strtotime($data_nascimento)) . '</div>';
            echo '    <div class="info-grupo"><strong>Status:</strong> ' . htmlspecialchars($status_formatado) . '</div>';
            echo '    <div class="capa-container">';
            echo '        <p>Foto 3x4 enviada:</p>';
            echo '        <img src="' . $nome_foto . '" alt="Foto do Leitor" class="imagem-capa">';
            echo '    </div>';
            echo '    <a href="leitor.php" class="botao-voltar">Voltar para Leitores</a>';
            echo '</div>';
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            if (!empty($nome_foto) && file_exists($nome_foto)) {
                unlink($nome_foto);
            }
            echo "<div class='caixa-sucesso'>";
            echo "    <h2 style='color:#e74c3c;'>⚠️ E-mail já cadastrado!</h2>";
            echo "    <p class='info-grupo'>O e-mail <strong>" . htmlspecialchars($email) . "</strong> já pertence a outro leitor.</p>";
            echo "    <a href='leitor.php' class='botao-voltar'>Voltar e corrigir</a>";
            echo "</div>";
        } else {
            echo "<div class='caixa-sucesso'><h2 style='color:#e74c3c;'>❌ Erro ao gravar dados:</h2> " . $e->getMessage() . "</div>";
        }
    }

    $stmt->close();
} else {
    // Se alguém acessar salvar.php diretamente sem enviar formulário, redireciona para leitor.php
    header("Location: leitor.php");
    exit;
}

$conexao->close();
?>

</body>
</html>
