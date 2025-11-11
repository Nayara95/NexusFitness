USE master;
GO

IF EXISTS (SELECT name FROM sys.databases WHERE name = 'BD_Nexus')
BEGIN
    DROP DATABASE BD_Nexus;
END
GO

CREATE DATABASE BD_Nexus;
GO

USE BD_Nexus;
GO

-- REMOVIDO FKs PROBLEMÁTICAS DAS DEFINIÇÕES INICIAIS
-- ORGANIZADO TABELAS POR NECESSIDADE DE CRIAÇÃO INICIAL (BASE)
-- AJUSTADO EM CAMEL CASE
-- CRIADO APENAS ATRIBUTOS BASE E DEPOIS ALTER TABLE PARA REALIZAR FK'S

CREATE TABLE tbl_enderecoAluno(
    id_enderecoAluno int PRIMARY KEY NOT NULL,
    rua varchar(100) NOT NULL,
    numero_endereco numeric(10) NOT NULL,
    bairro varchar(50) NOT NULL,
    cep numeric(8) NOT NULL,
    cidade varchar(50) NOT NULL,
    uf varchar(3) NOT NULL,
    complemento varchar(100)
);
GO

CREATE TABLE tbl_aluno(
    nome varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    cpf numeric(14) NOT NULL,
    genero varchar(100) NOT NULL,
    data_nasc date NOT NULL,
    dd1 numeric(3) NOT NULL, 
    telefone numeric(12) NOT NULL,
    data_cadastro DATETIME DEFAULT NULL,
    senha numeric(18) NULL,
    data_pg datetime NULL,
    data_alteracao datetime NULL,
    id_aluno INT PRIMARY KEY IDENTITY(1,1),
    id_enderecoAluno int
);
GO

CREATE TABLE tbl_plano(
    id_plano int PRIMARY KEY NOT NULL,
    nome_plano varchar(30) NOT NULL,
    valor_plano int NOT NULL,
    data_cadastro datetime NOT NULL,
    observacao varchar(200) NOT NULL,
    id_aluno int,
    id_funcionarios int
);
GO

CREATE TABLE tbl_funcionarios(
    id_funcionarios int PRIMARY KEY NOT NULL,
    foto varbinary (max),
    nome varchar(100) NOT NULL,
    nome_social varchar(100),
    genero varchar(3) NOT NULL,
    email varchar(100) NOT NULL,
    cargo varchar(100) NOT NULL,
    situacao varchar(20) NOT NULL,
    data_registro datetime NOT NULL,
    cpf numeric(11) NOT NULL,
    data_nasc date NOT NULL,
    dd1 numeric(3) NOT NULL, 
    telefone numeric(9) NOT NULL,
    data_cadastro datetime NOT NULL,
    senha numeric(18) NOT NULL,
    data_inicio datetime NOT NULL,
    data_alteracao datetime NOT NULL,
    rua varchar(100) NOT NULL,
    numero_endereco numeric(10) NOT NULL,
    bairro varchar(50) NOT NULL,
    cep numeric(8) NOT NULL,
    cidade varchar(50) NOT NULL,
    uf varchar(3) NOT NULL,
    complemento varchar(100),
    id_professor int,
    id_permissao int
);
GO

CREATE TABLE tbl_agendaTreino(
    id_agendaTreino int PRIMARY KEY NOT NULL,
    segunda varchar(100) NOT NULL,
    terca varchar(100) NOT NULL,
    quarta varchar(100) NOT NULL,
    quinta varchar(100) NOT NULL,
    sexta varchar(100) NOT NULL,
    sabado varchar(100) NOT NULL,
    domingo varchar(100) NOT NULL,
    id_aluno int,
    id_professor int
);
GO

CREATE TABLE tbl_professor(
    id_professor int PRIMARY KEY NOT NULL,
    foto varbinary (max),
    nome varchar(100) NOT NULL,
    nome_social varchar(100),
    genero varchar(3) NOT NULL,
    email varchar(100) NOT NULL,
    registro_cref numeric(6) NOT NULL,
    cpf numeric(11) NOT NULL,
    data_nasc date NOT NULL,
    dd1 numeric(3) NOT NULL, 
    telefone numeric(9) NOT NULL,
    data_cadastro datetime NOT NULL,
    senha numeric(18) NOT NULL,
    data_inicio datetime NOT NULL,
    data_alteracao datetime NOT NULL,
    rua varchar(100) NOT NULL,
    numero_endereco numeric(10) NOT NULL,
    bairro varchar(50) NOT NULL,
    cep numeric(8) NOT NULL,
    cidade varchar(50) NOT NULL,
    uf varchar(3) NOT NULL,
    complemento varchar(100),
    id_agendaTreino int,
    id_funcionarios int
);
GO

