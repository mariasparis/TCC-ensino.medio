<?php
include("config.php");
session_start();

$sucesso = '';
$erro = '';

$usuarioLogado = $_SESSION['user_nome'] ?? '';

$espacos = mysqli_query($conn, "SELECT id, nome, valor FROM espacos ORDER BY nome ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar_id'])) {

    $cancelar_id = intval($_POST['cancelar_id']);

    $usuario_query = mysqli_query($conn, "SELECT id FROM usuarios WHERE nome = '$usuarioLogado'");
    $usuario_data = mysqli_fetch_assoc($usuario_query);
    $usuario_id = $usuario_data['id'] ?? 0;

    mysqli_query($conn, "DELETE FROM reservas WHERE id = $cancelar_id AND usuario_id = $usuario_id");

    if (mysqli_affected_rows($conn) > 0) {
        $sucesso = "Reserva cancelada com sucesso!";
    } else {
        $erro = "Não foi possível cancelar esta reserva.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['cancelar_id'])) {

    $reserva = $_POST['reserva'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $espaco = $_POST['espaco'] ?? '';
    $data = $_POST['data'] ?? '';
    $inicio = $_POST['inicio'] ?? '';
    $fim = $_POST['fim'] ?? '';
    $valor = $_POST['valor'] ?? '';

    $valor = str_replace(['R$', ' '], '', $valor);
    $horaInicio = strtotime($inicio);
    $horaFim = strtotime($fim);

    if($reserva && $usuario && $espaco && $data && $inicio && $fim && $valor){

        if($horaFim <= $horaInicio){
            $erro = "A hora final deve ser maior que a hora inicial.";
        } else {

            $usuario_query = mysqli_query($conn, "SELECT id FROM usuarios WHERE nome = '$usuario'");
            $usuario_data = mysqli_fetch_assoc($usuario_query);

            if($usuario_data && !empty($usuario_data['id'])) {

                $usuario_id = $usuario_data['id'];

                $sql = "INSERT INTO reservas (reserva, usuario_id, espaco, data, inicio, fim, valor, status)
                        VALUES ('$reserva', '$usuario_id', '$espaco', '$data', '$inicio', '$fim', '$valor', 'Pendente')";

                if(mysqli_query($conn, $sql)){
                    $sucesso = "Reserva realizada com sucesso!";
                } else {
                    $erro = "Erro ao realizar reserva: " . mysqli_error($conn);
                }

            } else {
                $erro = "Usuário não encontrado. Verifique se o nome está correto.";
            }
        }

    } else {
        $erro = "Preencha todos os campos.";
    }
}


$reservas_usuario = [];
if($usuarioLogado){
    $usuario_query = mysqli_query($conn, "SELECT id FROM usuarios WHERE nome = '$usuarioLogado'");
    $usuario_data = mysqli_fetch_assoc($usuario_query);
    if($usuario_data){
        $usuario_id = $usuario_data['id'];
        $reserva_query = mysqli_query($conn, "
            SELECT r.*, e.nome AS espaco_nome 
            FROM reservas r
            JOIN espacos e ON r.espaco = e.id
            WHERE r.usuario_id = $usuario_id
            ORDER BY r.data DESC, r.inicio ASC
        ");
        while($row = mysqli_fetch_assoc($reserva_query)){
            $reservas_usuario[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fides - Reservar Espaço</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
@keyframes scaleIn { 
  from {opacity:0; transform:scale(0.95);} 
  to {opacity:1; transform:scale(1);} 
}
.animate-scale-in { animation: scaleIn 0.4s ease forwards; }

/* Modo daltonismo */
.daltonismo {
  --cor-bg: #ffffff !important;
  --cor-bg2: #f2f2f2 !important;
  --cor-text: #000000ff !important;
  --cor-primaria: #eede00ff !important; 
  --cor-secundaria: #0a013fff !important;
}

.daltonismo body,
.daltonismo .bg-gray-100,
.daltonismo .bg-[#F1F5F9],
.daltonismo .bg-gradient-to-br {
  background: var(--cor-bg) !important;
}

.daltonismo h1,
.daltonismo h2,
.daltonismo h3,
.daltonismo p,
.daltonismo span,
.daltonismo div,
.daltonismo a {
  color: var(--cor-text) !important;
}

.daltonismo .bg-blue-900 { background: var(--cor-primaria) !important; }
.daltonismo .bg-red-900 { background: var(--cor-secundaria) !important; }
.daltonismo .hover\:bg-blue-700:hover { background: var(--cor-primaria) !important; }

.daltonismo .bg-red-600,
.daltonismo .bg-red-700:hover {
    background: var(--cor-primaria) !important;
    color: #000 !important;
}
</style>
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800">

<?php include("components/header.php"); ?>

<button id="btnDaltonismo" 
  class="fixed bottom-6 right-6 bg-blue-900 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition"
  style="z-index: 999999; pointer-events: auto;">
  👁️
</button>

<script>
document.getElementById('btnDaltonismo').addEventListener('click', function () {
    document.documentElement.classList.toggle('daltonismo');
});
</script>

<main class="container mx-auto p-6 flex-grow">

<h2 class="text-3xl font-bold text-blue-900 mb-6">Reservar Espaço</h2>

<form method="post" class="bg-white p-6 rounded-lg shadow-md space-y-4" id="reservaForm">

    <input type="text" name="reserva" placeholder="Nome da Reserva (seu nome de usuário e local reservado)" class="w-full p-2 border rounded-lg" required>
    <input type="text" name="usuario" placeholder="Seu nome de Usuário" class="w-full p-2 border rounded-lg" required value="<?= htmlspecialchars($usuarioLogado) ?>">

    <select name="espaco" id="espacoSelect" class="w-full p-2 border rounded-lg" required>
        <option value="" data-valor="0">Selecione um espaço</option>
        <?php
        mysqli_data_seek($espacos, 0);
        while($row = mysqli_fetch_assoc($espacos)):
        ?>
            <option value="<?= $row['id'] ?>" data-valor="<?= $row['valor'] ?>"><?= htmlspecialchars($row['nome']) ?></option>
        <?php endwhile; ?>
    </select>

    <input type="date" name="data" min="<?= date('Y-m-d') ?>" class="w-full p-2 border rounded-lg" required>

    <div class="floating-group">
        <input type="time" name="inicio" id="inicio" placeholder=" " required class="w-full p-2 border rounded-lg" />
        <label>Início</label>
    </div>

    <div class="floating-group">
        <input type="time" name="fim" id="fim" placeholder=" " required class="w-full p-2 border rounded-lg" />
        <label>Fim</label>
    </div>

    <input type="text" name="valor" id="valorInput" placeholder="Valor total" class="w-full p-2 border rounded-lg bg-gray-100" readonly required>

    <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Reservar</button>
</form>

<?php if(!empty($reservas_usuario)): ?>
    <h2 class="text-3xl font-bold text-blue-900 mt-12 mb-6">Minhas Reservas</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <?php foreach($reservas_usuario as $reserva): ?>
            <div class="bg-white rounded-xl shadow-md p-6 space-y-2 max-w-md w-full">
                <h3 class="text-xl font-bold text-blue-900"><?= htmlspecialchars($reserva['reserva']) ?></h3>
                <p><strong>Espaço:</strong> <?= htmlspecialchars($reserva['espaco_nome']) ?></p>
                <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($reserva['data'])) ?></p>
                <p><strong>Horário:</strong> <?= htmlspecialchars($reserva['inicio']) ?> - <?= htmlspecialchars($reserva['fim']) ?></p>
                <p><strong>Valor:</strong> R$ <?= number_format($reserva['valor'],2,",",".") ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($reserva['status']) ?></p>

                <form method="post" onsubmit="return confirmarCancelamento()">
                    <input type="hidden" name="cancelar_id" value="<?= $reserva['id'] ?>">
                    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 mt-3 w-full">
                        Cancelar Reserva
                    </button>
                </form>
            </div>
        <?php endforeach; ?>

    </div>
<?php endif; ?>

</main>

<?php include("components/footer.php"); ?>

<script>
function confirmarCancelamento() {
    return confirm("Tem certeza que deseja cancelar esta reserva?");
}

document.addEventListener('DOMContentLoaded', function() {
    const espacoSelect = document.getElementById('espacoSelect');
    const valorInput = document.getElementById('valorInput');
    const inicioInput = document.getElementById('inicio');
    const fimInput = document.getElementById('fim');

    let valorPorHora = 0;

    espacoSelect.addEventListener('change', function() {
        valorPorHora = parseFloat(this.options[this.selectedIndex].getAttribute('data-valor')) || 0;
        calcularValorTotal();
    });

    function calcularValorTotal() {
        const inicio = inicioInput.value;
        const fim = fimInput.value;
        if(!inicio || !fim || valorPorHora===0){ valorInput.value=""; return; }
        const diff = (new Date(`2000-01-01T${fim}:00`) - new Date(`2000-01-01T${inicio}:00`)) / 3600000;
        if(diff<=0){ valorInput.value=""; return; }
        valorInput.value = "R$ " + (diff*valorPorHora).toFixed(2);
    }

    inicioInput.addEventListener('change', calcularValorTotal);
    fimInput.addEventListener('change', calcularValorTotal);

    <?php if($sucesso): ?>
        Swal.fire({icon:'success',title:'Sucesso!',text:'<?= $sucesso ?>',timer:2000,showConfirmButton:false});
    <?php elseif($erro): ?>
        Swal.fire({icon:'error',title:'Erro!',text:'<?= $erro ?>',timer:3000,showConfirmButton:false});
    <?php endif; ?>
});
</script>

</body>
</html>
