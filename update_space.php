<?php
include("config.php");
session_start();

$isAdmin = $_SESSION['isAdmin'] ?? false;
if(!$isAdmin){
    header("Location: login.php");
    exit();
}

$id    = $_POST['id'] ?? '';
$nome  = $_POST['nome'] ?? '';
$valor = $_POST['valor'] ?? '';

if(!$id || !$nome || !$valor){
    header("Location: admin.php?error=Preencha todos os campos");
    exit();
}

if(isset($_FILES['img']) && $_FILES['img']['error'] === 0){

    $uploadDir = "uploads/";
    if(!is_dir($uploadDir)) mkdir($uploadDir);

    $fileName = time() . "_" . basename($_FILES['img']['name']);
    $filePath = $uploadDir . $fileName;

    if(move_uploaded_file($_FILES['img']['tmp_name'], $filePath)){

        $stmt = $conn->prepare("UPDATE espacos SET nome=?, valor=?, img=? WHERE id=?");
        $stmt->bind_param("sdsi", $nome, $valor, $filePath, $id);

    } else {
        header("Location: admin.php?error=Falha ao enviar imagem");
        exit();
    }

} else {
    // Sem alterar a imagem
    $stmt = $conn->prepare("UPDATE espacos SET nome=?, valor=? WHERE id=?");
    $stmt->bind_param("sdi", $nome, $valor, $id);
}

if($stmt->execute()){
    header("Location: admin.php?success=Espaço atualizado com sucesso");
} else {
    header("Location: admin.php?error=Erro ao atualizar espaço");
}
exit();
?>
