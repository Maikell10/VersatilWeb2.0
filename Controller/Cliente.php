<?php

require_once dirname(__DIR__) . '../Model/Cliente.php';

$contAct = 0;
$contInact = 0;
$currency = "";

$mes_arr = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

$obj = new Cliente();

// --- v_cliente.php
if ($pag == 'v_cliente') {
    $ci_cliente = $_GET['id_cliente'];
    $id_titular = $_GET['id_titu'];

    $cliente = $obj->get_poliza_by_cliente($id_titular);

    $datos_c = $obj->get_element_by_id('titular', 'id_titular', $id_titular);
    $newFnac = date("d/m/Y", strtotime($datos_c[0]['f_nac']));

    $contCliente = ($cliente != 0) ? sizeof($cliente) : 0;
    for ($i = 0; $i < $contCliente; $i++) {
        $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
        if ($cliente[$i]['f_hastapoliza'] >= date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
            $contAct = $contAct + 1;
        } if($no_renov[0]['no_renov'] == 1) {
            $contAnu = $contAnu + 1;
        }
    }
    for ($i = 0; $i < $contCliente; $i++) {
        $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
        if ($cliente[$i]['f_hastapoliza'] < date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
            $contInact = $contInact + 1;
        }
    }
}

// --- email_cliente.php
if ($pag == 'email_cliente') {
    $id_titular = $_GET['id_titu'];

    $cliente = $obj->get_poliza_by_cliente($id_titular);

    $datos_c = $obj->get_element_by_id('titular', 'id_titular', $id_titular);
    $newFnac = date("d/m/Y", strtotime($datos_c[0]['f_nac']));

    $contCliente = ($cliente != 0) ? sizeof($cliente) : 0;
    for ($i = 0; $i < $contCliente; $i++) {
        $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
        if ($cliente[$i]['f_hastapoliza'] >= date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
            $contAct = $contAct + 1;
        } if($no_renov[0]['no_renov'] == 1) {
            $contAnu = $contAnu + 1;
        }
    }
    for ($i = 0; $i < $contCliente; $i++) {
        $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
        if ($cliente[$i]['f_hastapoliza'] < date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
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

// --- birthday.php
if ($pag == 'birthday') {
    $birthdays_no_date = $obj->get_birthdays_no_date();

    $birthdays_month = $obj->get_birthdays_month(date("m"));
    $date = date("m") + 1;
    if(date("m") == 12) {
        $date = 01;
    }
    $birthdays_next_month = $obj->get_birthdays_month($date);
}
