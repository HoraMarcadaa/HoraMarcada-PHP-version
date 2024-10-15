<?php
session_start();
session_destroy(); // Destrói a sessão
header("Location: /Hora-Marcada/HoraMarcada/pages/CadastroPHP/index.html"); // Redireciona para a página de login
exit();
?>
