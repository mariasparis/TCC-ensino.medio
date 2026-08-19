<?php
session_start();
include("config.php");
$erro = '';
$mensagem = '';

// Verificar se o usuário já está logado
if (isset($_SESSION['user_nome'])) {
    $mensagem = "Você está logado como " . htmlspecialchars($_SESSION['user_nome']) . ".";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';

    $sql = "SELECT id, nome, email, senha, isAdmin FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        if (password_verify($pass, $usuario['senha'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['isAdmin'] = $usuario['isAdmin'];

            if ($usuario['isAdmin'] == 1) {
                header('Location: admin.php');
            } else {
                header('Location: reservar.php');
            }
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fides - Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800">

<?php include("components/header.php"); ?>

<main class="min-h-screen flex flex-col justify-center items-center p-4">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Login</h2>

        <?php if($mensagem): ?>
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4 text-center">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <?php if($erro): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-center">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-4">
                <label class="block text-gray-700">E-mail</label>
                <input type="email" name="email" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Senha</label>
                <input type="password" name="password" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900" required>
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white p-2 rounded-lg hover:bg-blue-700">Entrar</button>
        </form>

        <p class="text-gray-600 text-sm text-center mt-4">Não tem conta? <a href="register.php" class="text-blue-900 hover:underline">Cadastre-se</a></p>
    </div>
</main>

<?php include("components/footer.php"); ?>

</body>
</html>
