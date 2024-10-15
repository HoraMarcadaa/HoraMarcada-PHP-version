<?php
session_start();
include('../CadastroPHP/conexao.php'); // Ajuste o caminho conforme necessário

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Obter os dados do usuário logado
$user_id = $_SESSION['user_id'];

// Processa a atualização se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os dados foram enviados
    if (isset($_POST['username'], $_POST['email'])) {
        $nome = $_POST['username'];
        $email = $_POST['email'];
        $senha = $_POST['password']; // Captura a nova senha

        // Atualiza os dados no banco de dados
        if (!empty($senha)) {
            // Se a senha foi fornecida, atualize-a
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT); // Gera o hash da nova senha

            $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nome, $email, $senha_hash, $user_id); // Corrigido para usar "sssi"
        } else {
            // Caso contrário, apenas atualize o nome e o email
            $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nome, $email, $user_id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Dados atualizados com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao atualizar os dados.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Por favor, preencha todos os campos.');</script>";
    }
}

// Obter os dados do usuário atualizado
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    echo "Usuário não encontrado.";
    exit();
}

$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar perfil</title>
    <link rel="stylesheet" href="editar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    

</head>
<body>
    <div class="profile-container">
        <h1>Editar Perfil</h1>
        
        <!-- IMAGEM DO PERFIL -->
        <div class="profile-image-container">
            <img id="profile-img" src="img/main.png" alt="Profile Image">
            <label for="upload-img" class="edit-icon">
                <input type="file" id="upload-img" style="display: none;">
                <i class="fa fa-pencil"></i>
                <a href="../home/index.html" class="back-arrow">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </label>
        </div>
        

       <!-- INPUTS -->
       <form action="editar.php" method="POST">
        <div class="user-info">
    <label for="username">Nome:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" placeholder="Digite sua nova senha" required>

    
    <div class="action-buttons">
    <button type="submit" id="save-changes-btn" class="save-btn">Salvar Alterações</button>
    <button id="delete-account-btn" class="delete-btn">Excluir Conta</button>
    </div>
</div>
</form>

<!--
<div class="user-info">
    <label for="username">Nome:</label>
    <input type="text" id="username" name="username" placeholder="Digite seu nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Digite seu novo email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required pattern=".+@.+\..+" title="Por favor, insira um e-mail válido (ex: usuario@dominio.com)">
    
    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" placeholder="Digite sua nova senha" required>
</div> -->

        <br>

        <p id="error-message" class="error hidden">Preencha todos os campos antes de salvar as alterações.</p>
    
   <!-- CONFIRMA O SALVAMENTO DAS ALTERAÇÕES -->
   <form action="editar.php" method="POST">
<div id="confirm-save-modal" class="modal hidden">
    <div class="modal-content">
        <p>Tem certeza que deseja salvar as alterações feitas?</p>
        <button id="confirm-save-btn" class="confirm-btn">Confirmar</button>
        <button id="cancel-save-btn" class="cancel-btn">Cancelar</button>
    </div>
</div>
</form>

<!-- CONFIRMA A EXCLUSÃO DA CONTA-->
<form id="delete-form" action="deletar_conta.php" method="POST"> 
    <div id="confirm-delete-modal" class="modal hidden">
        <div class="modal-content">
            <p>Tem certeza que deseja excluir sua conta?</p>
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <button id="confirm-delete-btn" class="confirm-btn">Sim, excluir</button>
            <!-- Adicionando type="button" para evitar que o formulário seja enviado -->
            <button id="cancel-delete-btn" class="cancel-btn" type="button">Cancelar</button>
        </div>
    </div>
</form>
    <script src="editar.js"></script>
</body>
</html>
