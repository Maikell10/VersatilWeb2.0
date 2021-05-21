<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
//$pag = 'b_reportes';

require_once '../../Controller/Poliza.php';

$asesor = $obj->get_ejecutivo();
$cia = $obj->get_element('dcia', 'nomcia');

$fechaMin = $obj->get_fecha_min_max_gca('MIN', 'f_pago_gc');
$fechaMax = $obj->get_fecha_min_max_gca('MAX', 'f_pago_gc');

$fecha_min = date('Y', strtotime($fechaMin[0]["MIN(f_pago_gc)"]));
$fecha_max = date('Y', strtotime($fechaMax[0]["MAX(f_pago_gc)"]));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="../administracion.php" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold text-center">GC Cargada (ASESORES, REFERIDORES, PROYECTOS)</h1>
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
                                <form action="gc_cargada.php" class="form-horizontal" method="GET">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label align="left">Año Pago GC:</label>
                                            <select class="form-control selectpicker" name="anio" id="anio" data-style="btn-white" data-size="13" data-header="Seleccione Año">
                                                <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                    <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                                <?php $fecha_min = $fecha_min + 1;
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Mes Pago GC:</label>
                                            <select class="form-control selectpicker" name="mes" id="mes" data-style="btn-white" data-header="Seleccione Mes">
                                                <option value="">Seleccione Mes</option>
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Asesor:</label>
                                            <select class="form-control selectpicker" name="asesor[]" multiple data-style="btn-white" data-header="Seleccione el Asesor" data-size="12" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($asesor); $i++) {
                                                ?>
                                                    <option value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]) . '(' . $asesor[$i]["cod"] . ')'; ?></option>
                                                <?php } ?>
                                            </select>
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

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>
            $(document).ready(function () {
                var today = new Date();
                $('#mes').val(today.getMonth()+1);
                $('#mes').change();
            });
        </script>
</body>

</html>