<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="../style.css" />
  </head>
  <body>
    <div class="login-container">
      <!-- ======== LADO ESQUERDO ======== -->
      <div class="login-left">
        <h2>informe os dados para cadastro</h2>

        <form action="cadastrar.php" method="POST">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite seu email" required>

                <label for="cpf">CPF</label>
                <input type="number" id="cpf" name="cpf" placeholder="Digite seu CPF" required>

                <label for="genero">Gênero</label>
                <select id="genero" name="genero" required>
                    <option value="" disabled selected>Selecione seu gênero</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outros">Outros</option>
                </select>

                <label for="data-nasc">Data de Nascimento</label>
                <input type="date" id="data" name="data" placeholder="Informe a Data de Nascimento" required>

                <label for="ddd">DDD</label>
                <input type="number" id="ddd" name="ddd" placeholder="Informe o DDD" required>
                
                <label for="telefone">Telefone</label>
                <input type="number" id="telefone" name="telefone" placeholder="Informe o Telefone" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

                <label for="conf-senha">Confime sua senha</label>
                <input type="password" id="conf-senha" name="conf-senha" placeholder="Digite sua senha" required>

                <div class="login-buttons">
                    <button type="submit" class="btn-acessar">Cadastrar</button>
                    <button type="reset" class="btn-redefinir">Limpar</button>
                </div>
          <br />
          <label for="data-cadastro">Data de Cadastro Atual</label>
          <input type="text" id="data-cadastro" name="data-cadastro" disabled />
        </form>
      </div>
    </div>

    <script src="script.js"></script>
  </body>
</html>
