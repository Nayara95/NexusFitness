<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Nexus Fitness</title>
    <link rel="stylesheet" href="style.css">
    <!-- icone superior -->
  <link rel="shortcut icon" href="imagens/faviconNexus.png" type="logo Nexus Fitness">


</head>
<body>

    <div class="login-container">

        <!-- ======== LADO ESQUERDO ======== -->
        <div class="login-left">
            <h2>Área do cliente</h2>

            <form action="autenticacao/login.php" method="POST">
                <label for="email">Email ou CPF</label>
                <input type="text" id="email" name="email" placeholder="Digite seu email ou CPF" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

                <div class="login-buttons">
                    <button type="submit" class="btn-acessar">Acessar</button>
                    <button type="button" class="btn-redefinir">Redefinir senha</button>
                </div>
            </form>
        </div>

        <!-- ======== LADO DIREITO ======== -->
        <div class="login-right">
            <img src="imagens/nexus.png" alt="Nexus Fitness" class="logo-login">
            <h3>NEXUS<br>FITNESS</h3>
            <p>Ainda não é nosso aluno?</p>
            <a href="aluno/cadastro-aluno.php" class="btn-cadastrar">Cadastrar</a>
            
        </div>

    </div>

</body>
</html>
