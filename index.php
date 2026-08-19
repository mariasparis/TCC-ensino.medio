<?php 
include("config.php");
session_start();

$eventos = $conn->query("SELECT * FROM eventos ORDER BY data DESC");
$espacos = $conn->query("SELECT * FROM espacos ORDER BY id DESC");

$feedbacks = $conn->query("SELECT * FROM feedbacks");
if (!$feedbacks) $feedbacks = [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fides - Home</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

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
  --cor-text: #f8f5f5ff !important;
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


<!-- Carrossel -->
<section class="mt-6 px-4 sm:px-6 lg:px-8">
  <div class="swiper topSwiper rounded-xl overflow-hidden shadow-lg">
    <div class="swiper-wrapper">
      <?php
      $carouselImages = ['uploads/carr1.png','uploads/carr2.jpg','uploads/carr3.jpg'];
      foreach($carouselImages as $img): ?>
        <div class="swiper-slide">
          <img src="<?= $img ?>" alt="Banner" class="w-full h-full md:h-96 object-cover">
        </div>
      <?php endforeach; ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination mt-4"></div>
  </div>
</section>

<!-- Grid de Eventos -->
<main class="flex-1 py-16 px-4 sm:px-6 lg:px-8">
  <h1 class="text-3xl font-bold text-blue-900 mb-8">Eventos Disponíveis</h1>
  <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
    <?php while($ev = $eventos->fetch_assoc()): ?>
      <div class="group overflow-hidden rounded-xl bg-[#F1F5F9] shadow-md transition-all duration-300 hover:shadow-lg animate-scale-in">
        <div class="relative overflow-hidden">
          <img src="<?= (!empty($ev['imagem']) && file_exists($ev['imagem'])) ? $ev['imagem'] : 'assets/img/logo.png' ?>" 
               alt="<?= htmlspecialchars($ev['nome']) ?>" 
               class="w-full h-64 object-cover rounded mb-4">
          <div class="absolute top-3 left-3">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-white">Evento</span>
          </div>
        </div>
        <div class="p-6 text-left space-y-3">
          <h3 class="text-xl font-bold transition-colors duration-300 group-hover:text-blue-900"><?= htmlspecialchars($ev['nome']) ?></h3>

          <div class="flex items-center text-gray-800 font-semibold">
            <?= date("d/m/Y", strtotime($ev['data'])) ?>
          </div>

          <p class="text-gray-800 font-semibold"><?= htmlspecialchars($ev['localizacao'] ?? "Participe deste evento especial!") ?></p>
        </div>
        <div class="p-6 pt-0">
          <a href="event.php?id=<?= $ev['id'] ?>" class="block w-full text-center bg-blue-900 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:shadow-md">
            Ver Detalhes
          </a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Grid de Espaços -->
  <h1 class="text-3xl font-bold text-blue-900 mt-16 mb-8">Espaços Disponíveis</h1>
  <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
    <?php while($esp = $espacos->fetch_assoc()): ?>
      <div class="group overflow-hidden rounded-xl bg-[#F1F5F9] shadow-md transition-all duration-300 hover:shadow-lg animate-scale-in">
        <div class="relative overflow-hidden">
          <img src="<?= (!empty($esp['img']) && file_exists($esp['img'])) ? $esp['img'] : 'assets/img/logo.png' ?>" 
               alt="<?= htmlspecialchars($esp['nome']) ?>" 
               class="w-full h-64 object-cover rounded mb-4">
          <div class="absolute top-3 left-3">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-white">Espaço</span>
          </div>
        </div>
        <div class="p-6 text-left space-y-3">
          <h3 class="text-xl font-bold transition-colors duration-300 group-hover:text-blue-900"><?= htmlspecialchars($esp['nome']) ?></h3>
          <p class="text-sm leading-relaxed text-gray-700"><?= htmlspecialchars($esp['descricao'] ?? "") ?></p>
          <p class="text-gray-800 font-semibold">Valor por hora: R$ <?= number_format($esp['valor'],2,',','.') ?></p>
        </div>
        <div class="p-6 pt-0">
          <a href="reservar.php?id=<?= $esp['id'] ?>" class="block w-full text-center bg-blue-900 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:shadow-md">
            Reservar Espaço
          </a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</main>

<?php include('components/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
const swiperTop = new Swiper(".topSwiper", {
  loop: true,
  autoplay: { delay: 4000 },
  navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
  pagination: { el: ".swiper-pagination", clickable: true },
});
</script>

</body>
</html>
