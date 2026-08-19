<?php
include("config.php");
session_start();

$isAdmin = $_SESSION['isAdmin'] ?? false;
if (!$isAdmin) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do espaço não informado.");
}

$id = intval($_GET['id']);

// Verifica se o espaço existe
$sql = "SELECT * FROM espacos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Espaço não encontrado.");
}

$espaco = $result->fetch_assoc();

// Excluir imagem se existir
if (!empty($espaco['img']) && file_exists($espaco['img'])) {
    unlink($espaco['img']);
}

// Excluir o espaço
$sql = "DELETE FROM espacos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>
        alert('Espaço excluído com sucesso!');
        window.location.href = 'admin.php';
    </script>";
} else {
    echo "<script>
        alert('Erro ao excluir espaço: " . addslashes($conn->error) . "');
        window.location.href = 'admin.php';
    </script>";
}
?>
