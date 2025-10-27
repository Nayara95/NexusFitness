<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seja Bem-Vindo!</title>
    <link rel="shortcut icon" href="../imagens/faviconNexus.png" type="logo Nexus Fitness">

    <link rel="stylesheet" href="../style.css" />

    <!-- ======== link de site de icones ======== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />

   
</head>
<body>
 <!-- ======== CABEÇALHO/HEADER ======== -->
    <?php
         include ('../header.php')    
    ?>

<main>
  <section class="intro">
        <div class="intro-text">
          <h1>Um ponto de conexão inabalável!</h1>
          <p>
            Um pioneirismo que alia força e inovação. Aqui, sua evolução é o
            nosso combustível diário!
          </p>
          <button class="btn-principal">Saiba mais</button>
        </div>
        <div class="../img-banner">
          <img src="../imagens/banner1.png" alt="Tecnologia Fitness" />
        </div>
      </section>

    <!-- ======== escolha do plano - pós cadastro======== -->

    <div class="ativaAgenda_container">
        <button type="submit" onclick="redirecionar('ativa_plano.php')" class="btn-ativa-plano">Ative seu plano</button>

        <button type="submit" onclick="redirecionar('agenda_aluno.php')" class="btn-agenda">Agenda de treino</button>
    </div>



    <div class="checkIn">
      <form action="treinoAluno">
        <label for="status">Check-ins</label>
        <input type="number" id="status" name="status" required>
      </form>
      <img src="../imagens/checkIn.png" alt="Imagem de check-in">

    </div>

</main>




    





<!-- ======== RODAPÉ/FOOTER ======== -->

      <?php 
        include ('../footer.php');      
      ?>


      <script> //direcionado os botões para suas paginas
        function redirecionar(url) {
            window.location.href = url;
        }
    </script>

</body>
</html>