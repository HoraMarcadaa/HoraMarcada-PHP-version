<?php
// Incluir o arquivo de conexão
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber dados do formulário
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Buscar o usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        // Verificar a senha
        if (password_verify($senha, $usuario["senha"])) {
            // Login bem-sucedido
            header("Location: /Hora-Marcada/HoraMarcada/pages/home/index.html");
            exit();
        } else {
            // Senha incorreta
            echo "<script>window.location.href = 'index.html'; alert('Senha incorreta!');</script>";
        }
    } else {
        // Usuário não encontrado
        echo "<script>window.location.href = 'index.html'; alert('Usuário não encontrado!');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
