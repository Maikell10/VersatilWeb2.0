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


$distinct_a = $obj->get_gc_p_by_filtro_a_pago($desde, $hasta, $cia, $asesor);


for ($i = 0; $i < sizeof($distinct_a); $i++) {
    if ($distinct_a[$i]['currency'] == '$') {

        if ($distinct_a[$i]['pago'] == 'CONSECUTIVO') {
            $gc_h_p = $obj->agregarGChP($distinct_a[$i]['id_poliza'],$distinct_a[$i]['per_gc'],$distinct_a[$i]['id_comision']);

            if ($_GET['n_transf'.$distinct_a[$i]['id_poliza']] != '' || $_GET['n_banco'.$distinct_a[$i]['id_poliza']] != '') {
                $datos = array(
                    $gc_h_p['id_gc_h_p'],
                    $_GET['id_usuarioS'],
                    $_GET['n_transf'.$distinct_a[$i]['id_poliza']],
                    $_GET['n_banco'.$distinct_a[$i]['id_poliza']],
                    $_GET['f_pago_gc_p'.$distinct_a[$i]['id_poliza']],
                    $_GET['monto_p'.$distinct_a[$i]['id_poliza']]
                );
                
                $obj->agregarCargaPagoP($datos);
            }
        } else {
            $gc_h_p = $obj->agregarGChP($distinct_a[$i]['id_poliza'],$distinct_a[$i]['per_gc'],null);

            if ($_GET['n_transf'.$distinct_a[$i]['id_poliza']] != '' || $_GET['n_banco'.$distinct_a[$i]['id_poliza']] != '') {
                $datos = array(
                    $gc_h_p['id_gc_h_p'],
                    $_GET['id_usuarioS'],
                    $_GET['n_transf'.$distinct_a[$i]['id_poliza']],
                    $_GET['n_banco'.$distinct_a[$i]['id_poliza']],
                    $_GET['f_pago_gc_p'.$distinct_a[$i]['id_poliza']],
                    $_GET['monto_p'.$distinct_a[$i]['id_poliza']]
                );
                
                $obj->agregarCargaPagoP($datos);
            }
        }

    } else {

        if ($distinct_a[$i]['pago'] == 'CONSECUTIVO') {
            $monto = ($distinct_a[$i]['prima']*$distinct_a[$i]['per_gc'])/100;
            $gc_h_p = $obj->agregarGChP($distinct_a[$i]['id_poliza'],$monto,$distinct_a[$i]['id_comision']);

            if ($_GET['n_transf'.$distinct_a[$i]['id_poliza']] != '' || $_GET['n_banco'.$distinct_a[$i]['id_poliza']] != '') {
                $datos = array(
                    $gc_h_p['id_gc_h_p'],
                    $_GET['id_usuarioS'],
                    $_GET['n_transf'.$distinct_a[$i]['id_poliza']],
                    $_GET['n_banco'.$distinct_a[$i]['id_poliza']],
                    $_GET['f_pago_gc_p'.$distinct_a[$i]['id_poliza']],
                    $_GET['monto_p'.$distinct_a[$i]['id_poliza']]
                );
                
                $obj->agregarCargaPagoP($datos);
            }
        } else {
            $monto = ($distinct_a[$i]['prima']*$distinct_a[$i]['per_gc'])/100;
            $gc_h_p = $obj->agregarGChP($distinct_a[$i]['id_poliza'],$monto,null);

            if ($_GET['n_transf'.$distinct_a[$i]['id_poliza']] != '' || $_GET['n_banco'.$distinct_a[$i]['id_poliza']] != '') {
                $datos = array(
                    $gc_h_p['id_gc_h_p'],
                    $_GET['id_usuarioS'],
                    $_GET['n_transf'.$distinct_a[$i]['id_poliza']],
                    $_GET['n_banco'.$distinct_a[$i]['id_poliza']],
                    $_GET['f_pago_gc_p'.$distinct_a[$i]['id_poliza']],
                    $_GET['monto_p'.$distinct_a[$i]['id_poliza']]
                );
                
                $obj->agregarCargaPagoP($datos);
            }
        }
    }
    
    
}


header('Location: ../view/gc/b_gc_p.php?m=3');
