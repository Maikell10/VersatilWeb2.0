<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'b_reportes1';

require_once '../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
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
                            <div class="row ml-5 mr-5">
                                <h1 class="font-weight-bold ">Resultado de Búsqueda de Reporte de Comisiones</h1>
                            </div>

                            <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRep', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableRep" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th hidden>ocultar</th>
                                    <th hidden>ocultar</th>
                                    <th>Fecha Hasta Reporte</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                    <th>Compañía</th>
                                    <th>Fecha Pago de la GC</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($rep_com_busq); $i++) {
                                    $prima = 0;
                                    $comi = 0;

                                    $reporte_c = $obj->get_element_by_id('comision', 'id_rep_com', $rep_com_busq[$i]['id_rep_com']);

                                    for ($a = 0; $a < sizeof($reporte_c); $a++) {
                                        $prima = $prima + $reporte_c[$a]['prima_com'];
                                        $comi = $comi + $reporte_c[$a]['comision'];
                                        $totalPrimaCom = $totalPrimaCom + $reporte_c[$a]['prima_com'];
                                        $totalCom = $totalCom + $reporte_c[$a]['comision'];
                                    }

                                    $f_pago_gc = date("Y/m/d", strtotime($rep_com_busq[$i]['f_pago_gc']));
                                    $f_hasta_rep = date("Y/m/d", strtotime($rep_com_busq[$i]['f_hasta_rep']));

                                ?>
                                    <tr style="cursor: pointer">
                                        <td hidden=""><?= $rep_com_busq[$i]['f_hasta_rep']; ?></td>
                                        <td hidden=""><?= $rep_com_busq[$i]['id_rep_com']; ?></td>
                                        <td><?= $f_hasta_rep; ?></td>
                                        <td align="right"><?= "$ " . number_format($prima, 2); ?></td>
                                        <td align="right"><?= "$ " . number_format($comi, 2); ?></td>
                                        <td nowrap><?= ($rep_com_busq[$i]['nomcia']); ?></td>
                                        <td><?= $f_pago_gc; ?></td>
                                        <td class="text-center">
                                            <?php
                                            if ($reporte[$i]['pdf'] == 1) {

                                            ?>
                                                <a href="download.php?id_rep_com=<?= $reporte[$i]['id_rep_com']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="30" id="pdf"></a>
                                            <?php
                                            } else {
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th hidden="">ocultar</th>
                                    <th hidden="">ocultar</th>
                                    <th>Fecha Hasta Reporte</th>
                                    <th>Prima Cobrada <?= "$ " . number_format($totalPrimaCom, 2); ?></th>
                                    <th>Comisión Cobrada <?= "$ " . number_format($totalCom, 2); ?></th>
                                    <th>Compañía</th>
                                    <th>Fecha Pago de la GC</th>
                                    <th>PDF</th>
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





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>