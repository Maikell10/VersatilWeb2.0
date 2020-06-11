<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'b_f_product';

require_once '../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Pólizas por Fecha de Carga</h1>
                            </div>
                            <br><br><br>

                            <div class="col-md-8 mx-auto">
                                <form action="f_product.php" class="form-horizontal" method="POST">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <div class="md-form">
                                                <!--The "from" Date Picker -->
                                                <input placeholder="Fecha inicio" type="text" id="startingDate" name="desdeP" class="form-control datepicker" required>
                                                <label for="startingDate">Fecha Desde Producción:</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="md-form">
                                                <!--The "to" Date Picker -->
                                                <input placeholder="Fecha fin" type="text" id="endingDate" name="hastaP" class="form-control datepicker" required>
                                                <label for="endingDate">Fecha Hasta Producción:</label>
                                            </div>
                                        </div>
                                    </div>

                                    <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg">Buscar</button></center>
                                </form>

                                <div id="load" class="d-flex justify-content-center align-items-center" hidden>
                                    <div class="spinner-grow text-info" style="width: 9rem; height: 9rem;" id="load1" hidden></div>
                                </div>
                            </div>


                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>