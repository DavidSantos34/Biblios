<?php
// Inicia a sessão para podermos dar a "chave" de acesso ao usuário
session_start();

$erro = "";

// Verifica se o usuário clicou no botão Entrar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha   = $_POST['senha'];

    // Validação simples (pode ser alterada depois para buscar na tb_usuarios do banco)
    if ($usuario == "admin" && $senha == "1234") {
        // Se acertou, cria a sessão de segurança e manda para o index
        $_SESSION['logado'] = true;
        $_SESSION['usuario_nome'] = "Administrador";
        header("Location: index.php");
        exit;
    } else {
        // Se errou, cria a mensagem de alerta
        $erro = "Usuário ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>BibliOS - Entrar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="caixa-formulario">
        <h1 class="titulo-sistema">BibliOS</h1>
        <h2 class="titulo-formulario">Acesso ao Sistema</h2>

        <?php if (!empty($erro)): ?>
            <div class="erro-login"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="campo-grupo">
                <label for="usuario" class="rotulo-principal">Usuário / E-mail:</label>
                <input type="text" id="usuario" name="usuario" class="caixa-entrada" placeholder="Digite seu usuário" required>
            </div>

            <div class="campo-grupo">
                <label for="senha" class="rotulo-principal">Senha:</label>
                <input type="password" id="senha" name="senha" class="caixa-entrada" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="botao-salvar">Entrar no Sistema</button>
        </form>
    </div>

</body>
</html>