CREATE TABLE tbl_pagamento(
    id_pagamento int PRIMARY KEY NOT NULL,
    dataPagamento int NOT NULL,
    dataVencimento int NOT NULL,
    valor int NOT NULL,
    id_aluno int,
    id_plano int
);
GO

CREATE TABLE tbl_dadosSaude(
    id_dadosSaude int PRIMARY KEY NOT NULL,
    questionario xml,
    exame_bio xml,
    atestado_medico xml,
    data_alteracao datetime NOT NULL,
    id_aluno int
);
GO

CREATE TABLE tbl_fisicoAluno(
    id_fisicoAluno int PRIMARY KEY NOT NULL,
    altura numeric(10),
    peso numeric(10),
    braco numeric(10),
    abdomen numeric(10),
    perna numeric(10),
    data_alteracao datetime NOT NULL,
    id_professor int
);
GO

CREATE TABLE tbl_permissao(
    id_permissao int PRIMARY KEY NOT NULL,	
    nome_funcionario varchar(100) NOT NULL,
    tipo_permissao VARCHAR(50) NOT NULL,
    descricao varchar(50) NOT NULL,
    dataPermissao DATETIME NOT NULL,
    id_professor int,
    id_aluno int,
    id_funcionarios int
);
GO

CREATE TABLE tbl_modalidade(
    id_modalidade int PRIMARY KEY NOT NULL,
    nome_modal varchar(100),
    descricao_modal varchar(100),
    data_cadastro datetime,
    modal_situacao varchar(100),
    foto varbinary (max),
    id_funcionarios int
);
GO

-- INSERTS NA ORDEM CORRETA (SEM DEPENDÊNCIAS CIRCULARES)

-- 1. Primeiro: tbl_enderecoAluno (não depende de ninguém)
INSERT INTO tbl_enderecoAluno (id_enderecoAluno, rua, numero_endereco, bairro, cep, cidade, uf, complemento)
VALUES 
(1, 'Rua das Flores', 123, 'Centro', 12345678, 'São Paulo', 'SP', 'Apto 101'),
(2, 'Avenida Brasil', 456, 'Jardins', 87654321, 'Rio de Janeiro', 'RJ', 'Casa 2'),
(3, 'Rua das Palmeiras', 789, 'Vila Madalena', 11223344, 'São Paulo', 'SP', 'Sala 5'),
(4, 'Alameda Santos', 321, 'Bela Vista', 44332211, 'São Paulo', 'SP', 'Loja 10'),
(5, 'Rua do Comércio', 654, 'Centro', 55667788, 'Belo Horizonte', 'MG', 'Bloco B');
GO

-- 2. Segundo: tbl_aluno (depende apenas de tbl_enderecoAluno)
INSERT INTO tbl_aluno (nome, email, cpf, genero, data_nasc, dd1, telefone, data_cadastro, senha, data_pg, data_alteracao, id_enderecoAluno)
VALUES
('João Silva', 'joao.silva@email.com', 12345678901, 'Masculino', '1990-05-15', 11, 999998888, GETDATE(), 123456, GETDATE(), GETDATE(), 1),
('Maria Santos', 'maria.santos@email.com', 23456789012, 'Feminino', '1992-08-20', 21, 988887777, GETDATE(), 234567, GETDATE(), GETDATE(), 2),
('Carlos Oliveira', 'carlos.oliveira@email.com', 34567890123, 'Masculino', '1988-12-10', 31, 977776666, GETDATE(), 345678, GETDATE(), GETDATE(), 3),
('Ana Costa', 'ana.costa@email.com', 45678901234, 'Feminino', '1995-03-25', 11, 966665555, GETDATE(), 456789, GETDATE(), GETDATE(), 4),
('Pedro Rocha', 'pedro.rocha@email.com', 56789012345, 'Outros', '1993-07-08', 31, 955554444, GETDATE(), 567890, GETDATE(), GETDATE(), 5);
GO

