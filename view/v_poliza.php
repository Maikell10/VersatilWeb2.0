<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'v_poliza';

require_once '../Controller/Poliza.php';

require_once '../Dropbox/terceros/dropbox/vendor/autoload.php';

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey = constant('DROPBOX_KEY');
$dropboxSecret = constant('DROPBOX_SECRET');
$dropboxToken = constant('DROPBOX_TOKEN');


$app = new DropboxApp($dropboxKey, $dropboxSecret, $dropboxToken);
$dropbox = new Dropbox($app);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div class="card-header p-5 animated bounceInDown">
                <?php if (isset($_GET['m']) == 2) { ?>
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <strong>Póliza Subida correctamente en .pdf!</strong>
                        <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <div class="ml-5 mr-5">

                    <?php if($poliza[0]['id_tpoliza'] == 1) { ?>
                        <h1 class="float-left font-weight-bold mt-5">N</h1>
                    <?php } if($polizap != 0 && $poliza[0]['id_tpoliza'] == 2) { ?>
                        <h1 class="float-left font-weight-bold mt-5">R</h1>
                    <?php } ?>
                    

                    <?php

                    $id_poliza = $poliza[0]['id_poliza'] . ".pdf";

                    $file = $dropbox->search('/', $id_poliza);

                    $var = $file->getData();
                    $nombre_file = $var['matches'][0]['metadata']['name'];

                    $nombre_file;

                    if ($nombre_file) {
                        //echo "<br>";
                        //echo "I found ".$archivo." in directory";
                    ?>
                        <a href="download.php?id_poliza=<?= $poliza[0]['id_poliza']; ?>" class="btn cloudy-knoxville-gradient btn-rounded float-right" target="_blank"><img src="../assets/img/pdf-logo.png" width="60" alt=""></a>
                        <br>
                        <?php } else {
                        if ($poliza[0]['nramo'] == 'Vida') {
                            $vRenovpdf = $obj->verRenov3($poliza[0]['id_poliza']);
                            if ($vRenovpdf != 0) {
                                if ($vRenovpdf[0]['pdf'] != 0) {
                                    $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenovpdf[0]['id_poliza']); ?>

                                    <a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn cloudy-knoxville-gradient btn-rounded float-right" target="_blank"><img src="../assets/img/pdf-logo.png" width="60" alt=""></a>

                                    <?php } else {
                                    $poliza_pdf_vida = $obj->get_pdf_vida($vRenovpdf[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                    if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                        <a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn cloudy-knoxville-gradient btn-rounded float-right" target="_blank"><img src="../assets/img/pdf-logo.png" width="60" alt=""></a>
                                    <?php }
                                }
                            } else {
                                $poliza_pdf_vida = $obj->get_pdf_vida($poliza['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                    <a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn cloudy-knoxville-gradient btn-rounded float-right" target="_blank"><img src="../assets/img/pdf-logo.png" width="60" alt=""></a>
                    <?php }
                            }
                        }
                    } if ($_SESSION['id_permiso'] != 3) { ?>
                    <center>
                        <form class="md-form col-md-4" action="save.php" method="post" enctype="multipart/form-data">
                            <h5 class="text-center">Seleccione la Póliza pdf a cargar</h5>
                            <br>

                            <div class="file-field big">
                                <a class="btn-floating btn-lg red lighten-1 mt-0 float-left">
                                    <i class="fas fa-paperclip" aria-hidden="true"></i>
                                    <input type="file" id="archivo" name="archivo" accept="application/pdf" required>
                                </a>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Eliga un archivo PDF" disabled>
                                </div>
                            </div>

                            <button class="btn dusty-grass-gradient font-weight-bold btn-rounded">Subir Archivo <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></button>
                            <input type="text" name="id_poliza" value="<?= $poliza[0]['id_poliza']; ?>" hidden>
                            <input type="text" class="form-control" name="cond" value="3" hidden>
                        </form>
                    </center>
                    <?php }


                    if ($poliza[0]['nombre_t'] == 'PENDIENTE') { ?>
                        <center><a href="cargar_pp.php?id_poliza=<?= $poliza[0]['id_poliza']; ?>" data-toggle="tooltip" data-placement="top" title="Cargar Póliza Pendiente" class="btn dusty-grass-gradient font-weight-bold btn-lg">Cargar Póliza Pendiente &nbsp;<i class="fas fa-edit" aria-hidden="true"></i></a></center>
                    <?php } ?>


                    <?php if ($_SESSION['id_permiso'] != 3 && $poliza[0]['nombre_t'] != 'PENDIENTE' && $no_renov[0]['no_renov'] != 1) { ?>

                        <?php if ($vRenov != 0) { ?>
                            <a href="v_poliza.php?id_poliza=<?= $vRenov[0]['id_poliza']; ?>" data-toggle="tooltip" data-placement="top" title="Ver la Renovación" class="btn aqua-gradient btn-rounded float-right"><i class="fa fa-check" aria-hidden="true"></i></a>
                        <?php } else {
                            if($polizap != 0 || $_SESSION['id_permiso'] == 1) {
                        ?>
                            <a href="renov/crear_renov.php?id_poliza=<?= $poliza[0]['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Renovar" class="btn dusty-grass-gradient btn-rounded float-right"><i class="fa fa-check-circle" aria-hidden="true"></i></a>
                            
                            <?php } ?>

                            <?php if($polizap == 0 && $no_renov[0]['no_renov'] != 1 && $vRenov == 0) { ?>
                                <span>
                                    <a onclick="noRenovar1(<?= $poliza[0]['id_poliza']; ?>,'<?= $poliza[0]['f_hastapoliza']; ?>')" data-toggle="tooltip" data-placement="top" title="No Renovar" class="btn young-passion-gradient btn-rounded text-white float-right"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                                </span>
                            <?php } else { ?>
                                <span>
                                    <a onclick="noRenovar(<?= $poliza[0]['id_poliza']; ?>,'<?= $poliza[0]['f_hastapoliza']; ?>')" data-toggle="tooltip" data-placement="top" title="No Renovar" class="btn young-passion-gradient btn-rounded text-white float-right"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                                </span>
                            <?php } ?>


                            <span data-toggle="modal" data-target="#seguimientoRenov">
                                <a data-toggle="tooltip" data-placement="top" title="Cargar Seguimiento de Renovación" class="btn blue-gradient btn-rounded text-white float-right"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            </span>
                        <?php } ?>

                    <?php }
                        if($polizap == 0 && $no_renov[0]['no_renov'] != 1 && $vRenov == 0 && $poliza[0]['id_tpoliza'] != 1) { ?>
                            <div class="row">
                                <a class="btn-floating btn-lg btn-danger lighten-1 mt-0" style="cursor:default">
                                    <i class="fas fa-exclamation" aria-hidden="true"></i>
                                </a>
                                
                                <h5 class="mt-3 font-weight-bold text-danger">Póliza Pre-Renovada</h5>
                            </div>
                    <?php } ?>
                    

                    <h1 class="font-weight-bold">Cliente:
                        <?php
                        if ($poliza[0]['nombre_t'] == 'PENDIENTE') {
                            $asegurado = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $poliza[0]['id_poliza']);

                            $nombre = utf8_decode($asegurado[0]['asegurado']);
                        } else {
                            $nombre = utf8_decode($poliza[0]['nombre_t'] . " " . $poliza[0]['apellido_t']);
                        }
                        echo utf8_encode($nombre); ?>
                    </h1>

                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                            <h2 style="color: #2B9E34" class="font-weight-bold">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h2>
                        <?php } else { ?>
                            <h2 style="color: #E54848" class="font-weight-bold">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h2>
                        <?php }
                    } else { ?>
                        <h2 style="color: #4a148c" class="font-weight-bold">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h2>
                    <?php } ?>

                    <?php
                    if (isset($poliza[0]['idnom']) == null) {
                        $asesorr = $poliza[0]['cod'] . " -> " . $poliza[0]['nombre'];
                    } else {
                        $asesorr = $poliza[0]['cod'] . " -> " . $poliza[0]['idnom'];
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="font-weight-bold">Asesor: <?= utf8_encode($asesorr); ?></h3>
                        </div>

                        <div class="col-md-4">
                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                    <h2 style="color: #2B9E34" class="font-weight-bold float-right"><?= "ACTIVA"; ?></h2>
                                <?php } else { ?>
                                    <h2 style="color: #E54848" class="font-weight-bold float-right"><?= "INACTIVA"; ?></h2>
                                <?php }
                            } else { ?>
                                <h2 style="color: #4a148c" class="font-weight-bold float-right"><?= "ANULADA"; ?></h2>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comienzo tabla -->
            <div class="card-body p-5">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>N° de Póliza</th>
                                <th>Status</th>
                                <th>Fecha Desde Seguro</th>
                                <th>Fecha Hasta Seguro</th>
                                <th>Tipo de Póliza</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $newDesdeP = date("d/m/Y", strtotime($poliza[0]['f_desdepoliza']));
                            $newHastaP = date("d/m/Y", strtotime($poliza[0]['f_hastapoliza']));
                            $newfechaV = date("d/m/Y", strtotime($poliza[0]['fechaV']));
                            ?>
                            <tr>
                                <td><?= $poliza[0]['cod_poliza']; ?></td>

                                <?php if ($no_renov[0]['no_renov'] != 1) {
                                    if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                        <td style="color: #2B9E34;font-weight: bold; font-size: 20px"><?= "ACTIVA"; ?></td>
                                    <?php } else { ?>
                                        <td style="color: #E54848;font-weight: bold; font-size: 20px"><?= "INACTIVA"; ?></td>
                                    <?php }
                                } else { ?>
                                    <td style="color: #4a148c;font-weight: bold; font-size: 20px"><?= "ANULADA"; ?></td>
                                <?php } ?>

                                <td><?= $newDesdeP; ?></td>
                                <td><?= $newHastaP; ?></td>
                                <td><?= ($poliza[0]['tipo_poliza']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>Ramo</th>
                                <th>Compañía</th>
                                <th>Suma Asegurada</th>
                                <th style="background-color: #E54848;">Prima Suscrita</th>
                                <th>Periocidad de Pago</th>
                                <th>Tipo de Cuenta</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td><?= utf8_encode($poliza[0]['nramo']); ?></td>
                                <td><?= ($poliza[0]['nomcia']); ?></td>
                                <td><?= $currency . number_format($poliza[0]['sumaasegurada'], 2); ?></td>
                                <td><?= $currency . number_format($poliza[0]['prima'], 2); ?></td>
                                <td><?= $poliza[0]['fpago']; ?></td>
                                <td><?= $cond = ($poliza[0]['t_cuenta'] == 1) ? 'Individual' : 'Colectiva'; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <?php if ($poliza[0]['forma_pago'] == 1 || $poliza[0]['forma_pago'] == 3) { ?>
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Forma de Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $cond = ($poliza[0]['forma_pago'] == 1) ? 'ACH (CARGO EN CUENTA)' : 'PAGO VOLUNTARIO'; ?></td>
                                </tr>
                            </tbody>
                        <?php } else { ?>
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Forma de Pago</th>
                                    <th>Nº Tarjeta</th>
                                    <th>CVV</th>
                                    <th>Fecha de Vencimiento</th>
                                    <th>Nombre Tarjetahabiente</th>
                                    <th>Banco</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TARJETA DE CREDITO / DEBITO</td>
                                    <td><?= $poliza[0]['n_tarjeta']; ?></td>
                                    <td><?= $poliza[0]['cvv']; ?></td>
                                    <td><?= $newfechaV; ?></td>
                                    <td><?= utf8_decode($poliza[0]['nombre_titular']); ?></td>
                                    <td><?= $poliza[0]['banco']; ?></td>
                                </tr>
                            </tbody>
                        <?php
                        }
                        ?>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>N° Recibo</th>
                                <th>Fecha Desde Recibo</th>
                                <th>Fecha Hasta Recibo</th>
                                <th>Zona de Produc</th>
                                <th>N° de Cuotas</th>
                                <th>Monto Cuotas</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $newDesdeR = date("d/m/Y", strtotime($poliza[0]['f_desderecibo']));
                            $newHastaR = date("d/m/Y", strtotime($poliza[0]['f_hastarecibo']));

                            if ($poliza[0]['id_zproduccion'] == 1) {
                                $z_produc = "PANAMA";
                            }
                            if ($poliza[0]['id_zproduccion'] == 2) {
                                $z_produc = "CARACAS";
                            }
                            ?>
                            <tr>
                                <td><?= $poliza[0]['cod_recibo']; ?></td>
                                <td><?= $newDesdeR; ?></td>
                                <td><?= $newHastaR; ?></td>
                                <td><?= $z_produc; ?></td>
                                <td><?= $poliza[0]['ncuotas']; ?></td>
                                <td><?= $currency . number_format($poliza[0]['montocuotas'], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $poliza[0]['obs_p']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- -----------------SI ES PÓLIZA PENDIENTE NO MOSTRAR------------------ -->
                <?php
                if ($poliza[0]['nombre_t'] != 'PENDIENTE') {
                ?>

                    <div class="col-md-auto col-md-offset-2">
                        <h2 class="font-weight-bold">Datos del Titular</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Fecha Nacimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $newFnac = date("d/m/Y", strtotime($poliza[0]['f_nac']));
                                $sexo = "JURÍDICO";
                                $ecivil = "DIVORCIADO(A)";
                                if ($poliza[0]['id_sexo'] == 1) {
                                    $sexo = "MASCULINO";
                                }
                                if ($poliza[0]['id_sexo'] == 2) {
                                    $sexo = "FEMENINO";
                                }
                                if ($poliza[0]['id_ecivil'] == 1) {
                                    $ecivil = "SOLTERO(A)";
                                }
                                if ($poliza[0]['id_ecivil'] == 2) {
                                    $ecivil = "CASADO(A)";
                                }
                                if ($poliza[0]['id_ecivil'] == 3) {
                                    $ecivil = "JURÍDICO";
                                }
                                ?>
                                <tr>
                                    <td><?= $poliza[0]['ci']; ?></td>
                                    <td><?= ($poliza[0]['nombre_t']); ?></td>
                                    <td><?= ($poliza[0]['apellido_t']); ?></td>
                                    <td><?= $newFnac; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Celular</th>
                                    <th>Teléfono</th>
                                    <th>email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $poliza[0]['cell']; ?></td>
                                    <td><?= $poliza[0]['telf']; ?></td>
                                    <td><?= $poliza[0]['email']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= utf8_encode($poliza[0]['direcc']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-auto col-md-offset-2">
                        <h2 class="font-weight-bold">Datos del Tomador</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $tomador[0]['ci']; ?></td>
                                    <td><?= ($tomador[0]['nombre_t']); ?></td>
                                    <td><?= ($tomador[0]['apellido_t']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <?php
                    if ($poliza[0]['id_cod_ramo'] == 2 || $poliza[0]['id_cod_ramo'] == 25) {
                    ?>
                        <div id="tablaveh">

                            <div class="col-md-auto col-md-offset-2">
                                <h2 class="font-weight-bold">Datos del Vehículo</h2>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered">
                                    <thead class="blue-gradient text-white">
                                        <tr>
                                            <th>Placa</th>
                                            <th>Marca</th>
                                            <th>Modelo</th>
                                            <th>Tipo</th>
                                            <th>Año</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <div class="form-group col-md-12">
                                            <tr>
                                                <td><?= $vehiculo[0]['placa']; ?></td>
                                                <td><?= $vehiculo[0]['marca']; ?></td>
                                                <td><?= $vehiculo[0]['mveh']; ?></td>
                                                <td><?= $vehiculo[0]['tveh']; ?></td>
                                                <td><?= $vehiculo[0]['f_veh']; ?></td>
                                            </tr>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                    <div class="col-md-auto col-md-offset-2">
                        <h2 class="font-weight-bold">Datos del Asesor</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Código Asesor</th>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>GC Póliza</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $poliza[0]['cod']; ?></td>
                                    <td><?= $poliza[0]['id']; ?></td>
                                    <td><?php
                                        if (isset($poliza[0]['idnom']) == null) {
                                            echo ($poliza[0]['nombre']);
                                        } else {
                                            echo ($poliza[0]['idnom']);
                                        }
                                        ?></td>
                                    <td><?php
                                        if ($as == 1) {
                                            echo $poliza[0]['per_gc'] . " %";
                                        }
                                        if ($as == 2) {
                                            echo $poliza[0]['currencyM'] . ' ' . $poliza[0]['per_gc'];
                                        }
                                        if ($as == 3) {
                                            echo $poliza[0]['currencyM'] . ' ' . $poliza[0]['per_gc'];
                                        }
                                        if ($as == 4) {
                                            echo 'Modulo sin asignar';
                                        }
                                        ?></td>

                                </tr>
                                <?php if ($cia_pref[0]['per_gc_sum'] != null && $ramo != 35) { ?>
                                    <tr class="blue-gradient text-white">
                                        <th>Cía Preferencial</th>
                                        <th colspan="2">% GC Base Asesor</th>
                                        <th>% GC Preferencial</th>
                                    </tr>
                                    <tr>
                                        <td><?= 'Sí' ?></td>
                                        <td colspan="2"><?= $poliza[0]['nopre1'] . " %"; ?></td>
                                        <td><?= $cia_pref[0]['per_gc_sum'] . " %"; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <hr>
                    <center>

                        <span data-toggle="modal" data-target="#pagos">
                            <a class="btn blue-gradient btn-lg" data-toggle="tooltip" title="Ver Pagos" data-placement="top">Pagos &nbsp;<i class="fas fa-money-bill-alt"></i></a>
                        </span>

                        <?php if ($_SESSION['id_permiso'] != 3) { ?>
                            <span data-toggle="modal" data-target="#seguimiento">
                                <a class="btn peach-gradient btn-lg" data-toggle="tooltip" title="Ver Seguimiento" data-placement="top">Seguimiento &nbsp;<i class="fas fa-eye"></i></a>
                            </span>

                            <?php
                            if ($poliza[0]['nombre_t'] == 'PENDIENTE') {
                            } else {
                                $comval = ($polizap[0]['comision'] == null) ? 101011 : 101110;
                            ?>
                                <a href="e_poliza.php?id_poliza=<?= $poliza[0]['id_poliza']; ?>&comval=<?= $comval; ?>" data-toggle="tooltip" data-placement="top" title="Editar" class="btn dusty-grass-gradient btn-lg">Editar Póliza &nbsp;<i class="fas fa-edit"></i></a>
                            <?php
                            }
                            if ($_SESSION['id_permiso'] == 1) {
                            ?>
                                <button onclick="eliminarPoliza('<?= $poliza[0]['id_poliza']; ?>','<?= $_SESSION['id_usuario']; ?>','<?= $poliza[0]['cod_poliza']; ?>','<?= $poliza[0]['nombre_t'] . ' ' . $poliza[0]['apellido_t']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient text-white btn-lg">Eliminar Póliza &nbsp;<i class="fas fa-trash-alt"></i></button>
                        <?php }
                        } ?>
                    </center>
                    <hr>

                    <h5 class="text-center"><strong>Cliente: </strong><?= ($poliza[0]['nombre_t'] . " " . $poliza[0]['apellido_t']); ?> |
                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                <strong style="color: #2B9E34">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></strong>
                            <?php } else { ?>
                                <strong style="color: #E54848">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></strong>
                            <?php }
                        } else { ?>
                            <strong style="color: #4a148c">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></strong>
                        <?php } ?>
                    </h5>



                <?php } ?>
            </div>
        </div>
    </div>





    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

    <!-- Modal PAGOS -->
    <div class="modal fade" id="pagos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <div class="col-md-2">
                        <a href="v_pago.php?id_poliza=<?= $poliza[0]['id_poliza']; ?>" target="_blank" class="btn blue-gradient" data-toggle="tooltip" title="Ver Pagos Pestaña Nueva" data-placement="top"><i class="fa fa-window-maximize" aria-hidden="true"></i></a>
                    </div>


                    <div class="col-md-9 text-right">
                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                <h2 style="color: #2B9E34" class="font-weight-bold"><?= "ACTIVA"; ?></h2>
                            <?php } else { ?>
                                <h2 style="color: #E54848" class="font-weight-bold"><?= "INACTIVA"; ?></h2>
                            <?php }
                        } else { ?>
                            <h2 style="color: #4a148c" class="font-weight-bold"><?= "ANULADA"; ?></h2>
                        <?php } ?>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <?php if($poliza[0]['id_tpoliza'] == 1) { ?>
                        <h1 class="font-weight-bold">N</h1>
                    <?php } if($polizap != 0 && $poliza[0]['id_tpoliza'] == 2) { ?>
                        <h1 class="font-weight-bold">R</h1>
                    <?php } ?>

                    <h5 class="modal-title" id="exampleModalLabel"><strong>Cliente: </strong><?= ($poliza[0]['nombre_t'] . " " . $poliza[0]['apellido_t']); ?> |
                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                <strong style="color: #2B9E34">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></strong>
                            <?php } else { ?>
                                <strong style="color: #E54848">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></strong>
                            <?php }
                        } else { ?>
                            <strong style="color: #4a148c">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></strong>
                        <?php } ?>
                    </h5>

                    <hr>
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Asesor:</strong>
                        <?php
                        $asesorr = (isset($poliza[0]['idnom']) == null) ? $poliza[0]['cod'] . " -> " . $poliza[0]['nombre'] : $poliza[0]['cod'] . " -> " . $poliza[0]['idnom'];
                        echo ($asesorr);
                        ?>
                    </h5>
                    <hr>

                    <h5 class="modal-title" id="exampleModalLabel"><strong>Fecha Desde Seg: </strong><?= $newDesdeP; ?> | <strong>Fecha Hasta Seg: </strong><?= $newHastaP; ?></h5>
                    <hr>

                    <h5 class="modal-title" id="exampleModalLabel"><strong>Cía: </strong><?= ($poliza[0]['nomcia']); ?> | <strong>Ramo: </strong><?= ($poliza[0]['nramo']); ?> | <strong>Nº de Cuotas: </strong><?= $poliza[0]['ncuotas']; ?></h5>

                    <hr>

                    <h5 class="modal-title" id="exampleModalLabel"><strong>Observaciones: </strong><?= $poliza[0]['obs_p']; ?></h5>
                    <hr>

                    <form id="frmnuevoP">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered" id="tableModalPago">
                                <thead class="blue-gradient text-white">
                                    <tr class="text-center">
                                        <th hidden>id</th>
                                        <th class="align-middle">Ejecutivo</th>
                                        <th class="align-middle">Prima Cobrada</th>
                                        <th class="align-middle">F Pago Prima</th>
                                        <th class="align-middle">Comisión Cobrada</th>
                                        <th class="align-middle">% Com</th>
                                        <th class="align-middle">F Hasta Reporte</th>
                                        <th class="align-middle">GC Pagada</th>
                                        <?php if($as != 1 && $poliza[0]['currencyM'] == '%' ) { ?>
                                        <th class="align-middle">% GC</th>
                                        <?php } else {  ?>
                                        <th class="align-middle">Monto GC</th>
                                        <?php } ?>
                                        <th class="align-middle">F Pago GC</th>
                                    </tr>
                                </thead>
                                <?php
                                
                                if ($polizap[0]['comision'] != null) {

                                    for ($i = 0; $i < sizeof($polizap); $i++) {
                                        $totalprimaC = $totalprimaC + $polizap[$i]['prima_com'];
                                        $totalcomisionC = $totalcomisionC + $polizap[$i]['comision'];
                                        $totalGC = $totalGC + (($polizap[$i]['comision'] * $polizap[$i]['per_gc']) / 100);

                                        $newFPago = date("d/m/Y", strtotime($polizap[$i]['f_pago_prima']));
                                        $newFHastaR = date("d/m/Y", strtotime($polizap[$i]['f_hasta_rep']));
                                        $newFPagoGC = date("d/m/Y", strtotime($polizap[$i]['f_pago_gc']));

                                        $ejecutivo = $obj->get_ejecutivo_by_cod($polizap[$i]['cod_vend']);

                                        $pGCpago = ($polizap[$i]['comision'] * $polizap[$i]['per_gc']) / 100;

                                        if(substr($poliza[0]['cod'], 0, 1) == 'P' || substr($poliza[0]['cod'], 0, 1) == 'R') {
                                            $polizapp = $obj->get_comision_proyecto_by_id($id_poliza);
                                            $pGCpago = $polizapp[0]['monto_p'];
                                            $totalGC = $polizapp[0]['monto_p'];
                                            $newFPagoGC = date("d/m/Y", strtotime($polizapp[0]['f_pago_gc_r']));
                                        }
                                ?>
                                        <tr style="cursor: pointer">
                                            <td hidden><?= $polizap[$i]['id_rep_com']; ?></td>
                                            <td class="align-middle p-2"><?= $ejecutivo[0]['nombre']; ?></td>
                                            <td class="align-middle" align="right"><?= number_format($polizap[$i]['prima_com'], 2); ?></td>
                                            <td class="align-middle"><?= $newFPago; ?></td>
                                            <td class="align-middle" align="right"><?= number_format($polizap[$i]['comision'], 2); ?></td>
                                            <td class="align-middle" align="right"><?= number_format(($polizap[$i]['comision'] * 100) / $polizap[$i]['prima_com'], 2); ?></td>

                                            <td class="align-middle" nowrap><?= $newFHastaR; ?></td>
                                            <td class="align-middle" align="right"><?= number_format($pGCpago, 2); ?></td>

                                            <td class="align-middle" align="right"><?= number_format($polizap[$i]['per_gc'], 2); ?></td>

                                            <td class="align-middle" nowrap><?= $newFPagoGC; ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                $primaPendiente = number_format($poliza[0]['prima'] - $totalprimaC, 2);
                                ?>
                                <tr style="background-color: #F53333;color: white">
                                    <td></td>
                                    <td class="font-weight-bold">Prima Cobrada: <?= $currency . number_format($totalprimaC, 2); ?></td>
                                    <td class="font-weight-bold">Prima Suscrita: <?= $currency . number_format($poliza[0]['prima'], 2); ?></td>
                                    <td class="font-weight-bold">Comisión Cobrada: <?= $currency . number_format($totalcomisionC, 2); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td class="font-weight-bold">GC Pagada: <?= $currency . number_format($totalGC, 2); ?></td>
                                    <td></td>
                                    <td class="font-weight-bold"></td>
                                </tr>
                            </table>
                        </div>
                    </form>
                    <?php if ($primaPendiente > 0) { ?>
                        <h2>Prima Pendiente: <span class="text-danger"><?= $currency . $primaPendiente; ?></span></h2>
                    <?php }
                    if ($primaPendiente == 0) { ?>
                        <h2>Prima Pendiente: <?= $currency . $primaPendiente; ?></h2>
                    <?php }
                    if ($primaPendiente < 0) { ?>
                        <h2>Prima Pendiente: <span class="text-success"><?= $currency . $primaPendiente; ?></span></h2>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal SEGUIMIENTO -->
    <div class="modal fade" id="seguimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-8">
                        <h3 class="modal-title" id="exampleModalLabel">Seguimiento de la
                            <?php if ($no_renov[0]['no_renov'] != 1) {
                                if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                    <strong style="color: #2B9E34">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></strong>
                                <?php } else { ?>
                                    <strong style="color: #E54848">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></strong>
                                <?php }
                            } else { ?>
                                <strong style="color: #4a148c">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></strong>
                            <?php } ?>
                        </h3>
                    </div>

                    <div class="col-md-3 float-right">
                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d")) { ?>
                                <h2 style="color: #2B9E34" class="font-weight-bold float-right"><?= "ACTIVA"; ?></h2>
                            <?php } else { ?>
                                <h2 style="color: #E54848" class="font-weight-bold float-right"><?= "INACTIVA"; ?></h2>
                            <?php }
                        } else { ?>
                            <h2 style="color: #4a148c" class="font-weight-bold float-right"><?= "ANULADA"; ?></h2>
                        <?php } ?>
                    </div>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-auto col-md-offset-2">
                        <h2 class="title">Usuario que Generó la Póliza</h2>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th>Nombre Usuario</th>
                                    <th>Fecha y Hora Creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $newCreated = date("d/m/Y", strtotime($poliza[0]['created_at']));
                                    $newCreatedH = date("h:i:s a", strtotime($poliza[0]['created_at']));
                                    ?>
                                    <td><?= utf8_encode($usuario[0]['nombre_usuario']) . " " . utf8_encode($usuario[0]['apellido_usuario']); ?></td>
                                    <td><?= $newCreated . " " . $newCreatedH; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- AQUI VA LA TABLA SI LA POLIZA TIENE ALGUNA EDICION -->
                    <?php
                    $poliza_ed = $obj->get_element_by_id('poliza_ed', 'id_poliza', $id_poliza);

                    if ($poliza_ed[0]['id_poliza']) {
                    ?>
                        <div class="col-md-auto col-md-offset-2">
                            <h2 class="title">Ediciones de la Póliza</h2>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th>Seudónimo Usuario</th>
                                        <th>Fecha y Hora de Edición</th>
                                        <th>Campos Editados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < sizeof($poliza_ed); $i++) {
                                        $newCreated = date("d/m/Y", strtotime($poliza_ed[$i]['created_at']));
                                        $newCreatedH = date("h:i:s a", strtotime($poliza_ed[$i]['created_at']));
                                    ?>
                                        <tr>
                                            <td><?= $poliza_ed[$i]['usuario']; ?></td>
                                            <td><?= $newCreated . " " . $newCreatedH; ?></td>
                                            <td><?= $poliza_ed[$i]['campos_ed']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>

                    <!-- AQUI VA LA TABLA SI LA POLIZA TIENE ALGUN SEGUIMIENTO -->
                    <?php
                    $seguimiento = $obj->get_seguimiento($id_poliza);

                    if ($seguimiento[0]['id_seg'] || $no_renov[0]['no_renov'] == 1) {
                    ?>
                        <hr>
                        <div class="col-md-auto col-md-offset-2">
                            <h2 class="title">Seguimiento de Renovación</h2>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th>Nombre Usuario</th>
                                        <th>Comentario</th>
                                        <th>Fecha y Hora Creación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($no_renov[0]['no_renov'] == 1) {
                                        $newCreated = date("d/m/Y", strtotime($no_renov[0]['created_at']));
                                        $newCreatedH = date("h:i:s a", strtotime($no_renov[0]['created_at']));
                                    ?>
                                        <tr style="color: #4a148c">
                                            <td class="font-weight-bold"><?= $no_renov[0]['nombre_usuario'] . " " . $no_renov[0]['apellido_usuario']; ?></td>
                                            <td class="font-weight-bold"><?= $no_renov[0]['no_renov_n'] ?></td>
                                            <td class="font-weight-bold"><?= $newCreated . " " . $newCreatedH; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    for ($i = 0; $i < sizeof($seguimiento); $i++) {
                                        $newCreated = date("d/m/Y", strtotime($seguimiento[$i]['created_at']));
                                        $newCreatedH = date("h:i:s a", strtotime($seguimiento[$i]['created_at']));
                                    ?>
                                        <tr>
                                            <td><?= $seguimiento[$i]['nombre_usuario'] . " " . $seguimiento[$i]['apellido_usuario']; ?></td>
                                            <td><?= $seguimiento[$i]['comentario'] ?></td>
                                            <td><?= $newCreated . " " . $newCreatedH; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

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
                        <input type="text" class="form-control" id="id_polizaS" name="id_polizaS" value="<?= $_GET['id_poliza']; ?>" hidden>
                        <input type="text" class="form-control" id="id_usuarioS" name="id_usuarioS" value="<?= $_SESSION['id_usuario']; ?>" hidden>
                        <label for="comentarioS">Ingrese Comentario</label>
                        <textarea class="form-control md-textarea" id="comentarioS" name="comentarioS" required onKeyDown="valida_longitud()" onKeyUp="mayus(this);valida_longitud()" maxlength="300"></textarea>

                        <input type="text" id="caracteres" class="form-control text-danger" disabled value="Caracteres restantes: 300">

                        <br>

                        <span data-toggle="tooltip" data-placement="top" title="Al seleccionar un Comentario rápido, el comentario normal queda sin validez para la carga actual">
                            <select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="comentarioSs" name="comentarioSs" searchable="Búsqueda rápida" title="Seleccione Comentario Rápido">
                                <option value="0">Seleccione Comentario Rápido</option>
                                <option value="SE SOLICITO LA POLIZA A LA CIA">SE SOLICITO LA POLIZA A LA CIA</option>
                                <option value="SE ENVIO LA POLIZA AL ASEGURADO">SE ENVIO LA POLIZA AL ASEGURADO</option>
                                <option value="SE ENVIO LA POLIZA AL ASEGURADO POR SEGUNDA VEZ">SE ENVIO LA POLIZA AL ASEGURADO POR SEGUNDA VEZ</option>
                                <option value="SE ENVIO LA POLIZA AL CORREDOR PARA SU TRAMITACION">SE ENVIO LA POLIZA AL CORREDOR PARA SU TRAMITACION</option>
                                <option value="SE LLAMO AL ASEGURADO Y SE LE OFRECIO LA POLIZA">SE LLAMO AL ASEGURADO Y SE LE OFRECIO LA POLIZA</option>
                                <option value="A LA ESPERA DE RESPUESTA DEL ASEGURADO">A LA ESPERA DE RESPUESTA DEL ASEGURADO</option>
                                <option value="SE MANDO A MODIFICAR LA POLIZA A LA CIA DE SEGUROS">SE MANDO A MODIFICAR LA POLIZA A LA CIA DE SEGUROS</option>
                            </select>
                        </span>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn dusty-grass-gradient" id="btnSeguimiento">Crear</button>
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
                            for ($i = 0; $i < sizeof($no_renovar); $i++) {
                            ?>
                                <option value="<?= $no_renovar[$i]["id_no_renov"]; ?>"><?= utf8_encode($no_renovar[$i]["no_renov_n"]); ?></option>
                            <?php } ?>
                        </select>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn dusty-grass-gradient" id="btnNoRenovP">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal NO RENOV 1-->
    <div class="modal fade" id="noRenov1" tabindex="-1" role="dialog" aria-labelledby="noRenov1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noRenov1">Anular Póliza Pre-Renovada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frmnuevoNR1" class="md-form">
                        <input type="text" class="form-control" id="id_polizaNR1" name="id_polizaNR1" hidden>
                        <input type="text" class="form-control" id="id_usuarioNR1" name="id_usuarioNR1" value="<?= $_SESSION['id_usuario']; ?>" hidden>
                        <input type="text" class="form-control" id="f_hastaNR1" name="f_hastaNR1" hidden>

                        <select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="no_renov1" name="no_renov1" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un Motivo" searchable="Búsqueda rápida">
                            <option value="">Seleccione el Motivo</option>
                            <?php
                            for ($i = 0; $i < sizeof($no_renovar); $i++) {
                            ?>
                                <option value="<?= $no_renovar[$i]["id_no_renov"]; ?>"><?= utf8_encode($no_renovar[$i]["no_renov_n"]); ?></option>
                            <?php } ?>
                        </select>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn dusty-grass-gradient" id="btnNoRenovP1">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/view/b_poliza.js"></script>
    <script src="../assets/view/modalE.js"></script>

    <script>
        var open_modal = 0;
        open_modal = <?= $val = (isset($_GET['modal'])) ? $_GET['modal'] : 2; ?>;
        if (open_modal == true) {
            $('#seguimiento').modal('show');
        }

        var open_pagos = 0;
        open_pagos = <?= $val = (isset($_GET['pagos'])) ? $_GET['pagos'] : 2; ?>;
        if (open_pagos == 1) {
            $('#pagos').modal('show');
        }
    </script>


</body>

</html>