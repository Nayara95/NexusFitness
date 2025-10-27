<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php include_once '../autenticacao/auth.php';   ?>

    <title>Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css" />
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
      <section class="intro">
        <div class="intro-text">
          <h1>BEM-VINDO, ALUNO</h1>
          <p>
            Aqui você pode consultar o seu plano, treino e medições físicas.
          </p>
        
      </section>
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


