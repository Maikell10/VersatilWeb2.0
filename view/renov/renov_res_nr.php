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
                    <h1 class="font-weight-bold text-center">Lista Pólizas No Renovadas</h1>
                    <h1 class="font-weight-bold text-center">Mes: <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                    <h1 class="font-weight-bold text-center">Año: <?= $_GET['anio']; ?></h1>
                </div>
            </div>

            <div id="enProceso"></div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenovAct', 'Listado de Pólizas a Renovar')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovAct2" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>Asesor</th>
                                <th>Obs Seguimiento</th>
                                <th>Cant Seg</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $cantPoliza = 0;
                            foreach ($polizasA as $polizaA) {
                                $vRenov = $obj->verRenov1($polizaA['id_poliza']);
                                if ($vRenov[0]['no_renov'] == 1) {
                                    $cantPoliza++;

                                    $prima_t = $prima_t + $polizaA['prima'];

                                    $newDesde = date("Y/m/d", strtotime($polizaA['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($polizaA['f_hastapoliza']));

                                    $seguimiento = $obj->seguimiento($polizaA['id_poliza']);
                                    $cant_seg = ($seguimiento == 0) ? 0 : sizeof($seguimiento);
                                    $ultimo_seg = $vRenov[0]['no_renov_n'];
                            ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $polizaA['f_hastapoliza']; ?></td>
                                        <td hidden><?= $polizaA['id_poliza']; ?></td>
                                        <td style="color: #E54848;font-weight: bold"><?= $polizaA['cod_poliza']; ?></td>
                                        <td><?= ($polizaA['nombre_t'] . ' ' . $polizaA['apellido_t']); ?></td>
                                        <td><?= $polizaA['nomcia']; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td align="right"><?= '$ ' . number_format($polizaA['prima'], 2); ?></td>
                                        <td><?= $polizaA['nombre']; ?></td>
                                        <td><?= $ultimo_seg; ?></td>
                                        <td class="text-center"><?= $cant_seg + 1; ?></td>
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
                                <th>Prima Suscrita $<?= number_format($prima_t,2);?></th>
                                <th>Asesor</th>
                                <th>Obs Seguimiento</th>
                                <th>Cant Seg</th>
                            </tr>
                        </tfoot>
                    </table>

                    <h1 class="title text-center">Total de Pólizas No Renovadas de <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                    <h1 class="title text-danger text-center"><?= $cantPoliza; ?></h1>

                    <h1 class="title text-center">Total de Prima Suscrita</h1>
                    <h1 class="title text-danger text-center">$ <?= number_format($prima_t, 2); ?></h1>
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

                    window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
                });
            </script>
</body>

</html>