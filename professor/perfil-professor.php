<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'professor') {
    // Se não estiver logado como professor, redireciona para a página de login
    header('Location: ../login.html');
    exit;
}

// --- DADOS DE EXEMPLO DO PROFESSOR ---
// Em um sistema real, você buscaria isso de um banco de dados
$professor_info = [
    'nome' => 'João da Silva',
    'email' => $_SESSION['email'],
    'especialidade' => 'Musculação e Treinamento Funcional',
    'cref' => '123456-G/SP'
];
// -------------------------------------

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Professor - Nexus Fitness</title>
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
        <div class="profile-container">
          <h2>Meu Perfil</h2>
            <div class="profile-details">
              
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($professor_info['nome']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($professor_info['email']); ?></p>
                <p><strong>Especialidade:</strong> <?php echo htmlspecialchars($professor_info['especialidade']); ?></p>
                <p><strong>CREF:</strong> <?php echo htmlspecialchars($professor_info['cref']); ?></p>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Nexus Fitness. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
