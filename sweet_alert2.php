<?php

session_start();

// isset verifica se a variavel foi criada
if (isset($_SESSION["tipo"]) && isset($_SESSION["msg"])) {
    echo "
    <script>
    $(function() {
        var Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 5000
        });
    
          Toast.fire({
            icon: '".$_SESSION["tipo"]."',
            title: '".$_SESSION["msg"]."'
          });
    });
    </script>
    ";

    //após exibir a mensagem, limpa as variaveis da sessão
}
