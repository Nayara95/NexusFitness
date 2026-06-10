<?php
// Define que o retorno será sempre um JSON estruturado para o Android
header('Content-Type: application/json; charset=utf-8');

// Importa o seu arquivo de conexão atual
require_once 'conexao.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// 1. Validação de campos vazios
if (empty($email) || empty($senha)) {
    echo json_encode(array(
        "status" => "erro",
        "mensagem" => "Por favor, preencha todos os campos."
    ));
    exit;
}

$conn = conectar();

// 2. Busca o aluno no banco pelo e-mail
$stmt = $conn->prepare("SELECT id_aluno, nome, senha FROM tbl_aluno WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Checa a senha usando a mesma criptografia do seu sistema web
if ($aluno && password_verify($senha, $aluno['senha'])) {
    
    $aluno_id_int = (int)$aluno['id_aluno'];

    // 4. Verifica se o aluno já tem plano (sua lógica exata do COUNT)
    $stmtPlano = $conn->prepare("SELECT COUNT(id_plano) FROM tbl_plano WHERE id_aluno = :id_aluno");
    $stmtPlano->bindParam(':id_aluno', $aluno_id_int, PDO::PARAM_INT);
    $stmtPlano->execute();

    $tem_plano = (int)$stmtPlano->fetchColumn();
    $possui_plano = ($tem_plano > 0);

    // Devolve a resposta de sucesso com o que o Java precisa para decidir a tela
    echo json_encode(array(
        "status" => "sucesso",
        "tem_plano" => $possui_plano,
        "id_aluno" => $aluno['id_aluno'],
        "nome" => $aluno['nome']
    ));
    exit;
}

// 5. Se as credenciais estiverem incorretas
echo json_encode(array(
    "status" => "erro",
    "mensagem" => "E-mail ou senha incorretos."
));
exit;
?>