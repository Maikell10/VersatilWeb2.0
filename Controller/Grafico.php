<?php

require_once dirname(__DIR__) . '../Model/Grafico.php';

$fhoy = date("Y");

$totals = 0;
$totalCant = 0;

$totalpa = 0;
$totalr = 0;

$totalG = 0;
$cantG = 0;

$totalpc = 0;
$totalcc = 0;
$totalgcp = 0;
$totalcantt = 0;
$totalPrimaSuscrita = 0;
$totalPrimaCobrada = 0;
$totalComisionCobrada = 0;
$totalGCPagada = 0;
$totalCant = 0;

$mesArray = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

$obj = new Grafico();

//--- Porcentaje/busqueda.php
if ($pag == 'Porcentaje/busqueda') {
    //$asesor = $obj->get_ejecutivo();
    $cia = $obj->get_distinct_element('nomcia', 'dcia');
    $ramo = $obj->get_distinct_element('nramo', 'dramo');

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
}

//--- Primas_Cobradas/busqueda.php
if ($pag == 'Primas_Cobradas/busqueda') {
    //$asesor = $obj->get_ejecutivo();
    $cia = $obj->get_distinct_element('nomcia', 'dcia');
    $ramo = $obj->get_distinct_element('nramo', 'dramo');

    $fecha_min = $obj->get_fecha_min_max('MIN', 'f_pago_prima', 'comision');
    $fecha_max = $obj->get_fecha_min_max('MAX', 'f_pago_prima', 'comision');

    //FECHA MAYORES A 2024
    $dateString = $fecha_max[0]["MAX(f_pago_prima)"];
    // Parse a textual date/datetime into a Unix timestamp
    $date = new DateTime($dateString);
    $format = 'Y';
    // Parse a textual date/datetime into a Unix timestamp
    $date = new DateTime($dateString);
    // Print it
    $fecha_max = $date->format($format);
    $fecha_min = date('Y', strtotime($fecha_min[0]["MIN(f_pago_prima)"]));
}

//--- Resumen/busqueda.php
if ($pag == 'Resumen/busqueda') {
    //$asesor = $obj->get_ejecutivo();
    $cia = $obj->get_distinct_element('nomcia', 'dcia');
    $ramo = $obj->get_distinct_element('nramo', 'dramo');

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
}

//--- Porcentaje/ramo.php
if ($pag == 'Porcentaje/ramo') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------


    if ($permiso != 3) {
        $ramo = $obj->get_distinct_element_ramo($desde, $hasta, $cia, $tipo_cuenta);
        if ($ramo == 0) {
            header("Location: busqueda_ramo.php?m=2");
        }
        $ramoArray[sizeof($ramo)] = null;
        $sumatotalRamo[sizeof($ramo)] = null;
        $cantArray[sizeof($ramo)] = null;

        for ($i = 0; $i < sizeof($ramo); $i++) {
            //if ($ramo[$i]['nramo'] != '-') {
            $ramoPoliza = $obj->get_poliza_graf_1($ramo[$i]['nramo'], $desde, $hasta, $cia, $tipo_cuenta);

            $cantArray[$i] = sizeof($ramoPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($ramoPoliza); $a++) {
                $sumasegurada = $sumasegurada + $ramoPoliza[$a]['prima'];
            }
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalRamo[$i] = $sumasegurada;
            $ramoArray[$i] = $ramo[$i]['nramo'];
            //}
        }
    }
    if ($permiso == 3) {
        $ramo = $obj->get_distinct_element_ramo_by_user($desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
        if ($ramo == 0) {
            header("Location: busqueda_ramo.php?m=2");
        }

        $ramoArray[sizeof($ramo)] = null;
        $sumatotalRamo[sizeof($ramo)] = null;
        $cantArray[sizeof($ramo)] = null;

        for ($i = 0; $i < sizeof($ramo); $i++) {
            $ramoPoliza = $obj->get_poliza_graf_1_by_user($ramo[$i]['nramo'], $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);

            $cantArray[$i] = sizeof($ramoPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($ramoPoliza); $a++) {
                $sumasegurada = $sumasegurada + $ramoPoliza[$a]['prima'];
            }
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalRamo[$i] = $sumasegurada;
            $ramoArray[$i] = $ramo[$i]['nramo'];
        }
    }

    asort($sumatotalRamo, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalRamo as $key => $value) {
        $x[count($x)] = $key;
    }

    $contador = (sizeof($ramo) > 10) ? sizeof($ramo) - 10 : 1;
}

//--- Porcentaje/tipo_poliza.php
if ($pag == 'Porcentaje/tipo_poliza') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------


    if ($permiso != 3) {
        $tpoliza = $obj->get_distinct_element_tpoliza($desde, $hasta, $cia, $ramo, $tipo_cuenta);

        if ($tpoliza == 0) {
            header("Location: busqueda_tipo_poliza.php?m=2");
        }

        $tpolizaArray[sizeof($tpoliza)] = null;
        $sumatotalTpoliza[sizeof($tpoliza)] = null;
        $cantArray[sizeof($tpoliza)] = null;

        for ($i = 0; $i < sizeof($tpoliza); $i++) {
            $tpolizaPoliza = $obj->get_poliza_graf_2($tpoliza[$i]['tipo_poliza'], $ramo, $desde, $hasta, $cia, $tipo_cuenta);

            $cantArray[$i] = sizeof($tpolizaPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($tpolizaPoliza); $a++) {
                $sumasegurada = $sumasegurada + $tpolizaPoliza[$a]['prima'];
            }
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalTpoliza[$i] = $sumasegurada;
            $tpolizaArray[$i] = $tpoliza[$i]['tipo_poliza'];
        }
    }
    if ($permiso == 3) {
        $tpoliza = $obj->get_distinct_element_tpoliza_by_user($desde, $hasta, $cia, $ramo, $tipo_cuenta, $asesor_u);

        if ($tpoliza == 0) {
            header("Location: busqueda_tipo_poliza.php?m=2");
        }

        $tpolizaArray[sizeof($tpoliza)] = null;
        $sumatotalTpoliza[sizeof($tpoliza)] = null;
        $cantArray[sizeof($tpoliza)] = null;

        for ($i = 0; $i < sizeof($tpoliza); $i++) {
            $tpolizaPoliza = $obj->get_poliza_graf_2_by_user($tpoliza[$i]['tipo_poliza'], $ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);

            $cantArray[$i] = sizeof($tpolizaPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($tpolizaPoliza); $a++) {
                $sumasegurada = $sumasegurada + $tpolizaPoliza[$a]['prima'];
            }
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalTpoliza[$i] = $sumasegurada;
            $tpolizaArray[$i] = $tpoliza[$i]['tipo_poliza'];
        }
    }

    asort($sumatotalTpoliza, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalTpoliza as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Porcentaje/cia.php
if ($pag == 'Porcentaje/cia') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------


    if ($permiso != 3) {
        $cia = $obj->get_distinct_element_cia($desde, $hasta, $ramo, $tipo_cuenta);

        if ($cia == 0) {
            header("Location: busqueda_cia.php?m=2");
        }

        $ciaArray[sizeof($cia)] = null;
        $sumatotalCia[sizeof($cia)] = null;
        $cantArray[sizeof($cia)] = null;

        for ($i = 0; $i < sizeof($cia); $i++) {
            $ciaPoliza = $obj->get_poliza_graf_3($cia[$i]['nomcia'], $ramo, $desde, $hasta, $tipo_cuenta);

            $cantArray[$i] = sizeof($ciaPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($ciaPoliza); $a++) {
                $sumasegurada = $sumasegurada + $ciaPoliza[$a]['prima'];
            }
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalCia[$i] = $sumasegurada;
            $ciaArray[$i] = $cia[$i]['nomcia'];
        }
    }
    if ($permiso == 3) {
        $cia = $obj->get_distinct_element_cia_by_user($desde, $hasta, $ramo, $tipo_cuenta, $asesor_u);

        if ($cia == 0) {
            header("Location: busqueda_cia.php?m=2");
        }

        $ciaArray[sizeof($cia)] = null;
        $sumatotalCia[sizeof($cia)] = null;
        $cantArray[sizeof($cia)] = null;

        for ($i = 0; $i < sizeof($cia); $i++) {
            $ciaPoliza = $obj->get_poliza_graf_3_by_user($cia[$i]['nomcia'], $ramo, $desde, $hasta, $tipo_cuenta, $asesor_u);

            $cantArray[$i] = sizeof($ciaPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($ciaPoliza); $a++) {
                $sumasegurada = $sumasegurada + $ciaPoliza[$a]['prima'];
            }
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalCia[$i] = $sumasegurada;
            $ciaArray[$i] = $cia[$i]['nomcia'];
        }
    }

    asort($sumatotalCia, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalCia as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : 1;
}

//--- Porcentaje/fpago.php
if ($pag == 'Porcentaje/fpago') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------


    if ($permiso != 3) {
        $fpago = $obj->get_distinct_element_fpago($desde, $hasta, $cia, $ramo, $tipo_cuenta);

        if ($fpago == 0) {
            header("Location: busqueda_fpago.php?m=2");
        }

        $fpagoArray[sizeof($fpago)] = null;
        $sumatotalFpago[sizeof($fpago)] = null;
        $cantArray[sizeof($fpago)] = null;

        for ($i = 0; $i < sizeof($fpago); $i++) {
            $fpagoPoliza = $obj->get_poliza_graf_4($fpago[$i]['fpago'], $ramo, $desde, $hasta, $cia, $tipo_cuenta);

            $cantArray[$i] = sizeof($fpagoPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($fpagoPoliza); $a++) {
                $sumasegurada = $sumasegurada + $fpagoPoliza[$a]['prima'];
            }
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalFpago[$i] = $sumasegurada;
            $fpagoArray[$i] = $fpago[$i]['fpago'];
        }
    }
    if ($permiso == 3) {
        $fpago = $obj->get_distinct_element_fpago($desde, $hasta, $cia, $ramo, $tipo_cuenta);


        if ($fpago == 0) {
            header("Location: busqueda_fpago.php?m=2");
        }

        $fpagoArray[sizeof($fpago)] = null;
        $sumatotalFpago[sizeof($fpago)] = null;
        $cantArray[sizeof($fpago)] = null;

        for ($i = 0; $i < sizeof($fpago); $i++) {
            $fpagoPoliza = $obj->get_poliza_graf_4_by_user($fpago[$i]['fpago'], $ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);

            $cantArray[$i] = sizeof($fpagoPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($fpagoPoliza); $a++) {
                $sumasegurada = $sumasegurada + $fpagoPoliza[$a]['prima'];
            }
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalFpago[$i] = $sumasegurada;
            $fpagoArray[$i] = $fpago[$i]['fpago'];
        }
    }

    asort($sumatotalFpago, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalFpago as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Porcentaje/ejecutivo.php
if ($pag == 'Porcentaje/ejecutivo') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    $ejecutivo = $obj->get_distinct_element_ejecutivo_ps($desde, $hasta, $cia, $ramo, $tipo_cuenta);

    if ($ejecutivo == 0) {
        header("Location: busqueda_ejecutivo.php?m=2");
    }

    $ejecutivoArray[sizeof($ejecutivo)] = null;
    $codEjecutivoArray[sizeof($ejecutivo)] = null;
    $sumatotalEjecutivo[sizeof($ejecutivo)] = null;
    $cantArray[sizeof($ejecutivo)] = null;

    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $ejecutivoPoliza = $obj->get_poliza_graf_prima_c_6($ejecutivo[$i]['codvend'], $ramo, $desde, $hasta, $cia, $tipo_cuenta);

        $ejecutivoArray[$i] = $ejecutivo[$i]['nombre'];
        $codEjecutivoArray[$i] = $ejecutivo[$i]['codvend'];
        $cantArray[$i] = sizeof($ejecutivoPoliza);
        $sumasegurada = 0;
        for ($a = 0; $a < sizeof($ejecutivoPoliza); $a++) {
            $sumasegurada = $sumasegurada + $ejecutivoPoliza[$a]['prima'];
        }
        $totals = $totals + $sumasegurada;
        $totalCant = $totalCant + $cantArray[$i];
        $sumatotalEjecutivo[$i] = $sumasegurada;
    }

    asort($sumatotalEjecutivo, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalEjecutivo as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($ejecutivo) > 10) ? sizeof($ejecutivo) - 10 : 1;
}

//--- Porcentaje/ramo_promedio.php
if ($pag == 'Porcentaje/ramo_promedio') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_hastapoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_hastapoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_hastapoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_hastapoliza)'];
    }

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------


    if ($permiso != 3) {
        $ramo = $obj->get_distinct_element_ramo($desde, $hasta, $cia, $tipo_cuenta);
        if ($ramo == 0) {
            header("Location: busqueda_ramo_promedio.php?m=2");
        }
        $ramoArray[sizeof($ramo)] = null;
        $sumatotalRamo[sizeof($ramo)] = null;
        $cantArray[sizeof($ramo)] = null;
        $sumatotalRamoProm[sizeof($ramo)] = null;

        for ($i = 0; $i < sizeof($ramo); $i++) {
            $ramoPoliza = $obj->get_poliza_graf_1($ramo[$i]['nramo'], $desde, $hasta, $cia, $tipo_cuenta);

            $cantArray[$i] = sizeof($ramoPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($ramoPoliza); $a++) {
                $sumasegurada = $sumasegurada + $ramoPoliza[$a]['prima'];
            }
            $totals = $totals + ($sumasegurada / $cantArray[$i]);
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalRamo[$i] = $sumasegurada;
            $ramoArray[$i] = $ramo[$i]['nramo'];
            $sumatotalRamoProm[$i] = $sumasegurada / $cantArray[$i];
        }
    }
    if ($permiso == 3) {
        $ramo = $obj->get_distinct_element_ramo_by_user($desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
        if ($ramo == 0) {
            header("Location: busqueda_ramo_promedio.php?m=2");
        }

        $ramoArray[sizeof($ramo)] = null;
        $sumatotalRamo[sizeof($ramo)] = null;
        $cantArray[sizeof($ramo)] = null;
        $sumatotalRamoProm[sizeof($ramo)] = null;

        for ($i = 0; $i < sizeof($ramo); $i++) {
            $ramoPoliza = $obj->get_poliza_graf_1_by_user($ramo[$i]['nramo'], $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);

            $cantArray[$i] = sizeof($ramoPoliza);
            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($ramoPoliza); $a++) {
                $sumasegurada = $sumasegurada + $ramoPoliza[$a]['prima'];
            }
            $totals = $totals + ($sumasegurada / $cantArray[$i]);
            $totalCant = $totalCant + $cantArray[$i];
            $sumatotalRamo[$i] = $sumasegurada;
            $ramoArray[$i] = $ramo[$i]['nramo'];
            $sumatotalRamoProm[$i] = $sumasegurada / $cantArray[$i];
        }
    }

    asort($sumatotalRamoProm, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalRamoProm as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($ramo) > 10) ? sizeof($ramo) - 10 : sizeof($ramo);
}

