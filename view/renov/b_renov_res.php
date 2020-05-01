<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'renov/b_renov';

require_once '../../Controller/Poliza.php';
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


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold ">Pólizas por Efectividad de Renovación</h1>
                        </div>
                        <br><br><br>

                        <div class="col-md-8 mx-auto">
                            <h3 class="text-center">Seleccione su Búsqueda</h3>
                            <?php if (isset($_GET['m']) == 2) { ?>

                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    No existen datos para la búsqueda seleccionada!
                                    <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                            <?php } ?>
                            <form action="renov_res.php" class="form-horizontal" method="POST">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label align="left">Año Vigencia Hasta Seguro:</label>
                                        <select class="form-control selectpicker" name="anio" id="anio" data-style="btn-white" data-size="13" data-header="Seleccione Año">
                                            <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                            <?php $fecha_min = $fecha_min + 1;
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                    </div>

                                </div>

                                <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg">Buscar</button></center>
                            </form>
                        </div>
                        <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>