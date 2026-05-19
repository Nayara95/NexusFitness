<?php
// Inclui a biblioteca Dompdf
require_once(__DIR__ . "/dompdf/autoload.inc.php");
use Dompdf\Dompdf;

// Captura os dados 
$nomePlano = $_GET['nome_plano'] ?? 'Plano Nexus';
$valorPlano = $_GET['valor_plano'] ?? '0,00';

//  design do que vai aparecer no PDF
$html = "
<style>
    body { font-family: 'Helvetica', sans-serif; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #f03c3c; padding-bottom: 10px; }
    .header img { width: 100px; }
    .box { margin-top: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
    h1 { color: #f03c3c; margin-bottom: 5px; }
    .footer { margin-top: 50px; font-size: 12px; text-align: center; color: #777; }
</style>

<div class='header'>
    <h1>NEXUS FITNESS</h1>
    <p>Um ponto de conexão inabalável!</p>
    
</div>

<div class='box'>
    <h2>Confirmação de Plano Selecionado</h2>
    <p><strong>Plano:</strong> " . htmlspecialchars($nomePlano) . "</p>
    <p><strong>Valor Mensal:</strong> R$ " . htmlspecialchars($valorPlano) . "</p>
    <hr>
    <p><small>Este documento é apenas um informativo dos benefícios do plano escolhido.</small></p>
</div>

<div class='footer'>
    Gerado em: " . date('d/m/Y H:i:s') . "<br>
    Nexus Fitness - Inovação e Força
</div>
";

// Inicializa o Dompdf
$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper('A4', 'portrait');
$dompdf->render();

// Faz o navegador baixar o arquivo automaticamente
$dompdf->stream("Plano_Nexus_" . $nomePlano . ".pdf", ["Attachment" => true]);
exit;