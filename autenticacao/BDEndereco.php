<?php
session_start();
require_once 'conexao.php'; 

header('Content-Type: application/json');

try {
    $conn = conectar();
    
    // 1. CAPTURA DOS DADOS E ID DO ALUNO
    $id_aluno = $_POST["id_aluno"] ?? $_SESSION['id_aluno'] ?? null;
    $cep = $_POST["cep"] ?? '';
    $rua = $_POST["rua"] ?? '';
    $bairro = $_POST["bairro"] ?? '';
    $cidade = $_POST["cidade"] ?? '';
    $estado = $_POST["estado"] ?? ''; // No HTML, você está usando o ID 'estado' para a UF
    $numero = $_POST["numero"] ?? '';
    $complemento = $_POST["complemento"] ?? '';

    // VARIÁVEIS LIMPAS E CONVERTIDAS
    // Garante que as variáveis numéricas sejam INT
    $cep_int = (int)str_replace('-', '', $_POST["cep"] ?? '');
    $numero_int = (int)($_POST["numero"] ?? 0);
    $id_enderecoAluno = null;// Verifica se o ID do aluno foi fornecido



    if (empty($id_aluno)) {
        http_response_code(400);
        die(json_encode(['status' => 'erro', 'mensagem' => 'ID do aluno não fornecido.']));
    }
    
    // 2. VERIFICA SE O ALUNO JÁ TEM UM ENDEREÇO CADASTRADO (Consulta na tbl_aluno)
    $stmtCheck = $conn->prepare("SELECT id_enderecoAluno FROM tbl_aluno WHERE id_aluno = :id_aluno");
    $stmtCheck->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT); // Use o nome do parâmetro correto
    $stmtCheck->execute();
    $id_enderecoExistente = $stmtCheck->fetchColumn();// Retorna o ID do endereço ou FALSE

    // Limpa o CEP e garante que o número seja INT para o BD
    $cep_limpo = str_replace('-', '', $cep);
    $numero_int = (int)$numero; 
    
    if ($id_enderecoExistente) {
        // 3a. SE JÁ TEM ENDEREÇO: Faz um UPDATE na tbl_enderecoAluno
        $sql = "UPDATE tbl_enderecoAluno SET 
                    rua = :rua, numero_endereco = :numero, bairro = :bairro, 
                    cep = :cep, cidade = :cidade, uf = :uf, complemento = :complemento
                WHERE id_enderecoAluno = :id_endereco;";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_endereco", $id_enderecoExistente, PDO::PARAM_INT);
        
    } else {
        // --- INSERT (Se o aluno é novo, precisa criar o ID da chave primária manualmente) ---
        // SQL Server/PDO obtém o próximo ID para tbl_enderecoAluno (se não for identity)
        $stmtNextId = $conn->query("SELECT ISNULL(MAX(id_enderecoAluno), 0) + 1 FROM tbl_enderecoAluno");
        //$proximo_id = $stmtNextId->fetchColumn();
        $novo_id_endereco = (int)$proximo_id ->fetchColumn();


        //  SE NÃO TEM ENDEREÇO: Faz um INSERT na tbl_enderecoAluno
        $sql = "INSERT INTO tbl_enderecoAluno 
                    (rua, numero_endereco, bairro, cep, cidade, uf, complemento, id_enderecoAluno)
                VALUES 
                    (:rua, :numero, :bairro, :cep, :cidade, :uf, :complemento, (SELECT ISNULL(MAX(id_enderecoAluno), 0) + 1 FROM tbl_enderecoAluno));";

        $stmt = $conn->prepare($sql);
        ///NOVO
        $stmt->bindParam(":novo_id_endereco", $novo_id_endereco, PDO::PARAM_INT);
    }
    
    // 4. VINCULAÇÃO DE DADOS COMUNS E EXECUÇÃO
    $stmt->bindValue(":rua", $rua);
    $stmt->bindValue(":numero", $numero_int, PDO::PARAM_INT);
    $stmt->bindValue(":bairro", $bairro);
    $stmt->bindValue(":cep", $cep_limpo , PDO::PARAM_INT);
    $stmt->bindValue(":cidade", $cidade);
    $stmt->bindValue(":uf", $estado); // O HTML usa ID 'estado' para UF, mas a coluna é 'uf'
    $stmt->bindValue(":complemento", $complemento);
    
    $stmt->execute();
    
    // 5. SE FOI UM INSERT, PRECISA VINCULAR O ID À TABELA ALUNO
    if (!$id_enderecoExistente) {
        //$novo_id_endereco = $conn->lastInsertId();
        
        // Atualiza a tbl_aluno para vincular o novo id_enderecoAluno
        $stmtUpdateAluno = $conn->prepare("UPDATE tbl_aluno SET id_enderecoAluno = :novo_id_endereco WHERE id_aluno = :aluno_id");
        $stmtUpdateAluno->bindParam(':novo_id_endereco', $novo_id_endereco, PDO::PARAM_INT);
        $stmtUpdateAluno->bindParam(':aluno_id', $aluno_id, PDO::PARAM_INT);
        $stmtUpdateAluno->execute();
    }
    
    // 6. RESPOSTA DE SUCESSO JSON
    echo json_encode([
        'status' => 'sucesso',
        'mensagem' => 'Endereço atualizado com sucesso!',
        'redirect_url' => '../aluno/pagamento.php'
    ]);
    exit;

} catch (PDOException $e) {
    // 7. TRATAMENTO DE ERRO PDO
    http_response_code(500);
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Erro ao processar endereço: ' . $e->getMessage()
    ]);
    exit;
}
?>