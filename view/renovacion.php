<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Model/Poliza.php';

$obj = new Poliza();
$polizas = $obj->renovar();
$cant_p = sizeof($polizas);

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
    <br><br><br><br><br><br>

    <div class="card">

        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <div class="ml-5 mr-5">
        </div>

        <br><br>

        <div class="ml-5 mr-5">
            <div class="col-md-auto col-md-offset-2 hover-collapse">
                <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">Renovación (Listados Pólizas a Renovar)</a></h2>
            </div>
            <br><br>

            <div class="collapse" id="collapse1">
                <div class="card-deck">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body hoverable">
                            <a href="renov/b_renov_por_cia.php">
                                <h5 class="card-title text-white">Organizadas Por Cía</h5>
                            </a>
                        </div>
                    </div>

                    <?php if ($_SESSION['id_permiso'] != 3) { ?>
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body hoverable">
                                <a href="renov/b_renov_por_asesor.php">
                                    <h5 class="card-title text-white">Organizadas Por Asesor</h5>
                                </a>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="card text-white bg-info mb-3">
                        <div class="card-body hoverable">
                            <a href="renov/b_renov_g.php">
                                <h5 class="card-title text-white">General</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="col-md-auto col-md-offset-2 hover-collapse">
                    <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2">Renovación (Carga)</a>
                        <?php if ($cant_p != 0) { ?>
                            <a data-toggle="tooltip" data-placement="top" title="Hay <?= $cant_p; ?> Pólizas para Renovar" class="btn peach-gradient btn-rounded btn-sm text-white" data-toggle="modal" data-target="#tarjetaV">
                                <p class="h5"><i class="fas fa-stopwatch" aria-hidden="true"></i> <?= $cant_p; ?></p>
                            </a>
                        <?php } ?>
                    </h2>
                </div>
                <br><br>

                <div class="collapse" id="collapse2">
                    <div class="card-deck">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body hoverable">
                                <a href="renov/b_renov_t.php">
                                    <h5 class="card-title text-white">Pólizas Pendientes a Renovar a la Fecha
                                        <?php if ($cant_p != 0) { ?>
                                            <span class="badge badge-pill peach-gradient ml-2"><?= $cant_p; ?></span>
                                        <?php } ?>
                                    </h5>
                                </a>
                            </div>
                        </div>
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body hoverable">
                                <a href="renov/b_renov_tg.php">
                                    <h5 class="card-title text-white">Pólizas Pendientes a Renovar por Año</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-auto col-md-offset-2 hover-collapse">
                    <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3">Renovación (Seguimiento)</a></h2>
                </div>
                <br><br>

                <div class="collapse" id="collapse3">
                    <div class="card-deck">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body hoverable">
                                <a href="renov/b_renov_res.php">
                                    <h5 class="card-title text-white">Efectividad de Renovación</h5>
                                </a>
                            </div>
                        </div>
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body hoverable">
                                <a href="renov/b_renov_act.php">
                                    <h5 class="card-title text-white">Resúmen de Pólizas Pendientes a Renovar a la Fecha</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <br>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>
</body>

</html>