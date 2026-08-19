<?php
include("config.php");
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $cpf = trim($_POST['cpf'] ?? '');
  $telefone = trim($_POST['telefone'] ?? '');
  $senha = $_POST['senha'] ?? '';
  
  if($nome && $email && $cpf && $telefone && $senha){
      $hash = password_hash($senha, PASSWORD_DEFAULT);
  
      $sql = "INSERT INTO usuarios (nome, email, cpf, telefone, senha) VALUES (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssss", $nome, $email, $cpf, $telefone, $hash);
  

        if($stmt->execute()){
            $sucesso = "Cadastro realizado com sucesso!";
        } else {
            if($conn->errno === 1062){
                $erro = "Esse e-mail já está cadastrado.";
            } else {
                $erro = "Erro ao cadastrar usuário: " . $conn->error;
            }
        }

        $stmt->close();
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
<title>Fides - Cadastro</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800">

<?php include("components/header.php"); ?>

<main class="min-h-screen flex flex-col justify-center items-center p-4">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
  <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Cadastro</h2>
  <?php if($erro): ?>
    <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?php echo $erro; ?></div>
  <?php endif; ?>
  <?php if($sucesso): ?>
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?php echo $sucesso; ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-4">
      <label class="block text-gray-700">Nome</label>
      <input type="text" name="nome" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900" required>
    </div>
    <div class="mb-4">
      <label class="block text-gray-700">Email</label>
      <input type="email" name="email" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900" required>
    </div>
    <div class="mb-4">
      <label class="block text-gray-700">Senha</label>
      <input type="password" name="senha" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900" required>
    </div>
    <div class="mb-4">
  <label class="block text-gray-700">CPF</label>
  <input type="text" name="cpf" maxlength="14" placeholder="000.000.000-00" 
         class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900" required>
</div>

<div class="mb-4">
  <label class="block text-gray-700">Telefone</label>
  <input type="text" name="telefone" maxlength="15" placeholder="(00) 00000-0000" 
         class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900" required>
</div>

    <button type="submit" class="w-full bg-blue-900 text-white p-2 rounded-lg hover:bg-blue-700">Cadastrar</button>
  </form>
  <p class="text-gray-600 text-sm text-center mt-4">Já tem conta? <a href="login.php" class="text-blue-900 hover:underline">Entrar</a></p>
</div>
</main>

<?php include("components/footer.php"); ?>


</body>
</html>