-- 3. Terceiro: tbl_funcionarios (não depende de outras tabelas ainda)
INSERT INTO tbl_funcionarios (id_funcionarios, nome, nome_social, genero, email, cargo, situacao, data_registro, cpf, data_nasc, dd1, telefone, data_cadastro, senha, data_inicio, data_alteracao, rua, numero_endereco, bairro, cep, cidade, uf, complemento, id_professor, id_permissao)
VALUES
(1, 'Roberto Alves', NULL, 'M', 'roberto.alves@nexus.com', 'Gerente', 'Ativo', GETDATE(), 11122233344, '1980-01-15', 11, 911112222, GETDATE(), 111111, GETDATE(), GETDATE(), 'Rua dos Gerentes', 100, 'Centro', 11111111, 'São Paulo', 'SP', 'Sala 1', NULL, NULL),
(2, 'Fernanda Lima', NULL, 'F', 'fernanda.lima@nexus.com', 'Recepcionista', 'Ativo', GETDATE(), 22233344455, '1985-06-20', 11, 922223333, GETDATE(), 222222, GETDATE(), GETDATE(), 'Avenida Central', 200, 'Jardins', 22222222, 'São Paulo', 'SP', 'Apto 201', NULL, NULL),
(3, 'Ricardo Moura', NULL, 'M', 'ricardo.moura@nexus.com', 'Administrativo', 'Ativo', GETDATE(), 33344455566, '1978-11-30', 11, 933334444, GETDATE(), 333333, GETDATE(), GETDATE(), 'Rua Secundária', 300, 'Vila Olímpia', 33333333, 'São Paulo', 'SP', 'Casa 3', NULL, NULL);
GO

-- 4. Quarto: tbl_agendaTreino (sem FKs iniciais)
INSERT INTO tbl_agendaTreino (id_agendaTreino, segunda, terca, quarta, quinta, sexta, sabado, domingo, id_aluno, id_professor)
VALUES
(1, 'Peito e Tríceps', 'Costas e Bíceps', 'Pernas', 'Ombros e Abdomen', 'Braços', 'Cardio', 'Descanso', 1, 1),
(2, 'Superior A', 'Inferior A', 'Descanso', 'Superior B', 'Inferior B', 'Cardio', 'Descanso', 2, 2),
(3, 'Push', 'Pull', 'Legs', 'Push', 'Pull', 'Cardio', 'Descanso', 3, 1),
(4, 'Força Superior', 'Força Inferior', 'Hipertrofia Superior', 'Hipertrofia Inferior', 'Cardio', 'Descanso', 'Descanso', 4, 2),
(5, 'ABC Full', 'ABC Full', 'ABC Full', 'ABC Full', 'ABC Full', 'Cardio', 'Descanso', 5, 1);
GO

-- 5. Quinto: tbl_professor (agora pode referenciar agendaTreino e funcionarios)
INSERT INTO tbl_professor (id_professor, nome, nome_social, genero, email, registro_cref, cpf, data_nasc, dd1, telefone, data_cadastro, senha, data_inicio, data_alteracao, rua, numero_endereco, bairro, cep, cidade, uf, complemento, id_agendaTreino, id_funcionarios)
VALUES
(1, 'Marcos Andrade', NULL, 'M', 'marcos.andrade@nexus.com', 123456, 44455566677, '1985-03-10', 11, 944445555, GETDATE(), 444444, GETDATE(), GETDATE(), 'Rua dos Professores', 400, 'Moema', 44444444, 'São Paulo', 'SP', 'Apto 401', 1, 1),
(2, 'Patrícia Santos', NULL, 'F', 'patricia.santos@nexus.com', 654321, 55566677788, '1990-07-22', 11, 955556666, GETDATE(), 555555, GETDATE(), GETDATE(), 'Alameda dos Esportes', 500, 'Itaim Bibi', 55555555, 'São Paulo', 'SP', 'Sala 10', 2, 1);
GO

-- 6. Sexto: tbl_plano (agora pode referenciar aluno e funcionarios)
INSERT INTO tbl_plano (id_plano, nome_plano, valor_plano, data_cadastro, observacao, id_aluno, id_funcionarios)
VALUES
(1, 'Plano Básico', 89, GETDATE(), 'Acesso à academia em horário comercial', 1, 1),
(2, 'Plano Premium', 149, GETDATE(), 'Acesso ilimitado + aulas especiais', 2, 1),
(3, 'Plano Plus', 119, GETDATE(), 'Acesso ampliado + avaliação física', 3, 1),
(4, 'Plano Básico', 89, GETDATE(), 'Acesso à academia em horário comercial', 4, 1),
(5, 'Plano Premium', 149, GETDATE(), 'Acesso ilimitado + aulas especiais', 5, 1);
GO

