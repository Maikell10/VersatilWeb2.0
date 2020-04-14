<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'renov/b_renov_t';

require_once '../../Controller/Poliza.php';

$polizas = $obj->renovarSE($_GET['fmaxY'], $_GET['mes']);
$cant_p = sizeof($polizas);

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
                <div class="ml-5 mr-5">
                    <h1 class="font-weight-bold text-center">Lista Pólizas en Proceso</h1>
                    <h1 class="font-weight-bold text-center">Mes: <?= $mes_arr[$_GET['mes'] - 1]; ?></h1>
                    <h1 class="font-weight-bold text-center">Año: <?= $_GET['fmaxY']; ?></h1>
                </div>
            </div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenovAct', 'Listado de Pólizas a Renovar')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovAct" width="100%">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>Obs Seguimiento</th>
                                <th>Cant Seg</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $prima_t = 0;
                            foreach ($polizas as $poliza) {
                                $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
                                if (sizeof($poliza_renov) == 0) {
                                    $prima_t = $prima_t + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $seguimiento = $obj->seguimiento($poliza['id_poliza']);
                                    $cant_seg = (sizeof($seguimiento) == 0) ? 0 : sizeof($seguimiento);
                                    $ultimo_seg = (sizeof($seguimiento) == 0) ? '' : $seguimiento[0]['comentario'];
                            ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $poliza['f_hastapoliza']; ?></td>
                                        <td hidden><?= $poliza['id_poliza']; ?></td>
                                        <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <td><?= utf8_encode($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                        <td><?= $poliza['nomcia']; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td align="right"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                        <td><?= $ultimo_seg; ?></td>
                                        <td><?= $cant_seg; ?></td>
                                    </tr>
                            <?php } else {
                                    $cant_p = $cant_p - 1;
                                }
                            } ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita</th>
                                <th>Obs Seguimiento</th>
                                <th>Cant Seg</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <h1 class="title text-center">Total de Prima Suscrita</h1>
                <h1 class="title text-danger text-center">$ <?= number_format($prima_t, 2); ?></h1>

                <h1 class="title text-center">Total de Pólizas</h1>
                <h1 class="title text-danger text-center"><?= $cant_p; ?></h1>
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
            $("#tableRenovAct tbody tr").dblclick(function() {
                var customerId = $(this).find("td").eq(1).html();

                window.open("../v_poliza.php?modal=true&id_poliza=" + customerId, '_blank');
            });
        </script>
</body>

</html>