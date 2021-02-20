<?php
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

require_once "../Model/Poliza.php";
$obj = new Poliza();


$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$cia = $_GET['cia'];
$asesor = $_GET['asesor'];
$tPoliza = $_GET['tPoliza'];
$fhoy = date("Y-m-d");


if (!$asesor == '') {
    $asesor_para_recibir_via_url = stripslashes($asesor);
    $asesor_para_recibir_via_url = urldecode($asesor_para_recibir_via_url);
    $asesor = unserialize($asesor_para_recibir_via_url);
}

if (!$cia == '') {
    $cia_para_recibir_via_url = stripslashes($cia);
    $cia_para_recibir_via_url = urldecode($cia_para_recibir_via_url);
    $cia = unserialize($cia_para_recibir_via_url);
}

$anioH = date("Y", strtotime($hasta));
$mesH = date("m", strtotime($hasta));
$diaH = date("d", strtotime($hasta));

if ($mesH == 1 || $mesH == 3 || $mesH == 5 || $mesH == 7 || $mesH == 8 || $mesH == 10 || $mesH == 10) {
    $hasta = $anioH . "-" . $mesH . "-31";
}
if ($mesH == 4 || $mesH == 6 || $mesH == 9 || $mesH == 11) {
    $hasta = $anioH . "-" . $mesH . "-30";
}
if ($mesH == 2) {
    $hasta = $anioH . "-" . $mesH . "-28";
}

$distinct_a = $obj->get_gc_by_filtro_distinct_a_carga($desde, $hasta, $cia, $asesor);
$cantdistinct_a = ($distinct_a == null) ? 0 : sizeof($distinct_a);


$gc_h = $obj->agregarGCh($fhoy, $desde, $hasta, $tPoliza);


if($gc_h != 'no') {
    
    for ($a = 0; $a < $cantdistinct_a; $a++) {
        $distinct_fpgc = $obj->get_distinct_fgc_by_filtro_by_a($desde, $hasta, $cia, $distinct_a[$a]['cod_vend']);
        $cantdistinct_fpgc = ($distinct_fpgc == null) ? 0 : sizeof($distinct_fpgc);

        for ($b = 0; $b < $cantdistinct_fpgc; $b++) {
            $ref = $_GET['ref_'.$distinct_a[$a]['cod_vend'].'_'.$distinct_fpgc[$b]['f_pago_gc']];
            $ftransf = $_GET['ftransf'.$distinct_a[$a]['cod_vend'].'_'.$distinct_fpgc[$b]['f_pago_gc'].'_submit'];
            $montop = $_GET['montop'.$distinct_a[$a]['cod_vend'].'_'.$distinct_fpgc[$b]['f_pago_gc']];

            if ($ref != '' && $ftransf != '' && $montop != '') {
                $obj->agregarGChPago($gc_h, $ref, $ftransf, $montop, $distinct_a[$a]['cod_vend'],$distinct_fpgc[$b]['f_pago_gc']);
            }
        }
    
        $poliza = $obj->get_gc_by_filtro_by_a($desde, $hasta, $cia, $distinct_a[$a]['cod_vend']);
    
        for ($i = 0; $i < sizeof($poliza); $i++) {
            $gc_h_comision = $obj->agregarGChComision($gc_h, $poliza[$i]['id_comision']);
        }
    }

    header('Location: ../view/gc/b_gc.php');
}
