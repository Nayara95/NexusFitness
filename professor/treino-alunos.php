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
$dadosSaude = null;
$questionario = [];

if (isset($_GET['aluno_id']) && !empty($_GET['aluno_id'])) {
    $alunoId = intval($_GET['aluno_id']);
    
    // Busca o aluno selecionado
    $stmt = $conn->prepare("SELECT id_aluno as id, nome, email FROM tbl_aluno WHERE id_aluno = :id");
    $stmt->execute(['id' => $alunoId]);
    $alunoSelecionado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Carrega treinos e informações de saúde se o aluno foi encontrado
    if ($alunoSelecionado) {
        $stmt = $conn->prepare("SELECT segunda, terca, quarta, quinta, sexta, sabado, domingo FROM tbl_agendaTreino WHERE id_aluno = :id");
        $stmt->execute(['id' => $alunoId]);
        $treinoAluno = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT peso, altura FROM tbl_fisicoAluno WHERE id_aluno = :id ORDER BY data_alteracao DESC");
        $stmt->execute(['id' => $alunoId]);
        $saudeAluno = $stmt->fetch(PDO::FETCH_ASSOC);

        // Busca dados de saúde
        $stmt = $conn->prepare("SELECT questionario, exame_bio, atestado_medico FROM tbl_dadosSaude WHERE id_aluno = :id");
        $stmt->execute(['id' => $alunoId]);
        $dadosSaude = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dadosSaude && !empty($dadosSaude['questionario'])) {
            $xml = simplexml_load_string($dadosSaude['questionario']);
            if ($xml) {
                foreach ($xml->children() as $key => $value) {
                    $questionario[(string)$key] = (string)$value;
                }
            }
        }
    }
}

// Processa a pesquisa
$resultadosPesquisa = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $termoPesquisa = trim($_GET['search']);
    
    $termoPesquisa = trim($_GET['search']);
    $termoPesquisaLike = '%' . strtolower($termoPesquisa) . '%';

    // Use a single query to search across ID, nome, and email
    // CAST id_aluno to VARCHAR for LIKE comparison
    $stmt = $conn->prepare("SELECT id_aluno as id, nome, email FROM tbl_aluno WHERE CAST(id_aluno AS VARCHAR) LIKE :termoId OR LOWER(nome) LIKE :termoNome OR LOWER(email) LIKE :termoEmail");
    $stmt->execute([
        'termoId' => $termoPesquisaLike,
        'termoNome' => $termoPesquisaLike,
        'termoEmail' => $termoPesquisaLike
    ]);
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

            <div class="content-wrapper">
                <div class="left-card">
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
                                    <a href="?aluno_id=<?php echo $aluno['id']; ?>" class="aluno-item-link">
                                        <div class="aluno-item">
                                            <div class="aluno-info-basica">
                                                <span class="aluno-id">ID: <?php echo $aluno['id']; ?></span>
                                                <strong><?php echo $aluno['nome']; ?></strong>
                                                <span class="aluno-email"><?php echo $aluno['email']; ?></span>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                                <p class="no-results">Nenhum aluno encontrado.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="right-card">
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
                        </div>

                        <!-- Informações de Saúde -->
                        <?php if ($saudeAluno || $dadosSaude): ?>
                        <div class="info-saude">
                            <h3>Informações de Saúde</h3>
                            <div class="saude-grid">
                                <?php if ($saudeAluno): ?>
                                <div class="info-item">
                                    <label>Peso:</label>
                                    <span><?php echo htmlspecialchars($saudeAluno['peso']); ?> kg</span>
                                </div>
                                <div class="info-item">
                                    <label>Altura:</label>
                                    <span><?php echo htmlspecialchars($saudeAluno['altura']); ?> m</span>
                                </div>
                                <?php endif; ?>

                                <?php if ($dadosSaude): ?>
                                    <?php if (!empty($questionario)): ?>
                                        <?php foreach ($questionario as $pergunta => $resposta): ?>
                                        <div class="info-item">
                                            <label><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $pergunta))); ?>:</label>
                                            <span><?php echo htmlspecialchars($resposta); ?></span>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="info-item">
                                        <label>Exame Bioimpedância:</label>
                                        <span>
                                            <?php if (!empty($dadosSaude['exame_bio'])): ?>
                                                <a href="../uploads/<?php echo htmlspecialchars($dadosSaude['exame_bio']); ?>" target="_blank">Ver Exame</a>
                                            <?php else: ?>
                                                Não informado
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <label>Atestado Médico:</label>
                                        <span>
                                            <?php if (!empty($dadosSaude['atestado_medico'])): ?>
                                                <a href="../uploads/<?php echo htmlspecialchars($dadosSaude['atestado_medico']); ?>" target="_blank">Ver Atestado</a>
                                            <?php else: ?>
                                                Não informado
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
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
                            <?php $isEditMode = isset($_GET['mode']) && $_GET['mode'] === 'edit'; ?>
                            <form action="atualizar-treino.php" method="POST">
                                <input type="hidden" name="aluno_id" value="<?php echo $alunoSelecionado['id']; ?>">

                                <div class="ficha-header">
                                    <h3>Ficha de Treinos</h3>
                                    <div class="ficha-actions">
                                        <?php if ($isEditMode): ?>
                                            <button type="submit" class="btn-salvar">Salvar Alterações</button>
                                            <a href="?aluno_id=<?php echo $alunoSelecionado['id']; ?>" class="btn-cancelar">Cancelar</a>
                                        <?php else: ?>
                                            <a href="?aluno_id=<?php echo $alunoSelecionado['id']; ?>&mode=edit" class="btn-editar">Editar Ficha</a>
                                        <?php endif; ?>
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
                                        $exerciciosDia = isset($treinoAluno[$diaKey]) ? $treinoAluno[$diaKey] : '';
                                    ?>
                                    <div class="dia-treino">
                                        <h4><?php echo $diaNome; ?></h4>
                                        <div class="exercicios-lista">
                                            <?php if ($isEditMode): ?>
                                                <textarea name="<?php echo $diaKey; ?>" class="exercicio-input"><?php echo htmlspecialchars($exerciciosDia); ?></textarea>
                                            <?php else: ?>
                                                <?php if (!empty($exerciciosDia)): ?>
                                                    <div class="exercicio-item">
                                                        <div class="exercicio-nome"><?php echo nl2br(htmlspecialchars($exerciciosDia)); ?></div>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="sem-treino">Sem treino cadastrado</p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php elseif (isset($_GET['aluno_id'])): ?>
                    <div class="no-aluno">
                        <p>Aluno não encontrado.</p>
                    </div>
                    <?php else: ?>
                    <div class="no-aluno">
                        <p>Selecione um aluno para ver suas informações.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <footer>
      <a>© 2025 Nexus Fitness — Todos os direitos reservados.</a>
    </footer>
</body>
</html>