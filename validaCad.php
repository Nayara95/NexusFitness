<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeCompleto = $_POST['nome'];
    $nomeSocial  = $_POST['nomeSocial'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['genero'];
    $nascimento = $_POST['nascimento'];
    $ddd = $_POST['ddd'];
    $celular = $_POST['celular'];

    // Isso garante que os dados sejam exibidos como texto e não como código HTML/JS.
    //htmlspecialchars = converte caracteres especiais em entidades html
    $nomeCompletoSeguro = htmlspecialchars($nomeCompleto, ENT_QUOTES, 'UTF-8');
    $emailSeguro = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    
    echo "<h1>Cadastro Recebido com Sucesso!</h1>";
    echo "<p>Bem-vindo(a), $nomeCompletoSeguro.</p>";
    echo "<p>Seus dados foram processados. Você será notificado(a) por e-mail: $emailSeguro</p>";

} else {
    // Se for acessada diretamente sem o POST
    header('Location: cadastro-aluno.php');
    exit;
}
?>













?>