<?php
// Arquivo: reset_senha.php
session_start();
include('../CadastroPHP/conexao.php'); // Certifique-se de que sua conexão ao banco de dados esteja configurada corretamente

// Incluir o autoloader do Composer
require 'vendor/autoload.php';  // Caminho para o autoloader do Composer

// Usar namespaces do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o e-mail do formulário
    $email = trim($_POST['email']);
    
    // Verifica se o e-mail existe no banco de dados e obtém o nome do usuário
    $sql = "SELECT nome FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $row = $resultado->fetch_assoc();
        $nome_usuario = $row['nome']; // Obtem o nome do usuário

        // Gera uma nova senha aleatória
        $nova_senha = substr(bin2hex(random_bytes(4)), 0, 8); // Gera uma senha de 8 caracteres
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualiza a senha no banco de dados
        $sql_update = "UPDATE usuarios SET senha = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $nova_senha_hash, $email);
        $stmt_update->execute();
        
        // Configura o e-mail a ser enviado usando PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';  // Servidor SMTP do Mailtrap
            $mail->SMTPAuth = true;
            $mail->Username = '9e9754bb7385dc';  // Substitua pelo seu Username do Mailtrap
            $mail->Password = '6d20005546cbe4';    // Substitua pela sua Senha do Mailtrap
            $mail->Port = 2525;                        // Porta SMTP do Mailtrap

            // Configurações do e-mail
            $mail->CharSet = 'UTF-8';  // Adicione esta linha para definir a codificação UTF-8
            $mail->setFrom('horamarcada@gmail.com', 'Hora Marcada');
            $mail->addAddress($email);  // Destinatário do e-mail
            $mail->isHTML(true);  // Enviar como HTML se preferir
            $mail->Subject = 'Redefinição de Senha';
            $mail->Body = "Olá, $nome_usuario!<br><br>Sua nova senha é: <strong>$nova_senha</strong><br><br>Você pode alterar essa senha após o login através da página de edição de perfil.";
            $mail->AltBody = "Olá, $nome_usuario!\n\nSua nova senha é: $nova_senha\n\nVocê pode alterar essa senha após o login através da página de edição de perfil.";

            // Envia o e-mail
            $mail->send();
            echo "<script>alert('Uma nova senha foi enviada para seu e-mail.'); window.location.href = '/Hora-Marcada/HoraMarcada/pages/CadastroPHP/index.html';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Ocorreu um erro ao enviar o e-mail. Tente novamente mais tarde.'); window.location.href = '/Hora-Marcada/HoraMarcada/pages/esq/esq.html';</script>";
        }
    } else {
        echo "<script>alert('E-mail não encontrado.'); window.location.href = '/Hora-Marcada/HoraMarcada/pages/esq/esq.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
