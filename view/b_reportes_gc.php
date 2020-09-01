<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'b_reportes_gc';

require_once '../Controller/Poliza.php';

$gc_h = $obj->get_element('gc_h', 'f_hoy_h');
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
                            <div class="row ml-5 mr-5">
                                <h1 class="font-weight-bold ">Historial de GC (Asesores)</h1>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableRepGC" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th>Nº Generada</th>
                                    <th>Fecha Creación de GC</th>
                                    <th>Fecha Desde Reporte GC</th>
                                    <th>Fecha Hasta Reporte GC</th>
                                    <th>Cant Comisiones</th>
                                    <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                        <th></th>
                                    <?php } ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($gc_h); $i++) {

                                    $f_pago_gc = date("Y/m/d", strtotime($gc_h[$i]['f_hoy_h']));
                                    $f_desde_rep = date("Y/m/d", strtotime($gc_h[$i]['f_desde_h']));
                                    $f_hasta_rep = date("Y/m/d", strtotime($gc_h[$i]['f_hasta_h']));

                                ?>
                                    <tr style="cursor: pointer">
                                        <td><?= $gc_h[$i]['id_gc_h']; ?></td>
                                        <td><?= $f_pago_gc; ?></td>
                                        <td><?= $f_desde_rep; ?></td>
                                        <td><?= $f_hasta_rep; ?></td>
                                        <td><?= $gc_h[$i]['tPoliza']; ?></td>
                                        <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                            <td class="text-center">
                                                <button onclick="eliminarReporteGC('<?= $gc_h[$i]['id_gc_h']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient text-white btn-sm"><i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th>Nº Generada</th>
                                    <th>Fecha Pago GC</th>
                                    <th>Fecha Desde Reporte GC</th>
                                    <th>Fecha Hasta Reporte GC</th>
                                    <th>Cant Comisiones</th>
                                    <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                        <th></th>
                                    <?php } ?>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
        <script src="../assets/view/modalE.js"></script>
</body>

</html>