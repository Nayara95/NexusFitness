<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha seu plano</title>
    <link rel="shortcut icon" href="../imagens/faviconNexus.png" type="logo Nexus Fitness">

    <link rel="stylesheet" href="../style.css" />
   
</head>
<body>
 <!-- ======== CABEÇALHO/HEADER ======== -->
    <?php
         include ('../header.php')    
    ?>
<main>
 <!-- ======== SEÇÃO PRINCIPAL BODY/MAIN ======== -->
   
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




  <div class="titulo_plano">
    <h2 >Escolha seu plano</h2>
  </div>

  <div class="escolha_plano_container">

    <div class="planos">

      <div class="nexus_equilibrio">
        <h3><img src="../imagens/imgPlano1.png" alt="imagem do logo do plano">Nexus Equilibrio</h3>
        <p>R$ 000,00/mês</p>
        <ul>
          <li>Acesso completo às instalações</li>
          <li>1 sessão de personal trainer por mês</li>
          <li>Acesso ao aplicativo Nexus Fitness</li>
        
         
        </ul>

        <button type="submit" class="btn-escolha-plano">Ver detalhes</button>
      </div>

      <div class="nexus_elite">
        <h3><img src="../imagens/imgPlano2.png" alt="imagem do logo do plano">Nexus Elite</h3>
        <p>R$ 000,00/mês</p>
        <ul>
          <li>Acesso completo às instalações para até 4 membros</li>
          <li>2 sessões de personal trainer por mês</li>
          <li>Acesso ao aplicativo Nexus Fitness</li>
        </ul>
        <button type="submit" class="btn-escolha-plano">Ver detalhes</button>
      </div>

    </div>
  </div>
</main>





<!-- ======== RODAPÉ/FOOTER ======== -->

      <?php 
        include ('../footer.php');      
      ?>

</body>
</html>