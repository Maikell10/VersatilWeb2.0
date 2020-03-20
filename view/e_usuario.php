<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Controller/Poliza.php';

$id_usuario = $_GET['id_usuario'];

$usuario = $obj->get_element_by_id('usuarios', 'id_usuario', $id_usuario);

$asesor = $obj->get_ejecutivo();
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
                                <h1 class="font-weight-bold">Usuario: <?= utf8_encode($usuario[0]['nombre_usuario'] . " " . $usuario[0]['apellido_usuario']); ?></h1>
                                <h2 class="title">Seudónimo: <?= $usuario[0]['seudonimo']; ?></h2>
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
                                                            <input type="text" class="form-control" name="nombre" required value="<?= utf8_encode($usuario[0]['nombre_usuario']); ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="apellido" value="<?= utf8_encode($usuario[0]['apellido_usuario']); ?>">
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
                                                    <td><select name="activo" id="activo" class="mdb-select md-form colorful-select dropdown-primary my-n2">
                                                            <option value="0">Inactivo</option>
                                                            <option value="1">Activo</option>
                                                        </select>
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td align="center">
                                                        <select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="asesor" id="asesor" data-style="btn-white" data-header="Seleccione el Asesor" searchable="Ingrese Búsqueda Rápida">
                                                            <?php
                                                            for ($i = 0; $i < sizeof($asesor); $i++) {
                                                            ?>
                                                                <option value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
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





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script>
            $(document).ready(function() {

                document.getElementById("zprod").value = "<?= $usuario[0]['z_produccion']; ?>";
                document.getElementById("id_permiso").value = "<?= $usuario[0]['id_permiso']; ?>";
                document.getElementById("activo").value = "<?= $usuario[0]['activo']; ?>";

                $('#asesor').val('<?= $usuario[0]['cod_vend']; ?>');
                $('#asesor').change();

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