//--- Primas_Suscritas/prima_mes.php
if ($pag == 'Primas_Suscritas/prima_mes') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $desde = $_GET['anio'] . '-01-01';
    $hasta = ($_GET['anio']) . '-12-31';

    $mes = $obj->get_mes_prima($desde, $hasta, $cia, $ramo, $tipo_cuenta, '1');

    $ramoArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;

    $primaPorMesPA[sizeof($mes)] = null;
    $primaPorMesR[sizeof($mes)] = null;

    if ($permiso != 3) {
        for ($i = 0; $i < sizeof($mes); $i++) {
            $desde = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-31";

            $primaMes = $obj->get_poliza_grafp_2($ramo, $desde, $hasta, $cia, $tipo_cuenta);
            if ($primaMes == 0) {
                header("Location: busqueda_prima_mes.php?m=2");
            }

            $cantArray[$i] = sizeof($primaMes);
            $sumasegurada = 0;
            $sumaseguradaPA = 0;
            $sumaseguradaR = 0;
            for ($a = 0; $a < sizeof($primaMes); $a++) {
                $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];
                if ($primaMes[$a]['id_tpoliza'] == 1) {
                    $sumaseguradaPA = $sumaseguradaPA + $primaMes[$a]['prima'];
                }
                if ($primaMes[$a]['id_tpoliza'] == 2) {
                    $sumaseguradaR = $sumaseguradaR + $primaMes[$a]['prima'];
                }
            }
            $totals = $totals + $sumasegurada;
            $totalpa = $totalpa + $sumaseguradaPA;
            $totalr = $totalr + $sumaseguradaR;
            $totalCant = $totalCant + $cantArray[$i];
            $ramoArray[$i] = $primaMes[0]['cod_ramo'];
            $primaPorMes[$i] = $sumasegurada;
            $primaPorMesPA[$i] = $sumaseguradaPA;
            $primaPorMesR[$i] = $sumaseguradaR;
        }
    }
    if ($permiso == 3) {
        $mes = $obj->get_mes_prima_by_user($desde, $hasta, $cia, $ramo, $tipo_cuenta, '1', $asesor_u);
        for ($i = 0; $i < sizeof($mes); $i++) {
            $desde = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-31";

            $primaMes = $obj->get_poliza_grafp_2_by_user($ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
            
            if ($primaMes == 0) {
                header("Location: busqueda_prima_mes.php?m=2");
            }

            $cantArray[$i] = sizeof($primaMes);
            $sumasegurada = 0;
            $sumaseguradaPA = 0;
            $sumaseguradaR = 0;
            for ($a = 0; $a < sizeof($primaMes); $a++) {
                $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];
                if ($primaMes[$a]['id_tpoliza'] == 1) {
                    $sumaseguradaPA = $sumaseguradaPA + $primaMes[$a]['prima'];
                }
                if ($primaMes[$a]['id_tpoliza'] == 2) {
                    $sumaseguradaR = $sumaseguradaR + $primaMes[$a]['prima'];
                }
            }
            $totals = $totals + $sumasegurada;
            $totalpa = $totalpa + $sumaseguradaPA;
            $totalr = $totalr + $sumaseguradaR;
            $totalCant = $totalCant + $cantArray[$i];
            $ramoArray[$i] = $primaMes[0]['cod_ramo'];
            $primaPorMes[$i] = $sumasegurada;
            $primaPorMesPA[$i] = $sumaseguradaPA;
            $primaPorMesR[$i] = $sumaseguradaR;
        }
    }
}

//--- Primas_Suscritas/prima_semana.php
if ($pag == 'Primas_Suscritas/prima_semana') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $mesA = $_GET['mes'];
    $numeroConCeros = str_pad($mesA, 2, "0", STR_PAD_LEFT);

    $desde = $_GET['anio'] . '-' . $numeroConCeros . '-01';
    $hasta = $_GET['anio'] . '-' . $numeroConCeros . '-31';

    $dia_mes = $obj->get_dia_mes_prima($desde, $hasta, $cia, $ramo, $tipo_cuenta);

    $ramoArray[sizeof($dia_mes)] = null;
    $cantArray[sizeof($dia_mes)] = null;
    $primaPorMes[sizeof($dia_mes)] = null;
    $diaSemana[sizeof($dia_mes)] = null;

    if ($permiso != 3) {
        for ($i = 0; $i < sizeof($dia_mes); $i++) {
            $dia = $dia_mes[$i]['f_desdepoliza'];

            $dia1   = substr($dia, 8, 2);
            $mes1 = substr($dia, 5, 2);
            $anio1 = substr($dia, 0, 4);
            $semana = date('W',  mktime(0, 0, 0, $mes1, $dia1, $anio1));

            $primaMes = $obj->get_poliza_graf_p3($ramo, $dia, $cia, $tipo_cuenta);
            if ($primaMes == 0) {
                header("Location: busqueda_prima_semana.php?m=2");
            }

            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($primaMes); $a++) {
                $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];
            }
            $cantArray[$i] = sizeof($primaMes);
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $semanaMesArray[$i] = $semana;
            $primaPorMes[$i] = $sumasegurada;
            $diaSemana[$i] = $dia;
        }
    }
    if ($permiso == 3) {
        for ($i = 0; $i < sizeof($dia_mes); $i++) {
            $dia = $dia_mes[$i]['f_desdepoliza'];

            $dia1   = substr($dia, 8, 2);
            $mes1 = substr($dia, 5, 2);
            $anio1 = substr($dia, 0, 4);
            $semana = date('W',  mktime(0, 0, 0, $mes1, $dia1, $anio1));

            $primaMes = $obj->get_poliza_graf_p3_by_user($ramo, $dia, $cia, $tipo_cuenta, $asesor_u);
            if ($primaMes == 0) {
                header("Location: busqueda_prima_semana.php?m=2");
            }

            $sumasegurada = 0;
            for ($a = 0; $a < sizeof($primaMes); $a++) {
                $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];
            }
            $cantArray[$i] = sizeof($primaMes);
            $totals = $totals + $sumasegurada;
            $totalCant = $totalCant + $cantArray[$i];
            $semanaMesArray[$i] = $semana;
            $primaPorMes[$i] = $sumasegurada;
        }
    }
    $semSinDuplicado = array_values(array_unique($semanaMesArray));
    for ($i = 0; $i < sizeof($semSinDuplicado); $i++) {
        $var1 = 0;
        $cant1 = 0;
        for ($a = 0; $a < sizeof($semanaMesArray); $a++) {
            if ($semanaMesArray[$a] == $semSinDuplicado[$i]) {
                $var1 = $var1 + $primaPorMes[$a];
                $cant1 = $cant1 + $cantArray[$a];
            }
        }
        $primaPorMesF[$i] = $var1;
        $cantArrayF[$i] = $cant1;
    }
}

//--- Primas_Cobradas/ramo.php
if ($pag == 'Primas_Cobradas/ramo') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    $mes = $obj->get_mes_prima_BN();

    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    if ($permiso != 3) {
        $ramo = $obj->get_distinct_ramo_prima_c($_GET['anio'], $cia, $tipo_cuenta);

        if ($ramo == 0) {
            header("Location: busqueda_ramo.php?m=2");
        }

        $totalPArray[sizeof($ramo)] = null;
        $ramoArray[sizeof($ramo)] = null;

        $sumasegurada[sizeof($ramo)] = null;
        $p1[sizeof($ramo)] = null;
        $p2[sizeof($ramo)] = null;
        $p3[sizeof($ramo)] = null;
        $p4[sizeof($ramo)] = null;
        $p5[sizeof($ramo)] = null;
        $p6[sizeof($ramo)] = null;
        $p7[sizeof($ramo)] = null;
        $p8[sizeof($ramo)] = null;
        $p9[sizeof($ramo)] = null;
        $p10[sizeof($ramo)] = null;
        $p11[sizeof($ramo)] = null;
        $p12[sizeof($ramo)] = null;
        $totalP[sizeof($ramo)] = null;
        $cantidad[sizeof($ramo)] = null;

        for ($i = 0; $i < sizeof($ramo); $i++) {
            $primaMes = $obj->get_poliza_c_cobrada_ramo($ramo[$i]['nramo'], $cia, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ramo($ramo[$i]['nramo'], $cia, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

            $sumasegurada = 0;
            $prima_pagada1 = 0;
            $prima_pagada2 = 0;
            $prima_pagada3 = 0;
            $prima_pagada4 = 0;
            $prima_pagada5 = 0;
            $prima_pagada6 = 0;
            $prima_pagada7 = 0;
            $prima_pagada8 = 0;
            $prima_pagada9 = 0;
            $prima_pagada10 = 0;
            $prima_pagada11 = 0;
            $prima_pagada12 = 0;

            $cantP = 0;

            for ($a = 0; $a < sizeof($primaMes); $a++) {
                $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                    $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                    $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                    $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                    $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                    $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                    $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                    $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                    $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                    $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                    $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                    $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                    $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
            }

            $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
            $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
            $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
            $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
            $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
            $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
            $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
            $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
            $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
            $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
            $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
            $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
            $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

            $p1[$i] = $prima_pagada1;
            $p2[$i] = $prima_pagada2;
            $p3[$i] = $prima_pagada3;
            $p4[$i] = $prima_pagada4;
            $p5[$i] = $prima_pagada5;
            $p6[$i] = $prima_pagada6;
            $p7[$i] = $prima_pagada7;
            $p8[$i] = $prima_pagada8;
            $p9[$i] = $prima_pagada9;
            $p10[$i] = $prima_pagada10;
            $p11[$i] = $prima_pagada11;
            $p12[$i] = $prima_pagada12;
            $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
            $ramoArray[$i] = $ramo[$i]['nramo'];

            $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

            $totalPC = $totalPC + $totalP[$i];

            $totalPArray[$i] = $totalP[$i];
        }
    }
    if ($permiso == 3) {
        $ramo = $obj->get_distinct_ramo_prima_c_by_user($_GET['anio'], $cia, $tipo_cuenta, $asesor_u);

        if ($ramo == 0) {
            header("Location: busqueda_ramo.php?m=2");
        }

        $totalPArray[sizeof($ramo)] = null;
        $ramoArray[sizeof($ramo)] = null;

        $sumasegurada[sizeof($ramo)] = null;
        $p1[sizeof($ramo)] = null;
        $p2[sizeof($ramo)] = null;
        $p3[sizeof($ramo)] = null;
        $p4[sizeof($ramo)] = null;
        $p5[sizeof($ramo)] = null;
        $p6[sizeof($ramo)] = null;
        $p7[sizeof($ramo)] = null;
        $p8[sizeof($ramo)] = null;
        $p9[sizeof($ramo)] = null;
        $p10[sizeof($ramo)] = null;
        $p11[sizeof($ramo)] = null;
        $p12[sizeof($ramo)] = null;
        $totalP[sizeof($ramo)] = null;
        $cantidad[sizeof($ramo)] = null;
        for ($i = 0; $i < sizeof($ramo); $i++) {
            $primaMes = $obj->get_poliza_c_cobrada_ramo_by_user($ramo[$i]['nramo'], $cia, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ramo_by_user($ramo[$i]['nramo'], $cia, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

            $sumasegurada = 0;
            $prima_pagada1 = 0;
            $prima_pagada2 = 0;
            $prima_pagada3 = 0;
            $prima_pagada4 = 0;
            $prima_pagada5 = 0;
            $prima_pagada6 = 0;
            $prima_pagada7 = 0;
            $prima_pagada8 = 0;
            $prima_pagada9 = 0;
            $prima_pagada10 = 0;
            $prima_pagada11 = 0;
            $prima_pagada12 = 0;

            $cantP = 0;

            for ($a = 0; $a < sizeof($primaMes); $a++) {
                $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                    $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                    $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                    $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                    $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                    $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                    $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                    $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                    $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                    $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                    $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                    $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                    $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
            }
            $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
            $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
            $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
            $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
            $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
            $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
            $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
            $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
            $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
            $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
            $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
            $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
            $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

            $p1[$i] = $prima_pagada1;
            $p2[$i] = $prima_pagada2;
            $p3[$i] = $prima_pagada3;
            $p4[$i] = $prima_pagada4;
            $p5[$i] = $prima_pagada5;
            $p6[$i] = $prima_pagada6;
            $p7[$i] = $prima_pagada7;
            $p8[$i] = $prima_pagada8;
            $p9[$i] = $prima_pagada9;
            $p10[$i] = $prima_pagada10;
            $p11[$i] = $prima_pagada11;
            $p12[$i] = $prima_pagada12;
            $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
            $ramoArray[$i] = $ramo[$i]['nramo'];

            $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

            $totalPC = $totalPC + $totalP[$i];

            $totalPArray[$i] = $totalP[$i];
        }
    }

    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {
        $x[count($x)] = $key;
    }

    $contador = (sizeof($ramo) > 10) ? sizeof($ramo) - 10 : 0;
}

//--- Primas_Cobradas/prima_mes.php
if ($pag == 'Primas_Cobradas/prima_mes') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    $desdeI = $_GET['anio'] . '-01-01';
    $hastaI = ($_GET['anio']) . '-12-31';
    $mes = $obj->get_mes_prima_BN();

    $ramoArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $sumasegurada[12] = null;
    $p1[12] = null;
    $p2[12] = null;
    $p3[12] = null;
    $p4[12] = null;
    $p5[12] = null;
    $p6[12] = null;
    $p7[12] = null;
    $p8[12] = null;
    $p9[12] = null;
    $p10[12] = null;
    $p11[12] = null;
    $p12[12] = null;
    $totalP[12] = null;
    $totalMes[12] = null;

    $cantidad[12] = null;
    for ($i = 0; $i < 12; $i++) {
        if ($mes[$i]["Month(f_desdepoliza)"] < 10) {
            $desde = $_GET['anio'] . "-0" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_GET['anio'] . "-0" . $mes[$i]["Month(f_desdepoliza)"] . "-31";
        } else {
            $desde = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-31";
        }
        $mesB = $i + 1;
        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_bn($ramo, $desde, $hasta, $cia, $mesB, $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_mes($ramo, $cia, $desde, $hasta, $tipo_cuenta);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_bn_by_user($ramo, $desde, $hasta, $cia, $mesB, $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_mes_by_user($ramo, $cia, $desde, $hasta, $tipo_cuenta, $asesor_u);
        }

        if ($primaMes == 0) {
            header("Location: busqueda_prima_mes.php?m=2");
        }

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantMes = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
        }
        $totals = $totals + $sumasegurada;
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $totalMes[$i] = $cantMes;
        $totalCant = $totalCant + $cantMes;

        $totalCantPC = $totalCantPC + $cantidad[$i];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];
    }
}

//--- Primas_Cobradas/cia.php
if ($pag == 'Primas_Cobradas/cia') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    $mes = $obj->get_mes_prima_BN();

    $ciaArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    if ($permiso != 3) {
        $cia = $obj->get_distinct_cia_prima_c($_GET['anio'], $ramo, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $cia = $obj->get_distinct_cia_prima_c_by_user($_GET['anio'], $ramo, $tipo_cuenta, $asesor_u);
    }

    if ($cia == 0) {
        header("Location: busqueda_cia.php?m=2");
    }

    $totalPArray[sizeof($cia)] = null;
    $totalPC = 0;

    $sumasegurada[sizeof($cia)] = null;
    $p1[sizeof($cia)] = null;
    $p2[sizeof($cia)] = null;
    $p3[sizeof($cia)] = null;
    $p4[sizeof($cia)] = null;
    $p5[sizeof($cia)] = null;
    $p6[sizeof($cia)] = null;
    $p7[sizeof($cia)] = null;
    $p8[sizeof($cia)] = null;
    $p9[sizeof($cia)] = null;
    $p10[sizeof($cia)] = null;
    $p11[sizeof($cia)] = null;
    $p12[sizeof($cia)] = null;
    $totalP[sizeof($cia)] = null;
    $cantidad[sizeof($cia)] = null;

    for ($i = 0; $i < sizeof($cia); $i++) {

        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_cia($cia[$i]['nomcia'], $ramo, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_cia($ramo, $cia[$i]['nomcia'], $_GET['anio'], $tipo_cuenta);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_cia_by_user($cia[$i]['nomcia'], $ramo, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_cia_by_user($ramo, $cia[$i]['nomcia'], $_GET['anio'], $tipo_cuenta, $asesor_u);
        }

        $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantP = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {

            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];


            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
        }

        $totals = $totals + $sumasegurada;
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $ciaArray[$i] = $cia[$i]['nomcia'];
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }

    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : 0;
}

//--- Primas_Cobradas/tipo_poliza.php
if ($pag == 'Primas_Cobradas/tipo_poliza') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    $mes = $obj->get_mes_prima_BN();

    $tipoPArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    if ($permiso != 3) {
        $tipo_poliza = $obj->get_distinct_tipo_poliza_prima_c($_GET['anio'], $ramo, $cia, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $tipo_poliza = $obj->get_distinct_tipo_poliza_prima_c_by_user($_GET['anio'], $ramo, $cia, $tipo_cuenta, $asesor_u);
    }

    if ($tipo_poliza == 0) {
        header("Location: busqueda_tipo_poliza.php?m=2");
    }

    $totalPArray[sizeof($tipo_poliza)] = null;
    $totalPC = 0;

    $sumasegurada[sizeof($tipo_poliza)] = null;
    $p1[sizeof($tipo_poliza)] = null;
    $p2[sizeof($tipo_poliza)] = null;
    $p3[sizeof($tipo_poliza)] = null;
    $p4[sizeof($tipo_poliza)] = null;
    $p5[sizeof($tipo_poliza)] = null;
    $p6[sizeof($tipo_poliza)] = null;
    $p7[sizeof($tipo_poliza)] = null;
    $p8[sizeof($tipo_poliza)] = null;
    $p9[sizeof($tipo_poliza)] = null;
    $p10[sizeof($tipo_poliza)] = null;
    $p11[sizeof($tipo_poliza)] = null;
    $p12[sizeof($tipo_poliza)] = null;
    $totalP[sizeof($tipo_poliza)] = null;
    $cantidad[sizeof($tipo_poliza)] = null;

    for ($i = 0; $i < sizeof($tipo_poliza); $i++) {

        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_tipo_poliza($tipo_poliza[$i]['tipo_poliza'], $cia, $ramo, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_tpoliza($ramo, $cia, $_GET['anio'], $tipo_cuenta, $tipo_poliza[$i]['tipo_poliza']);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_tipo_poliza_by_user($tipo_poliza[$i]['tipo_poliza'], $cia, $ramo, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_tpoliza_by_user($ramo, $cia, $_GET['anio'], $tipo_cuenta, $tipo_poliza[$i]['tipo_poliza'], $asesor_u);
        }
        $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantP = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
        }
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $tipoPArray[$i] = $tipo_poliza[$i]['tipo_poliza'];
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }
    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Primas_Cobradas/ejecutivo.php
if ($pag == 'Primas_Cobradas/ejecutivo') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    $mes = $obj->get_mes_prima_BN();

    $EjArray[sizeof($mes)] = null;
    $EjCodArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $ejecutivo = $obj->get_distinct_ejecutivo_prima_c($_GET['anio'], $ramo, $cia, $tipo_cuenta);

    if ($ejecutivo == 0) {
        header("Location: busqueda_ejecutivo.php?m=2");
    }

    //Ordeno los ejecutivos de menor a mayor alfabéticamente
    $EjecutivoArray[sizeof($ejecutivo)] = null;
    $codEj[sizeof($ejecutivo)] = null;

    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $nombre = $ejecutivo[$i]['nombre'];

        $EjecutivoArray[$i] = $nombre;
        $codEj[$i] = $ejecutivo[$i]['cod_vend'];
    }

    $totalPArray[sizeof($ejecutivo)] = null;
    $totalPC = 0;

    $sumasegurada[] = null;
    $p1[] = null;
    $p2[] = null;
    $p3[] = null;
    $p4[] = null;
    $p5[] = null;
    $p6[] = null;
    $p7[] = null;
    $p8[] = null;
    $p9[] = null;
    $p10[] = null;
    $p11[] = null;
    $p12[] = null;
    $totalP[] = null;
    $cantidad[] = null;


    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $primaMes = $obj->get_poliza_c_cobrada_ejecutivo($codEj[$i], $cia, $ramo, $_GET['anio'], $tipo_cuenta);

        $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ejecutivo($ramo, $cia, $_GET['anio'], $tipo_cuenta, $codEj[$i]);

        $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantP = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
        }
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $EjArray[$i] = $EjecutivoArray[$i];
        $EjCodArray[$i] = $codEj[$i];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }
    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($ejecutivo) > 10) ? sizeof($ejecutivo) - 10 : 0;
}

