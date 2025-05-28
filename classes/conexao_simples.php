<?php

$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "aula";
// Criando a conexão
$conexao = mysqli_connect($servidor, $usuario, $senha, $banco);
// Verificando a conexão
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Definindo o charset para UTF-8
mysqli_set_charset($conexao, "utf8");
