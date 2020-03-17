<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Controller/Poliza.php';

$cia = $obj->get_element('dcia', 'nomcia');
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


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5 text-center">
                            <h1 class="font-weight-bold"><i class="fas fa-search-plus" aria-hidden="true"></i> Seleccione Compañía Emisora</h1>
                        </div>
                        <br>

                        <div class="col-md-8 mx-auto">
                            <form action="f_product.php" class="form-horizontal" method="POST">
                                <div class="form-row  col-md-8 m-auto">
                                    <label>Seleccione una Cía:</label>
                                    <select class="form-control selectpicker" name="estructura_neg" id="estructura_neg" data-style="btn-white" required onchange="document.location.href=this.value" data-live-search="true">
                                        <option value="">Seleccione la Compañía</option>
                                        <?php
                                        for ($i = 0; $i < sizeof($cia); $i++) {
                                        ?>
                                            <option value="<?= 'cant_poliza.php?cia=' . $cia[$i]["idcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <!-- Modal TITULAR -->
        <div class="modal fade" id="agregarnuevotitular" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Debe Agregar Nuevo Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoT" autocomplete="off">

                            <div class="form-row">
                                <table class="table table-hover table-striped table-bordered" id="iddatatable">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Razón Social *</th>
                                            <th colspan="3">N° ID Titular</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <div class="form-group col-md-12">
                                            <tr style="background-color: white">
                                                <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="r_sNew" id="r_sNew" required>
                                                        <option value="">Seleccione</option>
                                                        <option value="PN-">PN-</option>
                                                        <option value="J-">J-</option>
                                                    </select>
                                                </td>
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="id_new_titular" name="id_new_titular" readonly="readonly">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan="2">Nombre(s) Titular *</th>
                                                <th colspan="2">Apellido(s) Titular</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="nT_new" name="nT_new" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="aT_new" name="aT_new" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan="2">Celular *</th>
                                                <th colspan="2">Teléfono</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos7" id="cT_new" name="cT_new" required>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos8" id="tT_new" name="tT_new" required>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th>Fecha de Nacimiento</th>
                                                <th colspan="2">Email *</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control datepicker" id="fnT_new" name="fnT_new" required />
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="email" class="form-control" id="eT_new" name="eT_new" required>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan=4>Dirección *</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan=4>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="dT_new" name="dT_new" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan=2>Ocupación</th>
                                                <th colspan=2>Ingreso</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan=2>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="oT_new" name="oT_new" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                                <td colspan=2>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos9" id="iT_new" name="iT_new" value="0" required>
                                                    </div>
                                                </td>
                                            </tr>
                                        </div>
                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnAgregarnuevo" class="btn aqua-gradient">Agregar nuevo</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal TOMADOR -->
        <div class="modal fade" id="agregarnuevotomador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Debe Agregar Nuevo Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoTom" autocomplete="off">

                            <div class="form-row">
                                <table class="table table-hover table-striped table-bordered nowrap" id="iddatatable1">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Razón Social *</th>
                                            <th colspan="3">N° ID Tomador</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <div class="form-group col-md-12">
                                            <tr style="background-color: white">
                                                <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="r_sNewT" id="r_sNewT" required>
                                                        <option value="">Seleccione</option>
                                                        <option value="PN-">PN-</option>
                                                        <option value="J-">J-</option>
                                                    </select>
                                                </td>
                                                <td colspan="3">
                                                    <div class="input-group md-form my-n1 grey lighten-2">
                                                        <input type="text" class="form-control" id="id_new_titularT" name="id_new_titularT" readonly="readonly">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan="2">Nombre(s) Tomador *</th>
                                                <th colspan="2">Apellido(s) Tomador</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="nT_newT" name="nT_newT" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="aT_newT" name="aT_newT" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan="2">Celular *</th>
                                                <th colspan="2">Teléfono</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos10" id="cT_newT" name="cT_newT" required>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos11" id="tT_newT" name="tT_newT" required>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th>Fecha de Nacimiento</th>
                                                <th colspan="2">Email *</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control datepicker" id="fnT_newT" name="fnT_newT" required>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group md-form my-n1">
                                                        <input type="email" class="form-control" id="eT_newT" name="eT_newT" required>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan=4>Dirección *</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan=4>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="dT_newT" name="dT_newT" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="blue-gradient text-white">
                                                <th colspan=2>Ocupación</th>
                                                <th colspan=2>Ingreso</th>
                                            </tr>
                                            <tr style="background-color: white">
                                                <td colspan=2>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control" id="oT_newT" name="oT_newT" required onkeyup="mayus(this);">
                                                    </div>
                                                </td>
                                                <td colspan=2>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="text" class="form-control validanumericos12" id="iT_newT" name="iT_newT" value="0" required>
                                                    </div>
                                                </td>
                                            </tr>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnAgregarnuevoT" class="btn aqua-gradient">Agregar nuevo</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Tarjetas Existentes-->
        <div class="modal fade" id="tarjetaexistente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Seleccione la Tarjeta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ">
                        <form id="frmnuevoP">
                            <div class="table-responsive-xl">
                                <a onclick="selecTarjetaNew()" style="color:black" data-toggle="tooltip" data-placement="top" title="Añadir Tarjeta Nueva" class="btn dusty-grass-gradient btn-sm float-right">Añadir Tarjeta Nueva <i class="fa fa-plus" aria-hidden="true"></i></a>
                                <table class="table table-hover table-striped table-bordered" id="tablaPE">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Nº de Tarjeta</th>
                                            <th>CVV</th>
                                            <th>F Vencimiento</th>
                                            <th>Nombre Tarjetahabiente</th>
                                            <th>Banco</th>
                                            <th>Póliza(s) Asociada(s)</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>

        </script>
</body>

</html>