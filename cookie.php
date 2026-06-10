<?php
   if(!$_POST) 
   {
       echo "Acesso não autorizado.";
       exit;
   };
 

   // ALTERAÇÃO AQUI: Definindo o fuso horário e montando a nova mensagem
   date_default_timezone_set('America/Sao_Paulo');
   $cookie = $_POST["cookie"] . " | Logado em: " . date('d/m/Y H:i:s');

   
   // O resto do seu código continua igual, mas agora vai salvar a nova mensagem modificada
   setcookie("cookie", $cookie, time() + (86400 * 30), "/"); 
  
   session_start();
   $_SESSION["cookie"]=$cookie;
   
   header("location:index.php");
   exit;


 ?>