<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

//$pag = 'b_reportes';

require_once '../../Controller/Poliza.php';

$ref = $obj->get_gc_h_r();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
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
                                <h1 class="font-weight-bold">Cargar pago a Referidores</h1>
                            </div>
                            <br><br><br>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden>

                    <?php if ($ref != 0) { ?>
                        <div class="table-responsive col-md-10 offset-1">
                            <table class="table table-hover table-striped table-bordered" id="tablrPagoGCR" style="cursor: pointer;">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>N° Póliza</th>
                                        <th>Referidor</th>
                                        <th>Monto GC</th>
                                        <th>Fecha Creación</th>
                                        <th>Status</th>
                                        <th>Cargar Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < sizeof($ref); $i++) {
                                        $newCreated = date("d/m/Y", strtotime($ref[$i]['created_at']));
                                        $newCreatedH = date("h:i:s a", strtotime($ref[$i]['created_at']));

                                        $status = ($ref[$i]['status_c'] == 0) ? 'Sin Registro' : 'Registrado';
                                    ?>
                                        <tr>
                                            <td hidden><?= $ref[$i]['id_poliza']; ?></td>
                                            <td><?= $ref[$i]['cod_poliza']; ?></td>
                                            <td><?= $ref[$i]['nombre']; ?></td>
                                            <td class="text-right"><?= '$ ' . $ref[$i]['monto']; ?></td>
                                            <td><?= $newCreated . " " . $newCreatedH; ?></td>
                                            <td><?= $status; ?></td>
                                            <td class="text-center">
                                                <a onclick="crearPago(<?= $ref[$i]['id_gc_h_r']; ?>)" data-toggle="tooltip" data-placement="top" title="Cargar Pago" class="btn blue-gradient btn-rounded btn-sm"><i class="fas fa-money-check-alt" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th hidden>Id Póliza</th>
                                        <th>N° Póliza</th>
                                        <th>Referidor</th>
                                        <th>Monto GC</th>
                                        <th>Fecha Creación</th>
                                        <th>Status</th>
                                        <th>Cargar Pago</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-auto col-md-offset-2 text-center">
                            <h2 class="title text-danger">No se encuentran pagos a Referidores pendientes</h2>
                        </div>
                    <?php } ?>

                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <!-- Modal CARGA PAGO-->
        <div class="modal fade" id="cargaPago" tabindex="-1" role="dialog" aria-labelledby="cargaPago" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cargaPago">Cargar Pago del Referidor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoS" class="md-form">
                            <input type="text" class="form-control" id="id_gc_h_r" name="id_gc_h_r" hidden>
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
                                                    <input type="text" class="form-control datepicker" id="f_pago_gc_r" name="f_pago_gc_r" required>
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
                        <button type="button" class="btn dusty-grass-gradient" id="btnCargaPago">Crear</button>
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
                    monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Augosto', 'Septiembre', 'Octubre',
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

            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

</body>

</html>