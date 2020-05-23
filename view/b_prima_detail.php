<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'b_poliza';

require_once '../Controller/Poliza.php';
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

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold text-center">Pólizas por Detalle de Prima Cobrada</h1>
                            </div>
                            <br><br><br>

                            <div class="col-md-8 mx-auto">
                                <form action="prima_detail.php" class="form-horizontal" method="POST">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label align="left">Año Vigencia Hasta Seguro:</label>
                                            <select class="form-control selectpicker" name="anio" id="anio" data-style="btn-white" data-size="13" data-header="Seleccione Año">
                                                <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                    <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                                <?php $fecha_min = $fecha_min + 1;
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Mes Vigencia Hasta Seguro:</label>
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
                                        <div class="form-group col-md-6">
                                            <label>Asesor:</label>
                                            <select class="form-control selectpicker" name="asesor[]" multiple data-style="btn-white" data-header="Seleccione el Asesor" data-size="12" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($asesor); $i++) {
                                                ?>
                                                    <option value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]); ?></option>
                                                <?php } ?>
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
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label align="left">Periocidad de Pago:</label>
                                            <select class="form-control selectpicker" name="fpago[]" data-style="btn-white" data-header="Periocidad de Pago" multiple>
                                                <option value="CONTADO">CONTADO</option>
                                                <option value="FRACCIONADO">FRACCIONADO</option>
                                                <option value="FINANCIADO">FINANCIADO</option>
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
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>