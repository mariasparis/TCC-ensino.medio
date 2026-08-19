<?php
include("config.php");
session_start();

$success = false;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = $_POST['nome'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';
    if($nome && $mensagem){
        $stmt = $conn->prepare("INSERT INTO feedbacks (nomeUsuario, mensagem) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $mensagem);
        $stmt->execute();
        $stmt->close();
        $success = true;
    }
}

$feedbacks = mysqli_query($conn, "SELECT * FROM feedbacks ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fides - Feedbacks</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>

<style>

  #btnDaltonismo {
    position: fixed !important;
    z-index: 999999999 !important;
  }

  /* Modo daltonismo */
  .daltonismo {
    --cor-bg: #ffffff !important;
    --cor-bg2: #f2f2f2 !important;
    --cor-text: #000000ff !important;
    --cor-primaria: #eede00ff !important;
    --cor-secundaria: #0a013fff !important;
  }

  .daltonismo body {
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

  .daltonismo .bg-blue-900 {
    background: var(--cor-primaria) !important;
  }

  .daltonismo .hover\:bg-blue-700:hover {
    background: var(--cor-primaria) !important;
  }

  .swiper-container {
    position: relative;
    padding: 0 50px;
  }

  .swiper-button-next,
  .swiper-button-prev {
    width: 40px;
    height: 40px;
    background: white;
    border: 2px solid #1e3a8a;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    top: 50%;
    transform: translateY(-50%);
    color: #1e3a8a;
    transition: all 0.3s ease;
  }

  .swiper-button-next:hover,
  .swiper-button-prev:hover {
    background: #1e3a8a;
    color: white;
  }

  .swiper-button-next { right: 0; }
  .swiper-button-prev { left: 0; }

  .swiper-button-next::after,
  .swiper-button-prev::after {
    font-size: 16px;
    font-weight: bold;
  }

  .swiper-pagination {
    margin-top: 30px !important;
    position: relative;
  }

  .feedback-card {
    height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .feedback-text {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.4;
    max-height: 5.6em;
  }
</style>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen text-gray-800">

<?php include("components/header.php")?>

<button id="btnDaltonismo"
  class="fixed bottom-6 right-6 bg-blue-900 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition"
  style="pointer-events: auto !important;">
  <span style="font-size: 22px;">👁️</span>
</button>

<script>
document.getElementById('btnDaltonismo').addEventListener('click', function () {
    document.documentElement.classList.toggle('daltonismo');
});
</script>

<main class="container mx-auto p-6 flex-grow text-center">
  <h2 class="text-3xl font-bold text-blue-900 mb-6">Deixe seu Feedback</h2>

  <?php if($success): ?>
    <p class="bg-green-200 text-green-800 p-2 rounded mb-4 inline-block">Feedback enviado com sucesso!</p>
  <?php endif; ?>

  <form method="post" class="bg-white p-6 rounded-lg shadow-md mx-auto max-w-md space-y-4 text-left">
    <label class="block font-semibold text-gray-700">Seu Nome</label>
    <input type="text" name="nome" placeholder="Digite seu nome" class="w-full p-2 border rounded-lg" required>
    
    <label class="block font-semibold text-gray-700">Mensagem</label>
    <textarea name="mensagem" placeholder="Escreva seu feedback" class="w-full p-2 border rounded-lg" required></textarea>
    
    <button type="submit" class="w-full bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Enviar Feedback</button>
  </form>

  <section class="bg-white rounded-lg shadow-md p-6 mt-8 container mx-auto mb-6">
    <h3 class="text-xl font-bold mb-6 text-left text-blue-900">Feedbacks</h3>

    <div class="swiper-container">
      <div class="swiper feedbackSwiper">
        <div class="swiper-wrapper">
          <?php while($r = mysqli_fetch_assoc($feedbacks)): 
            $mensagem_limpa = htmlspecialchars($r['mensagem']);
            if(strlen($mensagem_limpa) > 200) {
              $mensagem_limpa = substr($mensagem_limpa, 0, 200) . '...';
            }
          ?>
          <div class="swiper-slide">
            <div class="bg-[#F1F5F9] rounded-xl shadow-md p-6 mx-auto text-left feedback-card">
              <p class="text-gray-700 italic feedback-text">“<?= $mensagem_limpa ?>”</p>
              <div class="mt-auto text-right">
                <span class="font-semibold text-blue-900">— <?= htmlspecialchars($r['nomeUsuario']) ?></span>
              </div>
            </div>
          </div>
          <?php endwhile; ?>
        </div>

        <div class="swiper-pagination mt-8"></div>
      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </section>
</main>

<footer class="mt-auto">
  <?php include("components/footer.php")?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
const swiper = new Swiper(".feedbackSwiper", {
  loop: true,
  autoplay: { delay: 4000 },
  slidesPerView: 1,
  spaceBetween: 20,
  pagination: { el: ".swiper-pagination", clickable: true },
  navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
  breakpoints: {
    640: { slidesPerView: 1 },
    768: { slidesPerView: 2 },
    1024: { slidesPerView: 3 }
  }
});
</script>

</body>
</html>
