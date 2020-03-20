<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Controller/Poliza.php';

$nomcia = $_GET['nomcia'];
$cia = $obj->get_element_by_id('dcia', 'nomcia', $nomcia);
$asesor = $obj->get_element('ena', 'idnom');
$cant_a = sizeof($asesor);
$cia[0]['idcia'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
    <style>
        .picker .picker__frame {
            top: 0;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold text-center">Hacer Preferencial a la Cía <?= $cia[0]['nomcia']; ?></h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <form class="form-horizontal" id="frmnuevo" action="comp_pref_n.php" method="POST" onKeypress="if(event.keyCode == 13) event.returnValue = false;">
                <center><button type="submit" id="btnForm" class="btn dusty-grass-gradient btn-lg btn-rounded">Previsualizar</button></center>
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>Fecha Desde Preferida *</th>
                                <th>Fecha Hasta Preferida *</th>
                                <th>%GC a Sumar *</th>
                                <th hidden>nomcia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background-color: white">
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input onchange="cargarFechaDesde(this)" type="text" class="form-control datepicker" id="desdeP" name="desdeP" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control datepicker" id="hastaP" name="hastaP" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input onblur="cargarGC(<?= $cant_a; ?>);" type="text" class="form-control validanumericos" id="per_gc" name="per_gc" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio [Sólo introducir números]">
                                    </div>
                                </td>
                                <td hidden><input type="text" class="form-control" id="nomcia" name="nomcia" value="<?= $cia[0]['nomcia']; ?>"></td>
                            </tr>
                        </tbody>

                    </table>

                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th></th>
                                <th>Nombre Asesor</th>
                                <th>%GC</th>
                                <th>%GC a Sumar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < sizeof($asesor); $i++) {

                            ?>
                                <tr>
                                    <td class="text-center" width="20">
                                        <div class="form-check" style="padding: 0px 0px 0px 0px">
                                            <input type="checkbox" class="form-check-input" id="<?= 'chk' . $i; ?>" value="<?= $asesor[$i]['cod']; ?>" onChange="validarchk(<?= $i; ?>)">
                                            <label class="form-check-label" for="<?= 'chk' . $i; ?>"></label>
                                        </div>
                                    </td>
                                    
                                    <td><?= utf8_encode($asesor[$i]['idnom']) . " [" . $asesor[$i]['cod'] . "]"; ?></td>
                                    <td><?= $asesor[$i]['nopre1'] . " %"; ?></td>
                                    <td style="background-color: white"><input style="text-align:center" type="number" class="form-control validanumericos3" id="<?= 'gc_asesor' . $i; ?>" name="<?= 'gc_asesor' . $i; ?>" min="-90" max="90" data-toggle="tooltip" data-placement="bottom" title="Añadir sólo el numero a sumar al %GC" readonly></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                    </table>
                </div>

                <div class="table-responsive-xl">

                </div>


            </form>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_comp.js"></script>

    <script>
        function cargarGC(cant_a) {
            for (let index = 0; index < cant_a; index++) {
                $('#gc_asesor' + index).val($('#per_gc').val());
            }
        }

        function validarchk(id) {

            var chk = document.getElementById('chk' + id);
            if (chk.checked) {
                $('#gc_asesor' + id).removeAttr('readonly');
            } else {
                $("#gc_asesor" + id).attr("readonly", true);
            }
        }

        function cargarFechaDesde(desdeP) {
            var desdeP = $('#desdeP').val();

            $('#hastaP').val(desdeP);
            $('#hastaP').pickadate('picker').set('select', desdeP);
        }
    </script>
</body>

</html>