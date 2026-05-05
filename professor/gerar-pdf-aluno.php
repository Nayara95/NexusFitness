<?php
session_start();
require_once '../dompdf/vendor/autoload.php';
include '../autenticacao/conexao.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$conn = conectar();

// Verifica permissão
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

// Recebe o ID do aluno (pode vir via POST ou GET)
$alunoId = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
if ($alunoId <= 0) {
    die('Aluno inválido.');
}

// Busca dados do aluno
$sqlAluno = "SELECT id_aluno, nome, email FROM tbl_aluno WHERE id_aluno = :id";
$stmtAluno = $conn->prepare($sqlAluno);
$stmtAluno->execute(['id' => $alunoId]);
$aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    die('Aluno não encontrado.');
}

// Busca medições físicas do aluno
$sqlMedicoes = "SELECT altura, peso, braco, abdomen, perna, data_alteracao 
                FROM tbl_fisicoAluno 
                WHERE id_aluno = :id 
                ORDER BY data_alteracao ASC";
$stmtMedicoes = $conn->prepare($sqlMedicoes);
$stmtMedicoes->execute(['id' => $alunoId]);
$medicoes = $stmtMedicoes->fetchAll(PDO::FETCH_ASSOC);

// Conteúdo HTML do PDF
$html = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Medições - ' . htmlspecialchars($aluno['nome']) . '</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 20px; }
        h1 { color: #2c3e50; border-bottom: 2px solid #c82333; padding-bottom: 5px; }
        .info-aluno { margin-bottom: 20px; background: #f5f5f5; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #c82333; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; font-size: 12px; text-align: center; color: #7f8c8d; }
    </style>
</head>
<body>
    <h1>Relatório de Medições Físicas</h1>
    <div class="info-aluno">
        <strong>Aluno:</strong> ' . htmlspecialchars($aluno['nome']) . '<br>
        <strong>E-mail:</strong> ' . htmlspecialchars($aluno['email']) . '<br>
        <strong>Data de emissão:</strong> ' . date('d/m/Y H:i:s') . '
    </div>';

if (count($medicoes) > 0) {
    $html .= '
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Altura (m)</th>
                <th>Peso (kg)</th>
                <th>Braços (cm)</th>
                <th>Abdômen (cm)</th>
                <th>Pernas (cm)</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($medicoes as $m) {
        $html .= '<tr>
                    <td>' . date('d/m/Y', strtotime($m['data_alteracao'])) . '</td>
                    <td>' . number_format($m['altura'], 2, ',', '.') . '</td>
                    <td>' . number_format($m['peso'], 2, ',', '.') . '</td>
                    <td>' . number_format($m['braco'], 2, ',', '.') . '</td>
                    <td>' . number_format($m['abdomen'], 2, ',', '.') . '</td>
                    <td>' . number_format($m['perna'], 2, ',', '.') . '</td>
                  </tr>';
    }
    $html .= '</tbody>
    </table>';
} else {
    $html .= '<p>Nenhuma medição registrada para este aluno.</p>';
}

$html .= '
    <div class="footer">
        Nexus Fitness - Sistema de Avaliação Física<br>
        Documento gerado eletronicamente.
    </div>
</body>
</html>';

// Configura e gera o PDF
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Força o download
$nomeArquivo = 'medicoes_' . preg_replace('/[^a-zA-Z0-9]/', '_', $aluno['nome']) . '_' . date('Ymd_His') . '.pdf';
$dompdf->stream($nomeArquivo, ['Attachment' => true]);
exit;