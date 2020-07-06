<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'renov/b_renov_t';

require_once '../../Controller/Poliza.php';

$polizas = $obj->renovarM($_GET['anio'], $_GET['mes']);
$cant_p = sizeof($polizas);
/*foreach ($polizas as $poliza) {
    $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
    if (sizeof($poliza_renov) != 0) {
        $cant_p = $cant_p - 1;
    }
}*/

$polizasA = $obj->renovarME($_GET['anio'], $_GET['mes']);

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
                <div class="ml-5 mr-5">
                    <h1 class="font-weight-bold text-center">Lista Pólizas Renovadas</h1>
                    <h1 class="font-weight-bold text-center">Mes: <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                    <h1 class="font-weight-bold text-center">Año: <?= $_GET['anio']; ?></h1>
                </div>
            </div>

            <div id="enProceso"></div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenovAct3E', 'Listado de Pólizas a Renovar')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovAct3" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita</th>
                                <th>Prima Cobrada</th>
                                <th style="background-color: #E54848;">Prima Pendiente</th>
                                <th>Asesor</th>
                                <th>PDF</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $cantPoliza = 0;
                            foreach ($polizasA as $polizaA) {
                                $vRenov = $obj->verRenov2($polizaA['id_poliza']);
                                if ($vRenov[0]['no_renov'] == 0) {
                                    $cantPoliza++;

                                    $prima_t = $prima_t + $vRenov[0]['prima'];

                                    $newDesde = date("Y/m/d", strtotime($vRenov[0]['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($vRenov[0]['f_hastapoliza']));

                                    $primac = $obj->obetnComisiones($vRenov[0]['id_poliza']);
                                    $prima_tc = $prima_tc + $primac[0]['SUM(prima_com)'];

                                    $ppendiente = $vRenov[0]['prima'] - $primac[0]['SUM(prima_com)'];
                                    $prima_tp = $prima_tp + $ppendiente;
                                    $ppendiente = number_format($ppendiente, 2);
                                    if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                        $ppendiente = 0;
                                    }
                            ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $vRenov[0]['f_hastapoliza']; ?></td>
                                        <td hidden><?= $vRenov[0]['id_poliza']; ?></td>
                                        <?php if ($vRenov[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                            <td style="color: #2B9E34;font-weight: bold"><?= $vRenov[0]['cod_poliza']; ?></td>
                                        <?php } else { ?>
                                            <td style="color: #E54848;font-weight: bold"><?= $vRenov[0]['cod_poliza']; ?></td>
                                        <?php } ?>
                                        <td><?= ($vRenov[0]['nombre_t'] . ' ' . $vRenov[0]['apellido_t']); ?></td>
                                        <td><?= $vRenov[0]['nomcia']; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td align="right"><?= '$ ' . number_format($vRenov[0]['prima'], 2); ?></td>

                                        <td style="text-align: right"><?= '$ ' . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                        <?php if ($ppendiente > 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente == 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente < 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= '$ ' . $ppendiente; ?></td>
                                        <?php } ?>

                                        <td><?= $vRenov[0]['nombre']; ?></td>

                                        <?php if ($vRenov[0]['pdf'] == 1) { ?>
                                            <td class="text-center"><a href="../download.php?id_poliza=<?= $vRenov[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                            <?php } else {
                                            if ($vRenov[0]['nramo'] == 'Vida') {
                                                $vRenov1 = $obj->verRenov3($vRenov[0]['id_poliza']);
                                                if ($vRenov1 != 0) {
                                                    if ($vRenov1[0]['pdf'] != 0) {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov1[0]['id_poliza']); ?>
                                                        <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                        <?php } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($vRenov1[0]['cod_poliza'], $vRenov[0]['id_cia'], $vRenov[0]['f_hastapoliza']);
                                                        if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                            <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                        <?php } else { ?>
                                                            <td></td>
                                                        <?php }
                                                    }
                                                } else {
                                                    $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $vRenov[0]['id_cia'], $vRenov[0]['f_hastapoliza']);
                                                    if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                        <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                    <?php } else { ?>
                                                        <td></td>
                                                <?php }
                                                }
                                            } else { ?>
                                                <td></td>
                                            <?php } ?>
                                        <?php } ?>

                                    </tr>
                            <?php }
                            }  ?>
                        </tbody>

                        <tfoot class="text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita $<?= number_format($prima_t, 2); ?></th>
                                <th>Prima Cobrada $<?= number_format($prima_tc, 2); ?></th>
                                <th>Prima Pendiente $<?= number_format($prima_tp, 2); ?></th>
                                <th>Asesor</th>
                                <th>PDF</th>
                            </tr>
                        </tfoot>
                    </table>

                    <h1 class="title text-center">Total de Pólizas Renovadas de <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                    <h1 class="title text-danger text-center"><?= $cantPoliza; ?></h1>

                    <h1 class="title text-center">Total de Prima Suscrita</h1>
                    <h1 class="title text-danger text-center">$ <?= number_format($prima_t, 2); ?></h1>

                </div>


                <div class="table-responsive-xl" hidden>
                    <table class="table table-hover table-striped table-bordered" id="tableRenovAct3E" width="100%">
                        <thead>
                            <tr>
                                <th style="background-color: #4285F4; color: white">N° Póliza</th>
                                <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                                <th style="background-color: #4285F4; color: white">Cía</th>
                                <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                                <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                <th style="background-color: #E54848; color: white">Prima Pendiente</th>
                                <th style="background-color: #4285F4; color: white">Asesor</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $cantPoliza = 0;
                            $prima_t = 0;
                            $prima_tc = 0;
                            $prima_tp = 0;
                            foreach ($polizasA as $polizaA) {
                                $vRenov = $obj->verRenov2($polizaA['id_poliza']);
                                if ($vRenov[0]['no_renov'] == 0) {
                                    $cantPoliza++;

                                    $prima_t = $prima_t + $vRenov[0]['prima'];

                                    $newDesde = date("Y/m/d", strtotime($vRenov[0]['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($vRenov[0]['f_hastapoliza']));

                                    $primac = $obj->obetnComisiones($vRenov[0]['id_poliza']);
                                    $prima_tc = $prima_tc + $primac[0]['SUM(prima_com)'];

                                    $ppendiente = $vRenov[0]['prima'] - $primac[0]['SUM(prima_com)'];
                                    $prima_tp = $prima_tp + $ppendiente;
                                    $ppendiente = number_format($ppendiente, 2);
                                    if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                        $ppendiente = 0;
                                    }
                            ?>
                                    <tr style="cursor: pointer;">
                                        <?php if ($vRenov[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                            <td style="color: #2B9E34;font-weight: bold"><?= $vRenov[0]['cod_poliza']; ?></td>
                                        <?php } else { ?>
                                            <td style="color: #E54848;font-weight: bold"><?= $vRenov[0]['cod_poliza']; ?></td>
                                        <?php } ?>
                                        <td><?= ($vRenov[0]['nombre_t'] . ' ' . $vRenov[0]['apellido_t']); ?></td>
                                        <td><?= $vRenov[0]['nomcia']; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td align="right"><?= '$ ' . number_format($vRenov[0]['prima'], 2); ?></td>

                                        <td style="text-align: right"><?= '$ ' . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                        <?php if ($ppendiente > 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente == 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= '$ ' . $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente < 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= '$ ' . $ppendiente; ?></td>
                                        <?php } ?>

                                        <td><?= $vRenov[0]['nombre']; ?></td>
                                    </tr>
                            <?php }
                            }  ?>
                        </tbody>

                        <tfoot class="text-center">
                            <tr>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita $<?= number_format($prima_t, 2); ?></th>
                                <th>Prima Cobrada $<?= number_format($prima_tc, 2); ?></th>
                                <th>Prima Pendiente $<?= number_format($prima_tp, 2); ?></th>
                                <th>Asesor</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

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
            $("#tableRenovAct tbody tr").dblclick(function() {
                var customerId = $(this).find("td").eq(1).html();

                window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
            });
            $("#tableRenovAct1 tbody tr").dblclick(function() {
                var customerId = $(this).find("td").eq(1).html();

                window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
            });
            $("#tableRenovAct2 tbody tr").dblclick(function() {
                var customerId = $(this).find("td").eq(1).html();

                window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
            });
            $("#tableRenovAct3 tbody tr").dblclick(function() {
                var customerId = $(this).find("td").eq(1).html();

                //window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
                window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
            });
        </script>
</body>

</html>