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

// Verifica se é um aluno (A busca traz o hash salvo no banco)
$stmt = $conn->prepare("SELECT id_aluno, nome, senha FROM tbl_aluno WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);


////////////////////////////////////////////

//  Usando password_verify() para checar o hash do aluno 
if ($aluno && password_verify($senha, $aluno['senha'])) {
    
    // Login de aluno bem-sucedido
    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['permissao'] = 'aluno';
    $_SESSION['id_aluno'] = (int)$aluno['id_aluno'];
    $_SESSION['nome'] = $aluno['nome'];
    
    $aluno_id_int = (int)$aluno['id_aluno'];

    // VERIFICAÇÃO CONDICIONAL DE PLANO 
    $stmtCheckPlano = $conn->prepare("SELECT id_plano FROM tbl_aluno WHERE id_aluno = :id_aluno");
    $stmtCheckPlano->bindParam(':id_aluno', $aluno_id_int, PDO::PARAM_INT);
    $stmtCheckPlano->execute();
    $id_plano_atual = $stmtCheckPlano->fetchColumn();

   // Se o campo id_plano na tbl_aluno NÃO estiver vazio e for maior que zero, ele já tem plano
    if (!empty($id_plano_atual) && $id_plano_atual > 0) {
        // Se TEM plano, direciona para o perfil
        header('Location:../aluno/perfilAluno.php');
    } else {
        // Se NÃO TEM plano, direciona para a escolha do plano mantendo o parâmetro ativo
        header('Location:../aluno/escolha_plano.php?aluno_id=' . $aluno_id_int);
    }
    exit;
}

/////////////////////////////////////////////////////


// Verifica se é um professor (Continua intocado, comparando texto puro)
// =========================
// LOGIN DO PROFESSOR
// (Funcionários com id_professor preenchido)
// =========================
$stmt = $conn->prepare("
     SELECT
        id_funcionarios,
        id_professor,
        nome,
        senha,
        cargo
    FROM tbl_funcionarios
    WHERE email = :email
      AND LTRIM(RTRIM(cargo)) = 'Professor'
      AND situacao = 'Ativo'
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