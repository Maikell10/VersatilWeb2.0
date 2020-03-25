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

//--- Porcentaje/ramo.php
if ($pag == 'Porcentaje/ramo') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

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

    $contador = (sizeof($ramo) > 10) ? sizeof($ramo) - 10 : sizeof($ramo);
}

//--- Porcentaje/tipo_poliza.php
if ($pag == 'Porcentaje/tipo_poliza') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

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
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

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

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : sizeof($cia);
}

//--- Porcentaje/fpago.php
if ($pag == 'Porcentaje/fpago') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

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
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

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

    $ejecutivo = $obj->get_distinct_element_ejecutivo_ps($desde, $hasta, $cia, $ramo, $tipo_cuenta);

    if ($ejecutivo == 0) {
        header("Location: busqueda_ejecutivo.php?m=2");
    }

    $ejecutivoArray[sizeof($ejecutivo)] = null;
    $sumatotalEjecutivo[sizeof($ejecutivo)] = null;
    $cantArray[sizeof($ejecutivo)] = null;

    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $ejecutivoPoliza = $obj->get_poliza_graf_prima_c_6($ejecutivo[$i]['codvend'], $ramo, $desde, $hasta, $cia, $tipo_cuenta);

        $ejecutivoArray[$i] = $ejecutivo[$i]['nombre'];
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

    $contador = (sizeof($ejecutivo) > 10) ? sizeof($ejecutivo) - 10 : sizeof($ejecutivo);
}