//--- Primas_Cobradas/f_pago.php
if ($pag == 'Primas_Cobradas/f_pago') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    $mes = $obj->get_mes_prima_BN();

    $fPagoArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    if ($permiso != 3) {
        $f_pago = $obj->get_distinct_f_pago_prima_c($_GET['anio'], $ramo, $cia, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $f_pago = $obj->get_distinct_f_pago_prima_c_by_user($_GET['anio'], $ramo, $cia, $tipo_cuenta, $asesor_u);
    }

    if ($f_pago == 0) {
        header("Location: busqueda_fpago.php?m=2");
    }

    $totalPArray[sizeof($f_pago)] = null;
    $totalPC = 0;

    $sumasegurada[] = null;
    $p1[] = null;
    $p2[] = null;
    $p3[] = null;
    $p4[] = null;
    $p5[] = null;
    $p6[] = null;
    $p7[] = null;
    $p8[] = null;
    $p9[] = null;
    $p10[] = null;
    $p11[] = null;
    $p12[] = null;
    $totalP[] = null;
    $cantidad[] = null;

    for ($i = 0; $i < sizeof($f_pago); $i++) {
        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_f_pago($f_pago[$i]['fpago'], $cia, $ramo, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_fpago($ramo, $cia, $_GET['anio'], $tipo_cuenta, $f_pago[$i]['fpago']);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_f_pago_by_user($f_pago[$i]['fpago'], $cia, $ramo, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_fpago_by_user($ramo, $cia, $_GET['anio'], $tipo_cuenta, $f_pago[$i]['fpago'], $asesor_u);
        }

        $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantP = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
        }
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $fPagoArray[$i] = $f_pago[$i]['fpago'];
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }
    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Comisiones_Cobradas/ramo.php
if ($pag == 'Comisiones_Cobradas/ramo') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    $mes = $obj->get_mes_prima_BN();

    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    if ($permiso != 3) {
        $ramo = $obj->get_distinct_ramo_prima_c($_GET['anio'], $cia, $tipo_cuenta);

        if ($ramo == 0) {
            header("Location: busqueda_ramo.php?m=2");
        }

        $totalPArray[sizeof($ramo)] = null;
        $ramoArray[sizeof($ramo)] = null;

        $sumasegurada[sizeof($ramo)] = null;
        $p1[sizeof($ramo)] = null;
        $p2[sizeof($ramo)] = null;
        $p3[sizeof($ramo)] = null;
        $p4[sizeof($ramo)] = null;
        $p5[sizeof($ramo)] = null;
        $p6[sizeof($ramo)] = null;
        $p7[sizeof($ramo)] = null;
        $p8[sizeof($ramo)] = null;
        $p9[sizeof($ramo)] = null;
        $p10[sizeof($ramo)] = null;
        $p11[sizeof($ramo)] = null;
        $p12[sizeof($ramo)] = null;
        $totalP[sizeof($ramo)] = null;
        $cantidad[sizeof($ramo)] = null;

        for ($i = 0; $i < sizeof($ramo); $i++) {
            $primaMes = $obj->get_poliza_c_cobrada_ramo($ramo[$i]['nramo'], $cia, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ramo($ramo[$i]['nramo'], $cia, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

            $sumasegurada = 0;
            $prima_pagada1 = 0;
            $prima_pagada2 = 0;
            $prima_pagada3 = 0;
            $prima_pagada4 = 0;
            $prima_pagada5 = 0;
            $prima_pagada6 = 0;
            $prima_pagada7 = 0;
            $prima_pagada8 = 0;
            $prima_pagada9 = 0;
            $prima_pagada10 = 0;
            $prima_pagada11 = 0;
            $prima_pagada12 = 0;

            $cantP = 0;

            for ($a = 0; $a < sizeof($primaMes); $a++) {
                $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                    $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                    $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                    $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                    $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                    $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                    $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                    $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                    $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                    $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                    $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                    $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                    $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
            }

            $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
            $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
            $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
            $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
            $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
            $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
            $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
            $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
            $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
            $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
            $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
            $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
            $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

            $p1[$i] = $prima_pagada1;
            $p2[$i] = $prima_pagada2;
            $p3[$i] = $prima_pagada3;
            $p4[$i] = $prima_pagada4;
            $p5[$i] = $prima_pagada5;
            $p6[$i] = $prima_pagada6;
            $p7[$i] = $prima_pagada7;
            $p8[$i] = $prima_pagada8;
            $p9[$i] = $prima_pagada9;
            $p10[$i] = $prima_pagada10;
            $p11[$i] = $prima_pagada11;
            $p12[$i] = $prima_pagada12;
            $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
            $ramoArray[$i] = $ramo[$i]['nramo'];

            $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

            $totalPC = $totalPC + $totalP[$i];

            $totalPArray[$i] = $totalP[$i];
        }
    }
    if ($permiso == 3) {
        $ramo = $obj->get_distinct_ramo_prima_c_by_user($_GET['anio'], $cia, $tipo_cuenta, $asesor_u);

        if ($ramo == 0) {
            header("Location: busqueda_ramo.php?m=2");
        }

        $totalPArray[sizeof($ramo)] = null;
        $ramoArray[sizeof($ramo)] = null;

        $sumasegurada[sizeof($ramo)] = null;
        $p1[sizeof($ramo)] = null;
        $p2[sizeof($ramo)] = null;
        $p3[sizeof($ramo)] = null;
        $p4[sizeof($ramo)] = null;
        $p5[sizeof($ramo)] = null;
        $p6[sizeof($ramo)] = null;
        $p7[sizeof($ramo)] = null;
        $p8[sizeof($ramo)] = null;
        $p9[sizeof($ramo)] = null;
        $p10[sizeof($ramo)] = null;
        $p11[sizeof($ramo)] = null;
        $p12[sizeof($ramo)] = null;
        $totalP[sizeof($ramo)] = null;
        $cantidad[sizeof($ramo)] = null;
        for ($i = 0; $i < sizeof($ramo); $i++) {
            $primaMes = $obj->get_poliza_c_cobrada_ramo_by_user($ramo[$i]['nramo'], $cia, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ramo_by_user($ramo[$i]['nramo'], $cia, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

            $sumasegurada = 0;
            $prima_pagada1 = 0;
            $prima_pagada2 = 0;
            $prima_pagada3 = 0;
            $prima_pagada4 = 0;
            $prima_pagada5 = 0;
            $prima_pagada6 = 0;
            $prima_pagada7 = 0;
            $prima_pagada8 = 0;
            $prima_pagada9 = 0;
            $prima_pagada10 = 0;
            $prima_pagada11 = 0;
            $prima_pagada12 = 0;

            $cantP = 0;

            for ($a = 0; $a < sizeof($primaMes); $a++) {
                $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                    $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                    $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                    $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                    $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                    $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                    $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                    $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                    $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                    $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                    $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                    $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                    $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['comision'];
                    $cantP = $cantP + 1;
                }
            }
            $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
            $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
            $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
            $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
            $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
            $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
            $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
            $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
            $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
            $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
            $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
            $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
            $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

            $p1[$i] = $prima_pagada1;
            $p2[$i] = $prima_pagada2;
            $p3[$i] = $prima_pagada3;
            $p4[$i] = $prima_pagada4;
            $p5[$i] = $prima_pagada5;
            $p6[$i] = $prima_pagada6;
            $p7[$i] = $prima_pagada7;
            $p8[$i] = $prima_pagada8;
            $p9[$i] = $prima_pagada9;
            $p10[$i] = $prima_pagada10;
            $p11[$i] = $prima_pagada11;
            $p12[$i] = $prima_pagada12;
            $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
            $ramoArray[$i] = $ramo[$i]['nramo'];

            $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

            $totalPC = $totalPC + $totalP[$i];

            $totalPArray[$i] = $totalP[$i];
        }
    }

    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {
        $x[count($x)] = $key;
    }

    $contador = (sizeof($ramo) > 10) ? sizeof($ramo) - 10 : 0;
}

//--- Comisiones_Cobradas/prima_mes.php
if ($pag == 'Comisiones_Cobradas/prima_mes') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    $desdeI = $_GET['anio'] . '-01-01';
    $hastaI = ($_GET['anio']) . '-12-31';
    $mes = $obj->get_mes_prima_BN();

    $ramoArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $sumasegurada[12] = null;
    $p1[12] = null;
    $p2[12] = null;
    $p3[12] = null;
    $p4[12] = null;
    $p5[12] = null;
    $p6[12] = null;
    $p7[12] = null;
    $p8[12] = null;
    $p9[12] = null;
    $p10[12] = null;
    $p11[12] = null;
    $p12[12] = null;
    $totalP[12] = null;
    $totalMes[12] = null;

    $cantidad[12] = null;
    for ($i = 0; $i < 12; $i++) {
        if ($mes[$i]["Month(f_desdepoliza)"] < 10) {
            $desde = $_GET['anio'] . "-0" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_GET['anio'] . "-0" . $mes[$i]["Month(f_desdepoliza)"] . "-31";
        } else {
            $desde = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-31";
        }
        $mesB = $i + 1;
        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_bn($ramo, $desde, $hasta, $cia, $mesB, $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_mes($ramo, $cia, $desde, $hasta, $tipo_cuenta);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_bn_by_user($ramo, $desde, $hasta, $cia, $mesB, $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_mes_by_user($ramo, $cia, $desde, $hasta, $tipo_cuenta, $asesor_u);
        }

        if ($primaMes == 0) {
            header("Location: busqueda_prima_mes.php?m=2");
        }

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantMes = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['comision'];
                $cantMes = $cantMes + $primaMes[$a]['comision'];
            }
        }
        $totals = $totals + $sumasegurada;
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $totalMes[$i] = $cantMes;
        $totalCant = $totalCant + $cantMes;

        $totalCantPC = $totalCantPC + $cantidad[$i];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];
    }
}

