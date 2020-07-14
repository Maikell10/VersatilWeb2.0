<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../../Controller/Asesor.php';

$referidor = $obj->get_element_desc('enr', 'id_enr');

if ($referidor[0]['cod'] == null) {
    $cod_ref = "R-1";
} else {
    $u = $referidor[0]['cod'];
    $u = explode('-', $referidor[0]['cod']);
    $cod_ref = $u[0] . "-" . ($u[1] + 1);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
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
                                <h1 class="font-weight-bold text-center"><i class="fas fa-user-plus" aria-hidden="true"></i>&nbsp;Añadir Nuevo Referidor</h1>
                            </div>
                            <br><br>

                            <div class="col-md-9 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" autocomplete="off" action="../../procesos/agregarReferidor.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th nowrap>Cod Referidor *</th>
                                                    <th nowrap>N° ID *</th>
                                                    <th nowrap>Nombre Completo *</th>
                                                    <th nowrap>Cel *</th>
                                                    <th nowrap>Cuenta *</th>
                                                    <th nowrap>E-Mail *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cod" readonly="true" value="<?= $cod_ref; ?> ">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos" name="id_r" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input onblur="cargarCuenta(this)" type="text" class="form-control" name="nombre_r" required onkeyup="mayus(this);" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos1" name="cel" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" id="cuenta" name="cuenta" readonly="true">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="email" class="form-control" id="email" name="email" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" placeholder="ejemplo@email.com">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr class="blue-gradient text-white">
                                                    <th>Banco *</th>
                                                    <th>Tipo Cuenta</th>
                                                    <th colspan="2">N° Cuenta *</th>
                                                    <th></th>
                                                    <th>Monto *</th>
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
                                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="currency" required>
                                                            <option value="$">$</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control validanumericos3" id="monto" name="monto" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr class="blue-gradient text-white">
                                                    <th>Forma de Pago</th>
                                                    <th>Frecuencia de Pago</th>
                                                    <th colspan="4">Observaciones</th>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="f_pago" required>
                                                            <option value="EFECTIVO">EFECTIVO</option>
                                                            <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                                        </select>
                                                    </td>
                                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="pago" required>
                                                            <option value="UNICO">UNICO</option>
                                                            <option value="PORCENTUAL">PORCENTUAL</option>
                                                        </select>
                                                    </td>
                                                    <td colspan="4">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="obs" name="obs" onkeyup="mayus(this);">
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





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script>
            onload = function() {
                var ele = document.querySelectorAll('.validanumericos')[0];
                var ele1 = document.querySelectorAll('.validanumericos1')[0];
                var ele2 = document.querySelectorAll('.validanumericos2')[0];
                var ele3 = document.querySelectorAll('.validanumericos3')[0];

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
            }

            function cargarCuenta(nombre_r) {
                $('#cuenta').val("R. " + $(nombre_r).val());
            }

            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>