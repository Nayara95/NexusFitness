<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endereço de cobrança</title>
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

            <form class="dados-form">
                <h2>Endereço de cobrança:</h2>

                <label for="cep">CEP</label>
                <div class="input-group-cep">
                    <input type="text" id="cep" name="cep" required>
                    <a href="#" class="link-cep">não sei meu CEP</a>
                </div>

                <div class="input-row">
                    <div class="input-field">
                        <label for="estado">Estado</label>
                        <input type="text" id="estado" name="estado">
                    </div>
                    <div class="input-field">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade">
                    </div>
                </div>

                <label for="bairro">Bairro</label>
                <input type="text" id="bairro" name="bairro">

                <label for="rua">Rua</label>
                <input type="text" id="rua" name="rua">

                <div class="input-row">
                    <div class="input-field">
                        <label for="numero">Número</label>
                        <input type="text" id="numero" name="numero">
                    </div>
                    <div class="input-field">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento">
                    </div>
                </div>
            </form>

                <button type="submit"  onclick="pg('pagamento.php')" class="btn-agenda" class="payment-button">
                    Forma de pagamento
                </button>

        </div>
    </div>


    
      <script> //direcionado os botões para suas paginas provisorio
        function pg(url) {
            window.location.href = url;
        }
    </script>

   

</body>
</html>