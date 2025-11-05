
--aqui está a estrutura definida
create database BD_Nexus;

use BD_Nexus;

create table tbl_aluno(

--foto image,
nome varchar(100)not null,
email varchar(100) not null,
cpf numeric(14) not null,
genero varchar(100) not null, --M masculino,F feminino,� quer informar
data_nasc date not null,
dd1 numeric(3) not null, 
telefone numeric(12) not null,


--CHAVE ESTRANGEIRA
--id_pagamento int FOREIGN KEY references tbl_pagamento (id_pagamento),
--id_dadosSaude int FOREIGN KEY references tbl_dadosSaude(id_dadosSaude),
--id_Agendatreino int FOREIGN KEY references tbl_agendaTreino (id_Agendatreino)

);

SELECT * FROM tbl_aluno;

ALTER TABLE tbl_aluno --criando novos campos
ADD
data_cadastro DATETIME DEFAULT null,
senha numeric(18)null,
data_pg datetime null,
data_alteracao datetime null,
id_aluno INT PRIMARY KEY IDENTITY(1,1), --criando codigo sequencial 
id_enderecoAluno int FOREIGN KEY references tbl_enderecoAluno (id_enderecoAluno);



SELECT * FROM tbl_aluno;

create table tbl_pagamento(
id_pagamento int PRIMARY KEY not null,
DataPagamento int not null,
DataVencimento int not null,
Valor int not null,

--CHAVE ESTRANGEIRA
id_aluno int FOREIGN KEY references tbl_aluno(id_aluno),
id_plano int FOREIGN KEY references tbl_plano(id_plano)

);

create table tbl_professor(
id_professor int PRIMARY KEY not null,

foto image,
nome varchar(100)not null,
nome_social varchar(100),
genero varchar(3) not null, --M masculino,F feminino,ñ quer informar
email varchar(100) not null,

regidtro_cref numeric(6)not null,
cpf numeric(11) not null,
data_nasc date not null,
dd1 numeric(3) not null, 
telefone numeric(9) not null,
data_caastro datetime not null,
senha numeric(18)not null,
data_inicio datetime not null,
data_alteracao datetime not null,

rua varchar(100) not null,
numero_endereco numeric(10) not null,
bairro varchar(50) not null,
cep numeric(8) not null,
cidade varchar(50) not null,
uf varchar(3) not null,
complemento varchar(100),

--CHAVE ESTRANGEIRA
id_agendatreino int FOREIGN KEY references tbl_agendaTreino (id_agendatreino),
id_funcionarios int FOREIGN KEY references tbl_funcionarios (id_funcionarios)

);

create table tbl_plano(
id_plano int PRIMARY KEY not null,

nome_plano varchar(30) not null,
valor_plano int not null,
data_cadastro datetime not null,
observacao varchar(200) not null,

--CHAVE ESTRANGEIRA
id_aluno int FOREIGN KEY references tbl_aluno(id_aluno),
id_funcionarios int FOREIGN KEY references tbl_funcionarios(id_funcionarios)
);

create table tbl_agendaTreino(
id_Agendatreino int PRIMARY KEY not null,

segunda varchar(100) not null,
terça varchar(100) not null,
quarta varchar(100) not null,
quinta varchar(100) not null,
sexta varchar(100) not null,
sabado varchar(100) not null,
domingo varchar(100) not null,

--CHAVE ESTRANGEIRA
id_aluno int FOREIGN KEY references tbl_aluno(id_aluno),
id_professor int FOREIGN KEY references tbl_professor(id_professor)

);

create table tbl_dadosSaude(
id_dadosSaude int PRIMARY KEY not null,

questionario xml,
exame_bio xml,
atestado_medico xml,
data_alteracao datetime not null,

--CHAVE ESTRANGEIRA
id_aluno int FOREIGN KEY references tbl_aluno(id_aluno),


)

create table tbl_fisicoAluno(
id_fisicoAluno int PRIMARY KEY not null,

altura numeric(10),
peso numeric(10),
braco numeric(10),
abdomen numeric(10),
perna numeric(10),
data_alteracao datetime not null,

--CHAVE ESTRANGEIRA
id_professor int FOREIGN KEY references tbl_professor(id_professor)

);


create table tbl_enderecoAluno(
id_enderecoAluno int PRIMARY KEY not null,

rua varchar(100) not null,
numero_endereco numeric(10) not null,
bairro varchar(50) not null,
cep numeric(8) not null,
cidade varchar(50) not null,
uf varchar(3) not null,
complemento varchar(100),

--CHAVE ESTRANGEIRA
id_aluno int FOREIGN KEY references tbl_aluno(id_aluno)

);

create table tbl_funcionarios(
id_funcionarios int PRIMARY KEY not null,

foto image,
nome varchar(100)not null,
nome_social varchar(100),
genero varchar(3) not null, --M masculino,F feminino,ñ quer informar
email varchar(100) not null,
cargo  varchar(100) not null,
situacao varchar(20) not null,


data_registro datetime not null,
cpf numeric(11) not null,
data_nasc date not null,
dd1 numeric(3) not null, 
telefone numeric(9) not null,
data_caastro datetime not null,
senha numeric(18)not null,
data_inicio datetime not null,
data_alteracao datetime not null,

rua varchar(100) not null,
numero_endereco numeric(10) not null,
bairro varchar(50) not null,
cep numeric(8) not null,
cidade varchar(50) not null,
uf varchar(3) not null,
complemento varchar(100),

--CHAVE ESTRANGEIRA
id_professor int FOREIGN KEY references tbl_professor(id_professor)
id_permissao int FOREIGN KEY references tbl_permissao(id_permissao)


);

create table tbl_permissao(
id_permissao int PRIMARY KEY not null,	
nome_funcionario varchar(100) NOT NULL,
tipo_permissao VARCHAR(50) NOT NULL, -- Alterar, incluir/alterar, visualizar...
descrisao varchar(50) not null,
DataPermissao DATETIME not null,

--CHAVE ESTRANGEIRA
id_professor int FOREIGN KEY references tbl_professor(id_professor),
id_aluno int FOREIGN KEY references tbl_aluno(id_aluno)
id_funcionarios int FOREIGN KEY references id_funcionarios

);

create table tbl_modalidade(
id_modalidade int PRIMARY KEY not null,

nome_modal varchar(100),
descricao_modal varchar(100),
data_cadastro datetime,
modal_situação varchar(100),
foto image

--CHAVE ESTRANGEIRA
id_funcionarios int FOREIGN KEY references tbl_funcionarios(id_funcionarios)


);