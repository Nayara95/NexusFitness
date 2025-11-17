<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastrar | Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="../senha.css" />

     <link rel="shortcut icon" href="../imagens/faviconNexus.png" type="logo Nexus Fitness">

  </head>
  <body>


<main class="main-cadastro">
  

    <div class="cadastro_container"> <!-- div completa -->

      <!--  CADASTRO LADO ESQUERDO  -->
      <div class="cadastro_esquerdo">
            <img src="../imagens/nexus.png" alt="Nexus Fitness" class="logo-login">
            <p class="alunoTxt">Já é nosso aluno?</p>
            <a href="../login.php" class="btnEntrar">Entrar</a>
            
        </div>



      <!-- ======== LADO DIRETO ======== -->
      <div class="cadastro_direito">
        <h2>informe os dados para cadastro</h2>

        <form action="BDCadastro.php" method="POST" id="formCadastrar">

                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>

                <label for="nomeSocial">Nome social</label>
                <input type="text" id="nome_social" name="nome_social">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite seu email" required>

                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"title="000.000.000-00" > <!--definindo campo de cep com tipo de dado especifico -->

               <div class="campoSeletivo"> 

                  <div class="campo"> 
                    <label for="genero">Gênero</label>
                    <select id="genero" name="genero" required>
                        <option value="" disabled selected>Selecione seu gênero</option>
                        <option value="masculino">Masculino</option>
                        <option value="feminino">Feminino</option>
                        <option value="outros">Não informar</option>
                    </select>
                  </div>
                  <div class="campo"> 
                      <label for="data-nasc">Data de Nascimento</label>
                    <input type="date" id="data_nasc" name="data_nasc" placeholder="Informe a Data de Nascimento" required>
                  </div>

                </div> 
                <label for="dd1">DDD</label>
                <input type="number" id="dd1" name="dd1" placeholder="Informe o DDD" required>
                
                <label for="telefone">Celular</label>
                <input type="number" id="celular" name="telefone" placeholder="Informe o celular" required>

               <!-- <div class="btnCadastar">
                    <button type="submit" class="btn-cadastra">Cadastrar</button>
                    <a href="escolha_plano.php">aqui</a> 
                </div> -->
          <br />
        </form>
      <!-- BTN DE ENVIO -->

              <div class="cadastro_direito">
                <form action="/BDCompleto_php" method="POST" id="formCadastrar">
                    <div class="btnCadastar">

                        <button type="button" class="btn-cadastra" id="btn-mostrar-senha">Cadastrar</button>
                    </div>
                </form>
             </div>

       <!-- POP UP DE CADASTRO DE SENHA -->

    <div id="senha" class="senha-container">
        <div class="senha-conteudo">
            <h3>Crie sua Senha</h3>

            <form id="form-senha">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

                <label for="conf-senha">Confirme sua senha</label>
                <input type="password" id="conf-senha" name="conf-senha" placeholder="Confirme sua senha" required>

                <p id="erro-senha" class="erro" style="display:none;">As senhas não coincidem.</p>

                <button type="button" id="btn-finalizar-cadastro">Finalizar Cadastro</button>
            </form>
            
            <button id="btn-fechar-senha" class="btn-fechar-senha">X</button>
         </div>
     </div>



    </div> <!-- FIM div completa -->
