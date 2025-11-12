<?php
session_start();
include '../autenticacao/conexao.php';
$conn = conectar();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
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

// Processar seleção de aluno
if (isset($_GET['aluno_id'])) {
    $alunoId = intval($_GET['aluno_id']);
    $sql_aluno_selecionado = "SELECT id_aluno, nome, email FROM tbl_aluno WHERE id_aluno = :id";
    $stmt_aluno_selecionado = $conn->prepare($sql_aluno_selecionado);
    $stmt_aluno_selecionado->execute(['id' => $alunoId]);
    $alunoSelecionado = $stmt_aluno_selecionado->fetch(PDO::FETCH_ASSOC);

    if ($alunoSelecionado) {
        $sql_dados_fisicos = "SELECT id_fisicoAluno, altura, peso, braco, abdomen, perna, data_alteracao FROM tbl_fisicoAluno WHERE id_aluno = :id_aluno ORDER BY data_alteracao DESC";
        $stmt_dados_fisicos = $conn->prepare($sql_dados_fisicos);
        $stmt_dados_fisicos->execute(['id_aluno' => $alunoId]);
        $dadosAluno = $stmt_dados_fisicos->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Processar edição
if (isset($_GET['editar_id']) && $alunoSelecionado) {
    $editarId = intval($_GET['editar_id']);
    foreach ($dadosAluno as $dado) {
        if ($dado['id_fisicoAluno'] === $editarId) {
            $editarDado = $dado;
            break;
        }
    }
}

// Processar exclusão
if (isset($_GET['excluir_id']) && $alunoSelecionado) {
    $excluirId = intval($_GET['excluir_id']);
    $sql_excluir = "DELETE FROM tbl_fisicoAluno WHERE id_fisicoAluno = :id";
    $stmt_excluir = $conn->prepare($sql_excluir);
    if ($stmt_excluir->execute(['id' => $excluirId])) {
        header('Location: ?aluno_id=' . $alunoSelecionado['id_aluno'] . '&excluido=1');
        exit;
    }
}

// Lógica de pesquisa
$resultados = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $termo = '%' . strtolower($_GET['search']) . '%';
    $sql_search = "SELECT id_aluno, nome, email FROM tbl_aluno WHERE LOWER(nome) LIKE :termo1 OR LOWER(email) LIKE :termo2";
    $stmt_search = $conn->prepare($sql_search);
    $stmt_search->execute(['termo1' => $termo, 'termo2' => $termo]);
    $resultados = $stmt_search->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Se não houver pesquisa, carrega todos os alunos
    $resultados = $alunos;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação Física - Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="avaliacao-fisica.css">
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
            <h1>Avaliação Física</h1>
            
            <div class="pesquisa-section">
                <h2>Pesquisar Aluno</h2>
                <form method="GET" class="search-form">
                    <input type="text" name="search" id="searchInput" 
                           placeholder="Digite nome ou matrícula do aluno..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn-pesquisar">Pesquisar</button>
                </form>
                
                <div class="resultados-pesquisa">
                    <h3>Resultados da Pesquisa</h3>
                    <?php if (count($resultados) > 0): ?>
                    <div class="tabela-alunos">
                        <table>
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Matrícula</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultados as $aluno): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                                    <td>
                                        <a href="?aluno_id=<?php echo $aluno['id_aluno']; ?>" class="btn-selecionar">Selecionar</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="no-results">Nenhum aluno encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($alunoSelecionado): ?>
            <div class="aluno-selecionado-section">
                <div class="aluno-header">
                    <div class="aluno-info">
                        <h2><?php echo htmlspecialchars($alunoSelecionado['nome']); ?></h2>
                        <div class="aluno-meta">
                            <span>Matrícula: <?php echo htmlspecialchars($alunoSelecionado['email']); ?></span>
                            <span>N° Dados Físicos: <?php echo count($dadosAluno); ?></span>
                        </div>
                    </div>
                </div>

                <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-sucesso">
                    Dados salvos com sucesso!
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['excluido'])): ?>
                <div class="alert alert-sucesso">
                    Dados excluídos com sucesso!
                </div>
                <?php endif; ?>

                <div class="form-section">
                    <h3><?php echo $editarDado ? 'Editar Dados Físicos' : 'Inserir Novos Dados Físicos'; ?></h3>
                    
                    <form method="POST" action="salvar-dados.php" class="dados-form">
                        <input type="hidden" name="aluno_id" value="<?php echo $alunoSelecionado['id_aluno']; ?>">
                        <?php if ($editarDado): ?>
                        <input type="hidden" name="dado_id" value="<?php echo $editarDado['id_fisicoAluno']; ?>">
                        <?php endif; ?>

                        <div class="form-grid">
                            <div class="form-column">
                                <h4>Editor</h4>
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
                                <h4>Outras Informações</h4>
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
                                           value="<?php echo $editarDado ? date('Y-m-d', strtotime($editarDado['data_alteracao'])) : date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-salvar">
                                <?php echo $editarDado ? 'Atualizar Dados' : 'Salvar Dados'; ?>
                            </button>
                            <?php if ($editarDado): ?>
                            <a href="?aluno_id=<?php echo $alunoSelecionado['id_aluno']; ?>" class="btn-cancelar">Cancelar</a>
                            <?php endif; ?>
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
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($dado['data_alteracao'])); ?></td>
                                    <td><?php echo number_format($dado['braco'], 2); ?></td>
                                    <td><?php echo number_format($dado['abdomen'], 2); ?></td>
                                    <td><?php echo number_format($dado['peso'], 2); ?></td>
                                    <td><?php echo number_format($dado['altura'], 2); ?></td>
                                    <td><?php echo number_format($dado['perna'], 2); ?></td>
                                    <td class="acoes">
                                        <a href="?aluno_id=<?php echo $alunoSelecionado['id_aluno']; ?>&editar_id=<?php echo $dado['id_fisicoAluno']; ?>" 
                                           class="btn-editar">Editar</a>
                                        <a href="?aluno_id=<?php echo $alunoSelecionado['id_aluno']; ?>&excluir_id=<?php echo $dado['id_fisicoAluno']; ?>" 
                                           class="btn-excluir" 
                                           onclick="return confirm('Tem certeza que deseja excluir estes dados?')">Excluir</a>
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
            <?php elseif (isset($_GET['aluno_id'])): ?>
            <div class="no-aluno">
                <p>Aluno não encontrado.</p>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
      <a>© 2025 Nexus Fitness — Todos os direitos reservados.</a>
    </footer>
</body>
</html>