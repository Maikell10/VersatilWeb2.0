<?php

require_once dirname(__DIR__) . '\Model\Conection.php';

class Poliza extends Conection
{
    public function getPolizas()
    {
        $sql = 'SELECT poliza.id_poliza, poliza.cod_poliza, 
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
                poliza.codvend = ena.cod 

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
                poliza.codvend = enp.cod 

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
        //resto 3 meses
        $fmax = date("Y-m-d", strtotime($fhoy . "- 3 month"));

        $sql = "SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, idnom AS nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        drecibo, dcia, titular, ena
                        WHERE 
                        poliza.id_poliza = drecibo.idrecibo AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = ena.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza)
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        drecibo, dcia, titular, enp
                        WHERE 
                        poliza.id_poliza = drecibo.idrecibo AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enp.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza)
                    UNION
                SELECT id_poliza, poliza.cod_poliza, nomcia, f_desdepoliza, f_hastapoliza, prima, nombre_t, apellido_t, pdf, nombre, poliza.id_cia  FROM 
                        poliza
                        INNER JOIN
                        drecibo, dcia, titular, enr
                        WHERE 
                        poliza.id_poliza = drecibo.idrecibo AND
                        poliza.id_cia = dcia.idcia AND
                        poliza.id_titular = titular.id_titular AND
                        poliza.codvend = enr.cod AND
                        poliza.f_hastapoliza >= '$fmax' AND
                        poliza.f_hastapoliza <= '$fhoy' AND
                        not exists (select 1 from renovar where poliza.id_poliza = renovar.id_poliza)";

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

        $sql = "SELECT cod_poliza  FROM 
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
                cod, fechaV, tipo_poliza, nramo, nomcia, sumaasegurada, prima,
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
                cod, fechaV, tipo_poliza, nramo, nomcia, sumaasegurada, prima,
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
                cod, fechaV, tipo_poliza, nramo, nomcia, sumaasegurada, prima,
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
                        drecibo.prima, poliza.f_poliza, nombre_t, apellido_t,
                        idnom, pdf, nomcia
                FROM 
                poliza
                INNER JOIN drecibo, titular, dcia, ena
                WHERE 
                poliza.id_poliza = drecibo.idrecibo AND
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

    //------------------------------AGREGAR-------------------------------------
    public function agregarSeguimiento($datos)
    {

        $sql = "INSERT INTO seguimiento (id_poliza,comentario,id_usuario)
                VALUES ('$datos[0]',
                        '$datos[1]',
                        '$datos[2]')";
        return mysqli_query($this->con, $sql);
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
    }
}
