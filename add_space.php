<?php
include("config.php");
session_start();

$isAdmin = $_SESSION['isAdmin'] ?? false;
if(!$isAdmin){
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome  = trim($_POST['nome'] ?? '');
    $valor = floatval($_POST['valor'] ?? 0);
    $imgPath = null;

    // Upload da imagem
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $pasta = "uploads/espacos/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid("espaco_") . "." . strtolower($ext);
        $caminho = $pasta . $nomeArquivo;

        if (move_uploaded_file($_FILES['img']['tmp_name'], $caminho)) {
            $imgPath = $caminho;
        } else {
            echo "Erro ao salvar a imagem.";
            exit();
        }
    }

    // Inserir no banco
    if ($nome && $valor > 0 && $imgPath) {
        $sql = "INSERT INTO espacos (nome, valor, img) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sds", $nome, $valor, $imgPath);

        if ($stmt->execute()) {
            header("Location: admin.php?success=espaco");
            exit();
        } else {
            echo "Erro ao adicionar espaço: " . $conn->error;
        }

        $stmt->close();

    } else {
        echo "Preencha todos os campos corretamente.";
    }

} else {
    header("Location: admin.php");
    exit();
}
?>
