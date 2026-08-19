<?php
include("config.php");
session_start();

// Checar se o usuário é admin
$isAdmin = $_SESSION['isAdmin'] ?? false;
if(!$isAdmin){
    header("Location: login.php");
    exit();
}

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $data  = $_POST['data'] ?? '';
    $desc  = trim($_POST['desc'] ?? '');
    $local = trim($_POST['local'] ?? '');
    $imgPath = null; // vai armazenar o caminho da imagem

    // --- Upload da imagem ---
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $pasta = "uploads/";
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid("evento_") . "." . strtolower($ext);
        $caminho = $pasta . $nomeArquivo;

        if (move_uploaded_file($_FILES['img']['tmp_name'], $caminho)) {
            $imgPath = $caminho;
        } else {
            header("Location: admin.php?error=Erro ao salvar a imagem.");
            exit();
        }
    } else {
        header("Location: admin.php?error=Envie uma imagem válida.");
        exit();
    }

    // --- Inserir no banco ---
    if ($nome && $data && $desc && $local && $imgPath) {
        $sql = "INSERT INTO eventos (nome, data, descricao, localizacao, imagem) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $data, $desc, $local, $imgPath);

        if ($stmt->execute()) {
            header("Location: admin.php?success=Evento criado com sucesso!");
            exit();
        } else {
            header("Location: admin.php?error=Erro ao criar evento: " . urlencode($conn->error));
            exit();
        }
        $stmt->close();
    } else {
        header("Location: admin.php?error=Preencha todos os campos corretamente.");
        exit();
    }

} else {
    header("Location: admin.php");
    exit();
}
?>
