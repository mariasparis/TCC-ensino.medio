<?php
include("config.php");

// Autenticação simples
session_start();
$isAdmin = $_SESSION['isAdmin'] ?? false;
if(!$isAdmin){
    header("Location: login.php");
    exit();
}

// Buscar dados
$eventos = mysqli_query($conn, "SELECT * FROM eventos ORDER BY data DESC");
$espacos = mysqli_query($conn, "SELECT * FROM espacos");

// Buscar reservas com JOIN correto usando usuario_id
$reservas_query = "
    SELECT 
        r.*, 
        e.nome AS nome_espaco,
        u.id AS usuario_id,
        u.nome AS nome_usuario,
        u.email AS email_usuario
    FROM reservas r
    LEFT JOIN espacos e ON r.espaco = e.id
    LEFT JOIN usuarios u ON r.usuario_id = u.id
    ORDER BY r.data DESC
";

$reservas = mysqli_query($conn, $reservas_query);

// Mensagens via GET
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Estado inicial da aba ativa
$activeTab = $_GET['tab'] ?? 'eventos';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fides - Administrador</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .tab-button {
        transition: all 0.3s ease;
    }
    .tab-button.active {
        background-color: #1e40af;
        color: white;
        font-weight: 600;
    }
    .tab-button:not(.active) {
        background-color: #1e3a8a;
        color: white;
        opacity: 0.8;
    }
    .tab-button:not(.active):hover {
        background-color: #1e40af;
        opacity: 1;
    }
    .tab-content {
        transition: opacity 0.3s ease;
    }
</style>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

<?php include("components/header.php"); ?>

<main class="container mx-auto p-6 flex-grow">
  <h2 class="text-3xl font-bold text-blue-900 mb-6">Painel do Administrador</h2>

  <div class="flex items-center justify-between mb-6">
  <!-- Botões de abas -->
  <div class="flex space-x-4">
    <button 
        onclick="showTab('eventos')" 
        class="tab-button px-4 py-2 rounded hover:bg-blue-700 <?= $activeTab === 'eventos' ? 'active' : '' ?>"
        id="tab-eventos">
        Eventos
    </button>
    <button 
        onclick="showTab('reservas')" 
        class="tab-button px-4 py-2 rounded hover:bg-blue-700 <?= $activeTab === 'reservas' ? 'active' : '' ?>"
        id="tab-reservas">
        Reservas
    </button>
    <button 
        onclick="showTab('espacos')" 
        class="tab-button px-4 py-2 rounded hover:bg-blue-700 <?= $activeTab === 'espacos' ? 'active' : '' ?>"
        id="tab-espacos">
        Espaços
    </button>
  </div>

  <!-- Botão de logout à direita -->
  <a href="logout.php" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">
    Logout
  </a>
</div>


  <!-- Aba EVENTOS -->
  <div id="eventos" class="tab-content bg-white rounded-lg shadow-md p-6 <?= $activeTab !== 'eventos' ? 'hidden' : '' ?>">
    <h3 class="text-xl font-bold mb-4">Criar Evento</h3>
    <form action="create_event.php" method="post" enctype="multipart/form-data" class="space-y-4 mb-8">
      <input type="text" name="nome" placeholder="Nome do Evento" class="w-full p-2 border rounded-lg" required>
      <input type="date" name="data" class="w-full p-2 border rounded-lg" required>
      <textarea name="desc" placeholder="Descrição" class="w-full p-2 border rounded-lg" required></textarea>
      <input type="text" name="local" placeholder="Localização" class="w-full p-2 border rounded-lg" required>
      <input type="file" name="img" accept="image/*" class="w-full p-2 border rounded-lg" required>
      <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-700">Salvar Evento</button>
    </form>

    <!-- Lista de eventos -->
    <h3 class="text-xl font-bold mb-4">Eventos Cadastrados</h3>
    <table class="min-w-full border border-gray-300">
      <thead class="bg-gray-200">
        <tr>
          <th class="py-2 px-4 border-b">Nome</th>
          <th class="py-2 px-4 border-b">Data</th>
          <th class="py-2 px-4 border-b">Local</th>
          <th class="py-2 px-4 border-b">Imagem</th>
          <th class="py-2 px-4 border-b">Ações</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <?php while($ev = mysqli_fetch_assoc($eventos)): ?>
        <tr>
          <td class="py-2 px-4 border-b"><?= htmlspecialchars($ev['nome']) ?></td>
          <td class="py-2 px-4 border-b"><?= htmlspecialchars($ev['data']) ?></td>
          <td class="py-2 px-4 border-b"><?= htmlspecialchars($ev['localizacao']) ?></td>
          <td class="py-2 px-4 border-b">
            <img src="<?= htmlspecialchars($ev['imagem']) ?>" alt="<?= htmlspecialchars($ev['nome']) ?>" class="w-32 h-20 object-cover rounded">
          </td>
          <td class="py-2 px-4 border-b space-x-2">
            <a href="edit_event.php?id=<?= $ev['id'] ?>" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-400">Editar</a>
            <a href="#" onclick="confirmDeleteEvent(<?= $ev['id'] ?>)" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500">Excluir</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Aba RESERVAS -->
  <div id="reservas" class="tab-content bg-white rounded-lg shadow-md p-6 <?= $activeTab !== 'reservas' ? 'hidden' : '' ?>">
    <h3 class="text-xl font-bold mb-4">Reservas / Aluguéis</h3>
    <table class="min-w-full border border-gray-300">
      <thead class="bg-gray-200">
        <tr>
          <th class="py-2 px-4 border-b">Nome do Usuário</th>
          <th class="py-2 px-4 border-b">Espaço</th>
          <th class="py-2 px-4 border-b">Data</th>
          <th class="py-2 px-4 border-b">Valor</th>
          <th class="py-2 px-4 border-b">Ações</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <?php 
        mysqli_data_seek($reservas, 0);
        while($r = mysqli_fetch_assoc($reservas)): 
        ?>
        <tr>
          <td class="py-2 px-4 border-b">
            <?php if (!empty($r['usuario_id'])): ?>
              <!-- Link para usuario_info.php -->
              <a href="usuario_info.php?id=<?= $r['usuario_id'] ?>" 
                 class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                 <?= htmlspecialchars($r['nome_usuario']) ?>
              </a>
            <?php else: ?>
              <span class="text-gray-500">
                Usuário não encontrado
              </span>
            <?php endif; ?>
          </td>
          <td class="py-2 px-4 border-b"><?= htmlspecialchars($r['nome_espaco'] ?? 'Espaço não definido') ?></td>
          <td class="py-2 px-4 border-b"><?= htmlspecialchars($r['data']) ?></td>
          <td class="py-2 px-4 border-b">R$ <?= number_format($r['valor'], 2, ',', '.') ?></td>
          <td class="py-2 px-4 border-b space-x-2">
            <a href="edit_reserva.php?id=<?= $r['id'] ?>" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-400">Editar</a>
            <a href="#" onclick="confirmDeleteReserva(<?= $r['id'] ?>)" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500">Excluir</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Aba ESPAÇOS -->
  <div id="espacos" class="tab-content bg-white rounded-lg shadow-md p-6 <?= $activeTab !== 'espacos' ? 'hidden' : '' ?>">
    <h3 class="text-xl font-bold mb-4">Gerenciar Espaços</h3>
    <form action="add_space.php" method="post" enctype="multipart/form-data" class="space-y-4 mb-6">
  <input type="text" name="nome" placeholder="Nome do Espaço" class="w-full p-2 border rounded-lg" required>

  <input type="number" name="valor" placeholder="Valor (R$)" class="w-full p-2 border rounded-lg" step="0.01" required>

  <input type="file" name="img" accept="image/*" class="w-full p-2 border rounded-lg" required>

  <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-700">Adicionar Espaço</button>
