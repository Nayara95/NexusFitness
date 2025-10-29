<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

// Simulação de banco de dados
$alunos = [
    ['id' => 1, 'nome' => 'admin', 'matricula' => '1'],
    ['id' => 2, 'nome' => 'Brenda Marli dos Santos', 'matricula' => '2'],
    ['id' => 3, 'nome' => 'Enrico Mateus M?ccio da Luz', 'matricula' => '3']
];

// Dados físicos de exemplo
$dadosFisicos = [
    1 => [ // ID do admin
        [
            'id' => 1,
            'data' => '2019-06-01',
            'bracos' => 33.00,
            'cintura' => 82.00,
            'peso' => 79.00,
            'altura' => 1.70,
            'pernas' => 63.00
        ],
        [
            'id' => 2,
            'data' => '2019-07-01',
            'bracos' => 34.00,
            'cintura' => 80.00,
            'peso' => 78.00,
            'altura' => 1.70,
            'pernas' => 64.00
        ]
    ],
    2 => [ // ID da Brenda
        [
            'id' => 3,
            'data' => '2019-06-01',
            'bracos' => 28.00,
            'cintura' => 75.00,
            'peso' => 65.00,
            'altura' => 1.65,
            'pernas' => 58.00
        ]
    ]
];

$alunoSelecionado = null;
$dadosAluno = [];
$editarDado = null;

// Processar seleção de aluno
if (isset($_GET['aluno_id'])) {
    $alunoId = intval($_GET['aluno_id']);
    foreach ($alunos as $aluno) {
        if ($aluno['id'] === $alunoId) {
            $alunoSelecionado = $aluno;
            break;
        }
    }
    
    if ($alunoSelecionado && isset($dadosFisicos[$alunoId])) {
        $dadosAluno = $dadosFisicos[$alunoId];
    }
}

// Processar edição
if (isset($_GET['editar_id']) && $alunoSelecionado) {
    $editarId = intval($_GET['editar_id']);
    foreach ($dadosAluno as $dado) {
        if ($dado['id'] === $editarId) {
            $editarDado = $dado;
            break;
        }
    }
}

