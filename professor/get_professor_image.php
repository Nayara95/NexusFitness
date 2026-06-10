<?php
session_start();
require_once '../autenticacao/conexao.php';

if (isset($_GET['id']) || isset($_SESSION['id_professor'])) {
    $conn = conectar();
    
    $id_professor = $_GET['id'] ?? $_SESSION['id_professor'];

    $stmt = $conn->prepare("SELECT foto FROM tbl_professor WHERE id_professor = :id");
    $stmt->bindParam(':id', $id_professor, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->bindColumn('foto', $fotoData, PDO::PARAM_LOB);
    $result = $stmt->fetch(PDO::FETCH_BOUND);

    if ($result && !empty($fotoData)) {
        
        header('Content-Type: image/jpeg'); 
        echo $fotoData;
        exit;
    }
}

$defaultImage = '../imagens/semfoto.png';
if (file_exists($defaultImage)) {
    header('Content-Type: image/png');
    readfile($defaultImage);
} else {
    // Fallback caso a imagem padrão não seja encontrada
    header("HTTP/1.0 404 Not Found");
}
exit;
?>
