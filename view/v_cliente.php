<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'v_cliente';

require_once '../Controller/Cliente.php';
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
            <div class="ml-5 mr-5">
                <h1 class="font-weight-bold">Cliente: <?= ($datos_c[0]['nombre_t']) . " " . ($datos_c[0]['apellido_t']); ?></h1>
                <h2 class="title">Nº ID: <?= $datos_c[0]['ci']; ?></h2>
                <hr>
                <center>
                    <a href="e_cliente.php?id_titu=<?= $id_titular; ?>" data-toggle="tooltip" data-placement="top" title="Editar" class="btn dusty-grass-gradient btn-lg">Editar Cliente &nbsp;<i class="fas fa-edit" aria-hidden="true"></i></a>
                </center>
            </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">

            <?php if ($contAct > 0) { ?>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableCliente">
                        <thead class="blue-gradient text-white">
                            <tr class="dusty-grass-gradient">
                                <th colspan="8" class="font-weight-bold text-black text-center h2">Activas</th>
                            </tr>
                            <tr>
                                <th>N° de Póliza</th>
                                <th>Ramo</th>
                                <th>Cía</th>
                                <th>Nombre Asesor</th>
                                <th>Fecha Desde Póliza</th>
                                <th>Fecha Hasta Póliza</th>
                                <th>Prima Suscrita</th>
                                <th>PDF</th>
                                <th hidden>id poliza</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < sizeof($cliente); $i++) {
                                if ($cliente[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                                    $currency = ($cliente[$i]['currency'] == 1) ? "$ " : "Bs ";
                                    $newDesde = date("d-m-Y", strtotime($cliente[$i]["f_desdepoliza"]));
                                    $newHasta = date("d-m-Y", strtotime($cliente[$i]["f_hastapoliza"]));
                            ?>
                                    <tr style="cursor: pointer">
                                        <td><?= $cliente[$i]['cod_poliza']; ?></td>
                                        <td><?= ($cliente[$i]['nramo']); ?></td>
                                        <td><?= ($cliente[$i]['nomcia']); ?></td>
                                        <td><?= ($cliente[$i]['nombre']); ?></td>
                                        <td nowrap><?= $newDesde; ?></td>
                                        <td nowrap><?= $newHasta; ?></td>
                                        <td nowrap><?= $currency . number_format($cliente[$i]['prima'], 2); ?></td>
                                        <?php if ($cliente[$i]['pdf'] == 1) { ?>
                                            <td class="text-center"><a href="download.php?id_poliza=<?= $cliente[$i]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                        <?php } else { ?>
                                            <td></td>
                                        <?php } ?>
                                        <td hidden><?= $cliente[$i]['id_poliza']; ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <br>
            <?php }
            if ($contInact > 0) { ?>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableCliente">
                        <thead class="blue-gradient text-white">
                            <tr class="young-passion-gradient">
                                <th colspan="8" class="font-weight-bold text-center h2">Inactivas</th>
                            </tr>
                            <tr>
                                <th>N° de Póliza</th>
                                <th>Ramo</th>
                                <th>Cía</th>
                                <th>Nombre Asesor</th>
                                <th>Fecha Desde Póliza</th>
                                <th>Fecha Hasta Póliza</th>
                                <th>Prima Suscrita</th>
                                <th>PDF</th>
                                <th hidden>id poliza</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < sizeof($cliente); $i++) {
                                if ($cliente[$i]['f_hastapoliza'] <= date("Y-m-d")) {
                                    $currency = ($cliente[$i]['currency'] == 1) ? "$ " : "Bs ";
                                    $newDesde = date("d-m-Y", strtotime($cliente[$i]["f_desdepoliza"]));
                                    $newHasta = date("d-m-Y", strtotime($cliente[$i]["f_hastapoliza"]));
                            ?>
                                    <tr style="cursor: pointer">
                                        <td><?= $cliente[$i]['cod_poliza']; ?></td>
                                        <td><?= ($cliente[$i]['nramo']); ?></td>
                                        <td><?= ($cliente[$i]['nomcia']); ?></td>
                                        <td><?= ($cliente[$i]['nombre']); ?></td>
                                        <td nowrap><?= $newDesde; ?></td>
                                        <td nowrap><?= $newHasta; ?></td>
                                        <td nowrap><?= $currency . number_format($cliente[$i]['prima'], 2); ?></td>
                                        <?php if ($cliente[$i]['pdf'] == 1) { ?>
                                            <td class="text-center"><a href="download.php?id_poliza=<?= $cliente[$i]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                        <?php } else { ?>
                                            <td></td>
                                        <?php } ?>
                                        <td hidden><?= $cliente[$i]['id_poliza']; ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
            <br>

            <h1 class="font-weight-bold h1">Datos del Cliente</h1>

            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="blue-gradient text-white">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Fecha Nacimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $datos_c[0]['ci']; ?></td>
                            <td><?= ($datos_c[0]['nombre_t']); ?></td>
                            <td><?= ($datos_c[0]['apellido_t']); ?></td>
                            <td><?= $newFnac; ?></td>
                        </tr>
                        <tr class="blue-gradient text-white">
                            <th>Celular</th>
                            <th>Teléfono</th>
                            <th colspan="2">email</th>
                        </tr>
                        <tr>
                            <td><?= $datos_c[0]['cell']; ?></td>
                            <td><?= $datos_c[0]['telf']; ?></td>
                            <td colspan="2"><a href=mailto:<?= $datos_c[0]['email']; ?> data-toggle="tooltip" data-placement="bottom" title="Enviar Correo"><?= $datos_c[0]['email']; ?></a></td>
                        </tr>
                        <tr class="blue-gradient text-white">
                            <th colspan="4">Dirección</th>
                        </tr>
                        <tr>
                            <td colspan="4"><?= ($datos_c[0]['direcc']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>

</body>

</html>