-- 7. Oitavo: tbl_pagamento (depende de aluno e plano)
INSERT INTO tbl_pagamento (id_pagamento, dataPagamento, dataVencimento, valor, id_aluno, id_plano)
VALUES
(1, 20231005, 20231105, 89, 1, 1),
(2, 20231010, 20231110, 149, 2, 2),
(3, 20231015, 20231115, 119, 3, 3),
(4, 20231020, 20231120, 89, 4, 4),
(5, 20231025, 20231125, 149, 5, 5);
GO

-- 8. Nono: tbl_dadosSaude (depende apenas de aluno)
INSERT INTO tbl_dadosSaude (id_dadosSaude, questionario, exame_bio, atestado_medico, data_alteracao, id_aluno)
VALUES
(1, '<questionario><problema_cardiaco>Não</problema_cardiaco><lesoes>Não</lesoes><medicamentos>Não</medicamentos></questionario>', NULL, NULL, GETDATE(), 1),
(2, '<questionario><problema_cardiaco>Não</problema_cardiaco><lesoes>Sim</lesoes><medicamentos>Não</medicamentos></questionario>', NULL, NULL, GETDATE(), 2),
(3, '<questionario><problema_cardiaco>Não</problema_cardiaco><lesoes>Não</lesoes><medicamentos>Sim</medicamentos></questionario>', NULL, NULL, GETDATE(), 3),
(4, '<questionario><problema_cardiaco>Não</problema_cardiaco><lesoes>Não</lesoes><medicamentos>Não</medicamentos></questionario>', NULL, NULL, GETDATE(), 4),
(5, '<questionario><problema_cardiaco>Sim</problema_cardiaco><lesoes>Não</lesoes><medicamentos>Não</medicamentos></questionario>', NULL, NULL, GETDATE(), 5);
GO

-- 9. Décimo: tbl_fisicoAluno (depende de professor)
INSERT INTO tbl_fisicoAluno (id_fisicoAluno, altura, peso, braco, abdomen, perna, data_alteracao, id_professor)
VALUES
(1, 1.75, 75.5, 35.5, 85.0, 55.5, GETDATE(), 1),
(2, 1.65, 62.0, 28.0, 72.0, 48.0, GETDATE(), 2),
(3, 1.80, 82.0, 38.0, 90.0, 58.0, GETDATE(), 1),
(4, 1.70, 68.5, 30.5, 78.0, 50.5, GETDATE(), 2),
(5, 1.78, 79.0, 36.0, 88.0, 56.0, GETDATE(), 1);
GO

-- 10. Décimo primeiro: tbl_permissao (depende de professor, aluno e funcionarios)
INSERT INTO tbl_permissao (id_permissao, nome_funcionario, tipo_permissao, descricao, dataPermissao, id_professor, id_aluno, id_funcionarios)
VALUES
(1, 'Roberto Alves', 'Administrador', 'Acesso total ao sistema', GETDATE(), NULL, NULL, 1),
(2, 'Fernanda Lima', 'Gerente', 'Acesso gerencial', GETDATE(), NULL, NULL, 2),
(3, 'Ricardo Moura', 'Atendente', 'Acesso para operações básicas', GETDATE(), NULL, NULL, 3);
GO

-- 11. Décimo segundo: tbl_modalidade (depende de funcionarios)
INSERT INTO tbl_modalidade (id_modalidade, nome_modal, descricao_modal, data_cadastro, modal_situacao, id_funcionarios)
VALUES
(1, 'Musculação', 'Treinamento com pesos livres e máquinas', GETDATE(), 'Ativo', 1),
(2, 'Pilates', 'Método de exercícios para fortalecimento', GETDATE(), 'Ativo', 1),
(3, 'Yoga', 'Prática de posturas e respiração', GETDATE(), 'Ativo', 1),
(4, 'Spinning', 'Aula de ciclismo indoor', GETDATE(), 'Inativo', 1),
(5, 'Cross Training', 'Treinamento funcional intenso', GETDATE(), 'Ativo', 1);
GO

