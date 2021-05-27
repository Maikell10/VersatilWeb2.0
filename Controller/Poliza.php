<?php

require_once dirname(__DIR__) . '../Model/Asesor.php';

$fhoy = date("Y");
$totalsuma = 0;
$totalprima = 0;
$totalpoliza = 0;
$totalComision = 0;

$totalprimaC = 0;
$totalcomisionC = 0;
$totalGC = 0;

$totalPrimaCom = 0;
$totalCom = 0;

$totalprimacomT = 0;
$totalcomisionT = 0;
$totalgcT = 0;

$cont = 0;
$contR = 0;
$contRV = 0;

$mes_arr = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

$mes_arr_num = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

$obj = new Asesor();

//--- b_poliza3.php
if ($pag == 'b_poliza3') {
    $id_cia = $_GET["id_cia"];

    $polizas = $obj->get_poliza_total_by_filtro_c($id_cia);
}

//--- b_poliza2.php
if ($pag == 'b_poliza2') {
    $asesor = $_GET["asesor"];

    $polizas = $obj->get_poliza_total_by_filtro_a($asesor);
}

//--- b_poliza1.php
if ($pag == 'b_poliza1') {
    $anio = (isset($_GET["anio"]) != null) ? $_GET["anio"] : '';

    $mes = (isset($_GET["mes"]) != null) ? $_GET["mes"] : '';

    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';

    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';

    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';

    $polizas = $obj->get_poliza_total_by_filtro($mes, $anio, $cia, $ramo, $asesor);

    $asesor_b = $obj->get_asesor_por_cod($asesor);

    $asesorArray = '';
    for ($i = 0; $i < sizeof($asesor_b); $i++) {
        $asesorArray .= utf8_encode($asesor_b[$i]['nombre']) . ", ";
    }
    $asesorArray;
    $myString = substr($asesorArray, 0, -2);

    $anio_para_enviar_via_url = serialize($anio);
    $anioEnv = urlencode($anio_para_enviar_via_url);
    $mes_para_enviar_via_url = serialize($mes);
    $mesEnv = urlencode($mes_para_enviar_via_url);
    $cia_para_enviar_via_url = serialize($cia);
    $ciaEnv = urlencode($cia_para_enviar_via_url);
    $ramo_para_enviar_via_url = serialize($ramo);
    $ramoEnv = urlencode($ramo_para_enviar_via_url);
    $asesor_para_enviar_via_url = serialize($asesor);
    $asesorEnv = urlencode($asesor_para_enviar_via_url);

}

//--- b_poliza.php
if ($pag == 'b_poliza') {
    $fecha_min = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
    $fecha_max = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');

    //FECHA MAYORES A 2024
    $dateString = $fecha_max[0]["MAX(f_desdepoliza)"];
    // Parse a textual date/datetime into a Unix timestamp
    $date = new DateTime($dateString);
    $format = 'Y';
    // Parse a textual date/datetime into a Unix timestamp
    $date = new DateTime($dateString);
    // Print it
    $fecha_max = $date->format($format);
    $fecha_min = date('Y', strtotime($fecha_min[0]["MIN(f_desdepoliza)"]));

    $asesor = $obj->get_ejecutivo();
    $cia = $obj->get_distinct_element('nomcia', 'dcia');
    $ramo = $obj->get_distinct_element('nramo', 'dramo');

    $polizas = $obj->getPolizas();
}

//--- v_poliza.php
if ($pag == 'v_poliza') {
    require_once '../Model/Asesor.php';
    $id_poliza = $_GET['id_poliza'];

    $obj = new Asesor();

    $poliza = $obj->get_poliza_total_by_id($id_poliza);
    $as = 1;
    if ($poliza[0]['id_poliza'] == 0) {
        $poliza = $obj->get_poliza_total1_by_id($id_poliza);
        $as = 2;
    }
    if ($poliza[0]['id_poliza'] == 0) {
        $poliza = $obj->get_poliza_total2_by_id($id_poliza);
        $as = 3;
    }

    $condicion = ($poliza[0]['id_poliza'] == 0) ? 1 : 2;

    if ($condicion == 1) {
        header("Location: b_poliza.php?m=1");
        exit();
    }

    $tomador = $obj->get_element_by_id('titular', 'id_titular', $poliza[0]['id_tomador']);

    $currency = ($poliza[0]['currency'] == 1) ? '$ ' : 'Bs ';

    $vehiculo = $obj->get_element_by_id('dveh', 'idveh', $poliza[0]['id_poliza']);

    $usuario = $obj->get_element_by_id('usuarios', 'id_usuario', $poliza[0]['id_usuario']);

    $newCreacion = date("d/m/Y", strtotime($poliza[0]['f_poliza']));

    $cia_pref = $obj->get_per_gc_cia_pref($poliza[0]['f_desdepoliza'], $poliza[0]['id_cia'], $poliza[0]['codvend']);

    $polizap = $obj->get_comision_rep_com_by_id($id_poliza);

    $no_renov = $obj->verRenov1($id_poliza);

    $no_renovar = $obj->get_element('no_renov', 'no_renov_n');

    $vRenov = $obj->verRenov($id_poliza);
}

//--- b_polizaT.php
if ($pag == 'b_polizaT') {
    $polizas = $obj->get_polizas_by_tarjetaV($_GET['id_tarjeta']);
}

//--- b_pendientes.php
if ($pag == 'b_pendientes') {
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizas = $obj->get_poliza_pendiente_asesor($user[0]['cod_vend']);
    } else {
        $polizas = $obj->get_poliza_pendiente();
    }
}

//--- b_f_product.php
if ($pag == 'b_f_product') {
    $fechaMin = $obj->get_fecha_min_max('MIN', 'f_poliza', 'poliza');
    $fechaMax = $obj->get_fecha_min_max('MAX', 'f_poliza', 'poliza');

    $fechaMin = date('Y', strtotime($fechaMin[0]["MIN(f_poliza)"]));
    $fechaMax = date('Y', strtotime($fechaMax[0]["MAX(f_poliza)"]));

    $asesor = $obj->get_ejecutivo();
    $cia = $obj->get_distinct_element('nomcia', 'dcia');
    $ramo = $obj->get_distinct_element('nramo', 'dramo');
}

//--- f_product.php
if ($pag == 'f_product') {
    $desde = $_POST['desdeP_submit'];
    $hasta = $_POST['hastaP_submit'];

    $desdeP = $_POST['desdeP'];
    $hastaP = $_POST['hastaP'];

    $polizas = $obj->get_poliza_total_by_filtro_f_product($desde, $hasta);
}

//--- f_emision.php
if ($pag == 'f_emision') {
    $desde = $_GET['desdeP_submit'];
    $hasta = $_GET['hastaP_submit'];

    $desdeP = $_GET['desdeP'];
    $hastaP = $_GET['hastaP'];

    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';
    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';

    $polizas = $obj->get_poliza_total_by_filtro_f_emision($desde, $hasta, $cia, $ramo, $asesor);
}

//--- f_nueva.php
if ($pag == 'f_nueva') {
    $desde = $_GET['desdeP_submit'];
    $hasta = $_GET['hastaP_submit'];

    $desdeP = date('d-m-Y', strtotime($_GET['desdeP_submit']));
    $hastaP = date('d-m-Y', strtotime($_GET['hastaP_submit']));

    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';
    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';

    $polizas = $obj->get_poliza_total_by_filtro_f_nueva_n($desde, $hasta, $cia, $ramo, $asesor);

    $polizasR = $obj->get_poliza_total_by_filtro_f_nueva_r($desde, $hasta, $cia, $ramo, $asesor);

    $polizasA = $obj->get_poliza_total_by_filtro_f_nueva_a($desde, $hasta, $cia, $ramo, $asesor);
}

//--- renov/b_renov.php
if ($pag == 'renov/b_renov') {
    $asesor = $obj->get_ejecutivo();
    $cia = $obj->get_distinct_element('nomcia', 'dcia');

    $fecha_min = $obj->get_fecha_min_max('MIN', 'f_hastapoliza', 'poliza');
    $fecha_max = $obj->get_fecha_min_max('MAX', 'f_hastapoliza', 'poliza');

    //FECHA MAYORES A 2024
    $dateString = $fecha_max[0]["MAX(f_hastapoliza)"];
    // Parse a textual date/datetime into a Unix timestamp
    $date = new DateTime($dateString);
    $format = 'Y';
    // Parse a textual date/datetime into a Unix timestamp
    $date = new DateTime($dateString);
    // Print it
    $fecha_max = $date->format($format);
    $fecha_min = date('Y', strtotime($fecha_min[0]["MIN(f_hastapoliza)"]));
}

//--- renov/renov_por_cia.php
if ($pag == 'renov/renov_por_cia') {
    $asesor = (isset($_POST["asesor"]) != null) ? $_POST["asesor"] : '';

    $mes = $_POST['mes'];
    $desde = $_POST['anio'] . "-" . $_POST['mes'] . "-01";
    $hasta = $_POST['anio'] . "-" . $_POST['mes'] . "-31";

    if ($mes == null) {
        $mesD = 01;
        $mesH = 12;
        $desde = $_POST['anio'] . "-" . $mesD . "-01";
        $hasta = $_POST['anio'] . "-" . $mesH . "-31";
    }

    $anio = $_POST['anio'];
    if ($anio == null) {
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_hastapoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_hastapoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_hastapoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_hastapoliza)'];
    }

    $distinct_c = $obj->get_poliza_total_by_filtro_renov_distinct_c($desde, $hasta, $asesor);
    if ($distinct_c == 0) {
        header("Location: b_renov_por_cia.php?m=2");
    }

    $asesor_b = $obj->get_asesor_por_cod($asesor);

    $asesorArray = '';
    for ($i = 0; $i < sizeof($asesor_b); $i++) {
        $asesorArray .= $asesor_b[$i]['nombre'] . ", ";
    }
    $asesorArray;
    $myString = substr($asesorArray, 0, -2);
}

//--- renov/renov_por_asesor.php
if ($pag == 'renov/renov_por_asesor') {
    $cia = (isset($_POST["cia"]) != null) ? $_POST["cia"] : '';

    $mes = $_POST['mes'];
    $desde = $_POST['anio'] . "-" . $_POST['mes'] . "-01";
    $hasta = $_POST['anio'] . "-" . $_POST['mes'] . "-31";

    if ($mes == null) {
        $mesD = 01;
        $mesH = 12;
        $desde = $_POST['anio'] . "-" . $mesD . "-01";
        $hasta = $_POST['anio'] . "-" . $mesH . "-31";
    }


    $anio = $_POST['anio'];
    if ($anio == null) {
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_hastapoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_hastapoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_hastapoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_hastapoliza)'];
    }

    $distinct_a = $obj->get_poliza_total_by_filtro_renov_distinct_a($desde, $hasta, $cia);
    if ($distinct_a == 0) {
        header("Location: b_renov_por_asesor.php?m=2");
    }
}

//--- renov/renov_g.php
if ($pag == 'renov/renov_g') {
    $asesor = (isset($_POST["asesor"]) != null) ? $_POST["asesor"] : '';
    $cia = (isset($_POST["cia"]) != null) ? $_POST["cia"] : '';

    $mes = $_POST['mes'];
    $desde = $_POST['anio'] . "-" . $_POST['mes'] . "-01";
    $hasta = $_POST['anio'] . "-" . $_POST['mes'] . "-31";

    $cont = 1;
    if ($mes == null) {
        $cont = 12;
        $mesD = 01;
        $mesH = 12;
        $desde = $_POST['anio'] . "-" . $mesD . "-01";
        $hasta = $_POST['anio'] . "-" . $mesH . "-31";
    }

    $anio = $_POST['anio'];
    if ($anio == null) {
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_hastapoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_hastapoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_hastapoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_hastapoliza)'];
    }

    $distinct_ac = $obj->get_poliza_total_by_filtro_renov_distinct_ac($desde, $hasta, $cia, $asesor);
    if ($distinct_ac == 0) {
        header("Location: b_renov_g.php?m=2");
    }

    $asesor_b = $obj->get_asesor_por_cod($asesor);

    $asesorArray = '';
    for ($i = 0; $i < sizeof($asesor_b); $i++) {
        $asesorArray .= $asesor_b[$i]['nombre'] . ", ";
    }
    $asesorArray;
    $myString = substr($asesorArray, 0, -2);

    $no_renov = $obj->get_element('no_renov', 'no_renov_n');
}

//--- b_reportes.php
if ($pag == 'b_reportes') {
    $reporte = $obj->get_rep_com();
    $cia = $obj->get_element('dcia', 'nomcia');

    $fechaMin = $obj->get_fecha_min_max('MIN', 'f_pago_gc', 'rep_com');
    $fechaMax = $obj->get_fecha_min_max('MAX', 'f_pago_gc', 'rep_com');

    $fecha_min = date('Y', strtotime($fechaMin[0]["MIN(f_pago_gc)"]));
    $fecha_max = date('Y', strtotime($fechaMax[0]["MAX(f_pago_gc)"]));
}

//--- b_reportes1.php
if ($pag == 'b_reportes1') {
    $mes = $_GET['mes'];
    $desde = $_GET['anio'] . "-" . $_GET['mes'] . "-01";
    $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-31";

    if ($mes == null) {
        $mesD = 01;
        $mesH = 12;
        $desde = $_GET['anio'] . "-" . $mesD . "-01";
        $hasta = $_GET['anio'] . "-" . $mesH . "-31";
    }

    $anio = $_GET['anio'];
    if ($anio == null) {
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_pago_gc', 'rep_com');
        $desde = $fechaMin[0]['MIN(f_pago_gc)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_pago_gc', 'rep_com');
        $hasta = $fechaMax[0]['MAX(f_pago_gc)'];
    }

    $cia = (isset($_GET['cia'])) ? $_GET['cia'] : $_GET['cia'];
    if ($cia == 'Seleccione Cía') {
        $cia = 0;
    }

    $rep_com_busq = $obj->get_rep_comision_por_busqueda($desde, $hasta, $cia);
}

//--- v_reporte_com.php
if ($pag == 'v_reporte_com') {
    $id_rep_com = $_GET['id_rep_com'];

    $rep_com = $obj->get_element_by_id('rep_com', 'id_rep_com', $id_rep_com);

    $cia = $obj->get_element_by_id('dcia', 'idcia', $rep_com[0]['id_cia']);

    $comision = $obj->get_comisiones($_GET['id_rep_com']);

    $f_pago_gc = date("d-m-Y", strtotime($rep_com[0]['f_pago_gc']));
    $f_hasta_rep = date("d-m-Y", strtotime($rep_com[0]['f_hasta_rep']));

    $conciliacion = $obj->get_element_by_id('conciliacion', 'id_rep_com', $_GET['id_rep_com']);
}

//--- add/crear_poliza.php
if ($pag == 'add/crear_poliza') {
    $usuario = $obj->get_element_by_id('usuarios', 'seudonimo', $_SESSION['seudonimo']);
    $ramo = $obj->get_element('dramo', 'cod_ramo');
    $cia = $obj->get_element('dcia', 'nomcia');
    $asesor = $obj->get_ejecutivo();
    
    if ($usuario[0]['cod_vend'] != '') {
        $asesor = $obj->get_ejecutivo_by_cod($usuario[0]['cod_vend']);
    }
}

