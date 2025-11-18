<?php
session_start();
require_once '../autenticacao/conexao.php';

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

$email = $_SESSION['email'];
$id_professor = $_SESSION['id_professor'] ?? 0;

$nome_professor = '';
if ($id_professor > 0) {
    try {
        $conn = conectar();
        $stmt = $conn->prepare("SELECT nome FROM tbl_professor WHERE id_professor = :id");
        $stmt->bindParam(':id', $id_professor, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado && isset($resultado['nome'])) {
            $nome_professor = $resultado['nome'];
        }
    } catch (PDOException $e) {
        // Em caso de erro, podemos logar e continuar com o nome em branco
        error_log("Erro ao buscar nome do professor: " . $e->getMessage());
    }
}

// Fallback para o email caso o nome não seja encontrado
if (empty($nome_professor)) {
    $nome_professor = htmlspecialchars($email);
} else {
    $nome_professor = htmlspecialchars($nome_professor);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Professor - Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="painel-professor.css">
</head>
<body>
     <header>
      <div class="logo">
        <img src="../imagens/nexus.png" alt="Logo Nexus Fitness" />
      </div>
      <div class="header-buttons">
        <div class="dropdown">
          <button class="dropbtn">Minha Conta▾</button>
          <div class="dropdown-content">
            <a href="../professor/index-professor.php">Área do Professor</a>
            <a href="../professor/perfil-professor.php">Meu Perfil</a>
            <a href="../autenticacao/logout.php">Sair</a>
          </div>
        </div>
      </div>
    </header>

    <main>
        <div class="profile-card">
            <img src="get_professor_image.php" alt="Foto do Professor" class="profile-pic">
            <h2><?php echo $nome_professor; ?></h2>
            <p><?php echo htmlspecialchars($email); ?></p>
        </div>

        <div class="actions-container">
            <a href="medicoes-alunos.php" class="action-card">
                Medições dos Alunos
            </a>
            <a href="treino-alunos.php" class="action-card">
                Treinos dos Alunos
            </a>
        </div>
    </main>

    <footer>
      <a>© 2025 Nexus Fitness — Todos os direitos reservados.</a>
    </footer>
  
</body>
</html>