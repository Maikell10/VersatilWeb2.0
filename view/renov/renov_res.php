<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'renov/b_renov_t';

require_once '../../Controller/Poliza.php';


$fecha_min = $obj->get_fecha_min_max('MIN', 'created_at', 'seguimiento');
$fecha_max = $obj->get_fecha_min_max('MAX', 'created_at', 'seguimiento');

//FECHA MAYORES A 2024
$dateString = $fecha_max[0]["MAX(created_at)"];
// Parse a textual date/datetime into a Unix timestamp
$date = new DateTime($dateString);
$formatY = 'Y';
$formatM = 'm';
// Parse a textual date/datetime into a Unix timestamp
$date = new DateTime($dateString);
// Print it
$fecha_maxY = $_POST['anio'];
//$fecha_maxY = $date->format($formatY);
$fecha_maxM = $date->format($formatM);

$fecha_minY = $_POST['anio'];
//$fecha_minY = date('Y', strtotime($fecha_min[0]["MIN(created_at)"]));
$fecha_minM = date('m', strtotime($fecha_min[0]["MIN(created_at)"]));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold ">Lista Pólizas por Efectividad de Renovación</h1>
                            <h1 class="font-weight-bold">Año: <?= $fecha_maxY; ?></h1>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                <div class="table-responsive-xl col-md-8 mx-auto">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovR" width="100%">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th></th>
                                <th class="text-center">Cant Pólizas</th>
                                <th class="text-center">En Proceso</th>
                                <th class="text-center">En Seguimiento</th>
                                <th class="text-center">No Renovadas</th>
                                <th class="text-center">Renovadas</th>
                                <th class="text-center">% Efectividad</th>
                                <!-- <th class="text-center">Pólizas Mes Renov</th> -->
                                <th>Acciones</th>
                                <th hidden>Mes</th>
                                <th hidden>Año</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            for ($i = 0; $i < 12; $i++) {
                                $polizas = $obj->renovarR($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY);
                                $cant_p = sizeof($polizas);
                                $cont = $cont + $cant_p;

                                $polizasRSeg = $obj->renovarRSeg($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY);
                                $cant_pRSeg = sizeof($polizasRSeg);

                                if ($mes_arr_num[$i] < date('m') || $_POST['anio'] < date('Y')) {
                                    foreach ($polizasRSeg as $polizaRSeg) {
                                        $polizaRSeg_renov = $obj->comprobar_poliza($polizaRSeg['cod_poliza'], $polizaRSeg['id_cia']);
                                        if (sizeof($polizaRSeg_renov) != 0) {
                                            $cant_pRSeg = $cant_pRSeg - 1;
                                        }
                                    }
                                }


                                $contRSeg = $contRSeg + $cant_pRSeg;

                                $polizasRV = $obj->renovarRV($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY);
                                $cant_pRV = sizeof($polizasRV);
                                $contRV = $contRV + $cant_pRV;

                                $no_renov = $obj->get_no_renov($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY);
                                $contRA = $contRA + $no_renov['COUNT(*)'];

                                $div = ($cant_p == 0) ? 0 : (($cant_pRV * 100) / $cant_p);

                                /*$polizasR = $obj->renovarRS($mes_arr_num[$i], $fecha_maxY);
                                $cant_pR = ($polizasR == 0) ? '0' : sizeof($polizasR);
                                $contR = $contR + $cant_pR;*/
                            ?>
                                <tr style="cursor: pointer">
                                    <td><?= $mes_arr[$i]; ?></td>
                                    <td class="text-center"><?= $cant_p; ?></td>
                                    <td class="text-center"><?= $cant_p - $cant_pRV - $no_renov['COUNT(*)'] - $cant_pRSeg; ?></td>
                                    <td class="text-center"><?= $cant_pRSeg; ?></td>
                                    <td class="text-center"><?= $no_renov['COUNT(*)']; ?></td>
                                    <td class="text-center"><?= $cant_pRV; ?></td>
                                    <td class="text-center"><?= number_format($div, 2) . ' %'; ?></td>
                                    <!-- <td class="text-center"><?= $cant_pR; ?></td> -->
                                    <?php if (($cant_p - $cant_pRV - $no_renov['COUNT(*)'] - $cant_pRSeg) != 0 || $cant_pRSeg != 0 || $no_renov['COUNT(*)'] != 0 || $cant_pRV != 0) { ?>
                                        <td class="text-center" nowrap>
                                            <?php if (($cant_p - $cant_pRV - $no_renov['COUNT(*)'] - $cant_pRSeg) != 0) { ?>
                                                <a href="renov_res_p.php?mes=<?= $mes_arr_num[$i]; ?>&anio=<?= $fecha_maxY; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver en Proceso" class="btn winter-neva-gradient btn-sm"><i class="fas fa-clock"></i></a>
                                            <?php } ?>

                                            <?php if ($cant_pRSeg != 0) { ?>
                                                <a href="renov_res_s.php?mes=<?= $mes_arr_num[$i]; ?>&anio=<?= $fecha_maxY; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver en Seguimiento" class="btn blue-gradient btn-sm"><i class="fas fa-eye"></i></a>
                                            <?php } ?>

                                            <?php if ($no_renov['COUNT(*)'] != 0) { ?>
                                                <a href="renov_res_nr.php?mes=<?= $mes_arr_num[$i]; ?>&anio=<?= $fecha_maxY; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver no Renovadas" class="btn young-passion-gradient text-white btn-sm"><i class="fas fa-times"></i></a>
                                            <?php } ?>

                                            <?php if ($cant_pRV != 0) { ?>
                                                <a href="renov_res_r.php?mes=<?= $mes_arr_num[$i]; ?>&anio=<?= $fecha_maxY; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver Renovadas" class="btn aqua-gradient btn-sm"><i class="fas fa-check"></i></a>
                                            <?php } ?>
                                        </td>
                                    <?php } else { ?>
                                        <td></td>
                                    <?php } ?>
                                    <td hidden><?= $mes_arr_num[$i]; ?></td>
                                    <td hidden><?= $fecha_maxY; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th class="font-weight-bold">Total</th>
                                <th class="font-weight-bold text-center"><?= $cont; ?></th>
                                <th class="font-weight-bold text-center"><?= $cont - $contRV - $contRA - $contRSeg; ?></th>
                                <th class="font-weight-bold text-center"><?= $contRSeg; ?></th>
                                <th class="font-weight-bold text-center"><?= $contRA; ?></th>
                                <th class="font-weight-bold text-center"><?= $contRV; ?></th>
                                <th class="font-weight-bold text-center"><?= number_format((($contRV * 100) / $cont), 2) . ' %'; ?></th>
                                <!-- <th class="font-weight-bold text-center"><?= $contR; ?></th> -->
                                <th></th>
                                <th hidden>Mes</th>
                                <th hidden>Año</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <br>

                <h1 class="title text-center">Total de Pólizas</h1>
                <h1 class="title text-danger text-center"><?= $cont; ?></h1>

                <h1 class="title text-center">Total de Pólizas Renovadas</h1>
                <h1 class="title text-danger text-center"><?= $contRV; ?></h1>

                <h1 class="title text-center">Efectividad de Renovación</h1>
                <h1 class="title text-danger text-center"><?= number_format((($contRV * 100) / $cont), 2) . ' %'; ?></h1>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <!-- Modal SEGUIMIENTO RENOV-->
        <div class="modal fade" id="seguimientoRenov" tabindex="-1" role="dialog" aria-labelledby="seguimientoRenov" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="seguimientoRenov">Crear Comentario para Seguimiento de la Póliza</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoS" class="md-form">
                            <input type="text" class="form-control" id="id_polizaS" name="id_polizaS" hidden>
                            <input type="text" class="form-control" id="id_usuarioS" name="id_usuarioS" value="<?= $_SESSION['id_usuario']; ?>" hidden>
                            <label for="comentarioS">Ingrese Comentario</label>
                            <textarea class="form-control md-textarea" id="comentarioS" name="comentarioS" required onKeyDown="valida_longitud()" onKeyUp="valida_longitud()" maxlength="300"></textarea>

                            <input type="text" id="caracteres" class="form-control" disabled value="Caracteres restantes: 300">

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn dusty-grass-gradient" id="btnSeguimientoR">Crear</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>
            $("#tableRenovR tbody tr").dblclick(function() {
                var mes = $(this).find("td").eq(8).html();
                var anio = $(this).find("td").eq(9).html();

                window.open("renov_res_pp.php?mes=" + mes + "&anio=" + anio, '_blank');
            });
        </script>
</body>

</html>