<?php
// Inclui o arquivo de conexão com o banco de dados
include('../CadastroPHP/conexao.php');
session_start(); // Iniciar a sessão

if (!isset($_SESSION['user_id'])) {
    // Se não houver sessão, redirecionar para o login
    header("Location: /Hora-Marcada/HoraMarcada/pages/CadastroPHP/index.html");
    exit();
}

// Aqui, você pode usar o ID do usuário para buscar dados no banco, como a imagem de perfil
// Receber dados do formulário
$user_id = $_SESSION['user_id'];
$nome = $_POST["username"];
$email = $_POST["email"];
$senha = $_POST["password"];


// Pegando os dados do formulário
$nome_usuario = $_POST['name'];
$sala = $_POST['room'];
$data_reserva = $_POST['date'];
$hora_inicio = $_POST['start_time'];
$hora_fim = $_POST['end_time'];

// Consultar o ID da sala com base no nome
$sql_sala = "SELECT ID_Sala FROM Salas WHERE Nome_Sala = '$sala'";
$result_sala = $conn->query($sql_sala);

if ($result_sala->num_rows > 0) {
    $row_sala = $result_sala->fetch_assoc();
    $id_sala = $row_sala['ID_Sala'];

    // Verificar se já existe uma reserva para a mesma sala, data e horário
    $sql_verificar = "SELECT * FROM Reservas WHERE ID_Sala = '$id_sala' 
                      AND Data_Reserva = '$data_reserva' 
                      AND (
                          (Hora_Inicio < '$hora_fim' AND Hora_Fim > '$hora_inicio')
                      )";

    $result_verificar = $conn->query($sql_verificar);

    if ($result_verificar->num_rows > 0) {
        echo "<script>alert('A sala já está indisponível nesse horário.'); window.location.href = 'index.php';</script>";
    } else {
        // Inserir os dados na tabela de reservas
        $sql = "INSERT INTO Reservas (Nome_Usuario, ID_Sala, Data_Reserva, Hora_Inicio, Hora_Fim)
                VALUES ('$nome_usuario', '$id_sala', '$data_reserva', '$hora_inicio', '$hora_fim')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Reserva feita com sucesso!'); window.location.href = 'index.html';</script>";
        } else {
            echo "<script>alert('Erro ao fazer a reserva: " . $conn->error . "'); window.location.href = 'index.html';</script>";
        }
    }
} else {
    echo "<script>alert('Sala não encontrada.'); window.location.href = 'index.html';</script>";
}

// Fechar a conexão
$conn->close();
?>
