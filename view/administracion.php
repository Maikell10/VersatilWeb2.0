<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Model/Poliza.php';

$obj = new Poliza();
$polizas_r = $obj->get_polizas_r();

$contN = sizeof($polizas_r);

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
                <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">Administración (Listados)</a></h2>
            </div>
            <br><br>

            <div class="collapse" id="collapse1">
                <div class="card-deck">
                    <div class="card text-white bg-info mb-3">
                        <a href="b_reportes.php" class="hoverable">
                            <div class="card-body">
                                <h5 class="card-title text-white">Reportes de Comisión</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card text-white bg-info mb-3">
                        <a href="b_reportes_cia.php" class="hoverable">
                            <div class="card-body">
                                <h5 class="card-title text-white">Reportes de Comisión por Compañias</h5>
                            </div>
                        </a>
                    </div>
                    <div class="card text-white bg-info mb-3">
                        <a href="gc/b_pagos_ref.php" class="hoverable">
                            <div class="card-body">
                                <h5 class="card-title text-white">Pago de Referidores</h5>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="card-deck">
                    <div class="card text-white bg-info mb-3">
                        <a href="b_reportes_gc.php" class="hoverable">
                            <div class="card-body">
                                <h5 class="card-title text-white">Reportes de GC</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <br>

            <div class="col-md-auto col-md-offset-2 hover-collapse">
                <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2">Administración (Carga)</a></h2>
            </div>
            <br><br>

            <div class="collapse" id="collapse2">
                <div class="card-deck">
                    <div class="card text-white bg-info mb-3">
                        <a href="add/crear_comision.php" class="hoverable">
                            <div class="card-body">
                                <h5 class="card-title text-white">Comisiones</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <br>

            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                <div class="col-md-auto col-md-offset-2 hover-collapse">
                    <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3">Generar Pago GC</a></h2>
                </div>
                <br><br>

                <div class="collapse" id="collapse3">
                    <div class="card-deck">
                        <div class="card text-white bg-info mb-3">
                            <a href="gc/b_gc.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Generar GC Pago Asesores</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <br>

                <div class="col-md-auto col-md-offset-2 hover-collapse">
                    <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse4" role="button" aria-expanded="false" aria-controls="collapse4">Cobranza Referidores</a>
                        <?php if ($contN != 0) { ?>
                            <a data-toggle="tooltip" data-placement="top" title="Hay Referidores para pagar" class="btn peach-gradient btn-rounded btn-sm text-white" data-toggle="modal" data-target="#tarjetaV">
                                <p class="h5"><i class="fas fa-clipboard-list" aria-hidden="true"></i> <?= $contN; ?></p>
                            </a>
                        <?php } ?>
                    </h2>
                </div>
                <br><br>

                <div class="collapse" id="collapse4">
                    <div class="card-deck">
                        <div class="card text-white bg-info mb-3">
                            <a href="gc/b_gc_r.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Generar Pago</h5>
                                </div>
                            </a>
                        </div>
                        <div class="card text-white bg-info mb-3">
                            <a href="gc/pago_gc_r.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Cargar Pago</h5>
                                </div>
                            </a>
                        </div>
                        <div class="card text-white bg-info mb-3">
                            <a href="gc/b_gc_r.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Generar GC Pago</h5>
                                </div>
                            </a>
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