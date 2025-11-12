<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] !== 'professor') {
    header('Location: ../login.php');
    exit;
}

require_once '../autenticacao/conexao.php';
$conn = conectar();

// Busca os dados do professor no banco de dados
$stmt = $conn->prepare("SELECT * FROM tbl_professor WHERE id_professor = :id");
$stmt->bindParam(':id', $_SESSION['id_professor']);
$stmt->execute();
$professor_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o professor, redireciona para o login
if (!$professor_info) {
    header('Location: ../login.php?error=not_found');
    exit;
}

// Processar upload de foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $uploadDir = '../uploads/professores/';
    
    // Criar diretório se não existir
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = 'professor_' . $professor_info['id_professor'] . '_' . time() . '_' . basename($_FILES['foto']['name']);
    $uploadFile = $uploadDir . $fileName;
    
    // Verificar se é uma imagem
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            // Ler o conteúdo do arquivo para salvar como varbinary
            $fotoData = file_get_contents($uploadFile);

            // Atualizar o blob da foto no banco de dados
            $stmt_update = $conn->prepare("UPDATE tbl_professor SET foto = :foto WHERE id_professor = :id");
            // Especificar o tipo de encoding para dados binários para o driver SQLSRV
            $stmt_update->bindParam(':foto', $fotoData, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
            $stmt_update->bindParam(':id', $professor_info['id_professor'], PDO::PARAM_INT);
            
            if ($stmt_update->execute()) {
                // Não precisamos mais do arquivo temporário, o blob está no banco
                unlink($uploadFile); 
                $mensagem = "Foto atualizada com sucesso!";
                // Recarregar a página para que a nova imagem seja exibida corretamente
                header("Location: perfil-professor.php");
                exit;
            } else {
                // Se a atualização do banco falhar, remove o arquivo que foi upado
                unlink($uploadFile);
                $erro = "Erro ao atualizar a foto no banco de dados.";
            }
        } else {
            $erro = "Erro ao fazer upload da foto.";
        }
    } else {
        $erro = "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
    }
}

// Processar atualização de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
    $nome = $_POST['nome'] ?? $professor_info['nome'];
    $nome_social = $_POST['nome_social'] ?? null;
    $genero = $_POST['genero'] ?? $professor_info['genero'];
    $telefone = $_POST['telefone'] ?? $professor_info['telefone'];
    $registro_cref = $_POST['registro_cref'] ?? $professor_info['registro_cref'];
    $data_nasc = $_POST['data_nasc'] ?? $professor_info['data_nasc'];
    $dd1 = $_POST['dd1'] ?? $professor_info['dd1'];
    $rua = $_POST['rua'] ?? $professor_info['rua'];
    $numero_endereco = $_POST['numero_endereco'] ?? $professor_info['numero_endereco'];
    $bairro = $_POST['bairro'] ?? $professor_info['bairro'];
    $cep = $_POST['cep'] ?? $professor_info['cep'];
    $cidade = $_POST['cidade'] ?? $professor_info['cidade'];
    $uf = $_POST['uf'] ?? $professor_info['uf'];
    $complemento = $_POST['complemento'] ?? null;

    try {
        $stmt_update = $conn->prepare("UPDATE tbl_professor SET 
            nome = :nome,
            nome_social = :nome_social,
            genero = :genero,
            telefone = :telefone,
            registro_cref = :registro_cref,
            data_nasc = :data_nasc,
            dd1 = :dd1,
            rua = :rua,
            numero_endereco = :numero_endereco,
            bairro = :bairro,
            cep = :cep,
            cidade = :cidade,
            uf = :uf,
            complemento = :complemento,
            data_alteracao = GETDATE()
            WHERE id_professor = :id_professor");

        $stmt_update->bindParam(':nome', $nome);
        $stmt_update->bindParam(':nome_social', $nome_social);
        $stmt_update->bindParam(':genero', $genero);
        $stmt_update->bindParam(':telefone', $telefone);
        $stmt_update->bindParam(':registro_cref', $registro_cref);
        $stmt_update->bindParam(':data_nasc', $data_nasc);
        $stmt_update->bindParam(':dd1', $dd1);
        $stmt_update->bindParam(':rua', $rua);
        $stmt_update->bindParam(':numero_endereco', $numero_endereco);
        $stmt_update->bindParam(':bairro', $bairro);
        $stmt_update->bindParam(':cep', $cep);
        $stmt_update->bindParam(':cidade', $cidade);
        $stmt_update->bindParam(':uf', $uf);
        $stmt_update->bindParam(':complemento', $complemento);
        $stmt_update->bindParam(':id_professor', $_SESSION['id_professor'], PDO::PARAM_INT);

        if ($stmt_update->execute()) {
            $mensagem = "Dados atualizados com sucesso!";
            // Recarregar as informações do professor após a atualização
            $stmt = $conn->prepare("SELECT * FROM tbl_professor WHERE id_professor = :id");
            $stmt->bindParam(':id', $_SESSION['id_professor']);
            $stmt->execute();
            $professor_info = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $erro = "Erro ao atualizar os dados: " . implode(" ", $stmt_update->errorInfo());
        }
    } catch (PDOException $e) {
        $erro = "Erro de banco de dados: " . $e->getMessage();
    }
}

