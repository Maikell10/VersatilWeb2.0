<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'prima_moroso_excel';

require_once '../../Controller/Poliza.php';


header("Pragma: public");
header("Expires: 0");
$filename = "Listado de Seguimiento de Morosidad de la Cobranza de Primas.xls";
header('Content-type: application/x-msdownload; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

$d = new DateTime();

$today = date("Y-m-d");
//resto 30 día
$date_comp = date("Y-m-d",strtotime($today."- 30 days")); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Versatil Seguros</title>

    <style>
        thead th {
            font-size: 23px;
        }

        td {
            border: 1px solid #dee2e6;
        }
    </style>
</head>

<body>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Fecha de Exportada: <?= $d->format('d-m-Y'); ?></th>
            </tr>
            <tr>
                <th style="background-color: #4285F4; color: white">N° Póliza</th>
                <th style="background-color: #4285F4; color: white">Días Morosidad</th>
                <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                <th style="background-color: #4285F4; color: white">F Desde Seguro</th>
                <th style="background-color: #4285F4; color: white">Cía</th>
                <th style="background-color: #4285F4; color: white">Ramo</th>
                <th style="background-color: #4285F4; color: white">Ejecutivo</th>
                <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                <th style="background-color: #ef6c00; color: white">Dif Prima</th>
                <th style="background-color: #4285F4; color: white">Ene</th>
                <th style="background-color: #4285F4; color: white">Feb</th>
                <th style="background-color: #4285F4; color: white">Mar</th>
                <th style="background-color: #4285F4; color: white">Abr</th>
                <th style="background-color: #4285F4; color: white">May</th>
                <th style="background-color: #4285F4; color: white">Jun</th>
                <th style="background-color: #4285F4; color: white">Jul</th>
                <th style="background-color: #4285F4; color: white">Ago</th>
                <th style="background-color: #4285F4; color: white">Sep</th>
                <th style="background-color: #4285F4; color: white">Oct</th>
                <th style="background-color: #4285F4; color: white">Nov</th>
                <th style="background-color: #4285F4; color: white">Dic</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $c = 0;
            $totalpsMes = 0;
            $totalpcMes = 0;
            $totaldifMes = 0;
            $cantPolizas;

            $cont1 = (isset($p_dif1)) ? sizeof($p_dif1) : 0;
            for ($i = 0; $i < $cont1; $i++) {
                $no_renov = $obj->verRenov1($idpoliza1[$i]);
                $ppendiente = number_format($p_dif1[$i], 2);
                if ($ppendiente >= -0.99 && $ppendiente <= 0.99) {
                    $ppendiente = 0;
                }

                $moroso = $obj->get_f_pago_prima_moroso($idpoliza1[$i]);
                $varmoroso = '';
                $totalpmor = 0;
                if ($moroso != 0) {
                    if ($date_comp >= $moroso[0]['f_pago_prima']) {
                        foreach ($moroso as $mor) {
                            $totalpmor = $totalpmor + $mor['prima_com'];
                        }
                        if ($mor['ncuotas'] != 1) {
                            $varDiv = (($mor['ncuotas'] - count($moroso)) != 0) ? $mor['ncuotas'] - count($moroso) : 1;
                            $total_sn_mor = ($mor['prima'] / $mor['ncuotas']) * ($varDiv);
                        } else {
                            $total_sn_mor = $mor['prima'] - $totalpmor;
                        }
                        if ($total_sn_mor >= 1) {
                            $varmoroso = 'Moroso';
                            $dayMor = date('d', strtotime($mor['f_desdepoliza']));
                            $monthMor = date('m', strtotime($moroso[0]['f_pago_prima']));
                            $yearMor = date('Y', strtotime($moroso[0]['f_pago_prima']));
                            $dateMor = $yearMor . '-' . $monthMor . '-' . $dayMor;

                            $date1 = new DateTime($dateMor);
                            $date2 = new DateTime($today);
                            $diff = $date1->diff($date2);

                            if ($diff->days >= 30 && $diff->days < 60) {
                                $varmoroso1 = '30 Días';
                                $varmoroso2 = 30;
                            }
                            if (60 <= $diff->days && $diff->days < 90) {
                                $varmoroso1 = '60 Días';
                                $varmoroso2 = 60;
                            }
                            if (90 <= $diff->days && $diff->days < 120) {
                                $varmoroso1 = '90 Días';
                                $varmoroso2 = 90;
                            }
                            if ($diff->days >= 120) {
                                $varmoroso2 = 120;
                            }
                        }
                    }
                } else {
                    $moroso = $obj->get_element_by_id('poliza', 'id_poliza', $idpoliza1[$i]);
                    if ($date_comp >= $moroso[0]['f_desdepoliza']) {
                        $varmoroso = 'Moroso';

                        $date1 = new DateTime($moroso[0]['f_desdepoliza']);
                        $date2 = new DateTime($today);
                        $diff = $date1->diff($date2);

                        if ($diff->days >= 30 && $diff->days < 60) {
                            $varmoroso1 = '30 Días';
                            $varmoroso2 = 30;
                        }
                        if (60 <= $diff->days && $diff->days < 90) {
                            $varmoroso1 = '60 Días';
                            $varmoroso2 = 60;
                        }
                        if (90 <= $diff->days && $diff->days < 120) {
                            $varmoroso1 = '90 Días';
                            $varmoroso2 = 90;
                        }
                        if ($diff->days >= 120) {
                            $varmoroso1 = '120 Días +';
                            $varmoroso2 = 120;
                        }
                    }
                }

                if ($ppendiente != 0 && $no_renov[0]['no_renov'] != 1 && $varmoroso == 'Moroso') {
                    // NO HAY PAGOS DE PRIMA
                    $totalpoliza = $totalpoliza + 1;
                    $totalpsMes = $totalpsMes + $prima_s1[$i];
                    $totalpcMes = $totalpcMes + $p_tt1[$i];
                    $totaldifMes = $totaldifMes + ($prima_s1[$i] - $p_tt1[$i]);
            ?>
                    <tr>
                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($f_hasta_poliza1[$i] >= date("Y-m-d")) { ?>
                                <td class="align-middle" style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>"><?= $cod_poliza1[$i]; ?></td>
                            <?php } else { ?>
                                <td class="align-middle" style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>"><?= $cod_poliza1[$i]; ?></td>
                            <?php }
                        } else { ?>
                            <td class="align-middle" style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>"><?= $cod_poliza1[$i]; ?></td>
                        <?php } ?>

                        <td align="center"><?= $varmoroso2; ?></td>

                        <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>"><?= $ciente1[$i]; ?></td>

                        <td><?= $newDesde1[$i]; ?></td>
                        <td><?= $nomcia1[$i]; ?></td>
                        <td><?= $nramo1[$i]; ?></td>
                        <td><?= $ejecutivo1[$i]; ?></td>

                        <td class="align-middle" style="text-align: right;background-color: #D9D9D9" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1[$i], 2); ?></td>
                        <td class="align-middle" style="text-align: right;background-color: #D9D9D9" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1[$i], 2); ?></td>

                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($ppendiente > 0) { ?>
                                <td class="align-middle" style="background-color: #ffab40 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                            <?php }
                            if ($ppendiente == 0) { ?>
                                <td class="align-middle" style="background-color: #ffab40 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                            <?php }
                            if ($ppendiente < 0) { ?>
                                <td class="align-middle" style="background-color: #ffab40 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                            <?php }
                        } else { ?>
                            <td class="align-middle" style="background-color: #ffab40 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php } ?>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1[$i]; ?>" nowrap>
                            <?php
                            if ($p_enero1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_enero1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$i]; ?>" nowrap>
                            <?php
                            if ($p_febrero1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_febrero1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$i]; ?>" nowrap>
                            <?php
                            if ($p_marzo1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_marzo1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$i]; ?>" nowrap>
                            <?php
                            if ($p_abril1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_abril1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$i]; ?>" nowrap>
                            <?php
                            if ($p_mayo1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_mayo1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$i]; ?>" nowrap>
                            <?php
                            if ($p_junio1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_junio1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$i]; ?>" nowrap>
                            <?php
                            if ($p_julio1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_julio1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$i]; ?>" nowrap>
                            <?php
                            if ($p_agosto1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_agosto1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$i]; ?>" nowrap>
                            <?php
                            if ($p_septiempre1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_septiempre1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$i]; ?>" nowrap>
                            <?php
                            if ($p_octubre1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_octubre1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$i]; ?>" nowrap>
                            <?php
                            if ($p_noviembre1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_noviembre1[$i], 2);
                            }
                            ?>
                        </td>

                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$i]; ?>" nowrap>
                            <?php
                            if ($p_diciembre1[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_diciembre1[$i], 2);
                            }
                            ?>
                        </td>
                    </tr>
                <?php }
            }
            //parte 2 variable terminando en a
            $cont2 = (isset($p_dif1a)) ? sizeof($p_dif1a) : 0;
            for ($i = 0; $i < $cont2; $i++) {
                $no_renov = $obj->verRenov1($idpoliza1a[$i]);
                $ppendiente = number_format($p_dif1a[$i], 2);
                if ($ppendiente >= -0.99 && $ppendiente <= 0.99) {
                    $ppendiente = 0;
                }

                $moroso = $obj->get_f_pago_prima_moroso($idpoliza1a[$i]);
                $varmoroso = '';
                $totalpmor = 0;
                if ($moroso != 0) {
                    if ($date_comp >= $moroso[0]['f_pago_prima']) {
                        foreach ($moroso as $mor) {
                            $totalpmor = $totalpmor + $mor['prima_com'];
                        }
                        if ($mor['ncuotas'] != 1) {
                            $varDiv = (($mor['ncuotas'] - count($moroso)) != 0) ? $mor['ncuotas'] - count($moroso) : 1;
                            $total_sn_mor = ($mor['prima'] / $mor['ncuotas']) * ($varDiv);
                        } else {
                            $total_sn_mor = $mor['prima'] - $totalpmor;
                        }
                        if ($total_sn_mor >= 1) {
                            $varmoroso = 'Moroso';
                            $dayMor = date('d', strtotime($mor['f_desdepoliza']));
                            $monthMor = date('m', strtotime($moroso[0]['f_pago_prima']));
                            $yearMor = date('Y', strtotime($moroso[0]['f_pago_prima']));
                            $dateMor = $yearMor . '-' . $monthMor . '-' . $dayMor;

                            $date1 = new DateTime($dateMor);
                            $date2 = new DateTime($today);
                            $diff = $date1->diff($date2);

                            if ($diff->days >= 30 && $diff->days < 60) {
                                $varmoroso1 = '30 Días';
                                $varmoroso2 = 30;
                            }
                            if (60 <= $diff->days && $diff->days < 90) {
                                $varmoroso1 = '60 Días';
                                $varmoroso2 = 60;
                            }
                            if (90 <= $diff->days && $diff->days < 120) {
                                $varmoroso1 = '90 Días';
                                $varmoroso2 = 90;
                            }
                            if ($diff->days >= 120) {
                                $varmoroso1 = '120 Días +';
                                $varmoroso2 = 120;
                            }
                        }
                    }
                } else {
                    $moroso = $obj->get_element_by_id('poliza', 'id_poliza', $idpoliza1a[$i]);
                    if ($date_comp >= $moroso[0]['f_desdepoliza']) {
                        $varmoroso = 'Moroso';

                        $date1 = new DateTime($moroso[0]['f_desdepoliza']);
                        $date2 = new DateTime($today);
                        $diff = $date1->diff($date2);

                        if ($diff->days >= 30 && $diff->days < 60) {
                            $varmoroso1 = '30 Días';
                            $varmoroso2 = 30;
                        }
                        if (60 <= $diff->days && $diff->days < 90) {
                            $varmoroso1 = '60 Días';
                            $varmoroso2 = 60;
                        }
                        if (90 <= $diff->days && $diff->days < 120) {
                            $varmoroso1 = '90 Días';
                            $varmoroso2 = 90;
                        }
                        if ($diff->days >= 120) {
                            $varmoroso1 = '120 Días +';
                            $varmoroso2 = 120;
                        }
                    }
                }

                if ($ppendiente != 0 && $no_renov[0]['no_renov'] != 1 && $varmoroso == 'Moroso') {
                    $totalpoliza = $totalpoliza + 1;
                    $totalpsMes = $totalpsMes + $prima_s1a[$i];
                    $totalpcMes = $totalpcMes + $p_tt1a[$i];
                    $totaldifMes = $totaldifMes + ($prima_s1a[$i] - $p_tt1a[$i]);
                ?>
                    <tr>
                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($f_hasta_poliza1a[$i] >= date("Y-m-d")) { ?>
                                <td class="align-middle" style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>"><?= $cod_poliza1a[$i]; ?></td>
                            <?php } else { ?>
                                <td class="align-middle" style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>"><?= $cod_poliza1a[$i]; ?></td>
                            <?php }
                        } else { ?>
                            <td class="align-middle" style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>"><?= $cod_poliza1a[$i]; ?></td>
                        <?php } ?>

                        <td align="center"><?= $varmoroso2; ?></td>

                        <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>"><?= $ciente1a[$i]; ?></td>

                        <td ><?= $newDesde1a[$i]; ?></td>
                        <td ><?= $nomcia1a[$i]; ?></td>
                        <td ><?= $nramo1a[$i]; ?></td>
                        <td ><?= $ejecutivo1a[$i]; ?></td>

                        <td class="align-middle" style="text-align: right;background-color: #D9D9D9" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1a[$i], 2); ?></td>
                        <td class="align-middle" style="text-align: right;background-color: #D9D9D9" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1a[$i], 2); ?></td>

                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($ppendiente > 0) { ?>
                                <td class="align-middle" style="background-color: #ffab40 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                            <?php }
                            if ($ppendiente == 0) { ?>
                                <td class="align-middle" style="background-color: #ffab40 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                            <?php }
                            if ($ppendiente < 0) { ?>
                                <td class="align-middle" style="background-color: #ffab40 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                            <?php }
                        } else { ?>
                            <td class="align-middle" style="background-color: #ffab40 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php } ?>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_enero1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_enero1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_febrero1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_febrero1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_marzo1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_marzo1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_abril1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_abril1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_mayo1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_mayo1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_junio1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_junio1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_julio1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_julio1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_agosto1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_agosto1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_septiempre1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_septiempre1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_octubre1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_octubre1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_noviembre1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_noviembre1a[$i], 2);
                            }
                            ?>
                        </td>

                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1a[$i]; ?>" nowrap>
                            <?php
                            if ($p_diciembre1a[$i] == 0) {
                                echo '';
                            } else {
                                echo '$ ' . number_format($p_diciembre1a[$i], 2);
                            }
                            ?>
                        </td>
                    </tr>
                <?php }
            }


            //parte 3 variable terminando en b
            $cont3 = (isset($p_dif1b)) ? sizeof($p_dif1b) : 0;
            for ($i = 0; $i < $cont3; $i++) {
                $no_renov = $obj->verRenov1($idpoliza1b[$i]);
                $ppendiente = number_format($p_dif1b[$i], 2);
                if ($ppendiente >= -0.99 && $ppendiente <= 0.99) {
                    $ppendiente = 0;
                }

                $moroso = $obj->get_f_pago_prima_moroso($idpoliza1b[$i]);
                $varmoroso = '';
                $totalpmor = 0;
                if ($moroso != 0) {
                    if ($date_comp >= $moroso[0]['f_pago_prima']) {
                        foreach ($moroso as $mor) {
                            $totalpmor = $totalpmor + $mor['prima_com'];
                        }
                        if ($mor['ncuotas'] != 1) {
                            $varDiv = (($mor['ncuotas'] - count($moroso)) != 0) ? $mor['ncuotas'] - count($moroso) : 1;
                            $total_sn_mor = ($mor['prima'] / $mor['ncuotas']) * ($varDiv);
                        } else {
                            $total_sn_mor = $mor['prima'] - $totalpmor;
                        }
                        if ($total_sn_mor >= 1) {
                            $varmoroso = 'Moroso';
                            $dayMor = date('d', strtotime($mor['f_desdepoliza']));
                            $monthMor = date('m', strtotime($moroso[0]['f_pago_prima']));
                            $yearMor = date('Y', strtotime($moroso[0]['f_pago_prima']));
                            $dateMor = $yearMor . '-' . $monthMor . '-' . $dayMor;

                            $date1 = new DateTime($dateMor);
                            $date2 = new DateTime($today);
                            $diff = $date1->diff($date2);

                            if ($diff->days >= 30 && $diff->days < 60) {
                                $varmoroso1 = '30 Días';
                                $varmoroso2 = 30;
                            }
                            if (60 <= $diff->days && $diff->days < 90) {
                                $varmoroso1 = '60 Días';
                                $varmoroso2 = 60;
                            }
                            if (90 <= $diff->days && $diff->days < 120) {
                                $varmoroso1 = '90 Días';
                                $varmoroso2 = 90;
                            }
                            if ($diff->days >= 120) {
                                $varmoroso1 = '120 Días +';
                                $varmoroso2 = 120;
                            }
                        }
                    }
                } else {
                    $moroso = $obj->get_element_by_id('poliza', 'id_poliza', $idpoliza1b[$i]);
                    if ($date_comp >= $moroso[0]['f_desdepoliza']) {
                        $varmoroso = 'Moroso';

                        $date1 = new DateTime($moroso[0]['f_desdepoliza']);
                        $date2 = new DateTime($today);
                        $diff = $date1->diff($date2);

                        if ($diff->days >= 30 && $diff->days < 60) {
                            $varmoroso1 = '30 Días';
                        }
                        if (60 <= $diff->days && $diff->days < 90) {
                            $varmoroso1 = '60 Días';
                        }
                        if (90 <= $diff->days && $diff->days < 120) {
                            $varmoroso1 = '90 Días';
                        }
                        if ($diff->days >= 120) {
                            $varmoroso1 = '120 Días +';
                        }
                    }
                }

                if ($ppendiente != 0 && $no_renov[0]['no_renov'] != 1 && $varmoroso == 'Moroso') {
                    $p_enero1bi = ($p_enero1b == 0) ? '' : '$ ' . number_format($p_enero1b[$i], 2);
                    $p_febrero1bi = ($p_febrero1b == 0) ? '' : '$ ' . number_format($p_febrero1b[$i], 2);
                    $p_marzo1bi = ($p_marzo1b == 0) ? '' : '$ ' . number_format($p_marzo1b[$i], 2);
                    $p_abril1bi = ($p_abril1b == 0) ? '' : '$ ' . number_format($p_abril1b[$i], 2);
                    $p_mayo1bi = ($p_mayo1b == 0) ? '' : '$ ' . number_format($p_mayo1b[$i], 2);
                    $p_junio1bi = ($p_junio1b == 0) ? '' : '$ ' . number_format($p_junio1b[$i], 2);
                    $p_julio1bi = ($p_julio1b == 0) ? '' : '$ ' . number_format($p_julio1b[$i], 2);
                    $p_agosto1bi = ($p_agosto1b == 0) ? '' : '$ ' . number_format($p_agosto1b[$i], 2);
                    $p_septiembre1bi = ($p_septiembre1b == 0) ? '' : '$ ' . number_format($p_septiembre1b[$i], 2);
                    $p_octubre1bi = ($p_octubre1b == 0) ? '' : '$ ' . number_format($p_octubre1b[$i], 2);
                    $p_noviembre1bi = ($p_noviembre1b == 0) ? '' : '$ ' . number_format($p_noviembre1b[$i], 2);
                    $p_diciembre1bi = ($p_diciembre1b == 0) ? '' : '$ ' . number_format($p_diciembre1b[$i], 2);

                    $totalpoliza = $totalpoliza + 1;
                    $totalpsMes = $totalpsMes + $prima_s1b[$i];
                    $totalpcMes = $totalpcMes + $p_tt1b[$i];
                    $totaldifMes = $totaldifMes + ($prima_s1b[$i] - $p_tt1b[$i]);
                ?>
                    <tr>
                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($f_hasta_poliza1b[$i] >= date("Y-m-d")) { ?>
                                <td class="align-middle" style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>"><?= $cod_poliza1b[$i]; ?></td>
                            <?php } else { ?>
                                <td class="align-middle" style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>"><?= $cod_poliza1b[$i]; ?></td>
                            <?php }
                        } else { ?>
                            <td class="align-middle" style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>"><?= $cod_poliza1b[$i]; ?></td>
                        <?php } ?>

                        <td align="center"><?= $varmoroso2; ?></td>

                        <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>"><?= $ciente1b[$i]; ?></td>


                        <td ><?= $newDesde1b[$i]; ?></td>
                        <td ><?= $nomcia1b[$i]; ?></td>
                        <td ><?= $nramo1b[$i]; ?></td>
                        <td ><?= $ejecutivo1b[$i]; ?></td>

                        <td class="align-middle" style="text-align: right;background-color: #D9D9D9" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1b[$i], 2); ?></td>
                        <td class="align-middle" style="text-align: right;background-color: #D9D9D9" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1b[$i], 2); ?></td>

                        <?php if ($no_renov[0]['no_renov'] != 1) {
                            if ($ppendiente > 0) { ?>
                                <td class="align-middle" style="background-color: #ffab40 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                            <?php }
                            if ($ppendiente == 0) { ?>
                                <td class="align-middle" style="background-color: #ffab40 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                            <?php }
                            if ($ppendiente < 0) { ?>
                                <td class="align-middle" style="background-color: #ffab40 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                            <?php }
                        } else { ?>
                            <td class="align-middle" style="background-color: #ffab40 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php } ?>

                        <?php if ($p_enero1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1b[$i]; ?>" nowrap><?= $p_enero1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1b[$i]; ?>" nowrap><?= $p_enero1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_febrero1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1b[$i]; ?>" nowrap><?= $p_febrero1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1b[$i]; ?>" nowrap><?= $p_febrero1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_marzo1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1b[$i]; ?>" nowrap><?= $p_marzo1bi ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1b[$i]; ?>" nowrap><?= $p_marzo1bi ?></td>
                        <?php } ?>

                        <?php if ($p_abril1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1b[$i]; ?>" nowrap><?= $p_abril1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1b[$i]; ?>" nowrap><?= $p_abril1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_mayo1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1b[$i]; ?>" nowrap><?= $p_mayo1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1b[$i]; ?>" nowrap><?= $p_mayo1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_junio1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1b[$i]; ?>" nowrap><?= $p_junio1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1b[$i]; ?>" nowrap><?= $p_junio1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_julio1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1b[$i]; ?>" nowrap><?= $p_julio1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1b[$i]; ?>" nowrap><?= $p_julio1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_agosto1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1b[$i]; ?>" nowrap><?= $p_agosto1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1b[$i]; ?>" nowrap><?= $p_agosto1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_septiempre1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1b[$i]; ?>" nowrap><?= $p_septiempre1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1b[$i]; ?>" nowrap><?= $p_septiempre1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_octubre1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1b[$i]; ?>" nowrap><?= $p_octubre1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1b[$i]; ?>" nowrap><?= $p_octubre1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_noviembre1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1b[$i]; ?>" nowrap><?= $p_noviembre1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1b[$i]; ?>" nowrap><?= $p_noviembre1bi; ?></td>
                        <?php } ?>

                        <?php if ($p_diciembre1b[$i] > 0) { ?>
                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1b[$i]; ?>" nowrap><?= $p_diciembre1bi; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1b[$i]; ?>" nowrap><?= $p_diciembre1bi; ?></td>
                        <?php } ?>
                    </tr>
                <?php }
            } 
            
            //parte 4 variable terminando en c
            $cont4 = (isset($p_dif1c)) ? sizeof($p_dif1c) : 0;
            for ($i = 0; $i < $cont4; $i++) {
                $no_renov = $obj->verRenov1($idpoliza1c[$i]);
                $ppendiente = number_format($p_dif1c[$i], 2);
                if ($ppendiente >= -0.99 && $ppendiente <= 0.99) {
                    $ppendiente = 0;
                }

                if($ppendiente != 0 && $no_renov[0]['no_renov'] != 1){
                    $totalpoliza = $totalpoliza + 1;
                    $totalpsMes = $totalpsMes + $prima_s1c[$i];
                    $totalpcMes = $totalpcMes + $p_tt1c[$i];
                    $totaldifMes = $totaldifMes + ($prima_s1c[$i] - $p_tt1c[$i]);
            ?>
                <tr>
                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($f_hasta_poliza1c[$i] >= date("Y-m-d")) { ?>
                            <td class="align-middle" style="color: #2B9E34;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>"><?= $cod_poliza1c[$i]; ?></td>
                        <?php } else { ?>
                            <td class="align-middle" style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>"><?= $cod_poliza1c[$i]; ?></td>
                        <?php }
                    } else { ?>
                        <td class="align-middle" style="color: #4a148c;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>"><?= $cod_poliza1c[$i]; ?></td>
                    <?php } ?>

                    <td align="center">0</td>

                    <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>"><?= $ciente1c[$i]; ?></td>

                    <td hidden><?= $newDesde1c[$i]; ?></td>
                    <td hidden><?= $nomcia1c[$i]; ?></td>
                    <td hidden><?= $nramo1c[$i]; ?></td>
                    <td hidden><?= $ejecutivo1c[$i]; ?></td>

                    <td class="align-middle" style="text-align: right;background-color: #D9D9D9" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" nowrap><?= '$ ' . number_format($prima_s1c[$i], 2); ?></td>
                    <td class="align-middle" style="text-align: right;background-color: #D9D9D9" data-toggle="tooltip" data-placement="top" title="Prima Total" nowrap><?= '$ ' . number_format($p_tt1c[$i], 2); ?></td>

                    <?php if ($no_renov[0]['no_renov'] != 1) {
                        if ($ppendiente > 0) { ?>
                            <td class="align-middle" style="background-color: #ffab40 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente == 0) { ?>
                            <td class="align-middle" style="background-color: #ffab40 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                        if ($ppendiente < 0) { ?>
                            <td class="align-middle" style="background-color: #ffab40 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                        <?php }
                    } else { ?>
                        <td class="align-middle" style="background-color: #ffab40 ;color:#4a148c;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>" nowrap><?= '$ ' . $ppendiente; ?></td>
                    <?php } ?>

                    <?php if ($p_enero1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1c[$i], 2); ?></td>
                    <?php } elseif($p_enero1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_febrero1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1c[$i], 2); ?></td>
                    <?php } elseif($p_febrero1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_febrero1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_marzo1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1c[$i], 2); ?></td>
                    <?php } elseif($p_marzo1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_marzo1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_abril1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1c[$i], 2); ?></td>
                    <?php } elseif($p_abril1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_abril1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_mayo1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1c[$i], 2); ?></td>
                    <?php } elseif($p_mayo1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_mayo1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_junio1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1c[$i], 2); ?></td>
                    <?php } elseif($p_junio1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_junio1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_julio1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1c[$i], 2); ?></td>
                    <?php } elseif($p_julio1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_julio1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_agosto1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1c[$i], 2); ?></td>
                    <?php } elseif($p_agosto1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_agosto1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_septiempre1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1c[$i], 2); ?></td>
                    <?php } elseif($p_septiempre1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_septiempre1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_octubre1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1c[$i], 2); ?></td>
                    <?php } elseif($p_octubre1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_octubre1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_noviembre1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1c[$i], 2); ?></td>
                    <?php } elseif($p_noviembre1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_noviembre1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1c[$i], 2); ?></td>
                    <?php } ?>

                    <?php if ($p_diciembre1c[$i] > 0) { ?>
                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1c[$i], 2); ?></td>
                    <?php } elseif($p_diciembre1c[$i] == 0) { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_diciembre1c[$i]; ?>" nowrap><?= ''; ?></td>
                    <?php } else { ?>
                        <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1c[$i], 2); ?></td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>

        <tr>
            <td colspan="7" style="background-color: #F53333;color: white;font-weight: bold">Total: <font size=4><?= $totalpoliza; ?></font>
            </td>

            <td nowrap style="background-color: #F53333;color: white;font-weight: bold;text-align: right">
                <font size=4><?= '$ ' . number_format($totalpsMes, 2); ?></font>
            </td>
            <td nowrap style="background-color: #F53333;color: white;font-weight: bold;text-align: right">
                <font size=4><?= '$ ' . number_format($totalpcMes, 2); ?></font>
            </td>
            <td nowrap style="background-color: #F53333;color: white;font-weight: bold;text-align: right">
                <font size=4><?= '$ ' . number_format($totaldifMes, 2); ?></font>
            </td>
            <td colspan="12" style="background-color: #F53333;color: white;font-weight: bold;text-align: right">
            </td>
        </tr>

        <tfoot class="text-center">
            <tr>
                <th>N° Póliza</th>
                <th>Días Morosidad</th>
                <th>Nombre Titular</th>
                <th>F Desde Seguro</th>
                <th>Cía</th>
                <th>Ramo</th>
                <th>Ejecutivo</th>
                <th>Prima Suscrita</th>
                <th>Prima Cobrada</th>
                <th>Dif Prima</th>
                <th>Ene</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Abr</th>
                <th>May</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Ago</th>
                <th>Sep</th>
                <th>Oct</th>
                <th>Nov</th>
                <th>Dic</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>