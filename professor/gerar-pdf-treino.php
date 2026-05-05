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

// Recebe ID
$alunoId = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($alunoId <= 0) {
    die('Aluno inválido.');
}

// Buscar aluno
$stmt = $conn->prepare("SELECT id_aluno, nome, email FROM tbl_aluno WHERE id_aluno = :id");
$stmt->execute(['id' => $alunoId]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    die('Aluno não encontrado.');
}

// Buscar treino
$stmt = $conn->prepare("
    SELECT segunda, terca, quarta, quinta, sexta, sabado, domingo 
    FROM tbl_agendaTreino 
    WHERE id_aluno = :id
");
$stmt->execute(['id' => $alunoId]);
$treino = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar saúde (última medição)
$stmt = $conn->prepare("
    SELECT peso, altura 
    FROM tbl_fisicoAluno 
    WHERE id_aluno = :id 
    ORDER BY data_alteracao DESC
");
$stmt->execute(['id' => $alunoId]);
$saude = $stmt->fetch(PDO::FETCH_ASSOC);

// HTML do PDF
$html = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; margin: 20px; }
h1 { color: #2c3e50; border-bottom: 2px solid #c82333; }
.info { background: #f5f5f5; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
.dia { margin-bottom: 15px; }
.dia h3 { background: #c82333; color: white; padding: 5px; }
.footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
</style>
</head>
<body>

<h1>Ficha de Treino</h1>

<div class="info">
<strong>Aluno:</strong> ' . htmlspecialchars($aluno['nome']) . '<br>
<strong>Email:</strong> ' . htmlspecialchars($aluno['email']) . '<br>
<strong>Data:</strong> ' . date('d/m/Y H:i:s') . '<br>';

if ($saude) {
    $html .= '
    <strong>Peso:</strong> ' . $saude['peso'] . ' kg<br>
    <strong>Altura:</strong> ' . $saude['altura'] . ' m<br>';
}

$html .= '</div>';

// Dias da semana
$dias = [
    'segunda' => 'Segunda-feira',
    'terca' => 'Terça-feira',
    'quarta' => 'Quarta-feira',
    'quinta' => 'Quinta-feira',
    'sexta' => 'Sexta-feira',
    'sabado' => 'Sábado',
    'domingo' => 'Domingo'
];

if ($treino) {
    foreach ($dias as $key => $nomeDia) {
        $conteudo = !empty($treino[$key]) ? nl2br(htmlspecialchars($treino[$key])) : 'Sem treino';

        $html .= '
        <div class="dia">
            <h3>' . $nomeDia . '</h3>
            <p>' . $conteudo . '</p>
        </div>';
    }
} else {
    $html .= '<p>Nenhuma ficha de treino cadastrada.</p>';
}

$html .= '
<div class="footer">
Nexus Fitness - Sistema de Treinos<br>
Documento gerado automaticamente
</div>

</body>
</html>
';

// Gerar PDF
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Nome do arquivo
$nomeArquivo = 'treino_' . preg_replace('/[^a-zA-Z0-9]/', '_', $aluno['nome']) . '.pdf';

// Download
$dompdf->stream($nomeArquivo, ['Attachment' => true]);
exit;