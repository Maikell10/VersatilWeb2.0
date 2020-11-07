<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'v_reporte_com';

require_once '../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <?php if (isset($_GET['m']) == 2) { ?>
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    <strong>Reporte Subido correctamente en .pdf!</strong>
                    <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <div class="ml-5 mr-5">


                <?php

                $id_rep_com_p = $id_rep_com . "rep.pdf";
                $archivo = './' . $id_rep_com_p;

                //190.140.224.69                    
                $ftp_server = "186.75.241.90";
                $port = 21;
                $ftp_usuario = "usuario";
                $ftp_pass = "20127247";
                $con_id = @ftp_connect($ftp_server, $port) or die("Unable to connect to server.");
                $lr = ftp_login($con_id, $ftp_usuario, $ftp_pass);

                //ftp_pasv($con_id, true);

                if ((!$con_id) || (!$lr)) {
                    echo "no se pudo conectar";
                } else {
                    # Cambiamos al directorio especificado
                    if (ftp_chdir($con_id, '')) {

                        // Obtener los archivos contenidos en el directorio actual
                        $contents = ftp_nlist($con_id, ".");

                        if (in_array($archivo, $contents)) {
                            //echo "<br>";
                            //echo "I found ".$archivo." in directory";
                ?>
                            <a href="download.php?id_rep_com=<?= $id_rep_com; ?>" class="btn cloudy-knoxville-gradient btn-rounded float-right" target="_blank"><img src="../assets/img/pdf-logo.png" width="60" alt=""></a>
                            <br>
                        <?php } ?>
                        <center>
                            <form class="md-form col-md-4" action="save_r.php" method="post" enctype="multipart/form-data">
                                <h5 class="text-center">Seleccione el Reporte pdf a cargar</h5>
                                <br>

                                <div class="file-field big">
                                    <a class="btn-floating btn-lg red lighten-1 mt-0 float-left">
                                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                                        <input type="file" id="archivo" name="archivo" accept="application/pdf" required>
                                    </a>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" placeholder="Eliga un archivo PDF" disabled>
                                    </div>
                                </div>

                                <button class="btn dusty-grass-gradient font-weight-bold btn-rounded">Subir Archivo <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></button>
                                <input type="text" name="id_rep_com" value="<?= $id_rep_com; ?>" hidden>

                            </form>
                        </center>
                <?php ftp_close($con_id);
                    }
                } ?>

                <h1 class="font-weight-bold">Compañía: <?= utf8_encode($cia[0]['nomcia']); ?></h1>
                <hr>
                <center>
                    <a href="add/c_comision.php?id_rep=<?= $id_rep_com; ?>&f_hasta=<?= $f_hasta_rep; ?>&cant_poliza=1&f_pagoGc=<?= $f_pago_gc; ?>&primat_com=<?= $rep_com[0]['primat_com']; ?>&comt=<?= $rep_com[0]['comt']; ?>&cia=<?= $rep_com[0]['id_cia']; ?>&exx=1" target="_blank" data-toggle="tooltip" data-placement="top" title="Añadir Comisión" class="btn blue-gradient btn-lg">Añadir Comisión &nbsp;<i class="fas fa-plus" aria-hidden="true"></i></a>

                    <a href="e_reporte.php?id_rep_com=<?= $id_rep_com; ?>" data-toggle="tooltip" data-placement="top" title="Editar Fechas y Montos Totales" class="btn dusty-grass-gradient btn-lg">Editar Reporte &nbsp;<i class="fas fa-edit" aria-hidden="true"></i></a>


                    <?php if ($_SESSION['id_permiso'] == 1) { ?>
                        <button onclick="eliminarReporte('<?= $id_rep_com; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient btn-lg text-white">Eliminar Reporte &nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                    <?php } ?>

                    <a onclick="crearConciliacion(<?= $id_rep_com; ?>)" data-toggle="tooltip" data-placement="top" title="Añadir Conciliación Bancaria" class="btn morpheus-den-gradient text-white btn-lg">Añadir Conciliación Bancaria &nbsp;<i class="fas fa-plus" aria-hidden="true"></i></a>
                </center>
                <hr>
            </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">

            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableVRepComE', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

            <h4 class="text-danger text-right font-weight-bold" id="diferencia"></h4>

            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>Fecha Hasta Reporte</th>
                        <th>Fecha Pago GC</th>
                        <th>Prima Sujeta a Comisión Total</th>
                        <th>Comisión Total</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $f_hasta_rep; ?></td>
                            <td><?= $f_pago_gc; ?></td>
                            <td class="text-right"><?= number_format($rep_com[0]['primat_com'], 2); ?></td>
                            <td class="text-right"><?= number_format($rep_com[0]['comt'], 2); ?></td>
                        </tr>

                        <tr class="blue-gradient text-white">
                            <th colspan="4">Comentarios</th>
                        </tr>
                        <tr>
                            <td colspan="4"><?= $rep_com[0]['comentario_rep']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%" id="tableVRepCom">
                    <thead class="blue-gradient text-white">
                        <th hidden>id</th>
                        <th>N° de Póliza</th>
                        <th nowrap>Asegurado</th>
                        <th>Fecha Desde Póliza</th>
                        <th>Fecha de Pago de la Prima</th>
                        <th style="background-color: #E54848;">Prima Sujeta a Comisión</th>
                        <th>Comisión</th>
                        <th>% Comisión</th>
                        <th>Asesor - Ejecutivo</th>
                        <th></th>
                        <th hidden>id</th>
                    </thead>

                    <tbody>
                        <?php
                        for ($i = 0; $i < sizeof($comision); $i++) {
                            $totalPrimaCom = $totalPrimaCom + $comision[$i]['prima_com'];
                            $totalCom = $totalCom + $comision[$i]['comision'];

                            $titu = $obj->get_titulat_by_polizaid($comision[$i]['id_poliza']);

                            $newDesde = date("Y/m/d", strtotime($comision[$i]['f_desdepoliza']));
                            $f_pago_prima = date("Y/m/d", strtotime($comision[$i]['f_pago_prima']));

                            $nombre = $titu[0]['nombre_t'] . " " . $titu[0]['apellido_t'];
                            if ($titu[0]['id_titular'] == 0) {
                                $tituprep = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $comision[$i]['id_poliza']);
                                $nombre = $tituprep[0]['asegurado'];
                            }

                            $asesor = $obj->get_ejecutivo_by_cod($comision[$i]['cod_vend']);

                            $no_renov = $obj->verRenov1($comision[$i]['id_poliza']);
                        ?>
                            <tr style="cursor: pointer;">
                                <td hidden><?= $comision[$i]['id_poliza']; ?></td>

                                <?php if ($no_renov[0]['no_renov'] != 1) {
                                    if ($comision[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                        <td style="color: #2B9E34;font-weight: bold"><?= $comision[$i]['num_poliza']; ?></td>
                                    <?php } else { ?>
                                        <td style="color: #E54848;font-weight: bold"><?= $comision[$i]['num_poliza']; ?></td>
                                    <?php }
                                } else { ?>
                                    <td style="color: #4a148c;font-weight: bold"><?= $comision[$i]['num_poliza']; ?></td>
                                <?php } ?>

                                <td nowrap><?= ($nombre); ?></td>
                                <td><?= $newDesde; ?></td>
                                <td><?= $f_pago_prima; ?></td>
                                <td align="right"><?= "$ " . number_format($comision[$i]['prima_com'], 2); ?></td>
                                <td align="right"><?= "$ " . number_format($comision[$i]['comision'], 2); ?></td>
                                <td align="center"><?= number_format(($comision[$i]['comision'] * 100) / $comision[$i]['prima_com'], 2) . " %"; ?></td>
                                <td><?= $asesor[0]['nombre']; ?></td>
                                <td class="text-center"><button onclick="eliminarComision('<?= $comision[$i]['id_comision']; ?>','<?= $_SESSION['id_usuario']; ?>','<?= $comision[$i]['num_poliza']; ?>','<?= $f_hasta_rep; ?>','<?= $cia[0]['nomcia']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-danger btn-sm">&nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></button></td>

                                <td hidden><?= $comision[$i]['id_comision']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                    <tfoot>
                        <tr class="blue-gradient text-white">
                            <th hidden>id</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="right">
                                <font size=4><?= "$ " . number_format($totalPrimaCom, 2); ?></font>
                            </td>
                            <td align="right">
                                <font size=4><?= "$ " . number_format($totalCom, 2); ?></font>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th hidden>id</th>
                        </tr>
                        <tr>
                            <th hidden>id</th>
                            <th>N° de Póliza</th>
                            <th>Asegurado</th>
                            <th>Fecha Desde Póliza</th>
                            <th>Fecha de Pago de la Prima</th>
                            <th>Prima Sujeta a Comisión</th>
                            <th>Comisión</th>
                            <th>% Comisión</th>
                            <th>Asesor - Ejecutivo</th>
                            <th></th>
                            <th hidden>id</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <?php if ($conciliacion != null) { ?>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <th>Fecha de Conciliación</th>
                            <th>Monto Conciliación</th>
                            <th colspan="2">Comentario</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < sizeof($conciliacion); $i++) {
                                $montoCT = $montoCT + $conciliacion[$i]['m_con'];
                            ?>
                                <tr>
                                    <td><?= date("d/m/Y", strtotime($conciliacion[$i]['f_con'])); ?></td>
                                    <td class="text-right"><?= '$ ' . number_format($conciliacion[$i]['m_con'], 2); ?></td>
                                    <td colspan="2"><?= $conciliacion[$i]['comentario_con']; ?></td>
                                    <td class="text-center p-0">
                                        <button onclick="eliminarConciliacion('<?= $conciliacion[$i]['id_conciliacion']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-danger btn-sm">&nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr class="blue lighten-4">
                                <td></td>
                                <td class="text-right h5"><?= '$ ' . number_format($montoCT, 2); ?></td>
                                <td class="text-right h5"><?= 'Comisión Total: $ ' . number_format($totalCom, 2); ?></td>

                                <?php if ( ($totalCom - $montoCT) > 0) { ?>
                                    <td class="text-right h5" style="color: #F53333"><?= 'Dif Conciliación: $ ' . number_format($totalCom - $montoCT, 2); ?></td>
                                <?php } ?>
                                <?php if ( ($totalCom - $montoCT) < 0) { ?>
                                    <td class="text-right h5" style="color: #2B9E34"><?= 'Dif Conciliación: $ ' . number_format($totalCom - $montoCT, 2); ?></td>
                                <?php } ?>
                                <?php if ( ($totalCom - $montoCT) == 0) { ?>
                                    <td class="text-right h5">Dif Conciliación: $ 0.00</td>
                                <?php } ?>

                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>


            <div class="table-responsive-xl" hidden>
                <table class="table table-hover table-striped table-bordered" width="100%" id="tableVRepComE">
                    <thead>
                        <th style="background-color: #4285F4; color: white" colspan="3">Cía</th>
                        <th style="background-color: #4285F4; color: white">Fecha Hasta Reporte</th>
                        <th style="background-color: #4285F4; color: white">Fecha Pago GC</th>
                        <th style="background-color: #4285F4; color: white">Prima Sujeta a Comisión Total</th>
                        <th style="background-color: #4285F4; color: white" colspan="2">Comisión Total</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3"><?= $cia[0]['nomcia']; ?></td>
                            <td><?= $f_hasta_rep; ?></td>
                            <td><?= $f_pago_gc; ?></td>
                            <td style="text-align: right"><?= number_format($rep_com[0]['primat_com'], 2); ?></td>
                            <td style="text-align: right" colspan="2"><?= number_format($rep_com[0]['comt'], 2); ?></td>
                        </tr>
                        <tr>
                            <th style="background-color: #4285F4; color: white">N° de Póliza</th>
                            <th style="background-color: #4285F4; color: white">Asegurado</th>
                            <th style="background-color: #4285F4; color: white">Fecha Desde Póliza</th>
                            <th style="background-color: #4285F4; color: white">Fecha de Pago de la Prima</th>
                            <th style="background-color: #E54848; color: white">Prima Sujeta a Comisión</th>
                            <th style="background-color: #4285F4; color: white">Comisión</th>
                            <th style="background-color: #4285F4; color: white">% Comisión</th>
                            <th style="background-color: #4285F4; color: white">Asesor - Ejecutivo</th>
                        </tr>


                        <?php
                        $totalPrimaCom = 0;
                        $totalCom = 0;
                        for ($i = 0; $i < sizeof($comision); $i++) {
                            $totalPrimaCom = $totalPrimaCom + $comision[$i]['prima_com'];
                            $totalCom = $totalCom + $comision[$i]['comision'];

                            $titu = $obj->get_titulat_by_polizaid($comision[$i]['id_poliza']);

                            $newDesde = date("Y/m/d", strtotime($comision[$i]['f_desdepoliza']));
                            $f_pago_prima = date("d-m-Y", strtotime($comision[$i]['f_pago_prima']));

                            $nombre = $titu[0]['nombre_t'] . " " . $titu[0]['apellido_t'];
                            if ($titu[0]['id_titular'] == 0) {
                                $tituprep = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $comision[$i]['id_poliza']);
                                $nombre = $tituprep[0]['asegurado'];
                            }

                            $asesor = $obj->get_ejecutivo_by_cod($comision[$i]['cod_vend']);

                            $no_renov = $obj->verRenov1($comision[$i]['id_poliza']);
                        ?>
                            <tr style="cursor: pointer;">

                                <?php if ($no_renov[0]['no_renov'] != 1) {
                                    if ($comision[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                        <td style="color: #2B9E34;font-weight: bold"><?= $comision[$i]['num_poliza']; ?></td>
                                    <?php } else { ?>
                                        <td style="color: #E54848;font-weight: bold"><?= $comision[$i]['num_poliza']; ?></td>
                                    <?php }
                                } else { ?>
                                    <td style="color: #4a148c;font-weight: bold"><?= $comision[$i]['num_poliza']; ?></td>
                                <?php } ?>

                                <td nowrap><?= ($nombre); ?></td>
                                <td><?= $newDesde; ?></td>
                                <td><?= $f_pago_prima; ?></td>
                                <td align="right"><?= "$ " . number_format($comision[$i]['prima_com'], 2); ?></td>
                                <td align="right"><?= "$ " . number_format($comision[$i]['comision'], 2); ?></td>
                                <td align="center"><?= number_format(($comision[$i]['comision'] * 100) / $comision[$i]['prima_com'], 2) . " %"; ?></td>
                                <td><?= $asesor[0]['nombre']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td style="background-color: #4285F4; color: white"></td>
                            <td style="background-color: #4285F4; color: white"></td>
                            <td style="background-color: #4285F4; color: white"></td>
                            <td style="background-color: #4285F4; color: white"></td>
                            <td style="background-color: #4285F4; color: white; text-align: right">
                                <font size=4><?= "$ " . number_format($totalPrimaCom, 2); ?></font>
                            </td>
                            <td style="background-color: #4285F4; color: white; text-align: right">
                                <font size=4><?= "$ " . number_format($totalCom, 2); ?></font>
                            </td>
                            <td style="background-color: #4285F4; color: white"></td>
                            <td style="background-color: #4285F4; color: white"></td>
                        </tr>
                        <tr>
                            <th>N° de Póliza</th>
                            <th>Asegurado</th>
                            <th>Fecha Desde Póliza</th>
                            <th>Fecha de Pago de la Prima</th>
                            <th>Prima Sujeta a Comisión</th>
                            <th>Comisión</th>
                            <th>% Comisión</th>
                            <th>Asesor - Ejecutivo</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>


    </div>



    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

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
    <script src="../assets/view/modalE.js"></script>

    <script>
        var difprima = <?= $rep_com[0]['primat_com'] - $totalPrimaCom; ?>;
        var difcomision = <?= $rep_com[0]['comt'] - $totalCom; ?>;

        if (difprima > 1 || difprima < -1) {
            $('#diferencia').text('"Hay una diferencia en la prima"');
        }
        if (difcomision > 1 || difcomision < -1) {
            $('#diferencia').text('"Hay una diferencia en la comision"');
        }
        if ((difprima > 1 || difprima < -1) && (difcomision > 1 || difcomision < -1)) {
            $('#diferencia').text('"Hay una diferencia en la prima y la comisión"');
        }

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