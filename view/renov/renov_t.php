<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'renov/b_renov_t';

require_once '../../Controller/Poliza.php';

$polizas = $obj->renovarG($_POST['anio']);
$cant_p = sizeof($polizas);
/*
foreach ($polizas as $poliza) {
    $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
    if (sizeof($poliza_renov) != 0) {
        $cant_p = $cant_p - 1;
    }
}*/

$no_renov = $obj->get_element('no_renov', 'no_renov_n');

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
                            <h1 class="font-weight-bold ">Pólizas Pendientes a Renovar por Año</h1>
                            <h1 class="font-weight-bold ">Año: <?= $_POST['anio']; ?></h1>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenovG', 'Listado de Pólizas a Renovar')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovG" width="100%">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>Mes</th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th>Cía</th>
                                <th>Ramo</th>
                                <th>F Hasta Seguro</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>Nombre Titular</th>
                                <th>PDF</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $prima_t = 0;
                            foreach ($polizas as $poliza) {
                                $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
                                //if (sizeof($poliza_renov) == 0) {
                                $prima_t = $prima_t + $poliza['prima'];

                                $mes = date("m", strtotime($poliza['f_hastapoliza']));
                                $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));
                            ?>
                                <tr style="cursor: pointer;">
                                    <td hidden><?= $poliza['f_hastapoliza']; ?></td>
                                    <td hidden><?= $poliza['id_poliza']; ?></td>
                                    <td class="font-weight-bold"><?= $mes_arr[$mes - 1]; ?></td>

                                    <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                        <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                    <?php } else { ?>
                                        <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                    <?php } ?>
                                    <td><?= $poliza['nombre']; ?></td>
                                    <td><?= $poliza['nomcia']; ?></td>
                                    <td><?= $poliza['nramo']; ?></td>
                                    <td><?= $newHasta; ?></td>
                                    <td align="right"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                    <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                    <?php if ($poliza['pdf'] == 1) { ?>
                                        <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank" style="float: right"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                    <?php } else { ?>
                                        <td></td>
                                    <?php } ?>
                                    <td nowrap class="text-center">
                                        <a onclick="crearSeguimiento(<?= $poliza['id_poliza']; ?>)" data-toggle="tooltip" data-placement="top" title="Añadir Seguimiento" class="btn blue-gradient btn-rounded btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>

                                        <a href="crear_renov.php?id_poliza=<?= $poliza['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Renovar" class="btn dusty-grass-gradient btn-rounded btn-sm"><i class="fa fa-check-circle" aria-hidden="true"></i></a>

                                        <a onclick="noRenovar(<?= $poliza['id_poliza']; ?>,'<?= $poliza['f_hastapoliza']; ?>')" data-toggle="tooltip" data-placement="top" title="No Renovar" class="btn young-passion-gradient btn-rounded btn-sm"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            <?php //} else {
                                //$cant_p = $cant_p - 1;
                                //}
                            } ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>Mes</th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th>Cía</th>
                                <th>Ramo</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita $<?= number_format($prima_t, 2); ?></th>
                                <th>Nombre Titular</th>
                                <th>PDF</th>
                                <th>Acciones</th>
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

        <!-- Modal NO RENOV-->
        <div class="modal fade" id="noRenov" tabindex="-1" role="dialog" aria-labelledby="noRenov" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noRenov">No Renovar Póliza</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoNR" class="md-form">
                            <input type="text" class="form-control" id="id_polizaNR" name="id_polizaNR" hidden>
                            <input type="text" class="form-control" id="id_usuarioNR" name="id_usuarioNR" value="<?= $_SESSION['id_usuario']; ?>" hidden>
                            <input type="text" class="form-control" id="f_hastaNR" name="f_hastaNR" hidden>

                            <select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="no_renov" name="no_renov" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un Motivo" searchable="Búsqueda rápida">
                                <option value="">Seleccione el Motivo</option>
                                <?php
                                for ($i = 0; $i < sizeof($no_renov); $i++) {
                                ?>
                                    <option value="<?= $no_renov[$i]["id_no_renov"]; ?>"><?= utf8_encode($no_renov[$i]["no_renov_n"]); ?></option>
                                <?php } ?>
                            </select>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn dusty-grass-gradient" id="btnNoRenov">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>
            $("#tableRenovG tbody tr").dblclick(function() {
                if (customerId == null) {
                    var customerId = $(this).find("td").eq(1).html();
                }

                window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
            });
        </script>
</body>

</html>