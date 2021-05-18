<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../../Controller/Asesor.php';

$cod_proyecto = (isset($_POST['cod_proyecto']) == null) ? $_GET['cod_proyecto'] : $_POST['cod_proyecto'];

if ($cod_proyecto == 1) {
    $lProyecto = $obj->get_element_desc('lider_enp', 'id_proyecto');

    $u = $lProyecto[0]['cod_proyecto'];
    $u = explode('-', $lProyecto[0]['cod_proyecto']);
}
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
                <?php if ($cod_proyecto == 1) { ?>
                    <div class="card-header p-5 animated bounceInDown">
                        <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                            <- Regresar</a> <br><br>
                                <div class="ml-5 mr-5">
                                    <h1 class="font-weight-bold text-center"><i class="fas fa-user-plus" aria-hidden="true"></i>&nbsp;Añadir Nuevo Proyecto</h1>
                                </div>
                                <br><br>

                                <div class="col-md-6 mx-auto">
                                    <form class="form-horizontal" id="frmnuevo" autocomplete="off">
                                        <div class="table-responsive-xl">
                                            <table class="table" width="100%">
                                                <thead class="blue-gradient text-white">
                                                    <tr>
                                                        <th nowrap>Cod Proyecto *</th>
                                                        <th nowrap>Nº ID *</th>
                                                        <th nowrap>Nombre Líder *</th>
                                                        <th nowrap>Nombre del Proyecto</th>
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
                                                                <input type="text" class="form-control validanumericos" id="id_lider" name="id_lider" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input onblur="cargarCuenta(this)" type="text" class="form-control" id="nombre_l" name="nombre_l" required onkeyup="mayus(this);" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" id="cuenta" name="cuenta" required readonly="true">
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr class="blue-gradient text-white">
                                                        <th nowrap colspan="2">Forma de Pago *</th>
                                                        <th nowrap colspan="2">Observaciones</th>
                                                    </tr>
                                                    <tr style="background-color: white">
                                                        <td colspan="2">
                                                            <select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="pago" name="pago" required>
                                                                <option value="ÚNICO MENSUAL">ÚNICO MENSUAL</option>
                                                                <option value="ÚNICO SEMANAL">ÚNICO SEMANAL</option>
                                                                <option value="CONSECUTIVO">CONSECUTIVO</option>
                                                            </select>
                                                        </td>
                                                        <td colspan="2">
                                                            <div class="input-group md-form my-n1">
                                                                <input onkeyup="mayus(this);" type="text" class="form-control" id="obs" name="obs">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <center>
                                            <button type="button" id="btnAgregarProyecto" class="btn blue-gradient btn-lg btn-rounded">Confirmar</button>
                                        </center>
                                    </form>
                                </div>

                                <br><br><br>
                    </div>

                <?php } else {
                    $obj = new Asesor();
                    $lider_p = $obj->get_element_by_id('lider_enp', 'cod_proyecto', $cod_proyecto);
                    $cod_enp = "";
                    $proyecto = $obj->get_ultimo_a_proyecto($lider_p[0]['id_proyecto']);
                    $u = $proyecto[0]['cod'];
                    $u = explode('-', $proyecto[0]['cod']);

                    if ($proyecto[0]['cod'] == null) {
                        $cod_enp = $cod_proyecto . "-1";
                    } else {
                        $cod_enp = $u[0] . "-" . $u[1] . "-" . ($u[2] + 1);
                    }
                ?>
                    <div class="card-header p-5 animated bounceInDown">
                        <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                            <- Regresar</a> <br><br>
                                <div class="ml-5 mr-5">
                                    <h1 class="font-weight-bold text-center"><i class="fas fa-user-plus" aria-hidden="true"></i>&nbsp;Añadir Nuevo Asesor de Proyecto</h1>
                                </div>
                                <br><br>

                                <div class="col-md-9 mx-auto">
                                    <form class="form-horizontal" id="frmnuevo1" autocomplete="off">
                                        <div class="table-responsive-xl">
                                            <table class="table" width="100%">
                                                <thead class="blue-gradient text-white">
                                                    <tr>
                                                        <th hidden>ID Proyecto</th>
                                                        <th>Cod Proyecto *</th>
                                                        <th>Nº ID *</th>
                                                        <th>Nombre Asesor *</th>
                                                        <th></th>
                                                        <th>Monto / Porcentaje % *</th>
                                                        <th>E-Mail *</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="background-color: white">
                                                        <td hidden><input type="text" class="form-control" name="id_proyecto" value="<?= $lider_p[0]['id_proyecto']; ?>"></td>
                                                        <td>
                                                            <div class="input-group md-form my-n1 grey lighten-2">
                                                                <input type="text" class="form-control" name="cod_proyecto" readonly="true" value="<?= $cod_enp; ?>">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control validanumericos" id="id" name="id" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control" id="nombre_a" name="nombre_a" required onkeyup="mayus(this);" data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="currency" required>
                                                                <option value="$">$</option>
                                                                <option value="%" selected>%</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control validanumericos1" id="monto_a" name="monto_a" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números y punto (.) como separador decimal]">
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
                                                        <th>Tipo Cuenta *</th>
                                                        <th colspan="2">N° Cuenta *</th>
                                                        <th>Cel *</th>
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
                                                                <input type="text" class="form-control validanumericos3" id="cel" name="cel" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control" id="obs" name="obs" onkeyup="mayus(this);">
                                                            </div>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <center>
                                            <button type="button" id="btnAgregarAsesorProyecto" class="btn blue-gradient btn-lg btn-rounded">Agregar Nuevo</button>
                                        </center>
                                    </form>
                                </div>

                                <br><br><br>
                    </div>
            <?php }
            } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script>
            $(document).ready(function() {
                alertify.defaults.theme.ok = "btn blue-gradient";
                alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
                alertify.defaults.theme.input = "form-control";

                $('#btnAgregarProyecto').click(function() {
                    if ($("#nombre_l").val().length < 1) {
                        alertify.error("El Nombre es Obligatorio");
                        return false;
                    }
                    if ($("#pago").val().length < 1) {
                        alertify.error("El Pago es Obligatorio");
                        return false;
                    }
                    datos = $('#frmnuevo').serialize();
                    $.ajax({
                        type: "POST",
                        data: datos,
                        url: "../../procesos/agregarProyecto.php",
                        success: function(r) {
                            if (r == 1) {
                                alertify.alert('Exito!', 'Proyecto agregado satisfactoriamente, será redirigido a la selección de Proyecto', function() {
                                    window.location.replace("c_proyecto.php?en=7");
                                    alertify.success('Ok');
                                });
                            } else {
                                window.location.replace("c_proyecto.php?en=7");
                                alertify.error("Fallo al Agregar");
                            }
                        }
                    });
                });



                $('#btnAgregarAsesorProyecto').click(function() {
                    if ($("#id").val().length < 1) {
                        alertify.error("El N° de ID es Obligatorio");
                        return false;
                    }
                    if ($("#nombre_a").val().length < 1) {
                        alertify.error("El Nombre del Asesor es Obligatorio");
                        return false;
                    }
                    if ($("#monto_a").val().length < 1) {
                        alertify.error("El Monto es Obligatorio");
                        return false;
                    }
                    if ($("#email").val().length < 1) {
                        alertify.error("El E-Mail es Obligatorio");
                        return false;
                    }
                    if ($("#banco").val().length < 1) {
                        alertify.error("El Banco es Obligatorio");
                        return false;
                    }
                    if ($("#num_cuenta").val().length < 1) {
                        alertify.error("El N° de Cuenta es Obligatorio");
                        return false;
                    }
                    datos = $('#frmnuevo1').serialize();
                    $.ajax({
                        type: "POST",
                        data: datos,
                        url: "../../procesos/agregarAsesorProyecto.php",
                        success: function(r) {
                            if (r == 1) {
                                $('#frmnuevo1')[0].reset();

                                alertify.alert('Exito!', 'Asesor agregado satisfactoriamente, será redirigido a la página principal', function() {
                                    window.location.replace("../");
                                    alertify.success('Ok');
                                });
                            } else {
                                alertify.error("Fallo al Agregar");
                            }
                        }
                    });
                });

            });

            onload = function() {
                var ele = document.querySelectorAll('.validanumericos')[0];
                var ele1 = document.querySelectorAll('.validanumericos1')[0];
                var ele2 = document.querySelectorAll('.validanumericos2')[0];
                var ele3 = document.querySelectorAll('.validanumericos3')[0];

                ele.onkeypress = function(e) {
                    if (isNaN(this.value + String.fromCharCode(e.charCode)))
                        return false;
                }
                ele1.onkeypress = function(e1) {
                    if (isNaN(this.value + String.fromCharCode(e1.charCode)))
                        return false;
                }
                ele2.onkeypress = function(e2) {
                    if (isNaN(this.value + String.fromCharCode(e2.charCode)))
                        return false;
                }
                ele3.onkeypress = function(e3) {
                    if (isNaN(this.value + String.fromCharCode(e3.charCode)))
                        return false;
                }
            }

            function cargarCuenta(nombre_l) {
                $('#cuenta').val("P. " + $(nombre_l).val());
            }

            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>