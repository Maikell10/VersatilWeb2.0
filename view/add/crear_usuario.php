<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Controller/Poliza.php';

$asesor = $obj->get_ejecutivo();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <?php if (isset($_GET['cond'])) { ?>
                                    <h1 class="font-weight-bold text-center"><i class="fas fa-check-square text-success" aria-hidden="true"></i>&nbsp;Agregado con Éxito</h1>
                                <?php } ?>
                                <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Añadir Nuevo Usuario</h1>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" autocomplete="off" action="usuario.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Nombre del Usuario *</th>
                                                    <th>Apellido *</th>
                                                    <th>Cédula *</th>
                                                    <th>Z Producc *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="nombre" name="nombre" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="apellido" name="apellido" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="ci" name="ci">
                                                        </div>
                                                    </td>
                                                    <td><select name="zprod" id="zprod" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                            <option value="PANAMA">PANAMA</option>
                                                            <option value="CARACAS">CARACAS</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Seudónimo</th>
                                                    <th>Clave</th>
                                                    <th>Permisos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="seudonimo" name="seudonimo">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="clave" name="clave">
                                                        </div>
                                                    </td>
                                                    <td><select name="id_permiso" id="id_permiso" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                            <option value="1">Administrador</option>
                                                            <option value="2">Usuario</option>
                                                            <option value="3">Asesor</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%" id="tablaAsesor" hidden>
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Asesor Asociado *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <select class="form-control selectpicker" id="asesor" name="asesor" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista" data-style="btn-white" data-header="Seleccione Cía" data-actions-box="true" data-live-search="true">
                                                            <?php
                                                            for ($i = 0; $i < sizeof($asesor); $i++) {
                                                            ?>
                                                                <option value='<?= $asesor[$i]["cod"]; ?>'><?= utf8_encode($asesor[$i]["nombre"]) . ' (' . $asesor[$i]["cod"] . ')'; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <center>
                                        <button type="submit" id="btnForm" class="btn blue-gradient btn-lg btn-rounded">Previsualizar</button>
                                    </center>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script>
            function mayus(e) {
                e.value = e.value.toUpperCase();
            }

            $("#id_permiso").change(function() {
                if ($('#id_permiso').val() == 3) {
                    $('#tablaAsesor').removeAttr('hidden');
                } else {
                    $('#tablaAsesor').attr('hidden', true);
                }
            });
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>