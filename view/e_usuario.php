<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Poliza.php';

$id_usuario = $_GET['id_usuario'];

$usuario = $obj->get_element_by_id('usuarios', 'id_usuario', $id_usuario);

$asesor = $obj->get_ejecutivo();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold">Usuario: <?= utf8_encode($usuario[0]['nombre_usuario'] . " " . $usuario[0]['apellido_usuario']); ?></h1>
                                <h2 class="title">
                                    Seudónimo: <?= $usuario[0]['seudonimo']; ?>
                                    <?php if ($usuario[0]['updated'] == 0) { ?>
                                            <span class="badge badge-pill badge-danger">
                                            <i class="fa fa-exclamation" aria-hidden="true"></i>
                                            </span>
                                    <?php } ?>
                                </h2>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" action="e_usuario_n.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Nombre Usuario</th>
                                                    <th>Apellido</th>
                                                    <th>Cédula</th>
                                                    <th>Z Producc</th>
                                                    <th hidden>id</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="nombre" required value="<?= utf8_encode($usuario[0]['nombre_usuario']); ?>" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="apellido" value="<?= utf8_encode($usuario[0]['apellido_usuario']); ?>" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="ci" value="<?= $usuario[0]['cedula_usuario']; ?>">
                                                        </div>
                                                    </td>
                                                    <td><select name="zprod" id="zprod" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                            <option value="PANAMA">PANAMA</option>
                                                            <option value="CARACAS">CARACAS</option>
                                                        </select></td>
                                                    <td hidden><input type="text" class="form-control" id="zprod_e" value="<?= $usuario[0]['z_produccion']; ?>"></td>

                                                    <td hidden><input type="text" class="form-control" name="id_usuario" value="<?= $id_usuario; ?>"></td>
                                                </tr>

                                                <tr class="blue-gradient text-white">
                                                    <th>Seudónimo</th>
                                                    <th>Clave</th>
                                                    <th>Permiso</th>
                                                    <th>Activo</th>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="seudonimo" required value="<?= $usuario[0]['seudonimo']; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="clave" value="<?= $usuario[0]['clave_usuario']; ?>">
                                                        </div>
                                                    </td>
                                                    <td><select name="id_permiso" id="id_permiso" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                            <option value="1">Administrador</option>
                                                            <option value="2">Usuario</option>
                                                            <option value="3">Asesor</option>
                                                        </select>
                                                    </td>
                                                    <td hidden><input type="text" class="form-control" id="id_permiso_e" value="<?= $usuario[0]['id_permiso']; ?>"></td>

                                                    <td><select name="activo" id="activo" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                            <option value="0">Inactivo</option>
                                                            <option value="1">Activo</option>
                                                        </select>
                                                    </td>
                                                    <td hidden><input type="text" class="form-control" id="activo_e" value="<?= $usuario[0]['activo']; ?>"></td>

                                                </tr>

                                                <tr class="blue-gradient text-white">
                                                    <th colspan="4">Email</th>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td colspan="4">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="email" required value="<?= $usuario[0]['email']; ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive-xl">
                                        <table class="table" id="tablaAsesor" hidden>
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Asesor Asociado</th>
                                                    <th>Asesor de Carga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td align="center">
                                                        <select name="asesor" id="asesor" class="mdb-select md-form colorful-select dropdown-primary my-n2" data-style="btn-white" data-header="Seleccione el Asesor" searchable="Ingrese Búsqueda Rápida">
                                                            <?php
                                                            for ($i = 0; $i < sizeof($asesor); $i++) {
                                                                if($asesor[$i]["cod"] == $usuario[0]['cod_vend']) {
                                                            ?>
                                                                <option selected value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]).' ('.$asesor[$i]["cod"].')'; ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]).' ('.$asesor[$i]["cod"].')'; ?></option>
                                                            <?php } } ?>
                                                        </select>
                                                    </td>
                                                    <td hidden><input type="text" class="form-control" id="asesor_e" value="<?= $usuario[0]['cod_vend']; ?>"></td>

                                                    <td><select name="carga" id="carga" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                            <option value="0">No</option>
                                                            <option value="1">Sí</option>
                                                        </select>
                                                    </td>
                                                    <td hidden><input type="text" class="form-control" id="carga_e" value="<?= $usuario[0]['carga']; ?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive-xl">
                                    <h4>Copia al Correo</h4>
                                    <table class="table table-hover table-striped table-bordered" width="100%">
                                        <thead class="blue-gradient text-white text-center">
                                            <tr>
                                                <th>Carta Bienvenida</th>
                                                <th>Carta Renovación</th>
                                                <th>Cumpleaños</th>
                                                <th>Promociones</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr style="background-color: white">
                                                <td><select name="cb" id="cb" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                        <option value="0">No</option>
                                                        <option value="1">Sí</option>
                                                    </select>
                                                </td>
                                                <td hidden><input type="text" class="form-control" id="cb_e" value="<?= $usuario[0]['cb']; ?>"></td>

                                                <td><select name="cr" id="cr" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                        <option value="0">No</option>
                                                        <option value="1">Sí</option>
                                                    </select>
                                                </td>
                                                <td hidden><input type="text" class="form-control" id="cr_e" value="<?= $usuario[0]['cr']; ?>"></td>

                                                <td><select name="cc" id="cc" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                        <option value="0">No</option>
                                                        <option value="1">Sí</option>
                                                    </select>
                                                </td>
                                                <td hidden><input type="text" class="form-control" id="cc_e" value="<?= $usuario[0]['cc']; ?>"></td>
                                                
                                                <td><select name="cp" id="cp" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                        <option value="0">No</option>
                                                        <option value="1">Sí</option>
                                                    </select>
                                                </td>
                                                <td hidden><input type="text" class="form-control" id="cp_e" value="<?= $usuario[0]['cp']; ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                    <hr>
                                    <button type="submit" data-toggle="tooltip" title="Previsualizar" class="btn dusty-grass-gradient btn-lg btn-block" value="">Previsualizar Edición &nbsp;<i class="fas fa-check" aria-hidden="true"></i></button>
                                    <hr>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

        <script>
            $(document).ready(function() {

                if (<?= $usuario[0]['updated']; ?> == 0) {
                    $("#activo option[value=1]").attr("selected", true);
                } else {
                    $("#activo option[value=" + $('#activo_e').val() + "]").attr("selected", true);
                }

                $("#cb option[value=" + $('#cb_e').val() + "]").attr("selected", true);
                $("#cr option[value=" + $('#cr_e').val() + "]").attr("selected", true);
                $("#cc option[value=" + $('#cc_e').val() + "]").attr("selected", true);
                $("#cp option[value=" + $('#cp_e').val() + "]").attr("selected", true);

                $("#zprod option[value=" + $('#zprod_e').val() + "]").attr("selected", true);
                $("#id_permiso option[value=" + $('#id_permiso_e').val() + "]").attr("selected", true);
                $("#carga option[value=" + $('#carga_e').val() + "]").attr("selected", true);

                $("#asesor option[value=" + $('#asesor_e').val() + "]").attr("selected", true);

                if ($('#id_permiso').val() == 3) {
                    $('#tablaAsesor').removeAttr('hidden');
                } else {
                    $('#tablaAsesor').attr('hidden', true);
                }
            });


            $("#id_permiso").change(function() {
                if ($('#id_permiso').val() == 3) {
                    $('#tablaAsesor').removeAttr('hidden');
                } else {
                    $('#tablaAsesor').attr('hidden', true);
                }
            });

            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>