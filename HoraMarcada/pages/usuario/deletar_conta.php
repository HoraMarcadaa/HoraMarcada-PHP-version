<?php
// Conexão com o banco de dados
include('../CadastroPHP/conexao.php'); // Ajuste o caminho conforme necessário
$host = "localhost";
$dbname = "sistema"; // Nome do banco de dados
$username = "root"; // Usuário do banco de dados
$password = "1234"; // Senha do banco de dados (modifique se necessário)

$conn = new mysqli($host, $username, $password, $dbname);

// Verifica se houve algum erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se o ID do usuário foi passado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    var_dump($user_id); // Debug: Verifica se o ID está correto

    // Exclui todas as reservas associadas ao usuário antes de excluir o usuário
    $sql_delete_reservas = "DELETE FROM reservas WHERE ID_Usuario = ?";
    $stmt_reservas = $conn->prepare($sql_delete_reservas);
    $stmt_reservas->bind_param("i", $user_id);

    if ($stmt_reservas->execute()) {
        // Agora exclui o usuário
        $sql_delete_usuario = "DELETE FROM usuarios WHERE id = ?";
        $stmt_usuario = $conn->prepare($sql_delete_usuario);
        $stmt_usuario->bind_param("i", $user_id);

        if ($stmt_usuario->execute()) {
            echo "Conta excluída com sucesso!";
            // Redireciona o usuário para a página inicial
            header("Location: /Hora-Marcada/HoraMarcada/pages/CadastroPHP/index.html");
            exit();ithub
        } else {
            echo "Erro ao excluir a conta: " . $stmt_usuario->error;
        }

        $stmt_usuario->close();
    } else {
        echo "Erro ao excluir reservas do usuário: " . $stmt_reservas->error;
    }

    // Fecha as declarações e a conexão
    $stmt_reservas->close();
    $conn->close();
}
?>
