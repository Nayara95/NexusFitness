<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexao.php'; 

// Força o cabeçalho de resposta a ser estritamente JSON
header('Content-Type: application/json');

try {
    $conn = conectar();
    
    // 1. TRATAMENTO SEGURO DO ID DO ALUNO
    $id_aluno = null;
    if (isset($_POST["id_aluno"]) && $_POST["id_aluno"] !== 'null' && $_POST["id_aluno"] !== '') {
        $id_aluno = (int)$_POST["id_aluno"];
    } elseif (isset($_POST["aluno_id"]) && $_POST["aluno_id"] !== 'null' && $_POST["aluno_id"] !== '') {
        $id_aluno = (int)$_POST["aluno_id"];
    } elseif (isset($_SESSION['id_aluno']) && $_SESSION['id_aluno'] !== '') {
        $id_aluno = (int)$_SESSION['id_aluno'];
    }

    if (empty($id_aluno) || $id_aluno <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'erro', 'mensagem' => 'ID do aluno inválido ou sessão expirada.']);
        exit;
    }
    
    // 2. CAPTURA DOS DEMAIS DADOS
    $cep         = $_POST["cep"] ?? '';
    $rua         = $_POST["rua"] ?? '';
    $bairro      = $_POST["bairro"] ?? '';
    $cidade      = $_POST["cidade"] ?? '';
    $estado      = $_POST["estado"] ?? ''; 
    $numero      = $_POST["numero"] ?? '';
    $complemento = $_POST["complemento"] ?? '';

    $id_plano    = $_POST["id_plano"] ?? '';
    $nome_plano  = urlencode($_POST["nome_plano"] ?? '');
    $valor_plano = $_POST["valor_plano"] ?? '';

    // Tratamento dos campos de texto e números
    $cep_limpo  = (int)str_replace('-', '', $cep);
    $numero_int = (int)$numero; 
    
    // 3. VERIFICA SE O ALUNO JÁ TEM UM ENDEREÇO VINCULADO
    $stmtCheck = $conn->prepare("SELECT id_enderecoAluno FROM tbl_aluno WHERE id_aluno = :id_aluno");
    $stmtCheck->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
    $stmtCheck->execute();
    $id_enderecoExistente = $stmtCheck->fetchColumn();

    if ($id_enderecoExistente) {
        // SE JÁ TEM ENDEREÇO: Faz o UPDATE
        $sql = "UPDATE tbl_enderecoAluno SET 
                    rua = :rua, numero_endereco = :numero, bairro = :bairro, 
                    cep = :cep, cidade = :cidade, uf = :uf, complemento = :complemento
                WHERE id_enderecoAluno = :id_endereco;";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_endereco", $id_enderecoExistente, PDO::PARAM_INT);
    } else {
        // SE NÃO TEM ENDEREÇO: Faz o INSERT com cálculo de ID nativo padrão ANSI (Funciona em todos os bancos)
        $sql = "INSERT INTO tbl_enderecoAluno 
                    (rua, numero_endereco, bairro, cep, cidade, uf, complemento, id_enderecoAluno)
                VALUES 
                    (:rua, :numero, :bairro, :cep, :cidade, :uf, :complemento, 
                     (SELECT COALESCE(MAX(id_enderecoAluno), 0) + 1 FROM tbl_enderecoAluno AS t)
                    );";

        $stmt = $conn->prepare($sql);
    }
    
    // 4. VINCULAÇÃO DE VALORES COMUNS
    $stmt->bindValue(":rua", $rua);
    $stmt->bindValue(":numero", $numero_int, PDO::PARAM_INT);
    $stmt->bindValue(":bairro", $bairro);
    $stmt->bindValue(":cep", $cep_limpo, PDO::PARAM_INT);
    $stmt->bindValue(":cidade", $cidade);
    $stmt->bindValue(":uf", $estado); 
    $stmt->bindValue(":complemento", $complemento);
    $stmt->execute();
    
    // 5. SE FOI UM INSERT, VINCULA O ID RECÉM CRIADO NA TABELA ALUNO
    if (!$id_enderecoExistente) {
        // Busca qual foi o ID gerado na tabela de endereços
        $stmtMax = $conn->query("SELECT MAX(id_enderecoAluno) FROM tbl_enderecoAluno");
        $novo_id_endereco = (int)$stmtMax->fetchColumn();

        $stmtUpdateAluno = $conn->prepare("UPDATE tbl_aluno SET id_enderecoAluno = :novo_id_endereco WHERE id_aluno = :aluno_id");
        $stmtUpdateAluno->bindParam(':novo_id_endereco', $novo_id_endereco, PDO::PARAM_INT);
        $stmtUpdateAluno->bindParam(':aluno_id', $id_aluno, PDO::PARAM_INT);
        $stmtUpdateAluno->execute();
    }
    
    // 6. RETORNO DE SUCESSO
    echo json_encode([
        'status' => 'sucesso',
        'mensagem' => 'Endereço atualizado com sucesso!',
        'redirect_url' => "../aluno/pagamento.php?id_plano={$id_plano}&nome_plano={$nome_plano}&valor_plano={$valor_plano}&aluno_id={$id_aluno}"
    ]);
    exit;

} catch (PDOException $e) {
    // Se der erro no banco de dados, o erro real será enviado no JSON para você conseguir ler
    http_response_code(500);
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Erro de banco de dados: ' . $e->getMessage()
    ]);
    exit;
}
?>