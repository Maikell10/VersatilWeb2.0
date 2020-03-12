<?php

require_once dirname(__DIR__) . '\Model\Poliza.php';

class Asesor extends Poliza
{
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

    public function get_prima_s_asesor_total($id)
    {
        $sql = "SELECT SUM(prima), COUNT(*)  FROM 
                poliza
                WHERE 
                codvend = '$id' ";
        $query = mysqli_query($this->con, $sql);

        return $query->fetch_row();

        mysqli_close($this->con);
    }

    public function get_prima_c_asesor_total($codvend)
    {
        $sql = "SELECT SUM(prima_com)  FROM comision
						WHERE 
						cod_vend = '$codvend'";
        $query = mysqli_query($this->con, $sql);

        return $query->fetch_row();

        mysqli_close($this->con);
    }

    public function get_ejecutivo_by_cod($cod)
    {
        $sql = "SELECT idena AS id_asesor, id, cod, idnom AS nombre,  act FROM ena WHERE cod='$cod'
                UNION
                SELECT id_enp AS id_asesor, id, cod, nombre, act FROM enp WHERE cod='$cod'
                UNION
                SELECT id_enr AS id_asesor, id, cod, nombre, act FROM enr WHERE cod='$cod'";
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

    public function get_asesor_total($codvend)
    {
        $sql = "SELECT SUM(prima), nopre1, nopre1_renov, gc_viajes, gc_viajes_renov,  COUNT(*)  FROM 
                poliza
                INNER JOIN dramo, dcia, ena
                WHERE 
                poliza.id_cod_ramo = dramo.cod_ramo AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = ena.cod AND
                ena.cod = '$codvend' ";
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

    public function get_referidor_total($codvend)
    {
        $sql = "SELECT SUM(prima), COUNT(*)  FROM 
                poliza
                INNER JOIN dramo, dcia, enr
                WHERE 
                poliza.id_cod_ramo = dramo.cod_ramo AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = enr.cod AND
                enr.cod = '$codvend' ";
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

    public function get_asesor_proyecto_total($codvend)
    {
        $sql = "SELECT SUM(prima), COUNT(*)  FROM 
                poliza
                INNER JOIN dramo, dcia, enp
                WHERE 
                poliza.id_cod_ramo = dramo.cod_ramo AND
                poliza.id_cia = dcia.idcia AND
                poliza.codvend = enp.cod AND
                enp.cod = '$codvend' ";
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

    public function get_prima_cobrada_asesor($codvend)
	{
		$sql = "SELECT SUM(prima_com)  FROM comision
						WHERE 
						cod_vend = '$codvend'";
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
