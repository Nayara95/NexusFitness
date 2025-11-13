<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require_once '../autenticacao/conexao.php';

$conn = conectar();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate aluno_id
    if (!isset($_POST['aluno_id']) || !filter_var($_POST['aluno_id'], FILTER_VALIDATE_INT)) {
        header('Location: medicoes-alunos.php?erro=aluno_invalido');
        exit;
    }
    $aluno_id = (int)$_POST['aluno_id'];

    // Sanitize and validate measurement values
    $bracos_input = $_POST['bracos'];
    $abdomen_input = $_POST['abdomen'];
    $peso_input = $_POST['peso'];
    $altura_input = $_POST['altura'];
    $pernas_input = $_POST['pernas'];
    $data_medida_str = $_POST['data_medida'];

    $errors = [];

    // *** CORREÇÃO COMPLETA: Processamento robusto da data ***
    $data_medida_obj = DateTime::createFromFormat('Y-m-d', $data_medida_str);
    
    // Se a data for inválida ou futura, usar data atual
    $hoje = new DateTime();
    if ($data_medida_obj === false || $data_medida_obj > $hoje) {
        $data_medida_obj = $hoje;
    }

    // *** FORMATO ESPECÍFICO PARA SQL SERVER ***
    // Tentativa 1: Formato ISO básico (apenas data)
    $data_medida = $data_medida_obj->format('Y-m-d');

    if (!empty($bracos_input) && !is_numeric($bracos_input)) {
        $errors[] = "Braços deve ser um valor numérico.";
    } else {
        $bracos = empty($bracos_input) ? 0.00 : (float)$bracos_input;
    }

    if (!empty($abdomen_input) && !is_numeric($abdomen_input)) {
        $errors[] = "Abdômen deve ser um valor numérico.";
    } else {
        $abdomen = empty($abdomen_input) ? 0.00 : (float)$abdomen_input;
    }

    if (!empty($peso_input) && !is_numeric($peso_input)) {
        $errors[] = "Peso deve ser um valor numérico.";
    } else {
        $peso = empty($peso_input) ? 0.00 : (float)$peso_input;
    }

    if (!empty($altura_input) && !is_numeric($altura_input)) {
        $errors[] = "Altura deve ser um valor numérico.";
    } else {
        $altura = empty($altura_input) ? 0.00 : (float)$altura_input;
    }

    if (!empty($pernas_input) && !is_numeric($pernas_input)) {
        $errors[] = "Pernas deve ser um valor numérico.";
    } else {
        $pernas = empty($pernas_input) ? 0.00 : (float)$pernas_input;
    }

    if (!empty($errors)) {
        header('Location: medicoes-alunos.php?aluno_id=' . $aluno_id . '&erro=' . urlencode(implode(', ', $errors)));
        exit;
    }

    try {
        if (isset($_POST['dado_id']) && !empty($_POST['dado_id'])) {
            // Atualizar dados COM CONVERT explícito
            $dado_id = $_POST['dado_id'];
            $sql = "UPDATE tbl_fisicoAluno SET braco = :braco, abdomen = :abdomen, peso = :peso, altura = :altura, perna = :perna, 
                    data_alteracao = CONVERT(DATETIME, :data_medida, 120) WHERE id_fisicoAluno = :dado_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'braco' => $bracos,
                'abdomen' => $abdomen,
                'peso' => $peso,
                'altura' => $altura,
                'perna' => $pernas,
                'data_medida' => $data_medida,
                'dado_id' => $dado_id
            ]);
        } else {
            // Gerar um novo id_fisicoAluno
            $sql_max_id = "SELECT ISNULL(MAX(id_fisicoAluno), 0) + 1 AS new_id FROM tbl_fisicoAluno";
            $stmt_max_id = $conn->query($sql_max_id);
            $new_id_fisicoAluno = $stmt_max_id->fetch(PDO::FETCH_ASSOC)['new_id'];

            // Inserir novos dados COM CONVERT explícito
            $id_professor = $_SESSION['id_professor'];
            $sql = "INSERT INTO tbl_fisicoAluno (id_fisicoAluno, id_aluno, id_professor, braco, abdomen, peso, altura, perna, data_alteracao) 
                    VALUES (:id_fisicoAluno, :id_aluno, :id_professor, :braco, :abdomen, :peso, :altura, :perna, CONVERT(DATETIME, :data_medida, 120))";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'id_fisicoAluno' => $new_id_fisicoAluno,
                'id_aluno' => $aluno_id,
                'id_professor' => $id_professor,
                'braco' => $bracos,
                'abdomen' => $abdomen,
                'peso' => $peso,
                'altura' => $altura,
                'perna' => $pernas,
                'data_medida' => $data_medida
            ]);
        }

        header('Location: medicoes-alunos.php?aluno_id=' . $aluno_id . '&sucesso=1');
        exit;
    } catch (PDOException $e) {
        // *** TENTATIVA ALTERNATIVA SE A PRIMEIRA FALHAR ***
        try {
            // Formato alternativo: YYYYMMDD (sem separadores)
            $data_medida_alt = $data_medida_obj->format('Ymd');
            
            if (isset($_POST['dado_id']) && !empty($_POST['dado_id'])) {
                $dado_id = $_POST['dado_id'];
                $sql = "UPDATE tbl_fisicoAluno SET braco = :braco, abdomen = :abdomen, peso = :peso, altura = :altura, perna = :perna, 
                        data_alteracao = CONVERT(DATETIME, :data_medida) WHERE id_fisicoAluno = :dado_id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'braco' => $bracos,
                    'abdomen' => $abdomen,
                    'peso' => $peso,
                    'altura' => $altura,
                    'perna' => $pernas,
                    'data_medida' => $data_medida_alt,
                    'dado_id' => $dado_id
                ]);
            } else {
                $sql_max_id = "SELECT ISNULL(MAX(id_fisicoAluno), 0) + 1 AS new_id FROM tbl_fisicoAluno";
                $stmt_max_id = $conn->query($sql_max_id);
                $new_id_fisicoAluno = $stmt_max_id->fetch(PDO::FETCH_ASSOC)['new_id'];

                $id_professor = $_SESSION['id_professor'];
                $sql = "INSERT INTO tbl_fisicoAluno (id_fisicoAluno, id_aluno, id_professor, braco, abdomen, peso, altura, perna, data_alteracao) 
                        VALUES (:id_fisicoAluno, :id_aluno, :id_professor, :braco, :abdomen, :peso, :altura, :perna, CONVERT(DATETIME, :data_medida))";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'id_fisicoAluno' => $new_id_fisicoAluno,
                    'id_aluno' => $aluno_id,
                    'id_professor' => $id_professor,
                    'braco' => $bracos,
                    'abdomen' => $abdomen,
                    'peso' => $peso,
                    'altura' => $altura,
                    'perna' => $pernas,
                    'data_medida' => $data_medida_alt
                ]);
            }

            header('Location: medicoes-alunos.php?aluno_id=' . $aluno_id . '&sucesso=1');
            exit;

        } catch (PDOException $e2) {
            echo "Erro ao salvar dados (todas as tentativas falharam): " . $e2->getMessage();
            echo "<br>Primeira tentativa - Data: " . $data_medida;
            echo "<br>Segunda tentativa - Data: " . $data_medida_alt;
            echo "<br>Data original do POST: " . $_POST['data_medida'];
            echo "<br>SQL State: " . $e2->getCode();
            exit;
        }
    }
} else {
    header('Location: medicoes-alunos.php');
    exit;
}
?>