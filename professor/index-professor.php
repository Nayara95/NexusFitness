<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Professor - Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css">
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
        <div class="painel-simples">
            <h1>Painel do Professor</h1>
            <p class="bem-vindo">Bem-vindo, <?php echo htmlspecialchars($email); ?>!</p>
            
            <div class="botoes-simples">
                <a href="medicoes-alunos.php" class="btn-simples">Medições dos Alunos</a>
                <a href="treino-alunos.php" class="btn-simples">Treinos dos Alunos</a>
            </div>
        </div>
    </main>

    <footer>
      
      <a>© 2025 Nexus Fitness — Todos os direitos reservados.</a>
     
    </footer>
  
</body>
</html>