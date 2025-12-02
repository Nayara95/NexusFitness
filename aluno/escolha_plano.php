<?php

// Chamando o arquivo escolha_plano.php
session_start();

// 1. INCLUIR CONEXÃO
 require_once '../autenticacao/conexao.php'; 

// 2. OBTER O ID DO ALUNO necessario para o pagamento
// O ID é passado via URL após o cadastro, mas o usuário também pode estar logado.
$aluno_id = $_GET['aluno_id'] ?? ($_SESSION['id_aluno'] ?? null);

// 3. BUSCAR DADOS DOS PLANOS NO BANCO DE DADOS
$planos = [];
try {
    $conn = conectar();
    
    // Consulta SQL para buscar todos os planos
    $sql = "SELECT id_plano, nome_plano, valor_plano, observacao FROM tbl_plano";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $planos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em caso de erro, você pode logar a mensagem ou definir um array vazio
    error_log("Erro ao buscar planos: " . $e->getMessage());
    $planos = [];
}
?>


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
         include ('header.php')    
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


      <?php 
            // 4. LOOP PARA GERAR OS BLOCOS DE PLANO DINAMICAMENTE
            if (!empty($planos)): 
                foreach ($planos as $plano):
                    // Formata o valor para exibição em moeda (R$ 0,00)
                    $valor_formatado = number_format($plano['valor_plano'], 2, ',', '.');
                    
                    // Converte a observação (se contiver pontos ou quebras de linha) em itens de lista
                    $observacoes_lista = explode(';', $plano['observacao']);
            ?>
            
            <div class="nexus_plano" id="plano-<?php echo $plano['id_plano']; ?>">
                <h3>
                    <img src="../imagens/imgPlano1.png" alt="imagem do logo do plano">
                    <?php echo htmlspecialchars($plano['nome_plano']); ?>
                </h3>
                <p>R$ <?php echo $valor_formatado; ?>/mês</p>
                
                <ul>
                    <?php 
                    // Exibe os itens de observação
                    foreach ($observacoes_lista as $item) {
                        echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
                    }
                    ?>
                </ul>

                <form action="enderecoPg.php" method="GET" class="form-plano">
                    
                    <input type="hidden" name="id_plano" value="<?php echo $plano['id_plano']; ?>">
                    <input type="hidden" name="nome_plano" value="<?php echo htmlspecialchars($plano['nome_plano']); ?>">
                    <input type="hidden" name="valor_plano" value="<?php echo $plano['valor_plano']; ?>">
                    <input type="hidden" name="aluno_id" value="<?php echo htmlspecialchars($aluno_id); ?>">
                    
                    <div class="btn-escolha">
                        <button type="submit" class="btn-escolha-plano">Assinar</button>
                    </div>
                </form>
            </div>

            <?php 
                endforeach;
            else:
            ?>
            <p>Nenhum plano disponível no momento.</p>
            <?php endif; ?>
            
        </div>
    </div>
</main>
    <?php include ('footer.php'); ?>
</body>
</html>