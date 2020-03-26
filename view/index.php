<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

require_once '../Model/Poliza.php';

$obj = new Poliza();

$tarjeta = $obj->get_tarjeta_venc();
$contN = sizeof($tarjeta);

$polizas = $obj->renovar();
$cant_p = sizeof($polizas);

$polizas_r = $obj->get_polizas_r();
$contPR = sizeof($polizas_r);

foreach ($polizas as $poliza) {
    $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
    if (sizeof($poliza_renov) != 0) {
        $cant_p = $cant_p - 1;
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <!--
    <div class="landing darken-3" style="background-image: url(<?= constant('URL') . '/assets/img/logo2.png'; ?>);">
        <div class="container">
            <br><br><br>
            <h1 class="text-center font-weight-bold">Bienvenido <i class="material-icons">person</i></h1>
        </div>
    </div>-->
    <br><br><br><br><br><br>

    <div class="card">
        <div class="card-header p-5">
            <h1 class="text-center font-weight-bold">
                Bienvenido <?= $_SESSION['seudonimo']; ?> <i class="fas fa-user pr-2 cyan-text"></i></h1>
            <hr />
        </div>
        <div class="card-body ml-auto mr-auto">
            <ul class="nav md-pills  pills-primary" role="tablist">
                <li class="nav-item m-auto">
                    <a class="nav-link p-4" href="produccion.php"><i class="fas fa-table fa-3x"></i>
                        <h4>Producción
                            <?php if (($contN != 0) && ($_SESSION['id_permiso'] != 3)) { ?>
                                <span class="badge badge-pill peach-gradient ml-2"><?= $contN; ?></span>
                            <?php } ?>
                        </h4>
                    </a>
                </li>
                <li class="nav-item m-auto">
                    <a class="nav-link p-4" href="renovacion.php"><i class="fas fa-stopwatch fa-3x"></i>
                        <h4>Renovación
                            <?php if (($cant_p != 0) && ($_SESSION['id_permiso'] != 3)) { ?>
                                <span class="badge badge-pill peach-gradient ml-2"><?= $cant_p; ?></span>
                            <?php } ?>
                        </h4>
                    </a>
                </li>
                <?php if ($_SESSION['id_permiso'] != 3) { ?>
                    <li class="nav-item m-auto">
                        <a class="nav-link p-4" href="administracion.php"><i class="fas fa-clock fa-3x"></i>
                            <h4>Administración
                                <?php if ($contPR != 0 && $_SESSION['id_permiso'] == 1) { ?>
                                    <span class="badge badge-pill peach-gradient ml-2"><?= $contPR; ?></span>
                                <?php } ?>
                            </h4>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item m-auto">
                    <a class="nav-link p-4" href="graficos.php"><i class="fas fa-chart-line fa-3x"></i>
                        <h4>Gráficos</h4>
                    </a>
                </li>
                <li class="nav-item m-auto">
                    <a class="nav-link p-4" href="#tasks-1" role="tab" data-toggle="tab"><i class="fas fa-list-ul fa-3x"></i>
                        <h4>Siniestros</h4>
                    </a>
                </li>
            </ul>

            <div class="tab-content tab-space">
                <div class="tab-pane" id="tasks-1">Módulo en contrucción Siniestros</div>
            </div>
        </div>
    </div>

    <div class="section" style="background-color: #40A8CB">
        <div class="container p-5">
            <div class="row">
                <div class="col-md-12 ml-auto mr-auto">
                    <div class="card card-cascade narrower">
                        <div class="card-body card-body-cascade">
                            <div class="form-header blue-gradient">
                                <h3>
                                    <i class="fas fa-search"></i> Busqueda General de Póliza</h3>
                            </div>
                            <form method="POST" action="b_poliza_busq.php" class="form text-center">
                                <div class="md-form col-md-6 mx-auto">
                                    <input type="text" class="form-control" id="busq" name="busq" autoComplete="off" data-toggle="tooltip" data-placement="top" title="Busqueda General de Póliza por Nº de Póliza, id Titular, Nombre y Apellido del Titular" />
                                    <button type="submit" id="btnBusq" class="btn blue-gradient btn-lg">Buscar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>
    
    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>
</body>

</html>