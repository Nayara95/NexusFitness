<?php

function conectar() {
    $local_server = "DANNYPC";
    $usuario_server = "sa";
    $senha_server = "etesp";
    $banco_de_dados = "BD_Nexus"; // As in BDCompleta.sql

    $dns = "sqlsrv:Server=$local_server;Database=$banco_de_dados";

    try {
        $conn = new PDO($dns, $usuario_server, $senha_server);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("ERRO NA CONEXÃO: " . $e->getMessage());
    }
}
