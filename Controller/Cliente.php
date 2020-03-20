<?php

require_once dirname(__DIR__) . '../Model/Cliente.php';

$contAct = 0;
$contInact = 0;
$currency = "";

$obj = new Cliente();

// --- v_cliente.php
if ($pag == 'v_cliente') {
    $ci_cliente = $_GET['id_cliente'];
    $id_titular = $_GET['id_titu'];

    $cliente = $obj->get_poliza_by_cliente($id_titular);

    $datos_c = $obj->get_element_by_id('titular', 'id_titular', $id_titular);
    $newFnac = date("d/m/Y", strtotime($datos_c[0]['f_nac']));

    for ($i = 0; $i < sizeof($cliente); $i++) {
        if ($cliente[$i]['f_hastapoliza'] >= date("Y-m-d")) {
            $contAct = $contAct + 1;
        }
    }
    for ($i = 0; $i < sizeof($cliente); $i++) {
        if ($cliente[$i]['f_hastapoliza'] <= date("Y-m-d")) {
            $contInact = $contInact + 1;
        }
    }
}

// --- e_cliente.php
if ($pag == 'e_cliente') {
    $id_titular = $_GET['id_titu'];

    $cliente = $obj->get_element_by_id('titular', 'id_titular', $id_titular);
    $newFPP = date("d-m-Y", strtotime($cliente[0]['f_nac']));
}
