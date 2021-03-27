<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'crm/b_mensaje';

require_once '../../Controller/Poliza.php';
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
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Programar Mensaje de Promoción a Clientes</h1>
                            </div>
                            <br>

                            <?php if (isset($_GET['m'])) {
                                if ($_GET['m'] == 2) { ?>
                                    <div class="alert alert-danger alert-dismissible fade show col-md-8 m-auto" role="alert">
                                        No existen datos para la búsqueda seleccionada!
                                        <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                            <?php }
                            } ?>

                            <div class="col-md-8 mx-auto">
                                <form action="mensaje_prog.php" class="form-horizontal" method="GET">

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Cía:</label>
                                            <select class="form-control selectpicker" name="cia[]" multiple data-style="btn-white" data-header="Seleccione Cía" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($cia); $i++) {
                                                ?>
                                                    <option value="<?= $cia[$i]["idcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Asesor:</label>
                                            <select class="form-control selectpicker" name="asesor[]" multiple data-style="btn-white" data-header="Seleccione el Asesor" data-size="12" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($asesor); $i++) {
                                                ?>
                                                    <option value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]).' ('.$asesor[$i]["cod"].')'; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Ramo:</label>
                                            <select class="form-control selectpicker custom-select" name="ramo[]" multiple data-style="btn-white" data-header="Seleccione Ramo" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($ramo); $i++) {
                                                ?>
                                                    <option value="<?= $ramo[$i]["cod_ramo"]; ?>"><?= ($ramo[$i]["nramo"]); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Tipo de Póliza:</label>
                                            <select class="form-control selectpicker custom-select" name="t_poliza[]" multiple data-style="btn-white" data-header="Seleccione Tipo de Póliza" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($t_poliza); $i++) {
                                                ?>
                                                    <option value="<?= $t_poliza[$i]["id_t_poliza"]; ?>"><?= ($t_poliza[$i]["tipo_poliza"]); ?></option>
                                                <?php
                                                }
                                                ?>
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





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>