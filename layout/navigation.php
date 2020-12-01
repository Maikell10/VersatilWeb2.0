<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . DS . 'constants.php';
require_once dirname(__DIR__) . DS . 'Model' . DS . 'Poliza.php';

$obj = new Poliza();

$user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

$new_user = $obj->get_element_by_id('usuarios', 'updated', 0);

require_once dirname(__DIR__) . DS . 'Model' . DS . 'Cliente.php';
$obj1 = new Cliente();

$birthdays_day = $obj1->get_birthdays_day(date("d"), date("m"));
$birthdays_day = ($birthdays_day == 0) ? 0 : $birthdays_day[0]['count'] ;

?>
<!-- Main Navigation -->
<header>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark blue-gradient fixed-top scrolling-navbar">
        <div class="container-fluid smooth-scroll">
            <a class="navbar-brand" href="<?= constant('URL'); ?>"><img src=<?= constant('URL') . 'assets/img/logv.png'; ?> width='110px' alt="logo" /></a>
            <button class="navbar-toggler first-button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent20" aria-controls="navbarSupportedContent20" aria-expanded="false" aria-label="Toggle navigation">
                <div class="animated-icon1"><span></span><span></span><span></span></div>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent20">
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['seudonimo'])) {
                        //if ($_SESSION['id_permiso'] != 3) {
                        if ($_SESSION['id_permiso'] != 3 || $user[0]['carga'] == 1) {
                    ?>
                            <?php if($_SESSION['id_permiso'] != 3) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= constant('URL') . 'view/birthday/birthday.php'; ?>"><i class="fas fa-birthday-cake pr-1"></i></i> Cumpleañeros del Mes
                                    <?php if ($birthdays_day != 0) { ?>
                                        <span class="badge badge-pill badge-danger"><?= $birthdays_day; ?></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <?php } ?>

                            <li class="dropdown nav-item">
                                <a class="dropdown-toggle nav-link" href="/" data-toggle="dropdown"><i class="fas fa-plus pr-1"></i> Cargar Datos</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/add/crear_poliza.php'; ?>"><i class="fas fa-plus-square pr-2 cyan-text"></i> Póliza</a>
                                    <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/add/crear_comision.php'; ?>"><i class="fas fa-plus-square pr-2 cyan-text"></i> Comisión</a>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/add/crear_asesor.php'; ?>"><i class="fas fa-user-plus pr-2 cyan-text"></i> Asesor</a>
                                    <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                        <a class="dropdown-item" href="<?= constant('URL') . 'view/add/crear_compania.php'; ?>"><i class="fas fa-briefcase pr-2 cyan-text"></i> Compañía</a>
                                        <a class="dropdown-item" href="<?= constant('URL') . 'view/add/crear_ramo.php'; ?>"><i class="fas fa-box pr-2 cyan-text"></i> Ramo</a>
                                        <a class="dropdown-item" href="<?= constant('URL') . 'view/add/crear_usuario.php'; ?>"><i class="fas fa-user-plus pr-2 cyan-text"></i> Usuario</a>
                                    <?php } } ?>
                                </div>
                            </li>
                        <?php } ?>

                        <li class="dropdown nav-item">
                            <a class="dropdown-toggle nav-link" href="/" data-toggle="dropdown"><i class="fas fa-search"></i> Buscar <?php if ($new_user != 0) { ?>
                                    <span class="badge badge-pill badge-danger"><?= count($new_user); ?></span>
                                <?php } ?></a>
                            <div class="dropdown-menu">
                                <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_asesor.php'; ?>"><i class="fas fa-male pr-2 cyan-text"></i> Asesor</a>
                                <?php } ?>
                                <a class="dropdown-item" href="<?= constant('URL') . 'view/b_cliente.php'; ?>"><i class="fas fa-male pr-2 cyan-text"></i> Cliente</a>
                                <a class="dropdown-item" href="<?= constant('URL') . 'view/b_poliza.php'; ?>"><i class="far fa-clipboard pr-2 cyan-text"></i> Póliza</a>
                                <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_comp.php'; ?>"><i class="fas fa-briefcase pr-2 cyan-text"></i> Compañía</a>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_ramo.php'; ?>"><i class="fas fa-box pr-2 cyan-text"></i> Ramo</a>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_moroso.php'; ?>"><i class="fas fa-hand-holding-usd pr-2 cyan-text"></i> Morosidad</a>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_reportes.php'; ?>"><i class="fas fa-clipboard-list pr-2 cyan-text"></i> Reportes de Comision</a>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/b_reportes_cia.php'; ?>"><i class="fas fa-clipboard-list pr-2 cyan-text"></i> Reportes de Comision por Cía</a>
                                    <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                        <a class="dropdown-item" href="<?= constant('URL') . 'view/b_usuario.php'; ?>"><i class="fas fa-user-tie pr-2 cyan-text"></i> Usuario
                                            <?php if ($new_user != 0) { ?>
                                                <span class="badge badge-pill badge-danger"><?= count($new_user); ?></span>
                                            <?php } ?>
                                        </a>
                                <?php }
                                } ?>
                            </div>
                        </li>

                        <li class="dropdown nav-item">
                            <a class="dropdown-toggle nav-link" href="/" data-toggle="dropdown"><i class="fas fa-chart-line"></i> Gráficos</a>
                            <div class="dropdown-menu">
                                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                    <a class="dropdown-item" href="<?= constant('URL') . 'view/grafic/Comparativo/b_mm_ramo.php'; ?>"><i class="material-icons cyan-text">bar_chart</i> Utilidad en Ventas</a>
                                <?php } ?>
                                <a class="dropdown-item" href="<?= constant('URL') . 'view/grafic/porcentaje.php'; ?>"><i class="material-icons cyan-text">pie_chart</i> Porcentajes</a>
                                <a class="dropdown-item" href="<?= constant('URL') . 'view/grafic/primas_s.php'; ?>"><i class="material-icons cyan-text">bar_chart</i> Primas Suscritas</a>
                                <a class="dropdown-item" href="<?= constant('URL') . 'view/grafic/primas_c.php'; ?>"><i class="material-icons cyan-text">thumb_up</i> Primas Cobradas</a>
                                <a class="dropdown-item" href="<?= constant('URL') . 'view/grafic/comisiones_c.php'; ?>"><i class="material-icons cyan-text">timeline</i> Comisiones Cobradas</a>
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
                                <?php
                                //solo par servidor versatil
                                /*
                                if ($user[0]['avatar'] == 1) { ?>
                                    <img src="<?= constant('URL') . 'assets/img/perfil/' . $_SESSION['seudonimo'] . '.jpg'; ?>" class="rounded-circle z-depth-0" height="35" alt="avatar" />
                                <?php } else { ?>
                                    <img src="<?= constant('URL') . 'assets/img/perfil/user.png'; ?>" class="rounded-circle z-depth-0" />
                                <?php }*/ ?>
                                <?php if ($user[0]['id_permiso'] == 3) { ?>
                                    <font class="font-weight-bold"><?= $user[0]['cod_vend']; ?></font>
                                <?php } ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg-right dropdown-primary" aria-labelledby="navbarDropdownMenuLink-55">
                                <a class="dropdown-item cyan-text" href="<?= constant('URL') . 'view/perfil.php'; ?>"><i class="fas fa-user-cog pr-2"></i>Ver Perfil</a>
                                <?php if ($user[0]['id_permiso'] == 3) { ?>
                                    <a class="dropdown-item green-text" href="#" data-toggle="modal" data-target="#modalLoginAvatarDemo"><i class="fas fa-atom pr-2"></i>Ver Código</a>
                                <?php } ?>
                                <a class="dropdown-item red-text" href="<?= constant('URL') . 'logout.php'; ?>"><i class="fas fa-power-off pr-2"></i>Cerrar Sessión</a>
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