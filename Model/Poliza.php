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
                poliza.codvend = enr.cod 

                ORDER BY id_poliza ASC';

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
}
