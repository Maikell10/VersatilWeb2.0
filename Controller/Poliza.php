<?php

require_once dirname(__DIR__) . '../Model/Poliza.php';

$fhoy = date("Y");
$totalsuma = 0;
$totalprima = 0;
$totalpoliza = 0;

$totalprimaC = 0;
$totalcomisionC = 0;
$totalGC = 0;

$totalPrimaCom = 0;
$totalCom = 0;

$cont = 0;

$mes_arr = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

$obj = new Poliza();

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
}

//--- b_polizaT.php
if ($pag == 'b_polizaT') {
    $polizas = $obj->get_polizas_by_tarjetaV($_GET['id_tarjeta']);
}

//--- b_pendientes.php
if ($pag == 'b_pendientes') {
    $polizas = $obj->get_poliza_pendiente();
}

//--- b_f_product.php
if ($pag == 'b_f_product') {
    $fechaMin = $obj->get_fecha_min_max('MIN', 'f_poliza', 'poliza');
    $fechaMax = $obj->get_fecha_min_max('MAX', 'f_poliza', 'poliza');

    $fechaMin = date('Y', strtotime($fechaMin[0]["MIN(f_poliza)"]));
    $fechaMax = date('Y', strtotime($fechaMax[0]["MAX(f_poliza)"]));
}

//--- f_product.php
if ($pag == 'f_product') {
    $desde = $_POST['desdeP_submit'];
    $hasta = $_POST['hastaP_submit'];

    $desdeP = $_POST['desdeP'];
    $hastaP = $_POST['hastaP'];

    $polizas = $obj->get_poliza_total_by_filtro_f_product($desde, $hasta);
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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_pago_gc', 'rep_com');
        $desde = $fechaMin[0]['MIN(f_pago_gc)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_pago_gc', 'rep_com');
        $hasta = $fechaMax[0]['MAX(f_pago_gc)'];
    }

    $cia = $_POST['cia'];
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

    $comision = $obj->get_element_by_id('comision', 'id_rep_com', $_GET['id_rep_com']);

    $f_pago_gc = date("d-m-Y", strtotime($rep_com[0]['f_pago_gc']));
    $f_hasta_rep = date("d-m-Y", strtotime($rep_com[0]['f_hasta_rep']));
}
