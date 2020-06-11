<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'b_pendientes';

require_once '../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Pólizas Pendientes a Cargar</h1>
                            </div>
                </div>

                <?php if ($polizas == 0) { ?>
                    <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                        <h3 class="font-weight-bold text-center">No hay Pólizas Pendientes</h3>
                    </div>
                <?php } else { ?>


                    <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                        <div class="table-responsive-xl">
                            <table class="table table-hover table-striped table-bordered" id="tableP" width="100%">
                                <thead class="blue-gradient text-white text-center">
                                    <tr>
                                        <th hidden>f_poliza</th>
                                        <th hidden>id</th>
                                        <th>N° Póliza</th>
                                        <th hidden>Código Vendedor</th>
                                        <th>F Producción</th>
                                        <th>F Hasta Reporte</th>
                                        <th>Cia</th>
                                        <th>Asesor</th>
                                        <th>Asegurado</th>
                                        <th>Prima Cobrada</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    foreach ($polizas as $poliza) {

                                        $polizap = $obj->get_comision_by_polizaP($poliza['id_poliza']);

                                        $newFProd = date("Y/m/d", strtotime($poliza['f_poliza']));
                                        $newFRep = date("Y/m/d", strtotime($polizap[0]['f_hasta_rep']));

                                        $totalprimaC = $polizap[0]['SUM(prima_com)'];


                                        if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                            <tr style="cursor: pointer;">
                                                <td hidden><?= $poliza['f_poliza']; ?></td>
                                                <td hidden><?= $poliza['id_poliza']; ?></td>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                            <tr style="cursor: pointer;">
                                                <td hidden><?= $poliza['f_poliza']; ?></td>
                                                <td hidden><?= $poliza['id_poliza']; ?></td>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } ?>

                                            <td hidden><?= $poliza['codvend']; ?></td>
                                            <td><?= $newFProd; ?></td>
                                            <td><?= $newFRep; ?></td>
                                            <td><?= $poliza['nomcia']; ?></td>
                                            <td><?= $poliza['nombre']; ?></td>
                                            <td><?= $poliza['asegurado']; ?></td>
                                            <td style="text-align: right"><?= '$ ' . number_format($totalprimaC, 2); ?></td>
                                            <?php if ($totalprimaC == 0) { ?>
                                                <td class="text-center"><button onclick="eliminarPolizaP('<?= $poliza['id_poliza']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                            <?php } else { ?>
                                                <td></td>
                                            <?php } ?>
                                            </tr>
                                        <?php } ?>
                                </tbody>

                                <tfoot class="text-center">
                                    <tr>
                                        <th hidden>f_poliza</th>
                                        <th hidden>id</th>
                                        <th>N° Póliza</th>
                                        <th hidden>Código Vendedor</th>
                                        <th>F Producción</th>
                                        <th>F Hasta Reporte</th>
                                        <th>Cia</th>
                                        <th>Asesor</th>
                                        <th>Asegurado</th>
                                        <th>Prima Cobrada</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>


                        <p class="h1 text-center">Total de Pólizas Pendientes</p>
                        <p class="h1 text-center text-danger"><?php echo sizeof($polizas); ?></p>
                    </div>


            <?php }
            } ?>
        </div>





        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
        <script src="../assets/view/modalE.js"></script>
</body>

</html>