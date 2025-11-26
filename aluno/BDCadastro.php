<?php
// Função para conectar ao banco de dados
function conectar() {
    // CONEXÃO COM O BANCO DE DADOS
    $local_server = "DESKTOP-HSJ09OO\SQLEXPRESS1";
    $usuario_server = "sa";
    $senha_server = "loey";
    $banco_de_dados = "BD_Nexus";

    $dns = "sqlsrv:Server=$local_server;Database=$banco_de_dados";

    try {
        $conn = new PDO($dns, $usuario_server, $senha_server);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // CORREÇÃO: Enviar header JSON para o navegador entender a falha na conexão
        header('Content-Type: application/json');
        http_response_code(500);
        die(json_encode(['status' => 'erro', 'mensagem' => 'ERRO NA CONEXÃO: ' . $e->getMessage()]));
    }
}

// Início do Processamento
$conn = conectar();
$tabela = "tbl_aluno";

// Define o header JSON para a resposta do AJAX
header('Content-Type: application/json');

try {
    // 1. CAPTURA DOS DADOS
    $novonome = $_POST["nome"] ?? '';
    $novosocial = $_POST["nome_social"] ?? '';
    $novoemail = $_POST["email"] ?? '';
    $novocpf = $_POST["cpf"] ?? '';
    $novogenero = $_POST["genero"] ?? '';
    $novoDataNasc = $_POST["data_nasc"] ?? null;
    $novaddd = $_POST["dd1"] ?? '';
    $novacelular = $_POST["telefone"] ?? '';
    $senha_pura = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);
    $senha_db = $senha_pura; // Senha pura (INSEGURO, mas conforme o código)

    // 2. PREPARED STATEMENT
    $stmt = $conn->prepare(
        "INSERT INTO " . $tabela . " (nome, nome_social, email, cpf, genero, data_nasc, dd1, telefone, senha) " .
        "VALUES (:nome, :nome_social, :email, :cpf, :genero, :data_nasc, :dd1, :telefone, :senha);"
    );

    $stmt->bindValue(":nome", $novonome);
    $stmt->bindValue(":nome_social", $novosocial);
    $stmt->bindValue(":email", $novoemail);
    $stmt->bindValue(":cpf", $novocpf);
    $stmt->bindValue(":genero", $novogenero);
    $stmt->bindValue(":data_nasc", $novoDataNasc);
    $stmt->bindValue(":dd1", $novaddd);
    $stmt->bindValue(":telefone", $novacelular);
    $stmt->bindValue(":senha", $senha_db);

    // 3. EXECUÇÃO E OBTENÇÃO DO ID
    $stmt->execute();
    $novo_aluno_id = $conn->lastInsertId();

    // 4. VERIFICAÇÃO CONDICIONAL DE PLANO
    $stmtPlano = $conn->prepare("SELECT COUNT(id_plano) FROM tbl_plano WHERE id_aluno = :id_aluno");
    $stmtPlano->bindParam(':id_aluno', $novo_aluno_id, PDO::PARAM_INT);
    $stmtPlano->execute();
    $tem_plano = $stmtPlano->fetchColumn(); 

    $url_redirecionamento = '';
    if ($tem_plano > 0) {
        // Se TEM plano, direciona para o perfil
        $url_redirecionamento = '../aluno/perfilAluno.php';
    } else {
        // Se NÃO TEM plano, direciona para a escolha do plano (passando o ID)
        $url_redirecionamento = '../escolha_plano.php?aluno_id=' . $novo_aluno_id;
    }

    // 5. RESPOSTA DE SUCESSO JSON
    echo json_encode([
        'status' => 'sucesso',
        'mensagem' => 'Cadastro realizado! Redirecionando para a próxima etapa.',
        'redirect_url' => $url_redirecionamento
    ]);
    exit;

} catch (PDOException $e) {
    // 6. TRATAMENTO DE ERRO PDO
    http_response_code(500);
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'ATENÇÃO, erro na inclusão de dados: ' . $e->getMessage()
    ]);
    exit;
}

?>