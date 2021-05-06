<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'crm/prom_prog';

require_once '../../Controller/Poliza.php';
require_once '../../Model/Cliente.php';

$obj1 = new Cliente();

$totalPrimaNR = 0;

// Seteando Filtros
$filtroTPoliza = null;
$filtroCia = null;
$filtroRamo = null;
$filtroAsesor = null;

if ($cia != '') {
    for ($i=0; $i < sizeof($cias); $i++) { 
        $filtroCia .= $cias[$i][0]['nomcia'] . ' - ';
    }
}

if ($ramo != '') {
    for ($i=0; $i < sizeof($ramos); $i++) { 
        $filtroRamo .= $ramos[$i][0]['nramo'] . ' - ';
    }
}

if ($asesor != '') {
    for ($i=0; $i < sizeof($asesores); $i++) { 
        $filtroAsesor .= $asesores[$i][0]['nombre'] . ' - ';
    }
}

if ($t_poliza != '') {
    foreach ($t_poliza as $tipo) {
        if ($tipo == 1) {
            $filtroTPoliza .= ' -PRIMER AÑO- ';
        }
        if ($tipo == 2) {
            $filtroTPoliza .= ' -RENOVACIÓN- ';
        }
        if ($tipo == 3) {
            $filtroTPoliza .= ' -TRASPASO DE CARTERA- ';
        }
        if ($tipo == 4) {
            $filtroTPoliza .= ' -ANEXOS- ';
        }
        if ($tipo == 5) {
            $filtroTPoliza .= ' -REVALORIZACIÓN- ';
        }
    }
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

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold text-center">Programar Mensaje de Promoción a Clientes</h1>

                            <?php if ($t_poliza != '') { ?>
                                <h3 class="font-weight-bold text-center">
                                    Tipo de Póliza: <span class="text-danger">
                                        <?php foreach ($t_poliza as $tipo) {
                                            if ($tipo == 1) {
                                                echo ' -PRIMER AÑO- ';
                                            }
                                            if ($tipo == 2) {
                                                echo ' -RENOVACIÓN- ';
                                            }
                                            if ($tipo == 3) {
                                                echo ' -TRASPASO DE CARTERA- ';
                                            }
                                            if ($tipo == 4) {
                                                echo ' -ANEXOS- ';
                                            }
                                            if ($tipo == 5) {
                                                echo ' -REVALORIZACIÓN- ';
                                            }
                                        } ?>
                                    </span>
                                </h3>
                            <?php } ?>
                            <?php if ($cia != '') {
                                $ciaIn = implode(", ", $cia); ?>
                                <h3 class="font-weight-bold text-center">
                                    Cía: <span class="text-danger">
                                        <?php 
                                        for ($i=0; $i < sizeof($cias); $i++) { 
                                            echo $cias[$i][0]['nomcia'] . ' - ';
                                        }
                                        ?>
                                    </span>
                                </h3>
                            <?php } ?>
                            <?php if ($ramo != '') {
                                $ramoIn = implode(", ", $ramo); ?>
                                <h3 class="font-weight-bold text-center">
                                    Ramo: <span class="text-danger">
                                        <?php 
                                        for ($i=0; $i < sizeof($ramos); $i++) { 
                                            echo $ramos[$i][0]['nramo'] . ' - ';
                                        }
                                        ?>
                                    </span>
                                </h3>
                            <?php } ?>
                            <?php if ($asesor != '') {
                                $asesorIn = implode(", ", $asesor); ?>
                                <h3 class="font-weight-bold text-center">
                                    Asesor: <span class="text-danger">
                                        <?php 
                                        for ($i=0; $i < sizeof($asesores); $i++) { 
                                            echo $asesores[$i][0]['nombre'] . ' - ';
                                        }
                                        ?>
                                    </span>
                                </h3>
                            <?php } ?>
                        </div>
            </div>


            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                <!-- Grid row -->
                <div class="row">

                    <!-- Grid column -->
                    <div class="col-md-10 m-auto">

                        <center><a onclick="programar()" class="btn blue-gradient btn-rounded btn-lg mb-3" data-toggle="tooltip" data-placement="right" title="Programar Mensaje para la Búsqueda Actual" style="color:white">Programar</a></center>

                        <ul class="nav md-pills nav-justified pills-rounded pills-blue-gradient">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#panel100" role="tab">Clientes en Promoción</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel101" role="tab">Tarjeta de Promoción</a>
                            </li>
                        </ul>

                        <!-- Tab panels -->
                        <div class="tab-content card mt-2">

                            <!--Panel 1-->
                            <div class="tab-pane fade in show active" id="panel100" role="tabpanel">

                                <div class="table-responsive-xl">

                                    <!--<div class="form-group">
                                        <div class="col-sm-12 text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkbox" name="checkbox">
                                                <label class="form-check-label font-weight-bold h3" for="checkbox">
                                                Copia a Asesores
                                                </label>
                                            </div>
                                        </div>
                                    </div>-->

                                    <div class="table-responsive-xl" style="text-align: -webkit-center;">
                                        <table class="table" style="width: 70%">
                                            <thead class="blue-gradient text-white text-center">
                                                <tr>
                                                    <th>Fecha de Envío *</th>
                                                    <th>Frecuencia *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" id="fEnvio" name="fEnvio" class="form-control datepicker" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" autocomplete="off">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="number" step="1" id="frecuencia" name="frecuencia" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio | Sólo Enteros" autocomplete="off" value="1">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <table class="table table-hover table-striped table-bordered" id="table_cliente_bp" width="100%">
                                        <thead class="blue-gradient text-white text-center">
                                            <tr>
                                                <th hidden>id</th>
                                                <th hidden>ci</th>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th style="background-color: #E54848;">Cant. Pólizas</th>
                                                <th>Activas</th>
                                                <th>Inactivas</th>
                                                <th>Anuladas</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($titulares as $titular) {
                                                $m = date("m", strtotime($titular['f_nac']));

                                                $totalA = 0;
                                                $totalI = 0;
                                                $totalAn = 0;

                                                $cant = $obj1->get_polizas_t_cliente($titular['id_titular']);
                                                $totalCant = $totalCant + sizeof($cant);

                                                for ($a = 0; $a < sizeof($cant); $a++) {
                                                    $primaSusc = $primaSusc + $cant[$a]['prima'];
                                                    $totalPrima = $totalPrima + $cant[$a]['prima'];

                                                    $no_renov = $obj->verRenov1($cant[$a]['id_poliza']);
                                                    if ($no_renov[0]['no_renov'] != 1) {
                                                        if ($cant[$a]['f_hastapoliza'] >= date("Y-m-d")) {
                                                            $totalA = $totalA + 1;
                                                            $tA = $tA + 1;
                                                        } else {
                                                            $totalI = $totalI + 1;
                                                            $tI = $tI + 1;
                                                        }
                                                    } else {
                                                        $totalAn = $totalAn + 1;
                                                        $tAn = $tAn + 1;
                                                    }
                                                }
                                            ?>
                                                <tr style="cursor: pointer">
                                                    <td hidden><?= $titular['id_titular']; ?></td>
                                                    <td hidden><?= $titular['ci']; ?></td>

                                                    <td><?= $titular['r_social'] . '' . $titular['ci']; ?></td>
                                                    <td class="align-middle">
                                                        <?= $titular['nombre_t'] . ' ' . $titular['apellido_t']; ?>
                                                        <?php if ($titular['email'] != '-') { ?>
                                                            <span class="badge badge-pill badge-info">Email</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center"><?= sizeof($cant); ?></td>
                                                    <td class="text-center"><?= $totalA; ?></td>
                                                    <td class="text-center"><?= $totalI; ?></td>
                                                    <td class="text-center"><?= $totalAn; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>

                                        <tfoot class="text-center">
                                            <tr>
                                                <th hidden>id</th>
                                                <th hidden>ci</th>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th style="background-color: #E54848; color: white">Cant. Pólizas</th>
                                                <th>Activas</th>
                                                <th>Inactivas</th>
                                                <th>Anuladas</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                            <!--/.Panel 1-->

                            <!--Panel 2-->
                            <div class="tab-pane fade" id="panel101" role="tabpanel">

                                <div class="text-center">
                                    <img src="<?= constant('URL') . 'assets/img/tarjeta_promocion.png'; ?>" class="z-depth-1" alt="tarjeta_cumpleaños" style='width: 70%;vertical-align: middle;border-style: none' />
                                </div>

                                <hr />
                                <br>

                                <div class="description text-center">
                                    <form class="" enctype="multipart/form-data" action="" method="POST">
                                        <label class="form-control h4 font-weight-bold col-md-5 mx-auto">Actualice la Tarjeta de Promoción</label>
                                        <input name="uploadedfile" type="file" class="form-group btn btn-outline-info">
                                        <div class="">
                                            <input type="submit" value="Subir archivo" class="form-group btn blue-gradient">
                                        </div>
                                    </form>
                                </div>

                                <?php
                                $uploadedfileload = "true";
                                $uploadedfile_size = $_FILES['uploadedfile']['size'];
                                ?>
                                <h5 class="text-center"><?= $_FILES['uploadedfile']['name']; ?></h5>
                                <?php

                                if ($_FILES['uploadedfile']['size'] > 20000000) {
                                    $msg = $msg . "El archivo es mayor que 200KB, debes reduzcirlo antes de subirlo<BR>";
                                    $uploadedfileload = "false";
                                }

                                if (!($_FILES['uploadedfile']['type'] == "image/jpeg" or $_FILES['uploadedfile']['type'] == "image/jpeg" or $_FILES['uploadedfile']['type'] == "image/png")) {
                                    $msg = $msg . " Tu archivo tiene que ser JPG o PNG. Otros archivos no son permitidos.<BR> En lo posible ser imágen cuadrada (preferiblemente 800 x 800)";
                                    $uploadedfileload = "false";
                                }

                                $file_name = "tarjeta_promocion.jpg";
                                $add = "../../assets/img/$file_name";
                                if ($uploadedfileload == "true") {

                                    if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $add)) {
                                        //solo para el servidor versatil
                                        //$obj->update_user_profile($_SESSION['id_usuario']);
                                ?>
                                        <h5 class="text-center"><?= " Ha sido subido satisfactoriamente"; ?></h5>
                                    <?php

                                    } else {
                                    ?>
                                        <h5 class="text-center"><?= "Error al subir el archivo"; ?></h5>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <h5 class="text-center"><?= $msg; ?></h5>
                                <?php } ?>

                            </div>
                            <!--/.Panel 2-->


                        </div>

                    </div>
                    <!-- Grid column -->

                </div>
                <!-- Grid row -->

            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_cliente.js"></script>
        <script src="../../assets/view/modalN.js"></script>

        <script>
            function programar() {
                var copiaAsesor = 0;
                if($('input:checkbox[name=checkbox]:checked').val() == 'on') {
                    copiaAsesor = 1;
                }

                if($('#fEnvio').val() == '') {
                    alertify.error("La Fecha de Envío es Obligatoria");
                    return false;
                }
                if($('#frecuencia').val() == '') {
                    alertify.error("La Frecuencia de Envío es Obligatoria");
                    return false;
                }

                alertify.confirm('!!', '¿Desea Programar el Mensaje para la búsqueda actual?',
                    function() {
                        window.location.replace("../../procesos/agregarMPprom.php?ramo=<?= $ramoEnv; ?>&t_poliza=<?= $t_polizaEnv; ?>&cia=<?= $ciaEnv; ?>&asesor=<?= $asesorEnv; ?>&filtroTPoliza=<?= $filtroTPoliza; ?>&filtroCia=<?= $filtroCia; ?>&filtroRamo=<?= $filtroRamo; ?>&filtroAsesor=<?= $filtroAsesor; ?>&copiaAsesor="+copiaAsesor+"&fEnvio="+$('#fEnvio').val()+"&frecuencia="+$('#frecuencia').val());
                    },
                    function() {
                        alertify.error('Cancelada')
                    }).set('labels', {
                    ok: 'Sí',
                    cancel: 'No'
                }).set({
                    transition: 'zoom'
                }).show();
            }
        </script>
</body>

</html>