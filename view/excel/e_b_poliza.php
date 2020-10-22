<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

//$pag = 'b_poliza';

require_once '../../Controller/Poliza.php';

header("Pragma: public");
header("Expires: 0");
$filename = "Pólizas Generales.xls";
header('Content-type: application/x-msdownload; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");


$polizas = $obj->getPolizas();

if (isset($_GET['session'])) {
    if ($_GET['session'] == 1 || $_GET['session'] == 2) {
        $polizas = $obj->getPolizas();
    }else{
        $polizas = 0;
    }
    
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_GET['yhuejd']);
    $cod_asesor_user = $obj->get_element_by_id('usuarios', 'id_usuario', $_GET['yhuejd']);

    $polizas = $obj->get_poliza_total_by_asesor_ena_user($cod_asesor_user[0]['cod_vend']);
}

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
                <th>Fecha de Exportada: <?= $d->format('d-m-Y');?></th>
            </tr>
            <tr>
                <th style="background-color: #4285F4; color: white">-</th>
                <th style="background-color: #4285F4; color: white">Nº Póliza</th>
                <th style="background-color: #4285F4; color: white">Nombre Asesor</th>
                <th style="background-color: #4285F4; color: white">Cía</th>
                <th style="background-color: #4285F4; color: white">Ramo</th>
                <th style="background-color: #4285F4; color: white">F Desde Seguro</th>
                <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                <th style="background-color: #E54848;color:white;">Prima Suscrita</th>
                <th style="background-color: #4285F4; color: white">Nombre Titular</th>
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

                    <?php if ($poliza['id_tpoliza'] == 1) { ?>
                        <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                    <?php }
                    if ($poliza['id_tpoliza'] == 2) { ?>
                        <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                    <?php }
                    if ($poliza['id_tpoliza'] == 3) { ?>
                        <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                    <?php } ?>


                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                            <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                        <?php } else { ?>
                            <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                        <?php }
                    } else { ?>
                        <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                    <?php } ?>

                    <td nowrap><?= $poliza['nombre'] . ' (' . $poliza['codvend'] . ')'; ?></td>
                    <td><?= $poliza['nomcia']; ?></td>
                    <td><?= $poliza['nramo']; ?></td>
                    <td><?= $newDesde; ?></td>
                    <td><?= $newHasta; ?></td>
                    <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                    <td><?= ($nombre); ?></td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>