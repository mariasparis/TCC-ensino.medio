<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fides - Direitos Reservados</title>
<script src="https://cdn.tailwindcss.com"></script>

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
</style>
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

<?php include("components/header.php"); ?>

<button id="btnDaltonismo"
  class="fixed bottom-6 right-6 bg-blue-900 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition"
  style="z-index: 999999;">
  👁️
</button>

<script>
document.getElementById("btnDaltonismo").addEventListener("click", () => {
    document.documentElement.classList.toggle("daltonismo");
});
</script>

<main class="container mx-auto p-6 flex-grow text-center">
  <h2 class="text-3xl font-bold text-blue-900 mb-6">Direitos Reservados</h2>

  <div class="bg-white p-6 shadow-md rounded-lg text-gray-700 max-w-3xl mx-auto animate-scale-in">
    <p>
      Todos os direitos sobre o conteúdo, imagens, textos e funcionalidades deste site são reservados à Fides - 2025.
    </p>

    <p class="mt-4">
      Todos os direitos autorais referentes ao trabalho “Sistema Fides” são reservados exclusivamente aos autores 
      Leandra Bernardeli, Luiza de Almeida da Silva, Maria Candida Schunk Paris, Maria Eduarda Silva Ribas e 
      Pedro Henrique de Farias Kruzel.
      <br><br>
      Fica expressamente proibida a reprodução total ou parcial deste material, bem como sua distribuição, 
      divulgação, armazenamento ou utilização para quaisquer fins, sem a prévia e expressa autorização dos autores.
      <br><br>
      O conteúdo deste trabalho é resultado do esforço intelectual, criativo e técnico de seus criadores, 
      estando protegido pela Lei nº 9.610/1998 – Lei de Direitos Autorais.
      <br><br>
      Qualquer citação deve obrigatoriamente mencionar o título “Sistema Fides” e o nome de seus autores.
    </p>
  </div>
</main>

<?php include("components/footer.php"); ?>

</body>
</html>
