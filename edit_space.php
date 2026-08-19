<?php
include("config.php");
session_start();

$isAdmin = $_SESSION['isAdmin'] ?? false;
if (!$isAdmin) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? '';
if (!$id) {
    header("Location: admin.php?error=ID inválido");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM espacos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$espaco = $result->fetch_assoc();

if (!$espaco) {
    header("Location: admin.php?error=Espaço não encontrado");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Espaço</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-height-screen">

<div class="bg-white shadow-md rounded-lg p-6 w-full max-w-lg">
  <h2 class="text-2xl font-bold mb-4">Editar Espaço</h2>

  <form action="update_space.php" method="post" enctype="multipart/form-data" class="space-y-4">
    <input type="hidden" name="id" value="<?= $espaco['id'] ?>">

    <label class="block font-semibold">Nome do espaço:</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($espaco['nome']) ?>" 
           class="w-full p-2 border rounded-lg" required>

    <label class="block font-semibold">Valor:</label>
    <input type="number" name="valor" value="<?= $espaco['valor'] ?>" 
           class="w-full p-2 border rounded-lg" step="0.01" required>

    <label class="block font-semibold">Imagem atual:</label>
    <img src="<?= $espaco['img'] ?>" alt="<?= htmlspecialchars($espaco['nome']) ?>"
         class="w-32 h-20 object-cover mb-2 rounded">

    <label class="block font-semibold">Alterar imagem:</label>
    <input type="file" name="img" accept="image/*" class="w-full p-2 border rounded-lg">

    <div class="flex gap-2">
        <button type="submit" 
                class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-700">
            Salvar Alterações
        </button>

        <a href="admin.php" 
           class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-400">
           Cancelar
        </a>
    </div>
  </form>
</div>

</body>
</html>
