<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

require_once('../autenticacao/conexao.php');
$conn = conectar();

// Processar ações POST antes de qualquer saída HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpar aluno selecionado
    if (isset($_POST['limpar_aluno'])) {
        unset($_SESSION['treino_aluno_id']);
        unset($_SESSION['treino_resultados_pesquisa']);
        unset($_SESSION['treino_termo_pesquisa']);
        unset($_SESSION['treino_edit_mode']);
        header('Location: treino-alunos.php');
        exit;
    }
    
    // Limpar pesquisa
    if (isset($_POST['limpar_pesquisa'])) {
        unset($_SESSION['treino_resultados_pesquisa']);
        unset($_SESSION['treino_termo_pesquisa']);
        header('Location: treino-alunos.php');
        exit;
    }
    
    // Sair do modo de edição
    if (isset($_POST['cancelar_edicao'])) {
        unset($_SESSION['treino_edit_mode']);
        header('Location: treino-alunos.php');
        exit;
    }
    
    // Entrar em modo de edição
    if (isset($_POST['editar_treino'])) {
        $_SESSION['treino_edit_mode'] = true;
        header('Location: treino-alunos.php');
        exit;
    }
}

// Busca todos os alunos
$stmt = $conn->query("SELECT id_aluno as id, nome, email, data_cadastro as data_inicio FROM tbl_aluno");
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$alunoSelecionado = null;
$treinoAluno = null;
$saudeAluno = null;
$dadosSaude = null;
$questionario = [];

// Processar seleção de aluno via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aluno_id_selecionado'])) {
    $alunoId = intval($_POST['aluno_id_selecionado']);
    $_SESSION['treino_aluno_id'] = $alunoId;
} elseif (isset($_SESSION['treino_aluno_id'])) {
    $alunoId = $_SESSION['treino_aluno_id'];
}

