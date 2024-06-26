<?php
include('../verificar_aut.php');
include('../conexao-pdo.php');

$pagina_ativa = 'ordens_servico';

$sql = "
    SELECT pk_servico, servico 
    FROM servicos
    ORDER BY servico
";

try {

    //prepara a sintaxe na conexão
    $stmt = $conn->prepare($sql);
    //executa o comando
    $stmt->execute();
    //recebe as infomações vindas do mysql
    $dados = $stmt->fetchAll(PDO::FETCH_OBJ);
    //recebe as informações para printar informações

    $options = '<option value =""> Selecione </option>';
    foreach ($dados as $row) {
        $options .= '<option value=" ' . $row->pk_servico . '">' . $row->servico . '</option>';
    }
} catch (Exception $ex) {
    $_SESSION["tipo"] = "error";
    $_SESSION["title"] = "Ops!";
    $_SESSION["msg"] = $ex->getMessage();

    header('Location: ./');
}




//verifica se está vindo id na url
if (empty($_GET["ref"])) {
    $pk_ordem_servico = "";
    $cpf = "";
    $nome = "";
    $data_ordem_servico = "";
    $data_inicio = "";
    $data_fim = "";
} else {
    $pk_ordem_servico = base64_decode((trim($_GET["ref"])));
    $sql = "
    SELECT pk_ordem_servico, data_ordem_servico, data_inicio, data_fim,
    cpf, nome
    FROM ordens_servicos
    JOIN clientes ON pk_cliente = fk_cliente
    WHERE pk_ordem_servico = :pk_ordem_servico
    ";
    //prepara a sintaxe 
    $stmt = $conn->prepare($sql);
    //substitui a string :pk_servico pela variável $pk_servico
    $stmt->bindParam(':pk_ordem_servico', $pk_ordem_servico);
    //executa a sintaxe final do sql 
    $stmt->execute();
    // verifica se encontrou algum registro no banco de dados
    if ($stmt->rowCount() > 0) {

        $dado = $stmt->fetch(PDO::FETCH_OBJ);
        $data_ordem_servico = $dado->data_ordem_servico;
        $data_inicio = $dado->data_inicio;
        $data_fim = $dado->data_fim;
        $cpf = $dado->cpf;
        $nome = $dado->nome;
    } else {
        $_SESSION["tipo"] = 'error';
        $_SESSION["title"] = 'Ops!';
        $_SESSION["msg"] = 'Registro não encontrado.';
        header(("Location ./"));
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servac | Formulário</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../dist/plugins/fontawesome-free/css/all.min.css">
    <!-- bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../dist/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../dist/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="dist/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../dist/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <?php include('../nav.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include('../aside.php') ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Main row -->
                    <div class="row">
                        <div class="col">
                            <!-- BAR CHART -->
                            <form action="salvar.php" method="post">
                                <div class="mt-3 card card-primary card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">Lista de Serviços</h3>

                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-2">
                                                <label for="pk_ordem_servico" class="form-label">Cód</label>
                                                <input readonly type="text" class="form-control" id="pk_ordem_servico" name="pk_ordem_servico" value="<?php echo $pk_ordem_servico ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="cpf" class="form-label">Cpf</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo $cpf ?>" data-mask="000.000.000-00" required minlength="14">
                                                    <span class="input-group-append">
                                                        <button type="button" id="btn-search" class="btn btn-default-btn-flat">
                                                            <i class="bi bi-search"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label for="nome" class="form-label">Nome</label>
                                                <input readonly type="text" class="form-control" id="nome" name="nome" value="<?php echo $nome ?>">
                                            </div>
                                        </div> <!-- /row -->
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="data_ordem_servico" class="form-label">Data O.S</label>
                                                <input readonly type="date" class="form-control" id="data_ordem_servico" name="data_ordem_servico" value="<?php echo $data_ordem_servico ?>">
                                            </div>
                                            <div class="col">
                                                <label for="data_inicio" class="form-label">Data Inicio</label>
                                                <input type="date" class="form-control" id="data_incio" name="data_inicio" value="<?php echo $data_inicio ?>">
                                            </div>
                                            <div class="col">
                                                <label for="data_fim" class="form-label">Data Fim</label>
                                                <input type="date" class="form-control" id="data_fim" name="data_fim" value="<?php echo $data_fim ?>">
                                            </div>
                                        </div> <!-- /row -->
                                        <div class="row">
                                            <div class="col">
                                                <div class="mt-3 card card-secondary card-outline">
                                                    <div class="card-header">
                                                        <h5> Lista de Serviços </h5>
                                                        <button type="button" class="btn btn-sm btn-primary float-right" id="btn-add">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col">
                                                                <table class="table" id="tabela_servicos">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Serviço</th>
                                                                            <th>Valor</th>
                                                                            <th>Opções</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if (empty($pk_ordem_servico)) {
                                                                           echo '
                                                                        <tr>
                                                                            <td>
                                                                                <select name="fk_servico[]" required class="form-select-lg form-control" aria-label="Default select example">
                                                                                    echo '.  $options .'
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="valor[]" id="" class="form-control">
                                                                            </td>
                                                                            <td class="text-center">
                                                                            </td>
                                                                        </tr>
                                                                        ';
                                                                        } else {
                                                                           $sql = "
                                                                           SELECT s.pk_servico, s.servico, rl.valor
                                                                           FROM servicos s
                                                                           JOIN rl_servicos_os rl ON rl.fk_servico = s.pk_servico
                                                                           where rl.fk_ordem_servico = :pk_ordem_servico
                                                                           "; 

                                                                           try {
                                                                            $stmt = $conn->prepare($sql);
                                                                            $stmt->bindParam (':pk_ordem_servico', $pk_ordem_servico);
                                                                            $stmt-> execute();

                                                                            $dados = $stmt->fetchAll(PDO::FETCH_OBJ);

                                                                            foreach ($dados as $key => $row) {
                                                                                echo '
                                                                                <tr>
                                                                                <td>
                                                                                    <select name="fk_servico[]" required class="form-select-lg form-control" aria-label="Default select example">
                                                                                    <option value=" '.$row->pk_servico.'"> '.$row->servico.' </option> 
                                                                                    '.  $options .'
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input value = "'.$row->valor.'" type="text" name="valor[]" id="" class="form-control">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                </td>
                                                                            </tr>
    ';
                                                                            }

                                                                           } catch (PDOException $ex) {
                                                                            $_SESSION["tipo"] = "error";
                                                                            $_SESSION["title"] = "Ops!";
                                                                            $_SESSION["msg"] = $ex->getMessage();
                                                                           }
                                                                        }
                                                                        

                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-right">
            <a href="./" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left"></i>
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-floppy"></i>
            </button>
        </div>
    </div>
    </form>

    </div>
    </div>
    <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- footer  -->
    <?php include('../footer.php') ?>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../dist/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../dist/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="../dist/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../dist/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../dist/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- Chart JS  -->
    <script src="../dist/plugins/chart.js/Chart.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.js"></script>
    <!-- Jquery Mask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        $(function() {

            $("#cpf").keyup(function() {
                //limpar input de nome
                $("#nome").val("");
            })

            $("#btn-search").click(function() {
                //limpar input de nome
                $("#nome").val("");
                //faz a requisição para o arquivo "consultar_cpf.php"
                $.getJSON(
                    'consultar_cpf.php', {
                        cpf: $("#cpf").val()
                    },
                    function(data) {
                        if (data['success'] == true) {
                            $("#nome").val(data['dado']['nome']);
                        } else {
                            alert(data['dado']);
                            $('#cpf').val("")
                        }
                    }
                )
            })

            $("#theme-mode").click(function() {
                //pegar atributo class do objeto
                var classMode = $("#theme-mode").attr("class");
                if (classMode == "fas fa-sun") {
                    $("body").removeClass("dark-mode");
                    $("#theme-mode").attr("class", "fas fa-moon");
                    $("#navBar").removeClass("navbar-dark navbar-black")
                    $("#navBar").addClass("navbar-light navbar-white")
                    $("#asideMenu").attr("class", "main-sidebar sidebar-light-primary elevation-4")

                } else {
                    $("body").addClass("dark-mode");
                    $("#theme-mode").attr("class", "fas fa-sun");
                    $("#navBar").removeClass("navbar-light navbar-white")
                    $("#navBar").addClass(" navbar-dark navbar-black")
                    $("#asideMenu").attr("class", "main-sidebar sidebar-dark-primary elevation-4")
                }
            });

            $("#btn-add").click(function() {
                var newRow = $("<tr>");
                var cols = "";
                cols += '<td>';
                cols += '<select class= "form-control" name="fk_servico[]">';
                cols += '<?php echo $options ?>';
                cols += '</select>';
                cols += '</td>';
                cols += '<td><input type="number" class= "form-control" name= "valor[]"></td>'
                cols += '<td>'
                cols += '<button class= "btn btn-danger btn-sm" onclick="RemoveRow(this)" type="button"> <i class="fas fa-trash"></i></button>';
                cols += '</td>'
                newRow.append(cols);
                $("#tabela_servicos").append(newRow);
            });

            RemoveRow = function(item) {
                var tr = $(item).closest('tr');
                tr.fadeOut(400, function() {
                    tr.remove();
                });
                return false;
            }
        });
    </script>

</body>

</html>