<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'Primas_Cobradas/busqueda';

require_once '../../../Controller/Grafico.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold text-center">Comisiones Cobradas por Ejecutivo</h1>
                            <br>
                            <center>
                                <a href="../comisiones_c.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
                            </center>
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
                            <form action="ejecutivo.php" class="form-horizontal" method="GET">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label align="left">Año de Cobranza de Prima:</label>
                                        <select class="form-control selectpicker" name="anio" id="anio" data-style="btn-white" data-size="13" data-header="Seleccione Año">
                                            <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                            <?php $fecha_min = $fecha_min + 1;
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Ramo:</label>
                                        <select class="form-control selectpicker" name="ramo[]" multiple data-style="btn-white" data-header="Seleccione Ramo" data-actions-box="true" data-live-search="true">
                                            <?php
                                            for ($i = 0; $i < sizeof($ramo); $i++) {
                                            ?>
                                                <option value="<?= $ramo[$i]["nramo"]; ?>"><?= ($ramo[$i]["nramo"]); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Tipo de Cuenta:</label>
                                        <select class="form-control selectpicker" name="tipo_cuenta[]" multiple data-style="btn-white" data-header="Tipo de Cuenta" data-actions-box="true" data-live-search="true">
                                            <option value="1">INDIVIDUAL</option>
                                            <option value="2">COLECTIVO</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Cía:</label>
                                        <select class="form-control selectpicker" name="cia[]" multiple data-style="btn-white" data-header="Seleccione Cía" data-actions-box="true" data-live-search="true">
                                            <?php
                                            for ($i = 0; $i < sizeof($cia); $i++) {
                                            ?>
                                                <option value="<?= $cia[$i]["nomcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg">Buscar</button></center>
                            </form>
                        </div>
                        <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../../assets/view/grafico.js"></script>
</body>

</html>