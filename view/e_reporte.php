<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Poliza.php';

$id_rep_com = $_GET['id_rep_com'];

$rep_com = $obj->get_element_by_id('rep_com', 'id_rep_com', $id_rep_com);

$cia = $obj->get_element_by_id('dcia', 'idcia', $rep_com[0]['id_cia']);

$comision = $obj->get_element_by_id('comision', 'id_rep_com', $_GET['id_rep_com']);

$f_pago_gc = date("d-m-Y", strtotime($rep_com[0]['f_pago_gc']));
$f_hasta_rep = date("d-m-Y", strtotime($rep_com[0]['f_hasta_rep']));

$ciaT = $obj->get_element('dcia', 'nomcia');
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
                                <h1 class="font-weight-bold">Compañía: <?= $cia[0]['nomcia']; ?></h1>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" action="e_reporte_n.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Fecha Hasta Reporte</th>
                                                    <th>Fecha Pago GC</th>
                                                    <th>Prima Sujeta a Comisión Total</th>
                                                    <th>Comisión Total</th>
                                                    <th hidden>id reporte</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control datepicker" id="f_rep" name="f_rep" value="<?= $f_hasta_rep; ?>">
                                                        </div>
                                                    </td>
                                                    <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control datepicker" id="f_pago" name="f_pago" value="<?= $f_pago_gc; ?>">
                                                            </div>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td>
                                                            <div class="input-group md-form my-n1" style="background-color: #e0e0e0">
                                                                <input type="text" class="form-control" id="f_pago" name="f_pago" value="<?= $f_pago_gc; ?>" readonly>
                                                            </div>
                                                        </td>
                                                    <?php } ?>

                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="number" step="0.01" class="form-control" name="primat_com" value="<?= $rep_com[0]['primat_com']; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="number" step="0.01" class="form-control" name="comt" value="<?= $rep_com[0]['comt']; ?>">
                                                        </div>
                                                    </td>
                                                    <td hidden>
                                                        <input type="text" class="form-control" name="id_rep_com" value="<?= $id_rep_com; ?>">
                                                    </td>
                                                </tr>

                                                <tr class="blue-gradient text-white">
                                                    <th>Cía</th>
                                                    <th colspan="3">Comentrios</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="cia" name="cia" required searchable="Búsqueda rápida">
                                                            <option value="">Seleccione Compañía</option>
                                                            <?php
                                                            for ($i = 0; $i < sizeof($ciaT); $i++) {
                                                            ?>
                                                                <option value="<?= $ciaT[$i]["idcia"]; ?>"><?= ($ciaT[$i]["nomcia"]); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td colspan="3">
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="comentario_rep" value="<?= $rep_com[0]['comentario_rep']; ?>">
                                                        </div>
                                                    </td>
                                                    <td hidden>
                                                        <input type="text" class="form-control" name="cia_e" id="cia_e" value="<?= $rep_com[0]['id_cia']; ?>">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>



                                    <hr>
                                    <button type="submit" style="width: 100%" data-toggle="tooltip" data-placement="bottom" title="Previsualizar" class="btn dusty-grass-gradient btn-lg" value="">Previsualizar Edición &nbsp;<i class="fa fa-check" aria-hidden="true"></i></button>
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
                $("#cia option[value=" + $('#cia_e').val() + "]").attr("selected", true);
            });

            onload = function() {
                var ele = document.querySelectorAll('.validanumericos')[0];
                var ele1 = document.querySelectorAll('.validanumericos1')[0];

                ele.onkeypress = function(e) {
                    if (isNaN(this.value + String.fromCharCode(e.charCode)))
                        return false;
                }
                ele1.onkeypress = function(e1) {
                    if (isNaN(this.value + String.fromCharCode(e1.charCode)))
                        return false;
                }
                ele1.onpaste = function(e1) {
                    e1.preventDefault();
                }

            };
            
        </script>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>