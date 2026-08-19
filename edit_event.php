<?php
include("config.php");
$erro = '';
$sucesso = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do evento não informado.");
}

$id = intval($_GET['id']);

// Buscar o evento no banco
$sql = "SELECT * FROM eventos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$evento = $result->fetch_assoc();

if (!$evento) {
    die("Evento não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $data = trim($_POST['data'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $localizacao = trim($_POST['localizacao'] ?? '');
    $imagem = $evento['imagem']; 

    if (!empty($_FILES['imagem']['name'])) {
        $pasta = "uploads/";
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $nomeArquivo = time() . "_" . basename($_FILES['imagem']['name']);
        $caminhoCompleto = $pasta . $nomeArquivo;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
            if (!empty($evento['imagem']) && file_exists($evento['imagem'])) {
                unlink($evento['imagem']);
            }
            $imagem = $caminhoCompleto;
        } else {
            $erro = "Erro ao enviar a nova imagem.";
        }
    }

    if ($nome && $data && $descricao && $localizacao) {
        $sql = "UPDATE eventos SET nome = ?, data = ?, descricao = ?, localizacao = ?, imagem = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nome, $data, $descricao, $localizacao, $imagem, $id);

        if ($stmt->execute()) {
            $sucesso = "Evento atualizado com sucesso!";
            $evento['nome'] = $nome;
            $evento['data'] = $data;
            $evento['descricao'] = $descricao;
            $evento['localizacao'] = $localizacao;
            $evento['imagem'] = $imagem;
        } else {
            $erro = "Erro ao atualizar evento: " . $conn->error;
        }
    } else {
        $erro = "Preencha todos os campos obrigatórios.";
    }
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
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800">

<main class="min-h-screen flex flex-col justify-center items-center p-4">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Editar Evento</h2>

        <?php if($erro): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?php echo $erro; ?></div>
        <?php endif; ?>

        <?php if($sucesso): ?>
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700">Nome</label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($evento['nome']); ?>" class="w-full p-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Data</label>
                <input type="date" name="data" value="<?php echo htmlspecialchars($evento['data']); ?>" class="w-full p-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Descrição</label>
                <textarea name="descricao" class="w-full p-2 border rounded-lg" required><?php echo htmlspecialchars($evento['descricao']); ?></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Localização</label>
                <input type="text" name="localizacao" value="<?php echo htmlspecialchars($evento['localizacao']); ?>" class="w-full p-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Imagem Atual</label><br>
                <img src="<?php echo htmlspecialchars($evento['imagem']); ?>" alt="Imagem do evento" class="w-40 h-24 object-cover rounded mb-2">
                <input type="file" name="imagem" accept="image/*" class="w-full p-2 border rounded-lg">
            </div>

            <button type="submit" class="w-full bg-blue-900 text-white p-2 rounded-lg hover:bg-blue-700">Salvar Alterações</button>
        </form>

        <p class="text-center mt-4">
            <a href="admin.php" class="text-blue-900 hover:underline">Voltar</a>
        </p>
    </div>
</main>

</body>
</html>
