<?php
session_start();
include '../autenticacao/conexao.php';
$conn = conectar();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

// Processar ações POST antes de qualquer saída HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpar aluno selecionado
    if (isset($_POST['limpar_aluno'])) {
        unset($_SESSION['aluno_selecionado_id']);
        unset($_SESSION['resultados_pesquisa']);
        unset($_SESSION['termo_pesquisa']);
        header('Location: medicoes-alunos.php');
        exit;
    }
    
    // Limpar pesquisa
    if (isset($_POST['limpar_pesquisa'])) {
        unset($_SESSION['resultados_pesquisa']);
        unset($_SESSION['termo_pesquisa']);
        header('Location: medicoes-alunos.php');
        exit;
    }
    
    // Processar exclusão
    if (isset($_POST['excluir_id']) && isset($_SESSION['aluno_selecionado_id'])) {
        $excluirId = intval($_POST['excluir_id']);
        $sql_excluir = "DELETE FROM tbl_fisicoAluno WHERE id_fisicoAluno = :id";
        $stmt_excluir = $conn->prepare($sql_excluir);
        if ($stmt_excluir->execute(['id' => $excluirId])) {
            $_SESSION['mensagem_sucesso'] = 'Dados excluídos com sucesso!';
            header('Location: medicoes-alunos.php');
            exit;
        }
    }
}

$alunoSelecionado = null;
$dadosAluno = [];
$editarDado = null;
$alunos = [];
$dadosFisicos = [];

// Buscar todos os alunos para a lista
$sql_alunos = "SELECT id_aluno, nome, email FROM tbl_aluno";
$stmt_alunos = $conn->query($sql_alunos);
if ($stmt_alunos) {
    $alunos = $stmt_alunos->fetchAll(PDO::FETCH_ASSOC);
}

// Processar seleção de aluno via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aluno_id_selecionado'])) {
    $alunoId = intval($_POST['aluno_id_selecionado']);
    $_SESSION['aluno_selecionado_id'] = $alunoId;
} elseif (isset($_SESSION['aluno_selecionado_id'])) {
    $alunoId = $_SESSION['aluno_selecionado_id'];
}

if (isset($alunoId)) {
    $sql_aluno_selecionado = "SELECT id_aluno, nome, email FROM tbl_aluno WHERE id_aluno = :id";
    $stmt_aluno_selecionado = $conn->prepare($sql_aluno_selecionado);
    $stmt_aluno_selecionado->execute(['id' => $alunoId]);
    $alunoSelecionado = $stmt_aluno_selecionado->fetch(PDO::FETCH_ASSOC);

    if ($alunoSelecionado) {
        $sql_dados_fisicos = "SELECT id_fisicoAluno, altura, peso, braco, abdomen, perna, data_alteracao FROM tbl_fisicoAluno WHERE id_aluno = :id_aluno ORDER BY tbl_fisicoAluno.data_alteracao ASC";
        $stmt_dados_fisicos = $conn->prepare($sql_dados_fisicos);
        $stmt_dados_fisicos->execute(['id_aluno' => $alunoId]);
        $dadosAluno = $stmt_dados_fisicos->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Processar edição via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id']) && $alunoSelecionado) {
    $editarId = intval($_POST['editar_id']);
    foreach ($dadosAluno as $dado) {
        if ($dado['id_fisicoAluno'] == $editarId) {
            $editarDado = $dado;
            break;
        }
    }
}

// Lógica de pesquisa via POST
$resultados = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search']) && !empty($_POST['search'])) {
    $termo = '%' . strtolower($_POST['search']) . '%';
    $sql_search = "SELECT id_aluno, nome, email FROM tbl_aluno WHERE LOWER(nome) LIKE :termoNome OR LOWER(email) LIKE :termoEmail";
    $stmt_search = $conn->prepare($sql_search);
    $stmt_search->execute(['termoNome' => $termo, 'termoEmail' => $termo]);
    $resultados = $stmt_search->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['resultados_pesquisa'] = $resultados;
    $_SESSION['termo_pesquisa'] = $_POST['search'];
} elseif (isset($_SESSION['resultados_pesquisa'])) {
    $resultados = $_SESSION['resultados_pesquisa'];
} else {
    // Se não houver pesquisa, carrega todos os alunos
    $resultados = $alunos;
}

