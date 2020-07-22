<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Model/Poliza.php';

$obj = new Poliza();
$ramo = $obj->get_element('dramo', 'nramo');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="row ml-5 mr-5">
                        <h1 class="font-weight-bold ">Lista de Ramos</h1>
                        <?php if ($_SESSION['id_permiso'] == 1) { ?>
                            <a href="add/crear_ramo.php" class="btn blue-gradient ml-auto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo Ramo</a>
                        <?php } ?>
                    </div>
        </div>

        <div class="row">
            <div class="col-md-2"></div>
            <div class="card-body p-5 animated bounceInUp col-md-8" id="tablaLoad">
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="table_ramo" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>id</th>
                                <th>Nombre</th>
                                <th>Cant. Pólizas</th>
                                <th nowrap>Activas</th>
                                <th nowrap>Inactivas</th>
                                <th nowrap>Anuladas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < sizeof($ramo); $i++) {
                                $primaSusc = 0;
                                $totalA = 0;
                                $totalI = 0;
                                $totalAn = 0;

                                $cant = $obj->get_polizas_t_ramo($ramo[$i]['cod_ramo']);
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
                                <tr style="cursor:pointer">
                                    <td hidden><?= $ramo[$i]['cod_ramo']; ?></td>
                                    <td><?= ($ramo[$i]['nramo']); ?></td>

                                    <td class="text-center"><?= sizeof($cant); ?></td>
                                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalA; ?></td>
                                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalI; ?></td>
                                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="<?= $tooltip; ?>"><?= $totalAn; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th hidden>id</th>
                                <th>Nombre</th>
                                <th nowrap style="font-weight: bold" class="text-center">Cant Pólizas: <?= $totalCant; ?></th>
                                <th nowrap style="font-weight: bold" class="text-center">Cant Activas: <?= $tA; ?></th>
                                <th nowrap style="font-weight: bold" class="text-center">Cant Inactivas: <?= $tI; ?></th>
                                <th nowrap style="font-weight: bold" class="text-center">Cant Anuladas: <?= $tAn; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>
</body>

</html>