<?php
include('../verificar_aut.php');
include('../conexao-pdo.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Servac - Clientes</title>

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
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../dist/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../dist/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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
              <div class="mt-3 card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Lista de Serviços</h3>
                  <a href="./form.php" class="btn btn-sm btn-primary float-right">
                    <i class="bi bi-plus"></i>
                  </a>

                </div>
                <div class="card-body">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>CÓD</th>
                        <th>CLIENTES</th>
                        <th>CPF</th>
                        <th>CONTATO (tel)</th>
                        <th>OPÇÕES</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = "
                      SELECT pk_cliente, nome, cpf, whatsapp
                      FROM clientes
                      ORDER BY pk_cliente
                      ";

                      $stmt = $conn->prepare($sql);
                      $stmt->execute();
                      $dados = $stmt->fetchAll(PDO::FETCH_OBJ);

                      
                      foreach ($dados as $row) {
                        echo '
                        <tr>
                        <td class ="text-center">' . $row->pk_cliente . '</td>
                        <td>' . $row->nome . '</td>
                        <td>'.$row->cpf .'</td>
                        <td>'.$row->whatsapp.'</td>
                        <td class= "text-center">
                            <div class=" text-center btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group me-2" role="group" aria-label="First group">
                                    <a href="form.php?ref=' . base64_encode($row->pk_cliente) . '" class="btn btn-sm btn-outline-secondary bi bi-brush "></a>
                                    <a href="remover.php?ref=' . base64_encode($row->pk_cliente) . '" class="btn btn-sm btn-outline-secondary bi bi-trash"></a>
                                </div>
                            </div>
                        </td>
                        </tr>
                        ';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
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
  <!-- SWeetAlert2 -->
  <script src="../dist/plugins/sweetalert2/sweetalert2.min.js"></script>
  <?php
  include("../sweet_alert2.php");
  ?>
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