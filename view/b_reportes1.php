<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'b_reportes1';

require_once '../Controller/Poliza.php';

isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';
if($cia != '') {
    $cia = $obj->get_element_by_id('dcia', 'idcia', $cia);
    $cia = $cia[0]['nomcia'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
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
                                <h1 class="font-weight-bold ">Resultado de Búsqueda de Reporte de Comisiones</h1>
                                <h3 class="font-weight-bold">
                                Año: <span class="text-danger"><?= $_GET['anio']; ?></span>
                                    <?php if ($mes != null) { ?>
                                        Mes: <span class="text-danger"><?= $mes_arr[$_GET['mes'] - 1]; ?></span>
                                    <?php } ?>
                                </h3>
                                <?php if ($cia != '') { ?>
                                    <h3 class="font-weight-bold">
                                    Cía: <span class="text-danger">
                                        <?= $cia; ?>
                                    </span>
                                    </h3>
                                <?php } ?>
                            </div>

                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRepE', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableRep" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th hidden>ocultar</th>
                                    <th hidden>ocultar</th>
                                    <th>Fecha Hasta Reporte</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>Compañía</th>
                                    <th>Fecha Pago de la GC</th>
                                    <th>Dif Conciliación</th>
                                    <th>PDF</th>
                                    <th>Conciliación Bancaria</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($rep_com_busq); $i++) {
                                    $prima = 0;
                                    $comi = 0;
                                    $totalConcil = 0;
                                    $dif = 0;

                                    $reporte_c = $obj->get_element_by_id('comision', 'id_rep_com', $rep_com_busq[$i]['id_rep_com']);

                                    for ($a = 0; $a < sizeof($reporte_c); $a++) {
                                        $prima = $prima + $reporte_c[$a]['prima_com'];
                                        $comi = $comi + $reporte_c[$a]['comision'];
                                        $totalPrimaCom = $totalPrimaCom + $reporte_c[$a]['prima_com'];
                                        $totalCom = $totalCom + $reporte_c[$a]['comision'];
                                    }

                                    $f_pago_gc = date("Y/m/d", strtotime($rep_com_busq[$i]['f_pago_gc']));
                                    $f_hasta_rep = date("Y/m/d", strtotime($rep_com_busq[$i]['f_hasta_rep']));

                                    $conciliacion = $obj->get_element_by_id('conciliacion', 'id_rep_com', $rep_com_busq[$i]['id_rep_com']);
                                    $cantConciliacion = ($conciliacion == 0) ? 0 : sizeof($conciliacion) ;

                                    for ($a = 0; $a < $cantConciliacion; $a++) {
                                        $totalConcil = $totalConcil + $conciliacion[$a]['m_con'];
                                    }

                                    $dif = $comi - $totalConcil;
                                    $dif = (($dif > 1 || $dif < -1) && ($dif != $comi)) ? number_format($dif, 2) : 0;

                                ?>
                                    <tr style="cursor: pointer">
                                        <td hidden=""><?= $rep_com_busq[$i]['f_hasta_rep']; ?></td>
                                        <td hidden=""><?= $rep_com_busq[$i]['id_rep_com']; ?></td>
                                        <td><?= $f_hasta_rep; ?></td>
                                        <td align="right"><?= "$ " . number_format($prima, 2); ?></td>
                                        <td align="right"><?= "$ " . number_format($comi, 2); ?></td>
                                        <td nowrap><?= ($rep_com_busq[$i]['nomcia']); ?></td>
                                        <td><?= $f_pago_gc; ?></td>
                                        
                                        <?php if ($totalConcil > 0) {
                                            if ($dif == 0) { ?>
                                                <td style="text-align: right; font-weight: bold">$ 0.00</td>
                                            <?php }
                                            if ($dif > 0) { ?>
                                                <td style="text-align: right; color: #F53333; font-weight: bold;font-size: 15px;"><?= '$ ' . $dif; ?></td>
                                            <?php }
                                            if ($dif < 0) { ?>
                                                <td style="text-align: right; color: #2B9E34; font-weight: bold;font-size: 15px;"><?= '$ ' . $dif; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="text-align: right; font-weight: bold"></td>
                                        <?php } ?>
                                        
                                        <td class="text-center">
                                            <?php
                                            if ($rep_com_busq[$i]['pdf'] == 1) {

                                            ?>
                                                <a href="download.php?id_rep_com=<?= $rep_com_busq[$i]['id_rep_com']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a>
                                            <?php
                                            } else {
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a onclick="crearConciliacion(<?= $rep_com_busq[$i]['id_rep_com']; ?>)" data-toggle="tooltip" data-placement="top" title="Añadir Conciliación Bancaria" class="btn blue-gradient btn-rounded btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th hidden="">ocultar</th>
                                    <th hidden="">ocultar</th>
                                    <th>Fecha Hasta Reporte</th>
                                    <th>Prima Cobrada <?= "$ " . number_format($totalPrimaCom, 2); ?></th>
                                    <th>Comisión Cobrada <?= "$ " . number_format($totalCom, 2); ?></th>
                                    <th>Compañía</th>
                                    <th>Fecha Pago de la GC</th>
                                    <th>Dif Conciliación</th>
                                    <th>PDF</th>
                                    <th>Conciliación Bancaria</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="table-responsive-xl" hidden>
                        <table class="table table-hover table-striped table-bordered" id="tableRepE" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th style="background-color: #4285F4; color: white">Fecha Hasta Reporte</th>
                                    <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                    <th style="background-color: #4285F4; color: white">Comisión Cobrada</th>
                                    <th style="background-color: #4285F4; color: white">Compañía</th>
                                    <th style="background-color: #4285F4; color: white">Fecha Pago de la GC</th>
                                    <th style="background-color: #4285F4; color: white">Dif Conciliación</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $totalPrimaCom = 0;
                                $totalCom = 0;
                                for ($i = 0; $i < sizeof($rep_com_busq); $i++) {
                                    $prima = 0;
                                    $comi = 0;
                                    $totalConcil = 0;
                                    $dif = 0;

                                    $reporte_c = $obj->get_element_by_id('comision', 'id_rep_com', $rep_com_busq[$i]['id_rep_com']);

                                    for ($a = 0; $a < sizeof($reporte_c); $a++) {
                                        $prima = $prima + $reporte_c[$a]['prima_com'];
                                        $comi = $comi + $reporte_c[$a]['comision'];
                                        $totalPrimaCom = $totalPrimaCom + $reporte_c[$a]['prima_com'];
                                        $totalCom = $totalCom + $reporte_c[$a]['comision'];
                                    }

                                    $f_pago_gc = date("Y/m/d", strtotime($rep_com_busq[$i]['f_pago_gc']));
                                    $f_hasta_rep = date("Y/m/d", strtotime($rep_com_busq[$i]['f_hasta_rep']));

                                    $conciliacion = $obj->get_element_by_id('conciliacion', 'id_rep_com', $rep_com_busq[$i]['id_rep_com']);
                                    $cantConciliacion = ($conciliacion == 0) ? 0 : sizeof($conciliacion) ;

                                    for ($a = 0; $a < $cantConciliacion; $a++) {
                                        $totalConcil = $totalConcil + $conciliacion[$a]['m_con'];
                                    }

                                    $dif = $comi - $totalConcil;
                                    $dif = (($dif > 1 || $dif < -1) && ($dif != $comi)) ? number_format($dif, 2) : 0;

                                ?>
                                    <tr style="cursor: pointer">
                                        <td><?= $f_hasta_rep; ?></td>
                                        <td align="right"><?= "$ " . number_format($prima, 2); ?></td>
                                        <td align="right"><?= "$ " . number_format($comi, 2); ?></td>
                                        <td nowrap><?= ($rep_com_busq[$i]['nomcia']); ?></td>
                                        <td><?= $f_pago_gc; ?></td>
                                        
                                        <?php if ($totalConcil > 0) {
                                            if ($dif == 0) { ?>
                                                <td style="text-align: right; font-weight: bold">$ 0.00</td>
                                            <?php }
                                            if ($dif > 0) { ?>
                                                <td style="text-align: right; color: #F53333; font-weight: bold;font-size: 15px;"><?= '$ ' . $dif; ?></td>
                                            <?php }
                                            if ($dif < 0) { ?>
                                                <td style="text-align: right; color: #2B9E34; font-weight: bold;font-size: 15px;"><?= '$ ' . $dif; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="text-align: right; font-weight: bold"></td>
                                        <?php } ?>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th>Fecha Hasta Reporte</th>
                                    <th>Prima Cobrada <?= "$ " . number_format($totalPrimaCom, 2); ?></th>
                                    <th>Comisión Cobrada <?= "$ " . number_format($totalCom, 2); ?></th>
                                    <th>Compañía</th>
                                    <th>Fecha Pago de la GC</th>
                                    <th>Dif Conciliación</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

        <!-- Modal CONCILIACION -->
        <div class="modal fade" id="agregarconciliacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Añadir Conciliación Bancaria</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoC" autocomplete="off">

                            <div class="form-row">
                                <table class="table table-hover table-striped table-bordered" id="iddatatable">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Fecha de Conciliación *</th>
                                            <th>Monto Conciliación *</th>
                                            <th>Comentario</th>
                                            <th hidden>id_rep</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <div class="form-group col-md-12">
                                            <tr style="background-color: white">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control datepicker" id="fc_new" name="fc_new" required />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="number" class="form-control" id="mc_new" name="mc_new" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="coment_new" name="coment_new" onkeyup="mayus(this);" />
                                                    </div>
                                                </td>
                                                <td hidden>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="id_reporte" name="id_reporte">
                                                    </div>
                                                </td>
                                            </tr>
                                        </div>
                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnAgregarcon" class="btn aqua-gradient">Agregar Conciliación</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../assets/view/b_poliza.js"></script>

        <script>
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
        </script>
</body>

</html>