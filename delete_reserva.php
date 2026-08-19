<?php
include("config.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin.php?error=ID da reserva não informado.");
    exit;
}

$id = intval($_GET['id']);

$sql = "DELETE FROM reservas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php?success=Reserva excluída com sucesso!");
} else {
    header("Location: admin.php?error=Erro ao excluir reserva: " . urlencode($conn->error));
}
exit;
?>
