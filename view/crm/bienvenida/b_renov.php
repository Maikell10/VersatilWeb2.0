<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'crm/bienvenida/b_renov';

require_once '../../../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="../../crm.php" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Carta de Bienvenida Pólizas Renovadas</h1>
                            </div>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                    <!-- Grid row -->
                    <div class="row">

                        <!-- Grid column -->
                        <div class="col-md-10 m-auto">

                            <ul class="nav md-pills nav-justified pills-rounded pills-blue-gradient">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#panel100" role="tab">Filtros Activos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel101" role="tab">Programar Filtros</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel102" role="tab">Carta de Bienvenida</a>
                                </li>
                            </ul>

                            <!-- Tab panels -->
                            <div class="tab-content card mt-2">

                                <!--Panel 1-->
                                <div class="tab-pane fade in show active" id="panel100" role="tabpanel">

                                    <h4 class="text-center font-weight-bold mb-4">Los Siguientes son a los que no le llegará la Carta de Bienvenida</h4>

                                    <div class="container">
                                        <p class="note note-primary">
                                            <strong>Asesores Restringidos:</strong>
                                            <?php if ($filtro_a == 0) { ?>
                                                No hay Asesores Restringidos.
                                            <?php } else {
                                                for ($i = 0; $i < sizeof($filtro_a); $i++) {
                                                    $asesores = $obj->get_ejecutivo_by_cod($filtro_a[$i]['cod_vend']);
                                                    echo $asesores[0]['nombre'] . ' (' . $asesores[0]['cod'] . '). ';
                                                }
                                            } ?>
                                        </p>

                                        <p class="note note-light">
                                            <strong>Cías Restringidas:</strong>
                                            <?php if ($filtro_c == 0) { ?>
                                                No hay Cías Restringidas.
                                            <?php } else {
                                                for ($i = 0; $i < sizeof($filtro_c); $i++) {
                                                    $cias = $obj->get_element_by_id('dcia', 'idcia', $filtro_c[$i]['id_cia']);
                                                    echo $cias[0]['nomcia'] . '. ';
                                                }
                                            } ?>
                                        </p>

                                        <p class="note note-info">
                                            <strong>Ramos Restringidos:</strong>
                                            <?php if ($filtro_r == 0) { ?>
                                                No hay Ramos Restringidos.
                                            <?php } else {
                                                for ($i = 0; $i < sizeof($filtro_r); $i++) {
                                                    $ramos = $obj->get_element_by_id('dramo', 'cod_ramo', $filtro_r[$i]['cod_ramo']);
                                                    echo $ramos[0]['nramo'] . '. ';
                                                }
                                            } ?>
                                        </p>
                                    </div>

                                </div>
                                <!--/.Panel 1-->

                                <!--Panel 2-->
                                <div class="tab-pane fade" id="panel101" role="tabpanel">

                                    <div class="col-md-8 mx-auto">
                                        <h4 class="text-center font-weight-bold mb-4">Seleccione a los que no le llegará la Carta de Bienvenida</h4>
                                        <form action="renov_prog.php" class="form-horizontal" method="GET">

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Cía:</label>
                                                    <select class="form-control selectpicker" name="cia[]" multiple data-style="btn-white" data-header="Seleccione Cía" data-actions-box="true" data-live-search="true">
                                                        <?php
                                                        for ($i = 0; $i < sizeof($cia); $i++) {
                                                        ?>
                                                            <option value="<?= $cia[$i]["idcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
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
                                                            <option value="<?= $asesor[$i]["cod"]; ?>"><?= utf8_encode($asesor[$i]["nombre"]) . ' (' . $asesor[$i]["cod"] . ')'; ?></option>
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
                                                            <option value="<?= $ramo[$i]["cod_ramo"]; ?>"><?= ($ramo[$i]["nramo"]); ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <center><button type="submit" class="btn aqua-gradient btn-rounded btn-lg">Programar</button></center>
                                        </form>

                                        <div id="load" class="d-flex justify-content-center align-items-center" hidden>
                                            <div class="spinner-grow text-info" style="width: 9rem; height: 9rem;" id="load1" hidden></div>
                                        </div>
                                    </div>

                                </div>
                                <!--/.Panel 2-->

                                <!--Panel 3-->
                                <div class="tab-pane fade" id="panel102" role="tabpanel">

                                    <div style='background-color: #f4f4f4;'>
                                        <br><br><br>
                                        <div style='width: 90%;margin: 0 auto;background-color: white'>
                                            <div style='padding: 30px'>

                                                <center>
                                                    <div>
                                                        <div class='title' style='background-color: #0f4296;color: white;width: 90%;font-size: 2vw'>Estimado Asegurado: <br>
                                                            'Nombre del Asegurado'
                                                            <br> Póliza No.: 'Nº Póliza'
                                                        </div>

                                                        <img src='https://versatilseguros.com/Aplicacion/assets/img/carta.jpg' alt='firma-versatil' style='width: 90%;vertical-align: middle;border-style: none'>
                                                    </div>
                                                </center>

                                                <br>

                                                <h3 style='width: 90%;margin-left: 9%;font-size: 2vw'>Escríbame a: fnavasn@outlook.com</h3>

                                                <br>
                                                <hr style='box-sizing: content-box;
										height: 0;
										overflow: visible;width: 90%'>

                                                <center>
                                                    <p>

                                                    <div style='background-color: #0f4296;color: white;width: 90%'>
                                                        <br>
                                                        <center><a href='https://www.versatilseguros.com'>
                                                                <h3 style='color:white'>www.versatilseguros.com</h3>
                                                            </a></center>
                                                        <center>
                                                            <h4 style='width: 90%'>Boulevard Costa del Este, Edificio Financial Park, Piso 8, Oficina 8-A, Ciudad de Panamá, Telf.: +5073876800-01</h4>
                                                        </center>
                                                        <br>
                                                    </div>

                                                    <br>

                                                    <center><a href='#'>
                                                            <h3 style='width: 90%;font-size: 1.7vw'>Click aquí para ver su póliza pdf</h3>
                                                        </a></center>

                                                    <center><img src='https://versatilseguros.com/Aplicacion/assets/img/footerV.jpg' alt='firma-versatil' style='width: 90%;'></center>

                                                    </p>
                                                </center>

                                            </div>

                                            <br>


                                        </div>
                                        <br><br><br>
                                    </div>



                                </div>
                                <!--/.Panel 3-->

                            </div>

                        </div>
                        <!-- Grid column -->

                    </div>
                    <!-- Grid row -->

                </div>

            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../../assets/view/b_poliza.js"></script>
</body>

</html>