// Processar alteração de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'alterar_senha') {
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    // Re-fetch professor info to get the current plain text password
    $stmt_current_pass = $conn->prepare("SELECT senha FROM tbl_professor WHERE id_professor = :id");
    $stmt_current_pass->bindParam(':id', $_SESSION['id_professor']);
    $stmt_current_pass->execute();
    $current_password_from_db = $stmt_current_pass->fetchColumn();

    if ($senha_atual !== $current_password_from_db) {
        $erro = "A senha atual está incorreta.";
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro = "As novas senhas não coincidem.";
    } elseif (strlen($nova_senha) < 6) {
        $erro = "A nova senha deve ter no mínimo 6 caracteres.";
    } else {
        try {
            $stmt_update_pass = $conn->prepare("UPDATE tbl_professor SET senha = :senha, data_alteracao = GETDATE() WHERE id_professor = :id");
            $stmt_update_pass->bindParam(':senha', $nova_senha);
            $stmt_update_pass->bindParam(':id', $_SESSION['id_professor'], PDO::PARAM_INT);
            
            if ($stmt_update_pass->execute()) {
                $mensagem = "Senha alterada com sucesso!";
            } else {
                $erro = "Erro ao alterar a senha: " . implode(" ", $stmt_update_pass->errorInfo());
            }
        } catch (PDOException $e) {
            $erro = "Erro de banco de dados ao alterar a senha: " . $e->getMessage();
        }
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
                    <?php
                        // Define a fonte da imagem. Se o campo 'foto' não estiver vazio, usa o script.
                        // Adiocinado um timestamp para evitar problemas de cache do navegador após o upload.
                        $fotoSrc = !empty($professor_info['foto']) ? 'get_professor_image.php?t=' . time() : '../imagens/semfoto.png';
                    ?>
                    <img src="<?php echo $fotoSrc; ?>" alt="Foto do Professor" id="fotoPreview"></br>
                    <button class="btn-alterar-foto" onclick="abrirModalFoto()">Alterar Foto</button>
                </div>
                
                <div class="info-basica">
                    <h3><?php echo exibirValor($professor_info, 'nome'); ?></h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Nome Social:</label>
                            <span><?php echo exibirValor($professor_info, 'nome_social', 'Não informado'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Gênero:</label>
                            <span><?php echo exibirValor($professor_info, 'genero'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Email:</label>
                            <span><?php echo exibirValor($professor_info, 'email'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>CPF:</label>
                            <span><?php echo exibirValor($professor_info, 'cpf'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Data Nasc.:</label>
                            <span><?php echo exibirValor($professor_info, 'data_nasc'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Telefone:</label>
                            <span>(<?php echo exibirValor($professor_info, 'dd1'); ?>) <?php echo exibirValor($professor_info, 'telefone', 'Não informado'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>CREF:</label>
                            <span><?php echo exibirValor($professor_info, 'registro_cref'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Rua:</label>
                            <span><?php echo exibirValor($professor_info, 'rua'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Número:</label>
                            <span><?php echo exibirValor($professor_info, 'numero_endereco'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Bairro:</label>
                            <span><?php echo exibirValor($professor_info, 'bairro'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>CEP:</label>
                            <span><?php echo exibirValor($professor_info, 'cep'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Cidade:</label>
                            <span><?php echo exibirValor($professor_info, 'cidade'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>UF:</label>
                            <span><?php echo exibirValor($professor_info, 'uf'); ?></span>
                        </div>
                        <div class="info-item full-width">
                            <label>Complemento:</label>
                            <span><?php echo exibirValor($professor_info, 'complemento', 'Não informado'); ?></span>
                        </div>
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
                            <label for="nome_social">Nome Social</label>
                            <input type="text" id="nome_social" name="nome_social" value="<?php echo exibirValor($professor_info, 'nome_social'); ?>">
                        </div>

                        <div class="input-group">
                            <label for="genero">Gênero</label>
                            <select id="genero" name="genero" required>
                                <option value="M" <?php echo (exibirValor($professor_info, 'genero') == 'M') ? 'selected' : ''; ?>>Masculino</option>
                                <option value="F" <?php echo (exibirValor($professor_info, 'genero') == 'F') ? 'selected' : ''; ?>>Feminino</option>
                                <option value="O" <?php echo (exibirValor($professor_info, 'genero') == 'O') ? 'selected' : ''; ?>>Outro</option>
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo exibirValor($professor_info, 'email'); ?>" required readonly>
                            <small style="color: #666;">Email não pode ser alterado</small>
                        </div>

                        <div class="input-group">
                            <label for="cpf">CPF</label>
                            <input type="text" id="cpf" name="cpf" value="<?php echo exibirValor($professor_info, 'cpf'); ?>" required readonly>
                            <small style="color: #666;">CPF não pode ser alterado</small>
                        </div>

                        <div class="input-group">
                            <label for="data_nasc">Data de Nascimento</label>
                            <input type="date" id="data_nasc" name="data_nasc" value="<?php echo exibirValor($professor_info, 'data_nasc'); ?>" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="dd1">DDD</label>
                            <input type="text" id="dd1" name="dd1" value="<?php echo exibirValor($professor_info, 'dd1'); ?>" required maxlength="3">
                        </div>

                        <div class="input-group">
                            <label for="telefone">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" value="<?php echo exibirValor($professor_info, 'telefone'); ?>" required maxlength="9">
                        </div>
                        
                        <div class="input-group">
                            <label for="registro_cref">CREF</label>
                            <input type="text" id="registro_cref" name="registro_cref" value="<?php echo exibirValor($professor_info, 'registro_cref'); ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="rua">Rua</label>
                            <input type="text" id="rua" name="rua" value="<?php echo exibirValor($professor_info, 'rua'); ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="numero_endereco">Número</label>
                            <input type="text" id="numero_endereco" name="numero_endereco" value="<?php echo exibirValor($professor_info, 'numero_endereco'); ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="bairro">Bairro</label>
                            <input type="text" id="bairro" name="bairro" value="<?php echo exibirValor($professor_info, 'bairro'); ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="cep">CEP</label>
                            <input type="text" id="cep" name="cep" value="<?php echo exibirValor($professor_info, 'cep'); ?>" required maxlength="8">
                        </div>

                        <div class="input-group">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade" value="<?php echo exibirValor($professor_info, 'cidade'); ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="uf">UF</label>
                            <input type="text" id="uf" name="uf" value="<?php echo exibirValor($professor_info, 'uf'); ?>" required maxlength="2">
                        </div>
                        
                        <div class="input-group full-width">
                            <label for="complemento">Complemento</label>
                            <textarea id="complemento" name="complemento"><?php echo exibirValor($professor_info, 'complemento'); ?></textarea>
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

     <footer>
      
      <a>© 2025 Nexus Fitness — Todos os direitos reservados.</a>
     
    </footer>
    
    <script src="../script.js"></script>
</body>
</html>