-- AGORA ADICIONAR AS FKs APÓS TODOS OS INSERTS
ALTER TABLE tbl_aluno ADD CONSTRAINT FK_Aluno_Endereco 
FOREIGN KEY (id_enderecoAluno) REFERENCES tbl_enderecoAluno(id_enderecoAluno);
GO

ALTER TABLE tbl_plano ADD CONSTRAINT FK_Plano_Aluno 
FOREIGN KEY (id_aluno) REFERENCES tbl_aluno(id_aluno);
GO

ALTER TABLE tbl_plano ADD CONSTRAINT FK_Plano_Funcionarios 
FOREIGN KEY (id_funcionarios) REFERENCES tbl_funcionarios(id_funcionarios);
GO

ALTER TABLE tbl_agendaTreino ADD CONSTRAINT FK_Agenda_Aluno 
FOREIGN KEY (id_aluno) REFERENCES tbl_aluno(id_aluno);
GO

ALTER TABLE tbl_agendaTreino ADD CONSTRAINT FK_Agenda_Professor 
FOREIGN KEY (id_professor) REFERENCES tbl_professor(id_professor);
GO

ALTER TABLE tbl_professor ADD CONSTRAINT FK_Professor_Agenda 
FOREIGN KEY (id_agendaTreino) REFERENCES tbl_agendaTreino(id_agendaTreino);
GO

ALTER TABLE tbl_professor ADD CONSTRAINT FK_Professor_Funcionarios 
FOREIGN KEY (id_funcionarios) REFERENCES tbl_funcionarios(id_funcionarios);
GO

ALTER TABLE tbl_pagamento ADD CONSTRAINT FK_Pagamento_Aluno 
FOREIGN KEY (id_aluno) REFERENCES tbl_aluno(id_aluno);
GO

ALTER TABLE tbl_pagamento ADD CONSTRAINT FK_Pagamento_Plano 
FOREIGN KEY (id_plano) REFERENCES tbl_plano(id_plano);
GO

ALTER TABLE tbl_dadosSaude ADD CONSTRAINT FK_DadosSaude_Aluno 
FOREIGN KEY (id_aluno) REFERENCES tbl_aluno(id_aluno);
GO

ALTER TABLE tbl_fisicoAluno ADD CONSTRAINT FK_FisicoAluno_Professor 
FOREIGN KEY (id_professor) REFERENCES tbl_professor(id_professor);
GO

ALTER TABLE tbl_permissao ADD CONSTRAINT FK_Permissao_Professor 
FOREIGN KEY (id_professor) REFERENCES tbl_professor(id_professor);
GO

ALTER TABLE tbl_permissao ADD CONSTRAINT FK_Permissao_Aluno 
FOREIGN KEY (id_aluno) REFERENCES tbl_aluno(id_aluno);
GO

ALTER TABLE tbl_permissao ADD CONSTRAINT FK_Permissao_Funcionarios 
FOREIGN KEY (id_funcionarios) REFERENCES tbl_funcionarios(id_funcionarios);
GO

ALTER TABLE tbl_modalidade ADD CONSTRAINT FK_Modalidade_Funcionarios 
FOREIGN KEY (id_funcionarios) REFERENCES tbl_funcionarios(id_funcionarios);
GO

-- SELECTS PARA VISUALIZAR OS DADOS
SELECT '=== ALUNOS CADASTRADOS ===' AS Info;
SELECT * FROM tbl_aluno;
GO

SELECT '=== PROFESSORES CADASTRADOS ===' AS Info;
SELECT * FROM tbl_professor;
GO

SELECT '=== FUNCIONÁRIOS CADASTRADOS ===' AS Info;
SELECT id_funcionarios, nome, cargo, situacao FROM tbl_funcionarios;
GO

SELECT '=== PLANOS CONTRATADOS ===' AS Info;
SELECT p.id_plano, p.nome_plano, p.valor_plano, a.nome as aluno 
FROM tbl_plano p 
INNER JOIN tbl_aluno a ON p.id_aluno = a.id_aluno;
GO

SELECT '=== AGENDAS DE TREINO ===' AS Info;
SELECT a.id_agendaTreino, al.nome as aluno, p.nome as professor, a.segunda, a.terca
FROM tbl_agendaTreino a 
INNER JOIN tbl_aluno al ON a.id_aluno = al.id_aluno 
INNER JOIN tbl_professor p ON a.id_professor = p.id_professor;
GO