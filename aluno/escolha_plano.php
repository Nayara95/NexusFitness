<?php

session_start();
 require_once '../autenticacao/conexao.php'; 

// Tenta pegar o ID de qualquer uma das variações de nomes de sessão mais comuns
$aluno_id = $_GET['aluno_id'] ?? $_SESSION['id_aluno'] ?? $_SESSION['id'] ?? $_SESSION['aluno_id'] ?? null;

//   VERIFICAÇÃO DE ENDEREÇO EXISTENTE ---
$ja_tem_endereco = false;
if (!empty($aluno_id)) {
    try {
        $conn = conectar();
        // Consulta se o aluno já tem o ID do endereço preenchido na tabela aluno
        $stmtCheckEnd = $conn->prepare("SELECT id_enderecoAluno FROM tbl_aluno WHERE id_aluno = :id_aluno");
        $stmtCheckEnd->bindParam(':id_aluno', $aluno_id, PDO::PARAM_INT);
        $stmtCheckEnd->execute();
        $id_endereco_vinculado = $stmtCheckEnd->fetchColumn();

        if ($id_endereco_vinculado) {
            $ja_tem_endereco = true; // Aluno já tem endereço cadastrado!
        }
    } catch (PDOException $e) {
        error_log("Erro ao checar endereço do aluno: " . $e->getMessage());
    }
}
// ------------------------------------------------

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
 <?php
         include ('header.php')    
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
            // LOOP PARA GERAR OS BLOCOS DE PLANO DINAMICAMENTE
            if (!empty($planos)): 
                foreach ($planos as $plano):
                    // Formata o valor para exibição em moeda (R$ 0,00)
                    $valor_formatado = number_format($plano['valor_plano'], 2, ',', '.');
                    
                    // Converte a observação (se contiver pontos ou quebras de linha) em itens de lista
                    $observacoes_lista = explode(';', $plano['observacao']);

                    // --- ADIÇÃO: DEFINE O DESTINO DO FORMULÁRIO DINAMICAMENTE ---
                    // Se já tiver endereço vai para pagamento.php, senão vai para enderecoPg.php
                    $action_destino = $ja_tem_endereco ? "pagamento.php" : "enderecoPg.php";
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

                <form action="<?php echo $action_destino; ?>" method="GET" class="form-plano">
                    
                    <input type="hidden" name="id_plano" value="<?php echo htmlspecialchars ($plano['id_plano']); ?>">
                    <input type="hidden" name="nome_plano" value="<?php echo htmlspecialchars($plano['nome_plano']); ?>">
                    <input type="hidden" name="valor_plano" value="<?php echo htmlspecialchars($plano['valor_plano']); ?>">
                    <input type="hidden" name="aluno_id" value="<?php echo htmlspecialchars($aluno_id); ?>">
                    
                    <div class="btn-escolha">
                       
                    <button type="submit" class="btn-escolha-plano">Assinar</button>
    
    
                    <a href="gerar_pdf.php?nome_plano=<?php echo urlencode($plano['nome_plano']); ?>&valor_plano=<?php echo $valor_formatado; ?>" 
                    style="display:block; margin-top:15px; text-decoration:none; color: #f03c3c; font-weight: bold; font-size: 14px;">
                    📄 Baixar Detalhes (PDF)
                    </a>
                    
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
    <?php include ('footer.php');
    
    ?>
</body>
</html>