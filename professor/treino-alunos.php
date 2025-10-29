<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'professor') {
    // Se não estiver logado como professor, redireciona para a página de login
    header('Location: ../login.php');
    exit;
}

// Dados de exemplo para demonstração
$alunos = [
    [
        'id' => 1,
        'nome' => 'João Silva',
        'email' => 'joao.silva@email.com',
        'status' => 'ativo',
        'data_inicio' => '2023-10-15'
    ],
    [
        'id' => 2,
        'nome' => 'Maria Santos',
        'email' => 'maria.santos@email.com',
        'status' => 'ativo',
        'data_inicio' => '2023-10-20'
    ],
    [
        'id' => 3,
        'nome' => 'Pedro Oliveira',
        'email' => 'pedro.oliveira@email.com',
        'status' => 'inativo',
        'data_inicio' => '2023-09-05'
    ],
    [
        'id' => 4,
        'nome' => 'Ana Costa',
        'email' => 'ana.costa@email.com',
        'status' => 'ativo',
        'data_inicio' => '2023-11-01'
    ],
    [
        'id' => 5,
        'nome' => 'Carlos Lima',
        'email' => 'carlos.lima@email.com',
        'status' => 'ativo',
        'data_inicio' => '2023-10-25'
    ]
];

// Dados de treinos de exemplo
$treinos = [
    1 => [ // ID do João Silva
        'segunda' => [
            ['exercicio' => 'Supino Reto', 'series' => '4x12', 'carga' => '40kg'],
            ['exercicio' => 'Crucifixo', 'series' => '3x15', 'carga' => '12kg'],
            ['exercicio' => 'Tríceps Corda', 'series' => '3x12', 'carga' => '20kg']
        ],
        'terca' => [
            ['exercicio' => 'Barra Fixa', 'series' => '4x8', 'carga' => 'Peso Corporal'],
            ['exercicio' => 'Remada Curvada', 'series' => '3x10', 'carga' => '50kg']
        ],
        'quarta' => [
            ['exercicio' => 'Agachamento Livre', 'series' => '4x10', 'carga' => '60kg'],
            ['exercicio' => 'Leg Press', 'series' => '3x12', 'carga' => '100kg']
        ],
        'quinta' => [],
        'sexta' => [
            ['exercicio' => 'Desenvolvimento', 'series' => '4x12', 'carga' => '25kg'],
            ['exercicio' => 'Elevação Lateral', 'series' => '3x15', 'carga' => '8kg']
        ],
        'sabado' => [],
        'domingo' => []
    ],
    2 => [ // ID da Maria Santos
        'segunda' => [
            ['exercicio' => 'Leg Press', 'series' => '4x12', 'carga' => '80kg'],
            ['exercicio' => 'Cadeira Extensora', 'series' => '3x15', 'carga' => '30kg']
        ],
        'terca' => [],
        'quarta' => [
            ['exercicio' => 'Supino Inclinado', 'series' => '4x10', 'carga' => '30kg'],
            ['exercicio' => 'Cross Over', 'series' => '3x12', 'carga' => '15kg']
        ],
        'quinta' => [
            ['exercicio' => 'Puxada Alta', 'series' => '4x10', 'carga' => '40kg'],
            ['exercicio' => 'Remada Baixa', 'series' => '3x12', 'carga' => '35kg']
        ],
        'sexta' => [],
        'sabado' => [
            ['exercicio' => 'Abdominal Supra', 'series' => '3x20', 'carga' => 'Peso Corporal'],
            ['exercicio' => 'Prancha', 'series' => '3x1min', 'carga' => 'Peso Corporal']
        ],
        'domingo' => []
    ],
    3 => [ // ID do Pedro Oliveira
        'segunda' => [
            ['exercicio' => 'Rosca Direta', 'series' => '4x12', 'carga' => '15kg'],
            ['exercicio' => 'Rosca Martelo', 'series' => '3x15', 'carga' => '12kg']
        ],
        'terca' => [],
        'quarta' => [],
        'quinta' => [
            ['exercicio' => 'Agachamento Smith', 'series' => '4x10', 'carga' => '50kg'],
            ['exercicio' => 'Afundo', 'series' => '3x12', 'carga' => '10kg']
        ],
        'sexta' => [],
        'sabado' => [],
        'domingo' => []
    ]
];

