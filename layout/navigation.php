<?php require_once dirname(__DIR__) . '\constants.php'; ?>
<!-- Main Navigation -->
<header>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark blue-gradient fixed-top scrolling-navbar">
        <div class="container-fluid smooth-scroll">
            <a class="navbar-brand" href="<?= constant('URL'); ?>"><img src=<?= constant('URL') . 'assets/img/logv.png'; ?> width='110px' alt="logo" /></a>
            <button class="navbar-toggler blue-gradient" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-7" aria-controls="navbarSupportedContent-7" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent-7">
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['seudonimo'])) {
                        if ($_SESSION['id_permiso'] != 3) {
                    ?>

                            <li class="dropdown nav-item">
                                <a class="dropdown-toggle nav-link" href="/" data-toggle="dropdown"><i class="fas fa-plus pr-1"></i> Cargar Datos</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="add/crear_poliza.php"><i class="fas fa-plus-square pr-2 cyan-text"></i> Póliza</a>
                                    <a class="dropdown-item" href="add/crear_poliza.php"><i class="fas fa-plus-square pr-2 cyan-text"></i> Comisión</a>
                                    <a class="dropdown-item" href="add/crear_poliza.php"><i class="fas fa-user-plus pr-2 cyan-text"></i> Asesor</a>
                                    <a class="dropdown-item" href="add/crear_poliza.php"><i class="fas fa-briefcase pr-2 cyan-text"></i> Compañía</a>
                                    <a class="dropdown-item" href="add/crear_poliza.php"><i class="fas fa-user-plus pr-2 cyan-text"></i> Usuario</a>
                                </div>
                            </li>
                        <?php } ?>

                        <li class="dropdown nav-item">
                            <a class="dropdown-toggle nav-link" href="/" data-toggle="dropdown"><i class="fas fa-search"></i> Buscar</a>
                            <div class="dropdown-menu">
                                <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_asesor.php'; ?>"><i class="fas fa-male pr-2 cyan-text"></i> Asesor</a>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_cliente.php'; ?>"><i class="fas fa-male pr-2 cyan-text"></i> Cliente</a>
                                <?php } ?>
                                <a class="dropdown-item" href="<?= constant('URL') . 'view/b_poliza.php'; ?>"><i class="far fa-clipboard pr-2 cyan-text"></i> Póliza</a>
                                <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_comp.php'; ?>"><i class="fas fa-briefcase pr-2 cyan-text"></i> Compañía</a>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_reportes.php'; ?>"><i class="fas fa-clipboard-list pr-2 cyan-text"></i> Reportes de Comision</a>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_reportes_cia.php'; ?>"><i class="fas fa-clipboard-list pr-2 cyan-text"></i> Reportes de Comision por Cía</a>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_usuario.php'; ?>"><i class="fas fa-user-tie pr-2 cyan-text"></i> Usuario</a>
                                <?php } ?>
                            </div>
                        </li>

                        <li class="dropdown nav-item">
                            <a class="dropdown-toggle nav-link" href="/" data-toggle="dropdown"><i class="fas fa-chart-line"></i> Gráficos</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="add/crear_poliza.php"><i class="material-icons cyan-text">pie_chart</i> Porcentajes</a>
                                <a class="dropdown-item" href="add/crear_poliza.php"><i class="material-icons cyan-text">bar_chart</i> Primas Suscritas</a>
                                <a class="dropdown-item" href="add/crear_poliza.php"><i class="material-icons cyan-text">thumb_up</i> Primas Cobradas</a>
                                <a class="dropdown-item" href="add/crear_poliza.php"><i class="material-icons cyan-text">timeline</i> Comisiones Cobradas</a>
                                <a class="dropdown-item" href="add/crear_poliza.php"><i class="material-icons cyan-text">show_chart</i> Gestión de Cobranza</a>
                            </div>
                        </li>

                        <li class="nav-item avatar dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" to="">
                                <?php
                                $file_headers = @get_headers(constant('URL') . 'assets/img/perfil/' . $_SESSION['seudonimo'] . '.jpg');
                                if ($file_headers[0] == 'HTTP/1.1 200 OK') { ?>
                                    <img src="<?= constant('URL') . 'assets/img/perfil/' . $_SESSION['seudonimo'] . '.jpg'; ?>" class="rounded-circle z-depth-0" height="35" alt="avatar" />
                                <?php } else { ?>
                                    <img src="<?= constant('URL') . 'assets/img/perfil/user.png'; ?>" class="rounded-circle z-depth-0" />
                                <?php } ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg-right dropdown-primary" aria-labelledby="navbarDropdownMenuLink-55">
                                <a class="dropdown-item cyan-text" href="#"><i class="fas fa-user-cog pr-2"></i>Ver Perfil</a>
                                <a class="dropdown-item red-text" href="" id="logout"><i class="fas fa-power-off pr-2"></i>Cerrar Sessión</a>
                            </div>
                        </li>

                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= constant('URL') . 'login.php'; ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= constant('URL') . 'singup.php'; ?>">Registrate</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

</header>