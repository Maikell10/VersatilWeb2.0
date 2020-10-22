<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'b_poliza';

require_once '../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <!--
    <div class="landing darken-3" style="background-image: url(<?= constant('URL') . '/assets/img/logo2.png'; ?>);">
        <div class="container">
            <br><br><br>
            <h1 class="text-center font-weight-bold">Bienvenido <i class="material-icons">person</i></h1>
        </div>
    </div>-->
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="row ml-5 mr-5">
                                <h1 class="font-weight-bold "> Listado de Pólizas Generales</h1>
                                <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                    <a href="add/crear_poliza.php" class="btn blue-gradient ml-auto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Póliza Nueva</a>
                                    
                                <?php } ?>
                            </div>
                            <div class="row ml-5 mr-5">
                                <h5>(Ordenadas por Fecha de Carga)</h5>
                            </div>
                            <br>

                            <?php if (isset($_GET['m'])) {
                                if ($_GET['m'] == 1) { ?>

                                    <div class="alert alert-danger alert-dismissible fade show col-md-8 m-auto" role="alert">
                                        La póliza seleccionada tiene un error en su data o no existe!
                                        <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                <?php }
                                if ($_GET['m'] == 2) { ?>
                                    <div class="alert alert-danger alert-dismissible fade show col-md-8 m-auto" role="alert">
                                        No existen datos para la búsqueda seleccionada!
                                        <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                            <?php }
                            } ?>

                            <div class="col-md-8 mx-auto">
                                <form action="b_poliza1.php" class="form-horizontal">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label align="left">Año Desde Producción:</label>
                                            <select class="form-control selectpicker" name="anio[]" id="anio" multiple data-style="btn-white" data-size="13" data-header="Seleccione Año" data-actions-box="true" data-live-search="true">
                                                <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                    <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                                <?php $fecha_min = $fecha_min + 1;
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Mes Desde Producción:</label>
                                            <select class="form-control selectpicker" name="mes[]" id="mes" multiple data-style="btn-white" data-header="Seleccione Mes" data-actions-box="true" data-live-search="true">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Cía:</label>
                                            <select class="form-control selectpicker" name="cia[]" multiple data-style="btn-white" data-header="Seleccione Cía" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($cia); $i++) {
                                                ?>
                                                    <option value="<?= $cia[$i]["nomcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Asesor:</label>
                                            <select class="form-control selectpicker" name="asesor[]" multiple data-style="btn-white" data-header="Seleccione el Asesor" data-size="12" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($asesor); $i++) {
                                                ?>
                                                    <option value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]).' ('.$asesor[$i]["cod"].')'; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Ramo:</label>
                                            <select class="form-control selectpicker custom-select" name="ramo[]" multiple data-style="btn-white" data-header="Seleccione Ramo" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($ramo); $i++) {
                                                ?>
                                                    <option value="<?= $ramo[$i]["nramo"]; ?>"><?= ($ramo[$i]["nramo"]); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg">Buscar</button></center>
                                </form>

                                <div id="load" class="d-flex justify-content-center align-items-center" hidden>
                                    <div class="spinner-grow text-info" style="width: 9rem; height: 9rem;" id="load1" hidden></div>
                                </div>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">



                    <center><a class="btn dusty-grass-gradient" href="excel/e_b_poliza.php" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th>-</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="background-color: #E54848;">Prima Suscrita</th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                foreach ($polizas as $poliza) {
                                    $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                                    $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                    $totalprima = $totalprima + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                                    $no_renov = $obj->verRenov1($poliza['id_poliza']);
                                ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $poliza['f_poliza']; ?></td>
                                        <td hidden><?= $poliza['id_poliza']; ?></td>


                                        <?php if ($poliza['id_tpoliza'] == 1) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                                        <?php } if ($poliza['id_tpoliza'] == 2) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                                        <?php } if ($poliza['id_tpoliza'] == 3) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                                        <?php } ?>

                                        

                                        <?php if ($no_renov[0]['no_renov'] != 1) {
                                            if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td nowrap><?= $poliza['nombre'].' ('.$poliza['codvend'].')'; ?></td>
                                        <td><?= $poliza['nomcia']; ?></td>
                                        <td><?= $newDesde; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                        <td><?= ($nombre); ?></td>

                                        <?php if ($poliza['pdf'] == 1) { ?>
                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                            <?php } else {
                                            if ($poliza['nramo'] == 'Vida') {
                                                $vRenov = $obj->verRenov3($poliza['id_poliza']);
                                                if ($vRenov != 0) {
                                                    if ($vRenov[0]['pdf'] != 0) {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                        <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                        <?php } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                        if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                        <?php } else { ?>
                                                            <td></td>
                                                        <?php }
                                                    }
                                                } else {
                                                    $poliza_pdf_vida = $obj->get_pdf_vida($poliza['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                    if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                        <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                    <?php } else { ?>
                                                        <td></td>
                                                <?php }
                                                }
                                            } else { ?>
                                                <td></td>
                                            <?php } ?>
                                        <?php } ?>

                                    </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot class="text-center">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th></th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="font-weight: bold" class="text-right">Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <p class="h1 text-center">Total de Prima Suscrita</p>
                    <p class="h1 text-center text-danger">$ <?php echo number_format($totalprima, 2); ?></p>

                    <p class="h1 text-center">Total de Pólizas</p>
                    <p class="h1 text-center text-danger"><?php echo sizeof($polizas); ?></p>
                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
                $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
            ?>

                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-tooltip="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="row ml-5 mr-5">
                                <h1 class="font-weight-bold "> Listado de Pólizas Generales</h1>
                            </div>
                            <div class="row ml-5 mr-5">
                                <h5>(Ordenadas por Fecha de Carga)</h5>
                            </div>
                            <br>

                            <?php if (isset($_GET['m'])) {
                                if ($_GET['m'] == 1) { ?>

                                    <div class="alert alert-danger alert-dismissible fade show col-md-8 m-auto" role="alert">
                                        La póliza seleccionada tiene un error en su data o no existe!
                                        <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                <?php }
                                if ($_GET['m'] == 2) { ?>
                                    <div class="alert alert-danger alert-dismissible fade show col-md-8 m-auto" role="alert">
                                        No existen datos para la búsqueda seleccionada!
                                        <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                            <?php }
                            } ?>

                            <div class="col-md-8 mx-auto">
                                <form action="b_poliza1.php" class="form-horizontal">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label align="left">Año Desde Producción:</label>
                                            <select class="form-control selectpicker" name="anio[]" id="anio" multiple data-style="btn-white" data-size="13" data-header="Seleccione Año" data-actions-box="true" data-live-search="true">
                                                <?php for ($i = $fecha_min; $i <= $fecha_max; $i++) { ?>
                                                    <option value="<?= $fecha_min; ?>"><?= $fecha_min; ?></option>
                                                <?php $fecha_min = $fecha_min + 1;
                                                } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Mes Producción:</label>
                                            <select class="form-control selectpicker" name="mes[]" id="mes" multiple data-style="btn-white" data-header="Seleccione Mes" data-actions-box="true" data-live-search="true">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row" style="text-align: left;">
                                        <div class="form-group col-md-12">
                                            <label>Cía:</label>
                                            <select class="form-control selectpicker" name="cia[]" multiple data-style="btn-white" data-header="Seleccione Cía" data-actions-box="true" data-live-search="true">
                                                <?php
                                                for ($i = 0; $i < sizeof($cia); $i++) {
                                                ?>
                                                    <option value="<?= $cia[$i]["nomcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6" hidden>
                                            <label>Asesor:</label>
                                            <select class="form-control selectpicker" name="asesor[]" data-style="btn-white">
                                                <option value="<?= $user[0]['cod_vend']; ?>">d</option>
                                            </select>
                                        </div>
                                    </div>

                                    <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg">Buscar</button></center>
                                </form>
                            </div>
                </div>

                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                    <center><a class="btn dusty-grass-gradient" href="excel/e_b_poliza.php?session=<?= $_SESSION['id_permiso']; ?>&yhuejd=<?= $_SESSION['id_usuario']; ?>" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="table" width="100%">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th>-</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th style="background-color: #E54848;">Prima Suscrita</th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalprima = 0;
                                $cod_asesor_user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

                                $polizas = $obj->get_poliza_total_by_asesor_ena_user($cod_asesor_user[0]['cod_vend']);
                                foreach ($polizas as $poliza) {
                                    $currency = ($poliza['currency'] == 1) ? "$ " : "Bs ";

                                    $totalsuma = $totalsuma + $poliza['sumaasegurada'];
                                    $totalprima = $totalprima + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $nombre = $poliza['nombre_t'] . ' ' . $poliza['apellido_t'];

                                    $no_renov = $obj->verRenov1($poliza['id_poliza']);
                                ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $poliza['f_poliza']; ?></td>
                                        <td hidden><?= $poliza['id_poliza']; ?></td>

                                        <?php if ($poliza['id_tpoliza'] == 1) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                                        <?php } if ($poliza['id_tpoliza'] == 2) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                                        <?php } if ($poliza['id_tpoliza'] == 3) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                                        <?php } ?>

                                        <?php if ($no_renov[0]['no_renov'] != 1) {
                                            if ($poliza['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                                <td style="color: #2B9E34;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php } else { ?>
                                                <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                            <?php }
                                        } else { ?>
                                            <td style="color: #4a148c;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <?php } ?>

                                        <td nowrap><?= $poliza['nombre'].' ('.$poliza['codvend'].')'; ?></td>
                                        <td><?= $poliza['nomcia']; ?></td>
                                        <td><?= $newDesde; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td class="text-right"><?= $currency . number_format($poliza['prima'], 2); ?></td>
                                        <td><?= ($nombre); ?></td>
                                        <?php if ($poliza['pdf'] == 1) { ?>
                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                        <?php } else { ?>
                                            <td></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th hidden>f_poliza</th>
                                    <th hidden>id</th>
                                    <th>-</th>
                                    <th>N° Póliza</th>
                                    <th>Nombre Asesor</th>
                                    <th>Cía</th>
                                    <th>F Desde Seguro</th>
                                    <th>F Hasta Seguro</th>
                                    <th>Prima Suscrita $<?= number_format($totalprima, 2); ?></th>
                                    <th>Nombre Titular</th>
                                    <th>PDF</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <p class="h1 text-center">Total de Prima Suscrita</p>
                    <p class="h1 text-center text-danger">$ <?php echo number_format($totalprima, 2); ?></p>

                    <p class="h1 text-center">Total de Pólizas</p>
                    <p class="h1 text-center text-danger"><?php echo sizeof($polizas); ?></p>

                </div>


            <?php } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>