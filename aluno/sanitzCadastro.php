<?php

session_start(); // Iniciando a sessão para armazenar dados do usuário
if (isset($_SESSION['loggedin']) && $_SESSION['permissao'] === 'aluno') {
    header('Location: index-aluno.php'); // Redireciona para a área do aluno se já estiver logado
    exit;
}

//sanitização dos dados do formulário
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha para segurança

// criação de cookie se solicitado

if (isset($_POST['criar_cookie'])) {
    setcookie('email', $email, time() + (86400 * 1), "/"); // Cookie válido por 1 dia
}


?>