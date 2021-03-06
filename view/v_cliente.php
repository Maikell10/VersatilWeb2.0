<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'v_cliente';

require_once '../Controller/Cliente.php';

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

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <div class="ml-5 mr-5">
                <h1 class="font-weight-bold">Cliente: <?= ($datos_c[0]['nombre_t']) . " " . ($datos_c[0]['apellido_t']); ?></h1>
                <h2 class="title">Nº ID: <?= $datos_c[0]['ci']; ?></h2>

                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tablaClientes', 'Listado de Pólizas')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                <hr>
                <center>

                    <?php if($datos_c[0]['email'] != '-' && $contAct > 0) { ?>
                        <a href="email/email_cliente.php?id_titu=<?= $id_titular; ?>" data-toggle="tooltip" data-placement="top" title="Enviar Correo" class="btn blue-gradient btn-lg">Enviar Correo &nbsp;<i class="far fa-paper-plane" aria-hidden="true"></i></a>
                    <?php } ?>

                    
                    <a href="e_cliente.php?id_titu=<?= $id_titular; ?>" data-toggle="tooltip" data-placement="top" title="Editar" class="btn dusty-grass-gradient btn-lg">Editar Cliente &nbsp;<i class="fas fa-edit" aria-hidden="true"></i></a>
                    <?php if ($_SESSION['id_permiso'] == 1 && $cliente == 0) { ?>
                        <button onclick="eliminarCliente('<?= $id_titular; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient text-white btn-lg">Eliminar Cliente &nbsp;<i class="fas fa-trash-alt"></i></button>
                    <?php } ?>
                </center>
            </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">

            <?php
            if ($cliente != 0) {
                if ($contAct > 0) { ?>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableCliente">
                            <thead class="blue-gradient text-white text-center">
                                <tr style="background-color: #2B9E34">
                                    <th colspan="10" class="font-weight-bold text-center h2">Activas</th>
                                </tr>
                                <tr>
                                    <th>N° de Póliza</th>
                                    <th>Ramo</th>
                                    <th>Cía</th>
                                    <th>Nombre Asesor</th>
                                    <th>Fecha Desde Póliza</th>
                                    <th>Fecha Hasta Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;">Prima Pendiente</th>
                                    <th>PDF</th>
                                    <th hidden>id poliza</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPS = 0;
                                $totalPC = 0;
                                $totalPP = 0;
                                for ($i = 0; $i < sizeof($cliente); $i++) {
                                    $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
                                    if ($cliente[$i]['f_hastapoliza'] >= date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
                                        $currency = ($cliente[$i]['currency'] == 1) ? "$ " : "Bs ";
                                        $newDesde = date("d-m-Y", strtotime($cliente[$i]["f_desdepoliza"]));
                                        $newHasta = date("d-m-Y", strtotime($cliente[$i]["f_hastapoliza"]));

                                        $primac = $obj->obetnComisiones($cliente[$i]['id_poliza']);
                                        $ppendiente = $cliente[$i]['prima'] - $primac[0]['SUM(prima_com)'];
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }

                                        $totalPS = $totalPS + $cliente[$i]['prima'];
                                        $totalPC = $totalPC + $primac[0]['SUM(prima_com)'];
                                        $totalPP = $totalPP + $ppendiente;
                                ?>
                                        <tr style="cursor: pointer">
                                            <td><?= $cliente[$i]['cod_poliza']; ?></td>
                                            <td><?= ($cliente[$i]['nramo']); ?></td>
                                            <td><?= ($cliente[$i]['nomcia']); ?></td>
                                            <td><?= $cliente[$i]['nombre'].' ('.$cliente[$i]['codvend'].')'; ?></td>
                                            <td nowrap><?= $newDesde; ?></td>
                                            <td nowrap><?= $newHasta; ?></td>
                                            <td nowrap class="text-right"><?= $currency . number_format($cliente[$i]['prima'], 2); ?></td>

                                            <td class="text-right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($ppendiente > 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente == 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente < 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php } ?>


                                            <?php if ($cliente[$i]['pdf'] == 1) { ?>
                                                <td class="text-center"><a href="download.php?id_poliza=<?= $cliente[$i]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                <?php } else {
                                                if ($cliente[$i]['nramo'] == 'Vida') {
                                                    $vRenov = $obj->verRenov3($cliente[$i]['id_poliza']);
                                                    if ($vRenov != 0) {
                                                        if ($vRenov[0]['pdf'] != 0) {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $cliente[$i]['id_cia'], $cliente[$i]['f_hastapoliza']);
                                                            if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                                <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else { ?>
                                                                <td></td>
                                                            <?php }
                                                        }
                                                    } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($cliente[$i]['cod_poliza'], $cliente[$i]['id_cia'], $cliente[$i]['f_hastapoliza']);
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

                                            <td hidden><?= $cliente[$i]['id_poliza']; ?></td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>N° de Póliza</th>
                                    <th>Ramo</th>
                                    <th>Cía</th>
                                    <th>Nombre Asesor</th>
                                    <th>Fecha Desde Póliza</th>
                                    <th>Fecha Hasta Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;color: white">Prima Pendiente</th>
                                    <th>PDF</th>
                                    <th hidden>id poliza</th>
                                </tr>
                                <tr>
                                    <td colspan="6">Total Pólizas: <font class="text-danger font-weight-bold h5"><?= $contAct; ?></font>
                                    </td>
                                    <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPS, 2); ?></td>
                                    <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPC, 2); ?></td>
                                    <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPP, 2); ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                <?php }
                if ($contInact > 0) { ?>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableCliente">
                            <thead class="blue-gradient text-white text-center">
                                <tr style="background-color: #E54848">
                                    <th colspan="10" class="font-weight-bold text-center h2">Inactivas</th>
                                </tr>
                                <tr>
                                    <th>N° de Póliza</th>
                                    <th>Ramo</th>
                                    <th>Cía</th>
                                    <th>Nombre Asesor</th>
                                    <th>Fecha Desde Póliza</th>
                                    <th>Fecha Hasta Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;">Prima Pendiente</th>
                                    <th>PDF</th>
                                    <th hidden>id poliza</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPS = 0;
                                $totalPC = 0;
                                $totalPP = 0;
                                for ($i = 0; $i < sizeof($cliente); $i++) {
                                    $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
                                    if ($cliente[$i]['f_hastapoliza'] <= date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
                                        $currency = ($cliente[$i]['currency'] == 1) ? "$ " : "Bs ";
                                        $newDesde = date("d-m-Y", strtotime($cliente[$i]["f_desdepoliza"]));
                                        $newHasta = date("d-m-Y", strtotime($cliente[$i]["f_hastapoliza"]));
                                        $primac = $obj->obetnComisiones($cliente[$i]['id_poliza']);
                                        $ppendiente = $cliente[$i]['prima'] - $primac[0]['SUM(prima_com)'];
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }

                                        $totalPS = $totalPS + $cliente[$i]['prima'];
                                        $totalPC = $totalPC + $primac[0]['SUM(prima_com)'];
                                        $totalPP = $totalPP + $ppendiente;
                                ?>
                                        <tr style="cursor: pointer">
                                            <td><?= $cliente[$i]['cod_poliza']; ?></td>
                                            <td><?= ($cliente[$i]['nramo']); ?></td>
                                            <td><?= ($cliente[$i]['nomcia']); ?></td>
                                            <td><?= $cliente[$i]['nombre'].' ('.$cliente[$i]['codvend'].')'; ?></td>
                                            <td nowrap><?= $newDesde; ?></td>
                                            <td nowrap><?= $newHasta; ?></td>
                                            <td nowrap class="text-right"><?= $currency . number_format($cliente[$i]['prima'], 2); ?></td>

                                            <td class="text-right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($ppendiente > 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente == 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente < 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php } ?>

                                            <?php if ($cliente[$i]['pdf'] == 1) { ?>
                                                <td class="text-center"><a href="download.php?id_poliza=<?= $cliente[$i]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                <?php } else {
                                                if ($cliente[$i]['nramo'] == 'Vida') {
                                                    $vRenov = $obj->verRenov3($cliente[$i]['id_poliza']);
                                                    if ($vRenov != 0) {
                                                        if ($vRenov[0]['pdf'] != 0) {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $cliente[$i]['id_cia'], $cliente[$i]['f_hastapoliza']);
                                                            if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                                <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else { ?>
                                                                <td></td>
                                                            <?php }
                                                        }
                                                    } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($cliente[$i]['cod_poliza'], $cliente[$i]['id_cia'], $cliente[$i]['f_hastapoliza']);
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

                                            <td hidden><?= $cliente[$i]['id_poliza']; ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>N° de Póliza</th>
                                    <th>Ramo</th>
                                    <th>Cía</th>
                                    <th>Nombre Asesor</th>
                                    <th>Fecha Desde Póliza</th>
                                    <th>Fecha Hasta Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;color: white">Prima Pendiente</th>
                                    <th>PDF</th>
                                    <th hidden>id poliza</th>
                                </tr>
                                <tr>
                                    <td colspan="6">Total Pólizas: <font class="text-danger font-weight-bold h5"><?= $contInact; ?></font>
                                    </td>
                                    <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPS, 2); ?></td>
                                    <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPC, 2); ?></td>
                                    <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPP, 2); ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php }
                if ($contAnu > 0) { ?>
                    <div class="table-responsive-xl">
                        <table class="table table-hover table-striped table-bordered" id="tableCliente">
                            <thead class="blue-gradient text-white text-center">
                                <tr style="background-color: #4a148c">
                                    <th colspan="10" class="font-weight-bold text-center h2">Anuladas</th>
                                </tr>
                                <tr>
                                    <th>N° de Póliza</th>
                                    <th>Ramo</th>
                                    <th>Cía</th>
                                    <th>Nombre Asesor</th>
                                    <th>Fecha Desde Póliza</th>
                                    <th>Fecha Hasta Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;">Prima Pendiente</th>
                                    <th>PDF</th>
                                    <th hidden>id poliza</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPS = 0;
                                $totalPC = 0;
                                $totalPP = 0;
                                for ($i = 0; $i < sizeof($cliente); $i++) {
                                    $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
                                    if ($no_renov[0]['no_renov'] == 1) {
                                        $currency = ($cliente[$i]['currency'] == 1) ? "$ " : "Bs ";
                                        $newDesde = date("d-m-Y", strtotime($cliente[$i]["f_desdepoliza"]));
                                        $newHasta = date("d-m-Y", strtotime($cliente[$i]["f_hastapoliza"]));
                                        $primac = $obj->obetnComisiones($cliente[$i]['id_poliza']);
                                        $ppendiente = $cliente[$i]['prima'] - $primac[0]['SUM(prima_com)'];
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }

                                        $totalPS = $totalPS + $cliente[$i]['prima'];
                                        $totalPC = $totalPC + $primac[0]['SUM(prima_com)'];
                                        $totalPP = $totalPP + $ppendiente;
                                ?>
                                        <tr style="cursor: pointer">
                                            <td><?= $cliente[$i]['cod_poliza']; ?></td>
                                            <td><?= ($cliente[$i]['nramo']); ?></td>
                                            <td><?= ($cliente[$i]['nomcia']); ?></td>
                                            <td><?= $cliente[$i]['nombre'].' ('.$cliente[$i]['codvend'].')'; ?></td>
                                            <td nowrap><?= $newDesde; ?></td>
                                            <td nowrap><?= $newHasta; ?></td>
                                            <td nowrap class="text-right"><?= $currency . number_format($cliente[$i]['prima'], 2); ?></td>

                                            <td class="text-right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($ppendiente > 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente == 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente < 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }  ?>

                                            <?php if ($cliente[$i]['pdf'] == 1) { ?>
                                                <td class="text-center"><a href="download.php?id_poliza=<?= $cliente[$i]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                <?php } else {
                                                if ($cliente[$i]['nramo'] == 'Vida') {
                                                    $vRenov = $obj->verRenov3($cliente[$i]['id_poliza']);
                                                    if ($vRenov != 0) {
                                                        if ($vRenov[0]['pdf'] != 0) {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                            <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else {
                                                            $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $cliente[$i]['id_cia'], $cliente[$i]['f_hastapoliza']);
                                                            if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                                <td class="text-center"><a href="download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                            <?php } else { ?>
                                                                <td></td>
                                                            <?php }
                                                        }
                                                    } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($cliente[$i]['cod_poliza'], $cliente[$i]['id_cia'], $cliente[$i]['f_hastapoliza']);
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

                                            <td hidden><?= $cliente[$i]['id_poliza']; ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>N° de Póliza</th>
                                    <th>Ramo</th>
                                    <th>Cía</th>
                                    <th>Nombre Asesor</th>
                                    <th>Fecha Desde Póliza</th>
                                    <th>Fecha Hasta Póliza</th>
                                    <th>Prima Suscrita</th>
                                    <th>Prima Cobrada</th>
                                    <th style="background-color: #E54848;color: white">Prima Pendiente</th>
                                    <th>PDF</th>
                                    <th hidden>id poliza</th>
                                </tr>
                                <tr>
                                    <td colspan="6">Total Pólizas: <font class="text-danger font-weight-bold h5"><?= $contAnu; ?></font>
                                    </td>
                                    <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPS, 2); ?></td>
                                    <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPC, 2); ?></td>
                                    <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPP, 2); ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php } ?>

                <!-- ------------------// TABLA EXCEL //------------------ -->
                <div class="table-responsive-xl" hidden>
                    <table class="table table-hover table-striped table-bordered" id="tablaClientes">
                        <thead>
                            <tr>
                                <td colspan="9" style="text-align: center; font-size: 18px">Cliente: <?= ($datos_c[0]['nombre_t']) . " " . ($datos_c[0]['apellido_t']); ?></td>
                            </tr>
                            <tr>
                                <th style="background-color: #4285F4; color: white">N° de Póliza</th>
                                <th style="background-color: #4285F4; color: white">Ramo</th>
                                <th style="background-color: #4285F4; color: white">Cía</th>
                                <th style="background-color: #4285F4; color: white">Nombre Asesor</th>
                                <th style="background-color: #4285F4; color: white">Fecha Desde Póliza</th>
                                <th style="background-color: #4285F4; color: white">Fecha Hasta Póliza</th>
                                <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                <th style="background-color: #ff4444; color: white">Prima Pendiente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($contAct > 0) { ?>
                                <tr>
                                    <th colspan="9" style="background-color: #2B9E34; color: white; font-weight: bold; text-align: center">Activas</th>
                                </tr>
                                <?php
                                $totalPS = 0;
                                $totalPC = 0;
                                $totalPP = 0;
                                for ($i = 0; $i < sizeof($cliente); $i++) {
                                    $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
                                    if ($cliente[$i]['f_hastapoliza'] >= date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
                                        $currency = ($cliente[$i]['currency'] == 1) ? "$ " : "Bs ";
                                        $newDesde = date("d-m-Y", strtotime($cliente[$i]["f_desdepoliza"]));
                                        $newHasta = date("d-m-Y", strtotime($cliente[$i]["f_hastapoliza"]));

                                        $primac = $obj->obetnComisiones($cliente[$i]['id_poliza']);
                                        $ppendiente = $cliente[$i]['prima'] - $primac[0]['SUM(prima_com)'];
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }

                                        $totalPS = $totalPS + $cliente[$i]['prima'];
                                        $totalPC = $totalPC + $primac[0]['SUM(prima_com)'];
                                        $totalPP = $totalPP + $ppendiente;
                                ?>
                                        <tr style="cursor: pointer">
                                            <td><?= $cliente[$i]['cod_poliza']; ?></td>
                                            <td><?= ($cliente[$i]['nramo']); ?></td>
                                            <td><?= ($cliente[$i]['nomcia']); ?></td>
                                            <td><?= $cliente[$i]['nombre'].' ('.$cliente[$i]['codvend'].')'; ?></td>
                                            <td nowrap><?= $newDesde; ?></td>
                                            <td nowrap><?= $newHasta; ?></td>
                                            <td nowrap style="text-align: right"><?= $currency . number_format($cliente[$i]['prima'], 2); ?></td>

                                            <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($ppendiente > 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente == 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente < 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php } ?>
                                        </tr>
                                <?php }
                                } ?>
                                <tr>
                                    <td colspan="6" style="background-color: #e0f7fa">Total Pólizas: <font style="color: #ff4444;font-weight: bold;font-size: 20px"><?= $contAct; ?></font>
                                    </td>
                                    <td style="text-align: right;color: #ff4444;font-size: 20px;font-weight: bold;background-color: #e0f7fa"><?= '$ ' . number_format($totalPS, 2); ?></td>
                                    <td style="text-align: right;color: #ff4444;font-size: 20px;font-weight: bold;background-color: #e0f7fa"><?= '$ ' . number_format($totalPC, 2); ?></td>
                                    <td style="text-align: right;color: #ff4444;font-size: 20px;font-weight: bold;background-color: #e0f7fa"><?= '$ ' . number_format($totalPP, 2); ?></td>
                                </tr>
                            <?php }
                            if ($contInact > 0) { ?>
                                <tr>
                                    <th colspan="9" style="background-color: #E54848; color: white; font-weight: bold; text-align: center">Inactivas</th>
                                </tr>
                                <?php
                                $totalPS = 0;
                                $totalPC = 0;
                                $totalPP = 0;
                                for ($i = 0; $i < sizeof($cliente); $i++) {
                                    $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
                                    if ($cliente[$i]['f_hastapoliza'] <= date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
                                        $currency = ($cliente[$i]['currency'] == 1) ? "$ " : "Bs ";
                                        $newDesde = date("d-m-Y", strtotime($cliente[$i]["f_desdepoliza"]));
                                        $newHasta = date("d-m-Y", strtotime($cliente[$i]["f_hastapoliza"]));
                                        $primac = $obj->obetnComisiones($cliente[$i]['id_poliza']);
                                        $ppendiente = $cliente[$i]['prima'] - $primac[0]['SUM(prima_com)'];
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }

                                        $totalPS = $totalPS + $cliente[$i]['prima'];
                                        $totalPC = $totalPC + $primac[0]['SUM(prima_com)'];
                                        $totalPP = $totalPP + $ppendiente;
                                ?>
                                        <tr style="cursor: pointer">
                                            <td><?= $cliente[$i]['cod_poliza']; ?></td>
                                            <td><?= ($cliente[$i]['nramo']); ?></td>
                                            <td><?= ($cliente[$i]['nomcia']); ?></td>
                                            <td><?= $cliente[$i]['nombre'].' ('.$cliente[$i]['codvend'].')'; ?></td>
                                            <td nowrap><?= $newDesde; ?></td>
                                            <td nowrap><?= $newHasta; ?></td>
                                            <td nowrap style="text-align: right"><?= $currency . number_format($cliente[$i]['prima'], 2); ?></td>

                                            <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($ppendiente > 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente == 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente < 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php } ?>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                                <tr>
                                    <td colspan="6" style="background-color: #e0f7fa">Total Pólizas: <font style="color: #ff4444;font-weight: bold;font-size: 20px"><?= $contInact; ?></font>
                                    </td>
                                    <td style="text-align: right;color: #ff4444;font-size: 20px;font-weight: bold;background-color: #e0f7fa"><?= '$ ' . number_format($totalPS, 2); ?></td>
                                    <td style="text-align: right;color: #ff4444;font-size: 20px;font-weight: bold;background-color: #e0f7fa"><?= '$ ' . number_format($totalPC, 2); ?></td>
                                    <td style="text-align: right;color: #ff4444;font-size: 20px;font-weight: bold;background-color: #e0f7fa"><?= '$ ' . number_format($totalPP, 2); ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($contAnu > 0) { ?>
                                <tr>
                                    <th colspan="9" style="background-color: #4a148c; color: white; font-weight: bold; text-align: center">Anuladas</th>
                                </tr>
                                <?php
                                $totalPS = 0;
                                $totalPC = 0;
                                $totalPP = 0;
                                for ($i = 0; $i < sizeof($cliente); $i++) {
                                    $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
                                    if ($no_renov[0]['no_renov'] == 1) {
                                        $currency = ($cliente[$i]['currency'] == 1) ? "$ " : "Bs ";
                                        $newDesde = date("d-m-Y", strtotime($cliente[$i]["f_desdepoliza"]));
                                        $newHasta = date("d-m-Y", strtotime($cliente[$i]["f_hastapoliza"]));
                                        $primac = $obj->obetnComisiones($cliente[$i]['id_poliza']);
                                        $ppendiente = $cliente[$i]['prima'] - $primac[0]['SUM(prima_com)'];
                                        if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                            $ppendiente = 0;
                                        }

                                        $totalPS = $totalPS + $cliente[$i]['prima'];
                                        $totalPC = $totalPC + $primac[0]['SUM(prima_com)'];
                                        $totalPP = $totalPP + $ppendiente;
                                ?>
                                        <tr style="cursor: pointer">
                                            <td><?= $cliente[$i]['cod_poliza']; ?></td>
                                            <td><?= ($cliente[$i]['nramo']); ?></td>
                                            <td><?= ($cliente[$i]['nomcia']); ?></td>
                                            <td><?= $cliente[$i]['nombre'].' ('.$cliente[$i]['codvend'].')'; ?></td>
                                            <td nowrap><?= $newDesde; ?></td>
                                            <td nowrap><?= $newHasta; ?></td>
                                            <td nowrap style="text-align: right"><?= $currency . number_format($cliente[$i]['prima'], 2); ?></td>

                                            <td style="text-align: right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                            <?php if ($ppendiente > 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente == 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php }
                                            if ($ppendiente < 0) { ?>
                                                <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                            <?php } ?>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                                <tr>
                                    <td colspan="6" style="background-color: #e0f7fa">Total Pólizas: <font style="color: #ff4444;font-weight: bold;font-size: 20px"><?= $contAnu; ?></font>
                                    </td>
                                    <td style="text-align: right;color: #ff4444;font-size: 20px;font-weight: bold;background-color: #e0f7fa"><?= '$ ' . number_format($totalPS, 2); ?></td>
                                    <td style="text-align: right;color: #ff4444;font-size: 20px;font-weight: bold;background-color: #e0f7fa"><?= '$ ' . number_format($totalPC, 2); ?></td>
                                    <td style="text-align: right;color: #ff4444;font-size: 20px;font-weight: bold;background-color: #e0f7fa"><?= '$ ' . number_format($totalPP, 2); ?></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>N° de Póliza</th>
                                <th>Ramo</th>
                                <th>Cía</th>
                                <th>Nombre Asesor</th>
                                <th>Fecha Desde Póliza</th>
                                <th>Fecha Hasta Póliza</th>
                                <th>Prima Suscrita</th>
                                <th>Prima Cobrada</th>
                                <th style="background-color: #E54848;color: white">Prima Pendiente</th>
                            </tr>

                        </tfoot>
                    </table>
                </div>
                <!-- ------------------// TABLA EXCEL //------------------ -->

            <?php } ?>
            <br>

            <h1 class="font-weight-bold h1">Datos del Cliente</h1>

            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="blue-gradient text-white">
                        <tr>
                            <th>Nº Id Titular</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Fecha Nacimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $datos_c[0]['r_social']. ' ' .$datos_c[0]['ci']; ?></td>
                            <td><?= ($datos_c[0]['nombre_t']); ?></td>
                            <td><?= ($datos_c[0]['apellido_t']); ?></td>
                            <td><?= $newFnac; ?></td>
                        </tr>
                        <tr class="blue-gradient text-white">
                            <th>Celular</th>
                            <th>Teléfono</th>
                            <th colspan="2">email</th>
                        </tr>
                        <tr>
                            <td><?= $datos_c[0]['cell']; ?></td>
                            <td><?= $datos_c[0]['telf']; ?></td>
                            <td colspan="2"><a href=mailto:<?= $datos_c[0]['email']; ?> data-toggle="tooltip" data-placement="bottom" title="Enviar Correo"><?= $datos_c[0]['email']; ?></a></td>
                        </tr>
                        <tr class="blue-gradient text-white">
                            <th colspan="4">Dirección</th>
                        </tr>
                        <tr>
                            <td colspan="4"><?= ($datos_c[0]['direcc']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>



    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../assets/view/b_cliente.js"></script>
    <script src="../assets/view/modalE.js"></script>

</body>

</html>