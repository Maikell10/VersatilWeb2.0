<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}

$pag = 'renov/renov_g';

require_once '../../Controller/Poliza.php';
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

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold ">Resultado de Búsqueda General de Póliza a Renovar</h1>
                            <h2>Año: <font style="font-weight:bold">
                                    <?= $_POST['anio'];
                                    if ($_POST['mes'] != null) { ?></font>
                                Mes: <font style="font-weight:bold">
                                <?= $mes_arr[$_POST['mes'] - 1];
                                    } ?></font>
                            </h2>

                            <?php if ($cia != '') {
                                $ciaIn = "" . implode(",", $cia) . "";
                            ?>
                                <h2>Cia: <font style="font-weight:bold"><?= $ciaIn; ?></font>
                                </h2>
                            <?php }
                            if ($asesor != '') {
                            ?>
                                Asesor: <font style="font-weight:bold"><?= $myString; ?></font>
                                </h2>
                            <?php } ?>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenovA', 'Pólizas a Renovar por Cía')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenovA" width="100%" style="cursor: pointer;">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>Mes</th>
                                <th>Cía</th>
                                <th>N° Póliza</th>
                                <th>F Hasta Seguro</th>
                                <th>Nombre Titular</th>
                                <th>Ramo</th>
                                <th>Asesor</th>
                                <th>PDF</th>
                                <th></th>
                                <th hidden>id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($a = 0; $a < $cont; $a++) {

                                if ($mes == null) {
                                    $desde1 = [$_POST['anio'] . "-01-01", $_POST['anio'] . "-02-01", $_POST['anio'] . "-03-01", $_POST['anio'] . "-04-01", $_POST['anio'] . "-05-01", $_POST['anio'] . "-06-01", $_POST['anio'] . "-07-01", $_POST['anio'] . "-08-01", $_POST['anio'] . "-09-01", $_POST['anio'] . "-10-01", $_POST['anio'] . "-11-01", $_POST['anio'] . "-12-01"];

                                    $hasta1 = [$_POST['anio'] . "-01-31", $_POST['anio'] . "-02-31", $_POST['anio'] . "-03-31", $_POST['anio'] . "-04-31", $_POST['anio'] . "-05-31", $_POST['anio'] . "-06-31", $_POST['anio'] . "-07-31", $_POST['anio'] . "-08-31", $_POST['anio'] . "-09-31", $_POST['anio'] . "-10-31", $_POST['anio'] . "-11-31", $_POST['anio'] . "-12-31"];

                                    $mes1 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                                } else {
                                    $desde1 = [$desde];
                                    $hasta1 = [$hasta];
                                    $mes1 = [$mes];
                                }

                                $poliza = $obj->get_poliza_total_by_filtro_renov_ac($desde1[$a], $hasta1[$a], $cia, $asesor);

                                if ($poliza == 0) {
                                    //header("Location: b_renov_g.php?m=1");
                                } else {
                            ?>
                                    <tr>
                                        <td rowspan="<?= sizeof($poliza); ?>" style="background-color: #D9D9D9"><?= $mes_arr[$mes1[$a] - 1]; ?></td>

                                        <?php
                                        for ($i = 0; $i < sizeof($poliza); $i++) {
                                            $vRenov = $obj->verRenov($poliza[$i]['id_poliza']);

                                            $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                                            $totalprima = $totalprima + $poliza[$i]['prima'];

                                            $newHasta = date("d/m/Y", strtotime($poliza[$i]['f_hastapoliza']));

                                            $currency = ($poliza[$i]['currency'] == 1) ? "$ " : "Bs ";

                                            $seguimiento = $obj->seguimiento($poliza[$i]['id_poliza']);

                                            if ($poliza[$i]['f_hastapoliza'] >= date("Y-m-d")) {
                                        ?>
                                                <td><?= ($poliza[$i]['nomcia']); ?></td>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php
                                            } else {
                                            ?>
                                                <td><?= ($poliza[$i]['nomcia']); ?></td>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza[$i]['cod_poliza']; ?></td>
                                            <?php } ?>

                                            <td><?= $newHasta; ?></td>
                                            <td><?= utf8_encode($poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t']); ?></td>
                                            <td nowrap><?= utf8_encode($poliza[$i]['nramo']); ?></td>
                                            <td nowrap><?= utf8_encode($poliza[$i]['nombre']); ?></td>
                                            <?php if ($poliza[$i]['pdf'] == 1) { ?>
                                                <td><a href="../download.php?id_poliza=<?= $poliza[$i]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank" style="float: right"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                            <?php } else { ?>
                                                <td></td>
                                            <?php } ?>
                                            <td nowrap>

                                                <?php if ($poliza[$i]['f_hastapoliza'] <= date("Y-m-d")) {
                                                    if ($vRenov == 0) {
                                                        if ($seguimiento == 0) { ?>
                                                            <a href="../v_poliza.php?id_poliza=<?= $poliza[$i]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="En Proceso" class="btn blue-gradient btn-rounded btn-sm btn-block">En Proceso</a>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <a href="../v_poliza.php?modal=true&id_poliza=<?= $poliza[$i]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="En Seguimiento" class="btn morpheus-den-gradient text-white btn-rounded btn-sm btn-block">En Seguimiento</a>
                                                        <?php
                                                        }
                                                        ?>


                                                        <?php } else {
                                                        if ($vRenov[0]['no_renov'] == 0) { ?>
                                                            <a href="../v_poliza.php?id_poliza=<?= $vRenov[0]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Renovada" class="btn aqua-gradient btn-rounded btn-sm btn-block">Renovada</a>
                                                        <?php }
                                                        if ($vRenov[0]['no_renov'] == 1) { ?>
                                                            <a href="../v_poliza.php?modal=true&id_poliza=<?= $poliza[$i]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="No Renovada" class="btn young-passion-gradient btn-rounded btn-sm btn-block text-white">No Renovada</a>
                                                <?php }
                                                    }
                                                } ?>
                                            </td>
                                            <td hidden><?= $poliza[$i]['id_poliza']; ?></td>
                                    </tr>
                                <?php
                                        }
                                ?>
                                <tr class="no-tocar">
                                    <td colspan="9" style="background-color: #F53333;color: white;font-weight: bold">Total <?= $mes_arr[$mes1[$a] - 1]; ?>: <font size=4 color="aqua"><?= sizeof($poliza); ?></font>
                                    </td>
                                </tr>
                        <?php
                                    $totalpoliza = $totalpoliza + sizeof($poliza);
                                }
                            }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Mes</th>
                                <th>Cía</th>
                                <th>N° Póliza</th>
                                <th>F Hasta Seguro</th>
                                <th>Nombre Titular</th>
                                <th>Ramo</th>
                                <th>Asesor</th>
                                <th>PDF</th>
                                <th></th>
                                <th hidden>id</th>
                            </tr>
                        </tfoot>
                    </table>

                    <h1 class="text-center font-weight-bold">Total de Prima Suscrita</h1>
                    <h1 class="text-center font-weight-bold text-danger">$ <?php echo number_format($totalprima, 2); ?></h1>

                    <h1 class="text-center font-weight-bold">Total de Pólizas</h1>
                    <h1 class="text-center font-weight-bold text-danger"><?php echo $totalpoliza; ?></h1>
                </div>

            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <!-- Modal SEGUIMIENTO RENOV-->
        <div class="modal fade" id="seguimientoRenov" tabindex="-1" role="dialog" aria-labelledby="seguimientoRenov" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="seguimientoRenov">Crear Comentario para Seguimiento de la Póliza</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoS" class="md-form">
                            <input type="text" class="form-control" id="id_polizaS" name="id_polizaS" hidden>
                            <input type="text" class="form-control" id="id_usuarioS" name="id_usuarioS" value="<?= $_SESSION['id_usuario']; ?>" hidden>
                            <label for="comentarioS">Ingrese Comentario</label>
                            <textarea class="form-control md-textarea" id="comentarioS" name="comentarioS" required onKeyDown="valida_longitud()" onKeyUp="valida_longitud()" maxlength="300"></textarea>

                            <input type="text" id="caracteres" class="form-control" disabled value="Caracteres restantes: 300">

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn dusty-grass-gradient" id="btnSeguimientoR">Crear</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal NO RENOV-->
        <div class="modal fade" id="noRenov" tabindex="-1" role="dialog" aria-labelledby="noRenov" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noRenov">No Renovar Póliza</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoNR" class="md-form">
                            <input type="text" class="form-control" id="id_polizaNR" name="id_polizaNR" hidden>
                            <input type="text" class="form-control" id="id_usuarioNR" name="id_usuarioNR" value="<?= $_SESSION['id_usuario']; ?>" hidden>
                            <input type="text" class="form-control" id="f_hastaNR" name="f_hastaNR" hidden>

                            <select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="no_renov" name="no_renov" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un Motivo" searchable="Búsqueda rápida">
                                <option value="">Seleccione el Motivo</option>
                                <?php
                                for ($i = 0; $i < sizeof($no_renov); $i++) {
                                ?>
                                    <option value="<?= $no_renov[$i]["id_no_renov"]; ?>"><?= utf8_encode($no_renov[$i]["no_renov_n"]); ?></option>
                                <?php } ?>
                            </select>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn dusty-grass-gradient" id="btnNoRenov">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>