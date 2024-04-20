<?php
include ('./verificar_aut.php');
session_start();
session_destroy();
header("Location:" . caminhoURL . "login.php");
exit;