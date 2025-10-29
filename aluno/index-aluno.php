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
    
       <?php 
        include ('../footer.php');      
      ?>
  </body>
</html>


