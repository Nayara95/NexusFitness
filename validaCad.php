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
  
  // Exemplo de validação de campo obrigatório no servidor
    if (empty($nomeCompleto) || empty($cpf) || empty($email)) {
        die("Erro: Campos obrigatórios não preenchidos.");
    }
     // Exemplo de validação de e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Erro: Formato de e-mail inválido.");
    }

    echo "<h1>Cadastro Recebido com Sucesso!</h1>";
    echo "<p>Bem-vindo(a), $nomeCompleto.</p>";
    echo "<p>Seus dados foram processados. Você será notificado(a) por e-mail: $email</p>";

} else {
    // Se  for acessada diretamente sem o POST 
    header('Location: cadastro-aluno.php'); 
    exit;
}













?>