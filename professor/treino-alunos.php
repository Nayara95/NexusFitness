<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'professor') {
    // Se não estiver logado como professor, redireciona para a página de login
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treinos dos Alunos - Nexus Fitness</title>
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

    <main class="treinos-alunos-page">
        <!-- Cabeçalho da Página -->
        <section class="page-header">
            <div class="header-content">
                <h1>Gerenciar Treinos dos Alunos</h1>
                <p>Visualize e edite as fichas de treino dos seus alunos</p>
            </div>
            <div class="professor-actions">
                <a href="index-professor.php" class=" btn-pendentes">
                      <span>Área Principal</span>
                    </a>
                    <a href="treino-alunos.php" class=" btn-pendentes">
                      <span class="pendentes-count">3</span>
                      <span class="pendentes-text">Pendentes</span>
                </a>
            </div>
        </section>

        <!-- Filtros e Busca -->
        <section class="filtros-section">
            <div class="filtros-container">
                <div class="search-group">
                    <input type="text" placeholder="Buscar aluno por nome..." class="search-input">
                    <button class="btn-search">🔍</button>
                </div>
                <div class="filter-group">
                    <select class="filter-select">
                        <option value="">Todos os status</option>
                        <option value="pendente">Pendentes</option>
                        <option value="ativo">Ativos</option>
                        <option value="inativo">Inativos</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Lista de Alunos com Fichas -->
        <section class="alunos-fichas-section">
            <div class="section-title">
                <h2>Fichas de Treino dos Alunos</h2>
            </div>

            <div class="fichas-grid">
                <!-- Ficha 1 -->
                <div class="ficha-card">
                    <div class="ficha-header">
                        <div class="aluno-info">
                            <div class="foto-placeholder">FT</div>
                            <div class="aluno-detalhes">
                                <h3>João Silva</h3>
                                <span class="matricula">Matrícula: 00001</span>
                                <span class="status status-pendente">Pendente</span>
                            </div>
                        </div>
                        <button class="btn-criar-ficha" data-aluno="João Silva" data-matricula="00001">
                            Criar Ficha
                        </button>
                    </div>
                    
                    <div class="ficha-preview">
                        <div class="preview-item">
                            <strong>Última atualização:</strong>
                            <span>Nenhuma ficha criada</span>
                        </div>
                        <div class="preview-item">
                            <strong>Dias de treino:</strong>
                            <span>---</span>
                        </div>
                    </div>
                </div>

                <!-- Ficha 2 -->
                <div class="ficha-card">
                    <div class="ficha-header">
                        <div class="aluno-info">
                            <div class="foto-placeholder">FT</div>
                            <div class="aluno-detalhes">
                                <h3>Maria Santos</h3>
                                <span class="matricula">Matrícula: 00002</span>
                                <span class="status status-pendente">Pendente</span>
                            </div>
                        </div>
                        <button class="btn-criar-ficha" data-aluno="Maria Santos" data-matricula="00002">
                            Criar Ficha
                        </button>
                    </div>
                    
                    <div class="ficha-preview">
                        <div class="preview-item">
                            <strong>Última atualização:</strong>
                            <span>Nenhuma ficha criada</span>
                        </div>
                        <div class="preview-item">
                            <strong>Dias de treino:</strong>
                            <span>---</span>
                        </div>
                    </div>
                </div>

                <!-- Ficha 3 -->
                <div class="ficha-card">
                    <div class="ficha-header">
                        <div class="aluno-info">
                            <div class="foto-placeholder">FT</div>
                            <div class="aluno-detalhes">
                                <h3>Pedro Oliveira</h3>
                                <span class="matricula">Matrícula: 00003</span>
                                <span class="status status-pendente">Pendente</span>
                            </div>
                        </div>
                        <button class="btn-criar-ficha" data-aluno="Pedro Oliveira" data-matricula="00003">
                            Criar Ficha
                        </button>
                    </div>
                    
                    <div class="ficha-preview">
                        <div class="preview-item">
                            <strong>Última atualização:</strong>
                            <span>Nenhuma ficha criada</span>
                        </div>
                        <div class="preview-item">
                            <strong>Dias de treino:</strong>
                            <span>---</span>
                        </div>
                    </div>
                </div>

                <!-- Ficha 4 (com ficha existente) -->
                <div class="ficha-card">
                    <div class="ficha-header">
                        <div class="aluno-info">
                            <div class="foto-placeholder">FT</div>
                            <div class="aluno-detalhes">
                                <h3>Ana Costa</h3>
                                <span class="matricula">Matrícula: 00004</span>
                                <span class="status status-ativo">Ativo</span>
                            </div>
                        </div>
                        <button class="btn-editar-ficha" data-aluno="Ana Costa" data-matricula="00004">
                            Editar Ficha
                        </button>
                    </div>
                    
                    <div class="ficha-preview">
                        <div class="preview-item">
                            <strong>Última atualização:</strong>
                            <span>15/01/2024</span>
                        </div>
                        <div class="preview-item">
                            <strong>Dias de treino:</strong>
                            <span>Seg, Qua, Sex</span>
                        </div>
                        <div class="preview-item">
                            <strong>Foco:</strong>
                            <span>Musculação - Membros Superiores</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal de Ficha de Treino -->
        <div class="ficha-treino-modal" id="fichaTreinoModal">
            <div class="ficha-treino-content">
                <div class="ficha-header">
                    <h2 id="fichaTitulo">Ficha de Treino - <span id="nomeAlunoFicha"></span></h2>
                    <button class="close-ficha" id="closeFicha">&times;</button>
                </div>
                
                <div class="ficha-container">
                    <div class="ficha-left">
                        <div class="aluno-foto-section">
                            <div class="foto-placeholder-modal">
                                <span>Foto</span>
                            </div>
                            <div class="matricula-input">
                                <label>Matrícula:</label>
                                <input type="text" id="matriculaAluno" readonly>
                            </div>
                        </div>
                        
                        <div class="exercicios-section">
                            <h3>Exercícios Programados:</h3>
                            <textarea placeholder="Descreva os exercícios do aluno..." rows="6"></textarea>
                        </div>

                        <div class="fisico-section">
                            <h3>Físico do Aluno</h3>
                            <div class="medidas-grid">
                                <div class="medida-input">
                                    <label>Altura:</label>
                                    <input type="text" placeholder="______">
                                </div>
                                <div class="medida-input">
                                    <label>Braço:</label>
                                    <input type="text" placeholder="______">
                                </div>
                                <div class="medida-input">
                                    <label>Cintura:</label>
                                    <input type="text" placeholder="______">
                                </div>
                                <div class="medida-input">
                                    <label>Perna:</label>
                                    <input type="text" placeholder="______">
                                </div>
                                <div class="medida-input">
                                    <label>Peso:</label>
                                    <input type="text" placeholder="______">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ficha-right">
                        <div class="saude-agenda-container">
                            <div class="saude-section">
                                <h3>Saúde do Aluno</h3>
                                <div class="dias-treino-grid">
                                    <label class="dia-checkbox">
                                        <input type="checkbox"> Segunda-Feira
                                    </label>
                                    <label class="dia-checkbox">
                                        <input type="checkbox"> Terça-Feira
                                    </label>
                                    <label class="dia-checkbox">
                                        <input type="checkbox"> Quarta-Feira
                                    </label>
                                    <label class="dia-checkbox">
                                        <input type="checkbox"> Quinta-Feira
                                    </label>
                                    <label class="dia-checkbox">
                                        <input type="checkbox"> Sexta-Feira
                                    </label>
                                    <label class="dia-checkbox">
                                        <input type="checkbox"> Sábado
                                    </label>
                                    <label class="dia-checkbox">
                                        <input type="checkbox"> Domingo
                                    </label>
                                </div>
                            </div>
                            
                            <div class="agenda-section">
                                <h3>Agenda do Aluno</h3>
                                <textarea placeholder="Observações da agenda..." rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="ficha-actions">
                    <button class="btn-salvar-rascunho">Salvar Rascunho</button>
                    <button class="btn-enviar-ficha">Enviar Ficha</button>
                </div>
            </div>
        </div>
    </main>
    <script src="../script.js"></script>

    <?php 
        include('../footer.php');      
    ?>