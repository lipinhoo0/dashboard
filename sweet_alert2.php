<?php

@session_start();

// isset verifica se a variavel foi criada
if (isset($_SESSION["tipo"]) && isset($_SESSION["title"]) && isset($_SESSION["msg"])) {
    echo "
    <script>
    $(function() {
        var Toast = Swal.mixin({
          position: 'center',
          showConfirmButton: false,
          timer: 5000
        });
    
          Toast.fire({
            icon: '".$_SESSION["tipo"]."',
            title: '".$_SESSION["title"]."',
            text: '".$_SESSION["msg"]."'
          });
    });
    </script>
    ";

    unset($_SESSION["tipo"]);
    unset($_SESSION["title"]);
    unset($_SESSION["msg"]);
    //após exibir a mensagem, limpa as variaveis da sessão
}
