<?php

require_once dirname(__DIR__) . '../Model/Asesor.php';

$totalPrima = 0;
$totalPrimaC = 0;
$totalCant = 0;

$obj = new Asesor();

// --- b_asesor.php
if ($pag == 'b_asesor') {
    $asesores = $obj->get_ejecutivo();
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
