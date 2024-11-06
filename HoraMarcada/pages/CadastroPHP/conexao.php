<?php
// Configurações de conexão com o banco de dados
$host = "localhost";
$dbname = "sistema";
$user = "root"; // Se o usuário do MySQL for diferente, altere aqui
$password = "1234"; // Se houver senha, adicione aqui

// Criar conexão
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar se houve erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
