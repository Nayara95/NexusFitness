<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

// --- DADOS DE EXEMPLO DO PROFESSOR ---
// Em um sistema real, você buscaria isso de um banco de dados
$professor_info = [
    'id' => 1,
    'nome' => 'João da Silva',
    'email' => $_SESSION['email'],
    'telefone' => '(11) 99999-9999',
    'especialidade' => 'Musculação e Treinamento Funcional',
    'cref' => '123456-G/SP',
    'experiencia' => '5 anos',
    'formacao' => 'Educação Física - USP',
    'sobre' => 'Professor dedicado com foco em resultados e bem-estar dos alunos.',
    'foto' => '../imagens/avatar-professor.jpg'
];

// Processar upload de foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $uploadDir = '../uploads/professores/';
    
    // Criar diretório se não existir
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = 'professor_' . $professor_info['id'] . '_' . time() . '_' . basename($_FILES['foto']['name']);
    $uploadFile = $uploadDir . $fileName;
    
    // Verificar se é uma imagem
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            $professor_info['foto'] = $uploadFile;
            $mensagem = "Foto atualizada com sucesso!";
        } else {
            $erro = "Erro ao fazer upload da foto.";
        }
    } else {
        $erro = "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
    }
}

// Processar atualização de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
    $professor_info['nome'] = $_POST['nome'] ?? $professor_info['nome'];
    $professor_info['telefone'] = $_POST['telefone'] ?? $professor_info['telefone'];
    $professor_info['especialidade'] = $_POST['especialidade'] ?? $professor_info['especialidade'];
    $professor_info['cref'] = $_POST['cref'] ?? $professor_info['cref'];
    $professor_info['experiencia'] = $_POST['experiencia'] ?? $professor_info['experiencia'];
    $professor_info['formacao'] = $_POST['formacao'] ?? $professor_info['formacao'];
    $professor_info['sobre'] = $_POST['sobre'] ?? $professor_info['sobre'];
    
    $mensagem = "Dados atualizados com sucesso!";
}

// Processar alteração de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'alterar_senha') {
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    if ($nova_senha === $confirmar_senha) {
        // Em um sistema real, você verificaria a senha atual no banco
        $mensagem = "Senha alterada com sucesso!";
    } else {
        $erro = "As senhas não coincidem.";
    }
}

// Função auxiliar para exibir valores de forma segura
function exibirValor($array, $chave, $padrao = '') {
    return isset($array[$chave]) ? htmlspecialchars($array[$chave]) : $padrao;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Professor - Nexus Fitness</title>
    <link rel="stylesheet" href="../style.css">
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
        <div class="perfil-container">
            <h2>Meu Perfil</h2>
            
            <!-- Mensagens de Sucesso/Erro -->
            <?php if (isset($mensagem)): ?>
            <div class="alert alert-sucesso">
                <?php echo $mensagem; ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($erro)): ?>
            <div class="alert alert-erro">
                <?php echo $erro; ?>
            </div>
            <?php endif; ?>
            
            <!-- Cabeçalho do Perfil -->
            <div class="perfil-header">
                <div class="foto-perfil">
                    <img src="<?php echo exibirValor($professor_info, 'foto', '../imagens/professor-nexus.png'); ?>" alt="Foto do Professor" id="fotoPreview">
                    <button class="btn-alterar-foto" onclick="abrirModalFoto()">Alterar Foto</button>
                </div>
                
                <div class="info-basica">
                    <h3><?php echo exibirValor($professor_info, 'nome'); ?></h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Email:</label>
                            <span><?php echo exibirValor($professor_info, 'email'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Telefone:</label>
                            <span><?php echo exibirValor($professor_info, 'telefone', 'Não informado'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>CREF:</label>
                            <span><?php echo exibirValor($professor_info, 'cref'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Experiência:</label>
                            <span><?php echo exibirValor($professor_info, 'experiencia', 'Não informada'); ?></span>
                        </div>
                    </div>
                    <div class="sobre-professor">
                        <label><strong>Sobre:</strong></label>
                        <p><?php echo exibirValor($professor_info, 'sobre', 'Nenhuma informação adicional.'); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Formulário de Edição de Dados -->
            <div class="form-section">
                <h3>Editar Dados Pessoais</h3>
                <form method="POST" action="">
                    <input type="hidden" name="acao" value="atualizar">
                    
                    <div class="form-grid">
                        <div class="input-group">
                            <label for="nome">Nome Completo</label>
                            <input type="text" id="nome" name="nome" value="<?php echo exibirValor($professor_info, 'nome'); ?>" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo exibirValor($professor_info, 'email'); ?>" required readonly>
                            <small style="color: #666;">Email não pode ser alterado</small>
                        </div>
                        
                        <div class="input-group">
                            <label for="telefone">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" value="<?php echo exibirValor($professor_info, 'telefone'); ?>" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="cref">CREF</label>
                            <input type="text" id="cref" name="cref" value="<?php echo exibirValor($professor_info, 'cref'); ?>" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="especialidade">Especialidade</label>
                            <input type="text" id="especialidade" name="especialidade" value="<?php echo exibirValor($professor_info, 'especialidade'); ?>" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="experiencia">Tempo de Experiência</label>
                            <input type="text" id="experiencia" name="experiencia" value="<?php echo exibirValor($professor_info, 'experiencia'); ?>" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="formacao">Formação Acadêmica</label>
                            <input type="text" id="formacao" name="formacao" value="<?php echo exibirValor($professor_info, 'formacao'); ?>" required>
                        </div>
                        
                        <div class="input-group full-width">
                            <label for="sobre">Sobre</label>
                            <textarea id="sobre" name="sobre" required><?php echo exibirValor($professor_info, 'sobre'); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-salvar">Salvar Alterações</button>
                    </div>
                </form>
            </div>
            
            <!-- Formulário de Alteração de Senha -->
            <div class="form-section">
                <h3>Alterar Senha</h3>
                <form method="POST" action="">
                    <input type="hidden" name="acao" value="alterar_senha">
                    
                    <div class="form-grid">
                        <div class="input-group">
                            <label for="senha_atual">Senha Atual</label>
                            <input type="password" id="senha_atual" name="senha_atual" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="nova_senha">Nova Senha</label>
                            <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
                        </div>
                        
                        <div class="input-group">
                            <label for="confirmar_senha">Confirmar Nova Senha</label>
                            <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-salvar">Alterar Senha</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Modal para Alterar Foto -->
    <div id="modalFoto" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Alterar Foto do Perfil</h3>
                <span class="close" onclick="fecharModalFoto()">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" action="" enctype="multipart/form-data" id="formFoto">
                    <div class="input-group">
                        <label for="foto">Selecionar Nova Foto</label>
                        <input type="file" id="foto" name="foto" accept="image/*" required>
                        <small style="color: #666;">Formatos permitidos: JPG, JPEG, PNG, GIF</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-salvar">Enviar Foto</button>
                        <button type="button" class="btn-cancelar" onclick="fecharModalFoto()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include ('../footer1.php'); ?>
    
    <script src="../script.js"></script>
</body>
</html>