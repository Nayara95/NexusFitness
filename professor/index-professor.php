<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'professor') {
    // Se não estiver logado como professor, redireciona para a página de login
    header('Location: ../login.php');
    exit;
}

$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Professor - Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css">
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

    <main class="painel-professor">
        <!-- Seção 1: Perfil do Professor -->
        <section class="professor-header">
            <div class="professor-info">
                <div class="foto-perfil-container">
                    <div class="foto-perfil-box">
                        <span>Foto</span>
                    </div>
                    <button class="btn-perfil">Ver Perfil</button>
                </div>
                
                <div class="professor-details">
                    <h1>Painel do Professor</h1>
                    <p class="boas-vindas">Seja bem-vindo(a) professor(a) <strong><?php echo htmlspecialchars($email); ?></strong>!</p>
                    <p class="descricao">Gerencie seus alunos e atividades da academia.</p>
                </div>
                
                <div class="professor-actions">
                    <button class="btn-ficha-treino">
                        <span>Fichas de Treinos</span>
                    </button>
                    <div class="pendentes-badge">
                        <span class="pendentes-count">3</span>
                        <span class="pendentes-text">Pendentes</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção 2: Busca de Alunos -->
        <section class="busca-section">
            <div class="busca-container">
                <h2>Buscar Aluno</h2>
                <div class="busca-form">
                    <div class="input-group">
                        <label for="nome-aluno">Nome do Aluno:</label>
                        <input type="text" id="nome-aluno" placeholder="Digite o nome do aluno">
                    </div>
                    
                    <div class="input-group">
                        <label for="matricula-aluno">Matrícula:</label>
                        <input type="text" id="matricula-aluno" placeholder="Digite a matrícula">
                    </div>
                    
                    <button class="btn-pesquisar">
                        <span>Pesquisar</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Seção 3: Lista de Alunos -->
        <section class="alunos-section">
            <div class="section-header">
                <h2>Gerenciamento de Alunos</h2>
            </div>
            
            <div class="alunos-container">
                <!-- Card do Aluno 1 -->
                <div class="aluno-card">
                    <div class="aluno-header">
                        <div class="aluno-foto">
                            <span>Foto</span>
                        </div>
                        <div class="aluno-info">
                            <h3>Nome do Aluno</h3>
                            <span class="matricula">Matrícula: 12345</span>
                        </div>
                    </div>
                    
                    <div class="aluno-content">
                        <div class="aluno-dados">
                            <div class="dados-group">
                                <label>Nome:</label>
                                <input type="text" value="João Silva" readonly>
                            </div>
                            <div class="dados-group">
                                <label>Matrícula:</label>
                                <input type="text" value="12345" readonly>
                            </div>
                        </div>
                        
                        <div class="exercicios-group">
                            <h4>Exercícios Programados</h4>
                            <textarea placeholder="Descreva os exercícios do aluno...">- Supino 3x12
- Agachamento 4x10
- Rosca direta 3x15</textarea>
                        </div>
                        
                        <div class="saude-group">
                            <h4>Dias de Treino</h4>
                            <div class="dias-treino">
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno1"> Segunda-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno1" checked> Terça-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno1"> Quarta-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno1" checked> Quinta-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno1"> Sexta-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno1"> Sábado
                                </label>
                            </div>
                        </div>
                        
                        <div class="agenda-group">
                            <h4>Agenda do Aluno</h4>
                            <textarea placeholder="Observações da agenda...">Treino focado em membros superiores esta semana.</textarea>
                        </div>
                    </div>
                    
                    <div class="aluno-actions">
                        <button class="btn-editar">Editar Ficha</button>
                        <button class="btn-enviar">Enviar</button>
                        <button class="btn-saude">Dados de Saúde</button>
                    </div>
                </div>
                
                <!-- Card do Aluno 2 (exemplo adicional) -->
                <div class="aluno-card">
                    <div class="aluno-header">
                        <div class="aluno-foto">
                            <span>Foto</span>
                        </div>
                        <div class="aluno-info">
                            <h3>Maria Santos</h3>
                            <span class="matricula">Matrícula: 12346</span>
                        </div>
                    </div>
                    
                    <div class="aluno-content">
                        <div class="aluno-dados">
                            <div class="dados-group">
                                <label>Nome:</label>
                                <input type="text" value="Maria Santos" readonly>
                            </div>
                            <div class="dados-group">
                                <label>Matrícula:</label>
                                <input type="text" value="12346" readonly>
                            </div>
                        </div>
                        
                        <div class="exercicios-group">
                            <h4>Exercícios Programados</h4>
                            <textarea placeholder="Descreva os exercícios do aluno...">- Leg press 3x15
- Cadeira extensora 3x12
- Stiff 4x10</textarea>
                        </div>
                        
                        <div class="saude-group">
                            <h4>Dias de Treino</h4>
                            <div class="dias-treino">
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno2"> Segunda-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno2"> Terça-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno2" checked> Quarta-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno2"> Quinta-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno2" checked> Sexta-feira
                                </label>
                                <label class="dia-option">
                                    <input type="checkbox" name="dia-aluno2"> Sábado
                                </label>
                            </div>
                        </div>
                        
                        <div class="agenda-group">
                            <h4>Agenda do Aluno</h4>
                            <textarea placeholder="Observações da agenda...">Foco em membros inferiores com descanso adequado.</textarea>
                        </div>
                    </div>
                    
                    <div class="aluno-actions">
                        <button class="btn-editar">Editar Ficha</button>
                        <button class="btn-enviar">Enviar</button>
                        <button class="btn-saude">Dados de Saúde</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php 
        include('../footer.php');      
    ?>
</body>
</html>