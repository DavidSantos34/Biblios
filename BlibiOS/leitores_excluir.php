<?php
require_once 'conexao.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Opcional: Busca o caminho da foto para apagar o arquivo físico antes de excluir o registro
    $sql_foto = "SELECT foto_leitor FROM leitor WHERE id = ?";
    $stmt_foto = mysqli_prepare($conexao, $sql_foto);
    mysqli_stmt_bind_param($stmt_foto, "i", $id);
    mysqli_stmt_execute($stmt_foto);
    $res = mysqli_stmt_get_result($stmt_foto);
    $leitor = mysqli_fetch_assoc($res);

    if ($leitor && !empty($leitor['foto_leitor']) && file_exists($leitor['foto_leitor'])) {
        unlink($leitor['foto_leitor']);
    }

    // Executa o DELETE
    $sql = "DELETE FROM leitor WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: leitor.php?sucesso=1");
        exit;
    } else {
        echo "Erro ao excluir leitor: " . mysqli_error($conexao);
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: leitor.php");
    exit;
}
?>
