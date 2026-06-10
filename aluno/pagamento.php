<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Se os dados vieram pela URL (GET), nós os guardamos imediatamente na Sessão
if (isset($_GET['id_plano'])) {
    $_SESSION['id_plano_escolhido'] = $_GET['id_plano'];
    $_SESSION['nome_plano_escolhido'] = $_GET['nome_plano'] ?? 'Plano Selecionado';
    $_SESSION['valor_plano_escolhido'] = $_GET['valor_plano'] ?? '0.00';
}
if (isset($_GET['aluno_id'])) {
    $_SESSION['id_aluno'] = $_GET['aluno_id'];
}

// 2. Agora capturamos os dados priorizando a Sessão (assim, mesmo que a URL limpe, os dados continuam vivos)
$id_plano_final   = $_SESSION['id_plano_escolhido'] ?? '';
$nome_plano_final  = $_SESSION['nome_plano_escolhido'] ?? 'Plano Nexus';
$valor_plano_final = $_SESSION['valor_plano_escolhido'] ?? '0.00';
$aluno_id          = $_SESSION['id_aluno'] ?? null;

// Formata o preço para o padrão brasileiro
$valor_formatado = "R$ " . number_format((float)$valor_plano_final, 2, ',', '.');
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endereço de cobrança</title>
     <link rel="shortcut icon" href="../imagens/faviconNexus.png" type="logo Nexus Fitness">
    <link rel="stylesheet" href="../style.css">

 <!-- ======== link de site de icones ======== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
     

</head>

<body class="endereco-body">



    <div class="container">


            <div class="logo-left">
                <img src="../imagens/logoBranco.png" alt="Logo Nexus Fitness" class="logo-image">
            
             </div>

        
        <div class="endereco-content">


            <div class="resumo-cobranca">
                
                <div class="resumo-item">
                    <span>Valor a pagar</span>
                    <span class="valor">R$ 00,00</span>
                </div>
                <div class="resumo-item">
                    <span>Plano</span>
                    <span class="nomePlano">Nexus Elite</span>
                </div>
            </div>

    <!-- Opções de Pagamento - BOTOES -->

         <div class="opcoes-pagamento" id="opcoesPagamento">
            <button type="button" class="tab-pagamento ativo" data-metodo="cartao">
                Cartão de Crédito
            </button>
            <button type="button" onclick="bemvindo('boasvindasAluno.php')"  class="tab-pagamento" data-metodo="pix">
                PIX
            </button>
        </div>

         <form id="formPagamento">
         <input type="hidden" id="final_id_plano" name="id_plano" value="<?php echo htmlspecialchars($id_plano_final); ?>">
        
            <!-- Opções cartão -->

            <div id="conteudo-cartao" class="conteudo-metodo ativo">
                <h2>Dados do Cartão</h2>
                
                <div class="grupo-nome-numero">
                    <label for="nome">Nome no Cartão:</label>
                    <input type="text" id="nome" placeholder="Nome no cartão" required>
                </div>

                <div class="grupo-nome-numero">
                    <label for="numero">Número do Cartão:</label>
                    <input type="text" id="numero" placeholder="0000 0000 0000 0000" maxlength="19" inputmode="numeric" required>
                    <span class="feedback-msg" id="feedbackNumero"></span>
                </div>

                <div class="grupo-valide-cvv">
                    <div class="grupo-nome-numero">
                        <label for="validade">Validade:</label>
                        <input type="text" id="validade" placeholder="MM/AA" maxlength="5" inputmode="numeric" required>
                    </div>

                    <div class="grupo-nome-numero">
                        <label for="cvv">CVV:</label>
                        <input type="text" id="cvv" placeholder="123" maxlength="4" inputmode="numeric" required>
                    </div>
                </div>
                
                <button type="submit" id="btnPagarCartao"> finalizar</button>
            </div>

             <!-- Opções PIX -->

              <div id="conteudo-pix" class="conteudo-metodo">
                <h2>Instruções para PIX</h2>
                
                <p class="instrucao-pix">Escaneie o código ou use a chave Copia e Cola para pagar:</p>
                
                <div class="qrcode-box">
                    <img src="#" alt="QR Code PIX">
                </div>

                <div class="copia-cola-area">
                    <input type="text" id="chavePix" 
                           value="#"
                           readonly>
                    <button type="button" id="btnCopiar">
                        Copiar Chave
                    </button>
                </div>
                
                <p class="aviso-expiracao">Prazo de expiração: 30 minutos.</p>

               <!--  //Simulador para pagamento do plano escolhido, redireciona para a área do aluno  -->
                <button type="submit" id="btnPagarPix">Já Paguei (Notificar)</button>
                
                
                </div>
            

                <div class="voltar-area">
                <button type="button" id="btnvoltar" onclick="bemvindo('escolha_plano.php')">
                        Voltar
                </button>
            
        </form>



           
    </div>

    

   
    <script src="../script.js">

    </script>

    <script>
document.getElementById('formPagamento').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            document.getElementById('btnPagarCartao').disabled = true;
            document.getElementById('btnPagarPix').disabled = true;

            fetch('atualizar_plano.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.sucesso) {
                    alert("Pagamento Simulado com Sucesso! Seu perfil foi atualizado.");
                    window.location.href = 'boasvindasAluno.php';
                } else {
                    alert("Erro no processamento: " + data.mensagem);
                    document.getElementById('btnPagarCartao').disabled = false;
                    document.getElementById('btnPagarPix').disabled = false;
                }
            })
            .catch(error => {
                console.error("Erro na comunicação:", error);
                alert("Houve um erro ao processar a simulação do plano.");
                document.getElementById('btnPagarCartao').disabled = false;
                document.getElementById('btnPagarPix').disabled = false;
            });
        });
    </script>
</body>
</html>