<?php
session_start();
require_once '../autenticacao/conexao.php';

// Verifica se o ID do professor foi fornecido (ou pode pegar da sessão)
if (isset($_GET['id']) || isset($_SESSION['id_professor'])) {
    $conn = conectar();
    
    $id_professor = $_GET['id'] ?? $_SESSION['id_professor'];

    // Busca os dados da foto do professor no banco de dados
    $stmt = $conn->prepare("SELECT foto FROM tbl_professor WHERE id_professor = :id");
    $stmt->bindParam(':id', $id_professor, PDO::PARAM_INT);
    $stmt->execute();
    
    // Associa a coluna 'foto' a uma variável
    $stmt->bindColumn('foto', $fotoData, PDO::PARAM_LOB);
    $result = $stmt->fetch(PDO::FETCH_BOUND);

    if ($result && !empty($fotoData)) {
        // Determina o tipo de conteúdo (assumindo JPEG, mas poderia ser melhorado)
        // Para uma solução mais robusta, o tipo MIME poderia ser armazenado no banco
        header('Content-Type: image/jpeg'); 
        echo $fotoData;
        exit;
    }
}

// Se não houver foto ou ID, exibe uma imagem padrão
// O caminho deve ser relativo ao local do script get_professor_image.php
$defaultImage = '../imagens/professor-nexus.png';
if (file_exists($defaultImage)) {
    header('Content-Type: image/png');
    readfile($defaultImage);
} else {
    // Fallback caso a imagem padrão não seja encontrada
    header("HTTP/1.0 404 Not Found");
}
exit;
?>
