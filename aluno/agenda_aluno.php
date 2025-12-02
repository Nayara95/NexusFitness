<?php

    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true)
       {
    header('Location: ../login.php');
    exit;
}

  //conexão com o banco de dados
  require_once '../autenticacao/conexao.php';
  //id de quem está logado
  $id_aluno = (int)$_SESSION['id_aluno'] ?? null; 
  $nome_aluno = $_SESSION['nome'] ?? 'Aluno';

  $agenda_semanal = [];

//chamando o comando para buscar a agenda do aluno
if ($id_aluno) {
    try {
     $conn = conectar();

   $sql = "SELECT segunda, terca, quarta, quinta, sexta, sabado, domingo FROM tbl_agendaTreino WHERE id_aluno = :id_aluno";

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);

      $stmt->execute();

      $treinos_aluno = $stmt->fetch(PDO::FETCH_ASSOC);

      $agenda_semanal = $treinos_aluno ?: [];

 } catch (PDOException $e){
        error_log("Erro ao buscar agenda: " . $e->getMessage());
        // Em caso de falha, a agenda permanece vazia
    }
  }   

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de treinos</title>

    <link rel="shortcut icon" href="../imagens/faviconNexus.png" type="logo Nexus Fitness">

    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="imagens/faviconNexus.png" type="logo Nexus Fitness">

    <!-- ======== link de site de icones ======== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />

   
</head>
<body>
<?php
         include ('header.php')    
    ?>
<main>
 
<!-- ======== CABEÇALHO/HEADER ======== -->

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

    <!-- ======== Agenda de treino ======== -->

    
    <div class="agenda-semanal">
        <h1>Minha Agenda Semanal <?php echo htmlspecialchars($nome_aluno); ?></h1>
        
        <div class="botoes-dias" id="btnDias">
            <button class="botao-dia" data-dia="segunda">Segunda-feira</button>
            <button class="botao-dia" data-dia="terca">Terça-feira</button>
            <button class="botao-dia" data-dia="quarta">Quarta-feira</button>
            <button class="botao-dia" data-dia="quinta">Quinta-feira</button>
            <button class="botao-dia" data-dia="sexta">Sexta-feira</button>
            <button class="botao-dia" data-dia="sabado">Sábado</button>
            <button class="botao-dia" data-dia="domingo">Domingo</button>
        </div>

        <p>Pré-visualização: Para detalhes dos exercícios acesse via aplicativo Nexus Fitiness</p>


        <div class="atividades-container">
            <h2 id="tituloDia">Selecione um dia para ver as atividades</h2>
            <ul id="listaAulas">
                </ul>
        </div>
    </div>

</main>

<!-- ======== RODAPÉ/FOOTER ======== -->


      <?php 
        include ('footer.php');      
      ?>

 <script src="../script.js"></script>

 <script>

  // Definir a variável JS a partir do PHP 
    const nomeAlunoJS = "<?php echo htmlspecialchars($nome_aluno); ?>";

    // 1. Recebe os dados da agenda do PHP (em formato JSON)
    const agendaTreinos = <?php echo json_encode($agenda_semanal); ?>;
    
    const listaAulas = document.getElementById('listaAulas');
    const tituloDia = document.getElementById('tituloDia');
    const botoesDias = document.querySelectorAll('.botao-dia');

    // Mapeamento para nomes de exibição e chaves do BD
    const nomesDias = {
        'segunda': 'Segunda-feira',
        'terca': 'Terça-feira',
        'quarta': 'Quarta-feira',
        'quinta': 'Quinta-feira',
        'sexta': 'Sexta-feira',
        'sabado': 'Sábado',
        'domingo': 'Domingo'
    };

    function mostrarTreino(dia) {
        // Remove a classe 'ativo' de todos os botões
        botoesDias.forEach(btn => btn.classList.remove('ativo'));
        
        // Adiciona a classe 'ativo' ao botão clicado
        const botaoAtivo = document.querySelector(`.botao-dia[data-dia="${dia}"]`);
        if (botaoAtivo) {
            botaoAtivo.classList.add('ativo');
        }

        // Obtém o texto do treino diretamente pela chave do dia
        const treinoTexto = agendaTreinos[dia] || ''; 
        let htmlLista = '';

        tituloDia.textContent = nomesDias[dia] || 'Dia Selecionado';

        if (treinoTexto && treinoTexto.trim() !== '') {
            // Divide o texto por quebras de linha ou algum separador (se o conteúdo for formatado)
            // Se for apenas um bloco de texto, exibe em uma lista simples
            htmlLista = `<li>${treinoTexto.replace(/\n/g, '</li><li>')}</li>`;
        } else {
            htmlLista = `<li>Nenhum treino agendado para ${nomesDias[dia]}.</li>`;
        }

        listaAulas.innerHTML = htmlLista;
    }

    // Adiciona o evento de clique a todos os botões de dias
    botoesDias.forEach(botao => {
        botao.addEventListener('click', function() {
            const diaSelecionado = this.getAttribute('data-dia');
            mostrarTreino(diaSelecionado);
        });
    });
    
    // Opcional: Mostra o treino da Segunda-feira por padrão, se houver dados
    if (Object.keys(agendaTreinos).length > 0) {
        mostrarTreino('segunda'); 
    } else {
        tituloDia.textContent = `Agenda de Treinos de ${nomeAlunoJS}`;
    }
</script>



</body>
</html>