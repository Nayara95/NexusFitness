<?php include_once '../autenticacao/auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meu Perfil - Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css" />
    <style>
        .profile-container {
            background-color: #fff;
            padding: 30px;
            margin: 30px auto;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .profile-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-details p {
            font-size: 1.1em;
            line-height: 1.8;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .profile-details p strong {
            color: #333;
        }
        .btn-edit {
            display: block;
            width: 180px;
            margin: 30px auto 0;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        .btn-edit:hover {
            background-color: #0056b3;
        }
    </style>
  </head>
  <body>
    <!-- ======== CABEÇALHO/HEADER ======== -->
    <header>
      <div class="logo">
        <img src="../imagens/nexus.png" alt="Logo Nexus Fitness" />
      </div>

      <div class="header-buttons">
        <!-- Menu dropdown (login) -->
        <div class="dropdown">
          <button class="dropbtn">Minha Conta▾</button>
          <div class="dropdown-content">
            <a href="../aluno/index-aluno.php">Área do Aluno</a>
            <a href="../aluno/perfil-aluno.php">Meu Perfil</a>
            <a href="../autenticacao/logout.php">Sair</a>
          </div>
        </div>
      </div>
    </header>

    <!-- ======== SEÇÃO PRINCIPAL BODY/MAIN ======== -->
    <main>
        <div class="profile-container">
            <h1>Meu Perfil</h1>
            <div class="profile-details">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['nome_aluno'] ?? 'Nome do Aluno'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email'] ?? 'email@exemplo.com'); ?></p>
                <p><strong>Plano Contratado:</strong> <?php echo htmlspecialchars($_SESSION['plano'] ?? 'Plano Padrão'); ?></p>
                <p><strong>Data de Matrícula:</strong> <?php echo htmlspecialchars($_SESSION['data_matricula'] ?? '01/01/2025'); ?></p>
            </div>
            <button class="btn-principal">Editar Informações</button>
        </div>
    </main>

    <!-- ======== RODAPÉ/FOOTER ======== -->
    <footer>
      <div class="footer-links">
        <a href="#">Sobre</a>
        <a href="#">Planos</a>
        <a href="#">Contato</a>
        <a href="#">Termos de Uso</a>
        <a href="#">Privacidade</a>
      </div>
      <br />
      <!-- ======== Redes Sociais ======== -->
      <div class="footer-img">
        <a href="#"><img src="../imagens/facebook.png" alt="Facebook" /></a>
        <a href="#"><img src="../imagens/instagram.png" alt="Instagram" /></a>
      </div>
      <p>© 2025 Nexus Fitness — Todos os direitos reservados.</p>
    </footer>
  </body>
</html>
