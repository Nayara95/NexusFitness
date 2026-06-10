<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../autenticacao/conexao.php';

// Define que o retorno será estritamente em formato JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_plano = $_POST['id_plano'] ?? null;
    $aluno_id = $_POST['aluno_id'] ?? ($_SESSION['id_aluno'] ?? null);

    if (!empty($id_plano) && !empty($aluno_id)) {
        try {
            $conn = conectar();
            
            // Query que vincula o plano escolhido ao perfil do aluno
            $sql = "UPDATE tbl_aluno SET id_plano = :id_plano WHERE id_aluno = :id_aluno";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_plano', $id_plano, PDO::PARAM_INT);
            $stmt->bindParam(':id_aluno', $aluno_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Perfil atualizado e plano liberado!']);
                exit;
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Não foi possível atualizar o plano no banco de dados.']);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno no banco de dados.']);
            exit;
        }
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos (ID do plano ou do aluno ausente).']);
        exit;
    }
}

echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida.']);
exit;