//--- Comisiones_Cobradas/cia.php
if ($pag == 'Comisiones_Cobradas/cia') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    $mes = $obj->get_mes_prima_BN();

    $ciaArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    if ($permiso != 3) {
        $cia = $obj->get_distinct_cia_prima_c($_GET['anio'], $ramo, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $cia = $obj->get_distinct_cia_prima_c_by_user($_GET['anio'], $ramo, $tipo_cuenta, $asesor_u);
    }

    if ($cia == 0) {
        header("Location: busqueda_cia.php?m=2");
    }

    $totalPArray[sizeof($cia)] = null;
    $totalPC = 0;

    $sumasegurada[sizeof($cia)] = null;
    $p1[sizeof($cia)] = null;
    $p2[sizeof($cia)] = null;
    $p3[sizeof($cia)] = null;
    $p4[sizeof($cia)] = null;
    $p5[sizeof($cia)] = null;
    $p6[sizeof($cia)] = null;
    $p7[sizeof($cia)] = null;
    $p8[sizeof($cia)] = null;
    $p9[sizeof($cia)] = null;
    $p10[sizeof($cia)] = null;
    $p11[sizeof($cia)] = null;
    $p12[sizeof($cia)] = null;
    $totalP[sizeof($cia)] = null;
    $cantidad[sizeof($cia)] = null;

    for ($i = 0; $i < sizeof($cia); $i++) {

        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_cia($cia[$i]['nomcia'], $ramo, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_cia($ramo, $cia[$i]['nomcia'], $_GET['anio'], $tipo_cuenta);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_cia_by_user($cia[$i]['nomcia'], $ramo, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_cia_by_user($ramo, $cia[$i]['nomcia'], $_GET['anio'], $tipo_cuenta, $asesor_u);
        }

        $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantP = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {

            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];


            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
        }

        $totals = $totals + $sumasegurada;
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $ciaArray[$i] = $cia[$i]['nomcia'];
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }

    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : 0;
}

//--- Comisiones_Cobradas/tipo_poliza.php
if ($pag == 'Comisiones_Cobradas/tipo_poliza') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    $mes = $obj->get_mes_prima_BN();

    $tipoPArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    if ($permiso != 3) {
        $tipo_poliza = $obj->get_distinct_tipo_poliza_prima_c($_GET['anio'], $ramo, $cia, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $tipo_poliza = $obj->get_distinct_tipo_poliza_prima_c_by_user($_GET['anio'], $ramo, $cia, $tipo_cuenta, $asesor_u);
    }

    if ($tipo_poliza == 0) {
        header("Location: busqueda_tipo_poliza.php?m=2");
    }

    $totalPArray[sizeof($tipo_poliza)] = null;
    $totalPC = 0;

    $sumasegurada[sizeof($tipo_poliza)] = null;
    $p1[sizeof($tipo_poliza)] = null;
    $p2[sizeof($tipo_poliza)] = null;
    $p3[sizeof($tipo_poliza)] = null;
    $p4[sizeof($tipo_poliza)] = null;
    $p5[sizeof($tipo_poliza)] = null;
    $p6[sizeof($tipo_poliza)] = null;
    $p7[sizeof($tipo_poliza)] = null;
    $p8[sizeof($tipo_poliza)] = null;
    $p9[sizeof($tipo_poliza)] = null;
    $p10[sizeof($tipo_poliza)] = null;
    $p11[sizeof($tipo_poliza)] = null;
    $p12[sizeof($tipo_poliza)] = null;
    $totalP[sizeof($tipo_poliza)] = null;
    $cantidad[sizeof($tipo_poliza)] = null;

    for ($i = 0; $i < sizeof($tipo_poliza); $i++) {

        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_tipo_poliza($tipo_poliza[$i]['tipo_poliza'], $cia, $ramo, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_tpoliza($ramo, $cia, $_GET['anio'], $tipo_cuenta, $tipo_poliza[$i]['tipo_poliza']);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_tipo_poliza_by_user($tipo_poliza[$i]['tipo_poliza'], $cia, $ramo, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_tpoliza_by_user($ramo, $cia, $_GET['anio'], $tipo_cuenta, $tipo_poliza[$i]['tipo_poliza'], $asesor_u);
        }
        $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantP = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
        }
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $tipoPArray[$i] = $tipo_poliza[$i]['tipo_poliza'];
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }
    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Comisiones_Cobradas/fpago.php
if ($pag == 'Comisiones_Cobradas/fpago') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    $mes = $obj->get_mes_prima_BN();

    $fPagoArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    if ($permiso != 3) {
        $f_pago = $obj->get_distinct_f_pago_prima_c($_GET['anio'], $ramo, $cia, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $f_pago = $obj->get_distinct_f_pago_prima_c_by_user($_GET['anio'], $ramo, $cia, $tipo_cuenta, $asesor_u);
    }

    if ($f_pago == 0) {
        header("Location: busqueda_fpago.php?m=2");
    }

    $totalPArray[sizeof($f_pago)] = null;
    $totalPC = 0;

    $sumasegurada[] = null;
    $p1[] = null;
    $p2[] = null;
    $p3[] = null;
    $p4[] = null;
    $p5[] = null;
    $p6[] = null;
    $p7[] = null;
    $p8[] = null;
    $p9[] = null;
    $p10[] = null;
    $p11[] = null;
    $p12[] = null;
    $totalP[] = null;
    $cantidad[] = null;

    for ($i = 0; $i < sizeof($f_pago); $i++) {
        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_f_pago($f_pago[$i]['fpago'], $cia, $ramo, $_GET['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_fpago($ramo, $cia, $_GET['anio'], $tipo_cuenta, $f_pago[$i]['fpago']);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_f_pago_by_user($f_pago[$i]['fpago'], $cia, $ramo, $_GET['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_fpago_by_user($ramo, $cia, $_GET['anio'], $tipo_cuenta, $f_pago[$i]['fpago'], $asesor_u);
        }

        $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantP = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
        }
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $fPagoArray[$i] = $f_pago[$i]['fpago'];
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }
    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Comisiones_Cobradas/ejecutivo.php
if ($pag == 'Comisiones_Cobradas/ejecutivo') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    $mes = $obj->get_mes_prima_BN();

    $EjArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;
    $primaCobradaPorMes3 = 0;
    $primaCobradaPorMes4 = 0;
    $primaCobradaPorMes5 = 0;
    $primaCobradaPorMes6 = 0;
    $primaCobradaPorMes7 = 0;
    $primaCobradaPorMes8 = 0;
    $primaCobradaPorMes9 = 0;
    $primaCobradaPorMes10 = 0;
    $primaCobradaPorMes11 = 0;
    $primaCobradaPorMes12 = 0;

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $ejecutivo = $obj->get_distinct_ejecutivo_prima_c($_GET['anio'], $ramo, $cia, $tipo_cuenta);

    if ($ejecutivo == 0) {
        header("Location: busqueda_ejecutivo.php?m=2");
    }

    //Ordeno los ejecutivos de menor a mayor alfabéticamente
    $EjecutivoArray[sizeof($ejecutivo)] = null;
    $codEj[sizeof($ejecutivo)] = null;

    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $nombre = $ejecutivo[$i]['nombre'];

        $EjecutivoArray[$i] = $nombre;
        $codEj[$i] = $ejecutivo[$i]['cod_vend'];
    }

    $totalPArray[sizeof($ejecutivo)] = null;
    $totalPC = 0;

    $sumasegurada[] = null;
    $p1[] = null;
    $p2[] = null;
    $p3[] = null;
    $p4[] = null;
    $p5[] = null;
    $p6[] = null;
    $p7[] = null;
    $p8[] = null;
    $p9[] = null;
    $p10[] = null;
    $p11[] = null;
    $p12[] = null;
    $totalP[] = null;
    $cantidad[12] = null;


    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $primaMes = $obj->get_poliza_c_cobrada_ejecutivo($codEj[$i], $cia, $ramo, $_GET['anio'], $tipo_cuenta);

        $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ejecutivo($ramo, $cia, $_GET['anio'], $tipo_cuenta, $codEj[$i]);

        $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;
        $prima_pagada3 = 0;
        $prima_pagada4 = 0;
        $prima_pagada5 = 0;
        $prima_pagada6 = 0;
        $prima_pagada7 = 0;
        $prima_pagada8 = 0;
        $prima_pagada9 = 0;
        $prima_pagada10 = 0;
        $prima_pagada11 = 0;
        $prima_pagada12 = 0;

        $cantP = 0;

        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_GET['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_GET['anio'] . '-12-31')) {
                $prima_pagada12 = $prima_pagada12 + $primaMes[$a]['comision'];
                $cantP = $cantP + 1;
            }
        }
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $primaPorMes[$i] = $sumasegurada;
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;
        $primaCobradaPorMes3 = $primaCobradaPorMes3 + $prima_pagada3;
        $primaCobradaPorMes4 = $primaCobradaPorMes4 + $prima_pagada4;
        $primaCobradaPorMes5 = $primaCobradaPorMes5 + $prima_pagada5;
        $primaCobradaPorMes6 = $primaCobradaPorMes6 + $prima_pagada6;
        $primaCobradaPorMes7 = $primaCobradaPorMes7 + $prima_pagada7;
        $primaCobradaPorMes8 = $primaCobradaPorMes8 + $prima_pagada8;
        $primaCobradaPorMes9 = $primaCobradaPorMes9 + $prima_pagada9;
        $primaCobradaPorMes10 = $primaCobradaPorMes10 + $prima_pagada10;
        $primaCobradaPorMes11 = $primaCobradaPorMes11 + $prima_pagada11;
        $primaCobradaPorMes12 = $primaCobradaPorMes12 + $prima_pagada12;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $p3[$i] = $prima_pagada3;
        $p4[$i] = $prima_pagada4;
        $p5[$i] = $prima_pagada5;
        $p6[$i] = $prima_pagada6;
        $p7[$i] = $prima_pagada7;
        $p8[$i] = $prima_pagada8;
        $p9[$i] = $prima_pagada9;
        $p10[$i] = $prima_pagada10;
        $p11[$i] = $prima_pagada11;
        $p12[$i] = $prima_pagada12;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $EjArray[$i] = $EjecutivoArray[$i];
        $EjCodArray[$i] = $codEj[$i];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }
    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($ejecutivo) > 10) ? sizeof($ejecutivo) - 10 : 0;
}

