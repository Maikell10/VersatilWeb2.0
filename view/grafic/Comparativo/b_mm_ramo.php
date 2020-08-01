<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
//$pag = 'Porcentaje/busqueda';

require_once '../../../Controller/Grafico.php';

//$asesor = $obj->get_ejecutivo();
$cia = $obj->get_distinct_element('nomcia', 'dcia');
$ramo = $obj->get_distinct_element('nramo', 'dramo');

$fecha_min = $obj->get_fecha_min_max('MIN', 'f_pago_prima', 'comision');
$fecha_max = $obj->get_fecha_min_max('MAX', 'f_pago_prima', 'comision');

//FECHA MAYORES A 2024
$dateString = $fecha_max[0]["MAX(f_pago_prima)"];
// Parse a textual date/datetime into a Unix timestamp
$date = new DateTime($dateString);
$format = 'Y';
// Parse a textual date/datetime into a Unix timestamp
$date = new DateTime($dateString);
// Print it
$fecha_max = $date->format($format);
$fecha_min = date('Y', strtotime($fecha_min[0]["MIN(f_pago_prima)"]));

$fecha_maxC = $fecha_max;
$fecha_minC = $fecha_min;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
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
                            <h1 class="font-weight-bold text-center">Gráfico Utilidad en Ventas</h1>
                            <br>
                            <center>
                                <a href="../comparativo.php" class="btn blue-gradient btn-lg btn-rounded">Menú de Gráficos</a>
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
                            <form action="mm_ramo.php" class="form-horizontal" method="GET">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label align="left">Año Cobranza:</label>
                                        <select class="form-control selectpicker" name="anio" id="anio" data-style="btn-white" data-size="13" data-header="Seleccione Año">
                                            <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                            <?php $fecha_min = $fecha_min + 1;
                                            } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label align="left">Año Cobranza a Comparar:</label>
                                        <select class="form-control selectpicker" name="anioC" id="anioC" data-style="btn-white" data-size="13" data-header="Seleccione Año">
                                            <?php for ($i = $fecha_minC; $i <= $fecha_maxC-1; $i++) { ?>
                                                <option value="<?= $fecha_minC; ?>"><?= $fecha_minC; ?></option>
                                            <?php $fecha_minC = $fecha_minC + 1;
                                            } ?>
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

                                <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg" id="btnSend">Buscar</button></center>
                            </form>

                            <div id="load" class="d-flex justify-content-center align-items-center" hidden>
                                <div class="spinner-grow text-info" style="width: 9rem; height: 9rem;" id="load1" hidden></div>
                            </div>

                        </div>
                        <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../../assets/view/grafico.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#mes').val(<?= date("m"); ?>);
                $('#mes').change();
            });
        </script>
</body>

</html>