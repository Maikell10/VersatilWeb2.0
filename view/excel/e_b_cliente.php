<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

DEFINE('DS', DIRECTORY_SEPARATOR);

//$pag = 'b_poliza';

require_once '../../Controller/Poliza.php';

header("Pragma: public");
header("Expires: 0");
$filename = "Clientes.xls";
header('Content-type: application/x-msdownload; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");


require_once '../../Model/Cliente.php';

$obj1 = new Cliente();
$clientes = $obj1->get_cliente();

if ($_SESSION['id_permiso'] == 3) {
    $obj = new Poliza();
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    $clientes = $obj1->get_cliente_asesor($user[0]['cod_vend']);
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
                <th style="background-color: #4285F4; color: white">Cédula</th>
                <th style="background-color: #4285F4; color: white">Nombre</th>
                <th style="background-color: #4285F4; color: white">Cant. Pólizas</th>
                <th nowrap style="background-color: #4285F4; color: white">Activas</th>
                <th nowrap style="background-color: #4285F4; color: white">Inactivas</th>
                <th nowrap style="background-color: #4285F4; color: white">Anuladas</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $totalCant = 0;
                for ($i = 1; $i < sizeof($clientes); $i++) {
                    $primaSusc = 0;
                    $totalA = 0;
                    $totalI = 0;
                    $totalAn = 0;

                    $cant = $obj1->get_polizas_t_cliente($clientes[$i]['id_titular']);
                    $totalCant = $totalCant + sizeof($cant);

                    for ($a = 0; $a < sizeof($cant); $a++) {
                        $primaSusc = $primaSusc + $cant[$a]['prima'];
                        $totalPrima = $totalPrima + $cant[$a]['prima'];

                        $no_renov = $obj->verRenov1($cant[$a]['id_poliza']);
                        if ($no_renov[0]['no_renov'] != 1) {
                            if ($cant[$a]['f_hastapoliza'] >= date("Y-m-d")) {
                                $totalA = $totalA + 1;
                                $tA = $tA + 1;
                            } else {
                                $totalI = $totalI + 1;
                                $tI = $tI + 1;
                            }
                        } else {
                            $totalAn = $totalAn + 1;
                            $tAn = $tAn + 1;
                        }
                    }

            ?>
                <tr style="cursor: pointer">
                    <td><?= $clientes[$i]['r_social'] . ' ' . $clientes[$i]['ci']; ?></td>
                    <td><?= $clientes[$i]['nombre_t'].' '.$clientes[$i]['apellido_t']; ?></td>
                    <td class="text-center"><?= sizeof($cant); ?></td>

                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalA; ?></td>
                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalI; ?></td>
                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalAn; ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class="text-center">
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th nowrap style="font-weight: bold" class="text-center">Cant Pólizas: <?= $totalCant; ?></th>
                <th nowrap style="font-weight: bold" class="text-center">Cant Activas: <?= $tA; ?></th>
                <th nowrap style="font-weight: bold" class="text-center">Cant Inactivas: <?= $tI; ?></th>
                <th nowrap style="font-weight: bold" class="text-center">Cant Anuladas: <?= $tAn; ?></th>
            </tr>
        </tfoot>
    </table>
</body>

</html>