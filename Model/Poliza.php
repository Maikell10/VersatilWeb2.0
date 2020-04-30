<?php

require_once dirname(__DIR__) . '\Model\Conection.php';

class Poliza extends Conection
{
    public function getPolizas()
    {
        $sql = 'SELECT poliza.id_poliza, poliza.cod_poliza, 
                poliza.f_desdepoliza, poliza.f_hastapoliza, 
                poliza.currency, poliza.sumaasegurada, poliza.codvend,
                prima, poliza.f_poliza, nombre_t, apellido_t,
                idnom AS nombre, pdf, nomcia
                FROM 
                poliza
                INNER JOIN titular, dcia, ena
                WHERE 
                poliza.id_titular = titular.id_titular AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = ena.cod 

                UNION ALL

                SELECT poliza.id_poliza, poliza.cod_poliza, 
                            poliza.f_desdepoliza, poliza.f_hastapoliza, 
                            poliza.currency, poliza.sumaasegurada, poliza.codvend,
                            prima, poliza.f_poliza, nombre_t, apellido_t,
                            nombre, pdf, nomcia
                FROM 
                poliza
                INNER JOIN titular, dcia, enp
                WHERE 
                poliza.id_titular = titular.id_titular AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = enp.cod 

                UNION ALL

                SELECT poliza.id_poliza, poliza.cod_poliza, 
                            poliza.f_desdepoliza, poliza.f_hastapoliza, 
                            poliza.currency, poliza.sumaasegurada, poliza.codvend,
                            prima, poliza.f_poliza, nombre_t, apellido_t,
                            nombre, pdf, nomcia
                FROM 
                poliza
                INNER JOIN titular, dcia, enr
                WHERE 
                poliza.id_titular = titular.id_titular AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = enr.cod ';

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_fecha_min_max($cond, $campo, $tabla)
    {
        $sql = "SELECT $cond($campo) FROM $tabla";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_array()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_fecha_min_max_ref($cond)
    {
        $sql = "SELECT $cond(f_hastapoliza) 
                FROM poliza
                INNER JOIN enr
                WHERE 
                poliza.codvend=enr.cod AND
                not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza) ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_array()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_ejecutivo()
    {
        $sql = "SELECT idena AS id_asesor, id, cod, idnom AS nombre,  act FROM ena 
                UNION
                SELECT id_enp AS id_asesor, id, cod, nombre, act FROM enp 
                UNION
                SELECT id_enr AS id_asesor, id, cod, nombre, act FROM enr
                ORDER BY nombre ASC";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_ejecutivo_by_cod($cod_vend)
    {
        $sql = "SELECT cod, idnom AS nombre FROM ena WHERE cod = '$cod_vend'
                UNION
                SELECT nombre, cod FROM enp WHERE cod = '$cod_vend'
                UNION
                SELECT nombre, cod FROM enr WHERE cod = '$cod_vend'";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_distinct_element($campo, $tabla)
    {
        $sql = "SELECT DISTINCT $campo FROM $tabla ORDER BY $campo ASC";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_element($tabla, $campo)
    {
        $sql = "SELECT * FROM $tabla ORDER BY $campo ASC";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_element_by_id($tabla, $cond, $campo)
    {
        $sql = "SELECT * FROM $tabla WHERE $cond = '$campo'";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function renovar()
    {
        $fhoy = date("Y-m-d");
        //resto 2 meses
        //$fmax = date("Y-m-d", strtotime($fhoy . "- 2 month"));
        $fmax = date("Y") . '-01-01';

        $sql = "SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, idnom AS nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, ena
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = ena.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enp
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enp.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enr
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enr.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function renovarG($anio)
    {
        /*if (date("Y") == $anio) {
            $fhoy = date("Y-m-d");
            $fmax = date("Y") . '-01-01';
        } else {*/
        $fhoy = $anio . '-12-31';
        $fmax = $anio . '-01-01';
        //}

        $sql = "SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, idnom AS nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, ena
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = ena.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enp
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enp.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enr
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enr.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function renovarSE($anio, $mes)
    {
        $fmin = $anio . '-' . $mes . '-31';
        //resto 2 meses
        //$fmax = date("Y-m-d", strtotime($fmin . "- 2 month"));
        $fmax = $anio . '-' . $mes . '-01';

        $sql = "SELECT poliza.id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, idnom AS nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, ena, seguimiento
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = ena.cod AND
                        poliza.id_poliza = seguimiento.id_poliza AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fmin' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT poliza.id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enp, seguimiento
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enp.cod AND
                        poliza.id_poliza = seguimiento.id_poliza AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fmin' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT poliza.id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enr, seguimiento
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enr.cod AND
                        poliza.id_poliza = seguimiento.id_poliza AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fmin' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old) ";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function renovarM($anio, $mes)
    {
        $fmin = $anio . '-' . $mes . '-31';
        //resto 2 meses
        //$fmax = date("Y-m-d", strtotime($fmin . "- 2 month"));
        $fmax = $anio . '-' . $mes . '-01';

        $sql = "SELECT poliza.id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, idnom AS nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, ena
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = ena.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fmin' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT poliza.id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enp
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enp.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fmin' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT poliza.id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enr
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enr.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fmin' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old) ";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function renovarME($anio, $mes)
    {
        $fmin = $anio . '-' . $mes . '-31';
        //resto 2 meses
        //$fmax = date("Y-m-d", strtotime($fmin . "- 2 month"));
        $fmax = $anio . '-' . $mes . '-01';

        $sql = "SELECT poliza.id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, idnom AS nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, ena
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = ena.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fmin' AND
                        exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT poliza.id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enp
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enp.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fmin' AND
                        exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT poliza.id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enr
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enr.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fmin' AND
                        exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old) ";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function comprobar_poliza($cod_poliza, $id_cia)
    {
        $fhoy = date("Y-m-d");

        $sql = "SELECT cod_poliza, id_poliza  FROM 
                        poliza
                        WHERE 
                        poliza.cod_poliza = '$cod_poliza' AND
                        poliza.id_cia = '$id_cia' AND
                        poliza.f_hastapoliza >= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza)";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_array()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_tarjeta_venc()
    {
        $fhoy = date("Y-m-d");

        $sql = "SELECT tarjeta.id_tarjeta, n_tarjeta, cvv, fechaV, nombre_titular, idrecibo, banco 
				  FROM tarjeta, drecibo
				  WHERE
					drecibo.id_tarjeta = tarjeta.id_tarjeta AND
					tarjeta.id_tarjeta != 0 AND
					fechaV <= '$fhoy'
				  ORDER BY fechaV DESC";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_polizas_r()
    {

        $sql = "SELECT * FROM 
                poliza
                INNER JOIN dcia, enr
                WHERE 
                poliza.id_cia=dcia.idcia AND
                poliza.codvend=enr.cod AND
                not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
                ORDER BY `poliza`.`id_poliza` ASC";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_poliza_total_by_id($id_poliza)
    {
        $sql = "SELECT id_tomador, poliza.currency, 
                poliza.id_poliza, id_usuario, 
                f_poliza, f_desdepoliza, f_hastapoliza, id_cia,
                codvend, nombre_t, apellido_t, poliza.cod_poliza, idnom as nombre, 
                cod, fechaV, tipo_poliza, nramo, nomcia, sumaasegurada, drecibo.prima,
                fpago, t_cuenta, forma_pago, tarjeta.id_tarjeta, n_tarjeta, cvv, nombre_titular,
                f_desderecibo, f_hastarecibo, id_zproduccion, cod_recibo,
                ncuotas, montocuotas, obs_p, f_nac, id_sexo, id_ecivil, ci,
                cell, telf, titular.email, direcc, id, per_gc, nopre1,
                nopre1_renov, id_cod_ramo, id_tpoliza, obs, created_at, tarjeta.banco
                FROM 
                poliza
                INNER JOIN drecibo, titular, tipo_poliza, dramo, dcia, ena, tarjeta
                WHERE 
                poliza.id_poliza = drecibo.idrecibo AND
                poliza.id_titular = titular.id_titular AND 
                poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                poliza.id_cod_ramo = dramo.cod_ramo AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = ena.cod AND
                drecibo.id_tarjeta = tarjeta.id_tarjeta AND
                poliza.id_poliza = $id_poliza";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_poliza_total1_by_id($id_poliza)
    {
        $sql = "SELECT id_tomador, poliza.currency, 
                poliza.id_poliza, id_usuario, 
                f_poliza, f_desdepoliza, f_hastapoliza, id_cia,
                codvend, nombre_t, apellido_t, poliza.cod_poliza, nombre, 
                cod, fechaV, tipo_poliza, nramo, nomcia, sumaasegurada, drecibo.prima,
                fpago, t_cuenta, forma_pago, tarjeta.id_tarjeta, n_tarjeta, cvv, nombre_titular,
                f_desderecibo, f_hastarecibo, id_zproduccion, cod_recibo,
                ncuotas, montocuotas, obs_p, f_nac, id_sexo, id_ecivil, ci,
                cell, telf, titular.email, direcc, id, per_gc,
                id_cod_ramo, id_tpoliza, obs, created_at, tarjeta.banco
                FROM 
                poliza
                INNER JOIN drecibo, titular, tipo_poliza, dramo, dcia, enp, tarjeta
                WHERE 
                poliza.id_poliza = drecibo.idrecibo AND
                poliza.id_titular = titular.id_titular AND 
                poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                poliza.id_cod_ramo = dramo.cod_ramo AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = enp.cod AND
                drecibo.id_tarjeta = tarjeta.id_tarjeta AND
                poliza.id_poliza = $id_poliza";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_poliza_total2_by_id($id_poliza)
    {
        $sql = "SELECT id_tomador, poliza.currency, 
                poliza.id_poliza, id_usuario, 
                f_poliza, f_desdepoliza, f_hastapoliza, id_cia,
                codvend, nombre_t, apellido_t, poliza.cod_poliza, nombre, 
                cod, fechaV, tipo_poliza, nramo, nomcia, sumaasegurada, drecibo.prima,
                fpago, t_cuenta, forma_pago, tarjeta.id_tarjeta, n_tarjeta, cvv, nombre_titular,
                f_desderecibo, f_hastarecibo, id_zproduccion, cod_recibo,
                ncuotas, montocuotas, obs_p, f_nac, id_sexo, id_ecivil, ci,
                cell, telf, titular.email, direcc, id, per_gc,
                id_cod_ramo, id_tpoliza, obs, created_at, monto, enr.currency as currencyM, tarjeta.banco
                FROM 
                poliza
                INNER JOIN drecibo, titular, tipo_poliza, dramo, dcia, enr, tarjeta
                WHERE 
                poliza.id_poliza = drecibo.idrecibo AND
                poliza.id_titular = titular.id_titular AND 
                poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                poliza.id_cod_ramo = dramo.cod_ramo AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = enr.cod AND
                drecibo.id_tarjeta = tarjeta.id_tarjeta AND
                poliza.id_poliza = $id_poliza";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_per_gc_cia_pref($f_desde, $id_cia, $cod)
    {
        $sql = "SELECT *  FROM 
                cia_pref
                WHERE 
                f_hasta_pref >= '$f_desde' AND
                f_desde_pref <= '$f_desde'AND
                id_cia = $id_cia AND
                cod_vend = '$cod'
                ORDER BY id_cia_pref DESC";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_comision_rep_com_by_id($id_poliza)
    {
        $sql = "SELECT * FROM comision 
                INNER JOIN rep_com, poliza
                WHERE 
                comision.id_rep_com = rep_com.id_rep_com AND
                poliza.id_poliza = comision.id_poliza AND
                comision.id_poliza = '$id_poliza'";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_comision_by_polizaP($id_poliza)
    {
        $sql = "SELECT f_hasta_rep, SUM(prima_com) FROM comision 
                INNER JOIN rep_com, poliza
                WHERE 
                comision.id_rep_com = rep_com.id_rep_com AND
                poliza.id_poliza = comision.id_poliza AND
                comision.id_poliza = '$id_poliza' ";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_poliza_total_by_filtro_f_product($f_desde, $f_hasta)
    {
        $sql = "SELECT poliza.id_poliza, poliza.cod_poliza, 
                    poliza.f_desdepoliza, poliza.f_hastapoliza, 
                    poliza.currency, poliza.sumaasegurada, poliza.codvend,
                    prima, poliza.f_poliza, nombre_t, apellido_t,
                    idnom AS nombre, pdf, nomcia, poliza.id_titular
                    FROM 
                    poliza
                    INNER JOIN titular, tipo_poliza, dcia, ena
                    WHERE 
                    poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                    poliza.id_titular = titular.id_titular AND
                    poliza.f_poliza >= '$f_desde' AND
                    poliza.f_poliza <= '$f_hasta' AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = ena.cod 
                    
                    UNION ALL
                    
                SELECT poliza.id_poliza, poliza.cod_poliza, 
                    poliza.f_desdepoliza, poliza.f_hastapoliza, 
                    poliza.currency, poliza.sumaasegurada, poliza.codvend,
                    prima, poliza.f_poliza, nombre_t, apellido_t,
                    nombre, pdf, nomcia, poliza.id_titular
                    FROM 
                    poliza
                    INNER JOIN titular, tipo_poliza, dcia, enr
                    WHERE 
                    poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                    poliza.id_titular = titular.id_titular AND
                    poliza.f_poliza >= '$f_desde' AND
                    poliza.f_poliza <= '$f_hasta' AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enr.cod
                    
                    UNION ALL 
                    
                SELECT poliza.id_poliza, poliza.cod_poliza, 
                    poliza.f_desdepoliza, poliza.f_hastapoliza, 
                    poliza.currency, poliza.sumaasegurada, poliza.codvend,
                    prima, poliza.f_poliza, nombre_t, apellido_t,
                    nombre, pdf, nomcia, poliza.id_titular
                    FROM 
                    poliza
                    INNER JOIN titular, tipo_poliza, dcia, enp
                    WHERE 
                    poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                    poliza.id_titular = titular.id_titular AND
                    poliza.f_poliza >= '$f_desde' AND
                    poliza.f_poliza <= '$f_hasta' AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enp.cod ";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_seguimiento($id_poliza)
    {
        $sql = "SELECT * FROM seguimiento 
				INNER JOIN usuarios
				WHERE 
				seguimiento.id_usuario = usuarios.id_usuario AND
				id_poliza = '$id_poliza'  
				ORDER BY seguimiento.created_at DESC";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_poliza_total_by_asesor_ena_user($cod_asesor_user)
    {
        $sql = "SELECT poliza.id_poliza, poliza.cod_poliza, 
                        poliza.f_desdepoliza, poliza.f_hastapoliza, 
                        poliza.currency, poliza.sumaasegurada, poliza.codvend,
                        prima, poliza.f_poliza, nombre_t, apellido_t,
                        idnom, pdf, nomcia
                FROM 
                poliza
                INNER JOIN titular, dcia, ena
                WHERE 
                poliza.id_titular = titular.id_titular AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = ena.cod AND
                poliza.codvend = '$cod_asesor_user'";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_f_cia_pref($id_cia)
    {
        $sql = "SELECT DISTINCT f_desde_pref, f_hasta_pref 
				FROM cia_pref WHERE id_cia=$id_cia ORDER BY f_desde_pref DESC";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_cia_pref($id_cia, $f_desde, $f_hasta)
    {
        $sql = "SELECT id_cia, id_cia_pref, nomcia, rif, cia_pref.f_desde_pref, cia_pref.f_hasta_pref, per_com, 
						per_gc_sum, cod_vend, idnom, nopre1, nopre1_renov, gc_viajes, gc_viajes_renov
				FROM cia_pref 
				INNER JOIN
				ena, dcia
				WHERE 
				cia_pref.id_cia = dcia.idcia AND
				cia_pref.cod_vend = ena.cod AND
				id_cia = $id_cia AND
				cia_pref.f_desde_pref = '$f_desde' AND
				cia_pref.f_hasta_pref = '$f_hasta'";
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_asesor_por_cod($cod)
    {

        if (is_array($cod)) {
            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $cod) . "')";

            $sql = "SELECT ena.cod AS cod, ena.idnom as nombre FROM ena WHERE cod IN $asesorIn
						UNION
						SELECT enp.cod as cod, enp.nombre as nombre FROM enp WHERE cod IN $asesorIn
						UNION
						SELECT enr.cod as cod, enr.nombre as nombre FROM enr WHERE cod IN $asesorIn";
        } else {
            $sql = "SELECT ena.cod AS cod, ena.idnom as nombre FROM ena WHERE cod='$cod'
					UNION
					SELECT enp.cod as cod, enp.nombre as nombre FROM enp WHERE cod='$cod'
					UNION
					SELECT enr.cod as cod, enr.nombre as nombre FROM enr WHERE cod='$cod'";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_poliza_total_by_filtro($mes, $anio, $cia, $ramo, $asesor)
    {

        if ($anio == '') {
            $anio = "('" . date("Y") . "')";
        } else {
            // create sql part for IN condition by imploding comma after each id
            $anio = "('" . implode("','", $anio) . "')";
        }

        if ($cia != '' && $asesor != '' && $mes != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            // create sql part for IN condition by imploding comma after each id
            $mesIn = "('" . implode("','", $mes) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";


            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                    FROM 
                    poliza
                    INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                    WHERE
                    poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = ena.cod AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    YEAR(poliza.f_desdepoliza) IN $anio AND
                    MONTH(poliza.f_desdepoliza) IN $mesIn AND
                    dcia.nomcia IN $ciaIn AND
                    dramo.nramo IN $ramoIn AND
                    codvend  IN $asesorIn  

                    UNION ALL
                    
            SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia 
                    FROM 
                    poliza
                    INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                    WHERE
                    poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enr.cod AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    YEAR(poliza.f_desdepoliza) IN $anio AND
                    MONTH(poliza.f_desdepoliza) IN $mesIn AND
                    dcia.nomcia IN $ciaIn AND
                    dramo.nramo IN $ramoIn AND
                    codvend  IN $asesorIn
                    
                    UNION ALL
                    
            SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia 
                    FROM 
                    poliza
                    INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                    WHERE
                    poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enp.cod AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    YEAR(poliza.f_desdepoliza) IN $anio AND
                    MONTH(poliza.f_desdepoliza) IN $mesIn AND
                    dcia.nomcia IN $ciaIn AND
                    dramo.nramo IN $ramoIn AND
                    codvend  IN $asesorIn ";
        } //1
        if ($cia == '' && $asesor == '' && $mes == '' && $ramo == '') {
            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						poliza.codvend = ena.cod AND
                        YEAR(poliza.f_desdepoliza) IN $anio  

                        UNION ALL

            SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						poliza.codvend = enr.cod AND
                        YEAR(poliza.f_desdepoliza) IN $anio 
                        
                        UNION ALL

            SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						poliza.codvend = enp.cod AND
						YEAR(poliza.f_desdepoliza) IN $anio";
        } //2
        if ($cia != '' && $asesor == '' && $mes == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
						poliza
						INNER JOIN titular, tipo_poliza, dcia, ena, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = ena.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
                        nomcia IN $ciaIn

                    UNION ALL

                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
						poliza
						INNER JOIN titular, tipo_poliza, dcia, enr, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = enr.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
						nomcia IN $ciaIn
                        
                        UNION ALL

                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
						poliza
						INNER JOIN titular, tipo_poliza, dcia, enp, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = enp.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
						nomcia IN $ciaIn ";
        } //3
        if ($cia == '' && $asesor != '' && $mes == '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
						poliza
						INNER JOIN titular, tipo_poliza, dcia, ena, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = ena.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
                        codvend  IN $asesorIn 
                        
                    UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM  
						poliza
						INNER JOIN titular, tipo_poliza, dcia, enr, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = enr.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
						codvend  IN $asesorIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM  
						poliza
						INNER JOIN titular, tipo_poliza, dcia, enp, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = enp.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
						codvend  IN $asesorIn ";
        } //4
        if ($cia == '' && $asesor == '' && $mes != '' && $ramo == '') {

            // create sql part for IN condition by imploding comma after each id
            $mesIn = "('" . implode("','", $mes) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
						poliza
						INNER JOIN titular, tipo_poliza, dcia, ena, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = ena.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn  
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
						poliza
						INNER JOIN titular, tipo_poliza, dcia, enr, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = enr.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
						MONTH(poliza.f_desdepoliza) IN $mesIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
						poliza
						INNER JOIN titular, tipo_poliza, dcia, enp, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = enp.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
						MONTH(poliza.f_desdepoliza) IN $mesIn ";
        } //5
        if ($cia == '' && $asesor == '' && $mes == '' && $ramo != '') {

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia 
                        FROM
						poliza
						INNER JOIN titular, tipo_poliza, dcia, ena, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = ena.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
						dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
						poliza
						INNER JOIN titular, tipo_poliza, dcia, enr, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = enr.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
						poliza
						INNER JOIN titular, tipo_poliza, dcia, enp, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.codvend = enp.cod AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						YEAR(poliza.f_desdepoliza) IN $anio AND
						dramo.nramo IN $ramoIn ";
        } //6
        if ($cia != '' && $asesor != '' && $mes == '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        nomcia IN $ciaIn AND
                        codvend  IN $asesorIn
                        
                        UNION ALL
                            
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        nomcia IN $ciaIn AND
                        codvend  IN '$asesorIn
                        
                        UNION ALL
                            
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        nomcia IN $ciaIn AND
                        codvend  IN '$asesorIn ";
        } //7
        if ($cia == '' && $asesor != '' && $mes != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            // create sql part for IN condition by imploding comma after each id
            $mesIn = "('" . implode("','", $mes) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        codvend  IN $asesorIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        codvend  IN $asesorIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        codvend  IN $asesorIn ";
        } //8
        if ($cia == '' && $asesor == '' && $mes != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            // create sql part for IN condition by imploding comma after each id
            $mesIn = "('" . implode("','", $mes) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM  
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        dramo.nramo IN $ramoIn ";
        } //9
        if ($cia != '' && $asesor == '' && $mes != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $mesIn = "('" . implode("','", $mes) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        nomcia IN $ciaIn 
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        nomcia IN $ciaIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        nomcia IN $ciaIn ";
        } //10
        if ($cia != '' && $asesor == '' && $mes == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        dramo.nramo IN $ramoIn 
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        dramo.nramo IN $ramoIn ";
        } //11
        if ($cia == '' && $asesor != '' && $mes == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        codvend  IN $asesorIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        codvend  IN $asesorIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        codvend  IN $asesorIn AND
                        dramo.nramo IN $ramoIn ";
        } //12
        if ($cia != '' && $asesor != '' && $mes != '' && $ramo == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            // create sql part for IN condition by imploding comma after each id
            $mesIn = "('" . implode("','", $mes) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        nomcia IN $ciaIn AND
                        codvend  IN $asesorIn 
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        nomcia IN $ciaIn AND
                        codvend  IN $asesorIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        nomcia IN $ciaIn AND
                        codvend  IN $asesorIn ";
        } //13
        if ($cia != '' && $asesor != '' && $mes == '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM  
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        nomcia IN $ciaIn AND
                        codvend  IN $asesorIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        nomcia IN $ciaIn AND
                        codvend  IN $asesorIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        nomcia IN $ciaIn AND
                        codvend  IN $asesorIn AND
                        dramo.nramo IN $ramoIn ";
        } //14
        if ($cia != '' && $asesor == '' && $mes != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $mesIn = "('" . implode("','", $mes) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia 
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        nomcia IN $ciaIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        nomcia IN $ciaIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        nomcia IN $ciaIn AND
                        dramo.nramo IN $ramoIn ";
        } //15
        if ($cia == '' && $asesor != '' && $mes != '' && $ramo != '') {
            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            // create sql part for IN condition by imploding comma after each id
            $mesIn = "('" . implode("','", $mes) . "')";

            // create sql part for IN condition by imploding comma after each id
            $ramoIn = "('" . implode("','", $ramo) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        codvend  IN $asesorIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM  
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        codvend  IN $asesorIn AND
                        dramo.nramo IN $ramoIn
                        
                        UNION ALL
                        
                    SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM  
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
                        WHERE 
                        poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) IN $anio AND
                        MONTH(poliza.f_desdepoliza) IN $mesIn AND
                        codvend  IN $asesorIn AND
                        dramo.nramo IN $ramoIn ";
        } //16

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_poliza_total_by_filtro_a($cod)
    {
        $sql = "SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, idnom AS nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, ena, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						poliza.codvend = ena.cod AND
                        ena.cod = '$cod'  

                        UNION ALL

            SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enr, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						poliza.codvend = enr.cod AND
                        enr.cod = '$cod'  
                        
                        UNION ALL

            SELECT id_poliza, poliza.id_titular, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, nombre, titular.nombre_t, titular.apellido_t, pdf, nomcia
                        FROM 
                        poliza
                        INNER JOIN titular, tipo_poliza, dcia, enp, dramo
						WHERE 
						poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						poliza.codvend = enp.cod AND
						enp.cod = '$cod' ";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_polizas_by_tarjetaV($id_tarjeta)
    {
        $sql = "SELECT poliza.id_poliza, poliza.cod_poliza, 
							poliza.f_desdepoliza, poliza.f_hastapoliza, 
							poliza.currency, poliza.sumaasegurada, poliza.codvend,
							drecibo.prima, poliza.f_poliza, nombre_t, apellido_t,
							idnom AS nombre, pdf, nomcia
				    FROM 
                    poliza
                  	INNER JOIN drecibo, titular, dcia, ena
                  	WHERE 
                  	poliza.id_poliza = drecibo.idrecibo AND
                  	poliza.id_titular = titular.id_titular AND
                  	poliza.id_cia = dcia.idcia AND
					poliza.codvend = ena.cod AND
                    drecibo.id_tarjeta = $id_tarjeta

                    UNION ALL

                SELECT poliza.id_poliza, poliza.cod_poliza, 
							 poliza.f_desdepoliza, poliza.f_hastapoliza, 
							 poliza.currency, poliza.sumaasegurada, poliza.codvend,
							 drecibo.prima, poliza.f_poliza, nombre_t, apellido_t,
							 nombre, pdf, nomcia
				    FROM 
                    poliza
                  	INNER JOIN drecibo, titular, dcia, enp
                  	WHERE 
                  	poliza.id_poliza = drecibo.idrecibo AND
                  	poliza.id_titular = titular.id_titular AND
                  	poliza.id_cia = dcia.idcia AND
					poliza.codvend = enp.cod AND
                    drecibo.id_tarjeta = $id_tarjeta

                    UNION ALL

                SELECT poliza.id_poliza, poliza.cod_poliza, 
				  			 poliza.f_desdepoliza, poliza.f_hastapoliza, 
							 poliza.currency, poliza.sumaasegurada, poliza.codvend,
							 drecibo.prima, poliza.f_poliza, nombre_t, apellido_t,
							 nombre, pdf, nomcia
				    FROM 
                    poliza
                  	INNER JOIN drecibo, titular, dcia, enr
                  	WHERE 
                  	poliza.id_poliza = drecibo.idrecibo AND
                  	poliza.id_titular = titular.id_titular AND
                  	poliza.id_cia = dcia.idcia AND
					poliza.codvend = enr.cod AND
                    drecibo.id_tarjeta = $id_tarjeta
                    ORDER BY id_poliza ASC";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_poliza_pendiente()
    {
        $sql = "SELECT poliza.id_poliza, cod_poliza, f_poliza, codvend, nomcia, asegurado, idnom AS nombre  
                FROM 
                poliza
                INNER JOIN titular, dcia, titular_pre_poliza, ena
                WHERE 
                poliza.id_poliza = titular_pre_poliza.id_poliza AND
                poliza.id_titular = titular.id_titular AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = ena.cod AND
                poliza.id_titular = '0'

                UNION ALL
                
                SELECT poliza.id_poliza, cod_poliza, f_poliza, codvend, nomcia, asegurado, nombre  
                FROM 
                poliza
                INNER JOIN titular, dcia, titular_pre_poliza, enr
                WHERE 
                poliza.id_poliza = titular_pre_poliza.id_poliza AND
                poliza.id_titular = titular.id_titular AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = enr.cod AND
                poliza.id_titular = '0'  
                
                UNION ALL
                
                SELECT poliza.id_poliza, cod_poliza, f_poliza, codvend, nomcia, asegurado, nombre  
                FROM 
                poliza
                INNER JOIN titular, dcia, titular_pre_poliza, enp
                WHERE 
                poliza.id_poliza = titular_pre_poliza.id_poliza AND
                poliza.id_titular = titular.id_titular AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = enp.cod AND
                poliza.id_titular = '0' ";
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

    public function get_poliza_total_by_filtro_renov_distinct_c($f_desde, $f_hasta, $asesor)
    {

        if ($asesor != '') {

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT DISTINCT nomcia FROM 
					poliza
					INNER JOIN dcia
					WHERE 
					poliza.id_cia = dcia.idcia AND
					poliza.f_hastapoliza >= '$f_desde' AND
					poliza.f_hastapoliza <= '$f_hasta' AND
					codvend IN " . $asesorIn . "
					ORDER BY nomcia ASC";
        }
        if ($asesor == '') {
            $sql = "SELECT DISTINCT nomcia FROM 
					poliza
					INNER JOIN dcia
					WHERE 
					poliza.id_cia = dcia.idcia AND
					poliza.f_hastapoliza >= '$f_desde' AND
					poliza.f_hastapoliza <= '$f_hasta' 
					ORDER BY nomcia ASC";
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

    public function get_poliza_total_by_filtro_renov_distinct_a($f_desde, $f_hasta, $cia)
    {

        if ($cia != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT codvend, idnom AS nombre FROM 
                    poliza
                    INNER JOIN  ena, dcia
                    WHERE 
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = ena.cod AND
                    nomcia IN " . $ciaIn . " AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'
                    
                    UNION ALL
                    
                    SELECT DISTINCT codvend, nombre FROM 
                    poliza
                    INNER JOIN  enr, dcia
                    WHERE 
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enr.cod AND
                    nomcia IN " . $ciaIn . " AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'

                    UNION ALL

                    SELECT DISTINCT codvend, nombre FROM 
                    poliza
                    INNER JOIN  enp, dcia
                    WHERE 
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enp.cod AND
                    nomcia IN " . $ciaIn . " AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'
                    ORDER BY nombre ASC";
        }
        if ($cia == '') {
            $sql = "SELECT DISTINCT codvend, idnom AS nombre FROM 
                    poliza
                    INNER JOIN  ena
                    WHERE 
                    poliza.codvend = ena.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'
                    
                    UNION ALL
                    
                    SELECT DISTINCT codvend, nombre FROM 
                    poliza
                    INNER JOIN  enr
                    WHERE 
                    poliza.codvend = enr.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'

                    UNION ALL

                    SELECT DISTINCT codvend, nombre FROM 
                    poliza
                    INNER JOIN  enp
                    WHERE 
                    poliza.codvend = enp.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'
                    ORDER BY nombre ASC";
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

    public function get_poliza_total_by_filtro_renov_distinct_ac($f_desde, $f_hasta, $cia, $asesor)
    {

        if ($cia != '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT DISTINCT nomcia FROM 
                    poliza
                    INNER JOIN dcia
                    WHERE 
                    poliza.id_cia = dcia.idcia AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    nomcia IN " . $ciaIn . " AND
					codvend  IN " . $asesorIn . " 
                    ORDER BY nomcia ASC";
        }
        if ($cia == '' && $asesor == '') {
            $sql = "SELECT DISTINCT nomcia FROM 
                    poliza
                    INNER JOIN dcia
                    WHERE 
                    poliza.id_cia = dcia.idcia AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'
                    ORDER BY nomcia ASC";
        }
        if ($cia == '' && $asesor != '') {

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT DISTINCT nomcia FROM 
                    poliza
                    INNER JOIN dcia
                    WHERE 
                    poliza.id_cia = dcia.idcia AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    codvend  IN " . $asesorIn . " 
                    ORDER BY nomcia ASC";
        }
        if ($asesor == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT nomcia FROM 
                    poliza
                    INNER JOIN dcia
                    WHERE 
                    poliza.id_cia = dcia.idcia AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    nomcia IN " . $ciaIn . "
                    ORDER BY nomcia ASC";
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

    public function get_poliza_total_by_filtro_renov_c($f_desde, $f_hasta, $cia, $asesor)
    {

        if ($asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT *  FROM 
						poliza
						INNER JOIN titular, dcia, dramo
						WHERE 
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						poliza.f_hastapoliza >= '$f_desde' AND
						poliza.f_hastapoliza <= '$f_hasta' AND
						nomcia = '$cia' AND
						codvend IN " . $asesorIn . "
						ORDER BY poliza.f_hastapoliza ASC";
        }
        if ($asesor == '') {
            $sql = "SELECT *  FROM 
						poliza
						INNER JOIN titular, dcia, dramo
						WHERE 
						poliza.id_titular = titular.id_titular AND
						poliza.id_cia = dcia.idcia AND
						poliza.id_cod_ramo = dramo.cod_ramo AND
						poliza.f_hastapoliza >= '$f_desde' AND
						poliza.f_hastapoliza <= '$f_hasta' AND
						nomcia = '$cia'
						ORDER BY poliza.f_hastapoliza ASC";
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

    public function get_poliza_total_by_filtro_renov_a($f_desde, $f_hasta, $cia, $asesor)
    {

        if ($cia != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT id_poliza, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, currency, cod_poliza, nombre_t, apellido_t, nomcia, nramo, pdf
                FROM 
                poliza
                INNER JOIN titular, dcia, dramo
                WHERE 
                poliza.id_titular = titular.id_titular AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_cod_ramo = dramo.cod_ramo AND
                poliza.f_hastapoliza >= '$f_desde' AND
                poliza.f_hastapoliza <= '$f_hasta' AND
                poliza.codvend = '$asesor' AND
				nomcia IN ' . $ciaIn . '
                ORDER BY poliza.f_hastapoliza ASC";
        }
        if ($cia == '') {
            $sql = "SELECT id_poliza, sumaasegurada, prima, f_desdepoliza, f_hastapoliza, currency, cod_poliza, nombre_t, apellido_t, nomcia, nramo, pdf
                FROM 
                poliza
                INNER JOIN titular, dcia, dramo
                WHERE 
                poliza.id_titular = titular.id_titular AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_cod_ramo = dramo.cod_ramo AND
                poliza.f_hastapoliza >= '$f_desde' AND
                poliza.f_hastapoliza <= '$f_hasta' AND
                poliza.codvend = '$asesor'
                ORDER BY poliza.f_hastapoliza ASC";
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

    public function get_poliza_total_by_filtro_renov_ac($f_desde, $f_hasta, $cia, $asesor)
    {

        if ($cia != '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, idnom AS nombre, cod_poliza, pdf, id_cia
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, ena
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = ena.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    nomcia IN " . $ciaIn . " AND
                    codvend  IN " . $asesorIn . " 
                    
                    UNION ALL

                SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, nombre, cod_poliza, pdf, id_cia  
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, enr
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = enr.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    nomcia IN " . $ciaIn . " AND
                    codvend  IN " . $asesorIn . " 

                    UNION ALL

                SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, nombre, cod_poliza, pdf, id_cia  
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, enp
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = enp.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    nomcia IN " . $ciaIn . " AND
                    codvend  IN " . $asesorIn . " 
                    ORDER BY f_hastapoliza ASC";
        }
        if ($cia == '' && $asesor == '') {
            $sql = "SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, idnom AS nombre, cod_poliza, pdf, id_cia  
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, ena
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = ena.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'

                    UNION ALL

                SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, nombre, cod_poliza, pdf, id_cia  
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, enr
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = enr.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'

                    UNION ALL

                SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, nombre, cod_poliza, pdf, id_cia 
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, enp
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = enp.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta'
                    ORDER BY f_hastapoliza ASC";
        }
        if ($cia == '' && $asesor != '') {

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, idnom AS nombre, cod_poliza, pdf, id_cia  
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, ena
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = ena.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    codvend  IN " . $asesorIn . " 

                    UNION ALL

                    SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, nombre, cod_poliza, pdf, id_cia  
                        FROM 
                        poliza
                        INNER JOIN titular, dcia, dramo, enr
                        WHERE 
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        poliza.codvend = enr.cod AND
                        poliza.f_hastapoliza >= '$f_desde' AND
                        poliza.f_hastapoliza <= '$f_hasta' AND
                        codvend  IN " . $asesorIn . " 

                        UNION ALL

                    SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, nombre, cod_poliza, pdf, id_cia  
                        FROM 
                        poliza
                        INNER JOIN titular, dcia, dramo, enp
                        WHERE 
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        poliza.codvend = enp.cod AND
                        poliza.f_hastapoliza >= '$f_desde' AND
                        poliza.f_hastapoliza <= '$f_hasta' AND
                        codvend  IN " . $asesorIn . " 
                        ORDER BY f_hastapoliza ASC";
        }
        if ($asesor == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, idnom AS nombre, cod_poliza, pdf, id_cia  
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, ena
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = ena.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    nomcia IN " . $ciaIn . "
                    
                    UNION ALL

                SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, nombre, cod_poliza, pdf, id_cia  
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, enr
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = enr.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    nomcia IN " . $ciaIn . "

                    UNION ALL

                SELECT sumaasegurada, prima, f_hastapoliza, poliza.currency, nomcia, nombre_t, apellido_t, nramo, id_poliza, nombre, cod_poliza, pdf, id_cia  
                    FROM 
                    poliza
                    INNER JOIN titular, dcia, dramo, enp
                    WHERE 
                    poliza.id_titular = titular.id_titular AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.codvend = enp.cod AND
                    poliza.f_hastapoliza >= '$f_desde' AND
                    poliza.f_hastapoliza <= '$f_hasta' AND
                    nomcia IN " . $ciaIn . "
                    ORDER BY f_hastapoliza ASC";
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

    public function get_rep_com()
    {
        $sql = "SELECT * FROM rep_com 
				INNER JOIN dcia
				WHERE
				rep_com.id_cia = dcia.idcia
				ORDER BY f_hasta_rep ASC";

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

    public function get_titulat_by_polizaid($id_poliza)
    {
        $sql = "SELECT poliza.id_titular, nombre_t, apellido_t  FROM 
						poliza
						INNER JOIN titular
						WHERE 
						poliza.id_titular = titular.id_titular AND
						poliza.id_poliza = $id_poliza";
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

    public function get_distinc_c_rep_com()
    {
        $sql = "SELECT DISTINCT id_cia, nomcia FROM rep_com 
				INNER JOIN dcia
				WHERE 
				rep_com.id_cia = dcia.idcia
				ORDER BY id_cia ASC";
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

    public function get_poliza_total_by_num($id_cia)
    {
        $sql = "SELECT prima  FROM 
                    poliza
                  	INNER JOIN dcia
                  	WHERE 
                    poliza.id_cia = dcia.idcia AND
                  	poliza.id_cia = $id_cia";
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

    public function get_rep_comision_por_busqueda($f_desde_rep, $f_hasta_rep, $cia)
    {
        if ($cia == 'Seleccione Cía') {
            $sql = "SELECT id_rep_com, f_pago_gc, f_hasta_rep, nomcia FROM rep_com 
                    INNER JOIN dcia
                    WHERE 
                    rep_com.id_cia = dcia.idcia AND
                    f_pago_gc >= '$f_desde_rep' AND
                    f_pago_gc <= '$f_hasta_rep' ";
        } else {
            $sql = "SELECT id_rep_com, f_pago_gc, f_hasta_rep, nomcia FROM rep_com 
                    INNER JOIN dcia
                    WHERE 
                    rep_com.id_cia = dcia.idcia AND
                    f_pago_gc >= '$f_desde_rep' AND
                    f_pago_gc <= '$f_hasta_rep' AND
                    nomcia = '$cia' ";
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

    public function get_a_reporte_gc_h($id_gc_h)
    {


        $sql = "SELECT DISTINCT comision.cod_vend, idnom AS nombre, act FROM poliza 
                    INNER JOIN dcia, dramo, comision, gc_h_comision, gc_h, ena WHERE 
                    poliza.id_cia=dcia.idcia AND
                    poliza.id_cod_ramo=dramo.cod_ramo AND
                    poliza.id_poliza = comision.id_poliza AND 
                    gc_h_comision.id_comision=comision.id_comision AND
                    gc_h_comision.id_gc_h=gc_h.id_gc_h AND
                    comision.cod_vend = ena.cod AND
                    gc_h.id_gc_h = '$id_gc_h'
                    
                    UNION ALL
                    
                SELECT DISTINCT comision.cod_vend, nombre, act FROM poliza 
                    INNER JOIN dcia, dramo, comision, gc_h_comision, gc_h, enr WHERE 
                    poliza.id_cia=dcia.idcia AND
                    poliza.id_cod_ramo=dramo.cod_ramo AND
                    poliza.id_poliza = comision.id_poliza AND 
                    gc_h_comision.id_comision=comision.id_comision AND
                    gc_h_comision.id_gc_h=gc_h.id_gc_h AND
                    comision.cod_vend = enr.cod AND
                    gc_h.id_gc_h = '$id_gc_h'
                    ORDER BY nombre ASC ";
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

    public function get_reporte_gc_h($id_gc_h, $cod_vend)
    {


        $sql = "SELECT comision.id_poliza, sumaasegurada, prima, prima_com, comision, per_gc, f_desdepoliza, f_hastapoliza, nombre_t, apellido_t, currency, cod_poliza, f_pago_prima, nomcia, nramo, f_hasta_rep
                    FROM poliza 
                    INNER JOIN dcia, dramo, comision, gc_h_comision, gc_h, titular, rep_com WHERE 
                    poliza.id_cia=dcia.idcia AND
                    poliza.id_cod_ramo=dramo.cod_ramo AND
                    poliza.id_poliza = comision.id_poliza AND 
                    gc_h_comision.id_comision=comision.id_comision AND
                    gc_h_comision.id_gc_h=gc_h.id_gc_h AND
                    poliza.id_titular=titular.id_titular AND
                    rep_com.id_rep_com=comision.id_rep_com AND
                    gc_h.id_gc_h = '$id_gc_h' AND
                    cod_vend = '$cod_vend' 
                    ORDER BY poliza.cod_poliza ASC";
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

    public function get_id_cliente($ci)
    {
        $sql = "SELECT id_titular, nombre_t, apellido_t, ci  FROM 
	                    titular
	                  	WHERE 
	                  	ci = $ci ";
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

    public function get_last_element($tabla, $campo)
    {
        $sql = "SELECT $campo FROM $tabla ORDER BY $campo DESC";
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

    public function get_comision($id_rep_com)
    {
        $sql = "SELECT comision.id_poliza, prima_com, comision, nombre_t, apellido_t, poliza.id_titular, f_pago_prima, cod_vend, num_poliza
                FROM 
                comision
                INNER JOIN
                poliza, titular
                WHERE 
                comision.id_poliza = poliza.id_poliza AND
                poliza.id_titular = titular.id_titular AND
                id_rep_com = $id_rep_com
                ORDER BY id_comision ASC";
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

    public function obtenPoliza_id($id)
    {
        $sql = "SELECT f_emi, f_desdepoliza, f_hastapoliza, id_cod_ramo, id_cia, 
        poliza.id_titular, id_tomador, codvend, idnom AS nombre, 
        nombre_t, apellido_t, id_poliza, poliza.cod_poliza  
                        FROM 
                        poliza
                        INNER JOIN titular, dramo, dcia, ena
                        WHERE 
                        poliza.id_titular = titular.id_titular AND 
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = ena.cod AND
                        poliza.id_poliza = $id
                        
                        UNION ALL
                        
                        SELECT f_emi, f_desdepoliza, f_hastapoliza, id_cod_ramo, id_cia, 
                        poliza.id_titular, id_tomador, codvend, nombre, 
                        nombre_t, apellido_t, id_poliza, poliza.cod_poliza  
                        FROM 
                        poliza
                        INNER JOIN titular, dramo, dcia, enr
                        WHERE 
                        poliza.id_titular = titular.id_titular AND 
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.id_poliza = $id

                        UNION ALL

                        SELECT f_emi, f_desdepoliza, f_hastapoliza, id_cod_ramo, id_cia, 
                        poliza.id_titular, id_tomador, codvend, nombre, 
                        nombre_t, apellido_t, id_poliza, poliza.cod_poliza  
                        FROM 
                        poliza
                        INNER JOIN titular, dramo, dcia, enp
                        WHERE 
                        poliza.id_titular = titular.id_titular AND 
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.id_poliza = $id
                        ";
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

    public function get_poliza_pre_carga($id_poliza)
    {
        $sql = "SELECT *  FROM 
				  	poliza
					INNER JOIN drecibo, titular, dveh
					WHERE 
					poliza.id_poliza = drecibo.idrecibo AND
					poliza.id_titular = titular.id_titular AND
					poliza.id_poliza = dveh.idveh AND
				  	poliza.id_poliza = $id_poliza";
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

    public function get__last_poliza_by_id($cod_poliza)
    {
        $sql = "SELECT id_poliza FROM poliza WHERE cod_poliza = '$cod_poliza'
		      			ORDER BY f_poliza DESC";
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

    public function get_gc_by_filtro_distinct_a_carga($f_desde, $f_hasta, $cia, $asesor)
    {

        if ($cia != '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre, act FROM 
								comision
								INNER JOIN poliza, rep_com, dcia, ena
								WHERE 
								poliza.id_poliza = comision.id_poliza AND
								comision.id_rep_com = rep_com.id_rep_com AND
								poliza.id_cia=dcia.idcia AND
								poliza.codvend=ena.cod AND
								rep_com.f_pago_gc >= '$f_desde' AND
								rep_com.f_pago_gc <= '$f_hasta' AND
								nomcia IN " . $ciaIn . " AND
								cod_vend  IN " . $asesorIn . " AND
								poliza.id_titular != 0 AND
                            	not exists (select 1 from gc_h_comision where gc_h_comision.id_comision = comision.id_comision)
								ORDER BY nombre ASC";
        }
        if ($cia == '' && $asesor == '') {
            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre, act FROM 
							comision
							INNER JOIN poliza, rep_com, dcia, ena
							WHERE 
							poliza.id_poliza = comision.id_poliza AND
							comision.id_rep_com = rep_com.id_rep_com AND
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=ena.cod AND
							rep_com.f_pago_gc >= '$f_desde' AND
							rep_com.f_pago_gc <= '$f_hasta' AND
							nomcia LIKE '%$cia%' AND
							cod_vend  LIKE '%$asesor%' AND
							poliza.id_titular != 0 AND
                            not exists (select 1 from gc_h_comision where gc_h_comision.id_comision = comision.id_comision)
							ORDER BY nombre ASC";
        }
        if ($cia == '' && $asesor != '') {

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre FROM 
							comision
							INNER JOIN poliza, rep_com, dcia, ena
							WHERE 
							poliza.id_poliza = comision.id_poliza AND
							comision.id_rep_com = rep_com.id_rep_com AND
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=ena.cod AND
							rep_com.f_pago_gc >= '$f_desde' AND
							rep_com.f_pago_gc <= '$f_hasta' AND
							nomcia LIKE '%$cia%' AND
							cod_vend  IN " . $asesorIn . " AND
							poliza.id_titular != 0 AND
                            not exists (select 1 from gc_h_comision where gc_h_comision.id_comision = comision.id_comision)
							ORDER BY nombre ASC";
        }
        if ($asesor == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT cod_vend, idnom AS nombre FROM 
							comision
							INNER JOIN poliza, rep_com, dcia, ena
							WHERE 
							poliza.id_poliza = comision.id_poliza AND
							comision.id_rep_com = rep_com.id_rep_com AND
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=ena.cod AND
							rep_com.f_pago_gc >= '$f_desde' AND
							rep_com.f_pago_gc <= '$f_hasta' AND
							cod_vend LIKE '%$asesor%' AND
							nomcia  IN " . $ciaIn . " AND
							poliza.id_titular != 0 AND
                            not exists (select 1 from gc_h_comision where gc_h_comision.id_comision = comision.id_comision)
							ORDER BY nombre ASC";
        }
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            header('Location: b_gc.php?m=2');
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

    public function get_gc_by_filtro_by_a($f_desde, $f_hasta, $cia, $asesor)
    {
        if ($cia != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT poliza.cod_poliza, sumaasegurada, poliza.prima, prima_com, comision, per_gc, f_desdepoliza, f_hastapoliza, currency, poliza.id_titular, poliza.id_poliza, nombre_t, apellido_t, nomcia, f_pago_prima,f_pago_gc, f_hasta_rep, id_comision, nramo FROM comision 
							INNER JOIN titular, dcia, dramo, poliza, rep_com 
							WHERE 
							poliza.id_titular = titular.id_titular AND
							poliza.id_cia = dcia.idcia AND
							poliza.id_cod_ramo = dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND
							comision.id_rep_com = rep_com.id_rep_com AND 
							rep_com.f_pago_gc >= '$f_desde' AND
							rep_com.f_pago_gc <= '$f_hasta' AND
							comision.cod_vend = '$asesor' AND 
							nomcia IN " . $ciaIn . " AND
							poliza.id_titular != 0 AND
              not exists (select 1 from gc_h_comision where gc_h_comision.id_comision = comision.id_comision)
							ORDER BY f_pago_prima ASC";
        }

        if ($cia == '') {
            $sql = "SELECT poliza.cod_poliza, sumaasegurada, poliza.prima, prima_com, comision, per_gc, f_desdepoliza, f_hastapoliza, currency, poliza.id_titular, poliza.id_poliza, nombre_t, apellido_t, nomcia, f_pago_prima,f_pago_gc, f_hasta_rep, id_comision, nramo
							FROM comision 
							INNER JOIN titular, dcia, dramo, poliza, rep_com 
							WHERE 
							poliza.id_titular = titular.id_titular AND
							poliza.id_cia = dcia.idcia AND
							poliza.id_cod_ramo = dramo.cod_ramo AND
							poliza.id_poliza = comision.id_poliza AND
							comision.id_rep_com = rep_com.id_rep_com AND 
							rep_com.f_pago_gc >= '$f_desde' AND
							rep_com.f_pago_gc <= '$f_hasta' AND
							comision.cod_vend = '$asesor' AND 
							poliza.id_titular != 0 AND
              not exists (select 1 from gc_h_comision where gc_h_comision.id_comision = comision.id_comision)
							ORDER BY f_pago_prima ASC";
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

    public function seguimiento($id_poliza)
    {
        $sql = "SELECT * FROM seguimiento
				WHERE
				id_poliza = $id_poliza
				ORDER BY created_at DESC";

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

    public function renovarR($fmaxM, $fmaxY, $fminM, $fminY)
    {
        $sql = "SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, idnom AS nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, ena
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = ena.cod AND
                        MONTH(poliza.f_hastapoliza) >= '$fmaxM' AND
                        YEAR(poliza.f_hastapoliza) >= '$fmaxY' AND
                        MONTH(poliza.f_hastapoliza) <= '$fminM' AND
                        YEAR(poliza.f_hastapoliza) <= '$fminY' 
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enp
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enp.cod AND
                        MONTH(poliza.f_hastapoliza) >= '$fmaxM' AND
                        YEAR(poliza.f_hastapoliza) >= '$fmaxY' AND
                        MONTH(poliza.f_hastapoliza) <= '$fminM' AND
                        YEAR(poliza.f_hastapoliza) <= '$fminY' 
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enr
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enr.cod AND
                        MONTH(poliza.f_hastapoliza) >= '$fmaxM' AND
                        YEAR(poliza.f_hastapoliza) >= '$fmaxY' AND
                        MONTH(poliza.f_hastapoliza) <= '$fminM' AND
                        YEAR(poliza.f_hastapoliza) <= '$fminY' ";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function renovarRSeg($fmaxM, $fmaxY, $fminM, $fminY)
    {
        $sql = "SELECT DISTINCT(poliza.id_poliza), cod_poliza, id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, ena, seguimiento
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = ena.cod AND
                        poliza.id_poliza = seguimiento.id_poliza AND
                        MONTH(poliza.f_hastapoliza) >= '$fmaxM' AND
                        YEAR(poliza.f_hastapoliza) >= '$fmaxY' AND
                        MONTH(poliza.f_hastapoliza) <= '$fminM' AND
                        YEAR(poliza.f_hastapoliza) <= '$fminY' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT DISTINCT(poliza.id_poliza), cod_poliza, id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enp, seguimiento
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enp.cod AND
                        poliza.id_poliza = seguimiento.id_poliza AND
                        MONTH(poliza.f_hastapoliza) >= '$fmaxM' AND
                        YEAR(poliza.f_hastapoliza) >= '$fmaxY' AND
                        MONTH(poliza.f_hastapoliza) <= '$fminM' AND
                        YEAR(poliza.f_hastapoliza) <= '$fminY' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)
                    UNION
                SELECT DISTINCT(poliza.id_poliza), cod_poliza, id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enr, seguimiento
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enr.cod AND
                        poliza.id_poliza = seguimiento.id_poliza AND
                        MONTH(poliza.f_hastapoliza) >= '$fmaxM' AND
                        YEAR(poliza.f_hastapoliza) >= '$fmaxY' AND
                        MONTH(poliza.f_hastapoliza) <= '$fminM' AND
                        YEAR(poliza.f_hastapoliza) <= '$fminY' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old)";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function renovarRV($fmaxM, $fmaxY, $fminM, $fminY)
    {
        $sql = "SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, idnom AS nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, ena
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = ena.cod AND
                        MONTH(poliza.f_hastapoliza) >= '$fmaxM' AND
                        YEAR(poliza.f_hastapoliza) >= '$fmaxY' AND
                        MONTH(poliza.f_hastapoliza) <= '$fminM' AND
                        YEAR(poliza.f_hastapoliza) <= '$fminY' AND
                        exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old AND renovar.no_renov=0)
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enp
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enp.cod AND
                        MONTH(poliza.f_hastapoliza) >= '$fmaxM' AND
                        YEAR(poliza.f_hastapoliza) >= '$fmaxY' AND
                        MONTH(poliza.f_hastapoliza) <= '$fminM' AND
                        YEAR(poliza.f_hastapoliza) <= '$fminY' AND
                        exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old AND renovar.no_renov=0)
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        dcia, titular, enr
                        WHERE 
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enr.cod AND
                        MONTH(poliza.f_hastapoliza) >= '$fmaxM' AND
                        YEAR(poliza.f_hastapoliza) >= '$fmaxY' AND
                        MONTH(poliza.f_hastapoliza) <= '$fminM' AND
                        YEAR(poliza.f_hastapoliza) <= '$fminY' AND
                        exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza_old AND renovar.no_renov=0)";

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_no_renov($fmaxM, $fmaxY, $fminM, $fminY)
    {
        $sql = "SELECT  COUNT(*) FROM renovar
                        WHERE 
                        no_renov = 1 AND
                        MONTH(f_hasta_poliza_old) >= '$fmaxM' AND
                        YEAR(f_hasta_poliza_old) >= '$fmaxY' AND
                        MONTH(f_hasta_poliza_old) <= '$fminM' AND
                        YEAR(f_hasta_poliza_old) <= '$fminY'  ";

        $query = mysqli_query($this->con, $sql);

        $reg = 0;

        $i = 0;
        while ($fila = $query->fetch_assoc()) {
            $reg = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function renovarRS($month, $year)
    {
        $sql = "SELECT *  FROM 
                        renovar
                        WHERE 
                        MONTH(created_at) = '$month' AND
                        YEAR(created_at) = '$year' ";

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

    public function verRenov($id_poliza)
    {
        $sql = "SELECT id_poliza, id_poliza_old, no_renov  FROM 
                        renovar
                        WHERE 
                        id_poliza_old = $id_poliza ";

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

    public function verRenov1($id_poliza)
    {
        $sql = "SELECT id_poliza, id_poliza_old, no_renov, nombre_usuario, apellido_usuario, no_renov_n, created_at  
                        FROM 
                        renovar
                        INNER JOIN
                        usuarios, no_renov
                        WHERE 
                        renovar.id_no_renov = no_renov.id_no_renov AND
                        renovar.id_usuario = usuarios.id_usuario AND
                        id_poliza_old = $id_poliza ";

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

    public function get_fecha_max_prima_d($anio, $fpago, $cia, $asesor)
    {
        if ($cia != '' && $fpago != '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $fpagoIn = "('" . implode("','", $fpago) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT MAX(f_desdepoliza) FROM poliza
                                INNER JOIN titular, dcia, dramo, drecibo
                                WHERE 
                                poliza.id_poliza = drecibo.idrecibo AND
                                poliza.id_titular = titular.id_titular AND
                                poliza.id_cia = dcia.idcia AND
                                poliza.id_cod_ramo = dramo.cod_ramo AND
                                YEAR(poliza.f_desdepoliza) = $anio AND
								nomcia IN " . $ciaIn . " AND
								codvend IN " . $asesorIn . " AND
                                fpago  IN " . $fpagoIn . "
                                ORDER BY poliza.f_desdepoliza ASC ";
        } //1
        if ($cia == '' && $fpago == '' && $asesor == '') {
            $sql = "SELECT MAX(f_desdepoliza) FROM poliza
                        INNER JOIN titular, dcia, dramo, drecibo
                        WHERE 
                        poliza.id_poliza = drecibo.idrecibo AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) = $anio
                        ORDER BY poliza.f_desdepoliza ASC ";
        } //2
        if ($cia != '' && $fpago == '' && $asesor == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT MAX(f_desdepoliza) FROM poliza
                            INNER JOIN titular, dcia, dramo, drecibo
                            WHERE 
                            poliza.id_poliza = drecibo.idrecibo AND
                            poliza.id_titular = titular.id_titular AND
                            poliza.id_cia = dcia.idcia AND
                            poliza.id_cod_ramo = dramo.cod_ramo AND
                            YEAR(poliza.f_desdepoliza) = $anio AND 
                            nomcia IN " . $ciaIn . "
                            ORDER BY poliza.f_desdepoliza ASC ";
        } //3
        if ($cia == '' && $fpago != '' && $asesor == '') {

            // create sql part for IN condition by imploding comma after each id
            $fpagoIn = "('" . implode("','", $fpago) . "')";

            $sql = "SELECT MAX(f_desdepoliza) FROM poliza
                            INNER JOIN titular, dcia, dramo, drecibo
                            WHERE 
                            poliza.id_poliza = drecibo.idrecibo AND
                            poliza.id_titular = titular.id_titular AND
                            poliza.id_cia = dcia.idcia AND
                            poliza.id_cod_ramo = dramo.cod_ramo AND
                            YEAR(poliza.f_desdepoliza) = $anio AND 
                            fpago  IN " . $fpagoIn . "
                            ORDER BY poliza.f_desdepoliza ASC ";
        } //4
        if ($cia == '' && $fpago == '' && $asesor != '') {

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT MAX(f_desdepoliza) FROM poliza
                            INNER JOIN titular, dcia, dramo, drecibo
                            WHERE 
                            poliza.id_poliza = drecibo.idrecibo AND
                            poliza.id_titular = titular.id_titular AND
                            poliza.id_cia = dcia.idcia AND
                            poliza.id_cod_ramo = dramo.cod_ramo AND
                            YEAR(poliza.f_desdepoliza) = $anio AND 
                            codvend IN " . $asesorIn . "
                            ORDER BY poliza.f_desdepoliza ASC  ";
        } //5
        if ($cia != '' && $fpago != '' && $asesor == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $fpagoIn = "('" . implode("','", $fpago) . "')";

            $sql = "SELECT MAX(f_desdepoliza) FROM poliza
                                INNER JOIN titular, dcia, dramo, drecibo
                                WHERE 
                                poliza.id_poliza = drecibo.idrecibo AND
                                poliza.id_titular = titular.id_titular AND
                                poliza.id_cia = dcia.idcia AND
                                poliza.id_cod_ramo = dramo.cod_ramo AND
                                YEAR(poliza.f_desdepoliza) = $anio AND 
								nomcia IN " . $ciaIn . " AND
                                fpago  IN " . $fpagoIn . "
                                ORDER BY poliza.f_desdepoliza ASC ";
        } //6
        if ($cia == '' && $fpago != '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $fpagoIn = "('" . implode("','", $fpago) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT MAX(f_desdepoliza) FROM poliza
                                INNER JOIN titular, dcia, dramo, drecibo
                                WHERE 
                                poliza.id_poliza = drecibo.idrecibo AND
                                poliza.id_titular = titular.id_titular AND
                                poliza.id_cia = dcia.idcia AND
                                poliza.id_cod_ramo = dramo.cod_ramo AND
                                YEAR(poliza.f_desdepoliza) = $anio AND 
								codvend IN " . $asesorIn . " AND
                                t_cuenta  IN " . $fpagoIn . "
                                ORDER BY poliza.f_desdepoliza ASC ";
        } //7
        if ($cia != '' && $fpago == '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT MAX(f_desdepoliza) FROM poliza
                                INNER JOIN titular, dcia, dramo, drecibo
                                WHERE 
                                poliza.id_poliza = drecibo.idrecibo AND
                                poliza.id_titular = titular.id_titular AND
                                poliza.id_cia = dcia.idcia AND
                                poliza.id_cod_ramo = dramo.cod_ramo AND
                                YEAR(poliza.f_desdepoliza) = $anio AND 
								nomcia IN " . $ciaIn . " AND
                                codvend IN " . $asesorIn . "
                                ORDER BY poliza.f_desdepoliza ASC  ";
        } //8
        $query = mysqli_query($this->con, $sql);

        $reg = [];

        $i = 0;
        while ($fila = $query->fetch_array()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function get_poliza_total_by_filtro_detalle_p($fpago, $anio, $cia, $asesor, $mes)
    {
        if ($cia != '' && $fpago != '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $fpagoIn = "('" . implode("','", $fpago) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, poliza.prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, titular.nombre_t, titular.apellido_t, pdf, nomcia, nramo
                                FROM 
                                poliza
                                INNER JOIN titular, dcia, dramo, drecibo
                                WHERE 
                                poliza.id_poliza = drecibo.idrecibo AND
                                poliza.id_titular = titular.id_titular AND
                                poliza.id_cia = dcia.idcia AND
                                poliza.id_cod_ramo = dramo.cod_ramo AND
                                YEAR(poliza.f_desdepoliza) = $anio AND 
                                MONTH(poliza.f_desdepoliza) = $mes AND
								nomcia IN " . $ciaIn . " AND
								codvend IN " . $asesorIn . " AND
                                fpago  IN " . $fpagoIn . "
                                ORDER BY poliza.f_desdepoliza ASC ";
        } //1
        if ($cia == '' && $fpago == '' && $asesor == '') {
            $sql = "SELECT id_poliza, poliza.id_titular, poliza.prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, titular.nombre_t, titular.apellido_t, pdf, nomcia, nramo
                        FROM 
                        poliza
                        INNER JOIN titular, dcia, dramo, drecibo
                        WHERE 
                        poliza.id_poliza = drecibo.idrecibo AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_cod_ramo = dramo.cod_ramo AND
                        YEAR(poliza.f_desdepoliza) = $anio AND 
                        MONTH(poliza.f_desdepoliza) = $mes  
                        ORDER BY poliza.f_desdepoliza ASC ";
        } //2
        if ($cia != '' && $fpago == '' && $asesor == '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, poliza.prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, titular.nombre_t, titular.apellido_t, pdf, nomcia, nramo
                            FROM 
                            poliza
                            INNER JOIN titular, dcia, dramo, drecibo
                            WHERE 
                            poliza.id_poliza = drecibo.idrecibo AND
                            poliza.id_titular = titular.id_titular AND
                            poliza.id_cia = dcia.idcia AND
                            poliza.id_cod_ramo = dramo.cod_ramo AND
                            YEAR(poliza.f_desdepoliza) = $anio AND 
                            MONTH(poliza.f_desdepoliza) = $mes AND
                            nomcia IN " . $ciaIn . "
                            ORDER BY poliza.f_desdepoliza ASC ";
        } //3
        if ($cia == '' && $fpago != '' && $asesor == '') {

            // create sql part for IN condition by imploding comma after each id
            $fpagoIn = "('" . implode("','", $fpago) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, poliza.prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, titular.nombre_t, titular.apellido_t, pdf, nomcia, nramo
                            FROM 
                            poliza
                            INNER JOIN titular, dcia, dramo, drecibo
                            WHERE 
                            poliza.id_poliza = drecibo.idrecibo AND
                            poliza.id_titular = titular.id_titular AND
                            poliza.id_cia = dcia.idcia AND
                            poliza.id_cod_ramo = dramo.cod_ramo AND
                            YEAR(poliza.f_desdepoliza) = $anio AND 
                            MONTH(poliza.f_desdepoliza) = $mes AND
                            fpago  IN " . $fpagoIn . "
                            ORDER BY poliza.f_desdepoliza ASC ";
        } //4
        if ($cia == '' && $fpago == '' && $asesor != '') {

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, poliza.prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, titular.nombre_t, titular.apellido_t, pdf, nomcia, nramo
                            FROM 
                            poliza
                            INNER JOIN titular, dcia, dramo, drecibo
                            WHERE 
                            poliza.id_poliza = drecibo.idrecibo AND
                            poliza.id_titular = titular.id_titular AND
                            poliza.id_cia = dcia.idcia AND
                            poliza.id_cod_ramo = dramo.cod_ramo AND
                            YEAR(poliza.f_desdepoliza) = $anio AND 
                            MONTH(poliza.f_desdepoliza) = $mes AND
                            codvend IN " . $asesorIn . "
                            ORDER BY poliza.f_desdepoliza ASC  ";
        } //5
        if ($cia != '' && $fpago != '' && $asesor == '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $fpagoIn = "('" . implode("','", $fpago) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, poliza.prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, titular.nombre_t, titular.apellido_t, pdf, nomcia, nramo
                                FROM 
                                poliza
                                INNER JOIN titular, dcia, dramo, drecibo
                                WHERE 
                                poliza.id_poliza = drecibo.idrecibo AND
                                poliza.id_titular = titular.id_titular AND
                                poliza.id_cia = dcia.idcia AND
                                poliza.id_cod_ramo = dramo.cod_ramo AND
                                YEAR(poliza.f_desdepoliza) = $anio AND 
                                MONTH(poliza.f_desdepoliza) = $mes AND
								nomcia IN " . $ciaIn . " AND
                                fpago  IN " . $fpagoIn . "
                                ORDER BY poliza.f_desdepoliza ASC ";
        } //6
        if ($cia == '' && $fpago != '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $fpagoIn = "('" . implode("','", $fpago) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, poliza.prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, titular.nombre_t, titular.apellido_t, pdf, nomcia, nramo
                                FROM 
                                poliza
                                INNER JOIN titular, dcia, dramo, drecibo
                                WHERE 
                                poliza.id_poliza = drecibo.idrecibo AND
                                poliza.id_titular = titular.id_titular AND
                                poliza.id_cia = dcia.idcia AND
                                poliza.id_cod_ramo = dramo.cod_ramo AND
                                YEAR(poliza.f_desdepoliza) = $anio AND 
                                MONTH(poliza.f_desdepoliza) = $mes AND
								codvend IN " . $asesorIn . " AND
                                t_cuenta  IN " . $fpagoIn . "
                                ORDER BY poliza.f_desdepoliza ASC ";
        } //7
        if ($cia != '' && $fpago == '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT id_poliza, poliza.id_titular, poliza.prima, f_desdepoliza, f_hastapoliza, f_poliza, poliza.currency, poliza.cod_poliza, titular.nombre_t, titular.apellido_t, pdf, nomcia, nramo
                                FROM 
                                poliza
                                INNER JOIN titular, dcia, dramo, drecibo
                                WHERE 
                                poliza.id_poliza = drecibo.idrecibo AND
                                poliza.id_titular = titular.id_titular AND
                                poliza.id_cia = dcia.idcia AND
                                poliza.id_cod_ramo = dramo.cod_ramo AND
                                YEAR(poliza.f_desdepoliza) = $anio AND 
                                MONTH(poliza.f_desdepoliza) = $mes AND
								nomcia IN " . $ciaIn . " AND
                                codvend IN " . $asesorIn . "
                                ORDER BY poliza.f_desdepoliza ASC  ";
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

    public function get_prima_cob_d($id_poliza, $mes)
    {
        $sql = "SELECT SUM(prima_com) FROM 
                comision WHERE 
                id_poliza = $id_poliza AND 
                MONTH(f_pago_prima) = $mes";
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

    public function get_poliza_by_busq($busq, $asesor)
    {
        if ($asesor == '') {
            $sql = "SELECT * FROM
					poliza, drecibo, titular, dramo, dcia
					WHERE
					poliza.id_poliza = drecibo.idrecibo AND
					poliza.id_titular = titular.id_titular AND 
					poliza.id_cod_ramo = dramo.cod_ramo AND
					poliza.id_cia = dcia.idcia AND
					poliza.cod_poliza LIKE '%$busq%'
					
					UNION ALL
					
					SELECT * FROM
					poliza, drecibo, titular, dramo, dcia
					WHERE
					poliza.id_poliza = drecibo.idrecibo AND
					poliza.id_titular = titular.id_titular AND 
					poliza.id_cod_ramo = dramo.cod_ramo AND
					poliza.id_cia = dcia.idcia AND
					titular.ci LIKE '%$busq%'
					
					UNION ALL
					
					SELECT * FROM
					poliza, drecibo, titular, dramo, dcia
					WHERE
					poliza.id_poliza = drecibo.idrecibo AND
					poliza.id_titular = titular.id_titular AND 
					poliza.id_cod_ramo = dramo.cod_ramo AND
					poliza.id_cia = dcia.idcia AND
					titular.nombre_t LIKE '%$busq%'
					
					UNION ALL
					
					SELECT * FROM
					poliza, drecibo, titular, dramo, dcia
					WHERE
					poliza.id_poliza = drecibo.idrecibo AND
					poliza.id_titular = titular.id_titular AND 
					poliza.id_cod_ramo = dramo.cod_ramo AND
					poliza.id_cia = dcia.idcia AND
					titular.apellido_t LIKE '%$busq%'
					";
        } else {
            $sql = "SELECT * FROM
					poliza, drecibo, titular, dramo, dcia
					WHERE
					poliza.id_poliza = drecibo.idrecibo AND
					poliza.id_titular = titular.id_titular AND 
					poliza.id_cod_ramo = dramo.cod_ramo AND
					poliza.id_cia = dcia.idcia AND
					poliza.codvend = '$asesor' AND
					poliza.cod_poliza LIKE '%$busq%'
					
					UNION ALL
					
					SELECT * FROM
					poliza, drecibo, titular, dramo, dcia
					WHERE
					poliza.id_poliza = drecibo.idrecibo AND
					poliza.id_titular = titular.id_titular AND 
					poliza.id_cod_ramo = dramo.cod_ramo AND
					poliza.id_cia = dcia.idcia AND
					poliza.codvend = '$asesor' AND
					titular.ci LIKE '%$busq%'
					
					UNION ALL
					
					SELECT * FROM
					poliza, drecibo, titular, dramo, dcia
					WHERE
					poliza.id_poliza = drecibo.idrecibo AND
					poliza.id_titular = titular.id_titular AND 
					poliza.id_cod_ramo = dramo.cod_ramo AND
					poliza.id_cia = dcia.idcia AND
					poliza.codvend = '$asesor' AND
					titular.nombre_t LIKE '%$busq%'
					
					UNION ALL
					
					SELECT * FROM
					poliza, drecibo, titular, dramo, dcia
					WHERE
					poliza.id_poliza = drecibo.idrecibo AND
					poliza.id_titular = titular.id_titular AND 
					poliza.id_cod_ramo = dramo.cod_ramo AND
					poliza.id_cia = dcia.idcia AND
					poliza.codvend = '$asesor' AND
					titular.apellido_t LIKE '%$busq%'
					";
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

    public function get_gc_r_by_filtro_distinct_a_carga($f_desde, $f_hasta, $cia, $asesor)
    {

        if ($cia != '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT DISTINCT codvend FROM 
								poliza
								INNER JOIN dcia, enr
								WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.codvend=enr.cod AND
								poliza.f_hastapoliza >= '$f_desde' AND
								poliza.f_hastapoliza <= '$f_hasta' AND
								nomcia IN " . $ciaIn . " AND
								codvend  IN " . $asesorIn . " AND
                            	not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY `poliza`.`id_poliza` ASC";
        }
        if ($cia == '' && $asesor == '') {
            $sql = "SELECT DISTINCT codvend FROM 
							poliza
							INNER JOIN dcia, enr
							WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=enr.cod AND
							poliza.f_hastapoliza >= '$f_desde' AND
							poliza.f_hastapoliza <= '$f_hasta' AND
							not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY `poliza`.`id_poliza` ASC";
        }
        if ($cia == '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT DISTINCT codvend FROM 
							poliza
							INNER JOIN dcia, enr
							WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=enr.cod AND
							poliza.f_hastapoliza >= '$f_desde' AND
							poliza.f_hastapoliza <= '$f_hasta' AND
							nomcia LIKE '%$cia%' AND
							codvend  IN " . $asesorIn . " AND
                            not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY `poliza`.`id_poliza` ASC";
        }
        if ($asesor == '' && $cia != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT DISTINCT codvend FROM 
							poliza
							INNER JOIN dcia, enr
							WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=enr.cod AND
							poliza.f_hastapoliza >= '$f_desde' AND
							poliza.f_hastapoliza <= '$f_hasta' AND
							codvend LIKE '%$asesor%' AND
							nomcia  IN " . $ciaIn . " AND
                            not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY `poliza`.`id_poliza` ASC";
        }

        $query = mysqli_query($this->con, $sql);

        $reg = [];

        if (mysqli_num_rows($query) == 0) {
            //return 0;
            header("Location: b_gc_r.php?m=2");
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

    public function get_gc_r_by_filtro_by_a($f_desde, $f_hasta, $cia, $asesor)
    {
        if ($cia != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT poliza.cod_poliza, sumaasegurada, poliza.prima, f_desdepoliza, f_hastapoliza, poliza.id_titular, poliza.id_poliza, nombre_t, apellido_t, nomcia, nramo, monto FROM 
					poliza
							INNER JOIN titular, tipo_poliza, dcia, dramo, enr 
							WHERE 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND 
							poliza.id_cod_ramo = dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=enr.cod AND
							poliza.id_titular = titular.id_titular AND
							poliza.f_hastapoliza >= '$f_desde' AND
							poliza.f_hastapoliza <= '$f_hasta' AND
							poliza.codvend = '$asesor' AND 
							nomcia IN " . $ciaIn . " AND
							not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY poliza.cod_poliza ASC";
        }

        if ($cia == '') {
            $sql = "SELECT poliza.cod_poliza, sumaasegurada, poliza.prima, f_desdepoliza, f_hastapoliza, poliza.id_titular, poliza.id_poliza, nombre_t, apellido_t, nomcia, nramo, monto FROM 
					poliza
							INNER JOIN titular, tipo_poliza, dcia, dramo, enr 
							WHERE 
							poliza.id_tpoliza = tipo_poliza.id_t_poliza AND 
							poliza.id_cod_ramo = dramo.cod_ramo AND
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=enr.cod AND
							poliza.id_titular = titular.id_titular AND
							poliza.f_hastapoliza >= '$f_desde' AND
							poliza.f_hastapoliza <= '$f_hasta' AND
							poliza.codvend = '$asesor' AND 
							not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY poliza.cod_poliza ASC";
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

    public function get_gc_r_by_filtro_a_pago($f_desde, $f_hasta, $cia, $asesor)
    {

        if ($cia != '' && $asesor != '') {
            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT id_poliza FROM 
								poliza
								INNER JOIN dcia, enr
								WHERE 
								poliza.id_cia=dcia.idcia AND
								poliza.codvend=enr.cod AND
								poliza.f_hastapoliza >= '$f_desde' AND
								poliza.f_hastapoliza <= '$f_hasta' AND
								nomcia IN " . $ciaIn . " AND
								codvend  IN " . $asesorIn . " AND
                            	not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY `poliza`.`id_poliza` ASC";
        }
        if ($cia == '' && $asesor == '') {
            $sql = "SELECT id_poliza FROM 
							poliza
							INNER JOIN dcia, enr
							WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=enr.cod AND
							poliza.f_hastapoliza >= '$f_desde' AND
							poliza.f_hastapoliza <= '$f_hasta' AND
							not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY `poliza`.`id_poliza` ASC";
        }
        if ($cia == '' && $asesor != '') {

            // create sql part for IN condition by imploding comma after each id
            $asesorIn = "('" . implode("','", $asesor) . "')";

            $sql = "SELECT id_poliza FROM 
							poliza
							INNER JOIN dcia, enr
							WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=enr.cod AND
							poliza.f_hastapoliza >= '$f_desde' AND
							poliza.f_hastapoliza <= '$f_hasta' AND
							nomcia LIKE '%$cia%' AND
							codvend  IN " . $asesorIn . " AND
                            not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY `poliza`.`id_poliza` ASC";
        }
        if ($asesor == '' && $cia != '') {

            // create sql part for IN condition by imploding comma after each id
            $ciaIn = "('" . implode("','", $cia) . "')";

            $sql = "SELECT id_poliza FROM 
							poliza
							INNER JOIN dcia, enr
							WHERE 
							poliza.id_cia=dcia.idcia AND
							poliza.codvend=enr.cod AND
							poliza.f_hastapoliza >= '$f_desde' AND
							poliza.f_hastapoliza <= '$f_hasta' AND
							codvend LIKE '%$asesor%' AND
							nomcia  IN " . $ciaIn . " AND
                            not exists (select 1 from gc_h_r where gc_h_r.id_poliza = poliza.id_poliza)
							ORDER BY `poliza`.`id_poliza` ASC";
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

    public function get_gc_h_r()
	{
		$sql = "SELECT poliza.cod_poliza, poliza.id_poliza, enr.nombre, enr.monto, gc_h_r.created_at, id_gc_h_r
                FROM gc_h_r 
				INNER JOIN poliza, enr
				WHERE 
				gc_h_r.id_poliza = poliza.id_poliza AND
				enr.cod = poliza.codvend AND
                status_c = 0 
                ORDER BY gc_h_r.created_at DESC ";
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


    //------------------------------GET-------------------------------------
    public function obtenPoliza($cod_poliza)
    {

        $sql = "SELECT f_emi, f_desdepoliza, f_hastapoliza, id_cod_ramo, id_cia, tcobertura,
        poliza.id_titular, id_tomador, f_desderecibo, f_hastarecibo, codvend, 
       	ci, currency, idnom AS nombre, nombre_t, apellido_t, placa, tveh, marca, mveh, f_veh, 
        serial, cveh, catveh, id_poliza, t_cuenta, poliza.cod_poliza, drecibo.prima, f_poliza
                    FROM 
                    poliza
                    INNER JOIN drecibo, titular, tipo_poliza, dramo, dcia, ena, dveh
                    WHERE 
                    poliza.id_poliza = drecibo.idrecibo AND
                    poliza.id_titular = titular.id_titular AND 
                    poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = ena.cod AND
                    poliza.id_poliza = dveh.idveh AND
                    poliza.cod_poliza = '$cod_poliza'
                    
                    UNION ALL
                    
                    SELECT f_emi, f_desdepoliza, f_hastapoliza, id_cod_ramo, id_cia, tcobertura,
            poliza.id_titular, id_tomador, f_desderecibo, f_hastarecibo, codvend, 
            ci, poliza.currency, nombre, nombre_t, apellido_t, placa, tveh, marca, mveh, f_veh, 
            serial, cveh, catveh, id_poliza, t_cuenta, poliza.cod_poliza, drecibo.prima, f_poliza
                    FROM 
                    poliza
                    INNER JOIN drecibo, titular, tipo_poliza, dramo, dcia, enr, dveh
                    WHERE 
                    poliza.id_poliza = drecibo.idrecibo AND
                    poliza.id_titular = titular.id_titular AND 
                    poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enr.cod AND
                    poliza.id_poliza = dveh.idveh AND
                    poliza.cod_poliza = '$cod_poliza'
                    
                    UNION ALL
                    
                    SELECT f_emi, f_desdepoliza, f_hastapoliza, id_cod_ramo, id_cia, tcobertura,
            poliza.id_titular, id_tomador, f_desderecibo, f_hastarecibo, codvend, 
            ci, poliza.currency, nombre, nombre_t, apellido_t, placa, tveh, marca, mveh, f_veh, 
            serial, cveh, catveh, id_poliza, t_cuenta, poliza.cod_poliza, drecibo.prima, f_poliza
                    FROM 
                    poliza
                    INNER JOIN drecibo, titular, tipo_poliza, dramo, dcia, enp, dveh
                    WHERE 
                    poliza.id_poliza = drecibo.idrecibo AND
                    poliza.id_titular = titular.id_titular AND 
                    poliza.id_tpoliza = tipo_poliza.id_t_poliza AND
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enp.cod AND
                    poliza.id_poliza = dveh.idveh AND
                    poliza.cod_poliza = '$cod_poliza'
                    ORDER BY f_poliza DESC";
        $query = mysqli_query($this->con, $sql);

        while ($fila = $query->fetch_assoc()) {
            $datos[] = array_map('utf8_encode', $fila);
        }
        return $datos;

        mysqli_close($this->con);
    }

    public function obtenTarjeta($n_tarjeta)
    {

        $sql = "SELECT id_tarjeta, n_tarjeta, cvv, fechaV, banco, nombre_titular FROM tarjeta WHERE n_tarjeta LIKE '%$n_tarjeta%'  ORDER BY fechaV DESC";

        $query = mysqli_query($this->con, $sql);

        while ($fila = $query->fetch_assoc()) {
            $datos[] = array_map('utf8_encode', $fila);
        }
        return $datos;

        mysqli_close($this->con);
    }

    public function obtenPolizaTarjeta($id_tarjeta)
    {

        $sql = "SELECT poliza.cod_poliza FROM drecibo, poliza WHERE 
		drecibo.idrecibo = poliza.id_poliza AND
        id_tarjeta = $id_tarjeta";

        $query = mysqli_query($this->con, $sql);

        while ($fila = $query->fetch_assoc()) {
            $datos[] = array_map('utf8_encode', $fila);
        }
        return $datos;

        mysqli_close($this->con);
    }

    public function obtenTarjetaIndv($id_tarjeta)
    {

        $sql = "SELECT * FROM tarjeta WHERE 
        id_tarjeta = $id_tarjeta";

        $query = mysqli_query($this->con, $sql);

        while ($fila = $query->fetch_assoc()) {
            $datos[] = array_map('utf8_encode', $fila);
        }
        return $datos;

        mysqli_close($this->con);
    }

    public function obtenReporte($f_hasta, $idcia)
    {

        $sql = "SELECT * FROM rep_com 
					WHERE 
					f_hasta_rep = '$f_hasta' AND
						id_cia= '$idcia'";

        $query = mysqli_query($this->con, $sql);
        $ver = mysqli_fetch_row($query);

        $datos = array(
            'id_rep_com' => $ver[0],
            'f_hasta_rep' => $ver[1],
            'f_pago_gc' => $ver[2],
            'id_cia' => $ver[3],
            'primat_com' => $ver[4],
            'comt' => $ver[5]
        );
        return $datos;

        mysqli_close($this->con);
    }

    public function obtenSumaReporte($id_rep_com)
    {

        $sql = "SELECT SUM(prima_com), SUM(comision) FROM rep_com, comision
				WHERE 
				rep_com.id_rep_com=comision.id_rep_com AND
				comision.id_rep_com= '$id_rep_com'";

        $query = mysqli_query($this->con, $sql);
        $ver = mysqli_fetch_row($query);

        $datos = array(
            'SUM(prima_com)' => $ver[0],
            'SUM(comision)' => $ver[1]
        );
        return $datos;

        mysqli_close($this->con);
    }

    public function obtenPolizaE($id)
    {

        $sql = "SELECT f_emi, f_desdepoliza, f_hastapoliza, id_cia,
        poliza.id_titular, id_tomador, codvend, poliza.currency, idnom AS nombre, nombre_t, 
        apellido_t, id_poliza, poliza.cod_poliza, prima, dcia.nomcia  
                FROM 
                poliza
                INNER JOIN titular, dcia, ena
                WHERE 
                poliza.id_titular = titular.id_titular AND 
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = ena.cod AND
                poliza.cod_poliza LIKE '%$id%'

                UNION ALL

                SELECT  f_emi, f_desdepoliza, f_hastapoliza, id_cia,
                        poliza.id_titular, id_tomador, codvend, poliza.currency, 
                        nombre, nombre_t, apellido_t, id_poliza, poliza.cod_poliza, prima, dcia.nomcia  FROM 
                        poliza
                        INNER JOIN titular, dcia, enr
                        WHERE 
                        poliza.id_titular = titular.id_titular AND 
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enr.cod AND
                        poliza.cod_poliza LIKE '%$id%'

                UNION ALL

                SELECT  f_emi, f_desdepoliza, f_hastapoliza, id_cia,
                        poliza.id_titular, id_tomador, codvend, poliza.currency, nombre, nombre_t, 
                        apellido_t, id_poliza, poliza.cod_poliza, prima, dcia.nomcia  
                        FROM 
                        poliza
                        INNER JOIN titular, dcia, enp
                        WHERE 
                        poliza.id_titular = titular.id_titular AND 
                        poliza.id_cia = dcia.idcia AND
                        poliza.codvend = enp.cod AND
                        poliza.cod_poliza LIKE '%$id%'
                        ORDER BY f_hastapoliza DESC, nombre_t ASC";
        $query = mysqli_query($this->con, $sql);

        while ($fila = $query->fetch_assoc()) {
            $datos[] = array_map('utf8_encode', $fila);
        }
        return $datos;

        mysqli_close($this->con);
    }

    public function obetnComisiones($id)
    {

        $sql = "SELECT SUM(prima_com) FROM comision 
			INNER JOIN rep_com, poliza
			WHERE 
			comision.id_rep_com = rep_com.id_rep_com AND
			poliza.id_poliza = comision.id_poliza AND
			comision.id_poliza = $id";
        $query = mysqli_query($this->con, $sql);

        while ($fila = $query->fetch_assoc()) {
            $datos[] = array_map('utf8_encode', $fila);
        }
        return $datos;

        mysqli_close($this->con);
    }

    //------------------------------AGREGAR-------------------------------------
    public function agregarSeguimiento($datos)
    {

        $sql = "INSERT INTO seguimiento (id_poliza,comentario,id_usuario)
                VALUES ('$datos[0]',
                        '$datos[1]',
                        '$datos[2]')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarPoliza(
        $cod_poliza,
        $f_poliza,
        $f_emi,
        $tcobertura,
        $f_desdepoliza,
        $f_hastapoliza,
        $currency,
        $id_tpoliza,
        $sumaasegurada,
        $id_zproduccion,
        $codvend,
        $id_cod_ramo,
        $id_cia,
        $id_titular,
        $id_tomador,
        $asesor_ind,
        $t_cuenta,
        $id_usuario,
        $obs,
        $prima
    ) {


        $sql = "INSERT into poliza (cod_poliza,f_poliza, f_emi, tcobertura, f_desdepoliza,
										f_hastapoliza, currency, id_tpoliza, sumaasegurada, id_zproduccion, codvend,
										id_cod_ramo, id_cia, id_titular, id_tomador, per_gc, t_cuenta, id_usuario, obs_p, prima)
									values ('$cod_poliza',
											'$f_poliza',
											'$f_emi',
											'$tcobertura',
											'$f_desdepoliza',
											'$f_hastapoliza',
											'$currency',
											'$id_tpoliza',
											'$sumaasegurada',
											'$id_zproduccion',
											'$codvend',
											'$id_cod_ramo',
											'$id_cia',
											'$id_titular',
											'$id_tomador',
											'$asesor_ind',
											'$t_cuenta',
											'$id_usuario',
											'$obs',
                                            '$prima')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarRecibo(
        $cod_recibo,
        $f_desderecibo,
        $f_hastarecibo,
        $prima,
        $fpago,
        $ncuotas,
        $montocuotas,
        $idtom,
        $idtitu,
        $cod_poliza,
        $forma_pago,
        $id_tarjeta
    ) {


        $sql = "INSERT into drecibo (cod_recibo,f_desderecibo, f_hastarecibo, prima, fpago,
										ncuotas, montocuotas, idtom, idtitu, cod_poliza, forma_pago, id_tarjeta)
									values ('$cod_recibo',
											'$f_desderecibo',
											'$f_hastarecibo',
											'$prima',
											'$fpago',
											'$ncuotas',
											'$montocuotas',
											'$idtom',
											'$idtitu',
											'$cod_poliza',
											'$forma_pago',
											'$id_tarjeta')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarVehiculo(
        $placa,
        $tveh,
        $marca,
        $mveh,
        $f_veh,
        $serial,
        $cveh,
        $catveh,
        $cod_recibo
    ) {


        $sql = "INSERT into dveh (placa,tveh,marca,mveh,
									f_veh,serial,cveh,catveh,cod_recibo)
									values ('$placa',
											'$tveh',
											'$marca',
											'$mveh',
											'$f_veh',
											'$serial',
											'$cveh',
											'$catveh',
											'$cod_recibo')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarTarjeta($n_tarjeta, $cvv, $fechaV, $titular_tarjeta, $banco)
    {

        $sql = "INSERT into tarjeta (n_tarjeta, cvv, fechaV, nombre_titular, banco)
				values ('$n_tarjeta',
						'$cvv',
						'$fechaV',
						'$titular_tarjeta',
						'$banco')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarPrePoliza($datos)
    {

        $sql = "INSERT into poliza (cod_poliza,f_poliza, f_emi, tcobertura, f_desdepoliza,
										f_hastapoliza, currency, id_tpoliza, sumaasegurada, id_zproduccion, codvend,
										id_cod_ramo, id_cia, id_titular, id_tomador, per_gc, t_cuenta, id_usuario)
									values ('$datos[0]',
											'$datos[2]',
											'$datos[2]',
											'N/A',
											'$datos[2]',
											'$datos[5]',
											'1',
											'1',
											'0',
											'$datos[3]',
											'AP-1',
											'0',
											'$datos[1]',
											'0',
											'0',
											'0',
											'1',
											'$datos[4]')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarAsegurado($asegurado, $id_poliza, $ci)
    {

        $sql = "INSERT into titular_pre_poliza (asegurado,id_poliza, ci)
				values ('$asegurado',
						'$id_poliza',
						'$ci')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarPrePolizaE($datos)
    {

        $sql = "INSERT into poliza (cod_poliza,f_poliza, f_emi, tcobertura, f_desdepoliza,
										f_hastapoliza, currency, id_tpoliza, sumaasegurada, id_zproduccion, codvend,
										id_cod_ramo, id_cia, id_titular, id_tomador, per_gc, t_cuenta, id_usuario)
									values ('$datos[0]',
											'$datos[2]',
											'$datos[2]',
											'$datos[6]',
											'$datos[14]',
											'$datos[5]',
											'$datos[7]',
											'$datos[8]',
											'$datos[9]',
											'$datos[3]',
											'$datos[10]',
											'$datos[11]',
											'$datos[1]',
											'0',
											'0',
											'$datos[12]',
											'$datos[13]',
											'$datos[4]')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarRepCom($f_hasta_rep, $f_pago_gc, $id_cia, $prima_comt, $comt)
    {


        $sql = "INSERT into rep_com (f_hasta_rep,f_pago_gc,id_cia,primat_com,comt,pdf)
									values ('$f_hasta_rep',
											'$f_pago_gc',
											'$id_cia',
											'$prima_comt',
											'$comt',
                                            0)";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarCom(
        $id_rep_com,
        $num_poliza,
        $cod_vend,
        $f_pago_prima,
        $prima_com,
        $comision,
        $id_poliza
    ) {


        $sql = "INSERT into comision (id_rep_com,num_poliza,cod_vend,f_pago_prima,
									prima_com,comision,id_poliza)
									values ('$id_rep_com',
											'$num_poliza',
											'$cod_vend',
											'$f_pago_prima',
											'$prima_com',
											'$comision',
											'$id_poliza')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarCia($nombre_cia, $rif)
    {


        $sql = "INSERT into dcia (nomcia,preferencial,f_desde_pref,f_hasta_pref,rif)
		values ('$nombre_cia',
				'0',
				'0000-00-00',
				'0000-00-00',
				'$rif')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarCiaPref(
        $id_cia,
        $fdesdeP,
        $fhastaP,
        $codvend,
        $per_gc
    ) {


        $sql = "INSERT into cia_pref (id_cia,f_desde_pref,f_hasta_pref,cod_vend,
									per_gc_sum)
									values ('$id_cia',
											'$fdesdeP',
											'$fhastaP',
											'$codvend',
											'$per_gc')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarContactoCia($id_cia, $nombre, $cargo, $tel, $cel, $email)
    {


        $sql = "INSERT into contacto_cia (id_cia,nombre,cargo,tel,cel,email)
		values ('$id_cia',
				'$nombre',
				'$cargo',
				'$tel',
				'$cel',
				'$email')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarUsuario($nombre, $apellido, $ci, $zprod, $seudonimo, $clave, $id_permiso, $asesor)
    {


        $sql = "INSERT into usuarios (nombre_usuario,cedula_usuario,clave_usuario,id_permiso,apellido_usuario,seudonimo,z_produccion,cod_vend)
		values ('$nombre',
				'$ci',
				'$clave',
				'$id_permiso',
				'$apellido',
				'$seudonimo',
				'$zprod',
				'$asesor')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarEditP($id_poliza, $campos, $usuario)
    {


        $sql = "INSERT into poliza_ed (id_poliza,campos_ed,usuario)
		values ('$id_poliza',
				'$campos',
				'$usuario')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarGCh($fhoy, $desde, $hasta, $tPoliza)
    {


        $sql = "INSERT into gc_h (f_hoy_h,f_desde_h,f_hasta_h, tPoliza)
									values ('$fhoy',
											'$desde',
											'$hasta',
											$tPoliza)";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarGChComision($id_gc_h, $id_comision)
    {

        $sql = "INSERT into gc_h_comision (id_gc_h,id_comision)
			values ('$id_gc_h',
					'$id_comision')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarRenovar($id_poliza, $id_poliza_old, $f_hasta_poliza_old)
    {

        $sql = "INSERT into renovar (id_poliza,id_poliza_old,f_hasta_poliza_old)
			values ('$id_poliza',
					'$id_poliza_old',
                    '$f_hasta_poliza_old')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarNoRenov($datos)
    {
        $sql = "INSERT INTO renovar (id_poliza,id_poliza_old,f_hasta_poliza_old,no_renov,id_no_renov,id_usuario)
                VALUES ('$datos[0]',
                        '$datos[0]',
                        '$datos[3]',
                        '1',
                        '$datos[2]',
                        '$datos[1]')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarGChR($id_poliza)
    {

        $sql = "INSERT into gc_h_r (id_poliza)
			    values ('$id_poliza')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    //------------------------------EDITAR-------------------------------------
    public function editarCia($id_cia, $nombre_cia, $rif, $per_com)
    {


        $sql = "UPDATE dcia set nomcia='$nombre_cia',
								rif='$rif',
								per_com='$per_com'

					where idcia= '$id_cia'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function editarUsuario($id_usuario, $nombre, $apellido, $ci, $zprod, $seudonimo, $clave, $id_permiso, $asesor, $activo)
    {
        $sql = "UPDATE usuarios SET nombre_usuario='$nombre',
								 	cedula_usuario='$ci',
									clave_usuario='$clave',
									id_permiso='$id_permiso',
									apellido_usuario='$apellido',
									seudonimo='$seudonimo',
									z_produccion='$zprod',
									cod_vend='$asesor',
									activo='$activo'
					WHERE id_usuario= '$id_usuario'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function editarRepCom($id_rep_com, $f_rep_1, $f_pago_1, $primat_com, $comt)
    {


        $sql = "UPDATE rep_com set 	f_hasta_rep='$f_rep_1',
								 	f_pago_gc='$f_pago_1',
									primat_com='$primat_com',
									comt='$comt'

					where id_rep_com= '$id_rep_com'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function editarPoliza($id_poliza, $n_poliza, $fhoy, $t_cobertura, $fdesdeP, $fhastaP, $currency, $tipo_poliza, $sumaA, $z_produc, $codasesor, $ramo, $cia, $idtitular, $idtomador, $asesor_ind, $t_cuenta, $obs_p, $prima)
    {


        $sql = "UPDATE poliza set cod_poliza='$n_poliza',
								f_poliza='$fhoy',
								f_emi='$fhoy',
								tcobertura='$t_cobertura',
								f_desdepoliza='$fdesdeP',
								f_hastapoliza='$fhastaP',
								currency='$currency',
								id_tpoliza='$tipo_poliza',
								sumaasegurada='$sumaA',
								id_zproduccion='$z_produc',
								codvend='$codasesor',
								id_cod_ramo='$ramo',
								id_cia='$cia',
								id_titular='$idtitular',
								id_tomador='$idtomador',
								per_gc='$asesor_ind',
								t_cuenta='$t_cuenta',
								obs_p='$obs_p',
                                prima='$prima'

					where id_poliza= '$id_poliza'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function editarRecibo($id_poliza, $n_recibo, $fdesde_recibo, $fhasta_recibo, $prima, $f_pago, $n_cuotas, $monto_cuotas, $idtomador, $idtitular, $n_poliza, $forma_pago, $id_tarjeta)
    {

        if ($id_tarjeta == 'no') {
            $sql = "UPDATE drecibo set cod_recibo='$n_recibo',
								f_desderecibo='$fdesde_recibo',
								f_hastarecibo='$fhasta_recibo',
								prima='$prima',
								fpago='$f_pago',
								ncuotas='$n_cuotas',
								montocuotas='$monto_cuotas',
								idtom='$idtomador',
								idtitu='$idtitular',
								cod_poliza='$n_poliza',
								forma_pago='$forma_pago',
                                id_tarjeta=0

					where idrecibo= '$id_poliza'";
        } else {
            $sql = "UPDATE drecibo set cod_recibo='$n_recibo',
								f_desderecibo='$fdesde_recibo',
								f_hastarecibo='$fhasta_recibo',
								prima='$prima',
								fpago='$f_pago',
								ncuotas='$n_cuotas',
								montocuotas='$monto_cuotas',
								idtom='$idtomador',
								idtitu='$idtitular',
								cod_poliza='$n_poliza',
								forma_pago='$forma_pago',
								id_tarjeta='$id_tarjeta'

					where idrecibo= '$id_poliza'";
        }
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function editarVehiculo($id_poliza, $placa, $tipo, $marca, $modelo, $anio, $serial, $color, $categoria, $n_recibo)
    {


        $sql = "UPDATE dveh set placa='$placa',
								tveh='$tipo',
								marca='$marca',
								mveh='$modelo',
								f_veh='$anio',
								serial='$serial',
								cveh='$color',
								catveh='$categoria',
								cod_recibo='$n_recibo'

					where idveh= '$id_poliza'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function editarAsesorCom($id_poliza, $codasesor)
    {


        $sql = "UPDATE comision set cod_vend='$codasesor'

					where id_poliza= '$id_poliza'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function update_poliza_pdf($id_poliza)
    {
        $sql = "UPDATE poliza SET pdf = 1 WHERE id_poliza = $id_poliza;";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function update_reporte_pdf($id_reporte_com)
    {
        $sql = "UPDATE rep_com SET pdf = 1 WHERE id_rep_com = $id_reporte_com;";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function update_user_profile($id_usuario)
    {
        $sql = "UPDATE usuarios SET avatar = 1 WHERE id_usuario = $id_usuario;";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarCargaPago($datos)
    {
        $f_pago_gc_r = date("Y/m/d", strtotime($datos[4]));

        $sql = "UPDATE gc_h_r SET status_c = 1,
                                  n_transf = '$datos[2]',
                                  n_banco = '$datos[3]',
                                  f_pago_gc_r = '$f_pago_gc_r',
                                  monto_p = '$datos[5]',
                                  id_usuario = '$datos[1]'
                WHERE id_gc_h_r = '$datos[0]' ";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    //------------------------------ELIMINAR-------------------------------------
    public function eliminarPoliza($id)
    {
        $sql3 = "SELECT * FROM comision WHERE id_poliza = '$id'";
        $query = mysqli_query($this->con, $sql3);

        if (mysqli_num_rows($query) == 0) {
            $sql1 = "DELETE from drecibo where idrecibo='$id'";
            mysqli_query($this->con, $sql1);

            $sql2 = "DELETE from dveh where idveh='$id'";
            mysqli_query($this->con, $sql2);

            $sql = "DELETE from poliza where id_poliza='$id'";
            return mysqli_query($this->con, $sql);
        }

        mysqli_close($this->con);
    }

    public function eliminarCiaPref($id_cia, $f_desde, $f_hasta)
    {

        $sql = "DELETE FROM cia_pref 
				WHERE 
				id_cia = '$id_cia' AND
				f_desde_pref = '$f_desde' AND
				f_hasta_pref = '$f_hasta'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function eliminarUsuario($id)
    {

        $sql = "DELETE from usuarios where id_usuario='$id'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function eliminarAsesor($id, $a)
    {
        if ($a == 1) {
            $sql = "DELETE from ena where idena='$id'";
            return mysqli_query($this->con, $sql);

            mysqli_close($this->con);
        }
        if ($a == 2) {
            $sql = "DELETE from enp where id_enp='$id'";
            return mysqli_query($this->con, $sql);

            mysqli_close($this->con);
        }
        if ($a == 3) {
            $sql = "DELETE from enr where id_enr='$id'";
            return mysqli_query($this->con, $sql);

            mysqli_close($this->con);
        }
    }

    public function eliminarCiaContacto($id_cia)
    {

        $sql = "DELETE from contacto_cia where id_cia='$id_cia'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function eliminarRepCom($id)
    {
        $sql2 = "DELETE from comision where id_rep_com='$id'";
        mysqli_query($this->con, $sql2);

        $sql = "DELETE from rep_com where id_rep_com='$id'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function eliminarComision($id)
    {
        $sql = "DELETE from comision where id_comision='$id'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function eliminarReporteGC($id)
    {
        $sql1 = "DELETE from gc_h_comision where id_gc_h='$id'";
        mysqli_query($this->con, $sql1);

        $sql = "DELETE from gc_h where id_gc_h='$id'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }
}
