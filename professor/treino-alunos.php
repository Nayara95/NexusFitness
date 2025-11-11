<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

require_once('../autenticacao/conexao.php');
$conn = conectar();

// Busca todos os alunos
$stmt = $conn->query("SELECT id_aluno as id, nome, email, data_cadastro as data_inicio FROM tbl_aluno");
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);






$alunoSelecionado = null;
$treinoAluno = null;
$saudeAluno = null;

if (isset($_GET['aluno_id']) && !empty($_GET['aluno_id'])) {
    $alunoId = intval($_GET['aluno_id']);
    
    // Busca o aluno selecionado
    $stmt = $conn->prepare("SELECT id_aluno as id, nome, email, 'ativo' as status FROM tbl_aluno WHERE id_aluno = :id");
    $stmt->execute(['id' => $alunoId]);
    $alunoSelecionado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Carrega treinos e informações de saúde se o aluno foi encontrado
    if ($alunoSelecionado) {
        $stmt = $conn->prepare("SELECT segunda, terca, quarta, quinta, sexta, sabado, domingo FROM tbl_agendaTreino WHERE id_aluno = :id");
        $stmt->execute(['id' => $alunoId]);
        $treinoAluno = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT peso, altura FROM tbl_fisicoAluno WHERE id_fisicoAluno = :id ORDER BY data_alteracao DESC");
        $stmt->execute(['id' => $alunoId]);
        $saudeAluno = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Processa a pesquisa
$resultadosPesquisa = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $termoPesquisa = trim($_GET['search']);
    
    if (is_numeric($termoPesquisa)) {
        $stmt = $conn->prepare("SELECT id_aluno as id, nome, email, 'ativo' as status FROM tbl_aluno WHERE id_aluno = :termo");
        $stmt->execute(['termo' => $termoPesquisa]);
    } else {
        $termoPesquisa = '%' . strtolower($termoPesquisa) . '%';
        $stmt = $conn->prepare("SELECT id_aluno as id, nome, email, 'ativo' as status FROM tbl_aluno WHERE lower(nome) LIKE :termo OR lower(email) LIKE :termo");
        $stmt->execute(['termo' => $termoPesquisa]);
    }
    $resultadosPesquisa = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treinos dos Alunos - Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="treino-alunos.css">
</head>
<body>
    <header>
      <div class="logo">
        <img src="../imagens/nexus.png" alt="Logo Nexus Fitness" />
      </div>

      <div class="header-buttons">
        <!-- Menu dropdown (login) -->
        <div class="dropdown">
          <button class="dropbtn">Minha Conta▾</button>
          <div class="dropdown-content">
            <a href="../professor/index-professor.php">Área do Professor</a>
            <a href="../professor/perfil-professor.php">Meu Perfil</a>
            <a href="../autenticacao/logout.php">Sair</a>
          </div>
        </div>
      </div>
    </header>

    <main>
        <div class="treinos-container">
            <h1>Gerenciar Treinos dos Alunos</h1>
            
            <!-- Seção de Pesquisa -->
            <div class="pesquisa-section">
                <h2>Pesquisar Aluno</h2>
                <form method="GET" class="search-form">
                    <input type="text" name="search" id="searchInput" 
                           placeholder="Digite ID, nome ou email do aluno..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn-pesquisar">Pesquisar</button>
                </form>
                
                <?php 
                $listaAlunos = (isset($_GET['search']) && !empty($_GET['search'])) ? $resultadosPesquisa : $alunos;
                ?>
                <div class="resultados-pesquisa">
                    <h3><?php echo (isset($_GET['search']) && !empty($_GET['search'])) ? 'Resultados da Pesquisa para "' . htmlspecialchars($_GET['search']) . '"' : 'Todos os Alunos'; ?></h3>
                    
                    <?php if (count($listaAlunos) > 0): ?>
                    <div class="alunos-lista">
                        <?php foreach ($listaAlunos as $aluno): ?>
                            <div class="aluno-item">
                                <div class="aluno-info-basica">
                                    <div class="aluno-id">ID: <?php echo $aluno['id']; ?></div>
                                    <strong><?php echo $aluno['nome']; ?></strong>
                                    <span class="aluno-email"><?php echo $aluno['email']; ?></span>
                                    <span class="status status-ativo">
                                        Ativo
                                    </span>
                                </div>
                                <a href="?aluno_id=<?php echo $aluno['id']; ?>" class="btn-selecionar">Selecionar</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <p class="no-results">Nenhum aluno encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Seção do Aluno Selecionado -->
            <?php if ($alunoSelecionado): ?>
            <div class="aluno-selecionado-section">
                <div class="aluno-header">
                    <div class="aluno-titulo">
                        <h2>Ficha de Treino - <?php echo $alunoSelecionado['nome']; ?></h2>
                        <div class="aluno-meta">
                            <span class="aluno-id">ID: <?php echo $alunoSelecionado['id']; ?></span>
                            <span class="aluno-email"><?php echo $alunoSelecionado['email']; ?></span>
                        </div>
                    </div>
                    <span class="status status-<?php echo $alunoSelecionado['status']; ?>">
                        <?php echo ucfirst($alunoSelecionado['status']); ?>
                    </span>
                </div>
                
                <!-- Informações de Saúde -->
                <?php if ($saudeAluno): ?>
                <div class="info-saude">
                    <h3>Informações de Saúde</h3>
                    <div class="saude-grid">
                        <div class="info-item">
                            <label>Peso:</label>
                            <span><?php echo $saudeAluno['peso']; ?></span>
                        </div>
                        <div class="info-item">
                            <label>Altura:</label>
                            <span><?php echo $saudeAluno['altura']; ?></span>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="info-saude">
                    <h3>Informações de Saúde</h3>
                    <p class="no-info">Nenhuma informação de saúde cadastrada.</p>
                </div>
                <?php endif; ?>
                
                <!-- Ficha de Treinos -->
                <div class="ficha-treinos">
                    <div class="ficha-header">
                        <h3>Ficha de Treinos</h3>
                        <div class="ficha-actions">
                            <button class="btn-editar" onclick="editarFicha(<?php echo $alunoSelecionado['id']; ?>)">Editar Ficha</button>
                            <button class="btn-enviar" onclick="enviarFicha(<?php echo $alunoSelecionado['id']; ?>)">Enviar para Aluno</button>
                        </div>
                    </div>
                    
                    <div class="dias-treino">
                        <?php 
                        $diasSemana = [
                            'segunda' => 'Segunda-feira',
                            'terca' => 'Terça-feira',
                            'quarta' => 'Quarta-feira',
                            'quinta' => 'Quinta-feira',
                            'sexta' => 'Sexta-feira',
                            'sabado' => 'Sábado',
                            'domingo' => 'Domingo'
                        ];
                        
                        foreach ($diasSemana as $diaKey => $diaNome): 
                            $exerciciosDia = isset($treinoAluno[$diaKey]) ? $treinoAluno[$diaKey] : [];
                        ?>
                        <div class="dia-treino">
                            <h4><?php echo $diaNome; ?></h4>
                            <div class="exercicios-lista">
                                <?php if (!empty($treinoAluno[$diaKey])): ?>
                                    <div class="exercicio-item">
                                        <div class="exercicio-nome"><?php echo $treinoAluno[$diaKey]; ?></div>
                                    </div>
                                <?php else: ?>
                                    <p class="sem-treino">Sem treino cadastrado</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php elseif (isset($_GET['aluno_id'])): ?>
            <div class="no-aluno">
                <p>Aluno não encontrado.</p>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include ('../footer1.php'); ?>
</body>
</html>