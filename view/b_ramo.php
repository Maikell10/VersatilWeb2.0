<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Model/Poliza.php';

$obj = new Poliza();
$ramo = $obj->get_element('dramo', 'nramo');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
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
            <div class="col-md-4"></div>
            <div class="card-body p-5 animated bounceInUp col-md-4" id="tablaLoad">
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="table_ramo" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>id</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < sizeof($ramo); $i++) { ?>
                                <tr style="cursor:pointer">
                                    <td hidden><?= $ramo[$i]['cod_ramo']; ?></td>
                                    <td class="text-center"><?= ($ramo[$i]['nramo']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th hidden>id</th>
                                <th>Nombre</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>
</body>

</html>