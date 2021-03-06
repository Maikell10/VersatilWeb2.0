<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'prima_moroso';

require_once '../Controller/Poliza.php';

$today = date("Y-m-d");
//resto 30 día
$date_comp = date("Y-m-d",strtotime($today."- 30 days")); 

/*
$moroso = $obj->get_f_pago_prima_moroso(2465);
$varmoroso = '';
$totalpmor = 0;
if($moroso != 0){
    if($date_comp >= $moroso[0]['f_pago_prima']){
        foreach ($moroso as $mor) {
            $totalpmor = $totalpmor + $mor['prima_com'];
        }
        if($mor['ncuotas'] != 1) {
            $varDiv = (($mor['ncuotas']-count($moroso)) != 0) ? $mor['ncuotas']-count($moroso) : 1;
            $total_sn_mor = ($mor['prima']/$mor['ncuotas'])*($varDiv);
        } else {
            $total_sn_mor = $mor['prima']-$totalpmor;
        }
        if($total_sn_mor >= 1){
            $dayMor = date('d',strtotime($mor['f_desdepoliza']));
            $monthMor = date('m',strtotime($moroso[0]['f_pago_prima']));
            $yearMor = date('Y',strtotime($moroso[0]['f_pago_prima']));
            $dateMor = $yearMor.'-'.$monthMor.'-'.$dayMor;
            
            $date1 = new DateTime($dateMor);
            $date2 = new DateTime($today);
            $diff = $date1->diff($date2);

            if($diff->days >= 30 && $diff->days < 60) {
                echo '30 Días';
            }
            if(60 <= $diff->days && $diff->days < 90) {
                echo '60 Días';
            }
            if(90 <= $diff->days && $diff->days < 120) {
                echo '90 Días';
            }
            if($diff->days >= 120) {
                echo '+ 120 Días';
            }
        }
    }
} else {
    $moroso = $obj->get_element_by_id('poliza','id_poliza',2465);
    if($date_comp >= $moroso[0]['f_desdepoliza']){
        $date1 = new DateTime($moroso[0]['f_desdepoliza']);
        $date2 = new DateTime($today);
        $diff = $date1->diff($date2);

        if($diff->days <= 30) {
            echo '30 Días';
        }
        if(30 < $diff->days && $diff->days <= 60) {
            echo '60 Días';
        }
        if(60 < $diff->days && $diff->days <= 90) {
            echo '60 Días';
        }
        if($diff->days > 90) {
            echo '+ 90 Días';
        }
    }
}*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
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
                                <h1 class="font-weight-bold ">Listado de Seguimiento de Morosidad de la Cobranza de Primas</h1>
                                <h2 class="font-weight-bold">Desde: <font style="color:red"><?= $desdeP; ?></font> Hasta: <font style="color:red"><?= $hastaP; ?></font>
                                </h2>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                    <center><a class="btn dusty-grass-gradient" href="excel/e_morosidad.php?session=<?= $_SESSION['id_permiso']; ?>&yhuejd=<?= $_SESSION['id_usuario']; ?>&desdeP=<?= $_GET['desdeP']; ?>&hastaP=<?= $_GET['hastaP']; ?>&desdeP_submit=<?= $_GET['desdeP_submit']; ?>&hastaP_submit=<?= $_GET['hastaP_submit']; ?>&ramo=<?= $ramoEnv; ?>&cia=<?= $ciaEnv; ?>&fpago=<?= $fpagoEnv; ?>&asesor=<?= $asesorEnv; ?>" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../assets/img/excel.png" width="60" alt=""></a></center>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered mx-auto" id="tablePDmoroso">
                            <thead class="blue-gradient text-white text-center">
                                <tr>
                                    <th class="align-middle">N° Póliza</th>
                                    <th class="align-middle">Nombre Titular</th>
                                    <th hidden>F Desde Seguro</th>
                                    <th hidden>Cía</th>
                                    <th hidden>Ramo</th>
                                    <th class="align-middle">Prima Suscrita</th>
                                    <th class="align-middle">Prima Cobrada</th>
                                    <th class="align-middle" style="background-color: #ef6c00;">Dif Prima</th>
                                    <th class="align-middle">Ene</th>
                                    <th class="align-middle">Feb</th>
                                    <th class="align-middle">Mar</th>
                                    <th class="align-middle">Abr</th>
                                    <th class="align-middle">May</th>
                                    <th class="align-middle">Jun</th>
                                    <th class="align-middle">Jul</th>
                                    <th class="align-middle">Ago</th>
                                    <th class="align-middle">Sep</th>
                                    <th class="align-middle">Oct</th>
                                    <th class="align-middle">Nov</th>
                                    <th class="align-middle">Dic</th>
                                    <th hidden>id</th>
                                    <th hidden>moroso</th>
                                </tr>
                            </thead>

                            <tbody style="cursor:pointer">
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
                                    if($moroso != 0){
                                        if($date_comp >= $moroso[0]['f_pago_prima']){
                                            foreach ($moroso as $mor) {
                                                $totalpmor = $totalpmor + $mor['prima_com'];
                                            }
                                            if($mor['ncuotas'] != 1) {
                                                $varDiv = (($mor['ncuotas']-count($moroso)) != 0) ? $mor['ncuotas']-count($moroso) : 1;
                                                $total_sn_mor = ($mor['prima']/$mor['ncuotas'])*($varDiv);
                                            } else {
                                                $total_sn_mor = $mor['prima']-$totalpmor;
                                            }
                                            if($total_sn_mor >= 1){
                                                $varmoroso = 'Moroso';
                                                $dayMor = date('d',strtotime($mor['f_desdepoliza']));
                                                $monthMor = date('m',strtotime($moroso[0]['f_pago_prima']));
                                                $yearMor = date('Y',strtotime($moroso[0]['f_pago_prima']));
                                                $dateMor = $yearMor.'-'.$monthMor.'-'.$dayMor;
                                                
                                                $date1 = new DateTime($dateMor);
                                                $date2 = new DateTime($today);
                                                $diff = $date1->diff($date2);
                                    
                                                if($diff->days >= 30 && $diff->days < 60) {
                                                    $varmoroso1 = '30 Días';
                                                    $varmoroso2 = 30;
                                                }
                                                if(60 <= $diff->days && $diff->days < 90) {
                                                    $varmoroso1 = '60 Días';
                                                    $varmoroso2 = 60;
                                                }
                                                if(90 <= $diff->days && $diff->days < 120) {
                                                    $varmoroso1 = '90 Días';
                                                    $varmoroso2 = 90;
                                                }
                                                if($diff->days >= 120) {
                                                    $varmoroso2 = 120;
                                                }
                                            }
                                        }
                                    } else {
                                        $moroso = $obj->get_element_by_id('poliza','id_poliza',$idpoliza1[$i]);
                                        if($date_comp >= $moroso[0]['f_desdepoliza']){
                                            $varmoroso = 'Moroso';

                                            $date1 = new DateTime($moroso[0]['f_desdepoliza']);
                                            $date2 = new DateTime($today);
                                            $diff = $date1->diff($date2);
                                    
                                            if($diff->days >= 30 && $diff->days < 60) {
                                                $varmoroso1 = '30 Días';
                                                $varmoroso2 = 30;
                                            }
                                            if(60 <= $diff->days && $diff->days < 90) {
                                                $varmoroso1 = '60 Días';
                                                $varmoroso2 = 60;
                                            }
                                            if(90 <= $diff->days && $diff->days < 120) {
                                                $varmoroso1 = '90 Días';
                                                $varmoroso2 = 90;
                                            }
                                            if($diff->days >= 120) {
                                                $varmoroso1 = '120 Días +';
                                                $varmoroso2 = 120;
                                            }
                                        }
                                    }

                                    if($ppendiente != 0 && $no_renov[0]['no_renov'] != 1 && $varmoroso == 'Moroso'){
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

                                        <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tool1[$i]; ?>"><?= $ciente1[$i]; ?> <span class="badge badge-pill badge-danger"><?= $varmoroso1;?></span></td>

                                        <td hidden><?= $newDesde1[$i]; ?></td>
                                        <td hidden><?= $nomcia1[$i]; ?></td>
                                        <td hidden><?= $nramo1[$i]; ?></td>

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
                                            if($p_enero1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_enero1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_febrero1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_febrero1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_marzo1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_marzo1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_abril1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_abril1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_mayo1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_mayo1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_junio1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_junio1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_julio1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_julio1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_agosto1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_agosto1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_septiempre1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_septiempre1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_octubre1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_octubre1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_noviembre1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_noviembre1[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1[$i]; ?>" nowrap>
                                            <?php
                                            if($p_diciembre1[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_diciembre1[$i], 2);
                                            }
                                            ?>
                                        </td>
                                        <td hidden><?= $idpoliza1[$i]; ?></td>
                                        <td hidden><?= $varmoroso2; ?></td>
                                    </tr>
                                <?php } }
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
                                    if($moroso != 0){
                                        if($date_comp >= $moroso[0]['f_pago_prima']){
                                            foreach ($moroso as $mor) {
                                                $totalpmor = $totalpmor + $mor['prima_com'];
                                            }
                                            if($mor['ncuotas'] != 1) {
                                                $varDiv = (($mor['ncuotas']-count($moroso)) != 0) ? $mor['ncuotas']-count($moroso) : 1;
                                                $total_sn_mor = ($mor['prima']/$mor['ncuotas'])*($varDiv);
                                            } else {
                                                $total_sn_mor = $mor['prima']-$totalpmor;
                                            }
                                            if($total_sn_mor >= 1){
                                                $varmoroso = 'Moroso';
                                                $dayMor = date('d',strtotime($mor['f_desdepoliza']));
                                                $monthMor = date('m',strtotime($moroso[0]['f_pago_prima']));
                                                $yearMor = date('Y',strtotime($moroso[0]['f_pago_prima']));
                                                $dateMor = $yearMor.'-'.$monthMor.'-'.$dayMor;
                                                
                                                $date1 = new DateTime($dateMor);
                                                $date2 = new DateTime($today);
                                                $diff = $date1->diff($date2);
                                    
                                                if($diff->days >= 30 && $diff->days < 60) {
                                                    $varmoroso1 = '30 Días';
                                                    $varmoroso2 = 30;
                                                }
                                                if(60 <= $diff->days && $diff->days < 90) {
                                                    $varmoroso1 = '60 Días';
                                                    $varmoroso2 = 60;
                                                }
                                                if(90 <= $diff->days && $diff->days < 120) {
                                                    $varmoroso1 = '90 Días';
                                                    $varmoroso2 = 90;
                                                }
                                                if($diff->days >= 120) {
                                                    $varmoroso1 = '120 Días +';
                                                    $varmoroso2 = 120;
                                                }
                                            }
                                        }
                                    } else {
                                        $moroso = $obj->get_element_by_id('poliza','id_poliza',$idpoliza1a[$i]);
                                        if($date_comp >= $moroso[0]['f_desdepoliza']){
                                            $varmoroso = 'Moroso';

                                            $date1 = new DateTime($moroso[0]['f_desdepoliza']);
                                            $date2 = new DateTime($today);
                                            $diff = $date1->diff($date2);
                                    
                                            if($diff->days >= 30 && $diff->days < 60) {
                                                $varmoroso1 = '30 Días';
                                                $varmoroso2 = 30;
                                            }
                                            if(60 <= $diff->days && $diff->days < 90) {
                                                $varmoroso1 = '60 Días';
                                                $varmoroso2 = 60;
                                            }
                                            if(90 <= $diff->days && $diff->days < 120) {
                                                $varmoroso1 = '90 Días';
                                                $varmoroso2 = 90;
                                            }
                                            if($diff->days >= 120) {
                                                $varmoroso1 = '120 Días +';
                                                $varmoroso2 = 120;
                                            }
                                        }
                                    }

                                    if($ppendiente != 0 && $no_renov[0]['no_renov'] != 1 && $varmoroso == 'Moroso'){
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

                                        <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tool1a[$i]; ?>"><?= $ciente1a[$i]; ?> <span class="badge badge-pill badge-danger"><?= $varmoroso1;?></span></td>

                                        <td hidden><?= $newDesde1a[$i]; ?></td>
                                        <td hidden><?= $nomcia1a[$i]; ?></td>
                                        <td hidden><?= $nramo1a[$i]; ?></td>

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

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'01',$a_enero1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Enero Año: ' . $a_enero1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_enero1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_enero1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'02',$a_febrero1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Febrero Año: ' . $a_febrero1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_febrero1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_febrero1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'03',$a_marzo1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Marzo Año: ' . $a_marzo1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_marzo1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_marzo1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'04',$a_abril1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Abril Año: ' . $a_abril1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_abril1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_abril1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'05',$a_mayo1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Mayo Año: ' . $a_mayo1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_mayo1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_mayo1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'06',$a_junio1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Junio Año: ' . $a_junio1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_junio1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_junio1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'07',$a_julio1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Julio Año: ' . $a_julio1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_julio1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_julio1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'08',$a_agosto1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Agosto Año: ' . $a_agosto1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_agosto1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_agosto1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'09',$a_septiempre1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Septiembre Año: ' . $a_septiempre1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_septiempre1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_septiempre1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'10',$a_octubre1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Octubre Año: ' . $a_octubre1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_octubre1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_octubre1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'11',$a_noviembre1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Noviembre Año: ' . $a_noviembre1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_noviembre1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_noviembre1a[$i], 2);
                                            }
                                            ?>
                                        </td>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1a[$i],'12',$a_diciembre1a[$i]); ?>
                                        <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Diciembre Año: ' . $a_diciembre1a[$i]; ?>" nowrap>
                                            <?php
                                            if($p_diciembre1a[$i] == 0){
                                                echo '';
                                            } else {
                                                echo '$ ' . number_format($p_diciembre1a[$i], 2);
                                            }
                                            ?>
                                        </td>
                                        <td hidden><?= $idpoliza1a[$i]; ?></td>
                                        <td hidden><?= $varmoroso2; ?></td>
                                    </tr>
                                <?php } }


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
                                    if($moroso != 0){
                                        if($date_comp >= $moroso[0]['f_pago_prima']){
                                            foreach ($moroso as $mor) {
                                                $totalpmor = $totalpmor + $mor['prima_com'];
                                            }
                                            if($mor['ncuotas'] != 1) {
                                                $varDiv = (($mor['ncuotas']-count($moroso)) != 0) ? $mor['ncuotas']-count($moroso) : 1;
                                                $total_sn_mor = ($mor['prima']/$mor['ncuotas'])*($varDiv);
                                            } else {
                                                $total_sn_mor = $mor['prima']-$totalpmor;
                                            }
                                            if($total_sn_mor >= 1){
                                                $varmoroso = 'Moroso';
                                                $dayMor = date('d',strtotime($mor['f_desdepoliza']));
                                                $monthMor = date('m',strtotime($moroso[0]['f_pago_prima']));
                                                $yearMor = date('Y',strtotime($moroso[0]['f_pago_prima']));
                                                $dateMor = $yearMor.'-'.$monthMor.'-'.$dayMor;
                                                
                                                $date1 = new DateTime($dateMor);
                                                $date2 = new DateTime($today);
                                                $diff = $date1->diff($date2);
                                    
                                                if($diff->days >= 30 && $diff->days < 60) {
                                                    $varmoroso1 = '30 Días';
                                                    $varmoroso2 = 30;
                                                }
                                                if(60 <= $diff->days && $diff->days < 90) {
                                                    $varmoroso1 = '60 Días';
                                                    $varmoroso2 = 60;
                                                }
                                                if(90 <= $diff->days && $diff->days < 120) {
                                                    $varmoroso1 = '90 Días';
                                                    $varmoroso2 = 90;
                                                }
                                                if($diff->days >= 120) {
                                                    $varmoroso1 = '120 Días +';
                                                    $varmoroso2 = 120;
                                                }
                                            }
                                        }
                                    } else {
                                        $moroso = $obj->get_element_by_id('poliza','id_poliza',$idpoliza1b[$i]);
                                        if($date_comp >= $moroso[0]['f_desdepoliza']){
                                            $varmoroso = 'Moroso';

                                            $date1 = new DateTime($moroso[0]['f_desdepoliza']);
                                            $date2 = new DateTime($today);
                                            $diff = $date1->diff($date2);
                                    
                                            if($diff->days >= 30 && $diff->days < 60) {
                                                $varmoroso1 = '30 Días';
                                            }
                                            if(60 <= $diff->days && $diff->days < 90) {
                                                $varmoroso1 = '60 Días';
                                            }
                                            if(90 <= $diff->days && $diff->days < 120) {
                                                $varmoroso1 = '90 Días';
                                            }
                                            if($diff->days >= 120) {
                                                $varmoroso1 = '120 Días +';
                                            }
                                        }
                                    }

                                    if($ppendiente != 0 && $no_renov[0]['no_renov'] != 1 && $varmoroso == 'Moroso'){
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

                                        <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tool1b[$i]; ?>"><?= $ciente1b[$i]; ?> <span class="badge badge-pill badge-danger"><?= $varmoroso1;?></span></td>


                                        <td hidden><?= $newDesde1b[$i]; ?></td>
                                        <td hidden><?= $nomcia1b[$i]; ?></td>
                                        <td hidden><?= $nramo1b[$i]; ?></td>

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

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'01',$a_enero1b[$i]); 
                                        if ($p_enero1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Enero Año: ' . $a_enero1b[$i]; ?>" nowrap><?= $p_enero1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Enero Año: ' . $a_enero1b[$i]; ?>" nowrap><?= $p_enero1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'02',$a_febrero1b[$i]); 
                                        if ($p_febrero1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Febrero Año: ' . $a_febrero1b[$i]; ?>" nowrap><?= $p_febrero1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Febrero Año: ' . $a_febrero1b[$i]; ?>" nowrap><?= $p_febrero1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'03',$a_marzo1b[$i]);
                                        if ($p_marzo1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Marzo Año: ' . $a_marzo1b[$i]; ?>" nowrap><?= $p_marzo1bi ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Marzo Año: ' . $a_marzo1b[$i]; ?>" nowrap><?= $p_marzo1bi ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'04',$a_abril1b[$i]);
                                        if ($p_abril1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Abril Año: ' . $a_abril1b[$i]; ?>" nowrap><?= $p_abril1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Abril Año: ' . $a_abril1b[$i]; ?>" nowrap><?= $p_abril1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'05',$a_mayo1b[$i]);
                                        if ($p_mayo1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Mayo Año: ' . $a_mayo1b[$i]; ?>" nowrap><?= $p_mayo1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Mayo Año: ' . $a_mayo1b[$i]; ?>" nowrap><?= $p_mayo1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'06',$a_junio1b[$i]);
                                        if ($p_junio1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Junio Año: ' . $a_junio1b[$i]; ?>" nowrap><?= $p_junio1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Junio Año: ' . $a_junio1b[$i]; ?>" nowrap><?= $p_junio1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'07',$a_julio1b[$i]);
                                        if ($p_julio1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Julio Año: ' . $a_julio1b[$i]; ?>" nowrap><?= $p_julio1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Julio Año: ' . $a_julio1b[$i]; ?>" nowrap><?= $p_julio1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'08',$a_agosto1b[$i]);
                                        if ($p_agosto1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Agosto Año: ' . $a_agosto1b[$i]; ?>" nowrap><?= $p_agosto1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Agosto Año: ' . $a_agosto1b[$i]; ?>" nowrap><?= $p_agosto1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'09',$a_septiempre1b[$i]);
                                        if ($p_septiempre1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Septiembre Año: ' . $a_septiempre1b[$i]; ?>" nowrap><?= $p_septiempre1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Septiembre Año: ' . $a_septiempre1b[$i]; ?>" nowrap><?= $p_septiempre1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'10',$a_octubre1b[$i]);
                                        if ($p_octubre1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Octubre Año: ' . $a_octubre1b[$i]; ?>" nowrap><?= $p_octubre1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Octubre Año: ' . $a_octubre1b[$i]; ?>" nowrap><?= $p_octubre1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'11',$a_noviembre1b[$i]);
                                        if ($p_noviembre1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Noviembre Año: ' . $a_noviembre1b[$i]; ?>" nowrap><?= $p_noviembre1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Noviembre Año: ' . $a_noviembre1b[$i]; ?>" nowrap><?= $p_noviembre1bi; ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1b[$i],'12',$a_diciembre1b[$i]);
                                        if ($p_diciembre1b[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Diciembre Año: ' . $a_diciembre1b[$i]; ?>" nowrap><?= $p_diciembre1bi; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Diciembre Año: ' . $a_diciembre1b[$i]; ?>" nowrap><?= $p_diciembre1bi; ?></td>
                                        <?php } ?>
                                        <td hidden><?= $idpoliza1b[$i]; ?></td>
                                        <td hidden><?= $varmoroso2; ?></td>
                                    </tr>
                                <?php } }


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

                                        <td class="align-middle" data-toggle="tooltip" data-placement="top" title="<?= $tool1c[$i]; ?>"><?= $ciente1c[$i]; ?></td>

                                        <td hidden><?= $newDesde1c[$i]; ?></td>
                                        <td hidden><?= $nomcia1c[$i]; ?></td>
                                        <td hidden><?= $nramo1c[$i]; ?></td>

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

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],01,$a_enero1c[$i]); 
                                        if ($p_enero1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Enero Año: ' . $a_enero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1c[$i], 2); ?></td>
                                        <?php } elseif($p_enero1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Enero Año: ' . $a_enero1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Enero Año: ' . $a_enero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_enero1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],02,$a_febrero1c[$i]);
                                        if ($p_febrero1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Febrero Año: ' . $a_febrero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1c[$i], 2); ?></td>
                                        <?php } elseif($p_febrero1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Febrero Año: ' . $a_febrero1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right;" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Febrero Año: ' . $a_febrero1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_febrero1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],03,$a_marzo1c[$i]);
                                        if ($p_marzo1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Marzo Año: ' . $a_marzo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1c[$i], 2); ?></td>
                                        <?php } elseif($p_marzo1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Marzo Año: ' . $a_marzo1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Marzo Año: ' . $a_marzo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_marzo1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],04,$a_abril1c[$i]);
                                        if ($p_abril1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Abril Año: ' . $a_abril1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1c[$i], 2); ?></td>
                                        <?php } elseif($p_abril1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Abril Año: ' . $a_abril1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Abril Año: ' . $a_abril1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_abril1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],05,$a_mayo1c[$i]);
                                        if ($p_mayo1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Mayo Año: ' . $a_mayo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1c[$i], 2); ?></td>
                                        <?php } elseif($p_mayo1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Mayo Año: ' . $a_mayo1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Mayo Año: ' . $a_mayo1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_mayo1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],06,$a_junio1c[$i]);
                                        if ($p_junio1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Junio Año: ' . $a_junio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1c[$i], 2); ?></td>
                                        <?php } elseif($p_junio1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Junio Año: ' . $a_junio1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Junio Año: ' . $a_junio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_junio1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],07,$a_julio1c[$i]);
                                        if ($p_julio1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Julio Año: ' . $a_julio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1c[$i], 2); ?></td>
                                        <?php } elseif($p_julio1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Julio Año: ' . $a_julio1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Julio Año: ' . $a_julio1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_julio1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],'08',$a_agosto1c[$i]);
                                        if ($p_agosto1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Agosto Año: ' . $a_agosto1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1c[$i], 2); ?></td>
                                        <?php } elseif($p_agosto1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Agosto Año: ' . $a_agosto1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Agosto Año: ' . $a_agosto1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_agosto1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],'09',$a_septiempre1c[$i]);
                                        if ($p_septiempre1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Septiembre Año: ' . $a_septiempre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1c[$i], 2); ?></td>
                                        <?php } elseif($p_septiempre1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Septiembre Año: ' . $a_septiempre1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Septiembre Año: ' . $a_septiempre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_septiempre1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],'10',$a_octubre1c[$i]);
                                        if ($p_octubre1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Octubre Año: ' . $a_octubre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1c[$i], 2); ?></td>
                                        <?php } elseif($p_octubre1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Octubre Año: ' . $a_octubre1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Octubre Año: ' . $a_octubre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_octubre1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],'11',$a_noviembre1c[$i]);
                                        if ($p_noviembre1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Noviembre Año: ' . $a_noviembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1c[$i], 2); ?></td>
                                        <?php } elseif($p_noviembre1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Noviembre Año: ' . $a_noviembre1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Noviembre Año: ' . $a_noviembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_noviembre1c[$i], 2); ?></td>
                                        <?php } ?>

                                        <?php $day_moroso = $obj->get_day_moroso($idpoliza1c[$i],'12',$a_diciembre1c[$i]);
                                        if ($p_diciembre1c[$i] > 0) { ?>
                                            <td class="align-middle" style="text-align: right;color: #e65100" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Diciembre Año: ' . $a_diciembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1c[$i], 2); ?></td>
                                        <?php } elseif($p_diciembre1c[$i] == 0) { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Mes: Diciembre Año: ' . $a_diciembre1c[$i]; ?>" nowrap><?= ''; ?></td>
                                        <?php } else { ?>
                                            <td class="align-middle" style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= 'Día: ' . $day_moroso[0]['day'] . ' Mes: Diciembre Año: ' . $a_diciembre1c[$i]; ?>" nowrap><?= '$ ' . number_format($p_diciembre1c[$i], 2); ?></td>
                                        <?php } ?>
                                        <td hidden><?= $idpoliza1c[$i]; ?></td>
                                        <td hidden><?= $varmoroso2; ?></td>
                                    </tr>
                                <?php } } ?>

                            </tbody>


                            <tr class="no-tocar">
                                <td colspan="2" style="background-color: #F53333;color: white;font-weight: bold">Total: <font size=4><?= $totalpoliza; ?></font>
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
                                    <th>Nombre Titular</th>
                                    <th hidden>F Desde Seguro</th>
                                    <th hidden>Cía</th>
                                    <th hidden>Ramo</th>
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
                                    <th hidden>id</th>
                                    <th hidden>moroso</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <p class="h1 text-center">Total de Prima Suscrita</p>
                    <p class="h1 text-center text-danger">$ <?php echo number_format($totalpsMes, 2); ?></p>

                    <p class="h1 text-center">Total de Pólizas</p>
                    <p class="h1 text-center text-danger"><?php echo $totalpoliza; ?></p>


                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            
        </div>





        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>

</body>

</html>