<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Model/Poliza.php';
require_once '../Model/Grafico.php';

$obj = new Poliza();

$tarjeta = $obj->get_tarjeta_venc();
$contN = sizeof($tarjeta);

if ($_SESSION['id_permiso'] == 3) {
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $polizas = $obj->renovar_asesor($user[0]['cod_vend']);
} else {
    $polizas = $obj->renovar();
}
$cant_p = sizeof($polizas);

$contPR = sizeof($obj->get_polizas_r());
$pago_ref = ($obj->get_polizas_p() != 0) ? sizeof($obj->get_polizas_p()) : 0;

foreach ($polizas as $poliza) {
    $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
    if (sizeof($poliza_renov) != 0) {
        $cant_p = $cant_p - 1;
    }
}

$_SESSION['creado'] = 1;

$polizasP = $obj->get_poliza_pendiente();
$contPP = 0;
if ($polizasP != 0) {
    $contPP = sizeof($polizasP);
}

// Widget Pólizas NUEVAS Y RENOVADAS
$desdeWP = date("Y") . '-' . (date("m")) . '-01';
$hastaWP = date("Y") . '-' . (date("m")) . '-31';

$desdeWP_ant = (date("Y") - 1) . '-' . (date("m")) . '-01';
$hastaWP_ant = (date("Y") - 1) . '-' . (date("m")) . '-31';

// NUEVAS
$polizas_nuevas = $obj->get_poliza_total_by_filtro_f_nueva_n($desdeWP, $hastaWP, '', '', '');
$cant_polizas_nuevas = ($polizas_nuevas != 0) ? sizeof($polizas_nuevas) : 0;
$total_ps_pn = 0;
if ($cant_polizas_nuevas > 0) {
    foreach ($polizas_nuevas as $poliza_nueva) {
        $total_ps_pn = $total_ps_pn + $poliza_nueva['prima'];
    }
}
// Año anterior
$polizas_nuevas_ant = $obj->get_poliza_total_by_filtro_f_nueva_n($desdeWP_ant, $hastaWP_ant, '', '', '');
$cant_polizas_nuevas_ant = ($polizas_nuevas_ant != 0) ? sizeof($polizas_nuevas_ant) : 0;
$total_ps_pn_ant = 0;
if ($cant_polizas_nuevas_ant > 0) {
    foreach ($polizas_nuevas_ant as $poliza_nueva) {
        $total_ps_pn_ant = $total_ps_pn_ant + $poliza_nueva['prima'];
    }
}

// RENOVADAS
$polizas_renov = $obj->get_poliza_total_by_filtro_f_nueva_r($desdeWP, $hastaWP, '', '', '');
$cant_polizas_renov = ($polizas_renov != 0) ? sizeof($polizas_renov) : 0;
$total_ps_pr = 0;
if ($cant_polizas_renov > 0) {
    foreach ($polizas_renov as $poliza_renov) {
        $total_ps_pr = $total_ps_pr + $poliza_renov['prima'];
    }
}
// Año anterior
$polizas_renov_ant = $obj->get_poliza_total_by_filtro_f_nueva_r($desdeWP_ant, $hastaWP_ant, '', '', '');
$cant_polizas_renov_ant = ($polizas_renov_ant != 0) ? sizeof($polizas_renov_ant) : 0;
$total_ps_pr_ant = 0;
if ($cant_polizas_renov_ant > 0) {
    foreach ($polizas_renov_ant as $poliza_renov) {
        $total_ps_pr_ant = $total_ps_pr_ant + $poliza_renov['prima'];
    }
    for ($i = 0; $i < $cant_polizas_renov_ant; $i++) {
        $no_renov = $obj->verRenov1($polizas_renov_ant[$i]['id_poliza']);
        if ($no_renov[0]['no_renov'] == 1) {
            $cant_polizas_renov_ant = $cant_polizas_renov_ant - 1;
            $total_ps_pr_ant = $total_ps_pr_ant - $polizas_renov_ant[$i]['prima'];
        }
    }
}

for ($i = 0; $i < $cant_polizas_renov; $i++) {
    $no_renov = $obj->verRenov1($polizas_renov[$i]['id_poliza']);
    if ($no_renov[0]['no_renov'] == 1) {
        $cant_polizas_renov = $cant_polizas_renov - 1;
        $total_ps_pr = $total_ps_pr - $polizas_renov[$i]['prima'];
    }
}


