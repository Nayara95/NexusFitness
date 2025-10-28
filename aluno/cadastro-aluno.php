<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastrar | Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="../style.css" />
  </head>
  <body>


<main>

    <div class="cadastro_container"> <!-- div completa -->

      <!--  CADASTRO LADO ESQUERDO  -->
      <div class="cadastro_esquerdo">
            <img src="../imagens/nexus.png" alt="Nexus Fitness" class="logo-login">
            <p class="alunoTxt">Já é nosso aluno?</p>
            <a href="login.php" class="btnEntrar">Entrar</a>
            
        </div>



      <!-- ======== LADO DIRETO ======== -->
      <div class="cadastro_direito">
        <h2>informe os dados para cadastro</h2>

        <form action="cadastrar.php" method="POST" id="formCadastrar">

                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>
                <label for="nomeSocial">Nome social</label>
                <input type="text" id="nomeSocial" name="nomeSocial">

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
                    <input type="date" id="nascimento" name="nascimento" placeholder="Informe a Data de Nascimento" required>
                  </div>

                </div> 
                <label for="ddd">DDD</label>
                <input type="number" id="ddd" name="ddd" placeholder="Informe o DDD" required>
                
                <label for="telefone">Telefone</label>
                <input type="number" id="telefone" name="telefone" placeholder="Informe o Telefone" required>


                 <div class="info-texto">
                    <p>Utilizamos seus dados pessoais para o cadastro em nossa plataforma, que nos permite lhe prestar nossos serviços. Para mais informações, acesse nosso Aviso de Privacidade. Caso não queira receber comunicações de marketing, <span class="destaque-importante">clique aqui.</span> Você pode alterar suas preferências de Privacidade ou pelo link disponibilizado no rodapé dos e-mails da Nexus Fitness.<br>
                    Importante: apenas comunicações de Marketing podem ser desativadas. Outras comunicações, sobre seus dados e/ou sobre suas aulas, continuarão a ser encaminhadas, pois são essenciais para prestação de serviços.</p>
                </div>

               <!-- <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

                <label for="conf-senha">Confime sua senha</label>
                <input type="password" id="conf-senha" name="conf-senha" placeholder="Digite sua senha" required> -->

                <div class="btnCadastar">
                    <button type="submit" class="btn-cadastra">Cadastrar</button>
                </div>

          <br />
        </form>

      </div>


    </div> <!-- FIM div completa -->

    <script src="../script.js"></script>
  </body>

</main>


</html>
