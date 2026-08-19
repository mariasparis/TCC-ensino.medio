<?php
include("config.php");
session_start();

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM eventos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$ev = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fides - Detalhes do Evento</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

<?php include("components/header.php"); ?>

<main class="container mx-auto p-6 flex-grow text-center">
  <?php if($ev): ?>
    <div class="bg-white shadow-md rounded-lg p-6 mx-auto max-w-3xl">
      <img src="<?php echo (!empty($ev['imagem']) && file_exists($ev['imagem'])) ? $ev['imagem'] : 'assets/img/logo.png'; ?>" 
           alt="<?php echo htmlspecialchars($ev['nome']); ?>" 
           class="w-full h-64 object-cover rounded mb-4">

      <h2 class="text-2xl font-bold text-blue-900 mb-2">
        <?php echo htmlspecialchars($ev['nome']); ?>
      </h2>

      <p class="text-gray-700 mb-1">
        <?php echo htmlspecialchars(date('d/m/Y', strtotime($ev['data']))); ?>
      </p>

      <p class="text-gray-700 mb-1">
        <strong>Local:</strong> <?php echo htmlspecialchars($ev['localizacao']); ?>
      </p>

      <p class="text-gray-700">
        <strong>Descrição:</strong> <?php echo htmlspecialchars($ev['descricao']); ?>
      </p>
    </div>
  <?php else: ?>
    <p class="text-red-600 font-bold">Evento não encontrado.</p>
  <?php endif; ?>
</main>

<?php include("components/footer.php"); ?>

</body>
</html>
