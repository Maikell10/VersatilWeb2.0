<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Controller/Poliza.php';

$nomcia = $_POST['nomcia'];

$cia = $obj->get_element_by_id('dcia', 'nomcia', $nomcia);
$asesor = $obj->get_element('ena', 'idnom');

$cant_a = sizeof($asesor);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
    <style>
        .picker .picker__frame {
            top: 0;
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
                        <h1 class="font-weight-bold text-center">Previsualizar Preferencial de la Cía <?= $cia[0]['nomcia']; ?></h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <form class="form-horizontal" id="frmnuevo" action="comp_pref_nn.php" method="POST">
                <center><button type="submit" id="btnForm" class="btn dusty-grass-gradient btn-lg btn-rounded">Agregar Preferencial</button></center>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="heavy-rain-gradient">
                            <tr>
                                <th class="text-black font-weight-bold">Fecha Desde Preferida *</th>
                                <th class="text-black font-weight-bold">Fecha Hasta Preferida *</th>
                                <th class="text-black font-weight-bold">%GC a Sumar *</th>
                                <th hidden>nomcia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" id="desdeP" name="desdeP" readonly value="<?= $_POST['desdeP']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" id="hastaP" name="hastaP" readonly value="<?= $_POST['hastaP']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" id="per_gc" name="per_gc" readonly value="<?= $_POST['per_gc']; ?>">
                                    </div>
                                </td>

                                <td hidden><input type="text" class="form-control" id="id_cia" name="id_cia" value="<?= $cia[0]['idcia']; ?>"></td>
                            </tr>
                        </tbody>

                    </table>

                    <table class="table table-hover table-striped table-bordered">
                        <thead class="heavy-rain-gradient">
                            <tr>
                                <th class="text-black font-weight-bold">Nombre Asesor</th>
                                <th class="text-black font-weight-bold">%GC</th>
                                <th class="text-black font-weight-bold">%GC a Sumar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < sizeof($asesor); $i++) {

                            ?>
                                <tr>
                                    <td><?= utf8_encode($asesor[$i]['idnom']) . " [" . $asesor[$i]['cod'] . "]"; ?></td>
                                    <td><?= $asesor[$i]['nopre1'] . " %"; ?></td>
                                    <td style="background-color: white">
                                        <div class="input-group md-form my-n1 grey lighten-2">
                                            <input style="text-align:center" type="text" class="form-control" id="<?= 'gc_asesor' . $i; ?>" name="<?= 'gc_asesor' . $i; ?>" readonly value="<?= $_POST['gc_asesor' . $i]; ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                    </table>
                </div>

                <div class="table-responsive-xl">

                </div>


            </form>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_comp.js"></script>
    
</body>

</html>