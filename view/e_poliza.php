<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

//$pag = 'v_poliza';

require_once '../Controller/Poliza.php';

//----Obtengo el permiso del usuario
$permiso = $_SESSION['id_permiso'];
//----------------------

$id_poliza = $_GET['id_poliza'];

$poliza = $obj->get_poliza_total_by_id($id_poliza);

if ($poliza[0]['id_poliza'] == 0) {
    $poliza = $obj->get_poliza_total1_by_id($id_poliza);
}
if ($poliza[0]['id_poliza'] == 0) {
    $poliza = $obj->get_poliza_total2_by_id($id_poliza);
}

$tomador = $obj->get_element_by_id('titular', 'id_titular', $poliza[0]['id_tomador']);
$currency = ($poliza[0]['currency'] == 1) ? "$ " : "Bs ";

$ramo = $obj->get_element('dramo', 'cod_ramo');
$cia = $obj->get_element('dcia', 'nomcia');
$asesor = $obj->get_element('ena', 'idnom');
$referidor = $obj->get_element('enr', 'nombre');
$usuario = $obj->get_element_by_id('usuarios', 'seudonimo', $_SESSION['seudonimo']);
$vehiculo = $obj->get_element_by_id('dveh', 'idveh', $poliza[0]['id_poliza']);
$newfechaV = date("d-m-Y", strtotime($poliza[0]['fechaV']));

$newDesdeP = date("d-m-Y", strtotime($poliza[0]['f_desdepoliza']));
$newHastaP = date("d-m-Y", strtotime($poliza[0]['f_hastapoliza']));
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

            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold">Cliente: <?= utf8_encode($poliza[0]['nombre_t'] . " " . $poliza[0]['apellido_t']); ?></h1>
                            <h2 class="font-weight-bold">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h2>
                            <?php $asesorr = $poliza[0]['cod'] . " -> " . $poliza[0]['nombre']; ?>
                            <h3 class="font-weight-bold">Asesor: <?= utf8_encode($asesorr); ?></h3>
                        </div>
            </div>

            <!-- Comienzo tabla -->
            <div class="card-body p-5">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>N° de Póliza</th>
                                <th>Fecha Desde Seguro</th>
                                <th>Fecha Hasta Seguro</th>
                                <th>Tipo de Póliza</th>
                                <th hidden>id Póliza</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php if ($permiso == 1) { ?>
                                    <td style="background-color:white">
                                        <input type="text" class="form-control" id="n_poliza" name="n_poliza" value="<?= $poliza[0]['cod_poliza']; ?>">
                                    </td>
                                <?php
                                } else {
                                ?>
                                    <td>
                                        <input type="text" class="form-control" id="n_poliza" name="n_poliza" value="<?= $poliza[0]['cod_poliza']; ?>" readonly>
                                    </td>
                                <?php
                                }
                                ?>


                                <td style="background-color:white">
                                    <div class="input-group date">
                                        <input onblur="cargarFechaDesde(this)" type="text" class="form-control" id="desdeP" name="desdeP" required autocomplete="off" value="<?= $newDesdeP; ?>">
                                    </div>
                                </td>
                                <td style="background-color:white">
                                    <div class="input-group date">
                                        <input type="text" class="form-control" id="hastaP" name="hastaP" required autocomplete="off" value="<?= $newHastaP; ?>">
                                    </div>
                                </td>

                                <td style="background-color:white"><select class="custom-select" id="tipo_poliza" name="tipo_poliza" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista">
                                        <option value="1">Primer Año</option>
                                        <option value="2">Renovación</option>
                                        <option value="3">Traspaso de Cartera</option>
                                        <option value="4">Anexos</option>
                                        <option value="5">Revalorización</option>
                                    </select>
                                </td>
                                <td hidden><input type="text" class="form-control" id="id_poliza" name="id_poliza" value="<?= $id_poliza; ?>"></td>
                                <td hidden><input type="text" class="form-control" id="id_tpoliza" name="id_tpoliza" value="<?= $poliza[0]['id_tpoliza']; ?>"></td>

                                <!-- Hidden -->
                                <td hidden><input type="text" class="form-control" id="n_poliza1" name="n_poliza1" value="<?= $poliza[0]['cod_poliza']; ?>"></td>
                                <td hidden><input type="text" class="form-control" id="desdeP1" name="desdeP1" value="<?= $newDesdeP; ?>"></td>
                                <td hidden><input type="text" class="form-control" id="hastaP1" name="hastaP1" value="<?= $newHastaP; ?>"></td>
                                <td hidden><input type="text" class="form-control" id="tipo_poliza1" name="tipo_poliza1" value="<?= $poliza[0]['id_tpoliza']; ?>"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>





    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>


</body>

</html>