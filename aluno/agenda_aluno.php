<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de treinos</title>

    <link rel="shortcut icon" href="../imagens/faviconNexus.png" type="logo Nexus Fitness">

    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="imagens/faviconNexus.png" type="logo Nexus Fitness">

    <!-- ======== link de site de icones ======== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />

   
</head>
<body>
<?php
         include ('header.php')    
    ?>
<main>
 
<!-- ======== CABEÇALHO/HEADER ======== -->

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
          <img src="../imagens/banner1.png" alt="Tecnologia Fitness" />
         
        </div>
      </section>

    <!-- ======== Agenda de treino ======== -->

    
    <div class="agenda-semanal">
        <h1>Minha Agenda Semanal</h1>
        
        <div class="botoes-dias" id="btnDias">
            <button class="botao-dia" data-dia="segunda">Segunda-feira</button>
            <button class="botao-dia" data-dia="terca">Terça-feira</button>
            <button class="botao-dia" data-dia="quarta">Quarta-feira</button>
            <button class="botao-dia" data-dia="quinta">Quinta-feira</button>
            <button class="botao-dia" data-dia="sexta">Sexta-feira</button>
            <button class="botao-dia" data-dia="sabado">Sábado</button>
            <button class="botao-dia" data-dia="domingo">Domingo</button>
        </div>

        <p>Pré-visualização: Para detalhes dos exercícios acesse via aplicativo Nexus Fitiness</p>


        <div class="atividades-container">
            <h2 id="tituloDia">Selecione um dia para ver as atividades</h2>
            <ul id="listaAulas">
                </ul>
        </div>
    </div>






    



</main>

<!-- ======== RODAPÉ/FOOTER ======== -->


      <?php 
        include ('footer.php');      
      ?>

 <script src="../script.js"></script>

</body>
</html>