if (isset($alunoId)) {
    // Busca o aluno selecionado
    $stmt = $conn->prepare("SELECT id_aluno as id, nome, email FROM tbl_aluno WHERE id_aluno = :id");
    $stmt->execute(['id' => $alunoId]);
    $alunoSelecionado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($alunoSelecionado) {
        $stmt = $conn->prepare("SELECT segunda, terca, quarta, quinta, sexta, sabado, domingo FROM tbl_agendaTreino WHERE id_aluno = :id");
        $stmt->execute(['id' => $alunoId]);
        $treinoAluno = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT peso, altura FROM tbl_fisicoAluno WHERE id_aluno = :id ORDER BY data_alteracao DESC");
        $stmt->execute(['id' => $alunoId]);
        $saudeAluno = $stmt->fetch(PDO::FETCH_ASSOC);

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

// Processa a pesquisa via POST
$resultadosPesquisa = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search']) && !empty($_POST['search'])) {
    $termoPesquisa = trim($_POST['search']);
    $termoPesquisaLike = '%' . strtolower($termoPesquisa) . '%';

    $stmt = $conn->prepare("SELECT id_aluno as id, nome, email FROM tbl_aluno WHERE CAST(id_aluno AS VARCHAR) LIKE :termoId OR LOWER(nome) LIKE :termoNome OR LOWER(email) LIKE :termoEmail");
    $stmt->execute([
        'termoId' => $termoPesquisaLike,
        'termoNome' => $termoPesquisaLike,
        'termoEmail' => $termoPesquisaLike
    ]);
    $resultadosPesquisa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['treino_resultados_pesquisa'] = $resultadosPesquisa;
    $_SESSION['treino_termo_pesquisa'] = $termoPesquisa;
} elseif (isset($_SESSION['treino_resultados_pesquisa'])) {
    $resultadosPesquisa = $_SESSION['treino_resultados_pesquisa'];
}

// Modo de edição via sessão
$isEditMode = isset($_SESSION['treino_edit_mode']);

// Mensagens de sucesso/erro via sessão
$mensagem_sucesso = '';
$mensagem_erro = '';
if (isset($_SESSION['treino_mensagem_sucesso'])) {
    $mensagem_sucesso = $_SESSION['treino_mensagem_sucesso'];
    unset($_SESSION['treino_mensagem_sucesso']);
}
if (isset($_SESSION['treino_mensagem_erro'])) {
    $mensagem_erro = $_SESSION['treino_mensagem_erro'];
    unset($_SESSION['treino_mensagem_erro']);
}

$listaAlunos = (!empty($resultadosPesquisa)) ? $resultadosPesquisa : $alunos;
$termoPesquisaAtual = isset($_SESSION['treino_termo_pesquisa']) ? $_SESSION['treino_termo_pesquisa'] : '';
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

            <?php if ($mensagem_sucesso): ?>
                <div class="alert alert-sucesso"><?php echo htmlspecialchars($mensagem_sucesso); ?></div>
            <?php endif; ?>
            <?php if ($mensagem_erro): ?>
                <div class="alert alert-erro"><?php echo htmlspecialchars($mensagem_erro); ?></div>
            <?php endif; ?>

            <div class="content-wrapper">
                <div class="left-card">
                    <div class="pesquisa-section">
                        <h2>Pesquisar Aluno</h2>
                        <form method="POST" class="search-form">
                            <input type="text" name="search" id="searchInput"
                                   placeholder="Digite ID, nome ou email do aluno..."
                                   value="<?php echo htmlspecialchars($termoPesquisaAtual); ?>">
                            <button type="submit" class="btn-pesquisar">Pesquisar</button>
                            <?php if (!empty($termoPesquisaAtual)): ?>
                                <button type="submit" name="limpar_pesquisa" value="1" class="btn-limpar">Limpar</button>
                            <?php endif; ?>
                        </form>

                        <div class="resultados-pesquisa">
                            <h3><?php echo (!empty($termoPesquisaAtual)) ? 'Resultados da Pesquisa para "' . htmlspecialchars($termoPesquisaAtual) . '"' : 'Todos os Alunos'; ?></h3>
                            <?php if (count($listaAlunos) > 0): ?>
                                <div class="alunos-lista">
                                    <?php foreach ($listaAlunos as $aluno): ?>
                                        <form method="POST" style="margin:0">
                                            <input type="hidden" name="aluno_id_selecionado" value="<?php echo $aluno['id']; ?>">
                                            <button type="submit" class="aluno-item-link">
                                                <div class="aluno-item">
                                                    <div class="aluno-info-basica">
                                                        <span class="aluno-id">ID: <?php echo htmlspecialchars($aluno['id']); ?></span>
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

                <div class="right-card">
                    <?php if ($alunoSelecionado): ?>
                        <div class="aluno-selecionado-section">
                            <form method="POST" style="display:inline">
                                <button type="submit" name="limpar_aluno" value="1" class="btn-voltar-lista">← Voltar para a Lista</button>
                            </form>

                            <div class="aluno-header">
                                <div class="aluno-titulo">
                                    <h2>Ficha de Treino - <?php echo htmlspecialchars($alunoSelecionado['nome']); ?></h2>
                                    <div class="aluno-meta">
                                        <span class="aluno-id">ID: <?php echo htmlspecialchars($alunoSelecionado['id']); ?></span>
                                        <span class="aluno-email"><?php echo htmlspecialchars($alunoSelecionado['email']); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações de Saúde -->
                            <?php if ($saudeAluno || $dadosSaude): ?>
                                <div class="info-saude">
                                    <h3>Informações de Saúde</h3>
                                    <div class="saude-grid">
                                        <?php if ($saudeAluno): ?>
                                            <div class="info-item"><label>Peso:</label><span><?php echo htmlspecialchars($saudeAluno['peso']); ?> kg</span></div>
                                            <div class="info-item"><label>Altura:</label><span><?php echo htmlspecialchars($saudeAluno['altura']); ?> m</span></div>
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
                                                <span><?php if (!empty($dadosSaude['exame_bio'])): ?><a href="../uploads/<?php echo htmlspecialchars($dadosSaude['exame_bio']); ?>" target="_blank">Ver Exame</a><?php else: ?>Não informado<?php endif; ?></span>
                                            </div>
                                            <div class="info-item">
                                                <label>Atestado Médico:</label>
                                                <span><?php if (!empty($dadosSaude['atestado_medico'])): ?><a href="../uploads/<?php echo htmlspecialchars($dadosSaude['atestado_medico']); ?>" target="_blank">Ver Atestado</a><?php else: ?>Não informado<?php endif; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="info-saude"><h3>Informações de Saúde</h3><p class="no-info">Nenhuma informação de saúde cadastrada.</p></div>
                            <?php endif; ?>

                            <!-- Ficha de Treinos -->
<div class="ficha-treinos">
    <?php if ($isEditMode): ?>
        <form action="atualizar-treino.php" method="POST" id="formEdicaoTreino">
            <input type="hidden" name="aluno_id" value="<?php echo $alunoSelecionado['id']; ?>">
            <div class="ficha-header">
                <h3>Ficha de Treinos</h3>
                <div class="ficha-actions">
                    <button type="submit" class="btn-salvar">Salvar Alterações</button>
                    <button type="button" id="btnCancelarEdicao" class="btn-cancelar">Cancelar</button>
                </div>
            </div>
            <div class="dias-treino">
                <?php
                $diasSemana = [
                    'segunda' => 'Segunda-feira', 'terca' => 'Terça-feira', 'quarta' => 'Quarta-feira',
                    'quinta' => 'Quinta-feira', 'sexta' => 'Sexta-feira', 'sabado' => 'Sábado', 'domingo' => 'Domingo'
                ];
                foreach ($diasSemana as $diaKey => $diaNome):
                    $exerciciosDia = isset($treinoAluno[$diaKey]) ? $treinoAluno[$diaKey] : '';
                ?>
                    <div class="dia-treino">
                        <h4><?php echo $diaNome; ?></h4>
                        <div class="exercicios-lista">
                            <textarea name="<?php echo $diaKey; ?>" class="exercicio-input" rows="4"><?php echo htmlspecialchars($exerciciosDia); ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
        <script>
            document.getElementById('btnCancelarEdicao').addEventListener('click', function() {
                var form = document.createElement('form');
                form.method = 'POST';
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'cancelar_edicao';
                input.value = '1';
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            });
        </script>
    <?php else: ?>
        <div class="ficha-header">
            <h3>Ficha de Treinos</h3>
            <div class="ficha-actions">
                <form method="POST" style="display:inline">
                    <button type="submit" name="editar_treino" value="1" class="btn-editar">Editar Ficha</button>
                </form>
            </div>
        </div>
        <div class="dias-treino">
            <?php
            $diasSemana = [
                'segunda' => 'Segunda-feira', 'terca' => 'Terça-feira', 'quarta' => 'Quarta-feira',
                'quinta' => 'Quinta-feira', 'sexta' => 'Sexta-feira', 'sabado' => 'Sábado', 'domingo' => 'Domingo'
            ];
            foreach ($diasSemana as $diaKey => $diaNome):
                $exerciciosDia = isset($treinoAluno[$diaKey]) ? $treinoAluno[$diaKey] : '';
            ?>
                <div class="dia-treino">
                    <h4><?php echo $diaNome; ?></h4>
                    <div class="exercicios-lista">
                        <?php if (!empty($exerciciosDia)): ?>
                            <div class="exercicio-item"><div class="exercicio-nome"><?php echo nl2br(htmlspecialchars($exerciciosDia)); ?></div></div>
                        <?php else: ?>
                            <p class="sem-treino">Sem treino cadastrado</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


    <?php if ($isEditMode): ?>
        <!-- Formulário de edição (envia para atualizar-treino.php) -->
        <form action="atualizar-treino.php" method="POST">
            <input type="hidden" name="aluno_id" value="<?php echo $alunoSelecionado['id']; ?>">
            <div class="ficha-header">
                <h3>Ficha de Treinos</h3>
                <div class="ficha-actions">
                    <button type="submit" class="btn-salvar">Salvar Alterações</button>
                    <!-- Botão Cancelar (formulário separado) -->
                    <form method="POST" style="display:inline">
                        <button type="submit" name="cancelar_edicao" value="1" class="btn-cancelar">Cancelar</button>
                    </form>
                </div>
            </div>
            <div class="dias-treino">
                <?php
                $diasSemana = [
                    'segunda' => 'Segunda-feira', 'terca' => 'Terça-feira', 'quarta' => 'Quarta-feira',
                    'quinta' => 'Quinta-feira', 'sexta' => 'Sexta-feira', 'sabado' => 'Sábado', 'domingo' => 'Domingo'
                ];
                foreach ($diasSemana as $diaKey => $diaNome):
                    $exerciciosDia = isset($treinoAluno[$diaKey]) ? $treinoAluno[$diaKey] : '';
                ?>
                    <div class="dia-treino">
                        <h4><?php echo $diaNome; ?></h4>
                        <div class="exercicios-lista">
                            <textarea name="<?php echo $diaKey; ?>" class="exercicio-input" rows="4"><?php echo htmlspecialchars($exerciciosDia); ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
    <?php else: ?>
        <!-- Visualização normal (sem edição) -->
        <div class="ficha-header">
            <h3>Ficha de Treinos</h3>
            <div class="ficha-actions">
                <!-- Botão Editar Ficha (formulário separado) -->
                <form method="POST" style="display:inline">
                    <button type="submit" name="editar_treino" value="1" class="btn-editar">Editar Ficha</button>
                </form>
            </div>
        </div>
        <div class="dias-treino">
            <?php
            $diasSemana = [
                'segunda' => 'Segunda-feira', 'terca' => 'Terça-feira', 'quarta' => 'Quarta-feira',
                'quinta' => 'Quinta-feira', 'sexta' => 'Sexta-feira', 'sabado' => 'Sábado', 'domingo' => 'Domingo'
            ];
            foreach ($diasSemana as $diaKey => $diaNome):
                $exerciciosDia = isset($treinoAluno[$diaKey]) ? $treinoAluno[$diaKey] : '';
            ?>
                <div class="dia-treino">
                    <h4><?php echo $diaNome; ?></h4>
                    <div class="exercicios-lista">
                        <?php if (!empty($exerciciosDia)): ?>
                            <div class="exercicio-item"><div class="exercicio-nome"><?php echo nl2br(htmlspecialchars($exerciciosDia)); ?></div></div>
                        <?php else: ?>
                            <p class="sem-treino">Sem treino cadastrado</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


                        </div>
                    <?php elseif (isset($_SESSION['treino_aluno_id']) && !$alunoSelecionado): ?>
                        <div class="no-aluno"><p>Aluno não encontrado.</p></div>
                    <?php else: ?>
                        <div class="no-aluno"><p>Selecione um aluno para ver suas informações.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <footer><a>© 2025 Nexus Fitness — Todos os direitos reservados.</a></footer>
</body>
</html>