// Informações de saúde dos alunos
$infoSaude = [
    1 => [
        'peso' => '78kg',
        'altura' => '175cm',
        'objetivo' => 'Hipertrofia',
        'restricoes' => 'Problema no ombro direito',
        'observacoes' => 'Precisa focar em técnica'
    ],
    2 => [
        'peso' => '65kg',
        'altura' => '165cm',
        'objetivo' => 'Definição Muscular',
        'restricoes' => 'Nenhuma',
        'observacoes' => 'Boa evolução nos últimos 2 meses'
    ],
    3 => [
        'peso' => '85kg',
        'altura' => '180cm',
        'objetivo' => 'Emagrecimento',
        'restricoes' => 'Problema no joelho esquerdo',
        'observacoes' => 'Focar em exercícios de baixo impacto'
    ]
];

$alunoSelecionado = null;
$treinoAluno = null;
$saudeAluno = null;

// Processa a seleção direta por ID
if (isset($_GET['aluno_id']) && !empty($_GET['aluno_id'])) {
    $alunoId = intval($_GET['aluno_id']);
    
    // Busca o aluno selecionado
    foreach ($alunos as $aluno) {
        if ($aluno['id'] === $alunoId) {
            $alunoSelecionado = $aluno;
            break;
        }
    }
    
    // Carrega treinos e informações de saúde se o aluno foi encontrado
    if ($alunoSelecionado) {
        $treinoAluno = isset($treinos[$alunoId]) ? $treinos[$alunoId] : [];
        $saudeAluno = isset($infoSaude[$alunoId]) ? $infoSaude[$alunoId] : null;
    }
}

// Processa a pesquisa
$resultadosPesquisa = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $termoPesquisa = strtolower(trim($_GET['search']));
    
    foreach ($alunos as $aluno) {
        // Busca por ID (se o termo for numérico)
        if (is_numeric($termoPesquisa) && intval($termoPesquisa) === $aluno['id']) {
            $resultadosPesquisa[] = $aluno;
        }
        // Busca por nome
        elseif (strpos(strtolower($aluno['nome']), $termoPesquisa) !== false) {
            $resultadosPesquisa[] = $aluno;
        }
        // Busca por email
        elseif (strpos(strtolower($aluno['email']), $termoPesquisa) !== false) {
            $resultadosPesquisa[] = $aluno;
        }
    }
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
                
                <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                <div class="resultados-pesquisa">
                    <h3>Resultados da Pesquisa para "<?php echo htmlspecialchars($_GET['search']); ?>"</h3>
                    
                    <?php if (count($resultadosPesquisa) > 0): ?>
                    <div class="alunos-lista">
                        <?php foreach ($resultadosPesquisa as $aluno): ?>
                            <div class="aluno-item">
                                <div class="aluno-info-basica">
                                    <div class="aluno-id">ID: <?php echo $aluno['id']; ?></div>
                                    <strong><?php echo $aluno['nome']; ?></strong>
                                    <span class="aluno-email"><?php echo $aluno['email']; ?></span>
                                    <span class="status status-<?php echo $aluno['status']; ?>">
                                        <?php echo ucfirst($aluno['status']); ?>
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
                <?php endif; ?>
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
                        <div class="info-item">
                            <label>Objetivo:</label>
                            <span><?php echo $saudeAluno['objetivo']; ?></span>
                        </div>
                        <div class="info-item full-width">
                            <label>Restrições:</label>
                            <span><?php echo $saudeAluno['restricoes']; ?></span>
                        </div>
                        <div class="info-item full-width">
                            <label>Observações:</label>
                            <span><?php echo $saudeAluno['observacoes']; ?></span>
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
                                <?php if (count($exerciciosDia) > 0): ?>
                                    <?php foreach ($exerciciosDia as $index => $exercicio): ?>
                                    <div class="exercicio-item">
                                        <div class="exercicio-header">
                                            <span class="exercicio-numero"><?php echo $index + 1; ?>.</span>
                                            <div class="exercicio-nome"><?php echo $exercicio['exercicio']; ?></div>
                                        </div>
                                        <div class="exercicio-detalhes">
                                            <span><strong>Séries:</strong> <?php echo $exercicio['series']; ?></span>
                                            <span><strong>Carga:</strong> <?php echo $exercicio['carga']; ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
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

    <?php include ('../footer.php'); ?>
</body>
</html>