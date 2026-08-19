<?php
include("config.php");
$erro = '';
$sucesso = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da reserva não informado.");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM reservas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$reserva = $res->fetch_assoc();

if (!$reserva) {
    die("Reserva não encontrada.");
}

$espacos_sql = "SELECT id, nome FROM espacos ORDER BY nome ASC";
$espacos_res = $conn->query($espacos_sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeReserva = $_POST['reserva'] ?? '';
    $espaco = $_POST['espaco'] ?? '';
    $data = $_POST['data'] ?? '';
    $inicio = $_POST['inicio'] ?? '';
    $fim = $_POST['fim'] ?? '';
    $valor = $_POST['valor'] ?? '';
    
    if ($nomeReserva && $espaco && $data && $inicio && $fim && $valor) {
        $sql = "UPDATE reservas SET reserva = ?, espaco = ?, data = ?, inicio = ?, fim = ?, valor = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssdi", $nomeReserva, $espaco, $data, $inicio, $fim, $valor, $id);

        if ($stmt->execute()) {
            $sucesso = "Reserva atualizada com sucesso!";
            $reserva['reserva'] = $nomeReserva;
            $reserva['espaco'] = $espaco;
            $reserva['data'] = $data;
            $reserva['inicio'] = $inicio;
            $reserva['fim'] = $fim;
            $reserva['valor'] = $valor;
        } else {
            $erro = "Erro ao atualizar reserva: " . $conn->error;
        }
    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Reserva</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800">

<main class="min-h-screen flex flex-col justify-center items-center p-4">
  <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-lg">
    <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Editar Reserva</h2>

    <?php if($erro): ?>
      <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= $erro ?></div>
    <?php endif; ?>

    <?php if($sucesso): ?>
      <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= $sucesso ?></div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
      <div>
        <label class="block text-gray-700">Nome da Reserva</label>
        <input type="text" name="reserva" value="<?= htmlspecialchars($reserva['reserva']) ?>" class="w-full p-2 border rounded-lg" required>
      </div>

      <div>
        <label class="block text-gray-700">Espaço</label>
        <select name="espaco" class="w-full p-2 border rounded-lg" required>
          <option value="">Selecione um espaço</option>
          <?php while($e = $espacos_res->fetch_assoc()): ?>
            <option value="<?= $e['id'] ?>" <?= $reserva['espaco'] == $e['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($e['nome']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div>
        <label class="block text-gray-700">Data</label>
        <input type="date" name="data" value="<?= htmlspecialchars($reserva['data']) ?>" class="w-full p-2 border rounded-lg" required>
      </div>

      <div>
        <label class="block text-gray-700">Horário de Início</label>
        <input type="time" name="inicio" value="<?= htmlspecialchars(substr($reserva['inicio'], 0, 5)) ?>" class="w-full p-2 border rounded-lg" required>
      </div>

      <div>
        <label class="block text-gray-700">Horário de Fim</label>
        <input type="time" name="fim" value="<?= htmlspecialchars(substr($reserva['fim'], 0, 5)) ?>" class="w-full p-2 border rounded-lg" required>
      </div>

      <div>
        <label class="block text-gray-700">Valor (R$)</label>
        <input type="number" name="valor" value="<?= htmlspecialchars($reserva['valor']) ?>" step="0.01" class="w-full p-2 border rounded-lg" required>
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
