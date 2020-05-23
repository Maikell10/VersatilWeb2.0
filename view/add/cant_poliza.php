<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Controller/Poliza.php';

$idcia = $_GET['cia'];
$cia = $obj->get_element_by_id('dcia', 'idcia', $idcia);

$_SESSION['creado'] = 1;
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
                            <h1 class="font-weight-bold">
                                <i class="fas fa-info-circle" aria-hidden="true"></i>&nbsp;Compañía: <?= ($cia[0]['nomcia']); ?>
                            </h1>
                        </div>
                        <br>

                        <div class="col-md-10 mx-auto">
                            <h2 id="existeRep" class="text-success text-center"><strong></strong></h2>
                            <h2 id="no_existeRep" class="text-danger text-center"><strong></strong></h2>

                            <form action="c_comision.php" class="form-horizontal" method="GET" autocomplete="off" id="frmnuevo">
                                <div class="table-responsive-xl">
                                    <table class="table" width="100%">
                                        <thead class="blue-gradient text-white">
                                            <tr>
                                                <th nowrap>Fecha Hasta *</th>
                                                <th nowrap>Seleccione Cantidad de Pólizas *</th>
                                                <th nowrap>Total Prima Cobrada</th>
                                                <th nowrap>Total Comisión Cobrada</th>
                                                <th nowrap>Fecha Creación GC *</th>
                                                <th hidden>id reporte</th>
                                                <th hidden>cia</th>
                                                <th hidden>cant_poliza</th>
                                                <th hidden></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background-color: white">
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input onchange="validarReporte(this)" type="text" class="form-control datepicker" id="f_hasta" name="f_hasta" required>
                                                    </div>
                                                </td>


                                                <td data-toggle="tooltip" data-placement="right" title="Seleccione un Nº correspondiente a la Cantidad de Pólizas a Cargar"><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="cant_poliza" name="cant_poliza" required>
                                                        <option value="">Seleccione Cant de Pólizas</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="number" step="0.01" class="form-control" id="primat_com" name="primat_com" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input type="number" step="0.01" class="form-control" id="comt" name="comt" required>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="input-group md-form my-n1">
                                                        <input onchange="cargar_f()" type="text" class="form-control datepicker" id="f_pagoGc" name="f_pagoGc" required>
                                                    </div>
                                                </td>

                                                <td hidden><input type="text" class="form-control" id="id_rep" name="id_rep" value="0"></td>
                                                <td hidden><input type="text" class="form-control" id="cia" name="cia" value="<?= $idcia; ?>"></td>
                                                <td hidden><input type="text" class="form-control" id="exx" name="exx"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <center>
                                    <button type="submit" id="btnForm" class="btn blue-gradient btn-lg btn-rounded">Confirmar</button>
                                </center>

                            </form>

                            <div id="load" class="d-flex justify-content-center align-items-center" hidden>
                                <div class="spinner-grow text-info" style="width: 9rem; height: 9rem;" id="load1" hidden></div>
                            </div>
                        </div>
                        <br>

                        <h2 class="text-success text-center" id="sumaP"></h2>
                        <h2 class="text-success text-center" id="sumaP1"></h2>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>
            $(document).ready(function() {
                $(".datepicker").prop('readonly', false);
            })

            function validarReporte(f_hasta) {
                if (f_hasta.value == '') {} else {
                    var fecha = f_hasta.value.split('-').reverse().join('-');
                    let date = new Date(fecha)

                    let dateM = date.getMonth() + 1
                    let dateY = date.getFullYear()

                    date.setDate(date.getDate() + 1)

                    if (10 > date.getMonth() + 2 > 0) {
                        var mes = '0' + (date.getMonth() + 2).toString()
                    } else {
                        var mes = (date.getMonth() + 2)
                    }
                    date = '10-' + mes + '-' + date.getFullYear()

                    let dateHoy = new Date()

                    $.ajax({
                        type: "POST",
                        data: "f_hasta=" + (f_hasta.value.split('-').reverse().join('-')),
                        url: "../../procesos/validarreporte.php?cia=<?= $cia[0]['idcia']; ?>",
                        success: function(r) {
                            datos = jQuery.parseJSON(r);
                            if (datos['id_rep_com'] == null) {
                                $("#existeRep").text('');
                                $("#no_existeRep").text('No se ha creado el Reporte de Comisión para la Cía y Fecha Seleccionada');

                                if ((dateM < dateHoy.getMonth() + 1) || (dateY < dateHoy.getFullYear())) {
                                    dateHoy.setDate(dateHoy.getDate() + 1)

                                    if (10 > dateHoy.getMonth() + 2 > 0) {
                                        var mes = '0' + (dateHoy.getMonth() + 2).toString()
                                    } else {
                                        var mes = (dateHoy.getMonth() + 2)
                                    }
                                    dateHoy = '10-' + mes + '-' + dateHoy.getFullYear()

                                    //if (<?= $_SESSION['id_permiso']; ?> == 1) {
                                    $('#f_pagoGc').pickadate('picker').set('min', dateHoy);
                                    //}

                                    $("#id_rep").val(0);
                                    $('#f_pagoGc').val(dateHoy);
                                    $('#f_pagoGc').pickadate('picker').set('select', dateHoy);
                                    $("#f_pagoGc").css('background-color', 'gold');
                                } else {

                                    //if (<?= $_SESSION['id_permiso']; ?> == 1) {
                                    $('#f_pagoGc').pickadate('picker').set('min', date);
                                    //}

                                    $("#id_rep").val(0);
                                    $('#f_pagoGc').val(date);
                                    $('#f_pagoGc').pickadate('picker').set('select', date);
                                    $("#f_pagoGc").css('background-color', 'gold');
                                }


                                $("#exx").val(0);

                                $("#primat_com").val('');
                                $("#comt").val('');
                            } else {
                                $("#existeRep").text('Reporte de Comisión ya Generado para la Cía y Fecha Seleccionada');
                                $("#no_existeRep").text('');
                                $("#id_rep").val(datos['id_rep_com']);

                                var f_pagoGc = datos['f_pago_gc'].split('-').reverse().join('-');
                                $("#f_pagoGc").val(f_pagoGc);
                                $('#f_pagoGc').pickadate('picker').set('select', f_pagoGc);
                                $("#f_pagoGc").css('background-color', 'white');

                                $("#exx").val(1);
                                $("#primat_com").val(datos['primat_com']);
                                $("#comt").val(datos['comt']);
                                $.ajax({
                                    type: "POST",
                                    data: "id_rep_com=" + (datos['id_rep_com']),
                                    url: "../../procesos/sumar_rep.php?id_rep_com=" + datos['id_rep_com'],
                                    success: function(r) {
                                        datos1 = jQuery.parseJSON(r);
                                        if (datos1['SUM(prima_com)'] == null) {
                                            $("#sumaP").text('No se han cargado comisiones al reporte todavía');
                                        } else {
                                            var restante = new Intl.NumberFormat().format(datos['primat_com'] - datos1['SUM(prima_com)']);
                                            $("#sumaP").text('La Prima Cobrada Pendiente a Cargar es: $' + restante);

                                            var comRestante = new Intl.NumberFormat().format(datos['comt'] - datos1['SUM(comision)']);
                                            $("#sumaP1").text('La Comisión Cobrada Pendiente a Cargar es: $' + comRestante);
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            }

            function cargar_f() {
                $("#f_pagoGc").css('background-color', 'white');
            }
        </script>


</body>

</html>