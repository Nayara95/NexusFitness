<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Aluno</title>
    <link rel="shortcut icon" href="../imagens/faviconNexus.png" type="logo Nexus Fitness">

    <link rel="stylesheet" href="../style.css" />

    <!-- ======== link de site de icones ======== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />

   
</head>
<body>
 <!--  CABEÇALHO -->
    <?php
         include ('header.php')    
    ?>


<!--  SEÇÃO VER CADASTRO - SEM ACESSO APENAS VISUALIZAÇÃO  -->

    <div class="perfil_container">
      <form action="cadastro" method="#">
        <label for="nome">Nome Completo</label>
        <input type="text" id="nome" name="nome" required />

        <label for="NomeSocial">Nome Social</label>
        <input type="text" id="NomeSocial" name="NomeSocial" required />

        <label for="genero">Gênero</label>
        <input type="text" id="genero" name="genero" required />

        <label for="data-nasc">Data de nascimento</label>
        <input type="date" id="data" name="data" required />

        <label for="telefone">Telefone</label>
        <input type="number" id="telefone" name="telefone" required>
     

        <p>Para acessar seus dados,baixe o aplicativo Nexus Fitness. Disponivel em: </p>
        <img src="../imagens/googlePlay.png" alt="App Nezus Fitness Google Play" />
     </form>

    </div>

    <div class="btn-plano">
      <p>Meu plano</p>
      
      <button type="submit" class="btn-plano">
        <i class="fa-solid fa-angle-down"></i>
      </button>
    </div>



    <div class="checkIn">
      <form action="treinoAluno">
        <label for="status">Check-ins</label>
        <input type="number" id="status" name="status" required>
      </form>
      <img src="../imagens/checkIn.png" alt="Imagem de check-in">

    </div>



    





<!-- ======== RODAPÉ/FOOTER ======== -->

      <?php 
        include ('footer.php');      
      ?>

</body>
</html>
