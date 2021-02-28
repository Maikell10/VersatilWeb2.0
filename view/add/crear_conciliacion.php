<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../../Controller/Poliza.php';

$id_rep_com = $_POST['id_rep_com'];


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


            <div class="card-header p-5 animated bounceInDown"> <br><br>
                <div class="ml-5 mr-5 text-center">
                    <h1 class="font-weight-bold">Cargar la Conciliación Bancaria</h1>
                </div>
                <br><br><br>

                <div class="col-md-8 mx-auto">
                    <form id="frmnuevoC" autocomplete="off">
                        <div class="table-responsive-xl">
                            <table class="table table-striped table-hover" width="100%" id="iddatatable">
                                <thead>
                                    <tr class="blue-gradient text-white">
                                        <th class="font-weight-bold">Fecha de Conciliación *</th>
                                        <th class="font-weight-bold">Monto Conciliación *</th>
                                        <th class="font-weight-bold">Comentario</th>
                                        <th hidden>id reporte</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="background-color: white">
                                        <td>
                                            <div class="input-group md-form my-n1">
                                                <input type="text" id="fc_new" name="fc_new" class="form-control datepicker" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1">
                                                <input type="number" class="form-control validanumericos1" id="mc_new" name="mc_new" data-toggle="tooltip" data-placement="bottom" title="Sólo introducir números y punto (.) como separador decimal">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group md-form my-n1">
                                                <input type="text" class="form-control" id="coment_new" name="coment_new" onkeyup="mayus(this);">
                                            </div>
                                        </td>
                                        <td hidden>
                                            <div class="input-group md-form my-n1">
                                                <input type="text" class="form-control" id="id_reporte" name="id_reporte" value="<?= $_GET['id_rep_com'];?>">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <center>
                            <button type="submit" id="btnAgregarcon2" class="btn blue-gradient btn-rounded btn-lg">Agregar Conciliación</button>
                        </center>
                    </form>
                </div>

                <br><br><br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script>
            $(document).ready(function() {
                alertify.defaults.theme.ok = "btn blue-gradient";
                alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
                alertify.defaults.theme.input = "form-control";





            });
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>