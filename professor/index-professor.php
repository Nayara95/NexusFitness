<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'professor') {
    // Se não estiver logado como professor, redireciona para a página de login
    header('Location: ../login.html');
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
        <!-- Menu dropdown (login) -->
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
        <div class="container">
            <h2>Bem-vindo, Professor!</h2>
            <p>Este é o seu painel de controle. Utilize o menu acima para navegar entre as seções.</p>
            <p>Email logado: <?php echo htmlspecialchars($email); ?></p>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Nexus Fitness. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
