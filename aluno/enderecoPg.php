
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Captura os dados que estão na barra de endereços (URL)
$id_plano_final = $_GET['id_plano'] ?? '';
$nome_plano_final = $_GET['nome_plano'] ?? 'Plano Selecionado';
$valor_plano_final = $_GET['valor_plano'] ?? '0.00';
$aluno_id = $_GET['aluno_id'] ?? ($_SESSION['id_aluno'] ?? '');

// Formata o preço recebido para aparecer como R$ XX,XX na tela
$valor_formatado = "R$ " . number_format((float)$valor_plano_final, 2, ',', '.');
?>


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
                    <span class="valor"><?php echo htmlspecialchars($valor_formatado); ?>
                </span>
                </div>
                <div class="resumo-item">
                    <span>Plano</span>
                    <span class="nomePlano"><?php echo htmlspecialchars($nome_plano_final); ?></span>
                </div>
            </div>

           <!-- <!<form action="pagamento.php" method="POST" id="id_aluno" name="id_aluno" value="<?php echo $_SESSION['id_aluno']; ?>" >

                <h2>Endereço de cobrança:</h2> -->

                <form action="pagamento.php" method="GET" id="formEndereco">
                    <input type="hidden" name="id_aluno" value="<?php echo $_SESSION['id_aluno'] ?? ''; ?>">
                    <input type="hidden" id="input_id_plano" name="id_plano" value=""> 

                    <h2>Endereço de cobrança:</h2>


                <label for="cep">CEP</label>
                <div class="input-group-cep">

                    <!-- Vinculando a função buscarCEP ao evento onblur --> 
                    <input type="text" id="cep" name="cep" required onblur="buscarCEP(this.value)">
                    <a href="https://buscacep.correios.com.br/sistemas/buscacep/" target="_blank" class="link-cep">não sei meu CEP</a>

                </div>

                <div class="input-row">
                    <div class="input-field">
                        <label for="estado">Estado</label>
                        <input type="text" id="estado" name="estado" readonly> <!-- readonly= campo somente leitura -->
                    </div>
                    <div class="input-field">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade">
                    </div>
                </div>

                <label for="bairro">Bairro</label>
                <input type="text" id="bairro" name="bairro" readonly >

                <label for="rua">Rua</label>
                <input type="text" id="rua" name="rua" readonly >

                <div class="input-row">
                    <div class="input-field">
                        <label for="numero">Número</label>
                        <input type="text" id="numero" name="numero" >
                    </div>
                    <div class="input-field">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento" >
                    </div>
                </div>

                <button type="submit" class="btn-agenda payment-button" style="margin-top: 5px;">Forma de pagamento</button>
            </form>

        </div>
    </div>








<script> /* linkando pagina de pagamento  */
    function pg(url) {
        window.location.href = url;
    }     
</script>

<script>
document.addEventListener('DOMContentLoaded',() =>{ 
    const form = document.getElementById('formEndereco');
    // 1. Obtém os parâmetros da URL (Query String)
    const urlParams = new URLSearchParams(window.location.search);
    
    const nomePlano = urlParams.get('nome_plano');
    const valorPlano = urlParams.get('valor_plano');

    const idPlano = urlParams.get('id_plano'); // Captura o ID vindo da URL
        if (idPlano) {
    document.getElementById('input_id_plano').value = idPlano; // Insere no formulário
    }
    
    // Formata o valor para R$ XX,XX
    let valorFormatado = 'R$ 00,00';

    if (valorPlano) {
        try {
            // Tenta converter para número e formatar
            const valorNumerico = parseFloat(valorPlano);
            valorFormatado = 'R$ ' + valorNumerico.toFixed(2).replace('.', ',');
        } catch (e) {
            console.error("Erro ao formatar valor:", e);
        }
    }

    // 2. Localiza os elementos HTML para atualização
    const elementoValor = document.querySelector('.resumo-cobranca .valor');
    const elementoNome = document.querySelector('.resumo-cobranca .nomePlano');

    // 3. Preenche os elementos
    if (elementoValor && valorPlano) {
        elementoValor.textContent = valorFormatado;
    } else if (elementoValor) {
        elementoValor.textContent = 'R$ N/D'; // Caso não encontre o valor
    }

    if (elementoNome && nomePlano) {
        elementoNome.textContent = nomePlano;
    } else if (elementoNome) {
        elementoNome.textContent = 'Plano não selecionado';
    }
});