</main>

  <script src="../script.js"></script>
  <script>
   
    // ponteiro de referencia,escuta de evento
    document.addEventListener('DOMContentLoaded', () => {
        //criei as referencias dos botoões e do pou up dentro da const
        const btnMostrarSenha = document.getElementById('btn-mostrar-senha');
        const dadoSenha = document.getElementById('senha'); // Este é o div do pop-up
        const btnFecharModal = document.getElementById('btn-fechar-senha');
        const btnFinalizarCadastro = document.getElementById('btn-finalizar-cadastro');
        const formPrincipal = document.getElementById('formCadastrar');
        
        // --- REFERÊNCIAS CORRIGIDAS PARA O POP-UP ---
        // Acessamos o formulário do pop-up e seus campos internos
        const formSenha = document.getElementById('form-senha'); 
        const inputSenha = formSenha.querySelector('#senha');         // O input da SENHA
        const inputConfSenha = formSenha.querySelector('#conf-senha'); // O input de CONFIRMAR SENHA

        // 🎯 GARANTA QUE ESTA LINHA ESTEJA PRESENTE 🎯
        const senhaConteudo = dadoSenha.querySelector('.senha-conteudo');
        // ---------------------------------------------
        
        const erroSenha = document.getElementById('erro-senha');

        // 1. Mostrar o pop up quando o botão "Cadastrar" for clicado
        btnMostrarSenha.addEventListener('click', (e) => {
            // Verifica a validade do formulário principal antes de abrir o pop up
            if (formPrincipal.checkValidity()) {
                dadoSenha.classList.add('ativo');
            } else {
                // Se o formulário principal for inválido, dispara a validação nativa do HTML5
                formPrincipal.reportValidity(); 
            }
        });

        // 2. Fechar 
        btnFecharModal.addEventListener('click', () => {
            dadoSenha.classList.remove('ativo');
            erroSenha.style.display = 'none'; // Esconde a mensagem de erro
        });

        // 3. Finalizar o Cadastro 
        btnFinalizarCadastro.addEventListener('click', () => {
            // Agora, estas referências estão garantidas de serem do pop-up
            const senha = inputSenha.value;
            const confSenha = inputConfSenha.value;

            // Validação da Senha
            if (senha === '' || confSenha === '') { //se a senha for igual a vazio
                alert('Por favor, preencha ambos os campos de senha.');
                return;
            }

            if (senha !== confSenha) { //se a senha for diferente
                erroSenha.style.display = 'block';
                inputConfSenha.focus();
                return;
            }

            erroSenha.style.display = 'none';

            // 🎯 Lógica para enviar os dados da senha junto com o formulário principal 🎯
            
            // 3.1. Cria campos de input HIDDEN para a senha e anexa-os ao formulário principal
           // let inputSenhaHidden = document.createElement('input');
           // inputSenhaHidden.type = 'hidden';
           // inputSenhaHidden.name = 'senha'; // Nome esperado pelo PHP ($_POST['senha'])
           // inputSenhaHidden.value = senha;
           // formPrincipal.appendChild(inputSenhaHidden);
            
            // 3.2. Submete o formulário principal
           // formPrincipal.submit(); 
            // O formulário principal (formCadastrar) agora contém todos os campos + o campo 'senha' oculto
        // 1. Coleta TODOS os dados do formulário principal
            const formData = new FormData(formPrincipal);
            
            // 2. Adiciona a senha do pop-up ao objeto FormData
            formData.append('senha', senha);

            // 3. OBTÉM o URL de destino do formulário (BDCadastro.php)
            const actionUrl = formPrincipal.action;

            // Desabilita o botão para evitar cliques duplicados
            btnFinalizarCadastro.disabled = true;
            btnFinalizarCadastro.textContent = 'Enviando...';
            
            // 4. Envia os dados via Fetch API (AJAX)
            fetch(actionUrl, {
                method: 'POST',
                body: formData // Envia os dados coletados
            })
            .then(response => {
                // O PHP retorna JSON, então processamos JSON.
                // Se houver erro HTTP (ex: 500), precisamos tratar.
                if (response.ok) {
                    return response.json();
                }
                // Se o status não for OK (ex: 500), tratamos o erro.
                return response.json().then(errorData => {
                    throw new Error(errorData.mensagem || 'Erro desconhecido ao cadastrar.');
                });
            })
            .then(data => {
                // Resposta de SUCESSO recebida do PHP
                if (data.status === 'sucesso') {
                    
                    // Exibir a mensagem de sucesso no pop-up
                    senhaConteudo.innerHTML = `
                        <div style="text-align:center; padding: 20px;">
                            <h3 style="color: #f03c3cff;">✅ Cadastro Realizado!</h3>
                            <p>${data.mensagem}</p>
                        </div>
                    `;
                    
                    // Redireciona para o próximo passo após 3 segundos
                    setTimeout(() => {
                         window.location.href = '../login.php'; 
                    }, 3000);

                } else {
                    // Caso o PHP retorne JSON com status 'erro' (Não deve acontecer se o erro for 500)
                    alert('Erro no cadastro: ' + data.mensagem);
                }
            })
            .catch(error => {
                // Trata falhas na rede, no JSON ou erros do servidor
                btnFinalizarCadastro.disabled = false;
                btnFinalizarCadastro.textContent = 'Finalizar Cadastro';
                alert('Falha no Cadastro: ' + error.message);
                console.error('Erro de Fetch:', error);
            });
        });
    });


  </script>

  </body>
</html>
