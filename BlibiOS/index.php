<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>BibliOS - Painel Inicial</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="dashboard-corpo">
        
        <h1 class="titulo-sistema">BibliOS</h1>
        <p class="subtitulo-sistema">Painel de Gestão da Biblioteca</p>

        <!-- Grade de botões modernos com ícones -->
        <div class="dashboard-grid">
            
            <!-- Atalho Livros -->
            <a href="cadastro-livros.html" class="menu-card">
                <span class="icone-card">📚</span>
                <span class="texto-card">Livros</span>
            </a>

            <!-- Atalho Leitores -->
            <a href="cadastro-leitores.html" class="menu-card">
                <span class="icone-card">👥</span>
                <span class="texto-card">Leitores</span>
            </a>

            <!-- Atalho Empréstimos/Devoluções -->
            <a href="devolucao-livros.html" class="menu-card">
                <span class="icone-card">🔄</span>
                <span class="texto-card">Empréstimos</span>
            </a>

        </div>

        <div class="rodape-usuario">
            <p>Conectado como: <strong><?php echo $_SESSION['usuario_nome']; ?></strong></p>
            <br>
            <a href="logout.php" style="color: #C62828; font-weight: bold; text-decoration: none;">🚪 Sair do Sistema</a>
        </div>

    </div>

</body>
</html>
