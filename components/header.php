<header class="sticky top-0 z-50 w-full border-b border-border bg-blue-900 backdrop-blur supports-[backdrop-filter]:bg-blue-1000/60"> 
  <div class="container mx-auto flex justify-between items-center">

    <!-- Logo -->
    <img src="uploads/logosalesianos.png" alt="Logo" class="h-18 w-auto max-h-24">

    <!-- Menu Desktop (só aparece em md pra cima) -->
    <nav class="hidden md:flex flex-1 justify-center gap-6">
      <a href="index.php" class="text-white hover:border-b-2 hover:border-white">Início</a>
      <a href="about.php" class="text-white hover:border-b-2 hover:border-white">Sobre Nós</a>
      <a href="reservar.php" class="text-white hover:border-b-2 hover:border-white">Reservar Espaços</a>
      <a href="feedback.php" class="text-white hover:border-b-2 hover:border-white">Feedbacks</a>
    </nav>

    <!-- Botão Admin (só aparece no desktop) -->
    <a href="admin.php" 
       class="hidden md:block bg-white text-blue-900 font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 hover:text-white transition-all">
      login
    </a>

    <!-- Botão Menu Mobile (só aparece em telas pequenas) -->
    <button id="menu-btn" class="block md:hidden text-white text-3xl focus:outline-none">
      ☰
    </button>
  </div>
</header>

<!-- Menu Lateral Mobile -->
<div id="mobile-menu" class="fixed top-0 right-0 w-64 h-full bg-blue-900 text-white transform translate-x-full transition-transform duration-300 z-50">
  <div class="p-6 flex flex-col gap-4">
    <button id="close-menu" class="text-white text-3xl self-end">✕</button>
    <a href="index.php" class="hover:underline">Início</a>
    <a href="about.php" class="hover:underline">Sobre Nós</a>
    <a href="reservar.php" class="hover:underline">Reservar Espaços</a>
    <a href="feedback.php" class="hover:underline">Feedbacks</a>
    <a href="admin.php" class="mt-4 bg-white text-blue-900 font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 hover:text-white transition-all">
      Admin
    </a>
  </div>
</div>

<script>
  const menuBtn = document.getElementById("menu-btn");
  const mobileMenu = document.getElementById("mobile-menu");
  const closeMenu = document.getElementById("close-menu");

  menuBtn.addEventListener("click", () => {
    mobileMenu.classList.remove("translate-x-full");
    mobileMenu.classList.add("translate-x-0");
  });

  closeMenu.addEventListener("click", () => {
    mobileMenu.classList.remove("translate-x-0");
    mobileMenu.classList.add("translate-x-full");
  });
</script>
