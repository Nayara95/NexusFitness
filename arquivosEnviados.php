?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["bio"])) {
    $nome_arquivo = $_FILES["bio"]["name"];
    $tipo_arquivo = $_FILES["bio"]["type"];
    $erro_arquivo = $_FILES["bio"]["error"];
    // 

    // diretorio 
    $destino = "uploads/";

   // Verifica se não houve erro 
    if ($erro_arquivo == 0) {

        if (file_exists(__DIR__ . "/" . $destino . $nome_arquivo)) {   // verifia se o arquivo ja foi enviado
		  
		   echo "<strong>ATENÇÃO: Arquivo já enviado. Para novo envio verifique com seu professor </strong><br />";
		
        }
        // Move o arquivo temporário para o diretório de destino com o nome original
			if (move_uploaded_file($temp_arquivo, $destino . $nome_arquivo)) {
				echo "Arquivo enviado com sucesso: " . $destino . $nome_arquivo;
				$arquivo = "http://localhost/PWIII_upload_arquivos/".$destino . $nome_arquivo;
				
			} else {
				echo "Erro ao enviar o arquivo.";
			}
		}
    } else {
        echo "Erro no upload do arquivo.";
    }






    

 ?>