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

$cia = $obj->get_distinc_c_rep_com_by_date($desde, $hasta);
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
                            <div class="row ml-5 mr-5">
                                <h1 class="font-weight-bold ">Resultado de Búsqueda de Reporte de Comisiones por Cía</h1>
                            </div>

                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableRepCF" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th>Nombre de Compañía</th>
                                    <th hidden="">ID</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th>Comisión Cobrada</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($cia); $i++) {
                                    $primap = 0;
                                    $poliza = $obj->get_poliza_total_by_num_by_date($cia[$i]['id_cia'], $desde, $hasta);
                                    for ($c = 0; $c < sizeof($poliza); $c++) {
                                        $primap = $primap + $poliza[0]['prima'];
                                    }

                                    $reporte1 = $obj->get_reporte_by_date($cia[$i]['id_cia'], $desde, $hasta);
                                    $prima = 0;
                                    $comi = 0;
                                    for ($a = 0; $a < sizeof($reporte1); $a++) {
                                        $reporte = $obj->get_element_by_id('comision', 'id_rep_com', $reporte1[$a]['id_rep_com']);
                                        for ($b = 0; $b < sizeof($reporte); $b++) {
                                            $prima = $prima + $reporte[$b]['prima_com'];
                                            $comi = $comi + $reporte[$b]['comision'];
                                            $totalPrimaCom = $totalPrimaCom + $reporte[$b]['prima_com'];
                                            $totalCom = $totalCom + $reporte[$b]['comision'];
                                        }
                                    }


                                ?>
                                    <tr style="cursor: pointer">
                                        <td><?= ($cia[$i]['nomcia']); ?></td>
                                        <td hidden><?= $cia[$i]['id_cia']; ?></td>
                                        <td><?= number_format($primap, 2); ?></td>
                                        <td><?= number_format($prima, 2); ?></td>
                                        <td><?= "$ " . number_format($comi, 2); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th>Nombre de Compañía</th>
                                    <th hidden="">ID</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada <?= "$ " . number_format($totalPrimaCom, 2); ?></th>
                                    <th>Comisión Cobrada <?= "$ " . number_format($totalCom, 2); ?></th>
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

        <script src="../assets/view/b_poliza.js"></script>

        <script>
            $("#tableRepCF tbody tr").dblclick(function() {
                var customerId = $(this).find("td").eq(1).html();

                window.location.href = "b_reportes1.php?anio=<?= $_GET['anio'];?>&mes=<?= $_GET['mes'];?>&cia=" + customerId;
            });
        </script>
</body>

</html>