//--- add/poliza_n.php
if ($pag == 'add/poliza_n') {
    //----Obtengo el permiso del usuario
    $permiso = $_SESSION['id_permiso'];
    //----------------------

    $n_poliza = $_GET['n_poliza'];
    $fhoy = $_GET['fhoy'];
    $femisionP = $_GET['fhoy'];
    $t_cobertura = $_GET['t_cobertura'];
    $fdesdeP = $_GET['desdeP'];
    $fhastaP = $_GET['hastaP'];
    $fdesdeP = date("Y-m-d", strtotime($fdesdeP));
    $fhastaP = date("Y-m-d", strtotime($fhastaP));
    $currency = $_GET['currency'];
    $tipo_poliza = $_GET['tipo_poliza'];
    $sumaA = $_GET['sumaA'];
    $z_produc = ($_GET['z_produc'] == "PANAMA") ? 1 : 2;
    $codasesor = $_GET['asesor'];
    $ramo = $_GET['ramo'];
    $cia = $_GET['cia'];
    $titular = $_GET['titular'];
    $tomador = $_GET['tomador'];
    $t_cuenta = $_GET['t_cuenta'];
    $asesor_ind = $_GET['asesor_ind'];
    if ($asesor_ind == null) {
        $asesor_ind = 0;
    }
    $frec_renov = $_GET['frec_renov'];

    $n_recibo = $_GET['n_recibo'];
    $fdesde_recibo = $_GET['desde_recibo'];
    $fhasta_recibo = $_GET['hasta_recibo'];
    $fdesde_recibo = date("Y-m-d", strtotime($fdesde_recibo));
    $fhasta_recibo = date("Y-m-d", strtotime($fhasta_recibo));
    $prima = $_GET['prima'];
    $f_pago = $_GET['f_pago'];

    $n_cuotas = $_GET['n_cuotas'];
    $monto_cuotas = $_GET['monto_cuotas'];

    $tomador = $_GET['tomador'];
    $titular = $_GET['titular'];

    $idtomador = $obj->get_id_cliente($tomador);

    $idtitular = $obj->get_id_cliente($titular);

    $placa = $_GET['placa'];
    $tipo = $_GET['tipo'];
    $marca = $_GET['marca'];
    $modelo = $_GET['modelo'];
    $anio = $_GET['anio'];
    $color = $_GET['color'];
    $serial = $_GET['serial'];
    $categoria = $_GET['categoria'];

    if ($placa == null) {
        $placa = '-';
        $tipo = '-';
        $marca = '-';
        $modelo = '-';
        $anio = '-';
        $color = '-';
        $serial = '-';
        $categoria = '-';
    }

    $obs = $_GET['obs'];

    $forma_pago = $_GET['forma_pago'];
    $n_tarjeta = $_GET['n_tarjeta'];
    $cvv = $_GET['cvv'];
    $fechaV = $_GET['fechaV'];
    $fechaVP = date("Y-m-d", strtotime($fechaV));
    $titular_tarjeta = $_GET['titular_tarjeta'];
    $bancoT = $_GET['bancoT'];
    $id_tarjeta = $_GET['id_tarjeta'];

    if ($forma_pago == 2) {
        if ($_GET['alert'] == 1 || $_GET['condTar'] == 1) {

            $tarjeta_existente = $obj->obetenTarjetaExistente($n_tarjeta, $cvv, $fechaVP, $titular_tarjeta, $bancoT);

            if($tarjeta_existente == 0) {
                $tarjeta = $obj->agregarTarjeta($n_tarjeta, $cvv, $fechaVP, $titular_tarjeta, $bancoT);

                if($tarjeta !== false) {
                    $id_tarjeta = $obj->get_last_element('tarjeta', 'id_tarjeta');
    
                    $id_tarjeta = $id_tarjeta[0]['id_tarjeta'];
                } else {
                    echo "<script type=\"text/javascript\">
                            alert('Ocurrió un error en la carga de la Tarjeta. Actualiza el navegador e intenta de nuevo');
                            window.history.back();
                        </script>";
                    exit;
                }
            }
        }
    }
    
    $poliza = $obj->agregarPoliza($n_poliza, $fhoy, $femisionP, $t_cobertura, $fdesdeP, $fhastaP, $currency, $tipo_poliza, $sumaA, $z_produc, $codasesor, $ramo, $cia, $idtitular[0]['id_titular'], $idtomador[0]['id_titular'], $asesor_ind, $t_cuenta, $_SESSION['id_usuario'], $obs, $prima, $frec_renov);

    if($poliza !== false) {
        $recibo = $obj->agregarRecibo($n_recibo, $fdesde_recibo, $fhasta_recibo, $prima, $f_pago, $n_cuotas, $monto_cuotas, $idtomador[0]['id_titular'], $idtitular[0]['id_titular'], $n_poliza, $forma_pago, $id_tarjeta);

        $vehiculo = $obj->agregarVehiculo($placa, $tipo, $marca, $modelo, $anio, $serial, $color, $categoria, $n_recibo);

        $tipo_poliza_print = ($tipo_poliza == 1) ? "Primer Año" : "";

        $ultima_poliza = $obj->get_last_element('poliza', 'id_poliza');

        $u_p = $ultima_poliza[0]['id_poliza'];
    } else {
        echo "<script type=\"text/javascript\">
                alert('Ocurrió un error en el servidor. Actualiza el navegador e intenta de nuevo');
                window.history.back();
            </script>";
        exit;
    }
}

