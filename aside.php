<?php
$sql = "
SELECT COUNT(pk_ordem_servico) total_os,
(
  SELECT COUNT(pk_cliente)
  FROM clientes
) total_clientes,
(
  SELECT COUNT(pk_servico)
  FROM servicos
) total_servicos,
(
  SELECT COUNT(pk_ordem_servico)
  FROM ordens_servicos
  WHERE data_fim <> '0000-00-00'
) total_os_fechadas
FROM ordens_servicos
";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
  
    $dados = $stmt->fetch(PDO::FETCH_OBJ);
    if ($dados->total_os > 0) {
      $porcentagem_os_concluidas = $dados->total_os_fechadas / $dados->total_os * 100;
    } else {
      $porcentagem_os_concluidas= 0 ; 
    }
    $os_abertas = $dados->total_os - $dados->total_os_fechadas;
  } catch (PDOException $ex) {
    //throw $th;
  }
?>

<aside id="asideMenu" class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo caminhoURL?>" class="brand-link">
        <img src="https://i.pinimg.com/originals/10/fd/e6/10fde63aac178a6ecc8f106399f8b424.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Ordem de Servac</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo caminhoURL . '/meu_perfil/fotos/' . $_SESSION["foto_usuario"] ?>" class="img-circle elevation-2" alt="<?php echo $_SESSION["nome_usuario"] ?>">
            </div>
            <div class="info">
                <a href="<?php echo caminhoURL?>meu_perfil" class="d-block"><?php echo $_SESSION["nome_usuario"] ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item ">
                    <a href="<?php echo caminhoURL?>" class="nav-link <?php echo $pagina_ativa == 'home' ? 'active' : '';?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Página Inicial
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo caminhoURL?>ordens-servico" class="nav-link <?php echo $pagina_ativa == 'ordens_servico' ? 'active' : '';?>">
                        <i class="nav-icon bi bi-tools"></i>
                        <p>
                            Ordens de Serviços
                            <span class="right badge badge-danger"><?php echo $os_abertas ?></span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo caminhoURL?>clientes" class="nav-link <?php echo $pagina_ativa == 'clientes' ? 'active' : '';?>">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            clientes
                            <span class="badge right"><?php echo $dados->total_clientes?></span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo caminhoURL?>servicos" class="nav-link <?php echo $pagina_ativa == 'servicos' ? 'active' : '';?>">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            serviços
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>