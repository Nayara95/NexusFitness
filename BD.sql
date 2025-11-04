--aqui a estrutura está sendo conectada com as seus atributos php
--EM ANDAMENTO

create database BD_NexusFit;

use BD_NexusFit;

create table tbl_aluno(
--id_aluno int PRIMARY KEY not null,

--foto image,
nome varchar(100)not null,
email varchar(100) not null,
cpf numeric(14) not null,
genero varchar(100) not null, --M masculino,F feminino,ñ quer informar
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
id_aluno INT PRIMARY KEY IDENTITY(1,1),
id_enderecoAluno int FOREIGN KEY references tbl_enderecoAluno (id_enderecoAluno);



create table tbl_enderecoAluno(
id_enderecoAluno INT PRIMARY KEY IDENTITY(1,1),

rua varchar(100) not null,
numero_endereco numeric(10) not null,
bairro varchar(50) not null,
cep numeric(10) not null,
cidade varchar(50) not null,
uf varchar(4) not null,
complemento varchar(100),

--CHAVE ESTRANGEIRA
--id_aluno int FOREIGN KEY references tbl_aluno(id_aluno)

);

ALTER TABLE tbl_enderecoAluno --criando novos campos


