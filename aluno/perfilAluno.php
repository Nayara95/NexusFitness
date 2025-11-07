<?php
session_start();
require_once '../autenticacao/conexao.php';

// Verifica se o usuário está logado e se é um aluno
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'aluno') {
    header('Location: ../login.php?auth_error=1');
    exit;
}

$conn = conectar();

// Busca os dados do aluno no banco de dados
$stmt = $conn->prepare("SELECT * FROM tbl_aluno WHERE id_aluno = :id");
$stmt->bindParam(':id', $_SESSION['id_aluno']);
$stmt->execute();
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o aluno, redireciona para o login
if (!$aluno) {
    header('Location: ../login.php?error=not_found');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Aluno</title>
    <link rel="shortcut icon" href="../imagens/faviconNexus.png" type="logo Nexus Fitness">

    <link rel="stylesheet" href="../style.css" />

    <!-- ======== link de site de icones ======== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />

   
</head>
<body>
 <!--  CABEÇALHO -->
    <?php
         include ('header.php')    
    ?>


<!--  SEÇÃO VER CADASTRO - SEM ACESSO APENAS VISUALIZAÇÃO  -->

    <div class="perfil_container">
      <form action="cadastro" method="#">
        <label for="nome">Nome Completo</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($aluno['nome']); ?>" readonly />

        <label for="NomeSocial">Nome Social</label>
        <input type="text" id="NomeSocial" name="NomeSocial" value="<?php echo htmlspecialchars($aluno['nome_social']); ?>" readonly />

        <label for="genero">Gênero</label>
        <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($aluno['genero']); ?>" readonly />

        <label for="data-nasc">Data de nascimento</label>
        <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($aluno['data_nasc']); ?>" readonly />

        <label for="telefone">Telefone</label>
        <input type="text" id="telefone" name="telefone" value="(<?php echo htmlspecialchars($aluno['dd1']); ?>) <?php echo htmlspecialchars($aluno['telefone']); ?>" readonly>
     

        <p>Para acessar seus dados,baixe o aplicativo Nexus Fitness. Disponivel em: </p>
        <img src="../imagens/googlePlay.png" alt="App Nezus Fitness Google Play" />
     </form>

    </div>

    <div class="btn-plano">
      <p>Meu plano</p>
      
      <button type="submit" class="btn-plano">
        <i class="fa-solid fa-angle-down"></i>
      </button>
    </div>



    <div class="checkIn">
      <form action="treinoAluno">
        <label for="status">Check-ins</label>
        <input type="number" id="status" name="status" required>
      </form>
      <img src="../imagens/checkIn.png" alt="Imagem de check-in">

    </div>



    





<!-- ======== RODAPÉ/FOOTER ======== -->

      <?php 
        include ('footer.php');      
      ?>

</body>
</html>
