<?php

require_once dirname(__DIR__) . '../Model/Asesor.php';

$totalPrima = 0;
$totalPrimaC = 0;
$totalCant = 0;

$mes_arr = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

$obj = new Asesor();

// --- b_asesor.php
if ($pag == 'b_asesor') {
    $asesores = $obj->get_ejecutivo();
}

// --- gc/b_asesor.php
if ($pag == 'gc/b_asesor') {
    $asesores = $obj->get_distinct_asesor_por_gc();
}

// --- v_gc_asesor.php
if ($pag == 'v_gc_asesor') {
    $asesor = $obj->get_ejecutivo_by_cod($_GET['asesor']);
    $poliza = $obj->get_gc_pago_por_asesor($_GET['asesor']);

    if($poliza == 0) {
        $distinct_poliza = $obj->get_distinct_poliza_gc_pago_por_proyecto($_GET['asesor']);
    }
}

// --- v_gc_asesor1.php
if ($pag == 'v_gc_asesor1') {
    $desde = $_GET['desdeP_submit'];
    $hasta = $_GET['hastaP_submit'];

    if ($desde == '' || $hasta == '') {
        header("Location: v_gc_asesor.php?m=1&asesor=" . $_GET['asesor'] . "");
    }

    $asesor = $obj->get_ejecutivo_by_cod($_GET['asesor']);
    $poliza = $obj->get_gc_pago_por_asesor_by_busq($_GET['asesor'],$desde, $hasta);

    if($poliza == 0) {
        $distinct_poliza = $obj->get_distinct_poliza_gc_pago_por_proyecto($_GET['asesor']);
    }
}

// --- v_asesor.php
if ($pag == 'v_asesor') {
    require_once '../Model/Cliente.php';

    $cod_asesor = $_GET['cod_asesor'];

    $obj = new Cliente();
    $asesor = $obj->get_asesor_by_cod('ena', $cod_asesor);
    $nombre = $asesor[0]['idnom'];
    $id = $asesor[0]['idena'];
    $a = 1;

    if (sizeof($asesor) == null) {
        $asesor = $obj->get_asesor_by_cod('enp', $cod_asesor);
        $nombre = $asesor[0]['nombre'];
        $id = $asesor[0]['id_enp'];
        $a = 2;
    }

    if (sizeof($asesor) == null) {
        $asesor = $obj->get_asesor_by_cod('enr', $cod_asesor);
        $nombre = $asesor[0]['nombre'];
        $id = $asesor[0]['id_enr'];
        $a = 3;
    }
}

// --- ena/b_asesor.php
if ($pag == 'ena/b_asesor') {
    $asesor = $obj->get_element('ena', 'idnom');
}

// --- ena/b_referidor.php
if ($pag == 'ena/b_referidor') {
    $referidor = $obj->get_element('enr', 'nombre');
}

// --- ena/b_proyecto.php
if ($pag == 'ena/b_proyecto') {
    $proyecto = $obj->get_element('enp','nombre'); 
}
