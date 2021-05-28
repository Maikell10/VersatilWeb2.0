<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

//$pag = 'renov/renov_g';

require_once '../../Controller/Poliza.php';

header("Pragma: public");
header("Expires: 0");
$filename = "Pólizas Generales a Renovar.xls";
header('Content-type: application/x-msdownload; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

$asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';
if (!$asesor == '') {
    $asesor_para_recibir_via_url = stripslashes($asesor);
    $asesor_para_recibir_via_url = urldecode($asesor_para_recibir_via_url);
    $asesor = unserialize($asesor_para_recibir_via_url);
}

$cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
if (!$cia == '') {
    $cia_para_recibir_via_url = stripslashes($cia);
    $cia_para_recibir_via_url = urldecode($cia_para_recibir_via_url);
    $cia = unserialize($cia_para_recibir_via_url);
}

$mes = $_GET['mes'];
$desde = $_GET['anio'] . "-" . $_GET['mes'] . "-01";
$hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-31";

$cont = 1;
if ($mes == null) {
    $cont = 12;
    $mesD = 01;
    $mesH = 12;
    $desde = $_GET['anio'] . "-" . $mesD . "-01";
    $hasta = $_GET['anio'] . "-" . $mesH . "-31";
}

$anio = $_GET['anio'];
if ($anio == null) {
    $fechaMin = $obj->get_fecha_min_max('MIN', 'f_hastapoliza', 'poliza');
    $desde = $fechaMin[0]['MIN(f_hastapoliza)'];

    $fechaMax = $obj->get_fecha_min_max('MAX', 'f_hastapoliza', 'poliza');
    $hasta = $fechaMax[0]['MAX(f_hastapoliza)'];
}

$distinct_ac = $obj->get_poliza_total_by_filtro_renov_distinct_ac($desde, $hasta, $cia, $asesor);
if ($distinct_ac == 0) {
    echo 'hola';
    exit();
    header("Location: b_renov_g.php?m=2");
}

$asesor_b = $obj->get_asesor_por_cod($asesor);

$asesorArray = '';
for ($i = 0; $i < sizeof($asesor_b); $i++) {
    $asesorArray .= $asesor_b[$i]['nombre'] . ", ";
}
$asesorArray;
$myString = substr($asesorArray, 0, -2);

$no_renov = $obj->get_element('no_renov', 'no_renov_n');

$d = new DateTime();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Versatil Seguros</title>

    <style>
        thead th {
            font-size: 23px;
        }

        td {
            border: 1px solid #dee2e6;
        }
    </style>
</head>

<body>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Fecha de Exportada: <?= $d->format('d-m-Y'); ?></th>
            </tr>
            <tr>
                <th style="background-color: #4285F4; color: white">Mes</th>
                <th style="background-color: #4285F4; color: white">Cía</th>
                <th style="background-color: #4285F4; color: white">Nº Póliza</th>
                <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                <th style="background-color: #4285F4; color: white">Ramo</th>
                <th style="background-color: #4285F4; color: white">Asesor</th>
                <th style="background-color: #4285F4; color: white">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($a = 0; $a < $cont; $a++) {

                if ($mes == null) {
                    $desde1 = [$_GET['anio'] . "-01-01", $_GET['anio'] . "-02-01", $_GET['anio'] . "-03-01", $_GET['anio'] . "-04-01", $_GET['anio'] . "-05-01", $_GET['anio'] . "-06-01", $_GET['anio'] . "-07-01", $_GET['anio'] . "-08-01", $_GET['anio'] . "-09-01", $_GET['anio'] . "-10-01", $_GET['anio'] . "-11-01", $_GET['anio'] . "-12-01"];

                    $hasta1 = [$_GET['anio'] . "-01-31", $_GET['anio'] . "-02-31", $_GET['anio'] . "-03-31", $_GET['anio'] . "-04-31", $_GET['anio'] . "-05-31", $_GET['anio'] . "-06-31", $_GET['anio'] . "-07-31", $_GET['anio'] . "-08-31", $_GET['anio'] . "-09-31", $_GET['anio'] . "-10-31", $_GET['anio'] . "-11-31", $_GET['anio'] . "-12-31"];

                    $mes1 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                } else {
                    $desde1 = [$desde];
                    $hasta1 = [$hasta];
                    $mes1 = [$mes];
                }

                $poliza = $obj->get_poliza_total_by_filtro_renov_ac($desde1[$a], $hasta1[$a], $cia, $asesor);

                if ($poliza == 0) {
                    //header("Location: b_renov_g.php?m=1");
                } else {
            ?>
                    <tr>
                        <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9"><?= $mes_arr[$mes1[$a] - 1]; ?></td>

                        <?php
                        for ($i = 0; $i < sizeof($poliza); $i++) {
                            $vRenov = $obj->verRenov($poliza[$i]['id_poliza']);

                            $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                            $totalprima = $totalprima + $poliza[$i]['prima'];

                            $newHasta = date("d/m/Y", strtotime($poliza[$i]['f_hastapoliza']));

                            $currency = ($poliza[$i]['currency'] == 1) ? "$ " : "Bs ";

                            $seguimiento = $obj->seguimiento($poliza[$i]['id_poliza']);

                            $no_renov = $obj->verRenov1($poliza[$i]['id_poliza']);

                            if ($no_renov[0]['no_renov'] != 1) {
                                if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                    <td><?= ($poliza[$i]['nomcia']); ?></td>
                                    <td style="color: #2B9E34;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                <?php } else { ?>
                                    <td><?= ($poliza[$i]['nomcia']); ?></td>
                                    <td style="color: #E54848;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                <?php }
                            } else { ?>
                                <td><?= ($poliza[$i]['nomcia']); ?></td>
                                <td style="color: #4a148c;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                            <?php } ?>



                            <td><?= $newHasta; ?></td>
                            <td><?= ($poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t']); ?></td>
                            <td nowrap><?= utf8_encode($poliza[$i]['nramo']); ?></td>
                            <td nowrap><?= utf8_encode($poliza[$i]['nombre']) . ' (' . $poliza[$i]['codvend'] . ')'; ?></td>


                            <td nowrap>

                                <?php if ($poliza[$i]['f_hastapoliza'] <= date("Y-m-d")) {
                                    if ($vRenov == 0) {
                                        if ($seguimiento == 0) {
                                ?>
                                            En Proceso
                                            <?php
                                        } else {
                                            $poliza_renov = $obj->comprobar_poliza($poliza[$i]['cod_poliza'], $poliza[$i]['id_cia']);
                                            if (sizeof($poliza_renov) != 0) {
                                            ?>
                                                Renovada
                                            <?php
                                            } else {
                                            ?>
                                                En Seguimiento
                                            <?php
                                            }
                                            ?>

                                        <?php
                                        }
                                    } else {
                                        if ($vRenov[0]['no_renov'] == 0) { ?>
                                            Renovada
                                        <?php }
                                        if ($vRenov[0]['no_renov'] == 1) { ?>
                                            No Renovada
                                        <?php }
                                    }
                                } else {
                                    if ($seguimiento != 0) {
                                        if ($vRenov[0]['no_renov'] == 0 && $vRenov[0]['no_renov'] != null) { ?>
                                            Renovada
                                        <?php } elseif ($vRenov[0]['no_renov'] == 1 && $vRenov[0]['no_renov'] != null) { ?>
                                            No Renovada
                                        <?php } else { ?>
                                            En Seguimiento
                                <?php }
                                    }
                                } ?>
                            </td>
                    </tr>
                <?php
                        }
                ?>
                <tr>
                    <td colspan="8" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $mes_arr[$mes1[$a] - 1]; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                    </td>
                </tr>
        <?php
                    $totalpoliza = $totalpoliza + sizeof($poliza);
                }
            }
        ?>
        </tbody>

        <tfoot class="text-center">
            <tr>
                <th>Mes</th>
                <th>Cía</th>
                <th>N° Póliza</th>
                <th>F Hasta Seguro</th>
                <th>Nombre Titular</th>
                <th>Ramo</th>
                <th>Asesor</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>