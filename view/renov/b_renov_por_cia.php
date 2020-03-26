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
                            <h1 class="font-weight-bold ">Pólizas a Renovar por Cía</h1>
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
                            <form action="renov_por_cia.php" class="form-horizontal" method="POST">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label align="left">Año Vigencia Seguro:</label>
                                        <select class="form-control selectpicker" name="anio" id="anio" data-style="btn-white" data-size="13" data-header="Seleccione Año">
                                            <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                            <?php $fecha_min = $fecha_min + 1;
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Mes Vigencia Seguro:</label>
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
                                <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Asesor:</label>
                                            <select class="form-control selectpicker" name="asesor[]" multiple data-style="btn-white" data-header="Seleccione el Asesor" data-size="12" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($asesor); $i++) {
                                                ?>
                                                    <option value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php }
                                if ($_SESSION['id_permiso'] == 3) {
                                    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
                                ?>
                                    <div class="form-row" hidden>
                                        <div class="form-group col-md-12">
                                            <label>Asesor:</label>
                                            <select class="form-control selectpicker" name="asesor[]">
                                                <option value="<?= $user[0]['cod_vend']; ?>"></option>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>

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