/////FUNÇÃO PARA BUSCA DE ENDEREÇO VIA CEP /////

    // Função auxiliar para preencher os campos no HTML

        function pg(url) {
        window.location.href = url;}


    function preencherCampos(rua, bairro, cidade, estado) {
    document.getElementById('rua').value = rua;
    document.getElementById('bairro').value = bairro;
    document.getElementById('cidade').value = cidade;
    document.getElementById('estado').value = estado;
    
    // Remove o atributo 'readonly' após preencher
    document.getElementById('rua').removeAttribute('readonly');
    document.getElementById('bairro').removeAttribute('readonly');
    document.getElementById('cidade').removeAttribute('readonly');
    document.getElementById('estado').removeAttribute('readonly');
}

function buscarCEP(cep) {
    let cepLimpo = cep.replace(/\D/g, '');

    if (cepLimpo.length !== 8) {
        preencherCampos('', '', '', '');
        return;
    }

    const url = `https://viacep.com.br/ws/${cepLimpo}/json/`;

    fetch(url)
        .then(response => response.json())
        .then(dados => {
            if (!dados.erro) {
                preencherCampos(
                    dados.logradouro, 
                    dados.bairro, 
                    dados.localidade, // CIDADE
                    dados.uf          // ESTADO (UF)
                );
            } else {
                alert("CEP não encontrado. Por favor, digite o endereço manualmente.");
                preencherCampos('', '', '', '');
            }
        })
        .catch(error => {
            console.error('Erro na consulta ViaCEP:', error);
            alert("Ocorreu um erro na comunicação. Tente novamente.");
        });
}

// --- EXECUÇÃO PRINCIPAL E EVENT LISTENERS (ESCOPO ÚNICO) ---

document.addEventListener('DOMContentLoaded', () => { 
    // 1. VARIÁVEL DO FORMULÁRIO (Declarada no escopo principal)
    const form = document.getElementById('formEndereco'); 
    
    // 2. LÓGICA DE PREENCHIMENTO DE RESUMO (URL Params)
    const urlParams = new URLSearchParams(window.location.search);
    const nomePlano = urlParams.get('nome_plano');
    const valorPlano = urlParams.get('valor_plano');
    let valorFormatado = 'R$ 00,00';

    if (valorPlano) {
        try {
            const valorNumerico = parseFloat(valorPlano);
            valorFormatado = 'R$ ' + valorNumerico.toFixed(2).replace('.', ',');
        } catch (e) {
            console.error("Erro ao formatar valor:", e);
        }
    }

    const elementoValor = document.querySelector('.resumo-cobranca .valor');
    const elementoNome = document.querySelector('.resumo-cobranca .nomePlano');

    if (elementoValor) elementoValor.textContent = valorFormatado;
    if (elementoNome) elementoNome.textContent = nomePlano || 'Plano não selecionado';

    // Garante que os campos de endereço estejam limpos no início
    preencherCampos('', '', '', ''); 

    // 3. MÁSCARA DE CEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.replace(/^(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    }

    //  ENVIAR DADOS DO ENDEREÇO (AJAX) 
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); 
            
            const formData = new FormData(form);

            // --- ADICIONE AS DUAS LINHAS ABAIXO ---
            const urlParams = new URLSearchParams(window.location.search);
            formData.append('id_aluno', urlParams.get('id_aluno'));
            
            // Requisita o PHP para salvar o endereço
            fetch('../autenticacao/BDEndereco.php', { //enviando os dados para o BDEndereco
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) { // Verifica se a resposta é OK (status 200-299)
                    throw new Error('Erro na requisição: ' + response.statusText);
                }
                return response.json(); 
            })
            .then(data => {
                if (data.status === 'sucesso') { // Se o PHP retornar sucesso direciona para a página de pagamento
                    alert(data.mensagem);
                    // Redireciona para o pagamento
                    window.location.href = data.redirect_url;
                } else {
                    alert('Falha ao salvar endereço: ' + data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro de envio:', error); // Exibe uma mensagem de erro genérica para o usuário
                alert('Erro ao processar a solicitação. Verifique o console.');
            });
        });
    }
}); 

</script>






    
    

   

</body>
</html>