// Widget UTILIDAD EN VENTAS
$mes_arr = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$mes_arr_s = ['','Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

$obj2 = new Grafico();

$desde = date("Y") . '-' . (date("m")) . '-01';
$hasta = date("Y") . '-' . (date("m")) . '-31';

$desde_ant = (date("Y") - 1) . '-' . (date("m")) . '-01';
$hasta_ant = (date("Y") - 1) . '-' . (date("m")) . '-31';

$mes = $obj2->get_prima_mm($desde, $hasta, '', '', '');

$mes_ant = $obj2->get_prima_mm($desde_ant, $hasta_ant, '', '', '');

if ($mes != 0) {

    $a = date("m");

    $desde = date("Y") . "-" . $mes[0]["Month(f_pago_prima)"] . "-01";
    $hasta = date("Y") . "-" . $mes[0]["Month(f_pago_prima)"] . "-31";

    $primaMes = $obj2->get_poliza_prima_mm('', $desde, $hasta, '', '');

    $primacMes = $obj2->get_poliza_pc_mm('', $desde, $hasta, '', '');
    $primacMesCant = $obj2->get_poliza_pc_mm_cant('', $desde, $hasta, '', '');

    $cantArrayPC[0] = sizeof($primacMesCant);
    $totalCantPC = $totalCantPC + $cantArrayPC[0];

    $sumaseguradaC = 0;
    $sumaseguradaCom = 0;
    $GCcobrada = 0;
    $perGC = 0;
    for ($a = 0; $a < sizeof($primacMes); $a++) {
        $sumaseguradaC = $sumaseguradaC + $primacMes[$a]['prima_com'];
        $sumaseguradaCom = $sumaseguradaCom + $primacMes[$a]['comision'];
        $GCcobrada = $GCcobrada + (($primacMes[$a]['comision'] * $primacMes[$a]['per_gc']) / 100);
    }
    $primacMesCant = $obj2->get_count_poliza_pc_mm('', $desde, $hasta, '', '');

    $totalc = $totalc + $sumaseguradaC;
    $totalCom = $totalCom + $sumaseguradaCom;
    $totalGC = $totalGC + $GCcobrada;
    $primaPorMesC[0] = $sumaseguradaC;
    $comisionPorMes[0] = $sumaseguradaCom;
    $comisionGC[0] = $GCcobrada;
    $perGCC[0] = ($comisionGC[0] / $comisionPorMes[0]) * 100;
    $totalperGC = $totalperGC + $perGCC[0];
}

if ($mes_ant != 0) {

    $a = date("m");

    $desde = (date("Y") - 1) . "-" . $mes_ant[0]["Month(f_pago_prima)"] . "-01";
    $hasta = (date("Y") - 1) . "-" . $mes_ant[0]["Month(f_pago_prima)"] . "-31";

    $primaMes = $obj2->get_poliza_prima_mm('', $desde, $hasta, '', '');

    $primacMes = $obj2->get_poliza_pc_mm('', $desde, $hasta, '', '');
    $primacMesCant = $obj2->get_poliza_pc_mm_cant('', $desde, $hasta, '', '');

    $cantArrayPC[0] = sizeof($primacMesCant);
    $totalCantPC = $totalCantPC + $cantArrayPC[0];

    $sumaseguradaC = 0;
    $sumaseguradaCom = 0;
    $GCcobrada = 0;
    $perGC = 0;
    for ($a = 0; $a < sizeof($primacMes); $a++) {
        $sumaseguradaC = $sumaseguradaC + $primacMes[$a]['prima_com'];
        $sumaseguradaCom = $sumaseguradaCom + $primacMes[$a]['comision'];
        $GCcobrada = $GCcobrada + (($primacMes[$a]['comision'] * $primacMes[$a]['per_gc']) / 100);
    }
    $primacMesCant = $obj2->get_count_poliza_pc_mm('', $desde, $hasta, '', '');

    $totalc = $totalc + $sumaseguradaC;
    $totalCom = $totalCom + $sumaseguradaCom;
    $totalGC = $totalGC + $GCcobrada;
    $primaPorMesC_ant[0] = $sumaseguradaC;
    $comisionPorMes[0] = $sumaseguradaCom;
    $comisionGC[0] = $GCcobrada;
    $perGCC[0] = ($comisionGC[0] / $comisionPorMes[0]) * 100;
    $totalperGC = $totalperGC + $perGCC[0];
}





// Widget GRAFICOS FINAL

for ($i=5; $i >= 0; $i--) { 
    $anio_b = date("Y",strtotime(date('d-m-Y')."- $i month")); 
    $mes_b = date("m",strtotime(date('d-m-Y')."- $i month")); 
    
    $desdeWP_G = $anio_b . '-' . $mes_b . '-01';
    $hastaWP_G = $anio_b . '-' . $mes_b . '-31';

    $desdeWP_G_ant = ($anio_b-1) . '-' . $mes_b . '-01';
    $hastaWP_G_ant = ($anio_b-1) . '-' . $mes_b . '-31';

    // NUEVAS
    $polizas_nuevas_G = $obj->get_poliza_total_by_filtro_f_nueva_n($desdeWP_G, $hastaWP_G, '', '', '');
    $cant_polizas_nuevas_G = ($polizas_nuevas_G != 0) ? sizeof($polizas_nuevas_G) : 0;
    $total_ps_pn_G = 0;
    if ($cant_polizas_nuevas_G > 0) {
        foreach ($polizas_nuevas_G as $poliza_nueva_G) {
            $total_ps_pn_G = $total_ps_pn_G + $poliza_nueva_G['prima'];
        }
    }

    // Año anterior
    $polizas_nuevas_ant_G = $obj->get_poliza_total_by_filtro_f_nueva_n($desdeWP_G_ant, $hastaWP_G_ant, '', '', '');
    $cant_polizas_nuevas_ant_G = ($polizas_nuevas_ant_G != 0) ? sizeof($polizas_nuevas_ant_G) : 0;
    $total_ps_pn_ant_G = 0;
    if ($cant_polizas_nuevas_ant_G > 0) {
        foreach ($polizas_nuevas_ant_G as $poliza_nueva_G) {
            $total_ps_pn_ant_G = $total_ps_pn_ant_G + $poliza_nueva_G['prima'];
        }
    }
    // RENOVADAS
    $polizas_renov_G = $obj->get_poliza_total_by_filtro_f_nueva_r($desdeWP_G, $hastaWP_G, '', '', '');
    $cant_polizas_renov_G = ($polizas_renov_G != 0) ? sizeof($polizas_renov_G) : 0;
    $total_ps_pr_G = 0;
    if ($cant_polizas_renov_G > 0) {
        foreach ($polizas_renov_G as $poliza_renov_G) {
            $total_ps_pr_G = $total_ps_pr_G + $poliza_renov_G['prima'];
        }
        for ($f = 0; $f < $cant_polizas_renov_G; $f++) {
            $no_renov = $obj->verRenov1($polizas_renov_G[$f]['id_poliza']);
            if ($no_renov[0]['no_renov'] == 1) {
                $cant_polizas_renov_G = $cant_polizas_renov_G - 1;
                $total_ps_pr_G = $total_ps_pr_G - $polizas_renov_G[$f]['prima'];
            }
        }
    }
    // Año anterior
    $polizas_renov_ant_G = $obj->get_poliza_total_by_filtro_f_nueva_r($desdeWP_G_ant, $hastaWP_G_ant, '', '', '');
    $cant_polizas_renov_ant_G = ($polizas_renov_ant_G != 0) ? sizeof($polizas_renov_ant_G) : 0;
    $total_ps_pr_ant_G = 0;
    if ($cant_polizas_renov_ant_G > 0) {
        foreach ($polizas_renov_ant_G as $poliza_renov_G) {
            $total_ps_pr_ant_G = $total_ps_pr_ant_G + $poliza_renov_G['prima'];
        }
        for ($f = 0; $f < $cant_polizas_renov_ant_G; $f++) {
            $no_renov = $obj->verRenov1($polizas_renov_ant_G[$f]['id_poliza']);
            if ($no_renov[0]['no_renov'] == 1) {
                $cant_polizas_renov_ant_G = $cant_polizas_renov_ant_G - 1;
                $total_ps_pr_ant_G = $total_ps_pr_ant_G - $polizas_renov_ant_G[$f]['prima'];
            }
        }
    }
    $total_sus_G[$i-1] = $total_ps_pn_G + $total_ps_pr_G;
    $total_sus_G_ant[$i-1] = $total_ps_pn_ant_G + $total_ps_pr_ant_G;


    // Grafico Cobranza
    if ($mes_b < 10) {
        $desde_cob_G = $anio_b . "-0" . $mes_b . "-01";
        $hasta_cob_G = $anio_b . "-0" . $mes_b . "-31";

        $desde_cob_G_ant = ($anio_b-1) . "-0" . $mes_b . "-01";
        $hasta_cob_G_ant = ($anio_b-1) . "-0" . $mes_b . "-31";
    } else {
        $desde_cob_G = $anio_b . "-" . $mes_b . "-01";
        $hasta_cob_G = $anio_b . "-" . $mes_b . "-31";

        $desde_cob_G_ant = ($anio_b-1) . "-" . $mes_b . "-01";
        $hasta_cob_G_ant = ($anio_b-1) . "-" . $mes_b . "-31";
    }

    $mesB = $mes_b;
    $primaMes = $obj2->get_poliza_c_cobrada_bn('', $desde_cob_G, $hasta_cob_G, '', $mesB, '');
    $primaMes_ant = $obj2->get_poliza_c_cobrada_bn('', $desde_cob_G_ant, $hasta_cob_G_ant, '', $mesB, '');

    if ($primaMes == 0) {
        $prima_pag[$i] = 0;
    } else{
        $cantMes = 0;
        for ($a = 0; $a < sizeof($primaMes); $a++) {
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-01-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-02-29')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-03-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-04-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-05-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-06-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-07-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-08-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-09-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-10-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-11-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $anio_b . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $anio_b . '-12-31')) {
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
        }
        $prima_pag[$i] = $cantMes;
    }

    if ($primaMes_ant == 0) {
        $prima_pag_ant[$i] = 0;
    } else{
        $cantMes_ant = 0;
        for ($a = 0; $a < sizeof($primaMes_ant); $a++) {
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-01-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-01-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-02-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-02-29')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-03-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-03-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-04-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-04-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-05-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-05-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-06-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-06-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-07-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-07-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-08-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-08-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-09-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-09-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-10-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-10-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-11-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-11-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
            if (($primaMes_ant[$a]['f_pago_prima'] >= ($anio_b-1) . '-12-01') && ($primaMes_ant[$a]['f_pago_prima'] <= ($anio_b-1) . '-12-31')) {
                $cantMes_ant = $cantMes_ant + $primaMes_ant[$a]['prima_com'];
            }
        }
        $prima_pag_ant[$i] = $cantMes_ant;
    }
}

$di = date("m");
$di_i = date("m",strtotime(date('Y-m-d')."- 5 month")) + 1;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">
        <div class="card-header p-5">

            <!--Grid row-->
            <!--
            <div class="row container m-auto">

                <div class="col-md-6 mb-4">

                    <div class="card gradient-card">

                        <div class="card-image" style="background-image: url(https://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20%2814%29.jpg)">

                            <a href="#!">
                                <div class="text-white d-flex h-100 mask blue-gradient-rgba">
                                    <div class="first-content align-self-center p-3">
                                        <h3 class="card-title">Cobranza del mes <?= $mes_arr[date("m") - 2]; ?></h3>
                                        <p class="lead mb-0">Clic para ver los detalles</p>
                                    </div>
                                    <div class="second-content align-self-center mx-auto text-center">
                                        <i class="far fa-money-bill-alt fa-3x"></i>
                                    </div>
                                </div>
                            </a>

                        </div>

                        <div class="third-content ml-auto mr-4 mb-2">
                            <p class="text-uppercase text-muted mt-5 ml-2">Cobranza del mes <?= $mes_arr[date("m") - 2]; ?></p>
                            <h4 class="font-weight-bold float-right" data-toggle="tooltip" data-placement="top" title="Prima Cobrada"><?= "$" . number_format($primaPorMesC[0], 2); ?></h4>
                        </div>

                        <div class="card-body white">
                            <div class="progress md-progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-muted">Mejor que el mes pasado (25%)</p>
                            <h4 class="text-uppercase font-weight-bold my-4">Utilidad en Ventas</h4>
                            <p class="text-muted" align="justify">
                                <?= "$" . number_format(($comisionPorMes[0] - $comisionGC[0]), 2); ?>
                                <a href="grafic/Listados/poliza_uv.php?mes=<?= date("m") - 1; ?>&anio=<?= date("Y"); ?>&ramo=&cia=&tipo_cuenta=" target="_blank" class="btn blue-gradient-rgba text-white ml-2">Ver Detalle</a>
                            </p>
                        </div>

                    </div>

                </div>

                <div class="col-md-6 mb-4">

                    <div class="card gradient-card">

                        <div class="card-image" style="background-image: url(https://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20%2814%29.jpg);">

                            <a href="#!">
                                <div class="text-white d-flex h-100 mask purple-gradient-rgba">
                                    <div class="first-content align-self-center p-3">
                                        <h3 class="card-title">Pólizas Nuevas del mes <?= $mes_arr[date("m") - 1]; ?></h3>
                                        <p class="lead mb-0">Clic para ver los detalles</p>
                                    </div>
                                    <div class="second-content  align-self-center mx-auto text-center">
                                        <i class="fas fa-chart-line fa-3x"></i>
                                    </div>
                                </div>
                            </a>

                        </div>

                        <div class="third-content  ml-auto mr-4 mb-2">
                            <p class="text-uppercase text-muted mt-5 ml-2">Pólizas Nuevas del mes <?= $mes_arr[date("m") - 1]; ?></p>
                            <h4 class="font-weight-bold float-right"><?= $cant_polizas_nuevas; ?></h4>
                        </div>

                        <div class="card-body white">
                            <?php if ($cant_polizas_nuevas_ant > $cant_polizas_nuevas) {
                                $dif_per = 100 - (($cant_polizas_nuevas * 100) / $cant_polizas_nuevas_ant);
                            ?>
                                <div class="progress md-progress">
                                    <div class="progress-bar purple lighten-2" role="progressbar" style="width: <?= $dif_per; ?>%" aria-valuenow="<?= $dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted">Por Debajo que el mes pasado (<?= number_format($dif_per, 2); ?>%)</p>
                            <?php } else { ?>
                                <div class="progress md-progress">
                                    <div class="progress-bar purple lighten-2" role="progressbar" style="width: <?= $dif_per; ?>%" aria-valuenow="<?= $dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted">Mejor que el mes pasado (<?= number_format($dif_per, 2); ?>%)</p>
                            <?php } ?>


                            <a href="f_nueva.php?desdeP_submit=<?= $desdeWP; ?>&hastaP_submit=<?= $hastaWP; ?>" target="_blank" class="btn purple-gradient-rgba text-white ml-2">Ver Pólizas Emitidas</a>
                        </div>

                    </div>

                </div>

                <div class="col-md-6 mb-4">

                    <div class="card gradient-card">

                        <div class="card-image" style="background-image: url(https://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20%2814%29.jpg);">

                            <a href="#!">
                                <div class="text-white d-flex h-100 mask peach-gradient-rgba">
                                    <div class="first-content align-self-center p-3">
                                        <h3 class="card-title">Pólizas Anuladas del mes <?= $mes_arr[date("m") - 1]; ?></h3>
                                        <p class="lead mb-0">Clic para ver los detalles</p>
                                    </div>
                                    <div class="second-content  align-self-center mx-auto text-center">
                                        <i class="fas fa-chart-pie fa-3x"></i>
                                    </div>
                                </div>
                            </a>

                        </div>

                        <div class="third-content  ml-auto mr-4 mb-2">
                            <p class="text-uppercase text-muted mt-5 ml-2">Pólizas Anuladas del mes <?= $mes_arr[date("m") - 1]; ?></p>
                            <h4 class="font-weight-bold float-right">20000</h4>
                        </div>

                        <div class="card-body white animated">
                            <div class="progress md-progress">
                                <div class="progress-bar amber darken-3" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-muted">Por Debajo que el mes pasado (75%)</p>
                            <h4 class="text-uppercase font-weight-bold my-4">Detalles</h4>
                            <canvas id="lineChart"></canvas>
                        </div>

                    </div>

                </div>

                <div class="col-md-6 mb-4">

                    <div class="card gradient-card">

                        <div class="card-image" style="background-image: url(https://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20%2814%29.jpg);">

                            <a href="#!">
                                <div class="text-white d-flex h-100 mask aqua-gradient-rgba">
                                    <div class="first-content align-self-center p-3">
                                        <h3 class="card-title">Pólizas Renovadas del mes <?= $mes_arr[date("m") - 1]; ?></h3>
                                        <p class="lead mb-0">Clic para ver los detalles</p>
                                    </div>
                                    <div class="second-content  align-self-center mx-auto text-center">
                                        <i class="fas fa-chart-line fa-3x"></i>
                                    </div>
                                </div>
                            </a>

                        </div>

                        <div class="third-content  ml-auto mr-4 mb-2">
                            <p class="text-uppercase text-muted mt-5 ml-2" style="margin-top: 105px">Pólizas Renovadas del mes <?= $mes_arr[date("m") - 1]; ?></p>
                            <h4 class="font-weight-bold float-right"><?= $cant_polizas_renov; ?></h4>
                        </div>

                        <div class="card-body white">
                            <?php if ($cant_polizas_renov_ant > $cant_polizas_renov) {
                                $dif_per = 100 - (($cant_polizas_renov * 100) / $cant_polizas_renov_ant);
                            ?>
                                <div class="progress md-progress">
                                    <div class="progress-bar cyan lighten-1" role="progressbar" style="width: <?= $dif_per; ?>%" aria-valuenow="<?= $dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted">Por Debajo que el mes pasado (<?= number_format($dif_per, 2); ?>%)</p>
                            <?php } else { ?>
                                <div class="progress md-progress">
                                    <div class="progress-bar cyan lighten-1" role="progressbar" style="width: <?= $dif_per; ?>%" aria-valuenow="<?= $dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted">Mejor que el mes pasado (<?= number_format($dif_per, 2); ?>%)</p>
                            <?php } ?>

                            <a href="f_nueva.php?desdeP_submit=<?= $desdeWP; ?>&hastaP_submit=<?= $hastaWP; ?>" target="_blank" class="btn aqua-gradient-rgba text-white ml-2">Ver Pólizas Emitidas</a>
                        </div>

                    </div>

                </div>

            </div> -->
            <!--Grid row-->


            <h1 class="text-center font-weight-bold">
                Bienvenido <?= $_SESSION['seudonimo']; ?> <i class="fas fa-user pr-2 cyan-text"></i></h1>

            <?php if ($user[0]['id_permiso'] == 3) {
                $cods_asesor = $obj->get_cod_a_by_user($user[0]['cedula_usuario']);
            ?>
                <h5 class="text-center font-weight-bold text-success">Código del Usuario: <?= $user[0]['cod_vend']; ?></h5>
                <?php if (count($cods_asesor) != 1) { ?>
                    <div class="text-center">
                        <a class="btn blue-gradient" href="#" data-toggle="modal" data-target="#modalLoginAvatarDemo"><i class="fas fa-atom pr-2"></i>Cambiar Código</a>
                    </div>
            <?php }
            } ?>
            <hr />
        </div>
        <div class="card-body ml-auto mr-auto">
            <ul class="nav md-pills  pills-primary" role="tablist">
                <li class="nav-item m-auto">
                    <a class="nav-link p-4" href="produccion.php"><i class="fas fa-table fa-3x"></i>
                        <h4>Producción
                            <?php if (($contN != 0 || $contPP != 0) && ($_SESSION['id_permiso'] != 3)) { ?>
                                <span class="badge badge-pill peach-gradient ml-2"><?= $contN + $contPP; ?></span>
                            <?php } ?>
                        </h4>
                    </a>
                </li>
                <li class="nav-item m-auto">
                    <a class="nav-link p-4" href="renovacion.php"><i class="fas fa-stopwatch fa-3x"></i>
                        <h4>Renovación
                            <?php if ($cant_p != 0) { ?>
                                <span class="badge badge-pill peach-gradient ml-2"><?= $cant_p; ?></span>
                            <?php } ?>
                        </h4>
                    </a>
                </li>

                <li class="nav-item m-auto">
                    <a class="nav-link p-4" href="administracion.php"><i class="fas fa-clock fa-3x"></i>
                        <h4>Administración
                            <?php if (($contPR != 0 && $_SESSION['id_permiso'] == 1) || ($pago_ref != 0 && $_SESSION['id_permiso'] == 1)) { ?>
                                <span class="badge badge-pill peach-gradient ml-2"><?= $contPR + $pago_ref; ?></span>
                            <?php } ?>
                        </h4>
                    </a>
                </li>

                <li class="nav-item m-auto">
                    <a class="nav-link p-4" href="graficos.php"><i class="fas fa-chart-line fa-3x"></i>
                        <h4>Gráficos</h4>
                    </a>
                </li>
                <li class="nav-item m-auto">
                    <a class="nav-link p-4" href="crm.php"><i class="fas fa-book fa-3x"></i>
                        <h4>Gestión del Cliente</h4>
                    </a>
                </li>
            </ul>


            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                <!-- Section: Widgets -->
                <section class="mb-5">
                    <h3 class="text-center mb-3">Datos del mes de <?= $mes_arr[date("m") - 1]; ?></h3>
                    <!-- First row -->
                    <div class="row">
                        <!-- First column -->
                        <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">

                            <!-- Card -->
                            <div class="card card-cascade cascading-admin-card">

                                <!-- Card Data -->
                                <div class="admin-up">
                                    <i class="fas fa-chart-bar primary-color mr-3 z-depth-2"></i>
                                    <div class="data">
                                        <p class="text-uppercase">Suscripción Total</p>
                                        <h4 class="font-weight-bold dark-grey-text" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" style="font-size: 1.1rem">$ <?= number_format($total_ps_pn + $total_ps_pr, 2); ?></h4>
                                    </div>
                                </div>

                                <!-- Card content -->
                                <div class="card-body card-body-cascade">
                                    <?php if (($total_ps_pn_ant + $total_ps_pr_ant) > ($total_ps_pn + $total_ps_pr)) {
                                        $dif_per = 100 - ((($total_ps_pn + $total_ps_pr) * 100) / ($total_ps_pn_ant + $total_ps_pr_ant));
                                    ?>
                                        <div class="progress mb-3">
                                            <div class="progress-bar red accent-2" role="progressbar" style="width: <?= 100-$dif_per; ?>%" aria-valuenow="<?= 100-$dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="card-text" style="font-size: .7rem">Por Debajo que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                                    <?php } else {
                                        $dif_per = 100 - ((($total_ps_pn_ant + $total_ps_pr_ant) * 100) / ($total_ps_pn + $total_ps_pr)); ?>
                                        <div class="progress mb-3">
                                            <div class="progress-bar bg-success accent-2" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="card-text" style="font-size: .7rem">Mejor que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                                    <?php } ?>

                                    <a href="f_nueva.php?desdeP_submit=<?= $desdeWP; ?>&hastaP_submit=<?= $hastaWP; ?>" target="_blank" class="btn btn-primary btn-block m-auto"><i class="fas fa-eye"></i></a>
                                </div>

                            </div>
                            <!-- Card -->

                        </div>
                        <!-- First column -->

                        <!-- Second column -->
                        <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">

                            <!-- Card -->
                            <div class="card card-cascade cascading-admin-card">

                                <!-- Card Data -->
                                <div class="admin-up">
                                    <i class="fas fa-chart-line warning-color mr-3 z-depth-2"></i>
                                    <div class="data">
                                        <p class="text-uppercase">Pólizas Nuevas</p>
                                        <h4 class="font-weight-bold dark-grey-text" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" style="font-size: 1.1rem">$ <?= number_format($total_ps_pn, 2); ?></h4>
                                    </div>
                                </div>

                                <!-- Card content -->
                                <div class="card-body card-body-cascade">
                                    <?php if ($total_ps_pn_ant > $total_ps_pn) {
                                        $dif_per = 100 - (($total_ps_pn * 100) / $total_ps_pn_ant);
                                    ?>
                                        <div class="progress mb-3">
                                            <div class="progress-bar red accent-2" role="progressbar" style="width: <?= 100-$dif_per; ?>%" aria-valuenow="<?= 100-$dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="card-text" style="font-size: .7rem">Por Debajo que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                                    <?php } else {
                                        if ($total_ps_pn != 0) {
                                            $dif_per = 100 - (($total_ps_pn_ant * 100) / $total_ps_pn);
                                        } else {
                                            $dif_per = 0;
                                        }
                                    ?>
                                        <div class="progress mb-3">
                                            <div class="progress-bar bg-success accent-2" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="card-text" style="font-size: .7rem">Mejor que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                                    <?php } ?>

                                    <a href="f_nueva_widget.php?desdeP_submit=<?= $desdeWP; ?>&hastaP_submit=<?= $hastaWP; ?>" target="_blank" class="btn btn-warning btn-block m-auto"><i class="fas fa-eye"></i></a>
                                </div>

                            </div>
                            <!-- Card -->

                        </div>
                        <!-- Second column -->

                        <!-- Third column -->
                        <div class="col-xl-3 col-md-6 mb-md-0 mb-4">

                            <!-- Card -->
                            <div class="card card-cascade cascading-admin-card">

                                <!-- Card Data -->
                                <div class="admin-up">
                                    <i class="fas fa-chart-line light-blue lighten-1 mr-3 z-depth-2"></i>
                                    <div class="data">
                                        <p class="text-uppercase" style="margin-top: -5px;">Pólizas</p>
                                        <p class="text-uppercase" style="margin-top: -20px;">Renovadas</p>
                                        <h4 class="font-weight-bold dark-grey-text" style="margin-top: -8px;font-size: 1.1rem" data-toggle="tooltip" data-placement="top" title="Prima Suscrita">$ <?= number_format($total_ps_pr, 2); ?></h4>
                                    </div>
                                </div>

                                <!-- Card content -->
                                <div class="card-body card-body-cascade">
                                    <?php if ($total_ps_pr_ant > $total_ps_pr) {
                                        $dif_per = 100 - (($total_ps_pr * 100) / $total_ps_pr_ant);
                                    ?>
                                        <div class="progress mb-3">
                                            <div class="progress-bar red accent-2" role="progressbar" style="width: <?= 100-$dif_per; ?>%" aria-valuenow="<?= 100-$dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="card-text" style="font-size: .7rem">Por Debajo que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                                    <?php } else {
                                        if ($total_ps_pr != 0) {
                                            $dif_per = 100 - (($total_ps_pr_ant * 100) / $total_ps_pr);
                                        } else {
                                            $dif_per = 0;
                                        }
                                    ?>
                                        <div class="progress mb-3">
                                            <div class="progress-bar bg-success accent-2" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="card-text" style="font-size: .7rem">Mejor que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                                    <?php } ?>

                                    <a href="f_nueva_renov_widget.php?desdeP_submit=<?= $desdeWP; ?>&hastaP_submit=<?= $hastaWP; ?>" target="_blank" class="btn light-blue text-white btn-block m-auto"><i class="fas fa-eye"></i></a>
                                </div>

                            </div>
                            <!-- Card -->

                        </div>
                        <!-- Third column -->

                        <!-- Fourth column -->
                        <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">

                            <!-- Card -->
                            <div class="card card-cascade cascading-admin-card">

                                <!-- Card Data -->
                                <div class="admin-up">
                                    <i class="far fa-money-bill-alt red accent-2 mr-3 z-depth-2"></i>
                                    <div class="data">
                                        <p class="text-uppercase">Cobranza</p>
                                        <h4 class="font-weight-bold dark-grey-text" data-toggle="tooltip" data-placement="top" title="Prima Cobrada" style="font-size: 1.1rem"><?= "$" . number_format($primaPorMesC[0], 2); ?></h4>
                                    </div>
                                </div>

                                <!-- Card content -->
                                <div class="card-body card-body-cascade">
                                    <?php if ($primaPorMesC_ant[0] > $primaPorMesC[0]) {
                                        $dif_per = 100 - (($primaPorMesC[0] * 100) / $primaPorMesC_ant[0]);
                                    ?>
                                        <div class="progress mb-3">
                                            <div class="progress-bar red accent-2" role="progressbar" style="width: <?= 100-$dif_per; ?>%" aria-valuenow="<?= 100-$dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="card-text" style="font-size: .7rem">Por Debajo que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                                    <?php } else {
                                        $dif_per = 100 - (($primaPorMesC_ant[0] * 100) / $primaPorMesC[0]); ?>
                                        <div class="progress mb-3">
                                            <div class="progress-bar bg-success accent-2" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="card-text" style="font-size: .7rem">Mejor que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                                    <?php } ?>

                                    <a href="grafic/Listados/poliza_uv.php?mes=<?= date("m"); ?>&anio=<?= date("Y"); ?>&ramo=&cia=&tipo_cuenta=" target="_blank" class="btn red btn-block text-white m-auto"><i class="fas fa-eye"></i></a>
                                </div>

                            </div>
                            <!-- Card -->

                        </div>
                        <!-- Fourth column -->

                    </div>
                    <!-- First row -->

                </section>
                <!-- Section: Widgets -->
            <?php } ?>

        </div>
    </div>


    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php if ($_SESSION['id_permiso'] == 1) { ?>
    <div class="section mt-5 mb-5">
        <div class="row container m-auto">
            <div class="col-xl-6 col-md-12 col-lg-6 mb-xl-0 mb-4">
                <!-- Suscripcion -->
                <div class="card card-cascade cascading-admin-card">

                    <!-- Card Data -->
                    <div class="admin-up">
                        <i class="fas fa-chart-bar primary-color mr-3 z-depth-2"></i>
                        <div class="data">
                            <p class="text-uppercase">Suscripción Mes en Curso</p>
                            <h4 class="font-weight-bold dark-grey-text" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" style="font-size: 1.1rem">$ <?= number_format($total_ps_pn + $total_ps_pr, 2); ?></h4>
                        </div>
                    </div>

                    <!-- Card content -->
                    <div class="card-body card-body-cascade">
                        <?php if (($total_ps_pn_ant + $total_ps_pr_ant) > ($total_ps_pn + $total_ps_pr)) {
                            $dif_per = 100 - ((($total_ps_pn + $total_ps_pr) * 100) / ($total_ps_pn_ant + $total_ps_pr_ant));
                        ?>
                            <div class="progress mb-3">
                                <div class="progress-bar red accent-2" role="progressbar" style="width: <?= 100-$dif_per; ?>%" aria-valuenow="<?= 100-$dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="card-text" style="font-size: .8rem">Por Debajo que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                        <?php } else {
                            $dif_per = 100 - ((($total_ps_pn_ant + $total_ps_pr_ant) * 100) / ($total_ps_pn + $total_ps_pr)); ?>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success accent-2" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="card-text" style="font-size: .8rem">Mejor que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                        <?php } ?>

                        <canvas id="lineChart"></canvas>
                        
                        

                    </div>

                </div>
            </div>

            <div class="col-xl-6 col-md-12 col-lg-6 mb-xl-0 mb-4">
                <!-- Cobranza -->
                <div class="card card-cascade cascading-admin-card">

                    <!-- Card Data -->
                    <div class="admin-up">
                        <i class="far fa-money-bill-alt red accent-2 mr-3 z-depth-2"></i>
                        <div class="data">
                            <p class="text-uppercase">Cobranza mes en curso</p>
                            <h4 class="font-weight-bold dark-grey-text" data-toggle="tooltip" data-placement="top" title="Prima Suscrita" style="font-size: 1.1rem"><?= "$" . number_format($primaPorMesC[0], 2); ?></h4>
                        </div>
                    </div>

                    <!-- Card content -->
                    <div class="card-body card-body-cascade">
                        <?php if ($primaPorMesC_ant[0] > $primaPorMesC[0]) {
                            $dif_per = 100 - (($primaPorMesC[0] * 100) / $primaPorMesC_ant[0]);
                        ?>
                            <div class="progress mb-3">
                                <div class="progress-bar red accent-2" role="progressbar" style="width: <?= 100-$dif_per; ?>%" aria-valuenow="<?= 100-$dif_per; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="card-text" style="font-size: .8rem">Por Debajo que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                        <?php } else {
                            $dif_per = 100 - (($primaPorMesC_ant[0] * 100) / $primaPorMesC[0]); ?>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success accent-2" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="card-text" style="font-size: .8rem">Mejor que el año pasado (<?= number_format($dif_per, 2); ?>%)</p>
                        <?php } ?>

                        <canvas id="cobranzaChart"></canvas>
                        
                        
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

    <script>
        // Grafico Suscripcion
        var ctxL = document.getElementById("lineChart").getContext('2d');
        var myLineChart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: ["<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 4 month"))]; ?>", "<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 3 month"))]; ?>", "<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 2 month"))]; ?>", "<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 1 month"))]; ?>", "<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 0 month"))]; ?>"],
                datasets: [
                    {
                        fill: false,
                        borderColor: "#03a9f4",
                        pointBackgroundColor: "#03a9f4",
                        data: [<?= $total_sus_G[3]; ?>, <?= $total_sus_G[2]; ?>, <?= $total_sus_G[1]; ?>, <?= $total_sus_G[0]; ?>, <?= $total_sus_G[-1]; ?>],
                        label: 'Suscripción Año en Curso',
                        pointHoverRadius: 15,
                        pointHitRadius: 7,
                        pointRadius: 4
                    },
                    {
                        fill: false,
                        borderColor: "red",
                        pointBackgroundColor: "red",
                        data: [<?= $total_sus_G_ant[3]; ?>, <?= $total_sus_G_ant[2]; ?>, <?= $total_sus_G_ant[1]; ?>, <?= $total_sus_G_ant[0]; ?>, <?= $total_sus_G_ant[-1]; ?>],
                        label: 'Suscripción Año Anterior',
                        pointHoverRadius: 15,
                        pointHitRadius: 7,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                legend: {
                    display: true
                },
                elements: {
                    line: {
                        tension: 0.0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                        },
                        ticks: {
                            padding: 15,
                            height: 30
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 15,
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                            return '$ ' + datasetLabel
                        }
                    }
                }
            }
        });

        // Grafico Cobranza
        var ctxL = document.getElementById("cobranzaChart").getContext('2d');
        var myLineChart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: ["<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 4 month"))]; ?>", "<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 3 month"))]; ?>", "<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 2 month"))]; ?>", "<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 1 month"))]; ?>", "<?= $mes_arr_s[date("n",strtotime(date('d-m-Y')."- 0 month"))]; ?>"],
                datasets: [
                    {
                        fill: false,
                        borderColor: "#03a9f4",
                        pointBackgroundColor: "#03a9f4",
                        data: [<?= $prima_pag[4]; ?>, <?= $prima_pag[3]; ?>, <?= $prima_pag[2]; ?>, <?= $prima_pag[1]; ?>, <?= $prima_pag[0]; ?>],
                        label: 'Prima Cobrada Año en Curso',
                        pointHoverRadius: 15,
                        pointHitRadius: 7,
                        pointRadius: 4
                    },
                    {
                        fill: false,
                        borderColor: "red",
                        pointBackgroundColor: "red",
                        data: [<?= $prima_pag_ant[4]; ?>, <?= $prima_pag_ant[3]; ?>, <?= $prima_pag_ant[2]; ?>, <?= $prima_pag_ant[1]; ?>, <?= $prima_pag_ant[0]; ?>],
                        label: 'Prima Cobrada Año en Anterior',
                        pointHoverRadius: 15,
                        pointHitRadius: 7,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                legend: {
                    display: true
                },
                elements: {
                    line: {
                        tension: 0.0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                        },
                        ticks: {
                            padding: 15,
                            height: 30
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 15,
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                            return '$ ' + datasetLabel
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>