// Processar exclusão
if (isset($_GET['excluir_id']) && $alunoSelecionado) {
    $excluirId = intval($_GET['excluir_id']);
    // Simular exclusão
    header('Location: ?aluno_id=' . $alunoSelecionado['id'] . '&excluido=1');
    exit;
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
            
            <!-- Seção de Pesquisa -->
            <div class="pesquisa-section">
                <h2>Pesquisar Aluno</h2>
                <form method="GET" class="search-form">
                    <input type="text" name="search" id="searchInput" 
                           placeholder="Digite nome ou matrícula do aluno..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn-pesquisar">Pesquisar</button>
                </form>
                
                <?php if (isset($_GET['search']) && !empty($_GET['search'])): 
                    $termo = strtolower($_GET['search']);
                    $resultados = array_filter($alunos, function($aluno) use ($termo) {
                        return strpos(strtolower($aluno['nome']), $termo) !== false || 
                               strpos(strtolower($aluno['matricula']), $termo) !== false;
                    });
                ?>
                <div class="resultados-pesquisa">
                    <h3>Resultados da Pesquisa</h3>
                    <?php if (count($resultados) > 0): ?>
                    <div class="tabela-alunos">
                        <table>
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Matrícula</th>
                                    <th>N° Dado Físico</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultados as $aluno): 
                                    $numDados = isset($dadosFisicos[$aluno['id']]) ? count($dadosFisicos[$aluno['id']]) : 0;
                                ?>
                                <tr>
                                    <td><?php echo $aluno['nome']; ?></td>
                                    <td><?php echo $aluno['matricula']; ?></td>
                                    <td><?php echo $numDados; ?></td>
                                    <td>
                                        <a href="?aluno_id=<?php echo $aluno['id']; ?>" class="btn-selecionar">Selecionar</a>
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
                <?php endif; ?>
            </div>

            <!-- Seção do Aluno Selecionado -->
            <?php if ($alunoSelecionado): ?>
            <div class="aluno-selecionado-section">
                <div class="aluno-header">
                    <div class="aluno-info">
                        <h2><?php echo $alunoSelecionado['nome']; ?></h2>
                        <div class="aluno-meta">
                            <span>Matrícula: <?php echo $alunoSelecionado['matricula']; ?></span>
                            <span>N° Dados Físicos: <?php echo count($dadosAluno); ?></span>
                        </div>
                    </div>
                    <div class="aluno-actions">
                        <a href="criar-exercicio.php" class="btn-criar-exercicio" target="_blank">Criar Exercício</a>
                        <a href="historico-grafico.php?aluno_id=<?php echo $alunoSelecionado['id']; ?>" class="btn-historico">Ver Histórico e Gráfico</a>
                    </div>
                </div>

                <!-- Mensagens de Sucesso -->
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

                <!-- Formulário de Dados Físicos -->
                <div class="form-section">
                    <h3><?php echo $editarDado ? 'Editar Dados Físicos' : 'Inserir Novos Dados Físicos'; ?></h3>
                    
                    <form method="POST" action="salvar-dados.php" class="dados-form">
                        <input type="hidden" name="aluno_id" value="<?php echo $alunoSelecionado['id']; ?>">
                        <?php if ($editarDado): ?>
                        <input type="hidden" name="dado_id" value="<?php echo $editarDado['id']; ?>">
                        <?php endif; ?>

                        <div class="form-grid">
                            <!-- Coluna 1 - Editor -->
                            <div class="form-column">
                                <h4>Editor</h4>
                                
                                <div class="input-group">
                                    <label for="bracos">Braços (CM)</label>
                                    <input type="number" id="bracos" name="bracos" step="0.01" 
                                           value="<?php echo $editarDado ? $editarDado['bracos'] : ''; ?>" required>
                                    <span class="unidade">CM</span>
                                </div>

                                <div class="input-group">
                                    <label for="cintura">Cintura (CM)</label>
                                    <input type="number" id="cintura" name="cintura" step="0.01"
                                           value="<?php echo $editarDado ? $editarDado['cintura'] : ''; ?>" required>
                                    <span class="unidade">CM</span>
                                </div>

                                <div class="input-group">
                                    <label for="peso">Peso (KG)</label>
                                    <input type="number" id="peso" name="peso" step="0.01"
                                           value="<?php echo $editarDado ? $editarDado['peso'] : ''; ?>" required>
                                    <span class="unidade">KG</span>
                                </div>
                            </div>

                            <!-- Coluna 2 - Outras Informações -->
                            <div class="form-column">
                                <h4>Outras Informações</h4>
                                
                                <div class="input-group">
                                    <label for="altura">Altura (M)</label>
                                    <input type="number" id="altura" name="altura" step="0.01"
                                           value="<?php echo $editarDado ? $editarDado['altura'] : ''; ?>" required>
                                    <span class="unidade">M</span>
                                </div>

                                <div class="input-group">
                                    <label for="pernas">Pernas (CM)</label>
                                    <input type="number" id="pernas" name="pernas" step="0.01"
                                           value="<?php echo $editarDado ? $editarDado['pernas'] : ''; ?>" required>
                                    <span class="unidade">CM</span>
                                </div>

                                <div class="input-group data-group">
                                    <label>Data da Medida</label>
                                    <div class="data-inputs">
                                        <div class="data-field">
                                            <input type="number" id="dia" name="dia" min="1" max="31" 
                                                   placeholder="DD" value="<?php echo $editarDado ? date('d', strtotime($editarDado['data'])) : ''; ?>" required>
                                            <span class="data-label">DIA</span>
                                        </div>
                                        <div class="data-field">
                                            <input type="number" id="mes" name="mes" min="1" max="12"
                                                   placeholder="MM" value="<?php echo $editarDado ? date('m', strtotime($editarDado['data'])) : ''; ?>" required>
                                            <span class="data-label">MÊS</span>
                                        </div>
                                        <div class="data-field">
                                            <input type="number" id="ano" name="ano" min="2000" max="2030"
                                                   placeholder="AAAA" value="<?php echo $editarDado ? date('Y', strtotime($editarDado['data'])) : ''; ?>" required>
                                            <span class="data-label">ANO</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-salvar">
                                <?php echo $editarDado ? 'Atualizar Dados' : 'Salvar Dados'; ?>
                            </button>
                            <?php if ($editarDado): ?>
                            <a href="?aluno_id=<?php echo $alunoSelecionado['id']; ?>" class="btn-cancelar">Cancelar</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Histórico de Dados -->
                <?php if (count($dadosAluno) > 0): ?>
                <div class="historico-section">
                    <h3>Histórico de Dados Físicos</h3>
                    
                    <div class="tabela-dados">
                        <table>
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Braços (CM)</th>
                                    <th>Cintura (CM)</th>
                                    <th>Peso (KG)</th>
                                    <th>Altura (M)</th>
                                    <th>Pernas (CM)</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dadosAluno as $dado): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($dado['data'])); ?></td>
                                    <td><?php echo number_format($dado['bracos'], 2); ?></td>
                                    <td><?php echo number_format($dado['cintura'], 2); ?></td>
                                    <td><?php echo number_format($dado['peso'], 2); ?></td>
                                    <td><?php echo number_format($dado['altura'], 2); ?></td>
                                    <td><?php echo number_format($dado['pernas'], 2); ?></td>
                                    <td class="acoes">
                                        <a href="?aluno_id=<?php echo $alunoSelecionado['id']; ?>&editar_id=<?php echo $dado['id']; ?>" 
                                           class="btn-editar">Editar</a>
                                        <a href="?aluno_id=<?php echo $alunoSelecionado['id']; ?>&excluir_id=<?php echo $dado['id']; ?>" 
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

    <?php include ('../footer.php'); ?>
</body>
</html>