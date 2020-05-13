<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'b_poliza1';

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
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Resultado de Búsqueda de Póliza</h1>
                                <?php
                                if ($anio == '') {
                                } else {
                                    $anioIn = "" . implode(",", $anio) . ""; ?>
                                    <h3>Año: <font style="font-weight:bold"><?= $anioIn; ?></font>
                                    </h3>
                                <?php }
                                if ($mes == null) {
                                } else {
                                ?>
                                    <h3>Mes: <font style="font-weight:bold">
                                        <?php for ($i = 0; $i < sizeof($mes); $i++) {
                                            echo $mes_arr[$mes[$i] - 1] . " - ";
                                        }
                                    }
                                        ?>
                                        </font>
                                    </h3>
                                    <?php
                                    if ($cia == '') {
                                    } else {
                                        $ciaIn = "" . implode(",", $cia) . "";
                                    ?>
                                        <h3>Cía: <font style="font-weight:bold"><?= $ciaIn; ?></font>
                                        <?php
                                    }
                                    if ($asesor == '') {
                                    } else {
                                        ?>
                                            Asesor: <font style="font-weight:bold"><?= $myString; ?></font>
                                        </h3>
                                    <?php
                                    }
                                    if ($ramo == '') {
                                    } else {
                                        $ramoIn = "" . implode(",", $ramo) . "";
                                    ?>
                                        <h3>Ramo: <font style="font-weight:bold"><?= $ramoIn; ?></font>
                                        </h3>
                                    <?php } ?>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">



                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="background-color: #E54848;">Prima Suscrita</th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                foreach ($polizas as $poliza) {
                                    $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                                    $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                    $totalprima = $totalprima + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                                    $no_renov = $obj->verRenov1($poliza['id_poliza']);
                                ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $poliza['f_poliza']; ?></td>
                                        <td hidden><?= $poliza['id_poliza']; ?></td>

                                        <?php if ($no_renov[0]['no_renov'] != 1) {
                                            if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td><?= $poliza['nombre']; ?></td>
                                        <td><?= $poliza['nomcia']; ?></td>
                                        <td><?= $newDesde; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                        <td><?= ($nombre); ?></td>
                                        <?php if ($poliza['pdf'] == 1) { ?>
                                            <td><a href="download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank" style="float: right"><img src="../assets/img/pdf-logo.png" width="30" id="pdf"></a></td>
                                        <?php } else { ?>
                                            <td></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <p class="h1 text-center">Total de Prima Suscrita</p>
                    <p class="h1 text-center text-danger">$ <?php echo number_format($totalprima, 2); ?></p>

                    <p class="h1 text-center">Total de Pólizas</p>
                    <p class="h1 text-center text-danger"><?php echo sizeof($polizas); ?></p>
                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) { ?>

                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-tooltip="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Resultado de Búsqueda de Póliza</h1>
                                <?php
                                if ($anio == '') {
                                } else {
                                    $anioIn = "" . implode(",", $anio) . ""; ?>
                                    <h3>Año: <font style="font-weight:bold"><?= $anioIn; ?></font>
                                    <?php }
                                if ($mes == null) {
                                } else {
                                    ?></font>
                                        Mes: <font style="font-weight:bold">
                                        <?php for ($i = 0; $i < sizeof($mes); $i++) {
                                            echo $mes_arr[$mes[$i] - 1] . " - ";
                                        }
                                    }
                                        ?>
                                        </font>
                                    </h3>
                                    <?php
                                    if ($cia == '') {
                                    } else {
                                        $ciaIn = "" . implode(",", $cia) . "";
                                    ?>
                                        <h3>Cía: <font style="font-weight:bold"><?= $ciaIn; ?></font>
                                        <?php
                                    }
                                    if ($asesor == '') {
                                    } else {
                                        ?>
                                            Asesor: <font style="font-weight:bold"><?= $myString; ?></font>
                                        </h3>
                                    <?php
                                    }
                                    if ($ramo == '') {
                                    } else {
                                        $ramoIn = "" . implode(",", $ramo) . "";
                                    ?>
                                        <h3>Ramo: <font style="font-weight:bold"><?= $ramoIn; ?></font>
                                        </h3>
                                    <?php } ?>
                            </div>
                </div>

                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                    <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('table', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="background-color: #E54848;">Prima Suscrita</th>
                                    <th nowrap>Nombre Titular</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($polizas as $poliza) {
                                    $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                                    $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                    $totalprima = $totalprima + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];
                                ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $poliza['f_poliza']; ?></td>
                                        <td hidden><?= $poliza['id_poliza']; ?></td>

                                        <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                            <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <?php } else { ?>
                                            <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <?php } ?>
                                        <td><?= $poliza['nombre']; ?></td>
                                        <td><?= $poliza['nomcia']; ?></td>
                                        <td><?= $newDesde; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                        <td><?= ($nombre); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th>Nombre Titular</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <p class="h1 text-center">Total de Prima Suscrita</p>
                    <p class="h1 text-center text-danger">$ <?php echo number_format($totalprima, 2); ?></p>

                    <p class="h1 text-center">Total de Pólizas</p>
                    <p class="h1 text-center text-danger"><?php echo sizeof($polizas); ?></p>

                </div>


            <?php } ?>


        </div>





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>