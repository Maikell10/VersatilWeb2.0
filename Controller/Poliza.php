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
    $polizas = $obj->get_poliza_pendiente();
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

//--- prima_detail.php
if ($pag == 'prima_detail') {
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
}

//--- prima_detail.php
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
        $newDesde[] = date("m", strtotime($polizas[$a]['f_desdepoliza']));
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
