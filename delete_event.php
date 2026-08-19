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

// Buscar evento
$stmt = $conn->prepare("SELECT * FROM eventos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$evento = $result->fetch_assoc();

if (!$evento) {
    header("Location: admin.php?error=Evento não encontrado");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Evento</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-md rounded-lg p-6 w-full max-w-lg">
    <h2 class="text-2xl font-bold mb-4">Editar Evento</h2>

    <form action="update_event.php" method="post" enctype="multipart/form-data" class="space-y-4">

        <input type="hidden" name="id" value="<?= $evento['id'] ?>">

        <label class="block font-semibold">Título do Evento:</label>
        <input type="text" 
               name="titulo" 
               value="<?= htmlspecialchars($evento['titulo']) ?>" 
               class="w-full p-2 border rounded-lg" 
               required>

        <label class="block font-semibold">Descrição:</label>
        <textarea name="descricao" 
                  class="w-full p-2 border rounded-lg h-24"
                  required><?= htmlspecialchars($evento['descricao']) ?></textarea>

        <label class="block font-semibold">Data:</label>
        <input type="date" 
               name="data_evento" 
               value="<?= $evento['data_evento'] ?>" 
               class="w-full p-2 border rounded-lg"
               required>

        <label class="block font-semibold">Valor:</label>
        <input type="number" 
               name="valor" 
               value="<?= $evento['valor'] ?>" 
               class="w-full p-2 border rounded-lg" 
               step="0.01" 
               required>

        <label class="block font-semibold">Imagem atual:</label>
        <?php if (!empty($evento['img'])): ?>
            <img src="<?= $evento['img'] ?>" 
                 alt="Imagem do evento" 
                 class="w-32 h-20 object-cover rounded mb-2">
        <?php else: ?>
            <p class="text-gray-500 text-sm">Nenhuma imagem cadastrada</p>
        <?php endif; ?>

        <label class="block font-semibold">Alterar imagem:</label>
        <input type="file" 
               name="img" 
               accept="image/*" 
               class="w-full p-2 border rounded-lg">

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
