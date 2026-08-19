<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fides";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