//--- Porcentaje/ramo_promedio.php
if ($pag == 'Porcentaje/ramo_promedio') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

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
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $desde = $_POST['anio'] . '-01-01';
    $hasta = ($_POST['anio']) . '-12-31';

    $mes = $obj->get_mes_prima($desde, $hasta, $cia, $ramo, $tipo_cuenta, '1');

    $ramoArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;

    $primaPorMesPA[sizeof($mes)] = null;
    $primaPorMesR[sizeof($mes)] = null;

    if ($permiso != 3) {
        for ($i = 0; $i < sizeof($mes); $i++) {
            $desde = $_POST['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_POST['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-31";

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
        for ($i = 0; $i < sizeof($mes); $i++) {
            $desde = $_POST['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_POST['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-31";

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
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

    $mesA = $_POST['mes'];
    $numeroConCeros = str_pad($mesA, 2, "0", STR_PAD_LEFT);

    $desde = $_POST['anio'] . '-' . $numeroConCeros . '-01';
    $hasta = $_POST['anio'] . '-' . $numeroConCeros . '-31';

    $dia_mes = $obj->get_dia_mes_prima($desde, $hasta, $cia, $ramo, $tipo_cuenta);

    $ramoArray[sizeof($dia_mes)] = null;
    $cantArray[sizeof($dia_mes)] = null;
    $primaPorMes[sizeof($dia_mes)] = null;

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
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

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
        $ramo = $obj->get_distinct_ramo_prima_c($_POST['anio'], $cia, $tipo_cuenta);

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
            $primaMes = $obj->get_poliza_c_cobrada_ramo($ramo[$i]['nramo'], $cia, $_POST['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ramo($ramo[$i]['nramo'], $cia, $_POST['anio'], $tipo_cuenta);

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

                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-01-31')) {
                    $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-02-29')) {
                    $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-03-31')) {
                    $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-04-31')) {
                    $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-05-31')) {
                    $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-06-31')) {
                    $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-07-31')) {
                    $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-08-31')) {
                    $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-09-31')) {
                    $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-10-31')) {
                    $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-11-31')) {
                    $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-12-31')) {
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
        $ramo = $obj->get_distinct_ramo_prima_c_by_user($_POST['anio'], $cia, $tipo_cuenta, $asesor_u);

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
            $primaMes = $obj->get_poliza_c_cobrada_ramo_by_user($ramo[$i]['nramo'], $cia, $_POST['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ramo_by_user($ramo[$i]['nramo'], $cia, $_POST['anio'], $tipo_cuenta, $asesor_u);

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

                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-01-31')) {
                    $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-02-29')) {
                    $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-03-31')) {
                    $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-04-31')) {
                    $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-05-31')) {
                    $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-06-31')) {
                    $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-07-31')) {
                    $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-08-31')) {
                    $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-09-31')) {
                    $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-10-31')) {
                    $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-11-31')) {
                    $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                    $cantP = $cantP + 1;
                }
                if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-12-31')) {
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

    $contador = (sizeof($ramo) > 10) ? sizeof($ramo) - 10 : sizeof($ramo);
}

//--- Primas_Cobradas/prima_mes.php
if ($pag == 'Primas_Cobradas/prima_mes') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

    $desdeI = $_POST['anio'] . '-01-01';
    $hastaI = ($_POST['anio']) . '-12-31';
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

    $sumasegurada[sizeof(12)] = null;
    $p1[sizeof(12)] = null;
    $p2[sizeof(12)] = null;
    $p3[sizeof(12)] = null;
    $p4[sizeof(12)] = null;
    $p5[sizeof(12)] = null;
    $p6[sizeof(12)] = null;
    $p7[sizeof(12)] = null;
    $p8[sizeof(12)] = null;
    $p9[sizeof(12)] = null;
    $p10[sizeof(12)] = null;
    $p11[sizeof(12)] = null;
    $p12[sizeof(12)] = null;
    $totalP[sizeof(12)] = null;
    $totalMes[sizeof(12)] = null;

    $cantidad[sizeof(12)] = null;
    for ($i = 0; $i < 12; $i++) {
        if ($mes[$i]["Month(f_desdepoliza)"] < 10) {
            $desde = $_POST['anio'] . "-0" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_POST['anio'] . "-0" . $mes[$i]["Month(f_desdepoliza)"] . "-31";
        } else {
            $desde = $_POST['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-01";
            $hasta = $_POST['anio'] . "-" . $mes[$i]["Month(f_desdepoliza)"] . "-31";
        }
        $mesB = $i + 1;
        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_bn($ramo, $desde, $hasta, $cia, $mesB, $tipo_cuenta);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_bn_by_user($ramo, $desde, $hasta, $cia, $mesB, $tipo_cuenta, $asesor_u);
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

            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantMes = $cantMes + $primaMes[$a]['prima_com'];
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-12-31')) {
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

        $totalMes[$i] = $cantMes;
        $totalCant = $totalCant + $cantMes;

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];
    }
}

//--- Primas_Cobradas/cia.php
if ($pag == 'Primas_Cobradas/cia') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';

    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

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
        $cia = $obj->get_distinct_cia_prima_c($_POST['anio'], $ramo, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $cia = $obj->get_distinct_cia_prima_c_by_user($_POST['anio'], $ramo, $tipo_cuenta, $asesor_u);
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
            $primaMes = $obj->get_poliza_c_cobrada_cia($cia[$i]['nomcia'], $ramo, $_POST['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_cia($ramo, $cia[$i]['nomcia'], $_POST['anio'], $tipo_cuenta);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_cia_by_user($cia[$i]['nomcia'], $ramo, $_POST['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_cia_by_user($ramo, $cia[$i]['nomcia'], $_POST['anio'], $tipo_cuenta, $asesor_u);
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


            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-12-31')) {
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

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : sizeof($cia);
}

//--- Primas_Cobradas/tipo_poliza.php
if ($pag == 'Primas_Cobradas/tipo_poliza') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';
    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

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
        $tipo_poliza = $obj->get_distinct_tipo_poliza_prima_c($_POST['anio'], $ramo, $cia, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $tipo_poliza = $obj->get_distinct_tipo_poliza_prima_c_by_user($_POST['anio'], $ramo, $cia, $tipo_cuenta, $asesor_u);
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
            $primaMes = $obj->get_poliza_c_cobrada_tipo_poliza($tipo_poliza[$i]['tipo_poliza'], $cia, $ramo, $_POST['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_tpoliza($ramo, $cia, $_POST['anio'], $tipo_cuenta, $tipo_poliza[$i]['tipo_poliza']);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_tipo_poliza_by_user($tipo_poliza[$i]['tipo_poliza'], $cia, $ramo, $_POST['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_tpoliza_by_user($ramo, $cia, $_POST['anio'], $tipo_cuenta, $tipo_poliza[$i]['tipo_poliza'], $asesor_u);
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

            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-12-31')) {
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
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';
    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

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

    $ejecutivo = $obj->get_distinct_ejecutivo_prima_c($_POST['anio'], $ramo, $cia, $tipo_cuenta);
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


    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $primaMes = $obj->get_poliza_c_cobrada_ejecutivo($codEj[$i], $cia, $ramo, $_POST['anio'], $tipo_cuenta);

        $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_ejecutivo($ramo, $cia, $_POST['anio'], $tipo_cuenta, $codEj[$i]);

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

            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-12-31')) {
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

        $totalP[$i] = $prima_pagada1 + $prima_pagada2 + $prima_pagada3 + $prima_pagada4 + $prima_pagada5 + $prima_pagada6 + $prima_pagada7 + $prima_pagada8 + $prima_pagada9 + $prima_pagada10 + $prima_pagada11 + $prima_pagada12;

        $totalPC = $totalPC + $totalP[$i];

        $totalPArray[$i] = $totalP[$i];
    }
    asort($totalP, SORT_NUMERIC);

    $x = array();
    foreach ($totalP as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($ejecutivo) > 10) ? sizeof($ejecutivo) - 10 : sizeof($ejecutivo);
}

//--- Primas_Cobradas/f_pago.php
if ($pag == 'Primas_Cobradas/f_pago') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';
    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

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
        $f_pago = $obj->get_distinct_f_pago_prima_c($_POST['anio'], $ramo, $cia, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $f_pago = $obj->get_distinct_f_pago_prima_c_by_user($_POST['anio'], $ramo, $cia, $tipo_cuenta, $asesor_u);
    }

    $totalPArray[sizeof($f_pago)] = null;
    $totalPC = 0;

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

    for ($i = 0; $i < sizeof($f_pago); $i++) {
        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_c_cobrada_f_pago($f_pago[$i]['fpago'], $cia, $ramo, $_POST['anio'], $tipo_cuenta);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_fpago($ramo, $cia, $_POST['anio'], $tipo_cuenta, $f_pago[$i]['fpago']);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_c_cobrada_f_pago_by_user($f_pago[$i]['fpago'], $cia, $ramo, $_POST['anio'], $tipo_cuenta, $asesor_u);

            $cantidadPolizaR = $obj->get_count_poliza_c_cobrada_fpago_by_user($ramo, $cia, $_POST['anio'], $tipo_cuenta, $f_pago[$i]['fpago'], $asesor_u);
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

            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-01-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-01-31')) {
                $prima_pagada1 = $prima_pagada1 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-02-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-02-29')) {
                $prima_pagada2 = $prima_pagada2 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-03-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-03-31')) {
                $prima_pagada3 = $prima_pagada3 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-04-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-04-31')) {
                $prima_pagada4 = $prima_pagada4 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-05-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-05-31')) {
                $prima_pagada5 = $prima_pagada5 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-06-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-06-31')) {
                $prima_pagada6 = $prima_pagada6 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-07-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-07-31')) {
                $prima_pagada7 = $prima_pagada7 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-08-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-08-31')) {
                $prima_pagada8 = $prima_pagada8 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-09-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-09-31')) {
                $prima_pagada9 = $prima_pagada9 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-10-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-10-31')) {
                $prima_pagada10 = $prima_pagada10 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-11-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-11-31')) {
                $prima_pagada11 = $prima_pagada11 + $primaMes[$a]['prima_com'];
                $cantP = $cantP + 1;
            }
            if (($primaMes[$a]['f_pago_prima'] >= $_POST['anio'] . '-12-01') && ($primaMes[$a]['f_pago_prima'] <= $_POST['anio'] . '-12-31')) {
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
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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

    if ($permiso != 3) {
        $ramo = $obj->get_distinct_element_ramo_pc($desde, $hasta, $cia, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $ramo = $obj->get_distinct_element_ramo_pc_by_user($desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
    }

    $ramoArray[sizeof($ramo)] = null;
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

        for ($a = 0; $a < sizeof($ramoPoliza); $a++) {
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
        $cantArray[$i] = sizeof($resumen_poliza);
        $totalCant = $totalCant + sizeof($resumen_poliza);
        for ($f = 0; $f < sizeof($resumen_poliza); $f++) {

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

    $contador = (sizeof($ramo) > 10) ? sizeof($ramo) - 10 : sizeof($ramo);
}

//--- Comisiones_Cobradas/prima_mes.php
if ($pag == 'Comisiones_Cobradas/prima_mes') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';
    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

    $desde = $_POST['anio'] . '-01-01';
    $hasta = ($_POST['anio']) . '-12-31';

    $mes = $obj->get_mes_prima_BN();

    $mes = $obj->get_mes_prima_pc($desde, $hasta, $cia, $ramo, $tipo_cuenta, '1');

    $mesArray[sizeof($mes)] = null;
    $cantArray[sizeof($mes)] = null;
    $primaPorMes[sizeof($mes)] = null;

    $sumatotalMes[sizeof($mes)] = null;
    $sumatotalMesPC[sizeof($mes)] = null;
    $sumatotalMesCC[sizeof($mes)] = null;

    for ($i = 0; $i < sizeof($mes); $i++) {
        $desde = $_POST['anio'] . "-" . $mes[$i]["Month(f_hastapoliza)"] . "-01";
        $hasta = $_POST['anio'] . "-" . $mes[$i]["Month(f_hastapoliza)"] . "-31";

        if ($permiso != 3) {
            $primaMes = $obj->get_poliza_grafp_2_pc($ramo, $desde, $hasta, $cia, $tipo_cuenta);
        }
        if ($permiso == 3) {
            $primaMes = $obj->get_poliza_grafp_2_pc_by_user($ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor_u);
        }

        $sumasegurada = 0;
        $prima_cobrada = 0;
        $comision_cobrada = 0;
        $gc_pagada = 0;
        for ($a = 0; $a < sizeof($primaMes); $a++) {
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
            $resumen_poliza = $obj->get_resumen_por_mes_en_poliza($desde, $hasta, $mes[$i]["Month(f_hastapoliza)"]);
        }
        if ($permiso == 3) {
            $resumen_poliza = $obj->get_resumen_por_mes_en_poliza_by_user($desde, $hasta, $mes[$i]["Month(f_hastapoliza)"], $asesor_u);
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

//--- Comisiones_Cobradas/cia.php
if ($pag == 'Comisiones_Cobradas/cia') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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

    if ($permiso != 3) {
        $cia = $obj->get_distinct_element_cia_pc($desde, $hasta, $ramo, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $cia = $obj->get_distinct_element_cia_pc_by_user($desde, $hasta, $ramo, $tipo_cuenta, $asesor_u);
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

        for ($a = 0; $a < sizeof($ciaPoliza); $a++) {

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
        $cantArray[$i] = sizeof($resumen_poliza);
        $totalCant = $totalCant + sizeof($resumen_poliza);
        for ($f = 0; $f < sizeof($resumen_poliza); $f++) {

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

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : sizeof($cia);
}

//--- Comisiones_Cobradas/tipo_poliza.php
if ($pag == 'Comisiones_Cobradas/tipo_poliza') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';
    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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

    if ($permiso != 3) {
        $tpoliza = $obj->get_distinct_element_tpoliza_pc($desde, $hasta, $cia, $ramo, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $tpoliza = $obj->get_distinct_element_tpoliza_pc_by_user($desde, $hasta, $cia, $ramo, $tipo_cuenta, $asesor_u);
    }
    $tpolizaArray[sizeof($tpoliza)] = null;
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
        for ($a = 0; $a < sizeof($tpolizaPoliza); $a++) {
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
        $cantArray[$i] = sizeof($resumen_poliza);
        $totalCant = $totalCant + sizeof($resumen_poliza);
        for ($f = 0; $f < sizeof($resumen_poliza); $f++) {

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

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : sizeof($cia);
}

//--- Comisiones_Cobradas/fpago.php
if ($pag == 'Comisiones_Cobradas/fpago') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';
    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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

    if ($permiso != 3) {
        $fpago = $obj->get_distinct_element_fpago_pc($desde, $hasta, $cia, $ramo, $tipo_cuenta);
    }
    if ($permiso == 3) {
        $fpago = $obj->get_distinct_element_fpago_pc_by_user($desde, $hasta, $cia, $ramo, $tipo_cuenta, $asesor_u);
    }
    $fpagoArray[sizeof($fpago)] = null;
    $sumatotalFpago[sizeof($fpago)] = null;
    $cantArray[sizeof($fpago)] = null;

    $sumatotalFpagoPC[sizeof($fpago)] = null;
    $sumatotalFpagoCC[sizeof($fpago)] = null;

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
        for ($a = 0; $a < sizeof($fpagoPoliza); $a++) {
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

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : sizeof($cia);
}

//--- Comisiones_Cobradas/ejecutivo.php
if ($pag == 'Comisiones_Cobradas/ejecutivo') {
    isset($_POST["tipo_cuenta"]) ? $tipo_cuenta = $_POST["tipo_cuenta"] : $tipo_cuenta = '';
    isset($_POST["ramo"]) ? $ramo = $_POST["ramo"] : $ramo = '';
    isset($_POST["cia"]) ? $cia = $_POST["cia"] : $cia = '';

    //----------------------------------------------------------------------------
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $asesor_u = $user[0]['cod_vend'];
    $permiso = $_SESSION['id_permiso'];
    //---------------------------------------------------------------------------

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

    $ejecutivo = $obj->get_distinct_element_ejecutivo($desde, $hasta, $cia, $ramo, $tipo_cuenta);

    $ejecutivoArray[sizeof($ejecutivo)] = null;
    $sumatotalEjecutivo[sizeof($ejecutivo)] = null;
    $sumatotalEjecutivoPC[sizeof($ejecutivo)] = null;
    $sumatotalEjecutivoCC[sizeof($ejecutivo)] = null;
    $cantArray[sizeof($ejecutivo)] = null;

    for ($i = 0; $i < sizeof($ejecutivo); $i++) {
        $nombre = $ejecutivo[$i]['nombre'];
        
        $resumen = $obj->get_resumen_por_asesor($desde, $hasta, $ejecutivo[$i]['cod_vend'], $cia, $ramo, $tipo_cuenta);

        $prima_cobrada = 0;
        $comision_cobrada = 0;
        $gc_pagada = 0;
        for ($a = 0; $a < sizeof($resumen); $a++) {
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
        $resumen_poliza = $obj->get_resumen_por_asesor_en_poliza($desde, $hasta, $ejecutivo[$i]['cod_vend'], $cia, $ramo, $tipo_cuenta);
        $cantArray[$i] = sizeof($resumen_poliza);
        $totalCant = $totalCant + sizeof($resumen_poliza);
        for ($a = 0; $a < sizeof($resumen_poliza); $a++) {
            $sumasegurada = $sumasegurada + $resumen_poliza[$a]['prima'];
        }
        $totals = $totals + $sumasegurada;
        $totalpc = $totalpc + $prima_cobrada;
        $totalcc = $totalcc + $comision_cobrada;
        $totalgcp = $totalgcp + $gc_pagada;
        $totalcantt = $totalcantt + sizeof($resumen);
        $sumatotalEjecutivo[$i] = $sumasegurada;
        $sumatotalEjecutivoPC[$i] = $prima_cobrada;
        $sumatotalEjecutivoCC[$i] = $comision_cobrada;
        $sumatotalEjecutivoGCP[$i] = $gc_pagada;
        $ejecutivoArray[$i] = $nombre;
    }
    asort($sumatotalEjecutivoCC, SORT_NUMERIC);

    $x = array();
    foreach ($sumatotalEjecutivoCC as $key => $value) {

        $x[count($x)] = $key;
    }

    $contador = (sizeof($cia) > 10) ? sizeof($cia) - 10 : sizeof($cia);
}
