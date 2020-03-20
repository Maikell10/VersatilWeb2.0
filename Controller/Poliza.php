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

$mes_arr = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

$obj = new Asesor();

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

    $cia = (isset($_POST['cia'])) ? $_POST['cia'] : $_GET['cia'] ;
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

//--- add/crear_poliza.php
if ($pag == 'add/crear_poliza') {
    $usuario = $obj->get_element_by_id('usuarios', 'seudonimo', $_SESSION['seudonimo']);
    $ramo = $obj->get_element('dramo', 'cod_ramo');
    $cia = $obj->get_element('dcia', 'nomcia');
    $asesor = $obj->get_ejecutivo();
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
    $z_produc = ($_GET['z_produc'] == "PANAMÁ") ? 1 : 2;
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
            $tarjeta = $obj->agregarTarjeta($n_tarjeta, $cvv, $fechaVP, $titular_tarjeta, $bancoT);

            $id_tarjeta = $obj->get_last_element('tarjeta', 'id_tarjeta');

            $id_tarjeta = $id_tarjeta[0]['id_tarjeta'];
        }
    }

    $poliza = $obj->agregarPoliza($n_poliza, $fhoy, $femisionP, $t_cobertura, $fdesdeP, $fhastaP, $currency, $tipo_poliza, $sumaA, $z_produc, $codasesor, $ramo, $cia, $idtitular[0]['id_titular'], $idtomador[0]['id_titular'], $asesor_ind, $t_cuenta, $_SESSION['id_usuario'], $obs, $prima);

    $recibo = $obj->agregarRecibo($n_recibo, $fdesde_recibo, $fhasta_recibo, $prima, $f_pago, $n_cuotas, $monto_cuotas, $idtomador[0]['id_titular'], $idtitular[0]['id_titular'], $n_poliza, $forma_pago, $id_tarjeta);

    $vehiculo = $obj->agregarVehiculo($placa, $tipo, $marca, $modelo, $anio, $serial, $color, $categoria, $n_recibo);

    $tipo_poliza_print = ($tipo_poliza == 1) ? "Primer Año" : "";

    $ultima_poliza = $obj->get_last_element('poliza', 'id_poliza');

    $u_p = $ultima_poliza[0]['id_poliza'];
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
        echo 'Aún módulo para Proyecto no esta generado';
        exit();
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
