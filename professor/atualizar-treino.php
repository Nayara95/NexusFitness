<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

require_once('../autenticacao/conexao.php');
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alunoId = filter_input(INPUT_POST, 'aluno_id', FILTER_VALIDATE_INT);

    if (!$alunoId) {
        header('Location: treino-alunos.php?error=invalid_id');
        exit;
    }

    $diasSemana = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
    $treinos = [];
    foreach ($diasSemana as $dia) {
        $treinos[$dia] = filter_input(INPUT_POST, $dia, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    try {
        // Verifica se já existe um registro de treino para o aluno
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_agendaTreino WHERE id_aluno = :id_aluno");
        $stmt->execute(['id_aluno' => $alunoId]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Atualiza o registro existente
            $sql = "UPDATE tbl_agendaTreino SET segunda = :segunda, terca = :terca, quarta = :quarta, quinta = :quinta, sexta = :sexta, sabado = :sabado, domingo = :domingo WHERE id_aluno = :id_aluno";
        } else {
            // Insere um novo registro
            $sql = "INSERT INTO tbl_agendaTreino (id_aluno, segunda, terca, quarta, quinta, sexta, sabado, domingo, id_agendaTreino, id_professor) VALUES (:id_aluno, :segunda, :terca, :quarta, :quinta, :sexta, :sabado, :domingo, (SELECT ISNULL(MAX(id_agendaTreino), 0) + 1 FROM tbl_agendaTreino), :id_professor)";
        }
        
        $stmt = $conn->prepare($sql);
        
        $params = [
            'id_aluno' => $alunoId,
            'segunda' => $treinos['segunda'],
            'terca' => $treinos['terca'],
            'quarta' => $treinos['quarta'],
            'quinta' => $treinos['quinta'],
            'sexta' => $treinos['sexta'],
            'sabado' => $treinos['sabado'],
            'domingo' => $treinos['domingo']
        ];

        if ($count == 0) {
            $params['id_professor'] = $_SESSION['id_professor']; 
        }

        $stmt->execute($params);

        header('Location: treino-alunos.php?aluno_id=' . $alunoId . '&update=success');
        exit;

    } catch (PDOException $e) {
        // Log the error and redirect with a generic error message
        error_log("Erro ao atualizar treino: " . $e->getMessage());
        header('Location: treino-alunos.php?aluno_id=' . $alunoId . '&update=error');
        exit;
    }
} else {
    // Redireciona se não for um POST
    header('Location: treino-alunos.php');
    exit;
}
?>