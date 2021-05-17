<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Model/Poliza.php';

$obj = new Poliza();
$polizas_r = $obj->get_polizas_r();
$polizas_p = $obj->get_polizas_p();

$contN = ($polizas_r == 0) ? 0 : sizeof($polizas_r);
$contP = ($polizas_p == 0) ? 0 : sizeof($polizas_p);

/*
$pago_ref = $obj->get_gc_h_r(0);
$pago_ref = ($pago_ref == 0) ? 0 : sizeof($pago_ref);*/

$pago_proyect = $obj->get_gc_h_p(0);
$pago_proyect = ($pago_proyect == 0) ? 0 : sizeof($pago_proyect);

$count_faltante_pago_gc = $obj->get_count_a_reporte_gc_h_restante();
if($count_faltante_pago_gc[0]['COUNT(DISTINCT comision.cod_vend)'] != 0) {
    $count_faltante_pago_gc = 1;
} else{
    $count_faltante_pago_gc = 0;
}

$count_faltante_pago_gcr = $obj->get_count_r_reporte_gc_h_restante();
if($count_faltante_pago_gcr[0]['COUNT(id_gc_h_r)'] != 0) {
    $count_faltante_pago_gcr = 1;
} else{
    $count_faltante_pago_gcr = 0;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">

        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a>
                    <div class="ml-5 mr-5">

                        <h1 class="text-center font-weight-bold">ADMINISTRACIÓN</h1>
                    </div>

                    <br><br>

                    <div class="ml-5 mr-5">
                        <div class="col-md-auto col-md-offset-2 hover-collapse">
                            <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">Listados</a>
                                <?php if (($count_faltante_pago_gc != 0 || $count_faltante_pago_gcr != 0) && $_SESSION['id_permiso'] != 3) { ?>
                                    <a data-toggle="tooltip" data-placement="top" title="Hay Pagos de GC sin Hacer" class="btn peach-gradient btn-rounded btn-sm text-white">
                                        <p class="h5"><i class="fas fa-money-check-alt" aria-hidden="true"></i> !</p>
                                    </a>
                                <?php } ?>
                            </h2>
                        </div>
                        <br><br>

                        <div class="collapse" id="collapse1">
                            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                <div class="card-deck">
                                    <div class="card text-white bg-info mb-3">
                                        <div class="card-body hoverable">
                                            <a href="b_reportes.php">
                                                <h5 class="card-title text-white">Reportes de Comisiones Generales</h5>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card text-white bg-info mb-3">
                                        <div class="card-body hoverable">
                                            <a href="b_reportes_cia.php">
                                                <h5 class="card-title text-white">Reportes de Comisiones organizados por Compañías</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr style="border: inset;margin-top: 15px; margin-bottom: 25px">
                            <?php } ?>

                            <div class="card-deck">
                                <div class="card text-white bg-info mb-3">
                                    <div class="card-body hoverable">
                                        <a href="b_prima_detail.php">
                                            <h5 class="card-title text-white">Cobranza</h5>
                                        </a>
                                    </div>
                                </div>
                                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                    <div class="card text-white bg-info mb-3">
                                        <div class="card-body hoverable">
                                            <a href="b_moroso.php">
                                                <h5 class="card-title text-white">Morosidad <i class="fas fa-asterisk text-warning pr-3"></i></h5>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <hr style="border: inset;margin-top: 15px; margin-bottom: 25px">

                            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                <div class="card-deck">
                                    <div class="card text-white bg-info mb-3">
                                        <div class="card-body hoverable">
                                            <a href="b_reportes_gc.php">
                                                <h5 class="card-title text-white">Historial de GC (Asesores)
                                                    <?php if ($count_faltante_pago_gc != 0) { ?>
                                                        <span class="badge badge-pill peach-gradient ml-2">!</span>
                                                    <?php } ?>
                                                </h5>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card text-white bg-info mb-3">
                                        <div class="card-body hoverable">
                                            <a href="gc/b_pagos_ref.php">
                                                <h5 class="card-title text-white">Historial de GC (Referidores)
                                                    <?php if ($count_faltante_pago_gcr != 0) { ?>
                                                        <span class="badge badge-pill peach-gradient ml-2">!</span>
                                                    <?php } ?>
                                                </h5>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card text-white bg-info mb-3">
                                        <div class="card-body hoverable">
                                            <a href="gc/b_pagos_proyect.php">
                                                <h5 class="card-title text-white">Historial de GC (Proyecto)</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr style="border: inset;margin-top: 15px; margin-bottom: 25px">

                                <div class="card-deck">
                                    <div class="card text-white bg-info mb-3">
                                        <div class="card-body hoverable">
                                            <a href="gc/b_asesor.php">
                                                <h5 class="card-title text-white">GC por Asesor</h5>
                                            </a>
                                        </div>
                                    </div>


                                </div>
                            <?php } ?>
                        </div>

                        <br>

                        <?php if ($_SESSION['id_permiso'] == 1) { ?>
                            <div class="col-md-auto col-md-offset-2 hover-collapse">
                                <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2">Carga</a>
                                    <?php 
                                    if ($pago_proyect != 0) { ?>
                                        <a data-toggle="tooltip" data-placement="top" title="Hay Proyectos para Cargar Pago" class="btn peach-gradient btn-rounded btn-sm text-white" data-toggle="modal" data-target="#tarjetaV">
                                            <p class="h5"><i class="fas fa-clipboard-list" aria-hidden="true"></i> <?= $pago_proyect; ?></p>
                                        </a>
                                    <?php } ?>
                                </h2>
                            </div>
                            <br><br>

                            <div class="collapse" id="collapse2">
                                <div class="card-deck">
                                    <div class="card text-white bg-info mb-3">
                                        <a href="add/crear_comision.php" class="hoverable card-body">
                                            <div class="">
                                                <h5 class="card-title text-white">Pago de Asesores </br> (Reportes de Comisiones)</h5>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="card text-white bg-info mb-3">
                                        <a href="gc/pago_gc_p.php" class="hoverable card-body">
                                            <div class="">
                                                <h5 class="card-title text-white">Pago Proyecto
                                                    <?php if ($pago_proyect != 0) { ?>
                                                        <span class="badge badge-pill peach-gradient ml-2"><?= $pago_proyect; ?></span>
                                                    <?php } ?>
                                                </h5>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <br>


                            <div class="col-md-auto col-md-offset-2 hover-collapse">
                                <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3">Gestión de Cobranza GC</a>
                                    <?php if ($contN != 0 && $contP == 0) { ?>
                                        <a data-toggle="tooltip" data-placement="top" title="Hay Referidores para Generar Pago" class="btn peach-gradient btn-rounded btn-sm text-white">
                                            <p class="h5"><i class="fas fa-clipboard-list" aria-hidden="true"></i> <?= $contN; ?></p>
                                        </a>
                                    <?php }
                                    if ($contN == 0 && $contP != 0) { ?>
                                        <a data-toggle="tooltip" data-placement="top" title="Hay Proyectos para Generar Pago" class="btn peach-gradient btn-rounded btn-sm text-white">
                                            <p class="h5"><i class="fas fa-clipboard-list" aria-hidden="true"></i> <?= $contP; ?></p>
                                        </a>
                                    <?php }
                                    if ($contN != 0 && $contP != 0) { ?>
                                        <a data-toggle="tooltip" data-placement="top" title="Hay Referidores y Proyectos para Generar Pago" class="btn peach-gradient btn-rounded btn-sm text-white">
                                            <p class="h5"><i class="fas fa-clipboard-list" aria-hidden="true"></i> <?= $contN + $contP; ?></p>
                                        </a>
                                    <?php } ?>
                                </h2>
                            </div>
                            <br><br>

                            <div class="collapse" id="collapse3">
                                <div class="card-deck">
                                    <div class="card text-white bg-info mb-3">
                                        <a href="gc/b_gc.php" class="hoverable card-body">
                                            <div class="">
                                                <h5 class="card-title text-white">GC Asesores</h5>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="card text-white bg-info mb-3">
                                        <a href="gc/b_gc_r.php" class="hoverable card-body">
                                            <div class="">
                                                <h5 class="card-title text-white">GC Referidores
                                                    <?php if ($contN != 0) { ?>
                                                        <span class="badge badge-pill peach-gradient ml-2"><?= $contN; ?></span>
                                                    <?php } ?>
                                                </h5>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="card text-white bg-info mb-3">
                                        <a href="gc/b_gc_p.php" class="hoverable card-body">
                                            <div class="">
                                                <h5 class="card-title text-white">GC Proyecto
                                                    <?php if ($contP != 0) { ?>
                                                        <span class="badge badge-pill peach-gradient ml-2"><?= $contP; ?></span>
                                                    <?php } ?>
                                                </h5>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <br>

                        <?php } ?>

                        <br>
                    </div>

        </div>



        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>
</body>

</html>