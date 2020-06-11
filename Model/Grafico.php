<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . DS . 'Model' . DS . 'Poliza.php';

class Grafico extends Poliza
{
    public function get_distinct_element_ramo($desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
								INNER JOIN dramo, dcia WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
		      					f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta'
							ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							nomcia IN " . $ciaIn . "
							ORDER BY dramo.nramo ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_ramo_by_user($desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
								INNER JOIN dramo, dcia WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
		      					f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            poliza.codvend = '$asesor'
							ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            poliza.codvend = '$asesor' AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            poliza.codvend = '$asesor' AND
							nomcia IN " . $ciaIn . "
							ORDER BY dramo.nramo ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_1($ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								dcia.nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								dramo.nramo = '$ramo'
								ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								dramo.nramo = '$ramo'
								ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								dramo.nramo = '$ramo'
								ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								dcia.nomcia IN " . $ciaIn . " AND
								dramo.nramo = '$ramo'
								ORDER BY dramo.nramo ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_1_by_user($ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								dcia.nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								dramo.nramo = '$ramo'
								ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								dramo.nramo = '$ramo'
								ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								dramo.nramo = '$ramo'
								ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								dcia.nomcia IN " . $ciaIn . " AND
								dramo.nramo = '$ramo'
								ORDER BY dramo.nramo ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_tpoliza($desde, $hasta, $cia, $ramo, $tipo_cuenta)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta'  ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_tpoliza_by_user($desde, $hasta, $cia, $ramo, $tipo_cuenta, $asesor)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            poliza.codvend = '$asesor' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            poliza.codvend = '$asesor' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            poliza.codvend = '$asesor' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
                                poliza.codvend = '$asesor' AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
                                poliza.codvend = '$asesor' AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
                                poliza.codvend = '$asesor' AND
								nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_2($tpoliza, $ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								tipo_poliza = '$tpoliza' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
							poliza.id_poliza = drecibo.idrecibo AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							nomcia IN " . $ciaIn . " AND
							nramo IN " . $ramoIn . " AND
							tipo_poliza = '$tpoliza' ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_2_by_user($tpoliza, $ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								tipo_poliza = '$tpoliza' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia IN " . $ciaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								tipo_poliza = '$tpoliza' ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia, tipo_poliza WHERE 
							poliza.id_poliza = drecibo.idrecibo AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            poliza.codvend = '$asesor' AND
							nomcia IN " . $ciaIn . " AND
							nramo IN " . $ramoIn . " AND
							tipo_poliza = '$tpoliza' ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_cia($desde, $hasta, $ramo, $tipo_cuenta)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' ";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . "  ";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_cia_by_user($desde, $hasta, $ramo, $tipo_cuenta, $asesor)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
                                f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' ";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nramo IN " . $ramoIn . "  ";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_3($cia, $ramo, $desde, $hasta, $tipo_cuenta)
    {

        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								nomcia = '$cia'";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								nomcia = '$cia'";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_3_by_user($cia, $ramo, $desde, $hasta, $tipo_cuenta, $asesor)
    {

        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								nomcia = '$cia'";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nramo IN " . $ramoIn . " AND
								nomcia = '$cia'";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_fpago($desde, $hasta, $cia, $ramo, $tipo_cuenta)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, drecibo, dcia, dramo WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . "
								ORDER BY fpago ASC ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT fpago FROM poliza, drecibo, dcia, dramo WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta'
								ORDER BY fpago ASC ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, drecibo, dcia, dramo WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . "
								ORDER BY fpago ASC ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, drecibo, dcia, dramo WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								t_cuenta  IN " . $tipo_cuentaIn . "
								ORDER BY fpago ASC ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, drecibo, dcia, dramo WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " 
								ORDER BY fpago ASC ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, drecibo, dcia, dramo WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . "
								ORDER BY fpago ASC ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, drecibo, dcia, dramo WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . "
								ORDER BY fpago ASC ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, drecibo, dcia, dramo WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "
								ORDER BY fpago ASC  ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_4($fpago, $ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								fpago = '$fpago'";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								fpago = '$fpago'";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								fpago = '$fpago' ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								fpago = '$fpago' ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								fpago = '$fpago' ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								fpago = '$fpago' ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								fpago = '$fpago' ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								fpago = '$fpago' ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_4_by_user($fpago, $ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								fpago = '$fpago'";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								fpago = '$fpago'";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia IN " . $ciaIn . " AND
								fpago = '$fpago' ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								fpago = '$fpago' ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nramo IN " . $ramoIn . " AND
								fpago = '$fpago' ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								fpago = '$fpago' ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " AND
								fpago = '$fpago' ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, drecibo, dramo, dcia WHERE 
								poliza.id_poliza = drecibo.idrecibo AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
                                poliza.codvend = '$asesor' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								fpago = '$fpago' ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_ejecutivo_ps($desde, $hasta, $cia, $ramo, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT poliza.codvend, idnom AS nombre FROM poliza, dcia, dramo, ena WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = ena.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . " 

                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enr WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enr.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . "
                                
                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enp WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enp.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT poliza.codvend, idnom AS nombre FROM poliza, dcia, dramo, ena WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = ena.cod AND
								f_hastapoliza >= '$desde' AND
                                f_hastapoliza <= '$hasta'  
                                
                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enr WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enr.cod AND
								f_hastapoliza >= '$desde' AND
                                f_hastapoliza <= '$hasta'  
                                
                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enp WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enp.cod AND
								f_hastapoliza >= '$desde' AND
                                f_hastapoliza <= '$hasta'";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT poliza.codvend, idnom AS nombre FROM poliza, dcia, dramo, ena WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = ena.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " 
                            
                            UNION ALL
                            
                            SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enr WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = enr.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " 
                            
                            UNION ALL
                            
                            SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enp WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = enp.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT poliza.codvend, idnom AS nombre FROM poliza, dcia, dramo, ena WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = ena.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 
                            
                            UNION ALL
                            
                            SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enr WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = enr.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 
                            
                            UNION ALL
                            
                            SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enp WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = enp.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT poliza.codvend, idnom AS nombre FROM poliza, dcia, dramo, ena WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = ena.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nramo IN " . $ramoIn . " 
                            
                            UNION ALL
                            
                            SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enr WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = enr.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nramo IN " . $ramoIn . " 
                            
                            UNION ALL
                            
                            SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enp WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = enp.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nramo IN " . $ramoIn . " ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT poliza.codvend, idnom AS nombre FROM poliza, dcia, dramo, ena WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = ena.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . " 
                                
                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enr WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enr.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . " 
                                
                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enp WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enp.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . "";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT poliza.codvend, idnom AS nombre FROM poliza, dcia, dramo, ena WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = ena.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . " 
                                
                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enr WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enr.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . " 
                                
                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enp WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enp.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT poliza.codvend, idnom AS nombre FROM poliza, dcia, dramo, ena WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = ena.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
                                nramo IN " . $ramoIn . "  
                                
                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enr WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enr.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
                                nramo IN " . $ramoIn . "  
                                
                                UNION ALL
                                
                                SELECT DISTINCT poliza.codvend, nombre FROM poliza, dcia, dramo, enp WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
                                poliza.codvend = enp.cod AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
                                nramo IN " . $ramoIn . "  ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_prima_c_6($codvend, $ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE  
									poliza.id_cod_ramo=dramo.cod_ramo AND 
									poliza.id_cia=dcia.idcia AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									nomcia IN " . $ciaIn . " AND
									nramo IN " . $ramoIn . " AND
									t_cuenta  IN " . $tipo_cuentaIn . " AND
									poliza.codvend = '$codvend' ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND 
									poliza.id_cia=dcia.idcia AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND  
									poliza.codvend = '$codvend' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND  
								nomcia IN " . $ciaIn . " AND  
								poliza.codvend = '$codvend'";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND  
								t_cuenta  IN " . $tipo_cuentaIn . " AND  
								poliza.codvend = '$codvend'";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND 
								nramo IN " . $ramoIn . "  AND  
								poliza.codvend = '$codvend'";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE  
									poliza.id_cod_ramo=dramo.cod_ramo AND 
									poliza.id_cia=dcia.idcia AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND 
									nomcia IN " . $ciaIn . " AND
									t_cuenta  IN " . $tipo_cuentaIn . " AND  
									poliza.codvend = '$codvend'";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE  
									poliza.id_cod_ramo=dramo.cod_ramo AND 
									poliza.id_cia=dcia.idcia AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND 
									nramo IN " . $ramoIn . " AND
									t_cuenta  IN " . $tipo_cuentaIn . " AND  
									poliza.codvend = '$codvend' ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE  
									poliza.id_cod_ramo=dramo.cod_ramo AND 
									poliza.id_cia=dcia.idcia AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND 
									nomcia IN " . $ciaIn . " AND
									nramo IN " . $ramoIn . "  AND  
									poliza.codvend = '$codvend' ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_mes_prima($cond1, $cond2, $cia, $ramo, $tipo_cuenta, $m)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_desdepoliza) FROM poliza,dcia,dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . "
						ORDER BY Month(f_desdepoliza) ASC ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT Month(f_desdepoliza) FROM poliza,dcia,dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' 
						ORDER BY Month(f_desdepoliza) ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT Month(f_desdepoliza) FROM poliza,dcia,dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nomcia IN " . $ciaIn . " 
						ORDER BY Month(f_desdepoliza) ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT Month(f_desdepoliza) FROM poliza,dcia,dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						t_cuenta  IN " . $tipo_cuentaIn . " 
						ORDER BY Month(f_desdepoliza) ASC";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_desdepoliza) FROM poliza,dcia,dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nramo IN " . $ramoIn . "  
						ORDER BY Month(f_desdepoliza) ASC";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT Month(f_desdepoliza) FROM poliza,dcia,dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " 
						ORDER BY Month(f_desdepoliza) ASC";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_desdepoliza) FROM poliza,dcia,dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " 
						ORDER BY Month(f_desdepoliza) ASC";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_desdepoliza) FROM poliza,dcia,dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . "  
						ORDER BY Month(f_desdepoliza) ASC";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_grafp_2($ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							f_desdepoliza >= '$desde' AND
							f_desdepoliza <= '$hasta' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							f_desdepoliza >= '$desde' AND
							f_desdepoliza <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							f_desdepoliza >= '$desde' AND
							f_desdepoliza <= '$hasta' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_grafp_2_by_user($ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
                                poliza.codvend = '$asesor' AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
                                poliza.codvend = '$asesor' AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
                            poliza.codvend = '$asesor' AND
							f_desdepoliza >= '$desde' AND
							f_desdepoliza <= '$hasta' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
                            poliza.codvend = '$asesor' AND
							f_desdepoliza >= '$desde' AND
							f_desdepoliza <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
                            poliza.codvend = '$asesor' AND
							f_desdepoliza >= '$desde' AND
							f_desdepoliza <= '$hasta' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
                                poliza.codvend = '$asesor' AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
                                poliza.codvend = '$asesor' AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_tpoliza, prima, cod_ramo FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
                                poliza.codvend = '$asesor' AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_dia_mes_prima($cond1, $cond2, $cia, $ramo, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT f_desdepoliza FROM poliza, dcia, dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " 
						ORDER BY f_desdepoliza ASC";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT f_desdepoliza FROM poliza, dcia, dramo
					WHERE
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_cia=dcia.idcia AND
					f_desdepoliza >= '$cond1' AND
					f_desdepoliza <= '$cond2' 
					ORDER BY f_desdepoliza ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT f_desdepoliza FROM poliza, dcia, dramo
					WHERE
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_cia=dcia.idcia AND
					f_desdepoliza >= '$cond1' AND
					f_desdepoliza <= '$cond2' AND
					nomcia IN " . $ciaIn . " 
					ORDER BY f_desdepoliza ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT f_desdepoliza FROM poliza, dcia, dramo
					WHERE
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_cia=dcia.idcia AND
					f_desdepoliza >= '$cond1' AND
					f_desdepoliza <= '$cond2' AND
					t_cuenta  IN " . $tipo_cuentaIn . " 
					ORDER BY f_desdepoliza ASC";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT f_desdepoliza FROM poliza, dcia, dramo
					WHERE
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_cia=dcia.idcia AND
					f_desdepoliza >= '$cond1' AND
					f_desdepoliza <= '$cond2' AND
					nramo IN " . $ramoIn . "  
					ORDER BY f_desdepoliza ASC";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT f_desdepoliza FROM poliza, dcia, dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " 
						ORDER BY f_desdepoliza ASC";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT f_desdepoliza FROM poliza, dcia, dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " 
						ORDER BY f_desdepoliza ASC";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT f_desdepoliza FROM poliza, dcia, dramo
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
						f_desdepoliza >= '$cond1' AND
						f_desdepoliza <= '$cond2' AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . "  
						ORDER BY f_desdepoliza ASC";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_p3($ramo, $dia, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_desdepoliza = '$dia' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_desdepoliza = '$dia' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_desdepoliza = '$dia' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_desdepoliza = '$dia' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							f_desdepoliza = '$dia' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_desdepoliza = '$dia' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_desdepoliza = '$dia' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
								f_desdepoliza = '$dia' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_p3_by_user($ramo, $dia, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
                                poliza.codvend = '$asesor' AND
								f_desdepoliza = '$dia' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
                            poliza.codvend = '$asesor' AND
							f_desdepoliza = '$dia' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
                            poliza.codvend = '$asesor' AND
							f_desdepoliza = '$dia' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
                            poliza.codvend = '$asesor' AND
							f_desdepoliza = '$dia' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
                            poliza.codvend = '$asesor' AND
							f_desdepoliza = '$dia' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
                                poliza.codvend = '$asesor' AND
								f_desdepoliza = '$dia' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
                                poliza.codvend = '$asesor' AND
								f_desdepoliza = '$dia' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza, dcia, dramo WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_cia=dcia.idcia AND
                                poliza.codvend = '$asesor' AND
								f_desdepoliza = '$dia' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_mes_prima_BN()
    {

        $sql = "SELECT DISTINCT Month(f_desdepoliza) FROM poliza,drecibo,dcia,dramo
		      WHERE 
		      poliza.id_poliza = drecibo.idrecibo AND
		      poliza.id_cod_ramo=dramo.cod_ramo AND
		      poliza.id_cia=dcia.idcia 
		      ORDER BY Month(f_desdepoliza) ASC ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_ramo_prima_c($anio, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							nomcia IN " . $ciaIn . " AND
							t_cuenta  IN " . $tipo_cuentaIn . " AND
							YEAR(f_pago_prima)=$anio
							ORDER BY dramo.nramo ASC ";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							YEAR(f_pago_prima)=$anio
							ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							t_cuenta  IN " . $tipo_cuentaIn . " AND 
							YEAR(f_pago_prima)=$anio
							ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							nomcia IN " . $ciaIn . "AND 
							YEAR(f_pago_prima)=$anio
							ORDER BY dramo.nramo ASC";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_ramo_prima_c_by_user($anio, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							nomcia IN " . $ciaIn . " AND
							t_cuenta  IN " . $tipo_cuentaIn . " AND
							YEAR(f_pago_prima)=$anio
							ORDER BY dramo.nramo ASC ";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio
							ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							t_cuenta  IN " . $tipo_cuentaIn . " AND 
							YEAR(f_pago_prima)=$anio
							ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							nomcia IN " . $ciaIn . "AND 
							YEAR(f_pago_prima)=$anio
							ORDER BY dramo.nramo ASC";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_ramo($ramo, $cia, $anio, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					nomcia IN " . $ciaIn . " AND
					t_cuenta  IN " . $tipo_cuentaIn . " AND
					nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					t_cuenta  IN " . $tipo_cuentaIn . " AND
					nramo = '$ramo'";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					nomcia IN " . $ciaIn . " AND
					nramo = '$ramo'";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_ramo_by_user($ramo, $cia, $anio, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND
                    poliza.codvend = '$asesor' AND 
					YEAR(f_pago_prima)=$anio AND
					nomcia IN " . $ciaIn . " AND
					t_cuenta  IN " . $tipo_cuentaIn . " AND
					nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
                    poliza.codvend = '$asesor' AND
					YEAR(f_pago_prima)=$anio AND
					nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
                    poliza.codvend = '$asesor' AND
					YEAR(f_pago_prima)=$anio AND
					t_cuenta  IN " . $tipo_cuentaIn . " AND
					nramo = '$ramo'";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
                    poliza.codvend = '$asesor' AND
					YEAR(f_pago_prima)=$anio AND
					nomcia IN " . $ciaIn . " AND
					nramo = '$ramo'";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_ramo($ramo, $cia, $anio, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nramo = '$ramo'";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo = '$ramo'";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_ramo_by_user($ramo, $cia, $anio, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nramo = '$ramo'";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo = '$ramo'";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_bn($ramo, $desde, $hasta, $cia, $mes, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								MONTH(f_desdepoliza)=$mes AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								MONTH(f_desdepoliza)=$mes";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							MONTH(f_desdepoliza)=$mes AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							MONTH(f_desdepoliza)=$mes AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							MONTH(f_desdepoliza)=$mes AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								MONTH(f_desdepoliza)=$mes AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								MONTH(f_desdepoliza)=$mes AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								MONTH(f_desdepoliza)=$mes AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_bn_by_user($ramo, $desde, $hasta, $cia, $mes, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.codvend = '$asesor' AND
								MONTH(f_desdepoliza)=$mes AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.codvend = '$asesor' AND
								MONTH(f_desdepoliza)=$mes";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							MONTH(f_desdepoliza)=$mes AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							MONTH(f_desdepoliza)=$mes AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							MONTH(f_desdepoliza)=$mes AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.codvend = '$asesor' AND
								MONTH(f_desdepoliza)=$mes AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.codvend = '$asesor' AND
								MONTH(f_desdepoliza)=$mes AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.codvend = '$asesor' AND
								MONTH(f_desdepoliza)=$mes AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_cia_prima_c($anio, $ramo, $tipo_cuenta)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								YEAR(f_pago_prima)=$anio AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY dcia.nomcia ASC";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							YEAR(f_pago_prima)=$anio 
							ORDER BY dcia.nomcia ASC";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							YEAR(f_pago_prima)=$anio AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY dcia.nomcia ASC";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							YEAR(f_pago_prima)=$anio AND
							nramo IN " . $ramoIn . "
							ORDER BY dcia.nomcia ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_cia_prima_c_by_user($anio, $ramo, $tipo_cuenta, $asesor)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza 
								INNER JOIN dcia, dramo, comision WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.codvend = '$asesor' AND
								YEAR(f_pago_prima)=$anio AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY dcia.nomcia ASC";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio 
							ORDER BY dcia.nomcia ASC";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY dcia.nomcia ASC";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio AND
							nramo IN " . $ramoIn . "
							ORDER BY dcia.nomcia ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_cia($cia, $ramo, $anio, $tipo_cuenta)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						nomcia = '$cia' AND
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						nomcia = '$cia' AND
						YEAR(f_pago_prima)=$anio";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					nomcia = '$cia' AND
					YEAR(f_pago_prima)=$anio AND
					t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					nomcia = '$cia' AND
					YEAR(f_pago_prima)=$anio AND
					nramo IN " . $ramoIn . " ";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_cia_by_user($cia, $ramo, $anio, $tipo_cuenta, $asesor)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						nomcia = '$cia' AND
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						nomcia = '$cia' AND
						YEAR(f_pago_prima)=$anio";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
                    poliza.codvend = '$asesor' AND
					nomcia = '$cia' AND
					YEAR(f_pago_prima)=$anio AND
					t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
                    poliza.codvend = '$asesor' AND
					nomcia = '$cia' AND
					YEAR(f_pago_prima)=$anio AND
					nramo IN " . $ramoIn . " ";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_cia($ramo, $cia, $anio, $tipo_cuenta)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia = '$cia'";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						nomcia = '$cia'";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_cia_by_user($ramo, $cia, $anio, $tipo_cuenta, $asesor)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia = '$cia'";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						nomcia = '$cia'";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_tipo_poliza_prima_c($anio, $ramo, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
								INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY tipo_poliza ASC";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
							INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
							YEAR(f_pago_prima)=$anio 
							ORDER BY tipo_poliza ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
							INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
							YEAR(f_pago_prima)=$anio AND
							nomcia IN " . $ciaIn . " 
							ORDER BY tipo_poliza ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
							INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
							YEAR(f_pago_prima)=$anio AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY tipo_poliza ASC";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
							INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
							YEAR(f_pago_prima)=$anio AND
							nramo IN " . $ramoIn . "  
							ORDER BY tipo_poliza ASC";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
								INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY tipo_poliza ASC";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
								INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
								YEAR(f_pago_prima)=$anio AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY tipo_poliza ASC";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
								INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  
								ORDER BY tipo_poliza ASC";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_tipo_poliza_prima_c_by_user($anio, $ramo, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
								INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                                poliza.codvend = '$asesor' AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY tipo_poliza ASC";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
							INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio 
							ORDER BY tipo_poliza ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
							INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio AND
							nomcia IN " . $ciaIn . " 
							ORDER BY tipo_poliza ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
							INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY tipo_poliza ASC";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
							INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio AND
							nramo IN " . $ramoIn . "  
							ORDER BY tipo_poliza ASC";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
								INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                                poliza.codvend = '$asesor' AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY tipo_poliza ASC";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
								INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                                poliza.codvend = '$asesor' AND
								YEAR(f_pago_prima)=$anio AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY tipo_poliza ASC";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza 
								INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
								poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                                poliza.codvend = '$asesor' AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  
								ORDER BY tipo_poliza ASC";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_tipo_poliza($tipo_poliza, $cia, $ramo, $anio, $tipo_cuenta)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
						YEAR(f_pago_prima)=$anio  ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
					tipo_poliza.tipo_poliza = '$tipo_poliza' AND
					YEAR(f_pago_prima)=$anio AND
					nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
					tipo_poliza.tipo_poliza = '$tipo_poliza' AND
					YEAR(f_pago_prima)=$anio AND
					t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
					tipo_poliza.tipo_poliza = '$tipo_poliza' AND
					YEAR(f_pago_prima)=$anio AND
					nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_tipo_poliza_by_user($tipo_poliza, $cia, $ramo, $anio, $tipo_cuenta, $asesor)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio  ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
					tipo_poliza.tipo_poliza = '$tipo_poliza' AND
                    poliza.codvend = '$asesor' AND
					YEAR(f_pago_prima)=$anio AND
					nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
					tipo_poliza.tipo_poliza = '$tipo_poliza' AND
                    poliza.codvend = '$asesor' AND
					YEAR(f_pago_prima)=$anio AND
					t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
					tipo_poliza.tipo_poliza = '$tipo_poliza' AND
                    poliza.codvend = '$asesor' AND
					YEAR(f_pago_prima)=$anio AND
					nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						tipo_poliza.tipo_poliza = '$tipo_poliza' AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_tpoliza($ramo, $cia, $anio, $tipo_cuenta, $t_poliza)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nomcia IN " . $ciaIn . "";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						t_cuenta  IN " . $tipo_cuentaIn . "  ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nramo IN " . $ramoIn . " ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nramo IN " . $ramoIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_tpoliza_by_user($ramo, $cia, $anio, $tipo_cuenta, $t_poliza, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nomcia IN " . $ciaIn . "";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						t_cuenta  IN " . $tipo_cuentaIn . "  ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nramo IN " . $ramoIn . " ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision, tipo_poliza WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						tipo_poliza.tipo_poliza='$t_poliza' AND
						nramo IN " . $ramoIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_ejecutivo_prima_c($anio, $ramo, $cia, $tipo_cuenta)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT comision.cod_vend, idnom AS nombre FROM poliza 
                            INNER JOIN dcia, dramo, comision, ena WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend=ena.cod AND
                            YEAR(f_pago_prima)=$anio AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 
                            
                            UNION ALL
                            
                        SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                            INNER JOIN dcia, dramo, comision, enr WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend=enr.cod AND
                            YEAR(f_pago_prima)=$anio AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 
                            
                            UNION ALL

                        SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                            INNER JOIN dcia, dramo, comision, enp WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.id_poliza = comision.id_poliza AND 
                            poliza.codvend=enp.cod AND
                            YEAR(f_pago_prima)=$anio AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 

                            ORDER BY nombre ASC";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT comision.cod_vend, idnom AS nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, ena WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        comision.cod_vend=ena.cod AND
                        YEAR(f_pago_prima)=$anio  
                        
                        UNION ALL
                        
                        SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enr WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        comision.cod_vend=enr.cod AND
                        YEAR(f_pago_prima)=$anio  
                        
                        UNION ALL
                        
                        SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enp WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        comision.cod_vend=enp.cod AND
                        YEAR(f_pago_prima)=$anio  
                        
                        ORDER BY nombre ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT comision.cod_vend, idnom AS nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, ena WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=ena.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nomcia IN " . $ciaIn . " 

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enr WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enr.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nomcia IN " . $ciaIn . " 

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enp WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enp.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nomcia IN " . $ciaIn . " 
                        ORDER BY nombre ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT comision.cod_vend, idnom AS nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, ena WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=ena.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        t_cuenta  IN " . $tipo_cuentaIn . "

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enr WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enr.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        t_cuenta  IN " . $tipo_cuentaIn . "

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enp WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enp.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        t_cuenta  IN " . $tipo_cuentaIn . "
                        ORDER BY nombre ASC ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT comision.cod_vend, idnom AS nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, ena WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=ena.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nramo IN " . $ramoIn . " 

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enr WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enr.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nramo IN " . $ramoIn . " 

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enp WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enp.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nramo IN " . $ramoIn . " 
                        ORDER BY nombre ASC ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT comision.cod_vend, idnom AS nombre FROM poliza 
								INNER JOIN dcia, dramo, comision, ena WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.codvend=ena.cod AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . "
                                
                                UNION ALL

                            SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
								INNER JOIN dcia, dramo, comision, enr WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.codvend=enr.cod AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
                                t_cuenta  IN " . $tipo_cuentaIn . "
                                
                                UNION ALL

                            SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
								INNER JOIN dcia, dramo, comision, enp WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.codvend=enp.cod AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . "
								ORDER BY nombre ASC ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT comision.cod_vend, idnom AS nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, ena WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=ena.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nramo IN " . $ramoIn . " AND
                        t_cuenta  IN " . $tipo_cuentaIn . "

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enr WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enr.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nramo IN " . $ramoIn . " AND
                        t_cuenta  IN " . $tipo_cuentaIn . "

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enp WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enp.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nramo IN " . $ramoIn . " AND
                        t_cuenta  IN " . $tipo_cuentaIn . "
                        ORDER BY nombre ASC ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT comision.cod_vend, idnom AS nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, ena WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=ena.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nomcia IN " . $ciaIn . " AND
                        nramo IN " . $ramoIn . "  

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enr WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enr.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nomcia IN " . $ciaIn . " AND
                        nramo IN " . $ramoIn . "  

                        UNION ALL

                    SELECT DISTINCT comision.cod_vend, nombre FROM poliza 
                        INNER JOIN dcia, dramo, comision, enp WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend=enp.cod AND
                        YEAR(f_pago_prima)=$anio AND
                        nomcia IN " . $ciaIn . " AND
                        nramo IN " . $ramoIn . "  
                        ORDER BY nombre ASC";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_ejecutivo($ejecutivo, $cia, $ramo, $anio, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						comision.cod_vend = '$ejecutivo' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
                        INNER JOIN dcia, dramo, comision WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        comision.cod_vend = '$ejecutivo' AND
                        YEAR(f_pago_prima)=$anio  ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
                        INNER JOIN dcia, dramo, comision WHERE 
                        poliza.id_cia=dcia.idcia AND
                        poliza.id_cod_ramo=dramo.cod_ramo AND
                        poliza.id_poliza = comision.id_poliza AND 
                        comision.cod_vend = '$ejecutivo' AND
                        YEAR(f_pago_prima)=$anio AND
                        nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					comision.cod_vend = '$ejecutivo' AND
					YEAR(f_pago_prima)=$anio AND
					t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					comision.cod_vend = '$ejecutivo' AND
					YEAR(f_pago_prima)=$anio AND
					nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						comision.cod_vend = '$ejecutivo' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						comision.cod_vend = '$ejecutivo' AND
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, f_pago_prima, prima_com FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						comision.cod_vend = '$ejecutivo' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_ejecutivo($ramo, $cia, $anio, $tipo_cuenta, $ejecutivo)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						comision.cod_vend='$ejecutivo' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						comision.cod_vend='$ejecutivo' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						comision.cod_vend='$ejecutivo' AND
						nomcia IN " . $ciaIn . "";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						comision.cod_vend='$ejecutivo' AND
						t_cuenta  IN " . $tipo_cuentaIn . "  ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						comision.cod_vend='$ejecutivo' AND
						nramo IN " . $ramoIn . " ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						comision.cod_vend='$ejecutivo' AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						comision.cod_vend='$ejecutivo' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						comision.cod_vend='$ejecutivo' AND
						nramo IN " . $ramoIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_f_pago_prima_c($anio, $ramo, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
								INNER JOIN dcia, dramo, comision, drecibo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.id_poliza=drecibo.idrecibo AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY fpago ASC";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT fpago FROM poliza 
							INNER JOIN dcia, dramo, comision, drecibo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.id_poliza=drecibo.idrecibo AND
							YEAR(f_pago_prima)=$anio 
							ORDER BY fpago ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
							INNER JOIN dcia, dramo, comision, drecibo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.id_poliza=drecibo.idrecibo AND
							YEAR(f_pago_prima)=$anio AND
							nomcia IN " . $ciaIn . " 
							ORDER BY fpago ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
							INNER JOIN dcia, dramo, comision, drecibo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.id_poliza=drecibo.idrecibo AND
							YEAR(f_pago_prima)=$anio AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY fpago ASC";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
							INNER JOIN dcia, dramo, comision, drecibo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.id_poliza=drecibo.idrecibo AND
							YEAR(f_pago_prima)=$anio AND
							nramo IN " . $ramoIn . "  
							ORDER BY fpago ASC";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
								INNER JOIN dcia, dramo, comision, drecibo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.id_poliza=drecibo.idrecibo AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY fpago ASC";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
								INNER JOIN dcia, dramo, comision, drecibo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.id_poliza=drecibo.idrecibo AND
								YEAR(f_pago_prima)=$anio AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . "
								ORDER BY fpago ASC";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
								INNER JOIN dcia, dramo, comision, drecibo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.id_poliza=drecibo.idrecibo AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  
								ORDER BY fpago ASC";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_f_pago_prima_c_by_user($anio, $ramo, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
								INNER JOIN dcia, dramo, comision, drecibo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND
                                poliza.id_poliza=drecibo.idrecibo AND
                                poliza.codvend = '$asesor' AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY fpago ASC";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT fpago FROM poliza 
							INNER JOIN dcia, dramo, comision, drecibo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.id_poliza=drecibo.idrecibo AND
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio 
							ORDER BY fpago ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
							INNER JOIN dcia, dramo, comision, drecibo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.id_poliza=drecibo.idrecibo AND
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio AND
							nomcia IN " . $ciaIn . " 
							ORDER BY fpago ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
							INNER JOIN dcia, dramo, comision, drecibo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.id_poliza=drecibo.idrecibo AND
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY fpago ASC";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
							INNER JOIN dcia, dramo, comision, drecibo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
                            poliza.id_poliza=drecibo.idrecibo AND
                            poliza.codvend = '$asesor' AND
							YEAR(f_pago_prima)=$anio AND
							nramo IN " . $ramoIn . "  
							ORDER BY fpago ASC";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
								INNER JOIN dcia, dramo, comision, drecibo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.id_poliza=drecibo.idrecibo AND
                                poliza.codvend = '$asesor' AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY fpago ASC";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
								INNER JOIN dcia, dramo, comision, drecibo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.id_poliza=drecibo.idrecibo AND
                                poliza.codvend = '$asesor' AND
								YEAR(f_pago_prima)=$anio AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . "
								ORDER BY fpago ASC";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza 
								INNER JOIN dcia, dramo, comision, drecibo WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								poliza.id_poliza = comision.id_poliza AND 
                                poliza.id_poliza=drecibo.idrecibo AND
                                poliza.codvend = '$asesor' AND
								YEAR(f_pago_prima)=$anio AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  
								ORDER BY fpago ASC";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_f_pago($f_pago, $cia, $ramo, $anio, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						drecibo.fpago = '$f_pago' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
					INNER JOIN dcia, drecibo, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_poliza=drecibo.idrecibo AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					drecibo.fpago = '$f_pago' AND
					YEAR(f_pago_prima)=$anio   ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
					INNER JOIN dcia, drecibo, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_poliza=drecibo.idrecibo AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					drecibo.fpago = '$f_pago' AND
					YEAR(f_pago_prima)=$anio AND
					nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
					INNER JOIN dcia, drecibo, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_poliza=drecibo.idrecibo AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					drecibo.fpago = '$f_pago' AND
					YEAR(f_pago_prima)=$anio AND
					t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
					INNER JOIN dcia, drecibo, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_poliza=drecibo.idrecibo AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					drecibo.fpago = '$f_pago' AND
					YEAR(f_pago_prima)=$anio AND
					nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						drecibo.fpago = '$f_pago' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						drecibo.fpago = '$f_pago' AND
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						drecibo.fpago = '$f_pago' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_f_pago_by_user($f_pago, $cia, $ramo, $anio, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						drecibo.fpago = '$f_pago' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
					INNER JOIN dcia, drecibo, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_poliza=drecibo.idrecibo AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
                    poliza.codvend = '$asesor' AND
					drecibo.fpago = '$f_pago' AND
					YEAR(f_pago_prima)=$anio   ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
					INNER JOIN dcia, drecibo, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_poliza=drecibo.idrecibo AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
                    poliza.codvend = '$asesor' AND
					drecibo.fpago = '$f_pago' AND
					YEAR(f_pago_prima)=$anio AND
					nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
					INNER JOIN dcia, drecibo, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_poliza=drecibo.idrecibo AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
                    poliza.codvend = '$asesor' AND
					drecibo.fpago = '$f_pago' AND
					YEAR(f_pago_prima)=$anio AND
					t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
					INNER JOIN dcia, drecibo, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND
					poliza.id_poliza=drecibo.idrecibo AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
                    poliza.codvend = '$asesor' AND
					drecibo.fpago = '$f_pago' AND
					YEAR(f_pago_prima)=$anio AND
					nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						drecibo.fpago = '$f_pago' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						drecibo.fpago = '$f_pago' AND
						YEAR(f_pago_prima)=$anio AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT f_pago_prima, poliza.prima, prima_com FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						drecibo.fpago = '$f_pago' AND
						YEAR(f_pago_prima)=$anio AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_fpago($ramo, $cia, $anio, $tipo_cuenta, $fpago)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nomcia IN " . $ciaIn . "";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						t_cuenta  IN " . $tipo_cuentaIn . "  ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nramo IN " . $ramoIn . " ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nramo IN " . $ramoIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_fpago_by_user($ramo, $cia, $anio, $tipo_cuenta, $fpago, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nomcia IN " . $ciaIn . "";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						t_cuenta  IN " . $tipo_cuentaIn . "  ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nramo IN " . $ramoIn . " ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, drecibo, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND
						poliza.id_poliza=drecibo.idrecibo AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
                        poliza.codvend = '$asesor' AND
						YEAR(f_pago_prima)=$anio AND
						drecibo.fpago='$fpago' AND
						nramo IN " . $ramoIn . " AND
						nomcia IN " . $ciaIn . " ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_ramo_pc($desde, $hasta, $cia, $tipo_cuenta)
    {

        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
								INNER JOIN dramo, dcia, comision, rep_com WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
								f_hastapoliza >= '$desde' AND
		      					f_hastapoliza <= '$hasta' AND
								poliza.id_poliza=comision.id_poliza AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia, comision, rep_com WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							rep_com.id_cia=dcia.idcia AND
							rep_com.id_rep_com=comision.id_rep_com AND
							poliza.id_poliza=comision.id_poliza AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta'
							ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia, comision, rep_com WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							rep_com.id_cia=dcia.idcia AND
							rep_com.id_rep_com=comision.id_rep_com AND
							poliza.id_poliza=comision.id_poliza AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia, comision, rep_com WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							rep_com.id_cia=dcia.idcia AND
							rep_com.id_rep_com=comision.id_rep_com AND
							poliza.id_poliza=comision.id_poliza AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							nomcia IN " . $ciaIn . "
							ORDER BY dramo.nramo ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_ramo_pc_by_user($desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {

        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
								INNER JOIN dramo, dcia, comision, rep_com WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
                                poliza.codvend = '$asesor' AND
								f_hastapoliza >= '$desde' AND
		      					f_hastapoliza <= '$hasta' AND
								poliza.id_poliza=comision.id_poliza AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia, comision, rep_com WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							rep_com.id_cia=dcia.idcia AND
							rep_com.id_rep_com=comision.id_rep_com AND
							poliza.id_poliza=comision.id_poliza AND
                            poliza.codvend = '$asesor' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta'
							ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia, comision, rep_com WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							rep_com.id_cia=dcia.idcia AND
							rep_com.id_rep_com=comision.id_rep_com AND
							poliza.id_poliza=comision.id_poliza AND
                            poliza.codvend = '$asesor' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . " 
							ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT dramo.nramo FROM poliza 
							INNER JOIN dramo, dcia, comision, rep_com WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							rep_com.id_cia=dcia.idcia AND
							rep_com.id_rep_com=comision.id_rep_com AND
							poliza.id_poliza=comision.id_poliza AND
                            poliza.codvend = '$asesor' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							nomcia IN " . $ciaIn . "
							ORDER BY dramo.nramo ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_1_pc($ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   dcia.nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   dramo.nramo = '$ramo'
								   ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									dramo.nramo = '$ramo'
									ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									t_cuenta  IN " . $tipo_cuentaIn . " AND
									dramo.nramo = '$ramo'
									ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									dcia.nomcia IN " . $ciaIn . " AND
									dramo.nramo = '$ramo'
									ORDER BY dramo.nramo ASC";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_1_pc_by_user($ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   dcia.nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   dramo.nramo = '$ramo'
								   ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									dramo.nramo = '$ramo'
									ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									t_cuenta  IN " . $tipo_cuentaIn . " AND
									dramo.nramo = '$ramo'
									ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									dcia.nomcia IN " . $ciaIn . " AND
									dramo.nramo = '$ramo'
									ORDER BY dramo.nramo ASC";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_ramo_en_poliza($desde, $hasta, $ramo)
    {
        $sql = "SELECT prima FROM poliza 
							INNER JOIN dramo WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
							dramo.nramo = '$ramo' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_ramo_en_poliza_by_user($desde, $hasta, $ramo, $asesor)
    {
        $sql = "SELECT prima FROM poliza 
							INNER JOIN dramo WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            poliza.codvend = '$asesor' AND
							dramo.nramo = '$ramo' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_mes_prima_pc($cond1, $cond2, $cia, $ramo, $tipo_cuenta, $m)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_hastapoliza) FROM poliza,dcia,dramo,comision,rep_com
								WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
								f_hastapoliza >= '$cond1' AND
								f_hastapoliza <= '$cond2' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . "
								ORDER BY Month(f_hastapoliza) ASC ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT Month(f_hastapoliza) FROM poliza,dcia,dramo,comision,rep_com
								WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
								f_hastapoliza >= '$cond1' AND
								f_hastapoliza <= '$cond2' 
								ORDER BY Month(f_hastapoliza) ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT Month(f_hastapoliza) FROM poliza,dcia,dramo,comision,rep_com
								WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
								f_hastapoliza >= '$cond1' AND
								f_hastapoliza <= '$cond2' AND
								nomcia IN " . $ciaIn . " 
								ORDER BY Month(f_hastapoliza) ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT Month(f_hastapoliza) FROM poliza,dcia,dramo,comision,rep_com
								WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
								f_hastapoliza >= '$cond1' AND
								f_hastapoliza <= '$cond2' AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY Month(f_hastapoliza) ASC";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_hastapoliza) FROM poliza,dcia,dramo,comision,rep_com
								WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
								f_hastapoliza >= '$cond1' AND
								f_hastapoliza <= '$cond2' AND
								nramo IN " . $ramoIn . "  
								ORDER BY Month(f_hastapoliza) ASC";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT Month(f_hastapoliza) FROM poliza,dcia,dramo,comision,rep_com
								WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
								f_hastapoliza >= '$cond1' AND
								f_hastapoliza <= '$cond2' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY Month(f_hastapoliza) ASC";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_hastapoliza) FROM poliza,dcia,dramo,comision,rep_com
								WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
								f_hastapoliza >= '$cond1' AND
								f_hastapoliza <= '$cond2' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " 
								ORDER BY Month(f_hastapoliza) ASC";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_hastapoliza) FROM poliza,dcia,dramo,comision,rep_com
								WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
								f_hastapoliza >= '$cond1' AND
								f_hastapoliza <= '$cond2' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  
								ORDER BY Month(f_hastapoliza) ASC";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_grafp_2_pc($ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_grafp_2_pc_by_user($ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dcia, dramo, rep_com WHERE  
								   poliza.id_cod_ramo=dramo.cod_ramo AND 
								   rep_com.id_cia=dcia.idcia AND 
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_mes_en_poliza($desde, $hasta, $mes)
    {

        $sql = "SELECT prima FROM poliza 
                        WHERE 
                        Month(f_hastapoliza) = $mes AND
                        f_hastapoliza >= '$desde' AND
                        f_hastapoliza <= '$hasta' ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_mes_en_poliza_by_user($desde, $hasta, $mes, $asesor)
    {

        $sql = "SELECT prima FROM poliza 
                        WHERE 
                        Month(f_hastapoliza) = $mes AND
                        poliza.codvend = '$asesor' AND
                        f_hastapoliza >= '$desde' AND
                        f_hastapoliza <= '$hasta' ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_cia_pc($desde, $hasta, $ramo, $tipo_cuenta)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo, comision, rep_com WHERE 
								   rep_com.id_cia=dcia.idcia AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   poliza.id_poliza=comision.id_poliza AND 
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo, comision, rep_com WHERE 
								   rep_com.id_cia=dcia.idcia AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   poliza.id_poliza=comision.id_poliza AND 
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' ";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo, comision, rep_com WHERE 
								   rep_com.id_cia=dcia.idcia AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   poliza.id_poliza=comision.id_poliza AND 
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo, comision, rep_com WHERE 
								   rep_com.id_cia=dcia.idcia AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   poliza.id_poliza=comision.id_poliza AND 
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . "  ";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_cia_pc_by_user($desde, $hasta, $ramo, $tipo_cuenta, $asesor)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo, comision, rep_com WHERE 
								   rep_com.id_cia=dcia.idcia AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   poliza.id_poliza=comision.id_poliza AND 
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo, comision, rep_com WHERE 
								   rep_com.id_cia=dcia.idcia AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   poliza.id_poliza=comision.id_poliza AND 
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' ";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo, comision, rep_com WHERE 
								   rep_com.id_cia=dcia.idcia AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   poliza.id_poliza=comision.id_poliza AND 
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza, dcia, dramo, comision, rep_com WHERE 
								   rep_com.id_cia=dcia.idcia AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   poliza.id_poliza=comision.id_poliza AND 
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . "  ";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_3_pc($cia, $ramo, $desde, $hasta, $tipo_cuenta)
    {

        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									nramo IN " . $ramoIn . " AND
									t_cuenta  IN " . $tipo_cuentaIn . " AND
									nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									t_cuenta  IN " . $tipo_cuentaIn . " AND
									nomcia = '$cia'";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									nramo IN " . $ramoIn . " AND
									nomcia = '$cia'";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_3_pc_by_user($cia, $ramo, $desde, $hasta, $tipo_cuenta, $asesor)
    {

        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									nramo IN " . $ramoIn . " AND
									t_cuenta  IN " . $tipo_cuentaIn . " AND
									nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									t_cuenta  IN " . $tipo_cuentaIn . " AND
									nomcia = '$cia'";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta' AND
									nramo IN " . $ramoIn . " AND
									nomcia = '$cia'";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_cia_en_poliza($desde, $hasta, $cia)
    {

        $sql = "SELECT prima FROM poliza 
							INNER JOIN dcia WHERE 
							poliza.id_cia=dcia.idcia AND
							dcia.nomcia = '$cia' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' ";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_cia_en_poliza_by_user($desde, $hasta, $cia, $asesor)
    {
        $sql = "SELECT prima FROM poliza 
							INNER JOIN dcia WHERE 
							poliza.id_cia=dcia.idcia AND
                            poliza.codvend = '$asesor' AND
							dcia.nomcia = '$cia' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' ";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_tpoliza_pc($desde, $hasta, $cia, $ramo, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
								   poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								   rep_com.id_cia=dcia.idcia AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta'  ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								rep_com.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								rep_com.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								rep_com.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_tpoliza_pc_by_user($desde, $hasta, $cia, $ramo, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
								   poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								   rep_com.id_cia=dcia.idcia AND
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_poliza=comision.id_poliza AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
									f_hastapoliza >= '$desde' AND
									f_hastapoliza <= '$hasta'  ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								rep_com.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
                                poliza.codvend = '$asesor' AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								rep_com.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
                                poliza.codvend = '$asesor' AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								rep_com.id_cia=dcia.idcia AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_rep_com=comision.id_rep_com AND
								poliza.id_poliza=comision.id_poliza AND
                                poliza.codvend = '$asesor' AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT tipo_poliza FROM poliza, tipo_poliza, dcia, dramo, comision, rep_com WHERE 
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_2_pc($tpoliza, $ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   rep_com.id_cia=dcia.idcia AND
								   poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   tipo_poliza = '$tpoliza' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								comision.id_poliza=poliza.id_poliza AND
								rep_com.id_rep_com=comision.id_rep_com AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   nomcia IN " . $ciaIn . " AND
							   nramo IN " . $ramoIn . " AND
							   tipo_poliza = '$tpoliza' ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_2_pc_by_user($tpoliza, $ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   rep_com.id_cia=dcia.idcia AND
								   poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   tipo_poliza = '$tpoliza' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_cia=dcia.idcia AND
									poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
									comision.id_poliza=poliza.id_poliza AND
									rep_com.id_rep_com=comision.id_rep_com AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   tipo_poliza = '$tpoliza' ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima, prima_com, comision, per_gc FROM comision INNER JOIN poliza, dramo, dcia, tipo_poliza, rep_com WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND
								rep_com.id_cia=dcia.idcia AND
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								comision.id_poliza=poliza.id_poliza AND
								rep_com.id_rep_com=comision.id_rep_com AND
                                poliza.codvend = '$asesor' AND
							   f_hastapoliza >= '$desde' AND
							   f_hastapoliza <= '$hasta' AND
							   nomcia IN " . $ciaIn . " AND
							   nramo IN " . $ramoIn . " AND
							   tipo_poliza = '$tpoliza' ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_tpoliza_en_poliza($desde, $hasta, $tpoliza)
    {
        $sql = "SELECT prima FROM poliza 
							INNER JOIN tipo_poliza WHERE 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							tipo_poliza.tipo_poliza = '$tpoliza' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_tpoliza_en_poliza_by_user($desde, $hasta, $tpoliza, $asesor)
    {
        $sql = "SELECT prima FROM poliza 
							INNER JOIN tipo_poliza WHERE 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
                            poliza.codvend = '$asesor' AND
							tipo_poliza.tipo_poliza = '$tpoliza' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_fpago_pc($desde, $hasta, $cia, $ramo, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
								   rep_com.id_cia=dcia.idcia AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . "
								   ORDER BY fpago ASC ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta'
								   ORDER BY fpago ASC ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . "
								   ORDER BY fpago ASC ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   t_cuenta  IN " . $tipo_cuentaIn . "
								   ORDER BY fpago ASC ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " 
								   ORDER BY fpago ASC ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . "
								   ORDER BY fpago ASC ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . "
								   ORDER BY fpago ASC ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . "
								   ORDER BY fpago ASC  ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_fpago_pc_by_user($desde, $hasta, $cia, $ramo, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
								   rep_com.id_cia=dcia.idcia AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   poliza.id_poliza=comision.id_poliza AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . "
								   ORDER BY fpago ASC ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta'
								   ORDER BY fpago ASC ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . "
								   ORDER BY fpago ASC ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   t_cuenta  IN " . $tipo_cuentaIn . "
								   ORDER BY fpago ASC ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " 
								   ORDER BY fpago ASC ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . "
								   ORDER BY fpago ASC ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . "
								   ORDER BY fpago ASC ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT fpago FROM poliza, dcia, dramo, comision, rep_com, drecibo WHERE 
									rep_com.id_cia=dcia.idcia AND
                                    poliza.id_poliza=drecibo.idrecibo AND 
									poliza.id_cod_ramo=dramo.cod_ramo AND
									rep_com.id_rep_com=comision.id_rep_com AND
									poliza.id_poliza=comision.id_poliza AND
                                    poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . "
								   ORDER BY fpago ASC  ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_4_pc($fpago, $ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc 
            FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   fpago = '$fpago'";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT poliza.prima, prima_com, comision, per_gc 
            FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   fpago = '$fpago'";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc 
            FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   fpago = '$fpago' ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc 
            FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   fpago = '$fpago' ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc
             FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   fpago = '$fpago' ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc
             FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   fpago = '$fpago' ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc
             FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   fpago = '$fpago' ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc
             FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   fpago = '$fpago' ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_graf_4_pc_by_user($fpago, $ramo, $desde, $hasta, $cia, $tipo_cuenta, $asesor)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc 
            FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   fpago = '$fpago'";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT poliza.prima, prima_com, comision, per_gc 
            FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   fpago = '$fpago'";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc 
            FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   fpago = '$fpago' ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc 
            FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   fpago = '$fpago' ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc
             FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   fpago = '$fpago' ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc
             FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   fpago = '$fpago' ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc
             FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nramo IN " . $ramoIn . " AND
								   t_cuenta  IN " . $tipo_cuentaIn . " AND
								   fpago = '$fpago' ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT poliza.prima, prima_com, comision, per_gc
             FROM comision INNER JOIN poliza, dramo, dcia, rep_com, drecibo WHERE 
								   poliza.id_cod_ramo=dramo.cod_ramo AND
                                   poliza.id_poliza=drecibo.idrecibo AND 
								   rep_com.id_cia=dcia.idcia AND
								   comision.id_poliza=poliza.id_poliza AND
								   rep_com.id_rep_com=comision.id_rep_com AND
                                   poliza.codvend = '$asesor' AND
								   f_hastapoliza >= '$desde' AND
								   f_hastapoliza <= '$hasta' AND
								   nomcia IN " . $ciaIn . " AND
								   nramo IN " . $ramoIn . " AND
								   fpago = '$fpago' ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_fpago_en_poliza($desde, $hasta, $fpago)
    {

        $sql = "SELECT poliza.prima FROM poliza ,drecibo
							WHERE 
                            poliza.id_poliza=drecibo.idrecibo AND 
							drecibo.fpago = '$fpago' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_fpago_en_poliza_by_user($desde, $hasta, $fpago, $asesor)
    {

        $sql = "SELECT poliza.prima FROM poliza ,drecibo
							WHERE 
                            poliza.id_poliza=drecibo.idrecibo AND 
                            poliza.codvend = '$asesor' AND
							drecibo.fpago = '$fpago' AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_element_ejecutivo($desde, $hasta, $cia, $ramo, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre 
                            FROM comision, poliza, dcia, dramo, ena WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = ena.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 

                            UNION ALL 

                        SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enr WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enr.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 

                            UNION ALL

                        SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enp WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enp.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 
                            ORDER BY cod_vend ASC";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre 
                            FROM comision, poliza, dcia, dramo, ena WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = ena.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta'

                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enr WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enr.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta'

                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enp WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enp.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta'  
							ORDER BY cod_vend ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre 
                            FROM comision, poliza, dcia, dramo, ena WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = ena.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " 
                            
                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enr WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enr.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " 
                            
                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enp WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enp.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							nomcia IN " . $ciaIn . " 
							ORDER BY cod_vend ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre 
                            FROM comision, poliza, dcia, dramo, ena WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = ena.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 
                            
                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enr WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enr.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            t_cuenta  IN " . $tipo_cuentaIn . "
                            
                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enp WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enp.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . "
							ORDER BY cod_vend ASC";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre 
                            FROM comision, poliza, dcia, dramo, ena WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = ena.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nramo IN " . $ramoIn . "  
                            
                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enr WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enr.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
                            nramo IN " . $ramoIn . "  
                            
                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enp WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_poliza=comision.id_poliza AND 
							poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enp.cod AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							nramo IN " . $ramoIn . "  
							ORDER BY cod_vend ASC";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre 
                            FROM comision, poliza, dcia, dramo, ena WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = ena.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 

                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enr WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enr.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 

                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enp WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enp.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 
                            ORDER BY cod_vend ASC";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre 
                            FROM comision, poliza, dcia, dramo, ena WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = ena.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 

                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enr WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enr.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 

                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enp WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enp.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " 
                            ORDER BY cod_vend ASC";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre 
                            FROM comision, poliza, dcia, dramo, ena WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = ena.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . "  

                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enr WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enr.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . "  

                            UNION ALL

                            SELECT DISTINCT cod_vend, nombre 
                            FROM comision, poliza, dcia, dramo, enp WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_poliza=comision.id_poliza AND 
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            comision.cod_vend = enp.cod AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . "  
                            ORDER BY cod_vend ASC";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_asesor($desde, $hasta, $cod_asesor, $cia, $ramo, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, prima, comision, per_gc FROM comision 
								INNER JOIN dcia, poliza, rep_com, dramo WHERE 
								rep_com.id_cia=dcia.idcia AND
								comision.id_poliza=poliza.id_poliza AND
								rep_com.id_rep_com = comision.id_rep_com AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								comision.cod_vend = '$cod_asesor' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima_com, prima, comision, per_gc FROM comision 
							INNER JOIN dcia, poliza, rep_com, dramo WHERE 
							rep_com.id_cia=dcia.idcia AND
							comision.id_poliza=poliza.id_poliza AND
							rep_com.id_rep_com = comision.id_rep_com AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							comision.cod_vend = '$cod_asesor'";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima_com, prima, comision, per_gc FROM comision 
							INNER JOIN dcia, poliza, rep_com, dramo WHERE 
							rep_com.id_cia=dcia.idcia AND
							comision.id_poliza=poliza.id_poliza AND
							rep_com.id_rep_com = comision.id_rep_com AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							comision.cod_vend = '$cod_asesor' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, prima, comision, per_gc FROM comision 
							INNER JOIN dcia, poliza, rep_com, dramo WHERE 
							rep_com.id_cia=dcia.idcia AND
							comision.id_poliza=poliza.id_poliza AND
							rep_com.id_rep_com = comision.id_rep_com AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							comision.cod_vend = '$cod_asesor' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, prima, comision, per_gc FROM comision 
							INNER JOIN dcia, poliza, rep_com, dramo WHERE 
							rep_com.id_cia=dcia.idcia AND
							comision.id_poliza=poliza.id_poliza AND
							rep_com.id_rep_com = comision.id_rep_com AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							comision.cod_vend = '$cod_asesor' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, prima, comision, per_gc FROM comision 
								INNER JOIN dcia, poliza, rep_com, dramo WHERE 
								rep_com.id_cia=dcia.idcia AND
								comision.id_poliza=poliza.id_poliza AND
								rep_com.id_rep_com = comision.id_rep_com AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								comision.cod_vend = '$cod_asesor' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, prima, comision, per_gc FROM comision 
								INNER JOIN dcia, poliza, rep_com, dramo WHERE 
								rep_com.id_cia=dcia.idcia AND
								comision.id_poliza=poliza.id_poliza AND
								rep_com.id_rep_com = comision.id_rep_com AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								comision.cod_vend = '$cod_asesor' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, prima, comision, per_gc FROM comision 
								INNER JOIN dcia, poliza, rep_com, dramo WHERE 
								rep_com.id_cia=dcia.idcia AND
								comision.id_poliza=poliza.id_poliza AND
								rep_com.id_rep_com = comision.id_rep_com AND
								poliza.id_cod_ramo=dramo.cod_ramo AND
								f_hastapoliza >= '$desde' AND
								f_hastapoliza <= '$hasta' AND
								comision.cod_vend = '$cod_asesor' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_resumen_por_asesor_en_poliza($desde, $hasta, $cod_asesor, $cia, $ramo, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza 
                            INNER JOIN dcia, dramo WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            codvend = '$cod_asesor' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima FROM poliza 
							INNER JOIN dcia, dramo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							codvend = '$cod_asesor' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima FROM poliza 
							INNER JOIN dcia, dramo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							codvend = '$cod_asesor' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima FROM poliza 
							INNER JOIN dcia, dramo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							codvend = '$cod_asesor' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza 
							INNER JOIN dcia, dramo WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							f_hastapoliza >= '$desde' AND
							f_hastapoliza <= '$hasta' AND
							codvend = '$cod_asesor' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima FROM poliza 
                            INNER JOIN dcia, dramo WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            codvend = '$cod_asesor' AND
                            nomcia IN " . $ciaIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza 
                            INNER JOIN dcia, dramo WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            codvend = '$cod_asesor' AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima FROM poliza 
                            INNER JOIN dcia, dramo WHERE 
                            poliza.id_cia=dcia.idcia AND
                            poliza.id_cod_ramo=dramo.cod_ramo AND
                            f_hastapoliza >= '$desde' AND
                            f_hastapoliza <= '$hasta' AND
                            codvend = '$cod_asesor' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_ramo_prima_c_comp($anio, $mes, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							nomcia IN " . $ciaIn . " AND
							t_cuenta  IN " . $tipo_cuentaIn . " AND
							YEAR(f_pago_prima)=$anio AND
                            MONTH(f_pago_prima)=$mes
							ORDER BY dramo.nramo ASC ";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							YEAR(f_pago_prima)=$anio AND
                            MONTH(f_pago_prima)=$mes
							ORDER BY dramo.nramo ASC";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							t_cuenta  IN " . $tipo_cuentaIn . " AND 
							YEAR(f_pago_prima)=$anio AND
                            MONTH(f_pago_prima)=$mes
							ORDER BY dramo.nramo ASC";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT nramo FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							nomcia IN " . $ciaIn . "AND 
							YEAR(f_pago_prima)=$anio AND
                            MONTH(f_pago_prima)=$mes
							ORDER BY dramo.nramo ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_ramo_comp($ramo, $cia, $anio, $mes, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					MONTH(f_pago_prima)=$mes AND
					nomcia IN " . $ciaIn . " AND
					t_cuenta  IN " . $tipo_cuentaIn . " AND
					nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta == '') {
            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					MONTH(f_pago_prima)=$mes AND
					nramo = '$ramo'";
        }
        if ($cia == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					MONTH(f_pago_prima)=$mes AND
					t_cuenta  IN " . $tipo_cuentaIn . " AND
					nramo = '$ramo'";
        }
        if ($tipo_cuenta == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					MONTH(f_pago_prima)=$mes AND
					nomcia IN " . $ciaIn . " AND
					nramo = '$ramo'";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_ramo_comp($ramo, $cia, $anio, $mes, $tipo_cuenta)
	{
		if ($cia != '' && $tipo_cuenta != '') {
			// create sql part for IN condition by imploding comma after each id
			$ciaIn = "('" . implode("','", $cia) . "')";

			// create sql part for IN condition by imploding comma after each id
			$tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

			$sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						MONTH(f_pago_prima)=$mes AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nramo = '$ramo'";
		}
		if ($cia == '' && $tipo_cuenta == '') {
			$sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						MONTH(f_pago_prima)=$mes AND
						nramo = '$ramo'";
		}
		if ($cia == '' && $tipo_cuenta != '') {

			// create sql part for IN condition by imploding comma after each id
			$tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

			$sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						MONTH(f_pago_prima)=$mes AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nramo = '$ramo'";
		}
		if ($tipo_cuenta == '' && $cia != '') {

			// create sql part for IN condition by imploding comma after each id
			$ciaIn = "('" . implode("','", $cia) . "')";

			$sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						MONTH(f_pago_prima)=$mes AND
						nomcia IN " . $ciaIn . " AND
						nramo = '$ramo'";
		}

		$query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_distinct_cia_prima_c_comp($anio, $mes, $ramo, $tipo_cuenta)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							nramo IN " . $ramoIn . " AND
							t_cuenta  IN " . $tipo_cuentaIn . " AND
							YEAR(f_pago_prima)=$anio AND
                            MONTH(f_pago_prima)=$mes
							ORDER BY dcia.nomcia ASC ";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							YEAR(f_pago_prima)=$anio AND
                            MONTH(f_pago_prima)=$mes
							ORDER BY dcia.nomcia ASC";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							t_cuenta  IN " . $tipo_cuentaIn . " AND 
							YEAR(f_pago_prima)=$anio AND
                            MONTH(f_pago_prima)=$mes
							ORDER BY dcia.nomcia ASC";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT nomcia FROM poliza 
							INNER JOIN dcia, dramo, comision WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.id_cod_ramo=dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND 
							nramo IN " . $ramoIn . "AND 
							YEAR(f_pago_prima)=$anio AND
                            MONTH(f_pago_prima)=$mes
							ORDER BY dcia.nomcia ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_c_cobrada_cia_comp($cia, $ramo, $anio, $mes, $tipo_cuenta)
    {
        if ($ramo != '' && $tipo_cuenta != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					MONTH(f_pago_prima)=$mes AND
					nramo IN " . $ramoIn . " AND
					t_cuenta  IN " . $tipo_cuentaIn . " AND
					nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta == '') {
            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					MONTH(f_pago_prima)=$mes AND
					nomcia = '$cia'";
        }
        if ($ramo == '' && $tipo_cuenta != '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					MONTH(f_pago_prima)=$mes AND
					t_cuenta  IN " . $tipo_cuentaIn . " AND
					nomcia = '$cia'";
        }
        if ($tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza 
					INNER JOIN dcia, dramo, comision WHERE 
					poliza.id_cia=dcia.idcia AND 
					poliza.id_cod_ramo=dramo.cod_ramo AND
					poliza.id_poliza = comision.id_poliza AND 
					YEAR(f_pago_prima)=$anio AND
					MONTH(f_pago_prima)=$mes AND
					nramo IN " . $ramoIn . " AND
					nomcia = '$cia'";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_c_cobrada_cia_comp($cia, $ramo, $anio, $mes, $tipo_cuenta)
	{
		if ($ramo != '' && $tipo_cuenta != '') {
			// create sql part for IN condition by imploding comma after each id
			$ramoIn = "('" . implode("','", $ramo) . "')";

			// create sql part for IN condition by imploding comma after each id
			$tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

			$sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						MONTH(f_pago_prima)=$mes AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia = '$cia'";
		}
		if ($ramo == '' && $tipo_cuenta == '') {
			$sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						MONTH(f_pago_prima)=$mes AND
						nomcia = '$cia'";
		}
		if ($ramo == '' && $tipo_cuenta != '') {

			// create sql part for IN condition by imploding comma after each id
			$tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

			$sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						MONTH(f_pago_prima)=$mes AND
						t_cuenta  IN " . $tipo_cuentaIn . " AND
						nomcia = '$cia'";
		}
		if ($tipo_cuenta == '' && $ramo != '') {

			// create sql part for IN condition by imploding comma after each id
			$ramoIn = "('" . implode("','", $ramo) . "')";

			$sql = "SELECT count(DISTINCT comision.id_poliza) FROM poliza 
						INNER JOIN dcia, dramo, comision WHERE 
						poliza.id_cia=dcia.idcia AND 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_poliza = comision.id_poliza AND 
						YEAR(f_pago_prima)=$anio AND
						MONTH(f_pago_prima)=$mes AND
						nramo IN " . $ramoIn . " AND
						nomcia = '$cia'";
		}

		$query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }
    
    public function get_prima_mm($cond1, $cond2, $cia, $ramo, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_pago_prima) FROM poliza,dcia,dramo,comision
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
                        poliza.id_poliza=comision.id_poliza AND
						f_pago_prima >= '$cond1' AND
						f_pago_prima <= '$cond2' AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . "
						ORDER BY Month(f_pago_prima) ASC ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT Month(f_pago_prima) FROM poliza,dcia,dramo,comision
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
                        poliza.id_poliza=comision.id_poliza AND
						f_pago_prima >= '$cond1' AND
						f_pago_prima <= '$cond2' 
						ORDER BY Month(f_pago_prima) ASC";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT Month(f_pago_prima) FROM poliza,dcia,dramo,comision
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
                        poliza.id_poliza=comision.id_poliza AND
						f_pago_prima >= '$cond1' AND
						f_pago_prima <= '$cond2' AND
						nomcia IN " . $ciaIn . " 
						ORDER BY Month(f_pago_prima) ASC";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT Month(f_pago_prima) FROM poliza,dcia,dramo,comision
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
                        poliza.id_poliza=comision.id_poliza AND
						f_pago_prima >= '$cond1' AND
						f_pago_prima <= '$cond2' AND
						t_cuenta  IN " . $tipo_cuentaIn . " 
						ORDER BY Month(f_pago_prima) ASC";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_pago_prima) FROM poliza,dcia,dramo,comision
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
                        poliza.id_poliza=comision.id_poliza AND
						f_pago_prima >= '$cond1' AND
						f_pago_prima <= '$cond2' AND
						nramo IN " . $ramoIn . "  
						ORDER BY Month(f_pago_prima) ASC";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT Month(f_pago_prima) FROM poliza,dcia,dramo,comision
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
                        poliza.id_poliza=comision.id_poliza AND
						f_pago_prima >= '$cond1' AND
						f_pago_prima <= '$cond2' AND
						nomcia IN " . $ciaIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " 
						ORDER BY Month(f_pago_prima) ASC";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_pago_prima) FROM poliza,dcia,dramo,comision
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
                        poliza.id_poliza=comision.id_poliza AND
						f_pago_prima >= '$cond1' AND
						f_pago_prima <= '$cond2' AND
						nramo IN " . $ramoIn . " AND
						t_cuenta  IN " . $tipo_cuentaIn . " 
						ORDER BY Month(f_pago_prima) ASC";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT Month(f_pago_prima) FROM poliza,dcia,dramo,comision
						WHERE 
						poliza.id_cod_ramo=dramo.cod_ramo AND
						poliza.id_cia=dcia.idcia AND
                        poliza.id_poliza=comision.id_poliza AND
						f_pago_prima >= '$cond1' AND
						f_pago_prima <= '$cond2' AND
						nomcia IN " . $ciaIn . " AND
						nramo IN " . $ramoIn . "  
						ORDER BY Month(f_pago_prima) ASC";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_prima_mm($ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {

        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT * FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT * FROM poliza, dcia, dramo, tipo_poliza WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							f_desdepoliza >= '$desde' AND
							f_desdepoliza <= '$hasta' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, dcia, dramo, tipo_poliza WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							f_desdepoliza >= '$desde' AND
							f_desdepoliza <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, dcia, dramo, tipo_poliza WHERE 
							poliza.id_cod_ramo=dramo.cod_ramo AND 
							poliza.id_cia=dcia.idcia AND 
							poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
							f_desdepoliza >= '$desde' AND
							f_desdepoliza <= '$hasta' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT * FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT * FROM poliza, dcia, dramo, tipo_poliza WHERE 
								poliza.id_cod_ramo=dramo.cod_ramo AND 
								poliza.id_cia=dcia.idcia AND 
								poliza.id_tpoliza=tipo_poliza.id_t_poliza AND
								f_desdepoliza >= '$desde' AND
								f_desdepoliza <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_poliza_pc_mm($ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT prima_com, comision, per_gc FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision
                            INNER JOIN poliza, dcia, dramo
                            WHERE 
                                poliza.id_cod_ramo=dramo.cod_ramo AND 
                                poliza.id_cia=dcia.idcia AND 
                                poliza.id_poliza=comision.id_poliza AND
                                f_pago_prima >= '$desde' AND
                                f_pago_prima <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision
                            INNER JOIN poliza, dcia, dramo
                            WHERE 
                                poliza.id_cod_ramo=dramo.cod_ramo AND 
                                poliza.id_cia=dcia.idcia AND 
                                poliza.id_poliza=comision.id_poliza AND
                                f_pago_prima >= '$desde' AND
                                f_pago_prima <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT prima_com, comision, per_gc FROM comision
                            INNER JOIN poliza, dcia, dramo
                            WHERE 
                                poliza.id_cod_ramo=dramo.cod_ramo AND 
                                poliza.id_cia=dcia.idcia AND 
                                poliza.id_poliza=comision.id_poliza AND
                                f_pago_prima >= '$desde' AND
                                f_pago_prima <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }

    public function get_count_poliza_pc_mm($ramo, $desde, $hasta, $cia, $tipo_cuenta)
    {
        if ($cia != '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT comision.id_poliza FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' AND
                            nomcia IN " . $ciaIn . " AND
                            nramo IN " . $ramoIn . " AND
                            t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //1
        if ($cia == '' && $tipo_cuenta == '' && $ramo == '') {
            $sql = "SELECT DISTINCT comision.id_poliza FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' ";
        } //2
        if ($cia != '' && $tipo_cuenta == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT comision.id_poliza FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' AND
							nomcia IN " . $ciaIn . " ";
        } //3
        if ($cia == '' && $tipo_cuenta != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT comision.id_poliza FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' AND
							t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //4
        if ($cia == '' && $tipo_cuenta == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT comision.id_poliza FROM comision
                        INNER JOIN poliza, dcia, dramo
                        WHERE 
                            poliza.id_cod_ramo=dramo.cod_ramo AND 
                            poliza.id_cia=dcia.idcia AND 
                            poliza.id_poliza=comision.id_poliza AND
                            f_pago_prima >= '$desde' AND
                            f_pago_prima <= '$hasta' AND
							nramo IN " . $ramoIn . "  ";
        } //5
        if ($cia != '' && $tipo_cuenta != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            $sql = "SELECT DISTINCT comision.id_poliza FROM comision
                            INNER JOIN poliza, dcia, dramo
                            WHERE 
                                poliza.id_cod_ramo=dramo.cod_ramo AND 
                                poliza.id_cia=dcia.idcia AND 
                                poliza.id_poliza=comision.id_poliza AND
                                f_pago_prima >= '$desde' AND
                                f_pago_prima <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //6
        if ($cia == '' && $tipo_cuenta != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $tipo_cuentaIn = "('" . implode("','", $tipo_cuenta) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT comision.id_poliza FROM comision
                            INNER JOIN poliza, dcia, dramo
                            WHERE 
                                poliza.id_cod_ramo=dramo.cod_ramo AND 
                                poliza.id_cia=dcia.idcia AND 
                                poliza.id_poliza=comision.id_poliza AND
                                f_pago_prima >= '$desde' AND
                                f_pago_prima <= '$hasta' AND
								nramo IN " . $ramoIn . " AND
								t_cuenta  IN " . $tipo_cuentaIn . " ";
        } //7
        if ($cia != '' && $tipo_cuenta == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT DISTINCT comision.id_poliza FROM comision
                            INNER JOIN poliza, dcia, dramo
                            WHERE 
                                poliza.id_cod_ramo=dramo.cod_ramo AND 
                                poliza.id_cia=dcia.idcia AND 
                                poliza.id_poliza=comision.id_poliza AND
                                f_pago_prima >= '$desde' AND
                                f_pago_prima <= '$hasta' AND
								nomcia IN " . $ciaIn . " AND
								nramo IN " . $ramoIn . "  ";
        } //8

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            return 0;
        } else {
            $i = 0;
            while ($fila = $query->fetch_assoc()) {
                $reg[$i] = $fila;
                $i++;
            }
            return $reg;
        }

        mysqli_close($this->con);
    }
}
