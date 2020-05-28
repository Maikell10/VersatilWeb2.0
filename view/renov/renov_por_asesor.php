<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'renov/renov_por_asesor';

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
                            <h1 class="font-weight-bold ">Resultado de Búsqueda de Póliza a Renovar por Asesor</h1>
                            <h2>Año: <font style="font-weight:bold">
                                    <?= $_POST['anio'];
                                    if ($_POST['mes'] != null) { ?></font>
                                Mes: <font style="font-weight:bold">
                                <?= $mes_arr[$_POST['mes'] - 1];
                                    } ?></font>
                            </h2>

                            <?php if ($cia != '') {
                                $ciaIn = "" . implode(",", $cia) . "";
                            ?>
                                <h2>Cia: <font style="font-weight:bold"><?= $ciaIn; ?></font>
                                </h2>
                            <?php } ?>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenovA', 'Pólizas a Renovar por Cía')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovA" width="100%" style="cursor: pointer;">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th>Asesor</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>Ramo</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th>PDF</th>
                                <th>Status</th>
                                <th hidden>id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($a = 0; $a < sizeof($distinct_a); $a++) {
                                $poliza = $obj->get_poliza_total_by_filtro_renov_a($desde, $hasta, $cia, $distinct_a[$a]['codvend']);

                                $nombre = $distinct_a[$a]['nombre'];
                            ?>
                                <tr>
                                    <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9"><?= $nombre; ?></td>

                                    <?php
                                    for ($i = 0; $i < sizeof($poliza); $i++) {
                                        $vRenov = $obj->verRenov($poliza[$i]['id_poliza']);

                                        $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                                        $totalprima = $totalprima + $poliza[$i]['prima'];

                                        $newDesde = date("d/m/Y", strtotime($poliza[$i]['f_desdepoliza']));
                                        $newHasta = date("d/m/Y", strtotime($poliza[$i]['f_hastapoliza']));

                                        $currency = ($poliza[$i]['currency'] == 1) ? "$ " : "Bs ";

                                        $seguimiento = $obj->seguimiento($poliza[$i]['id_poliza']);

                                        if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                                    ?>
                                            <td style="color: #2B9E34;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="color: #E54848;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td nowrap><?= ($poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t']); ?></td>
                                        <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>
                                        <td nowrap><?= ($poliza[$i]['nramo']); ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <?php if ($poliza[$i]['pdf'] == 1) { ?>
                                            <td><a href="../download.php?id_poliza=<?= $poliza[$i]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank" style="float: right"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                        <?php } else { ?>
                                            <td></td>
                                        <?php } ?>
                                        <td nowrap>
                                            <?php if ($poliza[$i]['f_hastapoliza'] <= date("Y-m-d")) {
                                                if ($vRenov == 0) {
                                                    if ($seguimiento == 0) {
                                            ?>
                                                        <a href="../v_poliza.php?id_poliza=<?= $poliza[$i]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="En Proceso" class="btn blue-gradient btn-rounded btn-sm btn-block">En Proceso</a>
                                                        <?php
                                                    } else {
                                                        $poliza_renov = $obj->comprobar_poliza($poliza[$i]['cod_poliza'], $poliza[$i]['id_cia']);
                                                        if (sizeof($poliza_renov) != 0) {
                                                        ?>
                                                            <a href="../v_poliza.php?id_poliza=<?= $poliza_renov[0]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Renovada" class="btn aqua-gradient btn-rounded btn-sm btn-block">Renovada A</a>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <a href="../v_poliza.php?modal=true&id_poliza=<?= $poliza[$i]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="En Seguimiento" class="btn morpheus-den-gradient text-white btn-rounded btn-sm btn-block">En Seguimiento</a>
                                                        <?php
                                                        }
                                                        ?>

                                                    <?php
                                                    }
                                                } else {
                                                    if ($vRenov[0]['no_renov'] == 0) { ?>
                                                        <a href="../v_poliza.php?id_poliza=<?= $vRenov[0]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Renovada" class="btn aqua-gradient btn-rounded btn-sm btn-block">Renovada</a>
                                                    <?php }
                                                    if ($vRenov[0]['no_renov'] == 1) { ?>
                                                        <a href="../v_poliza.php?modal=true&id_poliza=<?= $poliza[$i]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="No Renovada" class="btn young-passion-gradient btn-rounded btn-sm btn-block text-white">No Renovada</a>
                                                    <?php }
                                                }
                                            } else {
                                                if ($seguimiento != 0) {
                                                    if ($vRenov[0]['no_renov'] == 0 && $vRenov[0]['no_renov'] != null) { ?>
                                                        <a href="../v_poliza.php?id_poliza=<?= $vRenov[0]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Renovada" class="btn aqua-gradient btn-rounded btn-sm btn-block">Renovada</a>
                                                    <?php } elseif ($vRenov[0]['no_renov'] == 1 && $vRenov[0]['no_renov'] != null) { ?>
                                                        <a href="../v_poliza.php?modal=true&id_poliza=<?= $poliza[$i]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="No Renovada" class="btn young-passion-gradient btn-rounded btn-sm btn-block text-white">No Renovada</a>
                                                    <?php } else { ?>
                                                        <a href="../v_poliza.php?modal=true&id_poliza=<?= $poliza[$i]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="En Seguimiento" class="btn morpheus-den-gradient text-white btn-rounded btn-sm btn-block">En Seguimiento</a>
                                            <?php }
                                                }
                                            } ?>
                                        </td>
                                        <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="no-tocar">
                                <td colspan="9" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $nombre; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                                </td>
                            </tr>
                        <?php $totalpoliza = $totalpoliza + sizeof($poliza);
                            } ?>
                        </tbody>
                        <tfoot class="text-center">
                            <tr>
                                <th>Asesor</th>
                                <th>N° Póliza</th>
                                <th>Nombre Titular</th>
                                <th>Cía</th>
                                <th>Ramo</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th>PDF</th>
                                <th></th>
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