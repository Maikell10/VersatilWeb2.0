<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
//$pag = 'b_reportes';

require_once '../../Controller/Poliza.php';

$proyect = $obj->get_gc_h_p_created(1, $_GET['created_at']);

$newCreated = date("d-m-Y", strtotime($_GET['created_at']));

$count_faltante_pago_gcp = $obj->get_count_p_reporte_gc_h_restante_by_id($_GET["created_at"]);
if ($count_faltante_pago_gcp[0]['COUNT(id_gc_h_p)'] != 0) {
    $count_faltante_pago_gcp = $count_faltante_pago_gcp[0]['COUNT(id_gc_h_p)'];
} else {
    $count_faltante_pago_gcp = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Resultado de Búsqueda de GC Pagada por Proyectos</h1>
                                <h3>Fecha Creación GC: <font style="font-weight:bold" class="text-danger"><?= $newCreated; ?></font>


                                    <?php if ($count_faltante_pago_gcp != 0) { ?>
                                        <h3 class="font-weight-bold float-right">
                                            Hay <font class="text-danger"><?= $count_faltante_pago_gcp; ?></font> Proyecto(s) sin Pagar
                                        </h3>
                                    <?php } ?>
                            </div>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <?php if ($proyect != 0) { ?>

                        <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tablrPagoGCRE', 'GC Pagada por Referidor')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                        <div class="table-responsive col-md-12">
                            <table class="table table-hover table-striped table-bordered" id="tablerPagoGCR" style="cursor: pointer;" width="100%">
                                <thead class="blue-gradient text-white text-center">
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>Proyecto</th>
                                        <th>Nombre Titular</th>
                                        <th>N° Póliza</th>
                                        <th>Fecha Pago</th>
                                        <th>Nº Transf</th>
                                        <th>Banco</th>
                                        <th style="background-color: #E54848; color: white">Monto GC Pagado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalMonto = 0;
                                    for ($i = 0; $i < sizeof($proyect); $i++) {
                                        $newCreated = date("Y/m/d", strtotime($proyect[$i]['f_pago_gc_r']));
                                        $newCreatedH = date("h:i:s a", strtotime($proyect[$i]['created_at']));

                                        $status = ($proyect[$i]['status_c'] == 0) ? 'Sin Pago' : 'Pagado';
                                        $totalMonto = $totalMonto + $proyect[$i]['monto_p'];

                                        $no_renov = $obj->verRenov1($proyect[$i]['id_poliza']);
                                    ?>
                                        <tr>
                                            <td hidden><?= $proyect[$i]['id_poliza']; ?></td>

                                            <?php if ($proyect[$i]['act'] == 0) { ?>
                                                <td style="font-weight: bold;color: #E54848"><?= $proyect[$i]['nombre'] . ' (' . $proyect[$i]['cod'] . ')'; ?></td>
                                            <?php }
                                            if ($proyect[$i]['act'] == 1) { ?>
                                                <td style="font-weight: bold;color: #2B9E34"><?= $proyect[$i]['nombre'] . ' (' . $proyect[$i]['cod'] . ')'; ?></td>
                                            <?php } ?>

                                            <td><?= $proyect[$i]['nombre_t'] . ' ' . $proyect[$i]['apellido_t']; ?></td>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($proyect[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                            <?php } ?>


                                            <td><?= $newCreated; ?></td>
                                            <td><?= $proyect[$i]['n_transf']; ?></td>
                                            <td><?= $proyect[$i]['n_banco']; ?></td>
                                            <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= '$ ' . number_format($proyect[$i]['monto_p'], 2); ?></td>


                                            <?php if ($_SESSION['id_permiso'] == 1 && $proyect[$i]['status_c'] == 0) { ?>
                                                <td style="text-align: center;">
                                                    <a onclick="crearPagoP(<?= $proyect[$i]['id_poliza']; ?>,<?= $proyect[$i]['monto_h']; ?>)" data-toggle="tooltip" data-placement="top" title="Añadir Pago" class="btn blue-gradient btn-rounded btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                                </td>
                                            <?php } else { ?>
                                                <td></td>
                                            <?php } ?>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                                <tfoot class="text-center">
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>Proyecto</th>
                                        <th>Nombre Titular</th>
                                        <th>N° Póliza</th>
                                        <th>Fecha Pago</th>
                                        <th>Nº Transf</th>
                                        <th>Banco</th>
                                        <th>Monto GC Pagado $<?= number_format($totalMonto, 2); ?></th>
                                        <th>Acciones</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <p class="h1 text-center">Total Monto Pagado GC</p>
                        <p class="h1 text-center text-danger">$ <?php echo number_format($totalMonto, 2); ?></p>


                        <div class="table-responsive col-md-12" hidden>
                            <table class="table table-hover table-striped table-bordered" id="tablrPagoGCRE" style="cursor: pointer;" width="100%">
                                <thead class="blue-gradient text-white text-center">
                                    <tr>
                                        <th style="background-color: #4285F4; color: white">Proyecto</th>
                                        <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                                        <th style="background-color: #4285F4; color: white">N° Póliza</th>
                                        <th style="background-color: #4285F4; color: white">Fecha Pago</th>
                                        <th style="background-color: #4285F4; color: white">Nº Transf</th>
                                        <th style="background-color: #4285F4; color: white">Banco</th>
                                        <th style="background-color: #E54848; color: white">Monto GC Pagado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalMonto = 0;
                                    for ($i = 0; $i < sizeof($proyect); $i++) {
                                        $newCreated = date("Y/m/d", strtotime($proyect[$i]['f_pago_gc_r']));
                                        $newCreatedH = date("h:i:s a", strtotime($proyect[$i]['created_at']));

                                        $status = ($proyect[$i]['status_c'] == 0) ? 'Sin Pago' : 'Pagado';
                                        $totalMonto = $totalMonto + $proyect[$i]['monto_p'];

                                        $no_renov = $obj->verRenov1($proyect[$i]['id_poliza']);
                                    ?>
                                        <tr>
                                            <?php if ($proyect[$i]['act'] == 0) { ?>
                                                <td style="font-weight: bold;color: #E54848"><?= $proyect[$i]['nombre'] . ' (' . $proyect[$i]['cod'] . ')'; ?></td>
                                            <?php }
                                            if ($proyect[$i]['act'] == 1) { ?>
                                                <td style="font-weight: bold;color: #2B9E34"><?= $proyect[$i]['nombre'] . ' (' . $proyect[$i]['cod'] . ')'; ?></td>
                                            <?php } ?>

                                            <td><?= $proyect[$i]['nombre_t'] . ' ' . $proyect[$i]['apellido_t']; ?></td>

                                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                                if ($proyect[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                    <td style="color: #2B9E34;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php } else { ?>
                                                    <td style="color: #E54848;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                                <?php }
                                            } else { ?>
                                                <td style="color: #4a148c;font-weight: bold"><?= $proyect[$i]['cod_poliza']; ?></td>
                                            <?php } ?>


                                            <td><?= $newCreated; ?></td>
                                            <td><?= $proyect[$i]['n_transf']; ?></td>
                                            <td><?= $proyect[$i]['n_banco']; ?></td>
                                            <td style="text-align: right;background-color: #D9D9D9;font-weight: bold"><?= '$ ' . number_format($proyect[$i]['monto_p'], 2); ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                                <tfoot class="text-center">
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>Nombre Titular</th>
                                        <th>N° Póliza</th>
                                        <th>Fecha Pago</th>
                                        <th>Nº Transf</th>
                                        <th>Banco</th>
                                        <th>Monto GC Pagado $<?= number_format($totalMonto, 2); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    <?php } else { ?>
                        <div class="col-md-auto col-md-offset-2 text-center">
                            <h2 class="title text-danger">No se encuentran pagos a Proyectos</h2>
                        </div>
                    <?php } ?>


                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <!-- Modal CARGA PAGO-->
        <div class="modal fade" id="cargaPagoP" tabindex="-1" role="dialog" aria-labelledby="cargaPagoP" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cargaPagoP">Cargar Pago del Proyecto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoS" class="md-form">
                            <input type="text" class="form-control" id="id_poliza" name="id_poliza" hidden>
                            <input type="text" class="form-control" id="id_usuarioS" name="id_usuarioS" value="<?= $_SESSION['id_usuario']; ?>" hidden>

                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Nº Transferencia</th>
                                            <th>Banco</th>
                                            <th>Fecha</th>
                                            <th>Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color: white">
                                            <td><input type="text" class="form-control" name="n_transf" id="n_transf"></td>
                                            <td><input type="text" class="form-control" name="n_banco" id="n_banco" onkeyup="mayus(this);"></td>
                                            <td>
                                                <div class="input-group md-form my-n1">
                                                    <input type="text" class="form-control datepicker" id="f_pago_gc_r" name="f_pago_gc_r" required value="<?= date('d-m-Y') ?>">
                                                </div>
                                            </td>
                                            <td><input type="number" class="form-control" name="monto_p" id="monto_p"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn dusty-grass-gradient" id="btnCargaPagoP">Crear</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>
            $(document).ready(function() {
                //Abrir picker en un modal
                var $input = $('.datepicker').pickadate({
                    // Strings and translations
                    monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre',
                        'Noviembre', 'Diciembre'
                    ],
                    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
                    weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado'],
                    weekdaysShort: ['Dom', 'Lun', 'Mart', 'Mierc', 'Jue', 'Vie', 'Sab'],
                    showMonthsShort: undefined,
                    showWeekdaysFull: undefined,

                    // Buttons
                    today: 'Hoy',
                    clear: 'Borrar',
                    close: 'Cerrar',

                    // Accessibility labels
                    labelMonthNext: 'Próximo Mes',
                    labelMonthPrev: 'Mes Anterior',
                    labelMonthSelect: 'Seleccione un Mes',
                    labelYearSelect: 'Seleccione un Año',

                    // Formats
                    dateFormat: 'dd-mm-yyyy',
                    format: 'dd-mm-yyyy',
                    formatSubmit: 'yyyy-mm-dd',
                });
                var picker = $input.pickadate('picker');

                $(window).on('shown.bs.modal', function() {
                    picker.close();
                });
            });
        </script>
</body>

</html>