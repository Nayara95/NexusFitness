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

/*if ($aluno && $senha == $aluno['senha']) {
    // Login de aluno bem-sucedido
    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['permissao'] = 'aluno';
    $_SESSION['id_aluno'] = $aluno['id_aluno'];
    $_SESSION['nome'] = $aluno['nome'];
    //header('Location: ../aluno/perfilAluno.php');
    exit;
}*/

////////////////////////////////////////////

// Use password_verify() para checar o hash - verificação de plano
if ($aluno && $senha == $aluno['senha']) {
    // Login de aluno bem-sucedido
    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['permissao'] = 'aluno';
    $_SESSION['id_aluno'] = $aluno['id_aluno'];
    $_SESSION['nome'] = $aluno['nome'];
    
    $aluno_id_int = (int)$aluno['id_aluno'];

    // 2. VERIFICAÇÃO CONDICIONAL DE PLANO 
    $stmtPlano = $conn->prepare("SELECT COUNT(id_plano) FROM tbl_plano WHERE id_aluno = :id_aluno");
    $stmtPlano->bindParam(':id_aluno', $aluno_id_int, PDO::PARAM_INT);
    $stmtPlano->execute();

    $tem_plano =(int)$stmtPlano->fetchColumn();

    if ($tem_plano > 0) {
        // Se TEM plano, direciona para o perfil
        header('Location:../aluno/perfilAluno.php');
    } else {
        // Se NÃO TEM plano, direciona para a escolha do plano
        header('Location:../aluno/escolha_plano.php?aluno_id=' . $aluno['id_aluno']);
    }
    exit;
}/////////////////////////////////////////////////////




// Verifica se é um professor
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
