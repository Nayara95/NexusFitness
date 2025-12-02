<?php
session_start();

// Verifica se o usuário não está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Monta o caminho para o login.html, considerando que auth.php pode estar em um subdiretório
    // Esta lógica assume que as páginas protegidas estão um nível abaixo da raiz
    $login_path = 'login.php'; 

    // Se a página protegida estiver na raiz, o caminho seria diferente
    if (basename(dirname($_SERVER['PHP_SELF'])) == basename(dirname(__DIR__))) {
        $login_path = 'login.php';
    }
    echo '<p style="color: red; text-align: center; margin-bottom: 10px;">Login ou senha incorretos.</p>';
    // Redireciona para a página de login e encerra o script
    header('Location: ' . $login_path . '?auth_error=1');
    exit;
}
?>
