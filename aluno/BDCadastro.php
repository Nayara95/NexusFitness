<?php

 function conectar() { 
	
	//CONEXÃO COM O BANCO DE DADOS 

	$local_server = "DESKTOP-HSJ09OO\SQLEXPRESS1";
	$usuario_server = "sa";
	$senha_server = "loey";
	$banco_de_dados = "BD_NexusFit";

	$dns = "sqlsrv:Server=$local_server;Database=$banco_de_dados";

	try{
		
		$conn = new PDO($dns, $usuario_server, $senha_server ); 
    
    $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexão estabelecida com sucesso usando Autenticação SQL!";
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
	$novocpf = intval($_POST["cpf"]);
	$novogenero = $_POST["genero"];
	
	//error_log("Gênero recebido: " . $novogenero . " (Tamanho: " . strlen($novogenero). ")");

	$novoDataNasc = $_POST["data_nasc"];
	$novaddd = intval($_POST["ddd"]);
	$novacelular = intval($_POST["telefone"]);

$stmt = $conn->prepare ("INSERT INTO ".$tabela.
//paramentros nomeados
	 "(nome, nome_social, email, cpf, genero, data_nasc, dd1, telefone) ". "VALUES (:nome, :nome_social, :email, :cpf, :genero, :data_nasc, :ddd, :telefone); ");
	 //chamando os campos nas suas respectivas ordem 

$stmt ->bindValue (":nome", $novonome);
$stmt ->bindValue (":nome_social", $novosocial);
$stmt ->bindValue (":email", $novoemail);
$stmt ->bindValue (":cpf", $novocpf);
$stmt ->bindValue (":genero", $novogenero);
$stmt ->bindValue (":data_nasc", $novoDataNasc);
$stmt ->bindValue (":ddd", $novaddd);
$stmt ->bindValue (":telefone", $novacelular);

$stmt -> execute();
 header('Location:  InclusaoOK.php');/*chamada e execução dos dados e inclusão no banco */
 exit;
	

}

// Usando PDOException para erros relacionados ao SQL/PDO
catch (PDOException $e){ 
     echo "ATENÇÃO, erro na inclusão de dados: " . $e->getMessage();
}


?>

