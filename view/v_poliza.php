<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'v_poliza';

require_once '../Controller/Poliza.php';

$no_renovar = $obj->get_element('no_renov', 'no_renov_n');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
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

                    <?php

                    $id_poliza = $poliza[0]['id_poliza'] . ".pdf";
                    $archivo = './' . $id_poliza;

                    //190.140.224.69                    
                    $ftp_server = "186.75.241.90";
                    $port = 21;
                    $ftp_usuario = "usuario";
                    $ftp_pass = "20127247";
                    $con_id = @ftp_connect($ftp_server, $port) or die("Unable to connect to server.");
                    $lr = ftp_login($con_id, $ftp_usuario, $ftp_pass);

                    //ftp_pasv($con_id, true);

                    if ((!$con_id) || (!$lr)) {
                        echo "no se pudo conectar";
                    } else {
                        # Cambiamos al directorio especificado
                        if (ftp_chdir($con_id, '')) {

                            // Obtener los archivos contenidos en el directorio actual
                            $contents = ftp_nlist($con_id, ".");

                            if (in_array($archivo, $contents)) {
                                //echo "<br>";
                                //echo "I found ".$archivo." in directory";
                    ?>
                                <a href="download.php?id_poliza=<?= $poliza[0]['id_poliza']; ?>" class="btn cloudy-knoxville-gradient btn-rounded float-right" target="_blank"><img src="../assets/img/pdf-logo.png" width="60" alt=""></a>
                                <br>
                            <?php } ?>
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

                                </form>
                            </center>
                        <?php ftp_close($con_id);
                        }
                    }


                    if ($poliza[0]['nombre_t'] == 'PENDIENTE') { ?>
                        <center><a href="cargar_pp.php?id_poliza=<?= $poliza[0]['id_poliza']; ?>" data-toggle="tooltip" data-placement="top" title="Cargar Póliza Pendiente" class="btn dusty-grass-gradient font-weight-bold btn-lg">Cargar Póliza Pendiente &nbsp;<i class="fas fa-edit" aria-hidden="true"></i></a></center>
                    <?php } ?>


                    <?php if ($_SESSION['id_permiso'] != 3 && $poliza[0]['nombre_t'] != 'PENDIENTE' && $no_renov[0]['no_renov'] != 1) { ?>
                        <span>
                            <a onclick="noRenovar(<?= $poliza[0]['id_poliza']; ?>,'<?= $poliza[0]['f_hastapoliza']; ?>')" data-toggle="tooltip" data-placement="top" title="No Renovar" class="btn young-passion-gradient btn-rounded text-white float-right"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                        </span>
                        <span data-toggle="modal" data-target="#seguimientoRenov">
                            <a data-toggle="tooltip" data-placement="top" title="Cargar Seguimiento de Renovación" class="btn blue-gradient btn-rounded text-white float-right"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </span>
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

                    <h2 class="font-weight-bold">Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h2>
                    <?php
                    if (isset($poliza[0]['idnom']) == null) {
                        $asesorr = $poliza[0]['cod'] . " -> " . $poliza[0]['nombre'];
                    } else {
                        $asesorr = $poliza[0]['cod'] . " -> " . $poliza[0]['idnom'];
                    }
                    ?>
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
                                <?php if ($poliza[0]['f_hastapoliza'] >= date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
                                ?>
                                    <td class="dusty-grass-gradient font-weight-bold"><?= "Activa"; ?></td>
                                <?php
                                } elseif ($no_renov[0]['no_renov'] == 1) { ?>
                                    <td class="young-passion-gradient text-white font-weight-bold"><?= "Anulada"; ?></td>
                                <?php } else { ?>
                                    <td class="young-passion-gradient text-white font-weight-bold"><?= "Inactiva"; ?></td>
                                <?php } ?>
                                <td><?= $newDesdeP; ?></td>
                                <td><?= $newHastaP; ?></td>
                                <td><?= utf8_encode($poliza[0]['tipo_poliza']); ?></td>
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
                                            echo utf8_encode($poliza[0]['nombre']);
                                        } else {
                                            echo utf8_encode($poliza[0]['idnom']);
                                        }
                                        ?></td>
                                    <td><?php
                                        if ($as == 1) {
                                            echo $poliza[0]['per_gc'] . " %";
                                        }
                                        if ($as == 2) {
                                            echo 'Modulo sin asignar';
                                        }
                                        if ($as == 3) {
                                            echo $poliza[0]['currencyM'] . ' ' . $poliza[0]['monto'];
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
                                <button onclick="eliminarPoliza('<?= $poliza[0]['id_poliza']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient text-white btn-lg">Eliminar Póliza &nbsp;<i class="fas fa-trash-alt"></i></button>
                        <?php }
                        } ?>
                    </center>
                    <hr>



                <?php } ?>
            </div>
        </div>
    </div>





    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <!-- Modal PAGOS -->
    <div class="modal fade" id="pagos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Cliente: </strong><?= ($poliza[0]['nombre_t'] . " " . $poliza[0]['apellido_t']); ?> | <strong>Póliza N°: </strong><?= $poliza[0]['cod_poliza']; ?></h5>

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

                    <h5 class="modal-title" id="exampleModalLabel"><strong>Cía: </strong><?= ($poliza[0]['nomcia']); ?> | <strong>Ramo: </strong><?= ($poliza[0]['nramo']); ?></h5>

                    <hr>

                    <h5 class="modal-title" id="exampleModalLabel"><strong>Observaciones: </strong><?= $poliza[0]['obs']; ?></h5>
                    <hr>

                    <form id="frmnuevoP">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered" id="tableModalPago">
                                <thead class="blue-gradient text-white">
                                    <tr>
                                        <th hidden>id</th>
                                        <th>Ejecutivo</th>
                                        <th>Prima Cobrada</th>
                                        <th>F Pago Prima</th>
                                        <th>Comisión Cobrada</th>
                                        <th>F Hasta Reporte</th>
                                        <th>GC Pagada</th>
                                        <th>F Pago GC</th>
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
                                ?>
                                        <tr style="cursor: pointer">
                                            <td hidden><?= $polizap[$i]['id_rep_com']; ?></td>
                                            <td><?= $ejecutivo[0]['nombre']; ?></td>
                                            <td align="right"><?= $polizap[$i]['prima_com']; ?></td>
                                            <td><?= $newFPago; ?></td>
                                            <td align="right"><?= $polizap[$i]['comision']; ?></td>
                                            <td nowrap><?= $newFHastaR; ?></td>
                                            <td align="right"><?= ($polizap[$i]['comision'] * $polizap[$i]['per_gc']) / 100; ?></td>
                                            <td nowrap><?= $newFPagoGC; ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                                <tr style="background-color: #F53333;color: white">
                                    <td></td>
                                    <td class="font-weight-bold">Prima Cobrada: <?= $currency . number_format($totalprimaC, 2); ?></td>
                                    <td class="font-weight-bold">Prima Suscrita: <?= $currency . number_format($poliza[0]['prima'], 2); ?></td>
                                    <td class="font-weight-bold">Comisión Cobrada: <?= $currency . number_format($totalcomisionC, 2); ?></td>
                                    <td class="font-weight-bold"></td>
                                    <td class="font-weight-bold">GC Pagada: <?= $currency . number_format($totalGC, 2); ?></td>
                                    <td class="font-weight-bold"></td>
                                </tr>
                            </table>
                        </div>
                    </form>
                    <h2>Prima Pendiente: <?= $currency . number_format($poliza[0]['prima'] - $totalprimaC, 2); ?></h2>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal SEGUIMIENTO -->
    <div class="modal fade" id="seguimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Seguimiento de la Póliza N°: <?= $poliza[0]['cod_poliza']; ?></h3>
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
                                        <tr class="young-passion-gradient text-white">
                                            <td><?= $no_renov[0]['nombre_usuario'] . " " . $no_renov[0]['apellido_usuario']; ?></td>
                                            <td><?= $no_renov[0]['no_renov_n'] ?></td>
                                            <td><?= $newCreated . " " . $newCreatedH; ?></td>
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
                        <textarea class="form-control md-textarea" id="comentarioS" name="comentarioS" required onKeyDown="valida_longitud()" onKeyUp="valida_longitud()" maxlength="300"></textarea>

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

    <script src="../assets/view/b_poliza.js"></script>

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