<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'v_cia';

require_once '../Controller/Poliza.php';

$id_cia = $_GET['id_cia'];
$f_desde = $_GET['f_desde'];
$f_hasta = $_GET['f_hasta'];

$cia = $obj->get_cia_pref($id_cia, $f_desde, $f_hasta);
$desde_prefn = date("d/m/Y", strtotime($cia[0]['f_desde_pref']));
$hasta_prefn = date("d/m/Y", strtotime($cia[0]['f_hasta_pref']));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold">Cía: <?= ($cia[0]['nomcia']); ?></h1>
                        <h2 class="font-weight-bold">RUC/Rif: <?= $cia[0]['rif']; ?></h2>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>Fecha Desde Preferencial</th>
                        <th>Fecha Hasta Preferencial</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $desde_prefn; ?></td>
                            <td><?= $hasta_prefn; ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-hover table-striped table-bordered" id="tableCP" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>Asesor</th>
                        <th>%GC</th>
                        <th>%GC Viajes</th>
                        <th>%GC a Sumar</th>
                        <th>%GC Preferencial</th>
                        <th>%GC Preferencial Viajes</th>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < sizeof($cia); $i++) { ?>
                            <tr>
                                <td><?= utf8_encode($cia[$i]['idnom']); ?></td>
                                <td class="text-right"><?= $cia[$i]['nopre1']; ?></td>
                                <td class="text-right"><?= $cia[$i]['gc_viajes']; ?></td>
                                <td class="text-right"><?= $cia[$i]['per_gc_sum']; ?></td>
                                <td class="text-danger font-weight-bold text-right"><?= $cia[$i]['nopre1'] + $cia[$i]['per_gc_sum']; ?></td>
                                <td class="text-danger font-weight-bold text-right"><?= $cia[$i]['gc_viajes'] + $cia[$i]['per_gc_sum']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>


    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/cia.js"></script>

</body>

</html>