//--- add/poliza.php
if ($pag == 'add/poliza') {
    //----Obtengo el permiso del usuario
    $permiso = $_SESSION['id_permiso'];
    //----------------------

    $n_poliza = $_POST['n_poliza'];
    $fhoy = date("Y-m-d");
    //$femisionP=$_POST['emisionP'];
    $femisionP = date("Y-m-d");
    $t_cobertura = $_POST['t_cobertura'];
    $fdesdeP = $_POST['desdeP'];
    $fhastaP = $_POST['hastaP'];
    $currency = $_POST['currency'];
    $tipo_poliza = $_POST['tipo_poliza'];
    $sumaA = $_POST['sumaA'];
    $z_produc = $_POST['z_produc'];
    $codasesor = $_POST['asesor'];
    $u = explode('=', $codasesor);
    $ramo = $_POST['ramo'];
    $cia = $_POST['cia'];
    $titular = $_POST['titular'];
    $n_recibo = $_POST['n_recibo'];
    $fdesde_recibo = $_POST['desde_recibo'];
    $fhasta_recibo = $_POST['hasta_recibo'];
    $prima = $_POST['prima'];
    $f_pago = $_POST['f_pago'];

    $frec_renov = $_POST['frec_renov'];
    if($frec_renov == 1) {
        // Anual
        $frec_renov_w = 'Anual';
    }
    if($frec_renov == 2) {
        // Mensual
        $frec_renov_w = 'Mensual';
    }
    if($frec_renov == 3) {
        // Trimestral
        $frec_renov_w = 'Trimestral';
    }
    if($frec_renov == 4) {
        // Semestral
        $frec_renov_w = 'Semestral';
    }

    $forma_pago = 'PAGO VOLUNTARIO';
    if ($_POST['forma_pago'] == 1) {
        $forma_pago = 'ACH (CARGO EN CUENTA)';
    }
    if ($_POST['forma_pago'] == 2) {
        $forma_pago = 'TARJETA DE CREDITO / DEBITO';
    }
    $n_tarjeta = ($_POST['n_tarjeta'] == null) ? 'N/A' : $_POST['n_tarjeta'];
    $cvv = ($_POST['cvv'] == null) ? 'N/A' : $_POST['cvv'];
    $fechaV = ($_POST['fechaV'] == null) ? 'N/A' : $_POST['fechaV'];
    $titular_tarjeta = ($_POST['titular_tarjeta'] == null) ? 'N/A' : $_POST['titular_tarjeta'];
    $bancoT = ($_POST['bancoT'] == null) ? 'N/A' : $_POST['bancoT'];
    $alert = $_POST['alert'];
    $id_tarjeta = $_POST['id_tarjeta'];

    $condTar = 0;
    if ($cvv != $_POST['cvv_h'] || $fechaV != $_POST['fechaV_h'] || $titular_tarjeta != $_POST['titular_tarjeta_h'] || $bancoT != $_POST['bancoT_h']) {
        $condTar = 1;
    }


    $obs = $_POST['obs'];
    $n_cuotas = $_POST['n_cuotas'];

    if ($f_pago == 1) {
        $n_cuotas = 1;
        $monto_cuotas = $prima;
    } else {
        $monto_cuotas = $prima / $n_cuotas;
    }

    $tomador = $_POST['tomador'];
    $titular = $_POST['titular'];

    $idtomador = $obj->get_id_cliente($tomador);

    $idtitular = $obj->get_id_cliente($titular);

    $tipo_poliza_print = "";
    if ($tipo_poliza == 1) {
        $tipo_poliza_print = "Primer Año";
    }
    if ($tipo_poliza == 2) {
        $tipo_poliza_print = "Renovación";
    }
    if ($tipo_poliza == 3) {
        $tipo_poliza_print = "Traspaso de Cartera";
    }
    if ($tipo_poliza == 4) {
        $tipo_poliza_print = "Anexos";
    }
    if ($tipo_poliza == 5) {
        $tipo_poliza_print = "Revalorización";
    }

    $nombre_ramo = $obj->get_element_by_id('dramo', 'cod_ramo', $ramo);
    $nombre_cia = $obj->get_element_by_id('dcia', 'idcia', $cia);

    if ($f_pago == 1) {
        $f_pago = 'CONTADO';
    }
    if ($f_pago == 2) {
        $f_pago = 'FRACCIONADO';
    }
    if ($f_pago == 3) {
        $f_pago = 'FINANCIADO';
    }

    $currencyl = ($currency == 1) ? "$ " : "Bs ";
    $t_cuenta = ($_POST['t_cuenta'] == 1) ? "Individual" : "Colectivo";

    if ($sumaA == "") {
        $sumaA = 0;
    }

    if ($nombre_cia[0]['preferencial'] == 1) {
    }


    $asesor_ind = $obj->get_element_by_id('ena', 'cod', $u[0]);
    $as = 0;
    $per_gc = ($ramo == 35) ? $asesor_ind[0]['gc_viajes'] : $asesor_ind[0]['nopre1'];

    if ($asesor_ind[0]['nopre1'] == null) {
        //buscar en referidor";
        $asesor_ind_r = $obj->get_element_by_id('enr', 'cod', $u[0]);
        $per_gc = $asesor_ind_r[0]['monto'];
        $as = 1;
        if ($asesor_ind_r[0]['currency'] == '$') {
            $tipo_r = 1;
        }
        if ($asesor_ind_r[0]['currency'] == '%') {
            $tipo_r = 2;
        }
    }
    if ($asesor_ind[0]['nopre1'] == null && $asesor_ind_r[0]['monto'] == null) {
        //buscar en proyecto";
        $asesor_ind_p = $obj->get_element_by_id('enp', 'cod', $u[0]);
        $per_gc = $asesor_ind_p[0]['monto'];
        $as = 2;
        if ($asesor_ind_p[0]['currency'] == '$') {
            $tipo_r = 1;
        }
        if ($asesor_ind_p[0]['currency'] == '%') {
            $tipo_r = 2;
        }
    }

    $placa = $_POST['placa'];
    $tipo = $_POST['tipo'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $anio = $_POST['anio'];
    $color = '-';
    $serial = '-';
    $categoria = '-';

    $fdesdeCP = date("Y-m-d", strtotime($fdesdeP));
    $cia_pref = $obj->get_per_gc_cia_pref($fdesdeCP, $cia, $u[0]);
    if ($cia_pref[0]['per_gc_sum'] != null && $ramo != 35) {
        $per_gc = $per_gc + $cia_pref[0]['per_gc_sum'];
    }
}

//--- prima_detail.php
/*if ($pag == 'prima_detail') {
    $anio = (isset($_POST["anio"]) != null) ? $_POST["anio"] : '';
    $mes = (isset($_POST["mes"]) != null) ? $_POST["mes"] : '';
    $fpago = (isset($_POST["fpago"]) != null) ? $_POST["fpago"] : '';
    $cia = (isset($_POST["cia"]) != null) ? $_POST["cia"] : '';
    $asesor = (isset($_POST["asesor"]) != null) ? $_POST["asesor"] : '';

    if ($mes == null) {
        $fechaMax = $obj->get_fecha_max_prima_d($anio, $fpago, $cia, $asesor);
        $fechaMax = date('m', strtotime($fechaMax[0]["MAX(f_desdepoliza)"]));
        $estado = 0;

        for ($i = 0; $i < $fechaMax; $i++) {
            $polizas = $obj->get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $asesor, $i + 1);

            for ($a = 0; $a < sizeof($polizas); $a++) {

                $p_ene1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '01');
                $p_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ene1[0]['SUM(prima_com)'];
                $a_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_ene1[0]['YEAR(f_pago_prima)'];
                $p_feb1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '02');
                $p_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? 0 : $p_feb1[0]['SUM(prima_com)'];
                $a_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_feb1[0]['YEAR(f_pago_prima)'];
                $p_mar1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '03');
                $p_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? 0 : $p_mar1[0]['SUM(prima_com)'];
                $a_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_mar1[0]['YEAR(f_pago_prima)'];
                $p_abr1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '04');
                $p_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? 0 : $p_abr1[0]['SUM(prima_com)'];
                $a_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_abr1[0]['YEAR(f_pago_prima)'];
                $p_may1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '05');
                $p_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? 0 : $p_may1[0]['SUM(prima_com)'];
                $a_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_may1[0]['YEAR(f_pago_prima)'];
                $p_jun1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '06');
                $p_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jun1[0]['SUM(prima_com)'];
                $a_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_jun1[0]['YEAR(f_pago_prima)'];
                $p_jul1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '07');
                $p_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jul1[0]['SUM(prima_com)'];
                $a_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_jul1[0]['YEAR(f_pago_prima)'];
                $p_ago1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '08');
                $p_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ago1[0]['SUM(prima_com)'];
                $a_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_ago1[0]['YEAR(f_pago_prima)'];
                $p_sep1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '09');
                $p_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? 0 : $p_sep1[0]['SUM(prima_com)'];
                $a_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_sep1[0]['YEAR(f_pago_prima)'];
                $p_oct1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '10');
                $p_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? 0 : $p_oct1[0]['SUM(prima_com)'];
                $a_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_oct1[0]['YEAR(f_pago_prima)'];
                $p_nov1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '11');
                $p_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? 0 : $p_nov1[0]['SUM(prima_com)'];
                $a_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_nov1[0]['YEAR(f_pago_prima)'];
                $p_dic1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '12');
                $p_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? 0 : $p_dic1[0]['SUM(prima_com)'];
                $a_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_dic1[0]['YEAR(f_pago_prima)'];


                $p_enero[] = $p_ene;
                $a_enero[] = $a_ene;
                $p_febrero[] = $p_feb;
                $a_febrero[] = $a_feb;
                $p_marzo[] = $p_mar;
                $a_marzo[] = $a_mar;
                $p_abril[] = $p_abr;
                $a_abril[] = $a_abr;
                $p_mayo[] = $p_may;
                $a_mayo[] = $a_may;
                $p_junio[] = $p_jun;
                $a_junio[] = $a_jun;
                $p_julio[] = $p_jul;
                $a_julio[] = $a_jul;
                $p_agosto[] = $p_ago;
                $a_agosto[] = $a_ago;
                $p_septiempre[] = $p_sep;
                $a_septiempre[] = $a_sep;
                $p_octubre[] = $p_oct;
                $a_octubre[] = $a_oct;
                $p_noviembre[] = $p_nov;
                $a_noviembre[] = $a_nov;
                $p_diciembre[] = $p_dic;
                $a_diciembre[] = $a_dic;

                $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

                $totalprima = $totalprima + $polizas[$a]['prima'];

                $cod_poliza[] = $polizas[$a]['cod_poliza'];
                $ciente[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
                $newDesde[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
                $nomcia[] = $polizas[$a]['nomcia'];
                $nramo[] = $polizas[$a]['nramo'];
                $prima_s[] = $polizas[$a]['prima'];
                $p_tt[] = $p_t;
                $p_dif[] = ($polizas[$a]['prima'] - $p_t);

                $f_hasta_poliza[] = $polizas[$a]['f_hastapoliza'];

                $idpoliza[] = $polizas[$a]['id_poliza'];

                $tool[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas'];
            }

            arsort($p_dif, SORT_NUMERIC);

            foreach ($p_dif as $key => $value) {
                $cod_poliza1[] = $cod_poliza[$key];
                $ciente1[] = $ciente[$key];
                $newDesde1[] = $newDesde[$key];
                $nomcia1[] = $nomcia[$key];
                $nramo1[] = $nramo[$key];
                $prima_s1[] = $prima_s[$key];
                $p_tt1[] = $p_tt[$key];
                $tool1[] = $tool[$key];
                $p_dif1[] = $value;

                $p_enero1[] = $p_enero[$key];
                $a_enero1[] = $a_enero[$key];
                $p_febrero1[] = $p_febrero[$key];
                $a_febrero1[] = $a_febrero[$key];
                $p_marzo1[] = $p_marzo[$key];
                $a_marzo1[] = $a_marzo[$key];
                $p_abril1[] = $p_abril[$key];
                $a_abril1[] = $a_abril[$key];
                $p_mayo1[] = $p_mayo[$key];
                $a_mayo1[] = $a_mayo[$key];
                $p_junio1[] = $p_junio[$key];
                $a_junio1[] = $a_junio[$key];
                $p_julio1[] = $p_julio[$key];
                $a_julio1[] = $a_julio[$key];
                $p_agosto1[] = $p_agosto[$key];
                $a_agosto1[] = $a_agosto[$key];
                $p_septiempre1[] = $p_septiempre[$key];
                $a_septiempre1[] = $a_septiempre[$key];
                $p_octubre1[] = $p_octubre[$key];
                $a_octubre1[] = $a_octubre[$key];
                $p_noviembre1[] = $p_noviembre[$key];
                $a_noviembre1[] = $a_noviembre[$key];
                $p_diciembre1[] = $p_diciembre[$key];
                $a_diciembre1[] = $a_diciembre[$key];

                $f_hasta_poliza1[] = $f_hasta_poliza[$key];
                $idpoliza1[] = $idpoliza[$key];
            }
            unset($p_dif, $nomcia, $cod_poliza, $ciente, $newDesde, $nramo, $prima_s, $p_tt, $tool, $p_enero, $p_febrero, $p_marzo, $p_abril, $p_mayo, $p_junio, $p_julio, $p_agosto, $p_septiempre, $p_octubre, $p_noviembre, $p_diciembre, $f_hasta_poliza, $idpoliza, $a_enero, $a_febrero, $a_marzo, $a_abril, $a_mayo, $a_junio, $a_julio, $a_agosto, $a_septiempre, $a_octubre, $a_noviembre, $a_diciembre);
        }
    } else {
        $fechaMax = $mes;
        $estado = 1;

        $polizas = $obj->get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $asesor, $fechaMax);
        for ($a = 0; $a < sizeof($polizas); $a++) {
            $p_ene1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '01');
            $p_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ene1[0]['SUM(prima_com)'];
            $a_ene = ($p_ene1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_ene1[0]['YEAR(f_pago_prima)'];
            $p_feb1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '02');
            $p_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? 0 : $p_feb1[0]['SUM(prima_com)'];
            $a_feb = ($p_feb1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_feb1[0]['YEAR(f_pago_prima)'];
            $p_mar1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '03');
            $p_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? 0 : $p_mar1[0]['SUM(prima_com)'];
            $a_mar = ($p_mar1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_mar1[0]['YEAR(f_pago_prima)'];
            $p_abr1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '04');
            $p_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? 0 : $p_abr1[0]['SUM(prima_com)'];
            $a_abr = ($p_abr1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_abr1[0]['YEAR(f_pago_prima)'];
            $p_may1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '05');
            $p_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? 0 : $p_may1[0]['SUM(prima_com)'];
            $a_may = ($p_may1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_may1[0]['YEAR(f_pago_prima)'];
            $p_jun1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '06');
            $p_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jun1[0]['SUM(prima_com)'];
            $a_jun = ($p_jun1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_jun1[0]['YEAR(f_pago_prima)'];
            $p_jul1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '07');
            $p_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? 0 : $p_jul1[0]['SUM(prima_com)'];
            $a_jul = ($p_jul1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_jul1[0]['YEAR(f_pago_prima)'];
            $p_ago1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '08');
            $p_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? 0 : $p_ago1[0]['SUM(prima_com)'];
            $a_ago = ($p_ago1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_ago1[0]['YEAR(f_pago_prima)'];
            $p_sep1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '09');
            $p_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? 0 : $p_sep1[0]['SUM(prima_com)'];
            $a_sep = ($p_sep1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_sep1[0]['YEAR(f_pago_prima)'];
            $p_oct1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '10');
            $p_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? 0 : $p_oct1[0]['SUM(prima_com)'];
            $a_oct = ($p_oct1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_oct1[0]['YEAR(f_pago_prima)'];
            $p_nov1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '11');
            $p_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? 0 : $p_nov1[0]['SUM(prima_com)'];
            $a_nov = ($p_nov1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_nov1[0]['YEAR(f_pago_prima)'];
            $p_dic1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '12');
            $p_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? 0 : $p_dic1[0]['SUM(prima_com)'];
            $a_dic = ($p_dic1[0]['SUM(prima_com)'] == 0) ? $_POST["anio"] : $p_dic1[0]['YEAR(f_pago_prima)'];


            $p_enero[] = $p_ene;
            $a_enero[] = $a_ene;
            $p_febrero[] = $p_feb;
            $a_febrero[] = $a_feb;
            $p_marzo[] = $p_mar;
            $a_marzo[] = $a_mar;
            $p_abril[] = $p_abr;
            $a_abril[] = $a_abr;
            $p_mayo[] = $p_may;
            $a_mayo[] = $a_may;
            $p_junio[] = $p_jun;
            $a_junio[] = $a_jun;
            $p_julio[] = $p_jul;
            $a_julio[] = $a_jul;
            $p_agosto[] = $p_ago;
            $a_agosto[] = $a_ago;
            $p_septiempre[] = $p_sep;
            $a_septiempre[] = $a_sep;
            $p_octubre[] = $p_oct;
            $a_octubre[] = $a_oct;
            $p_noviembre[] = $p_nov;
            $a_noviembre[] = $a_nov;
            $p_diciembre[] = $p_dic;
            $a_diciembre[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

            $totalprima = $totalprima + $polizas[$a]['prima'];

            $cod_poliza[] = $polizas[$a]['cod_poliza'];
            $ciente[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesde[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomcia[] = $polizas[$a]['nomcia'];
            $nramo[] = $polizas[$a]['nramo'];
            $prima_s[] = $polizas[$a]['prima'];
            $p_tt[] = $p_t;
            $p_dif[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_poliza[] = $polizas[$a]['f_hastapoliza'];

            $idpoliza[] = $polizas[$a]['id_poliza'];

            $tool[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas'];
        }
        arsort($p_dif, SORT_NUMERIC);

        foreach ($p_dif as $key => $value) {
            $cod_poliza1[] = $cod_poliza[$key];
            $ciente1[] = $ciente[$key];
            $newDesde1[] = $newDesde[$key];
            $nomcia1[] = $nomcia[$key];
            $nramo1[] = $nramo[$key];
            $prima_s1[] = $prima_s[$key];
            $p_tt1[] = $p_tt[$key];
            $tool1[] = $tool[$key];
            $p_dif1[] = $value;

            $p_enero1[] = $p_enero[$key];
            $a_enero1[] = $a_enero[$key];
            $p_febrero1[] = $p_febrero[$key];
            $a_febrero1[] = $a_febrero[$key];
            $p_marzo1[] = $p_marzo[$key];
            $a_marzo1[] = $a_marzo[$key];
            $p_abril1[] = $p_abril[$key];
            $a_abril1[] = $a_abril[$key];
            $p_mayo1[] = $p_mayo[$key];
            $a_mayo1[] = $a_mayo[$key];
            $p_junio1[] = $p_junio[$key];
            $a_junio1[] = $a_junio[$key];
            $p_julio1[] = $p_julio[$key];
            $a_julio1[] = $a_julio[$key];
            $p_agosto1[] = $p_agosto[$key];
            $a_agosto1[] = $a_agosto[$key];
            $p_septiempre1[] = $p_septiempre[$key];
            $a_septiempre1[] = $a_septiempre[$key];
            $p_octubre1[] = $p_octubre[$key];
            $a_octubre1[] = $a_octubre[$key];
            $p_noviembre1[] = $p_noviembre[$key];
            $a_noviembre1[] = $a_noviembre[$key];
            $p_diciembre1[] = $p_diciembre[$key];
            $a_diciembre1[] = $a_diciembre[$key];

            $f_hasta_poliza1[] = $f_hasta_poliza[$key];
            $idpoliza1[] = $idpoliza[$key];
        }
        unset($p_dif, $nomcia, $cod_poliza, $ciente, $newDesde, $nramo, $prima_s, $p_tt, $tool, $p_enero, $p_febrero, $p_marzo, $p_abril, $p_mayo, $p_junio, $p_julio, $p_agosto, $p_septiempre, $p_octubre, $p_noviembre, $p_diciembre, $f_hasta_poliza, $idpoliza, $a_enero, $a_febrero, $a_marzo, $a_abril, $a_mayo, $a_junio, $a_julio, $a_agosto, $a_septiempre, $a_octubre, $a_noviembre, $a_diciembre);
    }
}*/

//--- prima_detail1.php
if ($pag == 'prima_detail1') {

    $desde = $_GET['desdeP_submit'];
    $hasta = $_GET['hastaP_submit'];

    $desdeP = $_GET['desdeP'];
    $hastaP = $_GET['hastaP'];

    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';
    if ($ramo != '') {
        $ramoIn = "('" . implode("','", $ramo) . "')";
        $ramo = " AND dramo.nramo IN " .  $ramoIn;
    }

    $fpago = (isset($_GET["fpago"]) != null) ? $_GET["fpago"] : '';
    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';

    $polizas = $obj->get_poliza_total_by_filtro_detalle_p($desde, $hasta, $ramo, $fpago, $cia, $asesor);
    $cantPolizas = ($polizas == 0) ? 0 : sizeof($polizas) ;

    for ($a = 0; $a < $cantPolizas; $a++) {

        $p_ene1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '01');
        $p_ene = 0;
        $a_ene = 0;
        if ($p_ene1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_ene1 as $p_ene2) {
                $p_ene = $p_ene + $p_ene2['SUM(prima_com)'];
                $a_ene = $p_ene2['YEAR(f_pago_prima)'];
            }
        }
        $p_feb1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '02');
        $p_feb = 0;
        $a_feb = 0;
        if ($p_feb1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_feb1 as $p_feb2) {
                $p_feb = $p_feb + $p_feb2['SUM(prima_com)'];
                $a_feb = $p_feb2['YEAR(f_pago_prima)'];
            }
        }
        $p_mar1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '03');
        $p_mar = 0;
        $a_mar = 0;
        if ($p_mar1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_mar1 as $p_mar2) {
                $p_mar = $p_mar + $p_mar2['SUM(prima_com)'];
                $a_mar = $p_mar2['YEAR(f_pago_prima)'];
            }
        }
        $p_abr1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '04');
        $p_abr = 0;
        $a_abr = 0;
        if ($p_abr1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_abr1 as $p_abr2) {
                $p_abr = $p_abr + $p_abr2['SUM(prima_com)'];
                $a_abr = $p_abr2['YEAR(f_pago_prima)'];
            }
        }
        $p_may1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '05');
        $p_may = 0;
        $a_may = 0;
        if ($p_may1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_may1 as $p_may2) {
                $p_may = $p_may + $p_may2['SUM(prima_com)'];
                $a_may = $p_may2['YEAR(f_pago_prima)'];
            }
        }
        $p_jun1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '06');
        $p_jun = 0;
        $a_jun = 0;
        if ($p_jun1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_jun1 as $p_jun2) {
                $p_jun = $p_jun + $p_jun2['SUM(prima_com)'];
                $a_jun = $p_jun2['YEAR(f_pago_prima)'];
            }
        }
        $p_jul1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '07');
        $p_jul = 0;
        $a_jul = 0;
        if ($p_jul1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_jul1 as $p_jul2) {
                $p_jul = $p_jul + $p_jul2['SUM(prima_com)'];
                $a_jul = $p_jul2['YEAR(f_pago_prima)'];
            }
        }
        $p_ago1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '08');
        $p_ago = 0;
        $a_ago = 0;
        if ($p_ago1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_ago1 as $p_ago2) {
                $p_ago = $p_ago + $p_ago2['SUM(prima_com)'];
                $a_ago = $p_ago2['YEAR(f_pago_prima)'];
            }
        }
        $p_sep1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '09');
        $p_sep = 0;
        $a_sep = 0;
        if ($p_sep1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_sep1 as $p_sep2) {
                $p_sep = $p_sep + $p_sep2['SUM(prima_com)'];
                $a_sep = $p_sep2['YEAR(f_pago_prima)'];
            }
        }
        $p_oct1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '10');
        $p_oct = 0;
        $a_oct = 0;
        if ($p_oct1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_oct1 as $p_oct2) {
                $p_oct = $p_oct + $p_oct2['SUM(prima_com)'];
                $a_oct = $p_oct2['YEAR(f_pago_prima)'];
            }
        }
        $p_nov1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '11');
        $p_nov = 0;
        $a_nov = 0;
        if ($p_nov1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_nov1 as $p_nov2) {
                $p_nov = $p_nov + $p_nov2['SUM(prima_com)'];
                $a_nov = $p_nov2['YEAR(f_pago_prima)'];
            }
        }
        $p_dic1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '12');
        $p_dic = 0;
        $a_dic = 0;
        if ($p_dic1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_dic1 as $p_dic2) {
                $p_dic = $p_dic + $p_dic2['SUM(prima_com)'];
                $a_dic = $p_dic2['YEAR(f_pago_prima)'];
            }
        }

        $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
        $p_difC = ($polizas[$a]['prima'] - $p_t);

        if ($p_t == 0) {
            $p_enero[] = $p_ene;
            $a_enero[] = $a_ene;
            $p_febrero[] = $p_feb;
            $a_febrero[] = $a_feb;
            $p_marzo[] = $p_mar;
            $a_marzo[] = $a_mar;
            $p_abril[] = $p_abr;
            $a_abril[] = $a_abr;
            $p_mayo[] = $p_may;
            $a_mayo[] = $a_may;
            $p_junio[] = $p_jun;
            $a_junio[] = $a_jun;
            $p_julio[] = $p_jul;
            $a_julio[] = $a_jul;
            $p_agosto[] = $p_ago;
            $a_agosto[] = $a_ago;
            $p_septiempre[] = $p_sep;
            $a_septiempre[] = $a_sep;
            $p_octubre[] = $p_oct;
            $a_octubre[] = $a_oct;
            $p_noviembre[] = $p_nov;
            $a_noviembre[] = $a_nov;
            $p_diciembre[] = $p_dic;
            $a_diciembre[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivo[] = $ejecutivo_data[0]['nombre'];

            $cod_poliza[] = $polizas[$a]['cod_poliza'];
            $ciente[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesde[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomcia[] = $polizas[$a]['nomcia'];
            $nramo[] = $polizas[$a]['nramo'];
            $prima_s[] = $polizas[$a]['prima'];
            $p_tt[] = $p_t;
            $p_dif[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_poliza[] = $polizas[$a]['f_hastapoliza'];

            $idpoliza[] = $polizas[$a]['id_poliza'];

            $tool[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas'] . ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }

        if ($p_t != 0 && $p_difC > 0) {
            $p_eneroa[] = $p_ene;
            $a_eneroa[] = $a_ene;
            $p_febreroa[] = $p_feb;
            $a_febreroa[] = $a_feb;
            $p_marzoa[] = $p_mar;
            $a_marzoa[] = $a_mar;
            $p_abrila[] = $p_abr;
            $a_abrila[] = $a_abr;
            $p_mayoa[] = $p_may;
            $a_mayoa[] = $a_may;
            $p_junioa[] = $p_jun;
            $a_junioa[] = $a_jun;
            $p_julioa[] = $p_jul;
            $a_julioa[] = $a_jul;
            $p_agostoa[] = $p_ago;
            $a_agostoa[] = $a_ago;
            $p_septiemprea[] = $p_sep;
            $a_septiemprea[] = $a_sep;
            $p_octubrea[] = $p_oct;
            $a_octubrea[] = $a_oct;
            $p_noviembrea[] = $p_nov;
            $a_noviembrea[] = $a_nov;
            $p_diciembrea[] = $p_dic;
            $a_diciembrea[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivo[] = $ejecutivo_data[0]['nombre'];

            $cod_polizaa[] = $polizas[$a]['cod_poliza'];
            $cientea[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesdea[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomciaa[] = $polizas[$a]['nomcia'];
            $nramoa[] = $polizas[$a]['nramo'];
            $prima_sa[] = $polizas[$a]['prima'];
            $p_tta[] = $p_t;
            $p_difa[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_polizaa[] = $polizas[$a]['f_hastapoliza'];
            $idpolizaa[] = $polizas[$a]['id_poliza'];

            $toola[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas']. ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }

        if ($p_t != 0 && $p_difC == 0) {
            $p_enerob[] = $p_ene;
            $a_enerob[] = $a_ene;
            $p_febrerob[] = $p_feb;
            $a_febrerob[] = $a_feb;
            $p_marzob[] = $p_mar;
            $a_marzob[] = $a_mar;
            $p_abrilb[] = $p_abr;
            $a_abrilb[] = $a_abr;
            $p_mayob[] = $p_may;
            $a_mayob[] = $a_may;
            $p_juniob[] = $p_jun;
            $a_juniob[] = $a_jun;
            $p_juliob[] = $p_jul;
            $a_juliob[] = $a_jul;
            $p_agostob[] = $p_ago;
            $a_agostob[] = $a_ago;
            $p_septiempreb[] = $p_sep;
            $a_septiempreb[] = $a_sep;
            $p_octubreb[] = $p_oct;
            $a_octubreb[] = $a_oct;
            $p_noviembreb[] = $p_nov;
            $a_noviembreb[] = $a_nov;
            $p_diciembreb[] = $p_dic;
            $a_diciembreb[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivo[] = $ejecutivo_data[0]['nombre'];

            $cod_polizab[] = $polizas[$a]['cod_poliza'];
            $cienteb[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesdeb[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomciab[] = $polizas[$a]['nomcia'];
            $nramob[] = $polizas[$a]['nramo'];
            $prima_sb[] = $polizas[$a]['prima'];
            $p_ttb[] = $p_t;
            $p_difb[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_polizab[] = $polizas[$a]['f_hastapoliza'];
            $idpolizab[] = $polizas[$a]['id_poliza'];

            $toolb[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas']. ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }

        if ($p_t != 0 && $p_difC < 0) {
            $p_eneroc[] = $p_ene;
            $a_eneroc[] = $a_ene;
            $p_febreroc[] = $p_feb;
            $a_febreroc[] = $a_feb;
            $p_marzoc[] = $p_mar;
            $a_marzoc[] = $a_mar;
            $p_abrilc[] = $p_abr;
            $a_abrilc[] = $a_abr;
            $p_mayoc[] = $p_may;
            $a_mayoc[] = $a_may;
            $p_junioc[] = $p_jun;
            $a_junioc[] = $a_jun;
            $p_julioc[] = $p_jul;
            $a_julioc[] = $a_jul;
            $p_agostoc[] = $p_ago;
            $a_agostoc[] = $a_ago;
            $p_septiemprec[] = $p_sep;
            $a_septiemprec[] = $a_sep;
            $p_octubrec[] = $p_oct;
            $a_octubrec[] = $a_oct;
            $p_noviembrec[] = $p_nov;
            $a_noviembrec[] = $a_nov;
            $p_diciembrec[] = $p_dic;
            $a_diciembrec[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivo[] = $ejecutivo_data[0]['nombre'];
            
            $cod_polizac[] = $polizas[$a]['cod_poliza'];
            $cientec[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesdec[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomciac[] = $polizas[$a]['nomcia'];
            $nramoc[] = $polizas[$a]['nramo'];
            $prima_sc[] = $polizas[$a]['prima'];
            $p_ttc[] = $p_t;
            $p_difc[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_polizac[] = $polizas[$a]['f_hastapoliza'];
            $idpolizac[] = $polizas[$a]['id_poliza'];

            $toolc[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas']. ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }
    }

    if (isset($p_dif)) {
        arsort($p_dif, SORT_NUMERIC);
        foreach ($p_dif as $key => $value) {
            $cod_poliza1[] = $cod_poliza[$key];
            $ciente1[] = $ciente[$key];
            $newDesde1[] = $newDesde[$key];
            $nomcia1[] = $nomcia[$key];
            $nramo1[] = $nramo[$key];
            $prima_s1[] = $prima_s[$key];
            $p_tt1[] = $p_tt[$key];
            $tool1[] = $tool[$key];
            $p_dif1[] = $value;

            $p_enero1[] = $p_enero[$key];
            $a_enero1[] = $a_enero[$key];
            $p_febrero1[] = $p_febrero[$key];
            $a_febrero1[] = $a_febrero[$key];
            $p_marzo1[] = $p_marzo[$key];
            $a_marzo1[] = $a_marzo[$key];
            $p_abril1[] = $p_abril[$key];
            $a_abril1[] = $a_abril[$key];
            $p_mayo1[] = $p_mayo[$key];
            $a_mayo1[] = $a_mayo[$key];
            $p_junio1[] = $p_junio[$key];
            $a_junio1[] = $a_junio[$key];
            $p_julio1[] = $p_julio[$key];
            $a_julio1[] = $a_julio[$key];
            $p_agosto1[] = $p_agosto[$key];
            $a_agosto1[] = $a_agosto[$key];
            $p_septiempre1[] = $p_septiempre[$key];
            $a_septiempre1[] = $a_septiempre[$key];
            $p_octubre1[] = $p_octubre[$key];
            $a_octubre1[] = $a_octubre[$key];
            $p_noviembre1[] = $p_noviembre[$key];
            $a_noviembre1[] = $a_noviembre[$key];
            $p_diciembre1[] = $p_diciembre[$key];
            $a_diciembre1[] = $a_diciembre[$key];

            $f_hasta_poliza1[] = $f_hasta_poliza[$key];
            $idpoliza1[] = $idpoliza[$key];
        }
    }

    if (isset($p_difa)) {
        arsort($p_difa, SORT_NUMERIC);
        foreach ($p_difa as $key => $value) {
            $cod_poliza1a[] = $cod_polizaa[$key];
            $ciente1a[] = $cientea[$key];
            $newDesde1a[] = $newDesdea[$key];
            $nomcia1a[] = $nomciaa[$key];
            $nramo1a[] = $nramoa[$key];
            $prima_s1a[] = $prima_sa[$key];
            $p_tt1a[] = $p_tta[$key];
            $tool1a[] = $toola[$key];
            $p_dif1a[] = $value;

            $p_enero1a[] = $p_eneroa[$key];
            $a_enero1a[] = $a_eneroa[$key];
            $p_febrero1a[] = $p_febreroa[$key];
            $a_febrero1a[] = $a_febreroa[$key];
            $p_marzo1a[] = $p_marzoa[$key];
            $a_marzo1a[] = $a_marzoa[$key];
            $p_abril1a[] = $p_abrila[$key];
            $a_abril1a[] = $a_abrila[$key];
            $p_mayo1a[] = $p_mayoa[$key];
            $a_mayo1a[] = $a_mayoa[$key];
            $p_junio1a[] = $p_junioa[$key];
            $a_junio1a[] = $a_junioa[$key];
            $p_julio1a[] = $p_julioa[$key];
            $a_julio1a[] = $a_julioa[$key];
            $p_agosto1a[] = $p_agostoa[$key];
            $a_agosto1a[] = $a_agostoa[$key];
            $p_septiempre1a[] = $p_septiemprea[$key];
            $a_septiempre1a[] = $a_septiemprea[$key];
            $p_octubre1a[] = $p_octubrea[$key];
            $a_octubre1a[] = $a_octubrea[$key];
            $p_noviembre1a[] = $p_noviembrea[$key];
            $a_noviembre1a[] = $a_noviembrea[$key];
            $p_diciembre1a[] = $p_diciembrea[$key];
            $a_diciembre1a[] = $a_diciembrea[$key];

            $f_hasta_poliza1a[] = $f_hasta_polizaa[$key];
            $idpoliza1a[] = $idpolizaa[$key];
        }
    }

    if (isset($p_difb)) {
        arsort($p_difb, SORT_NUMERIC);
        foreach ($p_difb as $key => $value) {
            $cod_poliza1b[] = $cod_polizab[$key];
            $ciente1b[] = $cienteb[$key];
            $newDesde1b[] = $newDesdeb[$key];
            $nomcia1b[] = $nomciab[$key];
            $nramo1b[] = $nramob[$key];
            $prima_s1b[] = $prima_sb[$key];
            $p_tt1b[] = $p_ttb[$key];
            $tool1b[] = $toolb[$key];
            $p_dif1b[] = $value;

            $p_enero1b[] = $p_enerob[$key];
            $a_enero1b[] = $a_enerob[$key];
            $p_febrero1b[] = $p_febrerob[$key];
            $a_febrero1b[] = $a_febrerob[$key];
            $p_marzo1b[] = $p_marzob[$key];
            $a_marzo1b[] = $a_marzob[$key];
            $p_abril1b[] = $p_abrilb[$key];
            $a_abril1b[] = $a_abrilb[$key];
            $p_mayo1b[] = $p_mayob[$key];
            $a_mayo1b[] = $a_mayob[$key];
            $p_junio1b[] = $p_juniob[$key];
            $a_junio1b[] = $a_juniob[$key];
            $p_julio1b[] = $p_juliob[$key];
            $a_julio1b[] = $a_juliob[$key];
            $p_agosto1b[] = $p_agostob[$key];
            $a_agosto1b[] = $a_agostob[$key];
            $p_septiempre1b[] = $p_septiempreb[$key];
            $a_septiempre1b[] = $a_septiempreb[$key];
            $p_octubre1b[] = $p_octubreb[$key];
            $a_octubre1b[] = $a_octubreb[$key];
            $p_noviembre1b[] = $p_noviembreb[$key];
            $a_noviembre1b[] = $a_noviembreb[$key];
            $p_diciembre1b[] = $p_diciembreb[$key];
            $a_diciembre1b[] = $a_diciembreb[$key];

            $f_hasta_poliza1b[] = $f_hasta_polizab[$key];
            $idpoliza1b[] = $idpolizab[$key];
        }
    }

    if (isset($p_difc)) {
        arsort($p_difc, SORT_NUMERIC);
        foreach ($p_difc as $key => $value) {
            $cod_poliza1c[] = $cod_polizac[$key];
            $ciente1c[] = $cientec[$key];
            $newDesde1c[] = $newDesdec[$key];
            $nomcia1c[] = $nomciac[$key];
            $nramo1c[] = $nramoc[$key];
            $prima_s1c[] = $prima_sc[$key];
            $p_tt1c[] = $p_ttc[$key];
            $tool1c[] = $toolc[$key];
            $p_dif1c[] = $value;

            $p_enero1c[] = $p_eneroc[$key];
            $a_enero1c[] = $a_eneroc[$key];
            $p_febrero1c[] = $p_febreroc[$key];
            $a_febrero1c[] = $a_febreroc[$key];
            $p_marzo1c[] = $p_marzoc[$key];
            $a_marzo1c[] = $a_marzoc[$key];
            $p_abril1c[] = $p_abrilc[$key];
            $a_abril1c[] = $a_abrilc[$key];
            $p_mayo1c[] = $p_mayoc[$key];
            $a_mayo1c[] = $a_mayoc[$key];
            $p_junio1c[] = $p_junioc[$key];
            $a_junio1c[] = $a_junioc[$key];
            $p_julio1c[] = $p_julioc[$key];
            $a_julio1c[] = $a_julioc[$key];
            $p_agosto1c[] = $p_agostoc[$key];
            $a_agosto1c[] = $a_agostoc[$key];
            $p_septiempre1c[] = $p_septiemprec[$key];
            $a_septiempre1c[] = $a_septiemprec[$key];
            $p_octubre1c[] = $p_octubrec[$key];
            $a_octubre1c[] = $a_octubrec[$key];
            $p_noviembre1c[] = $p_noviembrec[$key];
            $a_noviembre1c[] = $a_noviembrec[$key];
            $p_diciembre1c[] = $p_diciembrec[$key];
            $a_diciembre1c[] = $a_diciembrec[$key];

            $f_hasta_poliza1c[] = $f_hasta_polizac[$key];
            $idpoliza1c[] = $idpolizac[$key];
        }
    }

    unset($p_dif, $nomcia, $cod_poliza, $ciente, $newDesde, $nramo, $prima_s, $p_tt, $tool, $p_enero, $p_febrero, $p_marzo, $p_abril, $p_mayo, $p_junio, $p_julio, $p_agosto, $p_septiempre, $p_octubre, $p_noviembre, $p_diciembre, $f_hasta_poliza, $idpoliza, $a_enero, $a_febrero, $a_marzo, $a_abril, $a_mayo, $a_junio, $a_julio, $a_agosto, $a_septiempre, $a_octubre, $a_noviembre, $a_diciembre);

    unset($p_difa, $nomciaa, $cod_polizaa, $cientea, $newDesdea, $nramoa, $prima_sa, $p_tta, $toola, $p_eneroa, $p_febreroa, $p_marzoa, $p_abrila, $p_mayoa, $p_junioa, $p_julioa, $p_agostoa, $p_septiemprea, $p_octubrea, $p_noviembrea, $p_diciembrea, $f_hasta_polizaa, $idpolizaa, $a_eneroa, $a_febreroa, $a_marzoa, $a_abrila, $a_mayoa, $a_junioa, $a_julioa, $a_agostoa, $a_septiemprea, $a_octubrea, $a_noviembrea, $a_diciembrea);

    unset($p_difb, $nomciab, $cod_polizab, $cienteb, $newDesdeb, $nramob, $prima_sb, $p_ttb, $toolb, $p_enerob, $p_febrerob, $p_marzob, $p_abrilb, $p_mayob, $p_juniob, $p_juliob, $p_agostob, $p_septiempreb, $p_octubreb, $p_noviembreb, $p_diciembreb, $f_hasta_polizab, $idpolizab, $a_enerob, $a_febrerob, $a_marzob, $a_abrilb, $a_mayob, $a_juniob, $a_juliob, $a_agostob, $a_septiempreb, $a_octubreb, $a_noviembreb, $a_diciembreb);

    unset($p_difc, $nomciac, $cod_polizac, $cientec, $newDesdec, $nramoc, $prima_sc, $p_ttc, $toolc, $p_eneroc, $p_febreroc, $p_marzoc, $p_abrilc, $p_mayoc, $p_junioc, $p_julioc, $p_agostoc, $p_septiemprec, $p_octubrec, $p_noviembrec, $p_diciembrec, $f_hasta_polizac, $idpolizac, $a_eneroc, $a_febreroc, $a_marzoc, $a_abrilc, $a_mayoc, $a_junioc, $a_julioc, $a_agostoc, $a_septiemprec, $a_octubrec, $a_noviembrec, $a_diciembrec);
}

//--- prima_moroso.php
if ($pag == 'prima_moroso') {

    $desde = $_GET['desdeP_submit'];
    $hasta = $_GET['hastaP_submit'];

    $desdeP = $_GET['desdeP'];
    $hastaP = $_GET['hastaP'];

    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';
    if ($ramo != '') {
        $ramoIn = "('" . implode("','", $ramo) . "')";
        $ramo = " AND dramo.nramo IN " .  $ramoIn;
    }

    $fpago = (isset($_GET["fpago"]) != null) ? $_GET["fpago"] : '';
    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';

    $polizas = $obj->get_poliza_total_by_filtro_detalle_p($desde, $hasta, $ramo, $fpago, $cia, $asesor);
    $cantPolizas = ($polizas == 0) ? 0 : sizeof($polizas) ;

    for ($a = 0; $a < $cantPolizas; $a++) {

        $p_ene1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '01');
        $p_ene = 0;
        $a_ene = 0;
        if ($p_ene1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_ene1 as $p_ene2) {
                $p_ene = $p_ene + $p_ene2['SUM(prima_com)'];
                $a_ene = $p_ene2['YEAR(f_pago_prima)'];
            }
        }
        $p_feb1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '02');
        $p_feb = 0;
        $a_feb = 0;
        if ($p_feb1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_feb1 as $p_feb2) {
                $p_feb = $p_feb + $p_feb2['SUM(prima_com)'];
                $a_feb = $p_feb2['YEAR(f_pago_prima)'];
            }
        }
        $p_mar1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '03');
        $p_mar = 0;
        $a_mar = 0;
        if ($p_mar1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_mar1 as $p_mar2) {
                $p_mar = $p_mar + $p_mar2['SUM(prima_com)'];
                $a_mar = $p_mar2['YEAR(f_pago_prima)'];
            }
        }
        $p_abr1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '04');
        $p_abr = 0;
        $a_abr = 0;
        if ($p_abr1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_abr1 as $p_abr2) {
                $p_abr = $p_abr + $p_abr2['SUM(prima_com)'];
                $a_abr = $p_abr2['YEAR(f_pago_prima)'];
            }
        }
        $p_may1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '05');
        $p_may = 0;
        $a_may = 0;
        if ($p_may1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_may1 as $p_may2) {
                $p_may = $p_may + $p_may2['SUM(prima_com)'];
                $a_may = $p_may2['YEAR(f_pago_prima)'];
            }
        }
        $p_jun1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '06');
        $p_jun = 0;
        $a_jun = 0;
        if ($p_jun1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_jun1 as $p_jun2) {
                $p_jun = $p_jun + $p_jun2['SUM(prima_com)'];
                $a_jun = $p_jun2['YEAR(f_pago_prima)'];
            }
        }
        $p_jul1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '07');
        $p_jul = 0;
        $a_jul = 0;
        if ($p_jul1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_jul1 as $p_jul2) {
                $p_jul = $p_jul + $p_jul2['SUM(prima_com)'];
                $a_jul = $p_jul2['YEAR(f_pago_prima)'];
            }
        }
        $p_ago1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '08');
        $p_ago = 0;
        $a_ago = 0;
        if ($p_ago1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_ago1 as $p_ago2) {
                $p_ago = $p_ago + $p_ago2['SUM(prima_com)'];
                $a_ago = $p_ago2['YEAR(f_pago_prima)'];
            }
        }
        $p_sep1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '09');
        $p_sep = 0;
        $a_sep = 0;
        if ($p_sep1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_sep1 as $p_sep2) {
                $p_sep = $p_sep + $p_sep2['SUM(prima_com)'];
                $a_sep = $p_sep2['YEAR(f_pago_prima)'];
            }
        }
        $p_oct1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '10');
        $p_oct = 0;
        $a_oct = 0;
        if ($p_oct1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_oct1 as $p_oct2) {
                $p_oct = $p_oct + $p_oct2['SUM(prima_com)'];
                $a_oct = $p_oct2['YEAR(f_pago_prima)'];
            }
        }
        $p_nov1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '11');
        $p_nov = 0;
        $a_nov = 0;
        if ($p_nov1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_nov1 as $p_nov2) {
                $p_nov = $p_nov + $p_nov2['SUM(prima_com)'];
                $a_nov = $p_nov2['YEAR(f_pago_prima)'];
            }
        }
        $p_dic1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '12');
        $p_dic = 0;
        $a_dic = 0;
        if ($p_dic1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_dic1 as $p_dic2) {
                $p_dic = $p_dic + $p_dic2['SUM(prima_com)'];
                $a_dic = $p_dic2['YEAR(f_pago_prima)'];
            }
        }

        $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
        $p_difC = ($polizas[$a]['prima'] - $p_t);

        if ($p_t == 0) {
            $p_enero[] = $p_ene;
            $a_enero[] = $a_ene;
            $p_febrero[] = $p_feb;
            $a_febrero[] = $a_feb;
            $p_marzo[] = $p_mar;
            $a_marzo[] = $a_mar;
            $p_abril[] = $p_abr;
            $a_abril[] = $a_abr;
            $p_mayo[] = $p_may;
            $a_mayo[] = $a_may;
            $p_junio[] = $p_jun;
            $a_junio[] = $a_jun;
            $p_julio[] = $p_jul;
            $a_julio[] = $a_jul;
            $p_agosto[] = $p_ago;
            $a_agosto[] = $a_ago;
            $p_septiempre[] = $p_sep;
            $a_septiempre[] = $a_sep;
            $p_octubre[] = $p_oct;
            $a_octubre[] = $a_oct;
            $p_noviembre[] = $p_nov;
            $a_noviembre[] = $a_nov;
            $p_diciembre[] = $p_dic;
            $a_diciembre[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivo[] = $ejecutivo_data[0]['nombre'];

            $cod_poliza[] = $polizas[$a]['cod_poliza'];
            $ciente[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesde[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomcia[] = $polizas[$a]['nomcia'];
            $nramo[] = $polizas[$a]['nramo'];
            $prima_s[] = $polizas[$a]['prima'];
            $p_tt[] = $p_t;
            $p_dif[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_poliza[] = $polizas[$a]['f_hastapoliza'];

            $idpoliza[] = $polizas[$a]['id_poliza'];

            $tool[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas'] . ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }

        if ($p_t != 0 && $p_difC > 0) {
            $p_eneroa[] = $p_ene;
            $a_eneroa[] = $a_ene;
            $p_febreroa[] = $p_feb;
            $a_febreroa[] = $a_feb;
            $p_marzoa[] = $p_mar;
            $a_marzoa[] = $a_mar;
            $p_abrila[] = $p_abr;
            $a_abrila[] = $a_abr;
            $p_mayoa[] = $p_may;
            $a_mayoa[] = $a_may;
            $p_junioa[] = $p_jun;
            $a_junioa[] = $a_jun;
            $p_julioa[] = $p_jul;
            $a_julioa[] = $a_jul;
            $p_agostoa[] = $p_ago;
            $a_agostoa[] = $a_ago;
            $p_septiemprea[] = $p_sep;
            $a_septiemprea[] = $a_sep;
            $p_octubrea[] = $p_oct;
            $a_octubrea[] = $a_oct;
            $p_noviembrea[] = $p_nov;
            $a_noviembrea[] = $a_nov;
            $p_diciembrea[] = $p_dic;
            $a_diciembrea[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivoa[] = $ejecutivo_data[0]['nombre'];

            $cod_polizaa[] = $polizas[$a]['cod_poliza'];
            $cientea[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesdea[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomciaa[] = $polizas[$a]['nomcia'];
            $nramoa[] = $polizas[$a]['nramo'];
            $prima_sa[] = $polizas[$a]['prima'];
            $p_tta[] = $p_t;
            $p_difa[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_polizaa[] = $polizas[$a]['f_hastapoliza'];
            $idpolizaa[] = $polizas[$a]['id_poliza'];

            $toola[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas'] . ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }

        if ($p_t != 0 && $p_difC == 0) {
            $p_enerob[] = $p_ene;
            $a_enerob[] = $a_ene;
            $p_febrerob[] = $p_feb;
            $a_febrerob[] = $a_feb;
            $p_marzob[] = $p_mar;
            $a_marzob[] = $a_mar;
            $p_abrilb[] = $p_abr;
            $a_abrilb[] = $a_abr;
            $p_mayob[] = $p_may;
            $a_mayob[] = $a_may;
            $p_juniob[] = $p_jun;
            $a_juniob[] = $a_jun;
            $p_juliob[] = $p_jul;
            $a_juliob[] = $a_jul;
            $p_agostob[] = $p_ago;
            $a_agostob[] = $a_ago;
            $p_septiempreb[] = $p_sep;
            $a_septiempreb[] = $a_sep;
            $p_octubreb[] = $p_oct;
            $a_octubreb[] = $a_oct;
            $p_noviembreb[] = $p_nov;
            $a_noviembreb[] = $a_nov;
            $p_diciembreb[] = $p_dic;
            $a_diciembreb[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivob[] = $ejecutivo_data[0]['nombre'];

            $cod_polizab[] = $polizas[$a]['cod_poliza'];
            $cienteb[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesdeb[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomciab[] = $polizas[$a]['nomcia'];
            $nramob[] = $polizas[$a]['nramo'];
            $prima_sb[] = $polizas[$a]['prima'];
            $p_ttb[] = $p_t;
            $p_difb[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_polizab[] = $polizas[$a]['f_hastapoliza'];
            $idpolizab[] = $polizas[$a]['id_poliza'];

            $toolb[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas']. ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }

        if ($p_t != 0 && $p_difC < 0) {
            $p_eneroc[] = $p_ene;
            $a_eneroc[] = $a_ene;
            $p_febreroc[] = $p_feb;
            $a_febreroc[] = $a_feb;
            $p_marzoc[] = $p_mar;
            $a_marzoc[] = $a_mar;
            $p_abrilc[] = $p_abr;
            $a_abrilc[] = $a_abr;
            $p_mayoc[] = $p_may;
            $a_mayoc[] = $a_may;
            $p_junioc[] = $p_jun;
            $a_junioc[] = $a_jun;
            $p_julioc[] = $p_jul;
            $a_julioc[] = $a_jul;
            $p_agostoc[] = $p_ago;
            $a_agostoc[] = $a_ago;
            $p_septiemprec[] = $p_sep;
            $a_septiemprec[] = $a_sep;
            $p_octubrec[] = $p_oct;
            $a_octubrec[] = $a_oct;
            $p_noviembrec[] = $p_nov;
            $a_noviembrec[] = $a_nov;
            $p_diciembrec[] = $p_dic;
            $a_diciembrec[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivoc[] = $ejecutivo_data[0]['nombre'];

            $cod_polizac[] = $polizas[$a]['cod_poliza'];
            $cientec[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesdec[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomciac[] = $polizas[$a]['nomcia'];
            $nramoc[] = $polizas[$a]['nramo'];
            $prima_sc[] = $polizas[$a]['prima'];
            $p_ttc[] = $p_t;
            $p_difc[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_polizac[] = $polizas[$a]['f_hastapoliza'];
            $idpolizac[] = $polizas[$a]['id_poliza'];

            $toolc[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas']. ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }
    }

    if (isset($p_dif)) {
        arsort($p_dif, SORT_NUMERIC);
        foreach ($p_dif as $key => $value) {
            $cod_poliza1[] = $cod_poliza[$key];
            $ciente1[] = $ciente[$key];
            $newDesde1[] = $newDesde[$key];
            $nomcia1[] = $nomcia[$key];
            $nramo1[] = $nramo[$key];
            $prima_s1[] = $prima_s[$key];
            $p_tt1[] = $p_tt[$key];
            $tool1[] = $tool[$key];
            $p_dif1[] = $value;
            $ejecutivo1[] = $ejecutivo[$key];

            $p_enero1[] = $p_enero[$key];
            $a_enero1[] = $a_enero[$key];
            $p_febrero1[] = $p_febrero[$key];
            $a_febrero1[] = $a_febrero[$key];
            $p_marzo1[] = $p_marzo[$key];
            $a_marzo1[] = $a_marzo[$key];
            $p_abril1[] = $p_abril[$key];
            $a_abril1[] = $a_abril[$key];
            $p_mayo1[] = $p_mayo[$key];
            $a_mayo1[] = $a_mayo[$key];
            $p_junio1[] = $p_junio[$key];
            $a_junio1[] = $a_junio[$key];
            $p_julio1[] = $p_julio[$key];
            $a_julio1[] = $a_julio[$key];
            $p_agosto1[] = $p_agosto[$key];
            $a_agosto1[] = $a_agosto[$key];
            $p_septiempre1[] = $p_septiempre[$key];
            $a_septiempre1[] = $a_septiempre[$key];
            $p_octubre1[] = $p_octubre[$key];
            $a_octubre1[] = $a_octubre[$key];
            $p_noviembre1[] = $p_noviembre[$key];
            $a_noviembre1[] = $a_noviembre[$key];
            $p_diciembre1[] = $p_diciembre[$key];
            $a_diciembre1[] = $a_diciembre[$key];

            $f_hasta_poliza1[] = $f_hasta_poliza[$key];
            $idpoliza1[] = $idpoliza[$key];
        }
    }

    if (isset($p_difa)) {
        arsort($p_difa, SORT_NUMERIC);
        foreach ($p_difa as $key => $value) {
            $cod_poliza1a[] = $cod_polizaa[$key];
            $ciente1a[] = $cientea[$key];
            $newDesde1a[] = $newDesdea[$key];
            $nomcia1a[] = $nomciaa[$key];
            $nramo1a[] = $nramoa[$key];
            $prima_s1a[] = $prima_sa[$key];
            $p_tt1a[] = $p_tta[$key];
            $tool1a[] = $toola[$key];
            $p_dif1a[] = $value;
            $ejecutivo1a[] = $ejecutivoa[$key];

            $p_enero1a[] = $p_eneroa[$key];
            $a_enero1a[] = $a_eneroa[$key];
            $p_febrero1a[] = $p_febreroa[$key];
            $a_febrero1a[] = $a_febreroa[$key];
            $p_marzo1a[] = $p_marzoa[$key];
            $a_marzo1a[] = $a_marzoa[$key];
            $p_abril1a[] = $p_abrila[$key];
            $a_abril1a[] = $a_abrila[$key];
            $p_mayo1a[] = $p_mayoa[$key];
            $a_mayo1a[] = $a_mayoa[$key];
            $p_junio1a[] = $p_junioa[$key];
            $a_junio1a[] = $a_junioa[$key];
            $p_julio1a[] = $p_julioa[$key];
            $a_julio1a[] = $a_julioa[$key];
            $p_agosto1a[] = $p_agostoa[$key];
            $a_agosto1a[] = $a_agostoa[$key];
            $p_septiempre1a[] = $p_septiemprea[$key];
            $a_septiempre1a[] = $a_septiemprea[$key];
            $p_octubre1a[] = $p_octubrea[$key];
            $a_octubre1a[] = $a_octubrea[$key];
            $p_noviembre1a[] = $p_noviembrea[$key];
            $a_noviembre1a[] = $a_noviembrea[$key];
            $p_diciembre1a[] = $p_diciembrea[$key];
            $a_diciembre1a[] = $a_diciembrea[$key];

            $f_hasta_poliza1a[] = $f_hasta_polizaa[$key];
            $idpoliza1a[] = $idpolizaa[$key];
        }
    }

    if (isset($p_difb)) {
        arsort($p_difb, SORT_NUMERIC);
        foreach ($p_difb as $key => $value) {
            $cod_poliza1b[] = $cod_polizab[$key];
            $ciente1b[] = $cienteb[$key];
            $newDesde1b[] = $newDesdeb[$key];
            $nomcia1b[] = $nomciab[$key];
            $nramo1b[] = $nramob[$key];
            $prima_s1b[] = $prima_sb[$key];
            $p_tt1b[] = $p_ttb[$key];
            $tool1b[] = $toolb[$key];
            $p_dif1b[] = $value;
            $ejecutivo1b[] = $ejecutivob[$key];

            $p_enero1b[] = $p_enerob[$key];
            $a_enero1b[] = $a_enerob[$key];
            $p_febrero1b[] = $p_febrerob[$key];
            $a_febrero1b[] = $a_febrerob[$key];
            $p_marzo1b[] = $p_marzob[$key];
            $a_marzo1b[] = $a_marzob[$key];
            $p_abril1b[] = $p_abrilb[$key];
            $a_abril1b[] = $a_abrilb[$key];
            $p_mayo1b[] = $p_mayob[$key];
            $a_mayo1b[] = $a_mayob[$key];
            $p_junio1b[] = $p_juniob[$key];
            $a_junio1b[] = $a_juniob[$key];
            $p_julio1b[] = $p_juliob[$key];
            $a_julio1b[] = $a_juliob[$key];
            $p_agosto1b[] = $p_agostob[$key];
            $a_agosto1b[] = $a_agostob[$key];
            $p_septiempre1b[] = $p_septiempreb[$key];
            $a_septiempre1b[] = $a_septiempreb[$key];
            $p_octubre1b[] = $p_octubreb[$key];
            $a_octubre1b[] = $a_octubreb[$key];
            $p_noviembre1b[] = $p_noviembreb[$key];
            $a_noviembre1b[] = $a_noviembreb[$key];
            $p_diciembre1b[] = $p_diciembreb[$key];
            $a_diciembre1b[] = $a_diciembreb[$key];

            $f_hasta_poliza1b[] = $f_hasta_polizab[$key];
            $idpoliza1b[] = $idpolizab[$key];
        }
    }

    if (isset($p_difc)) {
        arsort($p_difc, SORT_NUMERIC);
        foreach ($p_difc as $key => $value) {
            $cod_poliza1c[] = $cod_polizac[$key];
            $ciente1c[] = $cientec[$key];
            $newDesde1c[] = $newDesdec[$key];
            $nomcia1c[] = $nomciac[$key];
            $nramo1c[] = $nramoc[$key];
            $prima_s1c[] = $prima_sc[$key];
            $p_tt1c[] = $p_ttc[$key];
            $tool1c[] = $toolc[$key];
            $p_dif1c[] = $value;
            $ejecutivo1c[] = $ejecutivoc[$key];

            $p_enero1c[] = $p_eneroc[$key];
            $a_enero1c[] = $a_eneroc[$key];
            $p_febrero1c[] = $p_febreroc[$key];
            $a_febrero1c[] = $a_febreroc[$key];
            $p_marzo1c[] = $p_marzoc[$key];
            $a_marzo1c[] = $a_marzoc[$key];
            $p_abril1c[] = $p_abrilc[$key];
            $a_abril1c[] = $a_abrilc[$key];
            $p_mayo1c[] = $p_mayoc[$key];
            $a_mayo1c[] = $a_mayoc[$key];
            $p_junio1c[] = $p_junioc[$key];
            $a_junio1c[] = $a_junioc[$key];
            $p_julio1c[] = $p_julioc[$key];
            $a_julio1c[] = $a_julioc[$key];
            $p_agosto1c[] = $p_agostoc[$key];
            $a_agosto1c[] = $a_agostoc[$key];
            $p_septiempre1c[] = $p_septiemprec[$key];
            $a_septiempre1c[] = $a_septiemprec[$key];
            $p_octubre1c[] = $p_octubrec[$key];
            $a_octubre1c[] = $a_octubrec[$key];
            $p_noviembre1c[] = $p_noviembrec[$key];
            $a_noviembre1c[] = $a_noviembrec[$key];
            $p_diciembre1c[] = $p_diciembrec[$key];
            $a_diciembre1c[] = $a_diciembrec[$key];

            $f_hasta_poliza1c[] = $f_hasta_polizac[$key];
            $idpoliza1c[] = $idpolizac[$key];
        }
    }

    unset($p_dif, $nomcia, $cod_poliza, $ciente, $newDesde, $nramo, $prima_s, $p_tt, $tool, $p_enero, $p_febrero, $p_marzo, $p_abril, $p_mayo, $p_junio, $p_julio, $p_agosto, $p_septiempre, $p_octubre, $p_noviembre, $p_diciembre, $f_hasta_poliza, $idpoliza, $a_enero, $a_febrero, $a_marzo, $a_abril, $a_mayo, $a_junio, $a_julio, $a_agosto, $a_septiempre, $a_octubre, $a_noviembre, $a_diciembre, $ejecutivo);

    unset($p_difa, $nomciaa, $cod_polizaa, $cientea, $newDesdea, $nramoa, $prima_sa, $p_tta, $toola, $p_eneroa, $p_febreroa, $p_marzoa, $p_abrila, $p_mayoa, $p_junioa, $p_julioa, $p_agostoa, $p_septiemprea, $p_octubrea, $p_noviembrea, $p_diciembrea, $f_hasta_polizaa, $idpolizaa, $a_eneroa, $a_febreroa, $a_marzoa, $a_abrila, $a_mayoa, $a_junioa, $a_julioa, $a_agostoa, $a_septiemprea, $a_octubrea, $a_noviembrea, $a_diciembrea, $ejecutivoa);

    unset($p_difb, $nomciab, $cod_polizab, $cienteb, $newDesdeb, $nramob, $prima_sb, $p_ttb, $toolb, $p_enerob, $p_febrerob, $p_marzob, $p_abrilb, $p_mayob, $p_juniob, $p_juliob, $p_agostob, $p_septiempreb, $p_octubreb, $p_noviembreb, $p_diciembreb, $f_hasta_polizab, $idpolizab, $a_enerob, $a_febrerob, $a_marzob, $a_abrilb, $a_mayob, $a_juniob, $a_juliob, $a_agostob, $a_septiempreb, $a_octubreb, $a_noviembreb, $a_diciembreb, $ejecutivob);

    unset($p_difc, $nomciac, $cod_polizac, $cientec, $newDesdec, $nramoc, $prima_sc, $p_ttc, $toolc, $p_eneroc, $p_febreroc, $p_marzoc, $p_abrilc, $p_mayoc, $p_junioc, $p_julioc, $p_agostoc, $p_septiemprec, $p_octubrec, $p_noviembrec, $p_diciembrec, $f_hasta_polizac, $idpolizac, $a_eneroc, $a_febreroc, $a_marzoc, $a_abrilc, $a_mayoc, $a_junioc, $a_julioc, $a_agostoc, $a_septiemprec, $a_octubrec, $a_noviembrec, $a_diciembrec, $ejecutivoc);

    $cia_para_enviar_via_url = serialize($cia);
    $ciaEnv = urlencode($cia_para_enviar_via_url);
    $ramo_para_enviar_via_url = serialize($ramo);
    $ramoEnv = urlencode($ramo_para_enviar_via_url);
    $asesor_para_enviar_via_url = serialize($asesor);
    $asesorEnv = urlencode($asesor_para_enviar_via_url);
    $fpago_para_enviar_via_url = serialize($fpago);
    $fpagoEnv = urlencode($fpago_para_enviar_via_url);
}

//--- prima_moroso_excel.php
if ($pag == 'prima_moroso_excel') {

    $desde = $_GET['desdeP_submit'];
    $hasta = $_GET['hastaP_submit'];

    $desdeP = $_GET['desdeP'];
    $hastaP = $_GET['hastaP'];

    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
    if (isset($_GET["cia"]) != null) {
        $cia_para_recibir_via_url = stripslashes($_GET["cia"]);
        $cia_para_recibir_via_url = urldecode($cia_para_recibir_via_url);
        $cia = unserialize($cia_para_recibir_via_url);
    }else{
        $cia = '';
    }

    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';
    if (isset($_GET["ramo"]) != null) {
        $ramo_para_recibir_via_url = stripslashes($_GET["ramo"]);
        $ramo_para_recibir_via_url = urldecode($ramo_para_recibir_via_url);
        $ramo = unserialize($ramo_para_recibir_via_url);
    }else{
        $ramo = '';
    }

    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';
    if (isset($_GET["asesor"]) != null) {
        $asesor_para_recibir_via_url = stripslashes($_GET["asesor"]);
        $asesor_para_recibir_via_url = urldecode($asesor_para_recibir_via_url);
        $asesor = unserialize($asesor_para_recibir_via_url);
    }else{
        $asesor = '';
    }

    $fpago = (isset($_GET["fpago"]) != null) ? $_GET["fpago"] : '';
    if (isset($_GET["fpago"]) != null) {
        $fpago_para_recibir_via_url = stripslashes($_GET["fpago"]);
        $fpago_para_recibir_via_url = urldecode($fpago_para_recibir_via_url);
        $fpago = unserialize($fpago_para_recibir_via_url);
    }else{
        $fpago = '';
    }


    $polizas = $obj->get_poliza_total_by_filtro_detalle_p($desde, $hasta, $ramo, $fpago, $cia, $asesor);
    $cantPolizas = ($polizas == 0) ? 0 : sizeof($polizas) ;

    for ($a = 0; $a < $cantPolizas; $a++) {

        $p_ene1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '01');
        $p_ene = 0;
        $a_ene = 0;
        if ($p_ene1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_ene1 as $p_ene2) {
                $p_ene = $p_ene + $p_ene2['SUM(prima_com)'];
                $a_ene = $p_ene2['YEAR(f_pago_prima)'];
            }
        }
        $p_feb1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '02');
        $p_feb = 0;
        $a_feb = 0;
        if ($p_feb1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_feb1 as $p_feb2) {
                $p_feb = $p_feb + $p_feb2['SUM(prima_com)'];
                $a_feb = $p_feb2['YEAR(f_pago_prima)'];
            }
        }
        $p_mar1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '03');
        $p_mar = 0;
        $a_mar = 0;
        if ($p_mar1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_mar1 as $p_mar2) {
                $p_mar = $p_mar + $p_mar2['SUM(prima_com)'];
                $a_mar = $p_mar2['YEAR(f_pago_prima)'];
            }
        }
        $p_abr1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '04');
        $p_abr = 0;
        $a_abr = 0;
        if ($p_abr1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_abr1 as $p_abr2) {
                $p_abr = $p_abr + $p_abr2['SUM(prima_com)'];
                $a_abr = $p_abr2['YEAR(f_pago_prima)'];
            }
        }
        $p_may1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '05');
        $p_may = 0;
        $a_may = 0;
        if ($p_may1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_may1 as $p_may2) {
                $p_may = $p_may + $p_may2['SUM(prima_com)'];
                $a_may = $p_may2['YEAR(f_pago_prima)'];
            }
        }
        $p_jun1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '06');
        $p_jun = 0;
        $a_jun = 0;
        if ($p_jun1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_jun1 as $p_jun2) {
                $p_jun = $p_jun + $p_jun2['SUM(prima_com)'];
                $a_jun = $p_jun2['YEAR(f_pago_prima)'];
            }
        }
        $p_jul1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '07');
        $p_jul = 0;
        $a_jul = 0;
        if ($p_jul1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_jul1 as $p_jul2) {
                $p_jul = $p_jul + $p_jul2['SUM(prima_com)'];
                $a_jul = $p_jul2['YEAR(f_pago_prima)'];
            }
        }
        $p_ago1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '08');
        $p_ago = 0;
        $a_ago = 0;
        if ($p_ago1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_ago1 as $p_ago2) {
                $p_ago = $p_ago + $p_ago2['SUM(prima_com)'];
                $a_ago = $p_ago2['YEAR(f_pago_prima)'];
            }
        }
        $p_sep1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '09');
        $p_sep = 0;
        $a_sep = 0;
        if ($p_sep1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_sep1 as $p_sep2) {
                $p_sep = $p_sep + $p_sep2['SUM(prima_com)'];
                $a_sep = $p_sep2['YEAR(f_pago_prima)'];
            }
        }
        $p_oct1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '10');
        $p_oct = 0;
        $a_oct = 0;
        if ($p_oct1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_oct1 as $p_oct2) {
                $p_oct = $p_oct + $p_oct2['SUM(prima_com)'];
                $a_oct = $p_oct2['YEAR(f_pago_prima)'];
            }
        }
        $p_nov1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '11');
        $p_nov = 0;
        $a_nov = 0;
        if ($p_nov1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_nov1 as $p_nov2) {
                $p_nov = $p_nov + $p_nov2['SUM(prima_com)'];
                $a_nov = $p_nov2['YEAR(f_pago_prima)'];
            }
        }
        $p_dic1 = $obj->get_prima_cob_d($polizas[$a]['id_poliza'], '12');
        $p_dic = 0;
        $a_dic = 0;
        if ($p_dic1[0]['SUM(prima_com)'] != 0) {
            foreach ($p_dic1 as $p_dic2) {
                $p_dic = $p_dic + $p_dic2['SUM(prima_com)'];
                $a_dic = $p_dic2['YEAR(f_pago_prima)'];
            }
        }

        $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
        $p_difC = ($polizas[$a]['prima'] - $p_t);

        if ($p_t == 0) {
            $p_enero[] = $p_ene;
            $a_enero[] = $a_ene;
            $p_febrero[] = $p_feb;
            $a_febrero[] = $a_feb;
            $p_marzo[] = $p_mar;
            $a_marzo[] = $a_mar;
            $p_abril[] = $p_abr;
            $a_abril[] = $a_abr;
            $p_mayo[] = $p_may;
            $a_mayo[] = $a_may;
            $p_junio[] = $p_jun;
            $a_junio[] = $a_jun;
            $p_julio[] = $p_jul;
            $a_julio[] = $a_jul;
            $p_agosto[] = $p_ago;
            $a_agosto[] = $a_ago;
            $p_septiempre[] = $p_sep;
            $a_septiempre[] = $a_sep;
            $p_octubre[] = $p_oct;
            $a_octubre[] = $a_oct;
            $p_noviembre[] = $p_nov;
            $a_noviembre[] = $a_nov;
            $p_diciembre[] = $p_dic;
            $a_diciembre[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;

            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivo[] = $ejecutivo_data[0]['nombre'];

            $cod_poliza[] = $polizas[$a]['cod_poliza'];
            $ciente[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesde[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomcia[] = $polizas[$a]['nomcia'];
            $nramo[] = $polizas[$a]['nramo'];
            $prima_s[] = $polizas[$a]['prima'];
            $p_tt[] = $p_t;
            $p_dif[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_poliza[] = $polizas[$a]['f_hastapoliza'];

            $idpoliza[] = $polizas[$a]['id_poliza'];

            $tool[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas'] . ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }

        if ($p_t != 0 && $p_difC > 0) {
            $p_eneroa[] = $p_ene;
            $a_eneroa[] = $a_ene;
            $p_febreroa[] = $p_feb;
            $a_febreroa[] = $a_feb;
            $p_marzoa[] = $p_mar;
            $a_marzoa[] = $a_mar;
            $p_abrila[] = $p_abr;
            $a_abrila[] = $a_abr;
            $p_mayoa[] = $p_may;
            $a_mayoa[] = $a_may;
            $p_junioa[] = $p_jun;
            $a_junioa[] = $a_jun;
            $p_julioa[] = $p_jul;
            $a_julioa[] = $a_jul;
            $p_agostoa[] = $p_ago;
            $a_agostoa[] = $a_ago;
            $p_septiemprea[] = $p_sep;
            $a_septiemprea[] = $a_sep;
            $p_octubrea[] = $p_oct;
            $a_octubrea[] = $a_oct;
            $p_noviembrea[] = $p_nov;
            $a_noviembrea[] = $a_nov;
            $p_diciembrea[] = $p_dic;
            $a_diciembrea[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivoa[] = $ejecutivo_data[0]['nombre'];

            $cod_polizaa[] = $polizas[$a]['cod_poliza'];
            $cientea[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesdea[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomciaa[] = $polizas[$a]['nomcia'];
            $nramoa[] = $polizas[$a]['nramo'];
            $prima_sa[] = $polizas[$a]['prima'];
            $p_tta[] = $p_t;
            $p_difa[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_polizaa[] = $polizas[$a]['f_hastapoliza'];
            $idpolizaa[] = $polizas[$a]['id_poliza'];

            $toola[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas'] . ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }

        if ($p_t != 0 && $p_difC == 0) {
            $p_enerob[] = $p_ene;
            $a_enerob[] = $a_ene;
            $p_febrerob[] = $p_feb;
            $a_febrerob[] = $a_feb;
            $p_marzob[] = $p_mar;
            $a_marzob[] = $a_mar;
            $p_abrilb[] = $p_abr;
            $a_abrilb[] = $a_abr;
            $p_mayob[] = $p_may;
            $a_mayob[] = $a_may;
            $p_juniob[] = $p_jun;
            $a_juniob[] = $a_jun;
            $p_juliob[] = $p_jul;
            $a_juliob[] = $a_jul;
            $p_agostob[] = $p_ago;
            $a_agostob[] = $a_ago;
            $p_septiempreb[] = $p_sep;
            $a_septiempreb[] = $a_sep;
            $p_octubreb[] = $p_oct;
            $a_octubreb[] = $a_oct;
            $p_noviembreb[] = $p_nov;
            $a_noviembreb[] = $a_nov;
            $p_diciembreb[] = $p_dic;
            $a_diciembreb[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivob[] = $ejecutivo_data[0]['nombre'];

            $cod_polizab[] = $polizas[$a]['cod_poliza'];
            $cienteb[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesdeb[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomciab[] = $polizas[$a]['nomcia'];
            $nramob[] = $polizas[$a]['nramo'];
            $prima_sb[] = $polizas[$a]['prima'];
            $p_ttb[] = $p_t;
            $p_difb[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_polizab[] = $polizas[$a]['f_hastapoliza'];
            $idpolizab[] = $polizas[$a]['id_poliza'];

            $toolb[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas'] . ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }

        if ($p_t != 0 && $p_difC < 0) {
            $p_eneroc[] = $p_ene;
            $a_eneroc[] = $a_ene;
            $p_febreroc[] = $p_feb;
            $a_febreroc[] = $a_feb;
            $p_marzoc[] = $p_mar;
            $a_marzoc[] = $a_mar;
            $p_abrilc[] = $p_abr;
            $a_abrilc[] = $a_abr;
            $p_mayoc[] = $p_may;
            $a_mayoc[] = $a_may;
            $p_junioc[] = $p_jun;
            $a_junioc[] = $a_jun;
            $p_julioc[] = $p_jul;
            $a_julioc[] = $a_jul;
            $p_agostoc[] = $p_ago;
            $a_agostoc[] = $a_ago;
            $p_septiemprec[] = $p_sep;
            $a_septiemprec[] = $a_sep;
            $p_octubrec[] = $p_oct;
            $a_octubrec[] = $a_oct;
            $p_noviembrec[] = $p_nov;
            $a_noviembrec[] = $a_nov;
            $p_diciembrec[] = $p_dic;
            $a_diciembrec[] = $a_dic;

            $p_t = $p_ene + $p_feb + $p_mar + $p_abr + $p_may + $p_jun + $p_jul + $p_ago + $p_sep + $p_oct + $p_nov + $p_dic;
            $totalprima = $totalprima + $polizas[$a]['prima'];

            // Asesor
            $ejecutivo_data = $obj->get_ejecutivo_by_cod($polizas[$a]['codvend']);
            $ejecutivoc[] = $ejecutivo_data[0]['nombre'];

            $cod_polizac[] = $polizas[$a]['cod_poliza'];
            $cientec[] = $polizas[$a]['nombre_t'] . " " . $polizas[$a]['apellido_t'];
            $newDesdec[] = date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza']));
            $nomciac[] = $polizas[$a]['nomcia'];
            $nramoc[] = $polizas[$a]['nramo'];
            $prima_sc[] = $polizas[$a]['prima'];
            $p_ttc[] = $p_t;
            $p_difc[] = ($polizas[$a]['prima'] - $p_t);

            $f_hasta_polizac[] = $polizas[$a]['f_hastapoliza'];
            $idpolizac[] = $polizas[$a]['id_poliza'];

            $toolc[] = 'Fecha Desde Seguro: ' . date("d/m/Y", strtotime($polizas[$a]['f_desdepoliza'])) . ' | Cía: ' . $polizas[$a]['nomcia'] . ' | Ramo: ' . $polizas[$a]['nramo'] . ' | Nº de Cuotas: ' . $polizas[$a]['ncuotas'] . ' | Ejecutivo: ' . $ejecutivo_data[0]['nombre'];
        }
    }

    if (isset($p_dif)) {
        arsort($p_dif, SORT_NUMERIC);
        foreach ($p_dif as $key => $value) {
            $cod_poliza1[] = $cod_poliza[$key];
            $ciente1[] = $ciente[$key];
            $newDesde1[] = $newDesde[$key];
            $nomcia1[] = $nomcia[$key];
            $nramo1[] = $nramo[$key];
            $prima_s1[] = $prima_s[$key];
            $p_tt1[] = $p_tt[$key];
            $tool1[] = $tool[$key];
            $p_dif1[] = $value;
            $ejecutivo1[] = $ejecutivo[$key];

            $p_enero1[] = $p_enero[$key];
            $a_enero1[] = $a_enero[$key];
            $p_febrero1[] = $p_febrero[$key];
            $a_febrero1[] = $a_febrero[$key];
            $p_marzo1[] = $p_marzo[$key];
            $a_marzo1[] = $a_marzo[$key];
            $p_abril1[] = $p_abril[$key];
            $a_abril1[] = $a_abril[$key];
            $p_mayo1[] = $p_mayo[$key];
            $a_mayo1[] = $a_mayo[$key];
            $p_junio1[] = $p_junio[$key];
            $a_junio1[] = $a_junio[$key];
            $p_julio1[] = $p_julio[$key];
            $a_julio1[] = $a_julio[$key];
            $p_agosto1[] = $p_agosto[$key];
            $a_agosto1[] = $a_agosto[$key];
            $p_septiempre1[] = $p_septiempre[$key];
            $a_septiempre1[] = $a_septiempre[$key];
            $p_octubre1[] = $p_octubre[$key];
            $a_octubre1[] = $a_octubre[$key];
            $p_noviembre1[] = $p_noviembre[$key];
            $a_noviembre1[] = $a_noviembre[$key];
            $p_diciembre1[] = $p_diciembre[$key];
            $a_diciembre1[] = $a_diciembre[$key];

            $f_hasta_poliza1[] = $f_hasta_poliza[$key];
            $idpoliza1[] = $idpoliza[$key];
        }
    }

    if (isset($p_difa)) {
        arsort($p_difa, SORT_NUMERIC);
        foreach ($p_difa as $key => $value) {
            $cod_poliza1a[] = $cod_polizaa[$key];
            $ciente1a[] = $cientea[$key];
            $newDesde1a[] = $newDesdea[$key];
            $nomcia1a[] = $nomciaa[$key];
            $nramo1a[] = $nramoa[$key];
            $prima_s1a[] = $prima_sa[$key];
            $p_tt1a[] = $p_tta[$key];
            $tool1a[] = $toola[$key];
            $p_dif1a[] = $value;
            $ejecutivo1a[] = $ejecutivoa[$key];

            $p_enero1a[] = $p_eneroa[$key];
            $a_enero1a[] = $a_eneroa[$key];
            $p_febrero1a[] = $p_febreroa[$key];
            $a_febrero1a[] = $a_febreroa[$key];
            $p_marzo1a[] = $p_marzoa[$key];
            $a_marzo1a[] = $a_marzoa[$key];
            $p_abril1a[] = $p_abrila[$key];
            $a_abril1a[] = $a_abrila[$key];
            $p_mayo1a[] = $p_mayoa[$key];
            $a_mayo1a[] = $a_mayoa[$key];
            $p_junio1a[] = $p_junioa[$key];
            $a_junio1a[] = $a_junioa[$key];
            $p_julio1a[] = $p_julioa[$key];
            $a_julio1a[] = $a_julioa[$key];
            $p_agosto1a[] = $p_agostoa[$key];
            $a_agosto1a[] = $a_agostoa[$key];
            $p_septiempre1a[] = $p_septiemprea[$key];
            $a_septiempre1a[] = $a_septiemprea[$key];
            $p_octubre1a[] = $p_octubrea[$key];
            $a_octubre1a[] = $a_octubrea[$key];
            $p_noviembre1a[] = $p_noviembrea[$key];
            $a_noviembre1a[] = $a_noviembrea[$key];
            $p_diciembre1a[] = $p_diciembrea[$key];
            $a_diciembre1a[] = $a_diciembrea[$key];

            $f_hasta_poliza1a[] = $f_hasta_polizaa[$key];
            $idpoliza1a[] = $idpolizaa[$key];
        }
    }

    if (isset($p_difb)) {
        arsort($p_difb, SORT_NUMERIC);
        foreach ($p_difb as $key => $value) {
            $cod_poliza1b[] = $cod_polizab[$key];
            $ciente1b[] = $cienteb[$key];
            $newDesde1b[] = $newDesdeb[$key];
            $nomcia1b[] = $nomciab[$key];
            $nramo1b[] = $nramob[$key];
            $prima_s1b[] = $prima_sb[$key];
            $p_tt1b[] = $p_ttb[$key];
            $tool1b[] = $toolb[$key];
            $p_dif1b[] = $value;
            $ejecutivo1b[] = $ejecutivob[$key];

            $p_enero1b[] = $p_enerob[$key];
            $a_enero1b[] = $a_enerob[$key];
            $p_febrero1b[] = $p_febrerob[$key];
            $a_febrero1b[] = $a_febrerob[$key];
            $p_marzo1b[] = $p_marzob[$key];
            $a_marzo1b[] = $a_marzob[$key];
            $p_abril1b[] = $p_abrilb[$key];
            $a_abril1b[] = $a_abrilb[$key];
            $p_mayo1b[] = $p_mayob[$key];
            $a_mayo1b[] = $a_mayob[$key];
            $p_junio1b[] = $p_juniob[$key];
            $a_junio1b[] = $a_juniob[$key];
            $p_julio1b[] = $p_juliob[$key];
            $a_julio1b[] = $a_juliob[$key];
            $p_agosto1b[] = $p_agostob[$key];
            $a_agosto1b[] = $a_agostob[$key];
            $p_septiempre1b[] = $p_septiempreb[$key];
            $a_septiempre1b[] = $a_septiempreb[$key];
            $p_octubre1b[] = $p_octubreb[$key];
            $a_octubre1b[] = $a_octubreb[$key];
            $p_noviembre1b[] = $p_noviembreb[$key];
            $a_noviembre1b[] = $a_noviembreb[$key];
            $p_diciembre1b[] = $p_diciembreb[$key];
            $a_diciembre1b[] = $a_diciembreb[$key];

            $f_hasta_poliza1b[] = $f_hasta_polizab[$key];
            $idpoliza1b[] = $idpolizab[$key];
        }
    }

    if (isset($p_difc)) {
        arsort($p_difc, SORT_NUMERIC);
        foreach ($p_difc as $key => $value) {
            $cod_poliza1c[] = $cod_polizac[$key];
            $ciente1c[] = $cientec[$key];
            $newDesde1c[] = $newDesdec[$key];
            $nomcia1c[] = $nomciac[$key];
            $nramo1c[] = $nramoc[$key];
            $prima_s1c[] = $prima_sc[$key];
            $p_tt1c[] = $p_ttc[$key];
            $tool1c[] = $toolc[$key];
            $p_dif1c[] = $value;
            $ejecutivo1c[] = $ejecutivoc[$key];

            $p_enero1c[] = $p_eneroc[$key];
            $a_enero1c[] = $a_eneroc[$key];
            $p_febrero1c[] = $p_febreroc[$key];
            $a_febrero1c[] = $a_febreroc[$key];
            $p_marzo1c[] = $p_marzoc[$key];
            $a_marzo1c[] = $a_marzoc[$key];
            $p_abril1c[] = $p_abrilc[$key];
            $a_abril1c[] = $a_abrilc[$key];
            $p_mayo1c[] = $p_mayoc[$key];
            $a_mayo1c[] = $a_mayoc[$key];
            $p_junio1c[] = $p_junioc[$key];
            $a_junio1c[] = $a_junioc[$key];
            $p_julio1c[] = $p_julioc[$key];
            $a_julio1c[] = $a_julioc[$key];
            $p_agosto1c[] = $p_agostoc[$key];
            $a_agosto1c[] = $a_agostoc[$key];
            $p_septiempre1c[] = $p_septiemprec[$key];
            $a_septiempre1c[] = $a_septiemprec[$key];
            $p_octubre1c[] = $p_octubrec[$key];
            $a_octubre1c[] = $a_octubrec[$key];
            $p_noviembre1c[] = $p_noviembrec[$key];
            $a_noviembre1c[] = $a_noviembrec[$key];
            $p_diciembre1c[] = $p_diciembrec[$key];
            $a_diciembre1c[] = $a_diciembrec[$key];

            $f_hasta_poliza1c[] = $f_hasta_polizac[$key];
            $idpoliza1c[] = $idpolizac[$key];
        }
    }

    unset($p_dif, $nomcia, $cod_poliza, $ciente, $newDesde, $nramo, $prima_s, $p_tt, $tool, $p_enero, $p_febrero, $p_marzo, $p_abril, $p_mayo, $p_junio, $p_julio, $p_agosto, $p_septiempre, $p_octubre, $p_noviembre, $p_diciembre, $f_hasta_poliza, $idpoliza, $a_enero, $a_febrero, $a_marzo, $a_abril, $a_mayo, $a_junio, $a_julio, $a_agosto, $a_septiempre, $a_octubre, $a_noviembre, $a_diciembre, $ejecutivo);

    unset($p_difa, $nomciaa, $cod_polizaa, $cientea, $newDesdea, $nramoa, $prima_sa, $p_tta, $toola, $p_eneroa, $p_febreroa, $p_marzoa, $p_abrila, $p_mayoa, $p_junioa, $p_julioa, $p_agostoa, $p_septiemprea, $p_octubrea, $p_noviembrea, $p_diciembrea, $f_hasta_polizaa, $idpolizaa, $a_eneroa, $a_febreroa, $a_marzoa, $a_abrila, $a_mayoa, $a_junioa, $a_julioa, $a_agostoa, $a_septiemprea, $a_octubrea, $a_noviembrea, $a_diciembrea, $ejecutivoa);

    unset($p_difb, $nomciab, $cod_polizab, $cienteb, $newDesdeb, $nramob, $prima_sb, $p_ttb, $toolb, $p_enerob, $p_febrerob, $p_marzob, $p_abrilb, $p_mayob, $p_juniob, $p_juliob, $p_agostob, $p_septiempreb, $p_octubreb, $p_noviembreb, $p_diciembreb, $f_hasta_polizab, $idpolizab, $a_enerob, $a_febrerob, $a_marzob, $a_abrilb, $a_mayob, $a_juniob, $a_juliob, $a_agostob, $a_septiempreb, $a_octubreb, $a_noviembreb, $a_diciembreb, $ejecutivob);

    unset($p_difc, $nomciac, $cod_polizac, $cientec, $newDesdec, $nramoc, $prima_sc, $p_ttc, $toolc, $p_eneroc, $p_febreroc, $p_marzoc, $p_abrilc, $p_mayoc, $p_junioc, $p_julioc, $p_agostoc, $p_septiemprec, $p_octubrec, $p_noviembrec, $p_diciembrec, $f_hasta_polizac, $idpolizac, $a_eneroc, $a_febreroc, $a_marzoc, $a_abrilc, $a_mayoc, $a_junioc, $a_julioc, $a_agostoc, $a_septiemprec, $a_octubrec, $a_noviembrec, $a_diciembrec, $ejecutivoc);
}


//--- poliza_uv.php
if ($pag == 'poliza_uv') {
    $mes = $_GET["mes"];
    $anio = $_GET["anio"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo );
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia );
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta );
    $tipo_cuenta = unserialize($tipo_cuenta);

    $polizasC = $obj->get_cant_poliza_total_by_filtro_utilidad_v($mes, $anio, $cia, $tipo_cuenta, $ramo);

    $polizas = $obj->get_poliza_total_by_filtro_utilidad_v($polizasC[0]['id_poliza']);
    

}

//--- crm/b_mensaje.php
if ($pag == 'crm/b_mensaje') {
    $asesor = $obj->get_ejecutivo();
    $cia = $obj->get_element('dcia', 'nomcia');
    $ramo = $obj->get_element('dramo', 'nramo');
    $t_poliza = $obj->get_element('tipo_poliza', 'tipo_poliza');
}

//--- crm/mensaje_prog.php
if ($pag == 'crm/mensaje_prog') {
    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
    if (isset($_GET["cia"]) != null) {
        $cia = $_GET["cia"];
        for ($i=0; $i < sizeof($cia); $i++) { 
            $cias[$i] = $obj->get_element_by_id('dcia','idcia',$cia[$i]);
        }
    } else {
        $cia = '';
    }
    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';
    if (isset($_GET["ramo"]) != null) {
        $ramo = $_GET["ramo"];
        for ($i=0; $i < sizeof($ramo); $i++) { 
            $ramos[$i] = $obj->get_element_by_id('dramo','cod_ramo',$ramo[$i]);
        }
    } else {
        $ramo = '';
    }
    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';
    if (isset($_GET["asesor"]) != null) {
        $asesor = $_GET["asesor"];
        for ($i=0; $i < sizeof($asesor); $i++) { 
            $asesores[$i] = $obj->get_ejecutivo_by_cod($asesor[$i]);
        }
    } else {
        $asesor = '';
    }
    $t_poliza = (isset($_GET["t_poliza"]) != null) ? $_GET["t_poliza"] : '';

    $v_poliza = $_GET["v_poliza"];

    $titulares = $obj->get_birthdays_filter($asesor, $cia, $ramo, $t_poliza, $v_poliza);

    if ($titulares == 0) {
        header("Location: b_mensaje.php?m=2");
    }

    $titulares_noB = $obj->get_no_birthdays_filter($asesor, $cia, $ramo, $t_poliza);

    if (!$cia == '') {
        $cia_para_enviar_via_url = serialize($cia);
        $ciaEnv = urlencode($cia_para_enviar_via_url);
    } else {
        $ciaEnv = '';
    }

    if (!$ramo == '') {
        $ramo_para_enviar_via_url = serialize($ramo);
        $ramoEnv = urlencode($ramo_para_enviar_via_url);
    } else {
        $ramoEnv = '';
    }

    if (!$t_poliza == '') {
        $t_poliza_para_enviar_via_url = serialize($t_poliza);
        $t_polizaEnv = urlencode($t_poliza_para_enviar_via_url);
    } else {
        $t_polizaEnv = '';
    }

    if (!$asesor == '') {
        $asesor_para_enviar_via_url = serialize($asesor);
        $asesorEnv = urlencode($asesor_para_enviar_via_url);
    } else {
        $asesorEnv = '';
    }
}

//--- crm/prom_prog.php
if ($pag == 'crm/prom_prog') {
    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
    if (isset($_GET["cia"]) != null) {
        $cia = $_GET["cia"];
        for ($i=0; $i < sizeof($cia); $i++) { 
            $cias[$i] = $obj->get_element_by_id('dcia','idcia',$cia[$i]);
        }
    } else {
        $cia = '';
    }
    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';
    if (isset($_GET["ramo"]) != null) {
        $ramo = $_GET["ramo"];
        for ($i=0; $i < sizeof($ramo); $i++) { 
            $ramos[$i] = $obj->get_element_by_id('dramo','cod_ramo',$ramo[$i]);
        }
    } else {
        $ramo = '';
    }
    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';
    if (isset($_GET["asesor"]) != null) {
        $asesor = $_GET["asesor"];
        for ($i=0; $i < sizeof($asesor); $i++) { 
            $asesores[$i] = $obj->get_ejecutivo_by_cod($asesor[$i]);
        }
    } else {
        $asesor = '';
    }
    $t_poliza = (isset($_GET["t_poliza"]) != null) ? $_GET["t_poliza"] : '';

    $v_poliza = $_GET["v_poliza"];

    $titulares = $obj->get_clientes_prom($asesor, $cia, $ramo, $t_poliza, $v_poliza);

    if ($titulares == 0) {
        header("Location: b_prom.php?m=2");
    }

    if (!$cia == '') {
        $cia_para_enviar_via_url = serialize($cia);
        $ciaEnv = urlencode($cia_para_enviar_via_url);
    } else {
        $ciaEnv = '';
    }

    if (!$ramo == '') {
        $ramo_para_enviar_via_url = serialize($ramo);
        $ramoEnv = urlencode($ramo_para_enviar_via_url);
    } else {
        $ramoEnv = '';
    }

    if (!$t_poliza == '') {
        $t_poliza_para_enviar_via_url = serialize($t_poliza);
        $t_polizaEnv = urlencode($t_poliza_para_enviar_via_url);
    } else {
        $t_polizaEnv = '';
    }

    if (!$asesor == '') {
        $asesor_para_enviar_via_url = serialize($asesor);
        $asesorEnv = urlencode($asesor_para_enviar_via_url);
    } else {
        $asesorEnv = '';
    }
}

//--- crm/v_mensaje.php
if ($pag == 'crm/v_mensaje') {
    $id_mensaje_c1 = $_GET["id_mensaje_c1"];

    $mensaje_c1 = $obj->get_element_by_id('mensaje_c1', 'id_mensaje_c1', $id_mensaje_c1);

    $id_titulares = $obj->get_element_by_id('mensaje_c2', 'id_mensaje_c1', $id_mensaje_c1);

    if ($id_titulares == 0) {
        header("Location: v_mensaje_prog.php?m=2");
    }

    for ($i=0; $i < sizeof($id_titulares); $i++) { 
        $titulares[$i] = $obj->get_element_by_id('titular', 'id_titular', $id_titulares[$i]['id_titular']);
    }
}

//--- crm/v_prom.php
if ($pag == 'crm/v_prom') {
    $id_mensaje_p1 = $_GET["id_mensaje_p1"];

    $mensaje_p1 = $obj->get_element_by_id('mensaje_p1', 'id_mensaje_p1', $id_mensaje_p1);

    $id_titulares = $obj->get_element_by_id('mensaje_p2', 'id_mensaje_p1', $id_mensaje_p1);

    if ($id_titulares == 0) {
        header("Location: v_prom_prog.php?m=2");
    }

    for ($i=0; $i < sizeof($id_titulares); $i++) { 
        $titulares[$i] = $obj->get_element_by_id('titular', 'id_titular', $id_titulares[$i]['id_titular']);
    }
}

//--- crm/bienvenida/b_nueva.php
if ($pag == 'crm/bienvenida/b_nueva') {
    $asesor = $obj->get_ejecutivo();
    $cia = $obj->get_element('dcia', 'nomcia');
    $ramo = $obj->get_element('dramo', 'nramo');
    

    $filtro_a = $obj->getFiltroCarta_asesor('carta_new');
    $filtro_r = $obj->getFiltroCarta_ramo('carta_new');
    $filtro_c = $obj->getFiltroCarta_cia('carta_new');
}

//--- crm/bienvenida/nueva_prog.php
if ($pag == 'crm/bienvenida/nueva_prog') {
    $obj->borrarTablaCarta('carta_new');

    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
    if (isset($_GET["cia"]) != null) {
        $cia = $_GET["cia"];
        for ($i=0; $i < sizeof($cia); $i++) { 
            $obj->agregarFiltroCartaN('-', 0, $cia[$i]);
        }
    } else {
        $cia = '';
    }
    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';
    if (isset($_GET["ramo"]) != null) {
        $ramo = $_GET["ramo"];
        for ($i=0; $i < sizeof($ramo); $i++) { 
            $obj->agregarFiltroCartaN('-', $ramo[$i], 0);
        }
    } else {
        $ramo = '';
    }
    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';
    if (isset($_GET["asesor"]) != null) {
        $asesor = $_GET["asesor"];
        for ($i=0; $i < sizeof($asesor); $i++) { 
            $obj->agregarFiltroCartaN($asesor[$i], 0, 0);
        }
    } else {
        $asesor = '';
    }
}

//--- crm/bienvenida/b_renov.php
if ($pag == 'crm/bienvenida/b_renov') {
    $asesor = $obj->get_ejecutivo();
    $cia = $obj->get_element('dcia', 'nomcia');
    $ramo = $obj->get_element('dramo', 'nramo');
    

    $filtro_a = $obj->getFiltroCarta_asesor('carta_renov');
    $filtro_r = $obj->getFiltroCarta_ramo('carta_renov');
    $filtro_c = $obj->getFiltroCarta_cia('carta_renov');
}

//--- crm/bienvenida/renov_prog.php
if ($pag == 'crm/bienvenida/renov_prog') {
    $obj->borrarTablaCarta('carta_renov');

    $cia = (isset($_GET["cia"]) != null) ? $_GET["cia"] : '';
    if (isset($_GET["cia"]) != null) {
        $cia = $_GET["cia"];
        for ($i=0; $i < sizeof($cia); $i++) { 
            $obj->agregarFiltroCartaR('-', 0, $cia[$i]);
        }
    } else {
        $cia = '';
    }
    $ramo = (isset($_GET["ramo"]) != null) ? $_GET["ramo"] : '';
    if (isset($_GET["ramo"]) != null) {
        $ramo = $_GET["ramo"];
        for ($i=0; $i < sizeof($ramo); $i++) { 
            $obj->agregarFiltroCartaR('-', $ramo[$i], 0);
        }
    } else {
        $ramo = '';
    }
    $asesor = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';
    if (isset($_GET["asesor"]) != null) {
        $asesor = $_GET["asesor"];
        for ($i=0; $i < sizeof($asesor); $i++) { 
            $obj->agregarFiltroCartaR($asesor[$i], 0, 0);
        }
    } else {
        $asesor = '';
    }
}

//--- crm/bienvenida/edit_carta_new.php
if ($pag == 'crm/bienvenida/edit_carta_new') {
    $body =  $_GET["body"];
    $escribame =  $_GET["escribame"];
    $direccion =  $_GET["direccion"];

    $obj->updateCartaNew($body, $escribame, $direccion);
}

//--- crm/bienvenida/edit_carta_renov.php
if ($pag == 'crm/bienvenida/edit_carta_renov') {
    $body =  $_GET["body"];
    $escribame =  $_GET["escribame"];
    $direccion =  $_GET["direccion"];

    $obj->updateCartaRenov($body, $escribame, $direccion);
}

//--- gc/gc_cargada.php
if ($pag == 'gc/gc_cargada') {
    $asesor_g = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';

    $mes = $_GET['mes'];
    $desde = $_GET['anio'] . "-" . $_GET['mes'] . "-01";
    $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-31";

    if ($_GET['mes'] == '02') {
        $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-28";
    }
    if ($_GET['mes'] == '04' || $_GET['mes'] == '06' || $_GET['mes'] == '09' || $_GET['mes'] == '11') {
        $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-30";
    }

    if ($mes == null) {
        $mesD = 01;
        $mesH = 12;
        $desde = $_GET['anio'] . "-" . $mesD . "-01";
        $hasta = $_GET['anio'] . "-" . $mesH . "-31";
    }

    $anio = $_GET['anio'];
    if ($anio == null) {
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_pago_gc', 'rep_com');
        $desde = $fechaMin[0]['MIN(f_pago_gc)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_pago_gc', 'rep_com');
        $hasta = $fechaMax[0]['MAX(f_pago_gc)'];
    }

    $distinct_a = $obj->get_gc_exist_distinct_a($desde, $hasta, $asesor_g);
    $distinct_r = $obj->get_gc_exist_distinct_r($desde, $hasta, $asesor_g);
    $distinct_p = $obj->get_gc_exist_distinct_p($desde, $hasta, $asesor_g);
    if($distinct_a == 0 && $distinct_r == 0 && $distinct_p == 0) {
        header('Location: b_existente.php?m=2');
    }


    $asesorB = $asesor_g;

    if (!$asesor_g == '') {
        $asesor_para_enviar_via_url = serialize($asesor_g);
        $asesorEnv = urlencode($asesor_para_enviar_via_url);
    } else {
        $asesorEnv = '';
    }
}

//--- gc/gc_generada.php
if ($pag == 'gc/gc_generada') {
    $asesor_g = (isset($_GET["asesor"]) != null) ? $_GET["asesor"] : '';

    $mes = $_GET['mes'];
    $desde = $_GET['anio'] . "-" . $_GET['mes'] . "-01";
    $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-31";

    if ($_GET['mes'] == '02') {
        $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-28";
    }
    if ($_GET['mes'] == '04' || $_GET['mes'] == '06' || $_GET['mes'] == '09' || $_GET['mes'] == '11') {
        $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-30";
    }

    if ($mes == null) {
        $mesD = 01;
        $mesH = 12;
        $desde = $_GET['anio'] . "-" . $mesD . "-01";
        $hasta = $_GET['anio'] . "-" . $mesH . "-31";
    }

    $anio = $_GET['anio'];
    if ($anio == null) {
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_pago_gc', 'rep_com');
        $desde = $fechaMin[0]['MIN(f_pago_gc)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_pago_gc', 'rep_com');
        $hasta = $fechaMax[0]['MAX(f_pago_gc)'];
    }

    $distinct_a = $obj->get_gc_carg_distinct_a($desde, $hasta, $asesor_g);
    $distinct_r = $obj->get_gc_carg_distinct_r($desde, $hasta, $asesor_g);
    $distinct_p = $obj->get_gc_carg_distinct_p($desde, $hasta, $asesor_g);
    if($distinct_a == 0 && $distinct_r == 0 && $distinct_p == 0) {
        header('Location: b_generada.php?m=2');
    }


    $asesorB = $asesor_g;

    if (!$asesor_g == '') {
        $asesor_para_enviar_via_url = serialize($asesor_g);
        $asesorEnv = urlencode($asesor_para_enviar_via_url);
    } else {
        $asesorEnv = '';
    }
}
