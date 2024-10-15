<?php
// Conexão com o banco de dados
include('../CadastroPHP/conexao.php'); // Ajuste o caminho conforme necessário
$host = "localhost";
$dbname = "sistema"; // Nome do banco de dados
$username = "root"; // Usuário do banco de dados
$password = ""; // Senha do banco de dados (modifique se necessário)

$conn = new mysqli($host, $username, $password, $dbname);

// Verifica se houve algum erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se o ID do usuário foi passado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];

    // Evita SQL Injection usando prepared statements
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    // Executa a query e verifica se a exclusão foi bem-sucedida
    if ($stmt->execute()) {
        echo "Conta excluída com sucesso!";
        // Aqui você pode redirecionar o usuário, por exemplo, para a página inicial:
     header("Location: /Hora-Marcada/HoraMarcada/pages/CadastroPHP/index.html");
        exit();
    } else {
        echo "Erro ao excluir a conta: " . $stmt->error;
    }

    // Fecha a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>
