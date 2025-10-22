<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ative seu plano</title>
    <link rel="shortcut icon" href="imagens/faviconNexus.png" type="logo Nexus Fitness">

    <link rel="stylesheet" href="style.css" />

    <!-- ======== link de site de icones ======== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />

   
</head>
<body>
 <!-- ======== CABEÇALHO/HEADER ======== -->
    <?php
         include ('header.php')    
    ?>

    
      <section class="intro">
        <div class="intro-text">
          <h1>Um ponto de conexão inabalável!</h1>
          <p>
            Um pioneirismo que alia força e inovação. Aqui, sua evolução é o
            nosso combustível diário!
          </p>
          <button class="btn-principal">Saiba mais</button>
        </div>
        <div class="img-banner">
          <img src="imagens/banner1.png" alt="Tecnologia Fitness" />
        </div>
      </section>



   <main>
    <section class="saude">

        <div class="text-saude-principal">
            <h1>Ative seu plano</h1>
            <p>Envie os exames solicitados pelo personal:</p>
        </div>

        <div class="saude1">

            <h3>Questionario de saúde</h3>
            <p>Descrição do questionario</p>
            
            <form action="#" method="post"enctype="#">
          
                <input type="file" class="btn-enviarArquivo" name="arquivo" id="arquivo" />
                <input type="submit" value="Enviar">
            </form>

      </div>

        <div class="saude2">

            <h3>Exame de Bioimpedância</h3>
            <p>Descrição do questionario</p>
            
            <form action="#" method="post"enctype="#">
          
                <input type="file" class="btn-enviarArquivo" name="arquivo" id="arquivo" />
                <input type="submit" value="Enviar">
            </form>

      </div>

      
        <div class="saude3">

            <h3>Atestado Médico</h3>
            <p>Descrição do questionario</p>
            
            <form action="#" method="post"enctype="#">
          
                <input type="file" class="btn-enviarArquivo" name="arquivo" id="arquivo" />
                <input type="submit" value="Enviar">
            </form>

      </div>
      














   </main>




    <div class="checkIn">
      <form action="treinoAluno">
        <label for="status">Check-ins</label>
        <input type="number" id="status" name="status" required>
      </form>
      <img src="imagens/checkIn.png" alt="Imagem de check-in">

    </div>

</main>




    





<!-- ======== RODAPÉ/FOOTER ======== -->

      <?php 
        include ('footer.php');      
      ?>

</body>
</html>