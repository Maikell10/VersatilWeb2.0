<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Controller/Asesor.php';

$asesor = $obj->get_element('ena', 'cod');

$estructura = $_GET['en'];

if ($estructura == 1) {
    $uAsesor = $obj->get_ultimo_asesor();
    $u = $uAsesor[0]['cod'];
    $u = explode('-', $uAsesor[0]['cod']);
}
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

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold text-center"><i class="fas fa-user-plus" aria-hidden="true"></i>&nbsp;Añadir Nuevo Asesor</h1>
                            </div>
                            <br><br>

                            <div class="col-md-8 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" autocomplete="off" action="../../procesos/agregarAsesor.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Cod Asesor *</th>
                                                    <th>N° ID *</th>
                                                    <th>Nombre Completo *</th>
                                                    <th>E-Mail *</th>
                                                    <th>Cel *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cod" readonly="true" value="<?= $u[0] . "-" . ($u[1] + 1); ?> ">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos" name="id_a" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="nombre_a" required onkeyup="mayus(this);" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="email" class="form-control" name="email" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" placeholder="ejemplo@email.com" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos1" id="cel" name="cel" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr class="blue-gradient text-white">
                                                    <th>Banco *</th>
                                                    <th>Tipo Cuenta</th>
                                                    <th colspan="2">N° Cuenta *</th>

                                                    <th>Observaciones</th>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="banco" name="banco" required onkeyup="mayus(this);" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                                        </div>
                                                    </td>
                                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="tipo_cuenta" required>
                                                            <option value="CORRIENTE">CORRIENTE</option>
                                                            <option value="AHORRO">AHORRO</option>
                                                            <option value="JURÍDICO">JURÍDICO</option>
                                                        </select>
                                                    </td>
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos2" id="num_cuenta" name="num_cuenta" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="obs" name="obs" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr class="blue-gradient text-white">
                                                    <th colspan="2">%GC (Nuevo) *</th>
                                                    <th>%GC (Renovación) *</th>
                                                    <th>%GC Viajes (Nuevo) *</th>
                                                    <th>%GC Viajes (Renovación) *</th>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos3" id="gc" name="gc" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos4" id="gc_renov" name="gc_renov" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos5" id="viajes" name="viajes" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos6" id="viajes_renov" name="viajes_renov" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <center>
                                        <button type="submit" id="" class="btn blue-gradient btn-lg btn-rounded">Agregar nuevo</button>
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
            onload = function() {
                var ele = document.querySelectorAll('.validanumericos')[0];
                var ele1 = document.querySelectorAll('.validanumericos1')[0];
                var ele2 = document.querySelectorAll('.validanumericos2')[0];
                var ele3 = document.querySelectorAll('.validanumericos3')[0];
                var ele4 = document.querySelectorAll('.validanumericos4')[0];
                var ele5 = document.querySelectorAll('.validanumericos5')[0];
                var ele6 = document.querySelectorAll('.validanumericos6')[0];

                ele.onkeypress = function(e) {
                    if (isNaN(this.value + String.fromCharCode(e.charCode)))
                        return false;
                }
                ele.onpaste = function(e) {
                    e.preventDefault();
                }
                ele1.onkeypress = function(e1) {
                    if (isNaN(this.value + String.fromCharCode(e1.charCode)))
                        return false;
                }
                ele1.onpaste = function(e1) {
                    e1.preventDefault();
                }
                ele2.onkeypress = function(e2) {
                    if (isNaN(this.value + String.fromCharCode(e2.charCode)))
                        return false;
                }
                ele2.onpaste = function(e2) {
                    e2.preventDefault();
                }
                ele3.onkeypress = function(e3) {
                    if (isNaN(this.value + String.fromCharCode(e3.charCode)))
                        return false;
                }
                ele3.onpaste = function(e3) {
                    e3.preventDefault();
                }
                ele4.onkeypress = function(e4) {
                    if (isNaN(this.value + String.fromCharCode(e4.charCode)))
                        return false;
                }
                ele4.onpaste = function(e4) {
                    e4.preventDefault();
                }
                ele5.onkeypress = function(e5) {
                    if (isNaN(this.value + String.fromCharCode(e5.charCode)))
                        return false;
                }
                ele5.onpaste = function(e5) {
                    e5.preventDefault();
                }
                ele6.onkeypress = function(e6) {
                    if (isNaN(this.value + String.fromCharCode(e6.charCode)))
                        return false;
                }
                ele6.onpaste = function(e6) {
                    e6.preventDefault();
                }
            }

            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>