<?php
//inicia a sessão para ter acesso as variáveis globais
session_start();

//caminho do software web
define("caminhoURL", "http://localhost/eugenio_gatinho012/dashboard/");

// verifica se o usuario não está conectado
if ($_SESSION["autenticado"] != true) {
    session_destroy();
    header("Location: ./login.php");
    exit;
    // verifica se está conectado
} else {

    // segundos
    $tempo_limite = 30000;
    $tempo_atual = time();

    // verifica tempo inativo 
    if ($tempo_atual - $_SESSION["tempo_login"] > $tempo_limite) {
        $_SESSION["tipo"] = "warning";
        $_SESSION["title"] = "Ops!";
        $_SESSION["msg"] = "Tempo de sessão esgotado!";

        header("Location:".caminhoURL."login.php");
        exit;
    }else{
        $_SESSION["tempo_login"] = time();
    }
}
