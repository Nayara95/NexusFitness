<?php
session_start();
require_once 'conexao.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    header('Location: ../login.php?error=empty');
    exit;
}

$conn = conectar();

// Verifica se é um aluno
$stmt = $conn->prepare("SELECT id_aluno, nome, senha FROM tbl_aluno WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if ($aluno && $senha == $aluno['senha']) {
    // Login de aluno bem-sucedido
    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['permissao'] = 'aluno';
    $_SESSION['id_aluno'] = $aluno['id_aluno'];
    $_SESSION['nome'] = $aluno['nome'];
    header('Location: ../aluno/perfilAluno.php');
    exit;
}

// Verifica se é um professor
$stmt = $conn->prepare("SELECT id_professor, nome, senha FROM tbl_professor WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
$professor = $stmt->fetch(PDO::FETCH_ASSOC);

if ($professor && $senha == $professor['senha']) {
    // Login de professor bem-sucedido
    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['permissao'] = 'professor';
    $_SESSION['id_professor'] = $professor['id_professor'];
    $_SESSION['nome'] = $professor['nome'];
    header('Location: ../professor/perfil-professor.php');
    exit;
}

// Falha no login
header('Location: ../login.php?error=1');
exit;
?>