// Verificar mensagens de sucesso e erro
$mensagem_sucesso = '';
$mensagem_erro = '';
if (isset($_SESSION['mensagem_sucesso'])) {
    $mensagem_sucesso = $_SESSION['mensagem_sucesso'];
    unset($_SESSION['mensagem_sucesso']);
}
if (isset($_SESSION['mensagem_erro'])) {
    $mensagem_erro = $_SESSION['mensagem_erro'];
    unset($_SESSION['mensagem_erro']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação Física - Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="treino-alunos.css">
    <link rel="stylesheet" href="medicoes-alunos.css"> 
</head>
<body>
    <header>
        <div class="logo">
            <img src="../imagens/nexus.png" alt="Logo Nexus Fitness" />
        </div>

        <div class="header-buttons">
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
        <div class="avaliacao-container">
            <h1>Medições de Alunos</h1>
            
            <?php if ($mensagem_sucesso): ?>
            <div class="alert alert-sucesso" style="margin-bottom: 20px;">
                <?php echo htmlspecialchars($mensagem_sucesso); ?>
            </div>
            <?php endif; ?>
            
            <?php if ($mensagem_erro): ?>
            <div class="alert alert-erro" style="margin-bottom: 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; padding: 12px;">
                <?php echo htmlspecialchars($mensagem_erro); ?>
            </div>
            <?php endif; ?>
            
            <div class="content-wrapper" id="main-content-area">
                <div class="left-card" id="left-card">
                    <div class="pesquisa-section">
                        <h2>Pesquisar Aluno</h2>
                        <form method="POST" class="search-form" id="searchForm">
                            <input type="text" name="search" id="searchInput" 
                                   placeholder="Digite nome ou matrícula do aluno..." 
                                   value="<?php echo isset($_SESSION['termo_pesquisa']) ? htmlspecialchars($_SESSION['termo_pesquisa']) : ''; ?>">
                            <button type="submit" class="btn-pesquisar">Pesquisar</button>
                            <?php if (isset($_SESSION['termo_pesquisa'])): ?>
                            <button type="submit" name="limpar_pesquisa" value="1" class="btn-limpar">Limpar</button>
                            <?php endif; ?>
                        </form>
                        
                        <div class="resultados-pesquisa">
                            <h3>Resultados da Pesquisa</h3>
                            <?php if (count($resultados) > 0): ?>
                            <div class="alunos-lista">
                                <?php foreach ($resultados as $aluno): ?>
                                    <form method="POST" class="aluno-select-form" style="margin: 0;">
                                        <input type="hidden" name="aluno_id_selecionado" value="<?php echo $aluno['id_aluno']; ?>">
                                        <button type="submit" class="aluno-item-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                                            <div class="aluno-item">
                                                <div class="aluno-info-basica">
                                                    <span class="aluno-id">ID: <?php echo $aluno['id_aluno']; ?></span>
                                                    <strong><?php echo htmlspecialchars($aluno['nome']); ?></strong>
                                                    <span class="aluno-email"><?php echo htmlspecialchars($aluno['email']); ?></span>
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <p class="no-results">Nenhum aluno encontrado.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="right-card" id="right-card">
                    <?php if ($alunoSelecionado): ?>
                    <div class="aluno-selecionado-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <form method="POST" style="display: inline;">
            <button type="submit" name="limpar_aluno" value="1" class="btn-voltar-lista">← Voltar para a Lista</button>
        </form>

        <form method="POST" action="gerar-pdf-aluno.php" target="_blank">
            <input type="hidden" name="id" value="<?php echo $alunoSelecionado['id_aluno']; ?>">
            <button type="submit" class="btn-pdf" style="background-color: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                📄 Gerar PDF
            </button>
        </form>
    </div>

    <div class="aluno-header">
        <div class="aluno-info">
            <h2><?php echo htmlspecialchars($alunoSelecionado['nome']); ?></h2>
            <div class="aluno-meta">
                <span>Matrícula: <?php echo htmlspecialchars($alunoSelecionado['email']); ?></span>
                <span>N° Dados Físicos: <?php echo count($dadosAluno); ?></span>
            </div>
        </div>
    </div>
        <div class="form-section">
            <h3><?php echo $editarDado ? 'Editar Dados Físicos' : 'Inserir Novos Dados Físicos'; ?></h3>
                            
                <form method="POST" action="salvar-dados.php" class="dados-form">
                    <input type="hidden" name="aluno_id" value="<?php echo $alunoSelecionado['id_aluno']; ?>">
                    <input type="hidden" name="dado_id" value="<?php echo $editarDado ? htmlspecialchars($editarDado['id_fisicoAluno']) : ''; ?>">

                    <div class="form-grid">
                        <div class="form-column">
                            <div class="input-group">
                                <label for="bracos">Braços (CM)</label>
                                <input type="number" id="bracos" name="bracos" step="0.01" 
                                        value="<?php echo $editarDado ? htmlspecialchars($editarDado['braco']) : ''; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="abdomen">Abdômen (CM)</label>
                                <input type="number" id="abdomen" name="abdomen" step="0.01"
                                        value="<?php echo $editarDado ? htmlspecialchars($editarDado['abdomen']) : ''; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="peso">Peso (KG)</label>
                                <input type="number" id="peso" name="peso" step="0.01"
                                        value="<?php echo $editarDado ? htmlspecialchars($editarDado['peso']) : ''; ?>" required>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="input-group">
                                <label for="altura">Altura (M)</label>
                                <input type="number" id="altura" name="altura" step="0.01"
                                        value="<?php echo $editarDado ? htmlspecialchars($editarDado['altura']) : ''; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="pernas">Pernas (CM)</label>
                                <input type="number" id="pernas" name="pernas" step="0.01"
                                        value="<?php echo $editarDado ? htmlspecialchars($editarDado['perna']) : ''; ?>" required>
                            </div>
                            <div class="input-group data-group">
                            <label>Data da Medida</label>
                            <input type="date" id="data_medida" name="data_medida" 
                                value="<?php 
                                    date_default_timezone_set('America/Sao_Paulo');
                                    if ($editarDado && !empty($editarDado['data_alteracao'])) {
                                        echo date('Y-m-d', strtotime($editarDado['data_alteracao']));
                                    } else {
                                        echo date('Y-m-d');
                                    }
                                ?>" max="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-salvar">Salvar Dados</button>
                    </div>
                </form>
            </div>
                    <?php if (count($dadosAluno) > 0): ?>
                        <div class="historico-section">
                            <h3>Histórico de Dados Físicos</h3>
                            <div class="tabela-dados">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Braços (CM)</th>
                                            <th>Abdômen (CM)</th>
                                            <th>Peso (KG)</th>
                                            <th>Altura (M)</th>
                                            <th>Pernas (CM)</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php foreach ($dadosAluno as $dado): ?>
                                            <tr class="data-row" 
                                                data-id="<?php echo $dado['id_fisicoAluno']; ?>"
                                                data-braco="<?php echo $dado['braco']; ?>"
                                                data-abdomen="<?php echo $dado['abdomen']; ?>"
                                                data-peso="<?php echo $dado['peso']; ?>"
                                                data-altura="<?php echo $dado['altura']; ?>"
                                                data-perna="<?php echo $dado['perna']; ?>"
                                                data-data="<?php echo date('Y-m-d', strtotime($dado['data_alteracao'])); ?>">
                                                <td><?php echo date('d/m/Y', strtotime($dado['data_alteracao'])); ?></td>
                                                <td><?php echo number_format($dado['braco'], 2, ',', '.'); ?></td>
                                                <td><?php echo number_format($dado['abdomen'], 2, ',', '.'); ?></td>                                    
                                                <td><?php echo number_format($dado['peso'], 2, ',', '.'); ?></td>
                                                <td><?php echo number_format($dado['altura'], 2, ',', '.'); ?></td>
                                                <td><?php echo number_format($dado['perna'], 2, ',', '.'); ?></td>
                                                <td class="acoes">
                                                    <button type="button" class="btn-editar-js">Editar</button>
                                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir estes dados?')">
                                                        <input type="hidden" name="excluir_id" value="<?php echo $dado['id_fisicoAluno']; ?>">
                                                        <button type="submit" class="btn-excluir" style="background: none; border: none; color: #dc3545; cursor: pointer; text-decoration: underline; padding: 0;">Excluir</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="no-data">
                            <p>Nenhum dado físico cadastrado para este aluno.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php elseif (isset($_POST['aluno_id_selecionado']) && !$alunoSelecionado): ?>
                    <div class="no-aluno">
                        <p>Aluno não encontrado.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
      <a>© 2025 Nexus Fitness — Todos os direitos reservados.</a>
    </footer>
    
    <script src="medicoes-alunos.js"></script>
</body>
</html>