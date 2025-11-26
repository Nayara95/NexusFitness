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


// =========================
// LOGIN DO ALUNO
// =========================
$stmt = $conn->prepare("
    SELECT id_aluno, nome, senha 
    FROM tbl_aluno 
    WHERE email = :email
");
$stmt->bindParam(':email', $email);
$stmt->execute();
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if ($aluno && $senha == $aluno['senha']) {
    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['permissao'] = 'aluno';
    $_SESSION['id_aluno'] = $aluno['id_aluno'];
    $_SESSION['nome'] = $aluno['nome'];

    header('Location: ../aluno/perfilAluno.php');
    exit;
}


// =========================
// LOGIN DO PROFESSOR
// (Funcionários com id_professor preenchido)
// =========================
$stmt = $conn->prepare("
    SELECT id_funcionarios, id_professor, nome, senha
    FROM tbl_funcionarios
    WHERE email = :email
      AND id_professor IS NOT NULL
");
$stmt->bindParam(':email', $email);
$stmt->execute();
$professor = $stmt->fetch(PDO::FETCH_ASSOC);

if ($professor && $senha == $professor['senha']) {

    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['permissao'] = 'professor';
    $_SESSION['id_professor'] = $professor['id_professor'];
    $_SESSION['id_funcionario'] = $professor['id_funcionarios'];
    $_SESSION['nome'] = $professor['nome'];

    header('Location: ../professor/index-professor.php');
    exit;
}


// =========================
// FALHA NO LOGIN
// =========================
header('Location: ../login.php?error=1');
exit;
?>