</form>


    <table class="min-w-full border border-gray-300">
      <thead class="bg-gray-200">
        <tr>
          <th class="py-2 px-4 border-b">Espaço</th>
          <th class="py-2 px-4 border-b">Imagem</th>
          <th class="py-2 px-4 border-b">Valor</th>
          <th class="py-2 px-4 border-b">Ações</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <?php while($e = mysqli_fetch_assoc($espacos)): ?>
        <tr>
          <td class="py-2 px-4 border-b"><?= htmlspecialchars($e['nome']) ?></td>
          <td class="py-2 px-4 border-b">
            <img src="<?= htmlspecialchars($e['img']) ?>" alt="<?= htmlspecialchars($e['nome']) ?>" class="w-32 h-20 object-cover rounded">
          </td>
          <td class="py-2 px-4 border-b">R$ <?= number_format($e['valor'], 2, ',', '.') ?></td>
          <td class="py-2 px-4 border-b space-x-2">
            <a href="edit_space.php?id=<?= $e['id'] ?>" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-400">Editar</a>
            <a href="#" onclick="confirmDeleteSpace(<?= $e['id'] ?>)" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500">Excluir</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include("components/footer.php"); ?>

<script>
// Estado da aba ativa
let activeTab = "<?= $activeTab ?>";

function showTab(tab) {
  // Remove a classe active de todos os botões
  document.querySelectorAll('.tab-button').forEach(button => {
    button.classList.remove('active');
  });
  
  // Esconde todos os conteúdos
  document.querySelectorAll('.tab-content').forEach(content => {
    content.classList.add('hidden');
  });
  
  // Ativa o botão clicado
  document.getElementById(`tab-${tab}`).classList.add('active');
  
  // Mostra o conteúdo correspondente
  document.getElementById(tab).classList.remove('hidden');
  
  // Atualiza o estado
  activeTab = tab;
  
  // Atualiza a URL sem recarregar a página (opcional)
  const url = new URL(window.location);
  url.searchParams.set('tab', tab);
  window.history.replaceState({}, '', url);
}

// SweetAlert para sucesso/erro
document.addEventListener('DOMContentLoaded', () => {
  const success = "<?= $success ?>";
  const error = "<?= $error ?>";

  if (success) Swal.fire({ icon: 'success', title: 'Sucesso!', text: success, timer: 2500, showConfirmButton: false });
  if (error) Swal.fire({ icon: 'error', title: 'Erro!', text: error, timer: 2500, showConfirmButton: false });
});

// Confirmar exclusão de espaço
function confirmDeleteSpace(id) {
  Swal.fire({
    title: 'Tem certeza?',
    text: "Essa ação não pode ser desfeita!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sim, excluir!',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `delete_space.php?id=${id}`;
    }
  });
}

// Confirmar exclusão de evento
function confirmDeleteEvent(id) {
  Swal.fire({
    title: 'Tem certeza?',
    text: "Essa ação não pode ser desfeita!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sim, excluir!',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `delete_event.php?id=${id}`;
    }
  });
}

// Confirmar exclusão de reserva
function confirmDeleteReserva(id) {
  Swal.fire({
    title: 'Tem certeza?',
    text: "Essa reserva será excluída permanentemente!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sim, excluir!',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `delete_reserva.php?id=${id}`;
    }
  });
}
</script>
</body>
</html>