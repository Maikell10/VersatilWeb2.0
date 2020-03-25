<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'renov/b_renov_t';

require_once '../../Controller/Poliza.php';

$polizas = $obj->renovar();
$cant_p = sizeof($polizas);
foreach ($polizas as $poliza) {
    $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
    if (sizeof($poliza_renov) != 0) {
        $cant_p = $cant_p - 1;
    }
}

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
                            <h1 class="font-weight-bold ">Lista Pólizas Vencidas a Renovar</h1>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenov', 'Listado de Pólizas a Renovar')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenov" width="100%">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th>Cía</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>Nombre Titular</th>
                                <th>PDF</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $prima_t = 0;
                            foreach ($polizas as $poliza) {
                                $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
                                if (sizeof($poliza_renov) == 0) {
                                    $prima_t = $prima_t + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));
                            ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $poliza['f_hastapoliza']; ?></td>
                                        <td hidden><?= $poliza['id_poliza']; ?></td>
                                        <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <td><?= $poliza['nombre']; ?></td>
                                        <td><?= $poliza['nomcia']; ?></td>
                                        <td><?= $newDesde; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td align="right"><?= '$ '.number_format($poliza['prima'], 2); ?></td>
                                        <td><?= $poliza['nombre_t'] . ' ' . $poliza['apellido_t']; ?></td>
                                        <?php if ($poliza['pdf'] == 1) { ?>
                                            <td><a href="../download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank" style="float: right"><img src="../../assets/img/pdf-logo.png" width="30" id="pdf"></a></td>
                                        <?php } else { ?>
                                            <td></td>
                                        <?php } ?>
                                        <td><a href="#" data-toggle="tooltip" data-placement="top" title="Renovar" class="btn dusty-grass-gradient btn-rounded"><i class="fa fa-check-circle" aria-hidden="true"></i> </a></td>
                                    </tr>
                            <?php } else {
                                    $cant_p = $cant_p - 1;
                                }
                            } ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th>Cía</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita $<?= number_format($prima_t, 2); ?></th>
                                <th>Nombre Titular</th>
                                <th>PDF</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <h1 class="title text-center">Total de Prima Suscrita</h1>
                <h1 class="title text-danger text-center">$ <?= number_format($prima_t, 2); ?></h1>

                <h1 class="title text-center">Total de Pólizas</h1>
                <h1 class="title text-danger text-center"><?= $cant_p; ?></h1>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>