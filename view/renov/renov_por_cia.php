<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'renov/renov_por_cia';

require_once '../../Controller/Poliza.php';
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
                            <h1 class="font-weight-bold ">Resultado de Búsqueda de Póliza a Renovar por Cía</h1>
                            <h2>Año: <font style="font-weight:bold">
                                    <?= $_POST['anio'];
                                    if ($_POST['mes'] != null) { ?></font>
                                Mes: <font style="font-weight:bold">
                                <?= $mes_arr[$_POST['mes'] - 1];
                                    } ?></font>
                            </h2>

                            <?php if ($asesor != '') { ?>
                                <h2>Asesor: <font style="font-weight:bold"><?= $myString; ?></font>
                                </h2>
                            <?php } ?>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenovCia', 'Pólizas a Renovar por Cía')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovCia" width="100%" style="cursor: pointer;">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>Cía</th>
                                <th>N° Póliza</th>
                                <th>F Hasta Seguro</th>
                                <th>Nombre Titular</th>
                                <th>Ramo</th>
                                <th hidden>id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($a = 0; $a < sizeof($distinct_c); $a++) {
                                $poliza = $obj->get_poliza_total_by_filtro_renov_c($desde, $hasta, $distinct_c[$a]['nomcia'], $asesor);
                            ?>
                                <tr>
                                    <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9"><?= $distinct_c[$a]['nomcia']; ?></td>

                                    <?php
                                    for ($i = 0; $i < sizeof($poliza); $i++) {
                                        $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                                        $totalprima = $totalprima + $poliza[$i]['prima'];

                                        $newHasta = date("d/m/Y", strtotime($poliza[$i]['f_hastapoliza']));

                                        $currency = ($poliza[$i]['currency'] == 1) ? "$ " : "Bs ";

                                        if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                                    ?>
                                            <td style="color: #2B9E34;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php } else { ?>
                                            <td style="color: #E54848;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td><?= $newHasta; ?></td>
                                        <td nowrap><?= ($poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t']); ?></td>
                                        <td nowrap><?= ($poliza[$i]['nramo']); ?></td>
                                        <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="no-tocar">
                                <td colspan="5" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $distinct_c[$a]['nomcia']; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                                </td>
                            </tr>
                        <?php $totalpoliza = $totalpoliza + sizeof($poliza);
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Cía</th>
                                <th>N° Póliza</th>
                                <th>F Hasta Seguro</th>
                                <th>Nombre Titular</th>
                                <th>Ramo</th>
                                <th hidden>id</th>
                            </tr>
                        </tfoot>
                    </table>

                    <h1 class="text-center font-weight-bold">Total de Prima Suscrita</h1>
                    <h1 class="text-center font-weight-bold text-danger">$ <?php echo number_format($totalprima, 2); ?></h1>

                    <h1 class="text-center font-weight-bold">Total de Pólizas</h1>
                    <h1 class="text-center font-weight-bold text-danger"><?php echo $totalpoliza; ?></h1>
                </div>

            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>