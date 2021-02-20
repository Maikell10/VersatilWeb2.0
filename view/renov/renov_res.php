<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

//$pag = 'renov/b_renov_t';

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
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
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

                <div class="table-responsive col-md-10 mx-auto">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovR" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th></th>
                                <th class="align-middle">Cant Pólizas a Renovar</th>
                                <th class="align-middle">En Proceso</th>
                                <th class="align-middle">En Seguimiento</th>
                                <th class="align-middle">No Renovadas</th>
                                <th class="align-middle">Pre Renovadas</th>
                                <th class="align-middle">% Pre Renovadas</th>
                                <th class="align-middle bg-warning text-black">Renovadas</th>
                                <th class="align-middle bg-warning text-black">% Efectividad Renovación</th>
                                <!-- <th>Pólizas Mes Renov</th> -->
                                <th class="align-middle">Acciones</th>
                                <th hidden>Mes</th>
                                <th hidden>Año</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $totalPrimaS = 0;
                            $totalPrimaC = 0;
                            $contador=0;
                            for ($i = 0; $i < 12; $i++) {
                                $polizas = $obj->renovarR($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY);
                                if($_SESSION['id_permiso'] == 3){
                                    $polizas = $obj->renovarR_asesor($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY, $user[0]['cod_vend']);
                                }

                                $cant_p = sizeof($polizas);
                                $cont = $cont + $cant_p;
                                for ($a = 0; $a < sizeof($polizas); $a++) {
                                    $totalPrimaS = $totalPrimaS + $polizas[$a]['prima'];
                                    $primac = $obj->obetnComisiones($polizas[$a]['id_poliza']);
                                    $totalPrimaC = $totalPrimaC + $primac[0]['SUM(prima_com)'];
                                }

                                $polizasRSeg = $obj->renovarRSeg($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY);
                                if($_SESSION['id_permiso'] == 3){
                                    $polizasRSeg = $obj->renovarRSeg_asesor($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY, $user[0]['cod_vend']);
                                }
                                
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
                                if($_SESSION['id_permiso'] == 3){
                                    $polizasRV = $obj->renovarRV_asesor($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY, $user[0]['cod_vend']);
                                }

                                $cant_pRV = sizeof($polizasRV);
                                $contRV = $contRV + $cant_pRV;

                                $cant_pRVCom = 0;
                                foreach ($polizasRV as $polizaRV) {
                                    $vRenov = $obj->verRenov2($polizaRV['id_poliza']);
                                    if ($vRenov[0]['no_renov'] == 0) {
                                        $primac = $obj->obetnComisiones($vRenov[0]['id_poliza']);
                                        if ($primac[0]['SUM(prima_com)'] > 1) {
                                            $cant_pRVCom = $cant_pRVCom + 1;
                                        }
                                    }
                                }
                                $contRVCom = $contRVCom + $cant_pRVCom;

                                $no_renov = $obj->get_no_renov($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY);
                                if($_SESSION['id_permiso'] == 3){
                                    $no_renov = $obj->get_no_renov_asesor($mes_arr_num[$i], $fecha_maxY, $mes_arr_num[$i], $fecha_minY, $user[0]['cod_vend']);
                                }

                                $contRA = $contRA + $no_renov['COUNT(*)'];

                                $div = ($cant_p == 0) ? 0 : (($cant_pRV * 100) / $cant_p);
                                $div1 = ($cant_p == 0) ? 0 : (($cant_pRVCom * 100) / $cant_p);

                                /*$polizasR = $obj->renovarRS($mes_arr_num[$i], $fecha_maxY);
                                $cant_pR = ($polizasR == 0) ? '0' : sizeof($polizasR);
                                $contR = $contR + $cant_pR;*/
                            ?>
                                <tr style="cursor: pointer">
                                    <td class="align-middle"><?= $mes_arr[$i]; ?></td>
                                    <td class="text-center align-middle"><?= $cant_p; ?></td>
                                    <td class="text-center align-middle"><?= $cant_p - $cant_pRV - $no_renov['COUNT(*)'] - $cant_pRSeg; ?></td>
                                    <td class="text-center align-middle"><?= $cant_pRSeg; ?></td>
                                    <td class="text-center align-middle"><?= $no_renov['COUNT(*)']; ?></td>
                                    <td class="text-center align-middle"><?= $cant_pRV - $cant_pRVCom; ?></td>
                                    <td class="text-center align-middle"><?= number_format($div, 2) . ' %'; ?></td>

                                    <td class="text-center align-middle font-weight-bold h6"><?= $cant_pRVCom; ?></td>

                                    <td class="text-center align-middle font-weight-bold h6"><?= number_format($div1, 2) . ' %'; ?></td>
                                    <!-- <td class="text-center"><?= $cant_pR; ?></td> -->
                                    <?php if (($cant_p - $cant_pRV - $no_renov['COUNT(*)'] - $cant_pRSeg) != 0 || $cant_pRSeg != 0 || $no_renov['COUNT(*)'] != 0 || $cant_pRV != 0) { ?>
                                        <td class="text-center" nowrap>
                                            <a href="renov_res_p.php?mes=<?= $mes_arr_num[$i]; ?>&anio=<?= $fecha_maxY; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver en Proceso (<?= ($cant_p - $cant_pRV - $no_renov['COUNT(*)'] - $cant_pRSeg); ?>)" class="btn sunny-morning-gradient btn-sm"><i class="fas fa-clock"></i></a>

                                            <a href="renov_res_s.php?mes=<?= $mes_arr_num[$i]; ?>&anio=<?= $fecha_maxY; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver en Seguimiento (<?= $cant_pRSeg; ?>)" class="btn blue-gradient btn-sm"><i class="fas fa-eye"></i></a>

                                            <a href="renov_res_nr.php?mes=<?= $mes_arr_num[$i]; ?>&anio=<?= $fecha_maxY; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver no Renovadas (<?= $no_renov['COUNT(*)']; ?>)" class="btn young-passion-gradient text-white btn-sm"><i class="fas fa-times"></i></a>

                                            <a href="renov_res_pr.php?mes=<?= $mes_arr_num[$i]; ?>&anio=<?= $fecha_maxY; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver Pre-Renovadas (<?= $cant_pRV - $cant_pRVCom; ?>)" class="btn dusty-grass-gradient btn-sm"><i class="fas fa-hourglass-start"></i></a>

                                            <a href="renov_res_r.php?mes=<?= $mes_arr_num[$i]; ?>&anio=<?= $fecha_maxY; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver Renovadas (<?= $cant_pRVCom; ?>)" class="btn aqua-gradient btn-sm"><i class="fas fa-check"></i></a>
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

                        <tfoot class="text-center">
                            <tr>
                                <th class="font-weight-bold">Total</th>
                                <th class="font-weight-bold"><?= $cont; ?></th>
                                <th class="font-weight-bold"><?= $cont - $contRV - $contRA - $contRSeg; ?></th>
                                <th class="font-weight-bold"><?= $contRSeg; ?></th>
                                <th class="font-weight-bold"><?= $contRA; ?></th>
                                <th class="font-weight-bold"><?= $contRV - $contRVCom; ?></th>
                                <th class="font-weight-bold"><?= number_format((($contRV * 100) / $cont), 2) . ' %'; ?></th>

                                <th class="font-weight-bold"><?= $contRVCom; ?></th>

                                <th class="font-weight-bold"><?= number_format((($contRVCom * 100) / $cont), 2) . ' %'; ?></th>
                                <!-- <th class="font-weight-bold"><?= $contR; ?></th> -->
                                <th class="font-weight-bold">Acciones</th>
                                <th hidden>Mes</th>
                                <th hidden>Año</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <br>

                <h1 class="title text-center">Total de Pólizas a Renovar</h1>
                <h1 class="title text-danger text-center"><?= $cont; ?></h1>

                <h1 class="title text-center">Total de Prima Suscrita a Renovar</h1>
                <h1 class="title text-danger text-center">$ <?= number_format($totalPrimaS, 2); ?></h1>


                <h1 class="title text-center">Efectividad de Renovación</h1>
                <h1 class="title text-danger text-center"><?= number_format((($contRVCom * 100) / $cont), 2) . ' %'; ?></h1>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

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
                var mes = $(this).find("td").eq(10).html();
                var anio = $(this).find("td").eq(11).html();

                window.open("renov_res_pp.php?mes=" + mes + "&anio=" + anio, '_blank');
            });
        </script>
</body>

</html>