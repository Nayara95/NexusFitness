<?php
session_start();

// --- DADOS DE EXEMPLO ---
// Em um sistema real, você buscaria isso de um banco de dados.
$usuarios = [
    'aluno@nexus.com' => [
        'senha' => 'aluno123',
        'tipo' => 'aluno',
        'pagina' => '../aluno/boasvindasAluno.php'
    ],
    'prof@nexus.com' => [
        'senha' => 'prof123',
        'tipo' => 'professor',
        'pagina' => '../professor/index-professor.php' // Assumindo que esta página exista
    ]
];
// --------------------

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica se o usuário existe e a senha está correta
if (isset($usuarios[$email]) && $usuarios[$email]['senha'] === $senha) {
    // Login bem-sucedido
    $usuario = $usuarios[$email];

    // Configura a sessão
    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['tipo'] = $usuario['tipo'];

    // Redireciona para a página do usuário
    header('Location: ' . $usuario['pagina']);
    exit;
} else {
    // Falha no login: redireciona de volta para a página de login com um erro
    header('Location: ../login.html?error=1');
    exit;
}
?>
