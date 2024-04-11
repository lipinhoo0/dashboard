<?php

session_start();
// verifica se o usuario não está conectado
if ($_SESSION["autenticado"] != true) {
    session_destroy();
    header("Location: ../tela_login.php");
    exit;
    // verifica se está conectado
} else {

    // segundos
    $tempo_limite = 10;
    $tempo_atual = time();

    // verifica tempo inativo 
    if ($tempo_atual - $_SESSION["tempo_login"] > $tempo_limite) {
        session_destroy();
        header("Location: ../tela_login.php");
        exit;
    }else{
        $_SESSION["tempo_login"] = time();
    }
}
