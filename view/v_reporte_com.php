<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'v_reporte_com';

require_once '../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <div class="ml-5 mr-5">
                <h1 class="font-weight-bold">Compañía: <?= utf8_encode($cia[0]['nomcia']); ?></h1>
                <hr>
                <center>
                    <a href="add/c_comision.php?id_rep=<?= $id_rep_com; ?>&f_hasta=<?= $f_hasta_rep; ?>&cant_poliza=1&f_pagoGc=<?= $f_pago_gc; ?>&primat_com=<?= $rep_com[0]['primat_com']; ?>&comt=<?= $rep_com[0]['comt']; ?>&cia=<?= $rep_com[0]['id_cia']; ?>&exx=1" data-toggle="tooltip" data-placement="top" title="Añadir Comisión" class="btn blue-gradient btn-lg">Añadir Comisión &nbsp;<i class="fas fa-plus" aria-hidden="true"></i></a>

                    <a href="e_reporte.php?id_rep_com=<?= $id_rep_com; ?>" data-toggle="tooltip" data-placement="top" title="Editar Fechas y Montos Totales" class="btn dusty-grass-gradient btn-lg">Editar Reporte &nbsp;<i class="fas fa-edit" aria-hidden="true"></i></a>


                    <?php if ($_SESSION['id_permiso'] == 1) { ?>
                        <button onclick="eliminarDatos('<?= $id_rep_com; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient btn-lg text-white">Eliminar Reporte &nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                    <?php } ?>
                </center>
                <hr>
            </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">

            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>Fecha Hasta Reporte</th>
                        <th>Fecha Pago GC</th>
                        <th>Prima Sujeta a Comisión Total</th>
                        <th>Comisión Total</th>
                        <th hidden>id reporte</th>
                        <th hidden>cia</th>
                        <th hidden>cant_poliza</th>
                    </thead>
                    <tbody>
                        <td><?= $f_hasta_rep; ?></td>
                        <td><?= $f_pago_gc; ?></td>
                        <td class="text-right"><?= number_format($rep_com[0]['primat_com'], 2); ?></td>
                        <td class="text-right"><?= number_format($rep_com[0]['comt'], 2); ?></td>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th hidden>id</th>
                        <th>N° de Póliza</th>
                        <th nowrap>Asegurado</th>
                        <th>Fecha de Pago de la Prima</th>
                        <th style="background-color: #E54848;">Prima Sujeta a Comisión</th>
                        <th>Comisión</th>
                        <th>% Comisión</th>
                        <th>Asesor - Ejecutivo</th>
                        <th></th>
                    </thead>

                    <tbody>
                        <?php
                        for ($i = 0; $i < sizeof($comision); $i++) {
                            $totalPrimaCom = $totalPrimaCom + $comision[$i]['prima_com'];
                            $totalCom = $totalCom + $comision[$i]['comision'];

                            $titu = $obj->get_titulat_by_polizaid($comision[$i]['id_poliza']);

                            $f_pago_prima = date("d-m-Y", strtotime($comision[$i]['f_pago_prima']));

                            $nombre = $titu[0]['nombre_t'] . " " . $titu[0]['apellido_t'];
                            if ($titu[0]['id_titular'] == 0) {
                                $tituprep = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $comision[$i]['id_poliza']);
                                $nombre = $tituprep[0]['asegurado'];
                            }
                        ?>
                            <tr style="cursor: pointer;">
                                <td hidden><?= $comision[$i]['id_poliza']; ?></td>
                                <td><?= $comision[$i]['num_poliza']; ?></td>
                                <td nowrap><?= utf8_encode($nombre); ?></td>
                                <td><?= $f_pago_prima; ?></td>
                                <td align="right"><?= "$ " . number_format($comision[$i]['prima_com'], 2); ?></td>
                                <td align="right"><?= "$ " . number_format($comision[$i]['comision'], 2); ?></td>
                                <td align="center"><?= number_format(($comision[$i]['comision'] * 100) / $comision[$i]['prima_com'], 2) . " %"; ?></td>
                                <td><?= $comision[$i]['cod_vend']; ?></td>
                                <td class="text-center"><button onclick="eliminarComision('<?= $comision[$i]['id_comision']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-danger btn-sm">&nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></button></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                    <tfoot>
                        <tr class="blue-gradient text-white">
                            <th hidden>id</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="right">
                                <font size=4><?= "$ " . number_format($totalPrimaCom, 2); ?></font>
                            </td>
                            <td align="right">
                                <font size=4><?= "$ " . number_format($totalCom, 2); ?></font>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th hidden>id</th>
                            <th>N° de Póliza</th>
                            <th>Asegurado</th>
                            <th>Fecha de Pago de la Prima</th>
                            <th>Prima Sujeta a Comisión</th>
                            <th>Comisión</th>
                            <th>% Comisión</th>
                            <th>Asesor - Ejecutivo</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>


    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

</body>

</html>