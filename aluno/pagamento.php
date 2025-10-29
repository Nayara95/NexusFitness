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

                <button type="submit" id="btnPagarPix">Já Paguei (Notificar)</button>
            </div>
            
        </form>



           
    </div>

   
    <script src="../script.js"></script>

      <script> //direcionado os botões para suas paginas
        function bemvindo(url) {
            window.location.href = url;
        }
        
    </script>

</body>
</html>