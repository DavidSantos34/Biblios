<?php
require_once 'conexao.php';

// 1. PROCESSAR A ATUALIZAÇÃO (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome_completo'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['data_nascimento'];
    $status = $_POST['status_leitor'];
    $caminho_foto = $_POST['foto_atual']; // Mantém a foto antiga por padrão

    // Se uma nova foto foi enviada, substitui a antiga
    if (isset($_FILES['foto_leitor']) && $_FILES['foto_leitor']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['foto_leitor']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid('leitor_') . '.' . $extensao;
        $destino = 'uploads/' . $novo_nome;

        if (move_uploaded_file($_FILES['foto_leitor']['tmp_name'], $destino)) {
            if (!empty($caminho_foto) && file_exists($caminho_foto)) {
                unlink($caminho_foto); // Apaga a foto antiga do servidor
            }
            $caminho_foto = $destino;
        }
    }

    $sql = "UPDATE leitor SET nome_completo = ?, email = ?, telefone = ?, data_nascimento = ?, foto_leitor = ?, status_leitor = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $nome, $email, $telefone, $nascimento, $caminho_foto, $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: leitor.php?sucesso=1");
        exit;
    } else {
        $erro = "Erro ao atualizar leitor: " . mysqli_error($conexao);
    }
    mysqli_stmt_close($stmt);
}

// 2. BUSCAR DADOS DO LEITOR PARA PREENCHER O FORMULÁRIO (GET)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: leitor.php");
    exit;
}

$id = $_GET['id'];
$sql_busca = "SELECT * FROM leitor WHERE id = ?";
$stmt_busca = mysqli_prepare($conexao, $sql_busca);
mysqli_stmt_bind_param($stmt_busca, "i", $id);
mysqli_stmt_execute($stmt_busca);
$resultado = mysqli_stmt_get_result($stmt_busca);
$leitor = mysqli_fetch_assoc($resultado);

if (!$leitor) {
    header("Location: leitor.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>BibliOS - Editar Leitor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <a href="leitor.php" class="link-voltar">← Voltar para Leitores</a>

    <h3 class="titulo-pagina-cadastro">Editar Leitor</h3>
    <div class="caixa-formulario">
        <h2 class="titulo-formulario">Alterar Dados</h2>

        <?php if (isset($erro)): ?>
            <p style="color: red;"><?= $erro ?></p>
        <?php endif; ?>

        <form action="leitor_editar.php?id=<?= $leitor['id'] ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $leitor['id'] ?>">
            <input type="hidden" name="foto_atual" value="<?= htmlspecialchars($leitor['foto_leitor']) ?>">

            <div class="campo-grupo">
                <label for="nome" class="rotulo-principal">Nome Completo:</label>
                <input type="text" id="nome" name="nome_completo" class="caixa-entrada" value="<?= htmlspecialchars($leitor['nome_completo']) ?>" required>
            </div>

            <div class="campo-grupo">
                <label for="email" class="rotulo-principal">E-mail:</label>
                <input type="email" id="email" name="email" class="caixa-entrada" value="<?= htmlspecialchars($leitor['email']) ?>" required>
            </div>

            <div class="campo-grupo">
                <label for="telefone" class="rotulo-principal">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" class="caixa-entrada" value="<?= htmlspecialchars($leitor['telefone']) ?>" required>
            </div>

            <div class="campo-grupo">
                <label for="nascimento" class="rotulo-principal">Data de Nascimento:</label>
                <input type="date" id="nascimento" name="data_nascimento" class="caixa-entrada" value="<?= $leitor['data_nascimento'] ?>" required>
            </div>

            <div class="campo-grupo">
                <label for="foto" class="rotulo-principal">Alterar Foto 3x4 (opcional):</label>
                <?php if (!empty($leitor['foto_leitor'])): ?>
                    <div style="margin-bottom: 8px;">
                        <img src="<?= htmlspecialchars($leitor['foto_leitor']) ?>" width="60" alt="Foto Atual">
                    </div>
                <?php endif; ?>
                <input type="file" id="foto" name="foto_leitor" class="caixa-entrada" accept="image/*">
            </div>

            <div class="campo-grupo">
                <label class="rotulo-principal">Status do Cadastro:</label>
                <div class="grupo-radio">
                    <label class="opcao-radio">
                        <input type="radio" name="status_leitor" value="Ativo" <?= $leitor['status_leitor'] === 'Ativo' ? 'checked' : '' ?>> Ativo
                    </label>
                    <label class="opcao-radio">
                        <input type="radio" name="status_leitor" value="Inativo" <?= $leitor['status_leitor'] === 'Inativo' ? 'checked' : '' ?>> Inativo
                    </label>
                </div>
            </div>

            <button type="submit" class="botao-salvar">Atualizar Cadastro</button>
        </form>
    </div>

</body>
</html>
