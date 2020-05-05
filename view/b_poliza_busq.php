<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

//$pag = 'b_poliza';

require_once '../Controller/Poliza.php';


$busq = $_POST['busq'];

//----------------------------------------------------------------------------
$user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
$asesor_u = $user[0]['cod_vend'];
$permiso = $_SESSION['id_permiso'];
//---------------------------------------------------------------------------


$polizas = $obj->get_poliza_by_busq($busq, $asesor_u);


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

            <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="row ml-5 mr-5">
                            <h1 class="font-weight-bold ">Resultado de la Búsqueda</h1>
                        </div>

            </div>
            <hr />


            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">



                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableBusq', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableBusq" width="100%">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th hidden>f_poliza</th>
                                <th hidden>id</th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th>Cía</th>
                                <th>Ramo</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>Prima Cobrada</th>
                                <th>Prima Pendiente</th>
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

                                $ejecutivo = $obj->get_ejecutivo_by_cod($poliza['codvend']);

                                $primac = $obj->obetnComisiones($poliza['id_poliza']);

                                $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                            ?>
                                <tr style="cursor: pointer;">
                                    <td hidden><?= $poliza['f_poliza']; ?></td>
                                    <td hidden><?= $poliza['id_poliza']; ?></td>

                                    <?php if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                        <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                    <?php } else { ?>
                                        <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                    <?php } ?>
                                    <td><?= $ejecutivo[0]['nombre']; ?></td>
                                    <td><?= $poliza['nomcia']; ?></td>
                                    <td><?= $poliza['nramo']; ?></td>
                                    <td><?= $newDesde; ?></td>
                                    <td><?= $newHasta; ?></td>
                                    <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                    <td class="text-right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                    <?php if ($ppendiente >= 0) { ?>
                                        <td class="text-right"><?= $currency . number_format($ppendiente, 2); ?></td>
                                    <?php } else { ?>
                                        <td class="text-right text-danger"><?= $currency . number_format($ppendiente, 2); ?></td>
                                    <?php } ?>
                                    
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
                                <th>Ramo</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                <th>Prima Cobrada</th>
                                <th>Prima Pendiente</th>
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

        </div>
    </div>

    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>
</body>

</html>