//--- Comparativo/ramo_ps.php
if ($pag == 'Comparativo/ramo_ps') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $mes = $_GET['mes'];
    $desde = $_GET['anio'] . "-" . $_GET['mes'] . "-01";
    $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-31";
    $desdeOld = intval($_GET['anio'] - 1) . "-" . $_GET['mes'] . "-01";
    $hastaOld = intval($_GET['anio'] - 1) . "-" . $_GET['mes'] . "-31";

    if ($mes == null) {
        $mesD = 01;
        $mesH = 12;
        $desde = $_GET['anio'] . "-" . $mesD . "-01";
        $hasta = $_GET['anio'] . "-" . $mesH . "-31";
        $desdeOld = intval($_GET['anio'] - 1) . "-" . $mesD . "-01";
        $hastaOld = intval($_GET['anio'] - 1) . "-" . $mesH . "-31";
    }

    $ramo = $obj->get_distinct_element_ramo($desde, $hasta, $cia, $tipo_cuenta);
    if ($permiso == 3) {
        $ramo = $obj->get_distinct_element_ramo_by_user($desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
    }

    if ($ramo == 0) {
        header("Location: b_ramo.php?m=2");
    }


    $ramoArray[sizeof($ramo)] = null;
    $sumatotalRamo[sizeof($ramo)] = null;
    $sumatotalRamoOld[sizeof($ramo)] = null;
    $cantArray[sizeof($ramo)] = null;
    $cantArrayOld[sizeof($ramo)] = null;

    for ($i = 0; $i < sizeof($ramo); $i++) {

        $ramoPoliza = $obj->get_poliza_graf_1($ramo[$i]['nramo'], $desde, $hasta, $cia, $tipo_cuenta);
        $ramoPolizaOld = $obj->get_poliza_graf_1($ramo[$i]['nramo'], $desdeOld, $hastaOld, $cia, $tipo_cuenta);
        if ($permiso == 3) {
            $ramoPoliza = $obj->get_poliza_graf_1_by_user($ramo[$i]['nramo'], $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
            $ramoPolizaOld = $obj->get_poliza_graf_1_by_user($ramo[$i]['nramo'], $desdeOld, $hastaOld, $cia, $tipo_cuenta, $asesor_u);
        }

        $cantRamoPoliza = ($ramoPoliza == 0) ? 0 : sizeof($ramoPoliza);
        $cantRamoPolizaOld = ($ramoPolizaOld == 0) ? 0 : sizeof($ramoPolizaOld);

        if ($ramoPolizaOld == 0) {
            $cantArrayOld[$i] = 0;
        } else {
            $cantArrayOld[$i] = $cantRamoPolizaOld;
        }
        $cantArray[$i] = $cantRamoPoliza;
        $sumasegurada = 0;
        $sumaseguradaOld = 0;

        for ($a = 0; $a < $cantRamoPoliza; $a++) {
            $sumasegurada = $sumasegurada + $ramoPoliza[$a]['prima'];
        }
        for ($a = 0; $a < $cantRamoPolizaOld; $a++) {
            $sumaseguradaOld = $sumaseguradaOld + $ramoPolizaOld[$a]['prima'];
        }

        $totals = $totals + $sumasegurada;
        $totalsOld = $totalsOld + $sumaseguradaOld;
        $totalCant = $totalCant + $cantArray[$i];
        $totalCantOld = $totalCantOld + $cantArrayOld[$i];
        $sumatotalRamo[$i] = $sumasegurada;
        $sumatotalRamoOld[$i] = $sumaseguradaOld;
        $ramoArray[$i] = $ramo[$i]['nramo'];
    }
    asort($sumatotalRamo, SORT_NUMERIC);
    $x = array();
    foreach ($sumatotalRamo as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Comparativo/cia_ps.php
if ($pag == 'Comparativo/cia_ps') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $mes = $_GET['mes'];
    $desde = $_GET['anio'] . "-" . $_GET['mes'] . "-01";
    $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-31";
    $desdeOld = intval($_GET['anio'] - 1) . "-" . $_GET['mes'] . "-01";
    $hastaOld = intval($_GET['anio'] - 1) . "-" . $_GET['mes'] . "-31";

    if ($mes == null) {
        $mesD = 01;
        $mesH = 12;
        $desde = $_GET['anio'] . "-" . $mesD . "-01";
        $hasta = $_GET['anio'] . "-" . $mesH . "-31";
        $desdeOld = intval($_GET['anio'] - 1) . "-" . $mesD . "-01";
        $hastaOld = intval($_GET['anio'] - 1) . "-" . $mesH . "-31";
    }

    $cia = $obj->get_distinct_element_cia($desde, $hasta, $ramo, $tipo_cuenta);
    if ($permiso == 3) {
        $cia = $obj->get_distinct_element_cia_by_user($desde, $hasta, $ramo, $tipo_cuenta, $asesor_u);
    }

    if ($cia == 0) {
        header("Location: b_cia.php?m=2");
    }

    $ciaArray[sizeof($cia)] = null;
    $sumatotalCia[sizeof($cia)] = null;
    $sumatotalCiaOld[sizeof($cia)] = null;
    $cantArray[sizeof($cia)] = null;
    $cantArrayOld[sizeof($cia)] = null;

    for ($i = 0; $i < sizeof($cia); $i++) {

        $ciaPoliza = $obj->get_poliza_graf_3($cia[$i]['nomcia'], $ramo, $desde, $hasta, $tipo_cuenta);
        $ciaPolizaOld = $obj->get_poliza_graf_3($cia[$i]['nomcia'], $ramo, $desdeOld, $hastaOld, $tipo_cuenta);
        if ($permiso == 3) {
            $ciaPoliza = $obj->get_poliza_graf_3_by_user($cia[$i]['nomcia'], $ramo, $desde, $hasta, $tipo_cuenta, $asesor_u);
            $ciaPolizaOld = $obj->get_poliza_graf_3_by_user($cia[$i]['nomcia'], $ramo, $desdeOld, $hastaOld, $tipo_cuenta, $asesor_u);
        }

        $cantCiaPoliza = ($ciaPoliza == 0) ? 0 : sizeof($ciaPoliza);
        $cantCiaPolizaOld = ($ciaPolizaOld == 0) ? 0 : sizeof($ciaPolizaOld);

        if ($ciaPolizaOld == 0) {
            $cantArrayOld[$i] = 0;
        } else {
            $cantArrayOld[$i] = $cantCiaPolizaOld;
        }
        $cantArray[$i] = $cantCiaPoliza;
        $sumasegurada = 0;
        $sumaseguradaOld = 0;

        for ($a = 0; $a < $cantCiaPoliza; $a++) {
            $sumasegurada = $sumasegurada + $ciaPoliza[$a]['prima'];
        }
        for ($a = 0; $a < $cantCiaPolizaOld; $a++) {
            $sumaseguradaOld = $sumaseguradaOld + $ciaPolizaOld[$a]['prima'];
        }

        $totals = $totals + $sumasegurada;
        $totalsOld = $totalsOld + $sumaseguradaOld;
        $totalCant = $totalCant + $cantArray[$i];
        $totalCantOld = $totalCantOld + $cantArrayOld[$i];
        $sumatotalCia[$i] = $sumasegurada;
        $sumatotalCiaOld[$i] = $sumaseguradaOld;
        $ciaArray[$i] = $cia[$i]['nomcia'];
    }
    asort($sumatotalCia, SORT_NUMERIC);
    $x = array();
    foreach ($sumatotalCia as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Comparativo/ejecutivo_ps.php
if ($pag == 'Comparativo/ejecutivo_ps') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $mes = $_GET['mes'];
    $desde = $_GET['anio'] . "-" . $_GET['mes'] . "-01";
    $hasta = $_GET['anio'] . "-" . $_GET['mes'] . "-31";
    $desdeOld = intval($_GET['anio'] - 1) . "-" . $_GET['mes'] . "-01";
    $hastaOld = intval($_GET['anio'] - 1) . "-" . $_GET['mes'] . "-31";

    if ($mes == null) {
        $mesD = 01;
        $mesH = 12;
        $desde = $_GET['anio'] . "-" . $mesD . "-01";
        $hasta = $_GET['anio'] . "-" . $mesH . "-31";
        $desdeOld = intval($_GET['anio'] - 1) . "-" . $mesD . "-01";
        $hastaOld = intval($_GET['anio'] - 1) . "-" . $mesH . "-31";
    }

    $ejecutivo = $obj->get_distinct_element_ejecutivo_ps($desde, $hasta, $ramo, $cia, $tipo_cuenta);

    if ($ejecutivo == 0) {
        header("Location: b_ejecutivo.php?m=2");
    }

    $ejecutivoArray[] = null;
    $codejecutivoArray[] = null;
    $sumatotalCia[] = null;
    $sumatotalCiaOld[] = null;
    $cantArray[] = null;
    $cantArrayOld[] = null;

    for ($i = 0; $i < sizeof($ejecutivo); $i++) {

        $ejecutivoPoliza = $obj->get_poliza_graf_prima_c_6($ejecutivo[$i]['codvend'], $ramo, $desde, $hasta, $cia, $tipo_cuenta);
        $ejecutivoPolizaOld = $obj->get_poliza_graf_prima_c_6($ejecutivo[$i]['codvend'], $ramo, $desdeOld, $hastaOld, $cia, $tipo_cuenta);


        $cantEjecutivoPoliza = ($ejecutivoPoliza == 0) ? 0 : sizeof($ejecutivoPoliza);
        $cantEjecutivoPolizaOld = ($ejecutivoPolizaOld == 0) ? 0 : sizeof($ejecutivoPolizaOld);

        if ($ejecutivoPolizaOld == 0) {
            $cantArrayOld[$i] = 0;
        } else {
            $cantArrayOld[$i] = $cantEjecutivoPolizaOld;
        }
        $cantArray[$i] = $cantEjecutivoPoliza;
        $sumasegurada = 0;
        $sumaseguradaOld = 0;

        for ($a = 0; $a < $cantEjecutivoPoliza; $a++) {
            $sumasegurada = $sumasegurada + $ejecutivoPoliza[$a]['prima'];
        }
        for ($a = 0; $a < $cantEjecutivoPolizaOld; $a++) {
            $sumaseguradaOld = $sumaseguradaOld + $ejecutivoPolizaOld[$a]['prima'];
        }

        $totals = $totals + $sumasegurada;
        $totalsOld = $totalsOld + $sumaseguradaOld;
        $totalCant = $totalCant + $cantArray[$i];
        $totalCantOld = $totalCantOld + $cantArrayOld[$i];
        $sumatotalEjecutivo[$i] = $sumasegurada;
        $sumatotalEjecutivoOld[$i] = $sumaseguradaOld;
        $ejecutivoArray[$i] = $ejecutivo[$i]['nombre'] . ' (' . $ejecutivo[$i]['codvend'] . ')';
        $codejecutivoArray[$i] = $ejecutivo[$i]['codvend'];
    }
    asort($sumatotalEjecutivo, SORT_NUMERIC);
    $x = array();
    foreach ($sumatotalEjecutivo as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Comparativo/ramo_pc.php
if ($pag == 'Comparativo/ramo_pc') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $mes = $obj->get_mes_prima_BN();

    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;


    $ramo = $obj->get_distinct_ramo_prima_c_comp($_GET['anio'], $_GET['mes'], $cia, $tipo_cuenta);
    if ($permiso == 3) {
        $ramo = $obj->get_distinct_ramo_prima_c_comp_by_user($_GET['anio'], $_GET['mes'], $cia, $tipo_cuenta, $asesor_u);
    }

    if ($ramo == 0) {
        header("Location: b_ramo.php?m=2");
    }

    $totalPArray[sizeof($ramo)] = null;
    $ramoArray[sizeof($ramo)] = null;


    $sumasegurada[sizeof($ramo)] = null;
    $p1[sizeof($ramo)] = null;
    $p2[sizeof($ramo)] = null;
    $totalP[sizeof($ramo)] = null;
    $cantidad[sizeof($ramo)] = null;
    $cantidadOld[sizeof($ramo)] = null;

    for ($i = 0; $i < sizeof($ramo); $i++) {
        $primaMes = $obj->get_poliza_c_cobrada_ramo_comp($ramo[$i]['nramo'], $cia, $_GET['anio'], $_GET['mes'], $tipo_cuenta);
        $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ramo_comp($ramo[$i]['nramo'], $cia, $_GET['anio'], $_GET['mes'], $tipo_cuenta);

        $primaMesOld = $obj->get_poliza_c_cobrada_ramo_comp($ramo[$i]['nramo'], $cia, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta);
        $cantidadPolizaROld = $obj->get_count_poliza_c_cobrada_ramo_comp($ramo[$i]['nramo'], $cia, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta);

        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_ramo_comp_by_user($ramo[$i]['nramo'], $cia, $_GET['anio'], $_GET['mes'], $tipo_cuenta, $asesor_u);
            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ramo_comp_by_user($ramo[$i]['nramo'], $cia, $_GET['anio'], $_GET['mes'], $tipo_cuenta, $asesor_u);

            $primaMesOld = $obj->get_poliza_c_cobrada_ramo_comp_by_user($ramo[$i]['nramo'], $cia, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta, $asesor_u);
            $cantidadPolizaROld = $obj->get_count_poliza_c_cobrada_ramo_comp_by_user($ramo[$i]['nramo'], $cia, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta, $asesor_u);
        }

        $cantPrimaMes = ($primaMes == 0) ? 0 : sizeof($primaMes);
        $cantPrimaMesOld = ($primaMesOld == 0) ? 0 : sizeof($primaMesOld);

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;

        $cantP = 0;

        for ($a = 0; $a < $cantPrimaMes; $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
            $cantP = $cantP + 1;
        }

        for ($a = 0; $a < $cantPrimaMesOld; $a++) {
            $sumasegurada = $sumasegurada + $primaMesOld[$a]['prima'];

            $prima_pagada1 = $prima_pagada1 + $primaMesOld[$a]['prima_com'];
            $cantP = $cantP + 1;
        }
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $totalCantOld = $totalCantOld + $cantidadPolizaROld[0]['count(DISTINCT comision.id_poliza)'];
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $cantidadOld[$i] = $cantidadPolizaROld[0]['count(DISTINCT comision.id_poliza)'];
        $ramoArray[$i] = $ramo[$i]['nramo'];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }

    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Comparativo/cia_pc.php
if ($pag == 'Comparativo/cia_pc') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $mes = $obj->get_mes_prima_BN();

    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;


    $cia = $obj->get_distinct_cia_prima_c_comp($_GET['anio'], $_GET['mes'], $ramo, $tipo_cuenta);
    if ($permiso == 3) {
        $cia = $obj->get_distinct_cia_prima_c_comp_by_user($_GET['anio'], $_GET['mes'], $ramo, $tipo_cuenta, $asesor_u);
    }

    if ($cia == 0) {
        header("Location: b_cia.php?m=2");
    }

    $totalPArray[sizeof($cia)] = null;
    $ciaArray[sizeof($cia)] = null;


    $sumasegurada[sizeof($cia)] = null;
    $p1[sizeof($cia)] = null;
    $p2[sizeof($cia)] = null;
    $totalP[sizeof($cia)] = null;
    $cantidad[sizeof($cia)] = null;
    $cantidadOld[sizeof($cia)] = null;

    for ($i = 0; $i < sizeof($cia); $i++) {
        $primaMes = $obj->get_poliza_c_cobrada_cia_comp($cia[$i]['nomcia'], $ramo, $_GET['anio'], $_GET['mes'], $tipo_cuenta);
        $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_cia_comp($cia[$i]['nomcia'], $ramo, $_GET['anio'], $_GET['mes'], $tipo_cuenta);

        $primaMesOld = $obj->get_poliza_c_cobrada_cia_comp($cia[$i]['nomcia'], $ramo, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta);
        $cantidadPolizaROld = $obj->get_count_poliza_c_cobrada_cia_comp($cia[$i]['nomcia'], $ramo, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta);

        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_cia_comp_by_user($cia[$i]['nomcia'], $ramo, $_GET['anio'], $_GET['mes'], $tipo_cuenta, $asesor_u);
            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_cia_comp_by_user($cia[$i]['nomcia'], $ramo, $_GET['anio'], $_GET['mes'], $tipo_cuenta, $asesor_u);

            $primaMesOld = $obj->get_poliza_c_cobrada_cia_comp_by_user($cia[$i]['nomcia'], $ramo, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta, $asesor_u);
            $cantidadPolizaROld = $obj->get_count_poliza_c_cobrada_cia_comp_by_user($cia[$i]['nomcia'], $ramo, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta, $asesor_u);
        }

        $cantPrimaMes = ($primaMes == 0) ? 0 : sizeof($primaMes);
        $cantPrimaMesOld = ($primaMesOld == 0) ? 0 : sizeof($primaMesOld);

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;

        $cantP = 0;

        for ($a = 0; $a < $cantPrimaMes; $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
            $cantP = $cantP + 1;
        }

        for ($a = 0; $a < $cantPrimaMesOld; $a++) {
            $sumasegurada = $sumasegurada + $primaMesOld[$a]['prima'];

            $prima_pagada1 = $prima_pagada1 + $primaMesOld[$a]['prima_com'];
            $cantP = $cantP + 1;
        }
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $totalCantOld = $totalCantOld + $cantidadPolizaROld[0]['count(DISTINCT comision.id_poliza)'];
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $cantidadOld[$i] = $cantidadPolizaROld[0]['count(DISTINCT comision.id_poliza)'];
        $ciaArray[$i] = $cia[$i]['nomcia'];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }

    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Comparativo/ejecutivo_pc.php
if ($pag == 'Comparativo/ejecutivo_pc') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $mes = $obj->get_mes_prima_BN();

    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;
    $primaCobradaPorMes1 = 0;
    $primaCobradaPorMes2 = 0;


    $ejecutivo = $obj->get_distinct_ejecutivo_prima_c_comp($_GET['anio'], $_GET['mes'], $cia, $ramo, $tipo_cuenta);

    if ($ejecutivo == 0) {
        header("Location: b_ejecutivo.php?m=2");
    }

    $totalPArray[] = null;
    $ejecutivoArray[] = null;
    $codejecutivoArray[] = null;


    $sumasegurada[] = null;
    $p1[] = null;
    $p2[] = null;
    $totalP[] = null;
    $cantidad[] = null;
    $cantidadOld[] = null;

    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $primaMes = $obj->get_poliza_c_cobrada_ejecutivo_comp($ejecutivo[$i]['codvend'], $cia, $ramo, $_GET['anio'], $_GET['mes'], $tipo_cuenta);
        $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ejecutivo_comp($ejecutivo[$i]['codvend'], $cia, $ramo, $_GET['anio'], $_GET['mes'], $tipo_cuenta);

        $primaMesOld = $obj->get_poliza_c_cobrada_ejecutivo_comp($ejecutivo[$i]['codvend'], $cia, $ramo, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta);
        $cantidadPolizaROld = $obj->get_count_poliza_c_cobrada_ejecutivo_comp($ejecutivo[$i]['codvend'], $cia, $ramo, intval($_GET['anio'] - 1), $_GET['mes'], $tipo_cuenta);


        $cantPrimaMes = ($primaMes == 0) ? 0 : sizeof($primaMes);
        $cantPrimaMesOld = ($primaMesOld == 0) ? 0 : sizeof($primaMesOld);

        $sumasegurada = 0;
        $prima_pagada1 = 0;
        $prima_pagada2 = 0;

        $cantP = 0;

        for ($a = 0; $a < $cantPrimaMes; $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];

            $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
            $cantP = $cantP + 1;
        }

        for ($a = 0; $a < $cantPrimaMesOld; $a++) {
            $sumasegurada = $sumasegurada + $primaMesOld[$a]['prima'];

            $prima_pagada1 = $prima_pagada1 + $primaMesOld[$a]['prima_com'];
            $cantP = $cantP + 1;
        }
        $totalCant = $totalCant + $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $totalCantOld = $totalCantOld + $cantidadPolizaROld[0]['count(DISTINCT comision.id_poliza)'];
        $primaCobradaPorMes1 = $primaCobradaPorMes1 + $prima_pagada1;
        $primaCobradaPorMes2 = $primaCobradaPorMes2 + $prima_pagada2;

        $p1[$i] = $prima_pagada1;
        $p2[$i] = $prima_pagada2;
        $cantidad[$i] = $cantidadPolizaR[0]['count(DISTINCT comision.id_poliza)'];
        $cantidadOld[$i] = $cantidadPolizaROld[0]['count(DISTINCT comision.id_poliza)'];
        $ejecutivoArray[$i] = $ejecutivo[$i]['nombre'] . ' (' . $ejecutivo[$i]['codvend'] . ')';
        $codejecutivoArray[$i] = $ejecutivo[$i]['codvend'];

        $totalP[$i] = $prima_pagada1 + $prima_pagada2;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }

    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Comparativo/mm_ramo.php
if ($pag == 'Comparativo/mm_ramo') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $desde = $_GET['anio'] . '-01-01';
    $hasta = ($_GET['anio']) . '-12-31';

    $mes = $obj->get_prima_mm($desde, $hasta, $cia, $ramo, $tipo_cuenta);

    if ($mes == 0) {
        header("Location: b_mm_ramo.php?m=2");
    }

    $ramoArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $cantArrayPC[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;

    $primaPorMesC[sizeof($mes)] = null;
    $comisionPorMes[sizeof($mes)] = null;
    $comisionGC[sizeof($mes)] = null;
    $perGCC[sizeof($mes)] = null;

    for ($i = 0; $i < sizeof($mes); $i++) {

        if ($mes[$i]["Month(f_pago_prima)"] < 10) {
            $desde = $_GET['anio'] . "-0" . $mes[$i]["Month(f_pago_prima)"] . "-01";
            $hasta = $_GET['anio'] . "-0" . $mes[$i]["Month(f_pago_prima)"] . "-31";
        } else {
            $desde = $_GET['anio'] . "-" . $mes[$i]["Month(f_pago_prima)"] . "-01";
            $hasta = $_GET['anio'] . "-" . $mes[$i]["Month(f_pago_prima)"] . "-31";
        }

        $primaMes = $obj->get_poliza_prima_mm($ramo, $desde, $hasta, $cia, $tipo_cuenta);

        /*$cantArray[$i] = sizeof($primaMes);
        $totalCant = $totalCant + $cantArray[$i];
        $sumasegurada = 0;
        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];
        }
        $totals = $totals + $sumasegurada;
        $ramoArray[$i] = $primaMes[0]['cod_ramo'];
        $primaPorMes[$i] = $sumasegurada;*/

        $primacMes = $obj->get_poliza_pc_mm($ramo, $desde, $hasta, $cia, $tipo_cuenta);
        $primacMesCant = $obj->get_poliza_pc_mm_cant($ramo, $desde, $hasta, $cia, $tipo_cuenta);
        $cantArrayPC[$i] = sizeof($primacMesCant);
        $totalCantPC = $totalCantPC + $cantArrayPC[$i];

        $sumaseguradaC = 0;
        $sumaseguradaCom = 0;
        $GCcobrada = 0;
        $perGC = 0;
        for ($a = 0; $a < sizeof($primacMes); $a++) {
            $sumaseguradaC = $sumaseguradaC + $primacMes[$a]['prima_com'];
            $sumaseguradaCom = $sumaseguradaCom + $primacMes[$a]['comision'];
            $GCcobrada = $GCcobrada + (($primacMes[$a]['comision'] * $primacMes[$a]['per_gc']) / 100);
        }
        $primacMesCant = $obj->get_count_poliza_pc_mm($ramo, $desde, $hasta, $cia, $tipo_cuenta);
        //$cantArray[$i] = sizeof($primacMesCant);
        //$totalCant = $totalCant + $cantArray[$i];

        $totalc = $totalc + $sumaseguradaC;
        $totalCom = $totalCom + $sumaseguradaCom;
        $totalGC = $totalGC + $GCcobrada;
        $primaPorMesC[$i] = $sumaseguradaC;
        $comisionPorMes[$i] = $sumaseguradaCom;
        $comisionGC[$i] = $GCcobrada;
        $perGCC[$i] = ($comisionGC[$i] / $comisionPorMes[$i]) * 100;
        $totalperGC = $totalperGC + $perGCC[$i];
    }
    $totalperGC = $totalperGC / sizeof($mes);


    // tabla comparacion años anteriores
    $desdeC = $_GET['anioC'] . '-01-01';
    $hastaC = ($_GET['anioC']) . '-12-31';

    $mesC = $obj->get_prima_mm($desdeC, $hastaC, $cia, $ramo, $tipo_cuenta);

    $ramoArrayC[sizeof($mesC)] = null;
    $cantArrayC[sizeof($mesC)] = null;
    $cantArrayPCC[sizeof($mesC)] = null;
    $primaPorMesC[sizeof($mesC)] = null;

    $primaPorMesCC[sizeof($mesC)] = null;
    $comisionPorMesC[sizeof($mesC)] = null;
    $comisionGCC[sizeof($mesC)] = null;
    $perGCCC1[sizeof($mesC)] = null;

    for ($i = 0; $i < sizeof($mesC); $i++) {

        if ($mesC[$i]["Month(f_pago_prima)"] < 10) {
            $desdeC = $_GET['anioC'] . "-0" . $mesC[$i]["Month(f_pago_prima)"] . "-01";
            $hastaC = $_GET['anioC'] . "-0" . $mesC[$i]["Month(f_pago_prima)"] . "-31";
        } else {
            $desdeC = $_GET['anioC'] . "-" . $mesC[$i]["Month(f_pago_prima)"] . "-01";
            $hastaC = $_GET['anioC'] . "-" . $mesC[$i]["Month(f_pago_prima)"] . "-31";
        }

        $primaMesC = $obj->get_poliza_prima_mm($ramo, $desdeC, $hastaC, $cia, $tipo_cuenta);

        //$cantArrayC[$i] = sizeof($primaMesC);
        //$totalCantC = $totalCantC + $cantArrayC[$i];
        /*$sumasegurada = 0;
        for ($a = 0; $a < sizeof($primaMes); $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];
        }
        $totals = $totals + $sumasegurada;
        $ramoArray[$i] = $primaMes[0]['cod_ramo'];
        $primaPorMes[$i] = $sumasegurada;*/

        $primacMesC = $obj->get_poliza_pc_mm($ramo, $desdeC, $hastaC, $cia, $tipo_cuenta);
        $primacMesCantC = $obj->get_poliza_pc_mm_cant($ramo, $desdeC, $hastaC, $cia, $tipo_cuenta);
        $cantArrayPCC[$i] = sizeof($primacMesCantC);
        $totalCantPCC = $totalCantPCC + $cantArrayPCC[$i];

        $sumaseguradaCC = 0;
        $sumaseguradaComC = 0;
        $GCcobradaC = 0;
        $perGCC1 = 0;
        for ($a = 0; $a < sizeof($primacMesC); $a++) {
            $sumaseguradaCC = $sumaseguradaCC + $primacMesC[$a]['prima_com'];
            $sumaseguradaComC = $sumaseguradaComC + $primacMesC[$a]['comision'];
            $GCcobradaC = $GCcobradaC + (($primacMesC[$a]['comision'] * $primacMesC[$a]['per_gc']) / 100);
        }
        $primacMesCantC = $obj->get_count_poliza_pc_mm($ramo, $desdeC, $hastaC, $cia, $tipo_cuenta);
        //$cantArray[$i] = sizeof($primacMesCant);
        //$totalCant = $totalCant + $cantArray[$i];

        $totalcC = $totalcC + $sumaseguradaCC;
        $totalComC = $totalComC + $sumaseguradaComC;
        $totalGCC = $totalGCC + $GCcobradaC;
        $primaPorMesCC[$i] = $sumaseguradaCC;
        $comisionPorMesC[$i] = $sumaseguradaComC;
        $comisionGCC[$i] = $GCcobradaC;
        $perGCCC1[$i] = ($comisionGCC[$i] / $comisionPorMesC[$i]) * 100;
        $totalperGCC1 = $totalperGCC1 + $perGCCC1[$i];
    }
    $totalperGCC1 = $totalperGCC1 / sizeof($mesC);
}

//--- Resumen/ramo.php
if ($pag == 'Resumen/ramo') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    if ($permiso != 3) {
        $ramo = $obj->get_distinct_element_ramo_pc($desde, $hasta, $cia, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $ramo = $obj->get_distinct_element_ramo_pc_by_user($desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
    }

    if ($ramo == 0) {
        header("Location: busqueda_ramo.php?m=2");
    }

    $ramoArray[] = null;
    $sumatotalRamo[sizeof($ramo)] = null;
    $cantArray[sizeof($ramo)] = null;

    $sumatotalRamoPC[sizeof($ramo)] = null;
    $sumatotalRamoCC[sizeof($ramo)] = null;

    for ($i = 0; $i < sizeof($ramo); $i++) {

        if ($permiso != 3) {
            $ramoPoliza = $obj->get_poliza_graf_1_pc($ramo[$i]['nramo'], $desde, $hasta, $cia, $tipo_cuenta);
        }
        if ($permiso == 3) {
            $ramoPoliza = $obj->get_poliza_graf_1_pc_by_user($ramo[$i]['nramo'], $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
        }

        $sumasegurada = 0;
        $prima_cobrada = 0;
        $comision_cobrada = 0;
        $gc_pagada = 0;

        $ramoPolizaCant = ($ramoPoliza != 0) ? sizeof($ramoPoliza) : 0;
        for ($a = 0; $a < $ramoPolizaCant; $a++) {
            $prima_cobrada = $prima_cobrada + $ramoPoliza[$a]['prima_com'];
            $comision_cobrada = $comision_cobrada + $ramoPoliza[$a]['comision'];

            $gc_pagada = $gc_pagada + (($ramoPoliza[$a]['per_gc'] * $ramoPoliza[$a]['comision']) / 100);
        }
        $totalComisionCobrada = $totalComisionCobrada + $comision_cobrada;
        $totalGCPagada = $totalGCPagada + $gc_pagada;

        if ($prima_cobrada == 0) {
            $per_gc = 0;
        } else {
            $per_gc = (($comision_cobrada * 100) / $prima_cobrada);
        }

        $sumasegurada = 0;

        if ($permiso != 3) {
            $resumen_poliza = $obj->get_resumen_por_ramo_en_poliza($desde, $hasta, $ramo[$i]['nramo']);
        }
        if ($permiso == 3) {
            $resumen_poliza = $obj->get_resumen_por_ramo_en_poliza_by_user($desde, $hasta, $ramo[$i]['nramo'], $asesor_u);
        }

        $cResumen_poliza = ($resumen_poliza != 0) ? sizeof($resumen_poliza) : 0;
        $cantArray[$i] = $cResumen_poliza;
        $totalCant = $totalCant + $cResumen_poliza;
        for ($f = 0; $f < $cResumen_poliza; $f++) {

            $sumasegurada = $sumasegurada + $resumen_poliza[$f]['prima'];
        }
        $totals = $totals + $sumasegurada;
        $totalpc = $totalpc + $prima_cobrada;
        $totalcc = $totalcc + $comision_cobrada;
        $totalgcp = $totalgcp + $gc_pagada;
        $sumatotalRamo[$i] = $sumasegurada;
        $sumatotalRamoPC[$i] = $prima_cobrada;
        $sumatotalRamoCC[$i] = $comision_cobrada;
        $sumatotalRamoGCP[$i] = $gc_pagada;
        $ramoArray[$i] = $ramo[$i]['nramo'];
    }
    asort($sumatotalRamoCC, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalRamoCC as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($ramo) > 10) ? sizeof($ramo) - 10 : 0;
}

//--- Resumen/prima_mes.php
if ($pag == 'Resumen/prima_mes') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    $desde = $_GET['anio'] . '-01-01';
    $hasta = ($_GET['anio']) . '-12-31';

    $mes = $obj->get_mes_prima_BN();

    $mes = $obj->get_mes_prima_pc($desde, $hasta, $cia, $ramo, $tipo_cuenta, '1');

    //$mes = ['01','02','03','04','05','06','07','08','09','10','11','12'];

    $mesArray[] = null;
    $cantArray[] = null;
    $primaPorMes[] = null;

    $sumatotalMes[] = null;
    $sumatotalMesPC[] = null;
    $sumatotalMesCC[] = null;

    for ($i = 0; $i < sizeof($mes); $i++) {
        $desde = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
        $hasta = $_GET['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-31";

        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_grafp_2_pc($ramo, $desde, $hasta, $cia, $tipo_cuenta);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_grafp_2_pc_by_user($ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
        }

        if ($primaMes == 0) {
            header("Location: busqueda_prima_mes.php?m=2");
        }

        $sumasegurada = 0;
        $prima_cobrada = 0;
        $comision_cobrada = 0;
        $gc_pagada = 0;

        $cPrimaMes = ($primaMes != 0) ? sizeof($primaMes) : 0;
        for ($a = 0; $a < $cPrimaMes; $a++) {
            $sumasegurada = $sumasegurada + $primaMes[$a]['prima'];
            $prima_cobrada = $prima_cobrada + $primaMes[$a]['prima_com'];
            $comision_cobrada = $comision_cobrada + $primaMes[$a]['comision'];
            $gc_pagada = $gc_pagada + (($primaMes[$a]['per_gc'] * $primaMes[$a]['comision']) / 100);
        }
        $totalComisionCobrada = $totalComisionCobrada + $comision_cobrada;
        $totalGCPagada = $totalGCPagada + $gc_pagada;

        if ($prima_cobrada == 0) {
            $per_gc = 0;
        } else {
            $per_gc = (($comision_cobrada * 100) / $prima_cobrada);
        }
        $sumasegurada = 0;

        if ($permiso != 3) {
            $resumen_poliza = $obj->get_resumen_por_mes_en_poliza($desde, $hasta, $mes[$i]["Month(f_desdepoliza)"]);
        }
        if ($permiso == 3) {
            $resumen_poliza = $obj->get_resumen_por_mes_en_poliza_by_user($desde, $hasta, $mes[$i]["Month(f_desdepoliza)"], $asesor_u);
        }

        $cantArray[$i] = sizeof($resumen_poliza);
        $totalCant = $totalCant + sizeof($resumen_poliza);
        for ($f = 0; $f < sizeof($resumen_poliza); $f++) {
            $sumasegurada = $sumasegurada + $resumen_poliza[$f]['prima'];
        }
        $totals = $totals + $sumasegurada;
        $totalpc = $totalpc + $prima_cobrada;
        $totalcc = $totalcc + $comision_cobrada;
        $totalgcp = $totalgcp + $gc_pagada;
        $primaPorMes[$i] = $sumasegurada;
        $primaPorMesPC[$i] = $prima_cobrada;
        $primaPorMesCC[$i] = $comision_cobrada;
        $primaPorMesGCP[$i] = $gc_pagada;
    }
}

//--- Resumen/cia.php
if ($pag == 'Resumen/cia') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    if ($permiso != 3) {
        $cia = $obj->get_distinct_element_cia_pc($desde, $hasta, $ramo, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $cia = $obj->get_distinct_element_cia_pc_by_user($desde, $hasta, $ramo, $tipo_cuenta, $asesor_u);
    }

    if ($cia == 0) {
        header("Location: busqueda_cia.php?m=2");
    }

    $ciaArray[sizeof($cia)] = null;
    $sumatotalCia[sizeof($cia)] = null;
    $cantArray[sizeof($cia)] = null;
    $sumatotalCiaPC[sizeof($cia)] = null;
    $sumatotalCiaCC[sizeof($cia)] = null;

    for ($i = 0; $i < sizeof($cia); $i++) {

        if ($permiso != 3) {
            $ciaPoliza = $obj->get_poliza_graf_3_pc($cia[$i]['nomcia'], $ramo, $desde, $hasta, $tipo_cuenta);
        }
        if ($permiso == 3) {
            $ciaPoliza = $obj->get_poliza_graf_3_pc_by_user($cia[$i]['nomcia'], $ramo, $desde, $hasta, $tipo_cuenta, $asesor_u);
        }

        $sumasegurada = 0;
        $prima_cobrada = 0;
        $comision_cobrada = 0;
        $gc_pagada = 0;

        $ciaPolizaCant = ($ciaPoliza != 0) ? sizeof($ciaPoliza) : 0;
        for ($a = 0; $a < $ciaPolizaCant; $a++) {

            $prima_cobrada = $prima_cobrada + $ciaPoliza[$a]['prima_com'];
            $comision_cobrada = $comision_cobrada + $ciaPoliza[$a]['comision'];

            $gc_pagada = $gc_pagada + (($ciaPoliza[$a]['per_gc'] * $ciaPoliza[$a]['comision']) / 100);
        }
        $totalComisionCobrada = $totalComisionCobrada + $comision_cobrada;
        $totalGCPagada = $totalGCPagada + $gc_pagada;
        if ($prima_cobrada == 0) {
            $per_gc = 0;
        } else {
            $per_gc = (($comision_cobrada * 100) / $prima_cobrada);
        }
        $sumasegurada = 0;

        if ($permiso != 3) {
            $resumen_poliza = $obj->get_resumen_por_cia_en_poliza($desde, $hasta, $cia[$i]['nomcia']);
        }
        if ($permiso == 3) {
            $resumen_poliza = $obj->get_resumen_por_cia_en_poliza_by_user($desde, $hasta, $cia[$i]['nomcia'], $asesor_u);
        }

        $cResumen_poliza = ($resumen_poliza != 0) ? sizeof($resumen_poliza) : 0;
        $cantArray[$i] = $cResumen_poliza;
        $totalCant = $totalCant + $cResumen_poliza;
        for ($f = 0; $f < $cResumen_poliza; $f++) {

            $sumasegurada = $sumasegurada + $resumen_poliza[$f]['prima'];
        }

        $totals = $totals + $sumasegurada;
        $totalpc = $totalpc + $prima_cobrada;
        $totalcc = $totalcc + $comision_cobrada;
        $totalgcp = $totalgcp + $gc_pagada;
        $sumatotalCia[$i] = $sumasegurada;
        $sumatotalCiaPC[$i] = $prima_cobrada;
        $sumatotalCiaCC[$i] = $comision_cobrada;
        $sumatotalCiaGCP[$i] = $gc_pagada;
        $ciaArray[$i] = $cia[$i]['nomcia'];
    }
    asort($sumatotalCiaCC, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalCiaCC as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : 0;
}

//--- Resumen/tipo_poliza.php
if ($pag == 'Resumen/tipo_poliza') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    if ($permiso != 3) {
        $tpoliza = $obj->get_distinct_element_tpoliza_pc($desde, $hasta, $cia, $ramo, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $tpoliza = $obj->get_distinct_element_tpoliza_pc_by_user($desde, $hasta, $cia, $ramo, $tipo_cuenta, $asesor_u);
    }

    if ($tpoliza == 0) {
        header("Location: busqueda_tipo_poliza.php?m=2");
    }

    $tpolizaArray[] = null;
    $sumatotalTpoliza[sizeof($tpoliza)] = null;
    $cantArray[sizeof($tpoliza)] = null;

    $sumatotalTpolizaPC[sizeof($tpoliza)] = null;
    $sumatotalTpolizaCC[sizeof($tpoliza)] = null;

    for ($i = 0; $i < sizeof($tpoliza); $i++) {

        if ($permiso != 3) {
            $tpolizaPoliza = $obj->get_poliza_graf_2_pc($tpoliza[$i]['tipo_poliza'], $ramo, $desde, $hasta, $cia, $tipo_cuenta);
        }
        if ($permiso == 3) {
            $tpolizaPoliza = $obj->get_poliza_graf_2_pc_by_user($tpoliza[$i]['tipo_poliza'], $ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
        }
        $sumasegurada = 0;
        $prima_cobrada = 0;
        $comision_cobrada = 0;
        $gc_pagada = 0;

        $tpolizaPolizaCant = ($tpolizaPoliza != 0) ? sizeof($tpolizaPoliza) : 0;
        for ($a = 0; $a < $tpolizaPolizaCant; $a++) {
            $sumasegurada = $sumasegurada + $tpolizaPoliza[$a]['prima'];

            $prima_cobrada = $prima_cobrada + $tpolizaPoliza[$a]['prima_com'];
            $comision_cobrada = $comision_cobrada + $tpolizaPoliza[$a]['comision'];

            $gc_pagada = $gc_pagada + (($tpolizaPoliza[$a]['per_gc'] * $tpolizaPoliza[$a]['comision']) / 100);
        }

        $totalComisionCobrada = $totalComisionCobrada + $comision_cobrada;
        $totalGCPagada = $totalGCPagada + $gc_pagada;

        if ($prima_cobrada == 0) {
            $per_gc = 0;
        } else {
            $per_gc = (($comision_cobrada * 100) / $prima_cobrada);
        }

        $sumasegurada = 0;

        if ($permiso != 3) {
            $resumen_poliza = $obj->get_resumen_por_tpoliza_en_poliza($desde, $hasta, $tpoliza[$i]['tipo_poliza']);
        }
        if ($permiso == 3) {
            $resumen_poliza = $obj->get_resumen_por_tpoliza_en_poliza_by_user($desde, $hasta, $tpoliza[$i]['tipo_poliza'], $asesor_u);
        }

        $cResumen_poliza = ($resumen_poliza != 0) ? sizeof($resumen_poliza) : 0;
        $cantArray[$i] = $cResumen_poliza;
        $totalCant = $totalCant + $cResumen_poliza;
        for ($f = 0; $f < $cResumen_poliza; $f++) {

            $sumasegurada = $sumasegurada + $resumen_poliza[$f]['prima'];
        }
        $totals = $totals + $sumasegurada;
        $totalpc = $totalpc + $prima_cobrada;
        $totalcc = $totalcc + $comision_cobrada;
        $totalgcp = $totalgcp + $gc_pagada;
        $sumatotalTpoliza[$i] = $sumasegurada;
        $sumatotalTpolizaPC[$i] = $prima_cobrada;
        $sumatotalTpolizaCC[$i] = $comision_cobrada;
        $sumatotalTpolizaGCP[$i] = $gc_pagada;
        $tpolizaArray[$i] = $tpoliza[$i]['tipo_poliza'];
    }
    asort($sumatotalTpolizaCC, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalTpolizaCC as $key => $value) {

        $x[count($x)] = $key;
    }
}

//--- Resumen/ejecutivo.php
if ($pag == 'Resumen/ejecutivo') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    $ejecutivo = $obj->get_distinct_element_ejecutivo_pc($desde, $hasta, $cia, $ramo, $tipo_cuenta);

    if ($ejecutivo == 0) {
        header("Location: busqueda_ejecutivo.php?m=2");
    }

    $ejecutivoArray[] = null;
    $codEj[] = null;
    $sumatotalEjecutivo[sizeof($ejecutivo)] = null;
    $sumatotalEjecutivoPC[sizeof($ejecutivo)] = null;
    $sumatotalEjecutivoCC[sizeof($ejecutivo)] = null;
    $cantArray[sizeof($ejecutivo)] = null;

    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $nombre = $ejecutivo[$i]['nombre'];
        $cod = $ejecutivo[$i]['codvend'];

        $resumen = $obj->get_resumen_por_asesor($desde, $hasta, $ejecutivo[$i]['codvend'], $cia, $ramo, $tipo_cuenta);

        $prima_cobrada = 0;
        $comision_cobrada = 0;
        $gc_pagada = 0;

        $cResumen = ($resumen != 0) ? sizeof($resumen) : 0;
        for ($a = 0; $a < $cResumen; $a++) {
            $prima_cobrada = $prima_cobrada + $resumen[$a]['prima_com'];
            $comision_cobrada = $comision_cobrada + $resumen[$a]['comision'];
            $gc_pagada = $gc_pagada + (($resumen[$a]['per_gc'] * $resumen[$a]['comision']) / 100);
        }
        $totalComisionCobrada = $totalComisionCobrada + $comision_cobrada;
        $totalGCPagada = $totalGCPagada + $gc_pagada;
        if ($prima_cobrada == 0) {
            $per_gc = 0;
        } else {
            $per_gc = (($comision_cobrada * 100) / $prima_cobrada);
        }

        $sumasegurada = 0;
        $resumen_poliza = $obj->get_resumen_por_asesor_en_poliza($desde, $hasta, $ejecutivo[$i]['codvend'], $cia, $ramo, $tipo_cuenta);

        $cResumen_poliza = ($resumen_poliza != 0) ? sizeof($resumen_poliza) : 0;
        $cantArray[$i] = $cResumen_poliza;
        $totalCant = $totalCant + $cResumen_poliza;
        for ($a = 0; $a < $cResumen_poliza; $a++) {
            $sumasegurada = $sumasegurada + $resumen_poliza[$a]['prima'];
        }
        $totals = $totals + $sumasegurada;
        $totalpc = $totalpc + $prima_cobrada;
        $totalcc = $totalcc + $comision_cobrada;
        $totalgcp = $totalgcp + $gc_pagada;
        $totalcantt = $totalcantt + $cResumen;
        $sumatotalEjecutivo[$i] = $sumasegurada;
        $sumatotalEjecutivoPC[$i] = $prima_cobrada;
        $sumatotalEjecutivoCC[$i] = $comision_cobrada;
        $sumatotalEjecutivoGCP[$i] = $gc_pagada;
        $ejecutivoArray[$i] = $nombre;
        $codEj[$i] = $cod;
    }
    asort($sumatotalEjecutivoCC, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalEjecutivoCC as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($ejecutivo) > 10) ? sizeof($ejecutivo) - 10 : 0;
}

//--- Resumen/fpago.php
if ($pag == 'Resumen/fpago') {
    isset($_GET["tipo_cuenta"]) ? $tipo_cuenta = $_GET["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_GET["ramo"]) ? $ramo = $_GET["ramo"] : $ramo = '';
    isset($_GET["cia"]) ? $cia = $_GET["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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
        $fechaMin = $obj->get_fecha_min_max('MIN', 'f_desdepoliza', 'poliza');
        $desde = $fechaMin[0]['MIN(f_desdepoliza)'];

        $fechaMax = $obj->get_fecha_min_max('MAX', 'f_desdepoliza', 'poliza');
        $hasta = $fechaMax[0]['MAX(f_desdepoliza)'];
    }

    if ($permiso != 3) {
        $fpago = $obj->get_distinct_element_fpago_pc($desde, $hasta, $cia, $ramo, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $fpago = $obj->get_distinct_element_fpago_pc_by_user($desde, $hasta, $cia, $ramo, $tipo_cuenta, $asesor_u);
    }

    if ($fpago == 0) {
        header("Location: busqueda_fpago.php?m=2");
    }

    $fpagoArray[] = null;
    $sumatotalFpago[] = null;
    $cantArray[] = null;

    $sumatotalFpagoPC[] = null;
    $sumatotalFpagoCC[] = null;

    for ($i = 0; $i < sizeof($fpago); $i++) {

        if ($permiso != 3) {
            $fpagoPoliza = $obj->get_poliza_graf_4_pc($fpago[$i]['fpago'], $ramo, $desde, $hasta, $cia, $tipo_cuenta);
        }
        if ($permiso == 3) {
            $fpagoPoliza = $obj->get_poliza_graf_4_pc_by_user($fpago[$i]['fpago'], $ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
        }
        $sumasegurada = 0;
        $prima_cobrada = 0;
        $comision_cobrada = 0;
        $gc_pagada = 0;

        $fpagoPolizaCant = ($fpagoPoliza != 0) ? sizeof($fpagoPoliza) : 0;
        for ($a = 0; $a < $fpagoPolizaCant; $a++) {
            $sumasegurada = $sumasegurada + $fpagoPoliza[$a]['prima'];

            $prima_cobrada = $prima_cobrada + $fpagoPoliza[$a]['prima_com'];
            $comision_cobrada = $comision_cobrada + $fpagoPoliza[$a]['comision'];

            $gc_pagada = $gc_pagada + (($fpagoPoliza[$a]['per_gc'] * $fpagoPoliza[$a]['comision']) / 100);
        }
        $totalComisionCobrada = $totalComisionCobrada + $comision_cobrada;
        $totalGCPagada = $totalGCPagada + $gc_pagada;

        if ($prima_cobrada == 0) {
            $per_gc = 0;
        } else {
            $per_gc = (($comision_cobrada * 100) / $prima_cobrada);
        }
        $sumasegurada = 0;

        if ($permiso != 3) {
            $resumen_poliza = $obj->get_resumen_por_fpago_en_poliza($desde, $hasta, $fpago[$i]['fpago']);
        }
        if ($permiso == 3) {
            $resumen_poliza = $obj->get_resumen_por_fpago_en_poliza_by_user($desde, $hasta, $fpago[$i]['fpago'], $asesor_u);
        }

        $cantArray[$i] = sizeof($resumen_poliza);
        $totalCant = $totalCant + sizeof($resumen_poliza);
        for ($f = 0; $f < sizeof($resumen_poliza); $f++) {

            $sumasegurada = $sumasegurada + $resumen_poliza[$f]['prima'];
        }
        $totals = $totals + $sumasegurada;
        $totalpc = $totalpc + $prima_cobrada;
        $totalcc = $totalcc + $comision_cobrada;
        $totalgcp = $totalgcp + $gc_pagada;
        $sumatotalFpago[$i] = $sumasegurada;
        $sumatotalFpagoPC[$i] = $prima_cobrada;
        $sumatotalFpagoCC[$i] = $comision_cobrada;
        $sumatotalFpagoGCP[$i] = $gc_pagada;
        $fpagoArray[$i] = $fpago[$i]['fpago'];
    }
    asort($sumatotalFpagoCC, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalFpagoCC as $key => $value) {
        $x[count($x)] = $key;
    }
}

//-------- DBLCLICK ON GRAPHICS  --------------

//--- PorcentajeL/poliza_ramo.php
if ($pag == 'PorcentajeL/poliza_ramo') {
    $desde = $_GET["desde"];
    $hasta = $_GET["hasta"];

    $ramo = $_GET['ramo'];

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_poliza_graf_1_by_user($ramo, $desde, $hasta, $cia, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_poliza_graf_1($ramo, $desde, $hasta, $cia, $tipo_cuenta);
    }
}

//--- PorcentajeL/t_poliza.php
if ($pag == 'PorcentajeL/t_poliza') {
    $desde = $_GET["desde"];
    $hasta = $_GET["hasta"];
    $tpoliza = $_GET['tpoliza'];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_poliza_graf_2_by_user($tpoliza, $ramo, $desde, $hasta, $cia, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_poliza_graf_2($tpoliza, $ramo, $desde, $hasta, $cia, $tipo_cuenta);
    }
}

//--- PorcentajeL/cia.php
if ($pag == 'PorcentajeL/cia') {
    $desde = $_GET["desde"];
    $hasta = $_GET["hasta"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_poliza_graf_3_by_user($cia, $ramo, $desde, $hasta, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_poliza_graf_3($cia, $ramo, $desde, $hasta, $tipo_cuenta);
    }
}

//--- PorcentajeL/fpago.php
if ($pag == 'PorcentajeL/fpago') {
    $desde = $_GET["desde"];
    $hasta = $_GET["hasta"];
    $fpago = $_GET['fpago'];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_poliza_graf_4_by_user($fpago, $ramo, $desde, $hasta, $cia, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_poliza_graf_4($fpago, $ramo, $desde, $hasta, $cia, $tipo_cuenta);
    }
}

//--- PorcentajeL/ejecutivo.php
if ($pag == 'PorcentajeL/ejecutivo') {
    $desde = $_GET["desde"];
    $hasta = $_GET["hasta"];
    $ejecutivo = $_GET['ejecutivo'];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $polizasC = $obj->get_poliza_graf_5($ejecutivo, $ramo, $desde, $hasta, $cia, $tipo_cuenta);
}

//--- Primas_SuscritasL/prima_mes.php
if ($pag == 'Primas_SuscritasL/prima_mes') {
    $anio = $_GET["anio"];
    $mes = $_GET['mes'];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $desde = $anio . "-" . $mes . "-01";
    $hasta = $anio . "-" . $mes . "-31";

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_poliza_grafp_2_list_by_user($ramo, $desde, $hasta, $cia, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_poliza_grafp_2_list($ramo, $desde, $hasta, $cia, $tipo_cuenta);
    }
}

//--- Primas_CobradasL/poliza_ramo.php
if ($pag == 'Primas_CobradasL/poliza_ramo') {
    $anio = $_GET["anio"];

    $ramo = $_GET['ramo'];

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_ramo_by_user($ramo, $cia, $anio, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_ramo($ramo, $cia, $anio, $tipo_cuenta);
    }
}

//--- Primas_CobradasL/poliza_cia.php
if ($pag == 'Primas_CobradasL/poliza_cia') {
    $anio = $_GET["anio"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_cia_by_user($ramo, $cia, $anio, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_cia($ramo, $cia, $anio, $tipo_cuenta);
    }
}

//--- Primas_CobradasL/poliza_tpoliza.php
if ($pag == 'Primas_CobradasL/poliza_tpoliza') {
    $anio = $_GET["anio"];
    $tipo_poliza = $_GET["tipo_poliza"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_tpoliza_by_user($ramo, $cia, $anio, $tipo_cuenta, $tipo_poliza, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_tpoliza($ramo, $cia, $anio, $tipo_cuenta, $tipo_poliza);
    }
}

//--- Primas_CobradasL/poliza_fpago.php
if ($pag == 'Primas_CobradasL/poliza_fpago') {
    $anio = $_GET["anio"];
    $fpago = $_GET["fpago"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_fpago_by_user($ramo, $cia, $anio, $tipo_cuenta, $fpago, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_fpago($ramo, $cia, $anio, $tipo_cuenta, $fpago);
    }
}

//--- Primas_CobradasL/poliza_ejecutivo.php
if ($pag == 'Primas_CobradasL/poliza_ejecutivo') {
    $anio = $_GET["anio"];
    $ejecutivo = $_GET["ejecutivo"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $polizasC = $obj->get_distinct_id_poliza_c_cobrada_ejecutivo($ramo, $cia, $anio, $tipo_cuenta, $ejecutivo);
}

//--- Primas_CobradasL/poliza_mes.php
if ($pag == 'Primas_CobradasL/poliza_mes') {
    $anio = $_GET["anio"];
    $mes = $_GET["mes"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    if ($mes < 10) {
        $desde = $anio . "-0" . $mes . "-01";
        $hasta = $anio . "-0" . $mes . "-31";
    } else {
        $desde = $anio . "-" . $mes . "-01";
        $hasta = $anio . "-" . $mes . "-31";
    }

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_mes_by_user($ramo, $cia, $desde, $hasta, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_mes($ramo, $cia, $desde, $hasta, $tipo_cuenta);
    }
}

//--- ComparativoL/ramo.php
if ($pag == 'ComparativoL/ramo') {
    $anio = $_GET["anio"];
    $mes = $_GET["mes"];

    $ramo = $_GET['ramo'];

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_poliza_graf_1_comp_by_user($ramo, $anio, $mes, $cia, $tipo_cuenta, $user[0]['cod_vend']);

        $polizasCold = $obj->get_poliza_graf_1_comp_by_user($ramo, $anio-1, $mes, $cia, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_poliza_graf_1_comp($ramo, $anio, $mes, $cia, $tipo_cuenta);

        $polizasCold = $obj->get_poliza_graf_1_comp($ramo, $anio-1, $mes, $cia, $tipo_cuenta);
    }
    $canpolizasC = ($polizasC == 0) ? 0 : sizeof($polizasC);
    $canpolizasCold = ($polizasCold == 0) ? 0 : sizeof($polizasCold);
}

//--- ComparativoL/cia.php
if ($pag == 'ComparativoL/cia') {
    $anio = $_GET["anio"];
    $mes = $_GET["mes"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_poliza_graf_3_by_user_comp($cia, $ramo, $anio, $mes, $tipo_cuenta, $user[0]['cod_vend']);

        $polizasCold = $obj->get_poliza_graf_3_by_user_comp($cia, $ramo, $anio-1, $mes, $tipo_cuenta, $user[0]['cod_vend']);
    } else {
        $polizasC = $obj->get_poliza_graf_3_comp($cia, $ramo, $anio, $mes, $tipo_cuenta);

        $polizasCold = $obj->get_poliza_graf_3_comp($cia, $ramo, $anio-1, $mes, $tipo_cuenta);
    }
    $canpolizasC = ($polizasC == 0) ? 0 : sizeof($polizasC);
    $canpolizasCold = ($polizasCold == 0) ? 0 : sizeof($polizasCold);
}

//--- ComparativoL/ejecutivo.php
if ($pag == 'ComparativoL/ejecutivo') {
    $anio = $_GET["anio"];
    $mes = $_GET["mes"];
    $ejecutivo = $_GET['codvend'];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $polizasC = $obj->get_poliza_graf_5_comp($ejecutivo, $ramo, $anio, $mes, $cia, $tipo_cuenta);

    $polizasCold = $obj->get_poliza_graf_5_comp($ejecutivo, $ramo, $anio-1, $mes, $cia, $tipo_cuenta);

    $canpolizasC = ($polizasC == 0) ? 0 : sizeof($polizasC);
    $canpolizasCold = ($polizasCold == 0) ? 0 : sizeof($polizasCold);
}

//--- ComparativoL/poliza_ramo.php
if ($pag == 'ComparativoL/poliza_ramo') {
    $anio = $_GET["anio"];
    $mes = $_GET["mes"];

    $ramo = $_GET['ramo'];

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_ramo_by_user_comp($ramo, $cia, $anio, $tipo_cuenta, $user[0]['cod_vend'], $mes);

        $polizasCold = $obj->get_distinct_id_poliza_c_cobrada_ramo_by_user_comp($ramo, $cia, $anio-1, $tipo_cuenta, $user[0]['cod_vend'], $mes);
    } else {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_ramo_comp($ramo, $cia, $anio, $tipo_cuenta, $mes);

        $polizasCold = $obj->get_distinct_id_poliza_c_cobrada_ramo_comp($ramo, $cia, $anio-1, $tipo_cuenta, $mes);
    }

    $canpolizasC = ($polizasC == 0) ? 0 : sizeof($polizasC);
    $canpolizasCold = ($polizasCold == 0) ? 0 : sizeof($polizasCold);
}

//--- ComparativoL/poliza_cia.php
if ($pag == 'ComparativoL/poliza_cia') {
    $anio = $_GET["anio"];
    $mes = $_GET["mes"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);

    if ($user[0]['cod_vend'] != '') {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_cia_by_user_comp($ramo, $cia, $anio, $tipo_cuenta, $user[0]['cod_vend'], $mes);

        $polizasCold = $obj->get_distinct_id_poliza_c_cobrada_cia_by_user_comp($ramo, $cia, $anio-1, $tipo_cuenta, $user[0]['cod_vend'], $mes);
    } else {
        $polizasC = $obj->get_distinct_id_poliza_c_cobrada_cia_comp($ramo, $cia, $anio, $tipo_cuenta, $mes);

        $polizasCold = $obj->get_distinct_id_poliza_c_cobrada_cia_comp($ramo, $cia, $anio-1, $tipo_cuenta, $mes);
    }

    $canpolizasC = ($polizasC == 0) ? 0 : sizeof($polizasC);
    $canpolizasCold = ($polizasCold == 0) ? 0 : sizeof($polizasCold);
}

//--- ComparativoL/poliza_ejecutivo.php
if ($pag == 'ComparativoL/poliza_ejecutivo') {
    $anio = $_GET["anio"];
    $mes = $_GET["mes"];
    $codvend = $_GET["codvend"];

    $ramo = $_GET['ramo'];
    $ramo = stripslashes($ramo);
    $ramo = urldecode($ramo);
    $ramo = unserialize($ramo);

    $cia = $_GET['cia'];
    $cia = stripslashes($cia);
    $cia = urldecode($cia);
    $cia = unserialize($cia);

    $tipo_cuenta = $_GET['tipo_cuenta'];
    $tipo_cuenta = stripslashes($tipo_cuenta);
    $tipo_cuenta = urldecode($tipo_cuenta);
    $tipo_cuenta = unserialize($tipo_cuenta);

    $asesor_u = $_GET['asesor_u'];
    $asesor_u = stripslashes($asesor_u);
    $asesor_u = urldecode($asesor_u);
    $asesor_u = unserialize($asesor_u);

    $polizasC = $obj->get_distinct_id_poliza_c_cobrada_ejecutivo_comp($ramo, $cia, $anio, $tipo_cuenta, $codvend, $mes);

    $polizasCold = $obj->get_distinct_id_poliza_c_cobrada_ejecutivo_comp($ramo, $cia, $anio-1, $tipo_cuenta, $codvend, $mes);

    $canpolizasC = ($polizasC == 0) ? 0 : sizeof($polizasC);
    $canpolizasCold = ($polizasCold == 0) ? 0 : sizeof($polizasCold);
}