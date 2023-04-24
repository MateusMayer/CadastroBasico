<?php

$hostname = "localhost";
$bancodedados = "netwall";
$usuario = "root";
$senha = "";

$conexao = new mysqli ($hostname, $usuario, $senha, $bancodedados);
if($conexao->connect_errno) {
    echo "Falha ao conectar: (". $conexao->connect_errno . ") " . $conexao->connect_error;
}
?>