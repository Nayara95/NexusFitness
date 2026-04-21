<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

require_once('../autenticacao/conexao.php');
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alunoId = filter_input(INPUT_POST, 'aluno_id', FILTER_VALIDATE_INT);
    if (!$alunoId) {
        $_SESSION['treino_mensagem_erro'] = 'ID do aluno inválido.';
        header('Location: treino-alunos.php');
        exit;
    }

    $_SESSION['treino_aluno_id'] = $alunoId;

    $diasSemana = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
    $treinos = [];
    foreach ($diasSemana as $dia) {
        // O campo sempre existirá no POST (mesmo vazio)
        $treinos[$dia] = isset($_POST[$dia]) ? trim($_POST[$dia]) : '';
    }

    try {
        // Verifica se já existe registro
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_agendaTreino WHERE id_aluno = :id_aluno");
        $stmt->execute(['id_aluno' => $alunoId]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $sql = "UPDATE tbl_agendaTreino SET 
                    segunda = :segunda, terca = :terca, quarta = :quarta, 
                    quinta = :quinta, sexta = :sexta, sabado = :sabado, domingo = :domingo 
                    WHERE id_aluno = :id_aluno";
        } else {
            $sql = "INSERT INTO tbl_agendaTreino (id_aluno, segunda, terca, quarta, quinta, sexta, sabado, domingo, id_agendaTreino, id_professor) 
                    VALUES (:id_aluno, :segunda, :terca, :quarta, :quinta, :sexta, :sabado, :domingo, 
                    (SELECT ISNULL(MAX(id_agendaTreino), 0) + 1 FROM tbl_agendaTreino), :id_professor)";
        }
        
        $stmt = $conn->prepare($sql);
        $params = [
            'id_aluno' => $alunoId,
            'segunda'  => $treinos['segunda'],
            'terca'    => $treinos['terca'],
            'quarta'   => $treinos['quarta'],
            'quinta'   => $treinos['quinta'],
            'sexta'    => $treinos['sexta'],
            'sabado'   => $treinos['sabado'],
            'domingo'  => $treinos['domingo']
        ];
        if ($count == 0) {
            $params['id_professor'] = $_SESSION['id_professor'];
        }
        $stmt->execute($params);

        unset($_SESSION['treino_edit_mode']);
        $_SESSION['treino_mensagem_sucesso'] = 'Treino atualizado com sucesso!';
        header('Location: treino-alunos.php');
        exit;

    } catch (PDOException $e) {
        error_log("Erro ao atualizar treino: " . $e->getMessage());
        $_SESSION['treino_mensagem_erro'] = 'Erro ao salvar o treino. Tente novamente.';
        header('Location: treino-alunos.php');
        exit;
    }
} else {
    header('Location: treino-alunos.php');
    exit;
}
?>