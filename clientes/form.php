<?php
include('../verificar_aut.php');
include('../conexao-pdo.php');

$pagina_ativa = 'clientes';

//verifica se está vindo id na url
if (empty($_GET["ref"])) {
    $pk_cliente = "";
    $nome = "";
    $cpf = "";
    $whatsapp = "";
    $email = "";
} else {
    $pk_cliente = base64_decode((trim($_GET["ref"])));
    $sql = "
    SELECT pk_cliente, nome, cpf, whatsapp, email
    FROM clientes
    WHERE pk_cliente = :pk_cliente
    ";
    //prepara a sintaxe 
    $stmt = $conn->prepare($sql);
    //substitui a string :pk_servico pela variável $pk_servico
    $stmt->bindParam(':pk_cliente', $pk_cliente);
    //executa a sintaxe final do sql 
    $stmt->execute();
    // verifica se encontrou algum registro no banco de dados
    if ($stmt->rowCount() > 0) {

        $dado = $stmt->fetch(PDO::FETCH_OBJ);
        $nome = $dado->nome;
        $cpf = $dado->cpf;
        $whatsapp = $dado->whatsapp;
        $email = $dado->email;
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
    <title>AdminLTE 3 | Dashboard</title>

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
                                            <div class="col-3">
                                                <label for="pk_cliente" class="form-label">Cód</label>
                                                <input readonly type="text" class="form-control" id="pk_cliente" name="pk_cliente" value="<?php echo $pk_cliente ?>">
                                            </div>
                                            <div class="col">
                                                <label for="nome" class="form-label">Nome</label>
                                                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $nome ?>">
                                            </div>
                                        </div> <!-- /row -->
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="cpf" class="form-label">Cpf</label>
                                                <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo $cpf ?>" data-mask="000.000.000-00" required minlength="14">
                                            </div>
                                            <div class="col">
                                                <label for="whatsapp" class="form-label">Whatsapp</label>
                                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo $whatsapp ?>" data-mask="(00) 0 0000-0000" required minlength="16">
                                            </div>
                                            <div class="col">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" required>
                                            </div> 
                                        </div> <!-- /row -->
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
        });
    </script>

</body>

</html>