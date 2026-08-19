<?php
// sobre.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fides - Sobre Nós</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
  /* BOTÃO ACESSIBILIDADE SEMPRE VISÍVEL */
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

  .daltonismo .bg-blue-900,
  .daltonismo .bg-blue-700 {
    background: var(--cor-primaria) !important;
  }

  .daltonismo .hover\:bg-blue-700:hover,
  .daltonismo .hover\:bg-blue-300:hover {
    background: var(--cor-primaria) !important;
  }
</style>

</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

<?php include("components/header.php"); ?>

<!-- 🟡 BOTÃO DE DALTONISMO (MESMO DA INDEX) -->
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
  <h2 class="text-3xl font-bold text-blue-900 mb-6">Sobre Nós</h2>

  <!-- Seção 1: Salesianos -->
  <section class="mb-10">
    <h3 class="text-2xl font-semibold text-blue-900 mb-4">Salesianos</h3>

    <!-- Botões de navegação - Parte 1 -->
    <div class="flex justify-center space-x-4 mb-6">
      <button onclick="showSection('historia')" id="btn-historia" class="tab-btn bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-300 transition">Nossa História</button>
      <button onclick="showSection('missao')" id="btn-missao" class="tab-btn bg-gray-200 text-blue-900 px-4 py-2 rounded hover:bg-blue-300 transition">Missão e Valores</button>
      <button onclick="showSection('equipe')" id="btn-equipe" class="tab-btn bg-gray-200 text-blue-900 px-4 py-2 rounded hover:bg-blue-300 transition">Nossas Obras</button>
    </div>

    <!-- Conteúdo das seções Salesianos -->
    <div id="historia" class="section bg-white p-6 shadow-md rounded-lg mx-auto max-w-3xl">
      <p class="text-gray-700 leading-relaxed text-justify">
        A <span class="font-bold text-blue-900">Inspiração</span> de nossa missão nasceu do coração de São João Bosco, conhecido como Dom Bosco.
        Ao visitar presídios e caminhar pelas ruas de Turim, Dom Bosco se deparou com inúmeros jovens sem oportunidades de estudo ou trabalho, que acabavam caindo na criminalidade. Sensibilizado com essa realidade, decidiu agir.
        Em 1859, fundou em Turim a Sociedade de São Francisco de Sales, cujos membros ficaram conhecidos como Salesianos de Dom Bosco.
        Mais tarde, percebendo a necessidade de também oferecer formação e acolhimento às meninas, fundou em 1872, junto com Santa Maria Domingas Mazzarello, o Instituto das Filhas de Maria Auxiliadora (Irmãs Salesianas).
        A Ordem Salesiana foi oficialmente aprovada pelo Papa Pio IX em 1874, consolidando o carisma e a missão que até hoje inspiram obras em todo o mundo.
      </p>
    </div>

    <div id="missao" class="section bg-white p-6 shadow-md rounded-lg mx-auto max-w-3xl hidden">
      <p class="text-gray-700 leading-relaxed text-justify">
        Nosso carisma é fundamentado na Caridade Pastoral, ou seja, o amor que educa e transforma.
        Inspirados por Dom Bosco, buscamos “formar bons cristãos e honestos cidadãos”, por meio da educação e da evangelização, especialmente entre os jovens mais pobres.
        Vivemos essa missão com base no Sistema Preventivo, uma pedagogia que une razão, religião e amor educativo, valorizando cada jovem como protagonista de sua própria história e agente de transformação na sociedade.
      </p>
    </div>

    <div id="equipe" class="section bg-white p-6 shadow-md rounded-lg mx-auto max-w-3xl hidden">
      <p class="text-gray-700 leading-relaxed text-justify">
        As obras salesianas se espalham pelo mundo, abrangendo diversas iniciativas voltadas à educação, evangelização e formação humana.
        Entre elas, destacam-se:
        <br>• Escolas e centros de formação profissional, que preparam jovens para o futuro;
        <br>• Obras sociais e missionárias, que acolhem e promovem o desenvolvimento integral das pessoas;
        <br>• Projetos de contraturno escolar, que oferecem atividades educativas, esportivas e culturais;
        <br>• Ações pastorais em paróquias e comunidades, fortalecendo a fé e o sentido de pertencimento;
        <br>• Instituições de ensino superior, que unem conhecimento, valores e compromisso social.
        <br><br>
        Todas essas iniciativas têm um mesmo propósito: educar com amor, fé e esperança, formando gerações capazes de transformar o mundo com sabedoria e solidariedade.
      </p>
    </div>
  </section>

  <!-- Seção 2: Sobre os Desenvolvedores -->
  <section>
    <h3 class="text-2xl font-semibold text-blue-900 mb-4">Equipe Synaptech</h3>

    <!-- Botão e conteúdo "Sobre Nós" -->
    <div class="flex justify-center mb-6">
      <button onclick="showSection('sobre')" id="btn-sobre" class="tab-btn bg-gray-200 text-blue-900 px-4 py-2 rounded hover:bg-blue-300 transition">Sobre Nós</button>
    </div>

    <div id="sobre" class="section bg-white p-6 shadow-md rounded-lg mx-auto max-w-4xl hidden">
      <p class="text-gray-700 leading-relaxed text-justify mb-8">
        Somos cinco alunos da ETEC Paulino Botelho, do curso técnico de Informática para Internet, unidos não apenas pelo aprendizado tecnológico, mas também por um propósito maior. Este site é o resultado do nosso Trabalho de Conclusão de Curso (TCC) — um projeto desenvolvido com dedicação, criatividade e fé.
        <br><br>
        Escolhemos esse tema porque acreditamos que a tecnologia pode ser um instrumento para espalhar valores, conectar pessoas e fortalecer crenças. Cada linha de código aqui representa não só nosso esforço técnico, mas também nossa fé e convicção pessoal, que serviram de inspiração em cada etapa do desenvolvimento.
        <br><br>
        Nosso objetivo é demonstrar como a informática pode ser usada de forma positiva, promovendo mensagens que inspiram, confortam e unem. Este projeto reflete quem somos: estudantes determinados, sonhadores e guiados pela crença de que fé e conhecimento caminham juntos.
      </p>

      <div class="flex justify-center mt-10">
        <div class="bg-white shadow-lg rounded-xl p-5 w-full max-w-6xl">
          <img 
            src="uploads/ambiente1.webp" 
            alt="Equipe Synaptech" 
            class="w-full h-[450px] rounded-lg object-cover"
          >
          <p class="mt-5 text-blue-900 font-semibold text-xl text-center">
            Equipe Synaptech – Desenvolvedores do Projeto
          </p>
        </div>
      </div>

    </div>
  </section>
</main>

<?php include("components/footer.php"); ?>

<script>
  function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(sec => sec.classList.add('hidden'));

    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.classList.remove('bg-blue-700', 'text-white');
      btn.classList.add('bg-gray-200', 'text-blue-900');
    });

    document.getElementById(sectionId).classList.remove('hidden');

    const activeBtn = document.getElementById('btn-' + sectionId);
    activeBtn.classList.remove('bg-gray-200', 'text-blue-900');
    activeBtn.classList.add('bg-blue-700', 'text-white');
  }
</script>

</body>
</html>
