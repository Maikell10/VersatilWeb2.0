<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'b_reportes';

require_once '../Controller/Poliza.php';

$cia = $obj->get_distinc_c_rep_com();
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
                                <h1 class="font-weight-bold ">Lista de Reporte de Comisiones por Cía</h1>
                            </div>

                            <div class="col-md-8 mx-auto">
                                <form action="b_reportes_cia1.php" class="form-horizontal" method="GET">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label align="left">Año Reporte Pago GC:</label>
                                            <select class="form-control selectpicker" name="anio" id="anio" data-style="btn-white" data-size="13" data-header="Seleccione Año">
                                                <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                    <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                                <?php $fecha_min = $fecha_min + 1;
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Mes Reporte Pago GC:</label>
                                            <select class="form-control selectpicker" name="mes" id="mes" data-style="btn-white" data-header="Seleccione Mes">
                                                <option value="">Seleccione Mes</option>
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg">Buscar</button></center>
                                </form>

                                <div id="load" class="d-flex justify-content-center align-items-center" hidden>
                                    <div class="spinner-grow text-info" style="width: 9rem; height: 9rem;" id="load1" hidden></div>
                                </div>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableRepC" width="100%">
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
                                    $poliza = $obj->get_poliza_total_by_num($cia[$i]['id_cia']);
                                    for ($c = 0; $c < sizeof($poliza); $c++) {
                                        $primap = $primap + $poliza[0]['prima'];
                                    }

                                    $reporte1 = $obj->get_element_by_id('rep_com', 'id_cia', $cia[$i]['id_cia']);
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





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>