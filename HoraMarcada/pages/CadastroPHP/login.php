<?php
session_start();
// Incluir o arquivo de conexão
include  'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber dados do formulário
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Buscar o usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);

    // Verificar se o prepare foi bem-sucedido
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Captura os dados do usuário em um array associativo
        $usuario = $result->fetch_assoc();
        
        // Verificar a senha
        if (password_verify($senha, $usuario["senha"])) {
            // Iniciar a sessão com os dados corretos do usuário
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            
            // Redireciona para a home
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
