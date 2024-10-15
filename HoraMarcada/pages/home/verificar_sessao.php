<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Retorna os dados do usuário logado
    echo json_encode([
        'logged_in' => true,
        'user_name' => $_SESSION['user_name'],
        'user_id' => $_SESSION['user_id']
    ]);
} else {
    // Retorna que o usuário não está logado
    echo json_encode(['logged_in' => false]);
}
?>
