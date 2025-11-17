<?php

 function conectar() { 
	
	//CONEXÃO COM O BANCO DE DADOS 

	$local_server = "DESKTOP-HSJ09OO\SQLEXPRESS1";
	$usuario_server = "sa";
	$senha_server = "loey";
	$banco_de_dados = "BD_Nexus";

	$dns = "sqlsrv:Server=$local_server;Database=$banco_de_dados";

	try{
		
		$conn = new PDO($dns, $usuario_server, $senha_server ); 
    
    $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Conexão estabelecida com sucesso usando Autenticação SQL!";
		return $conn;
	}
	catch (PDOException $e) {
		  die("ERRO NA CONEXÃO: " . $e->getMessage());
		  exit; 
	}
};

//variavel de conexão
$conn = conectar (); //executando a chagada do BD
//variavel de chamada da tabela no bd
$tabela = "tbl_aluno";

//conectando dos campos da tabela

try{

	$novonome = $_POST["nome"];
	$novosocial = $_POST["nome_social"];
	$novoemail = $_POST["email"];
	$novocpf = $_POST["cpf"];
	$novogenero = $_POST["genero"];
	
	//error_log("Gênero recebido: " . $novogenero . " (Tamanho: " . strlen($novogenero). ")");

	$novoDataNasc = $_POST["data_nasc"];
	$novaddd = $_POST["dd1"];
	$novacelular = $_POST["telefone"];
	$senha_pura = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT); // Captura a senha

// CRUCIAL: HASH DA SENHA PARA SEGURANÇA
    $senha_db = $senha_pura;

$stmt = $conn->prepare ("INSERT INTO ".$tabela.
//paramentros nomeados
	 "(nome, nome_social, email, cpf, genero, data_nasc, dd1, telefone, senha) ". "VALUES (:nome, :nome_social, :email, :cpf, :genero, :data_nasc, :dd1, :telefone, :senha); ");
	 //chamando os campos nas suas respectivas ordem 

$stmt ->bindValue (":nome", $novonome);
$stmt ->bindValue (":nome_social", $novosocial);
$stmt ->bindValue (":email", $novoemail);
$stmt ->bindValue (":cpf", $novocpf);
$stmt ->bindValue (":genero", $novogenero);
$stmt ->bindValue (":data_nasc", $novoDataNasc);
$stmt ->bindValue (":dd1", $novaddd);
$stmt ->bindValue (":telefone", $novacelular);
$stmt ->bindValue (":senha", $senha_db);

//$stmt -> execute();
// header('Location:  InclusaoOK.php');/*chamada e execução dos dados e inclusão no banco */

 
// 5. RESPOSTA DE SUCESSO (JSON)
    echo json_encode([
        'status' => 'sucesso', 
        'mensagem' => 'Cadastro finalizado com sucesso! Você será redirecionado para a escolha do plano.'
    ]);
    exit;
}

// Usando PDOException para erros relacionados ao SQL/PDO
catch (PDOException $e){ 
    http_response_code(500); // Define código de erro
    echo json_encode([
        'status' => 'erro', 
        'mensagem' => 'ATENÇÃO, erro na inclusão de dados: ' . $e->getMessage()
    ]);
    exit;
}
?>

