<?php
// Inclui o arquivo de conexão com o banco de dados
include('../CadastroPHP/conexao.php');
session_start(); // Iniciar a sessão

if (!isset($_SESSION['user_id'])) {
    // Se não houver sessão, redirecionar para o login
    header("Location: /Hora-Marcada/HoraMarcada/pages/CadastroPHP/index.html");
    exit();
}

// Pegando o ID do usuário logado
$user_id = $_SESSION['user_id'];

// Receber dados do formulário
$nome_usuario = $_POST['name'];
$sala = $_POST['room'];
$data_reserva = $_POST['date'];
$hora_inicio = $_POST['start_time'];
$hora_fim = $_POST['end_time'];

// Consultar o ID da sala com base no nome
$sql_sala = "SELECT ID_Sala FROM Salas WHERE Nome_Sala = ?";
$stmt_sala = $conn->prepare($sql_sala);
$stmt_sala->bind_param("s", $sala);
$stmt_sala->execute();
$result_sala = $stmt_sala->get_result();

if ($result_sala->num_rows > 0) {
    $row_sala = $result_sala->fetch_assoc();
    $id_sala = $row_sala['ID_Sala'];

    // Verificar se já existe uma reserva para a mesma sala, data e horário
    $sql_verificar = "SELECT * FROM Reservas WHERE ID_Sala = ? 
                      AND Data_Reserva = ? 
                      AND (
                          (Hora_Inicio < ? AND Hora_Fim > ?)
                      )";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("isss", $id_sala, $data_reserva, $hora_fim, $hora_inicio);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar->num_rows > 0) {
        echo "<script>alert('A sala já está indisponível nesse horário.'); window.location.href = 'index.php';</script>";
    } else {
        // Inserir os dados na tabela de reservas
        $sql = "INSERT INTO Reservas (Nome_Usuario, ID_Sala, Data_Reserva, Hora_Inicio, Hora_Fim, ID_Usuario)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssi", $nome_usuario, $id_sala, $data_reserva, $hora_inicio, $hora_fim, $user_id);

        if ($stmt->execute()) {
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