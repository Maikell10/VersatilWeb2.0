<?php

require_once dirname(__DIR__) . '\Model\Poliza.php';

class Asesor extends Poliza
{
    public function get_prima_s_asesor_total($id)
    {
        $sql = "SELECT SUM(prima), COUNT(*)  FROM 
                poliza
                INNER JOIN drecibo
                WHERE 
                poliza.id_poliza = drecibo.idrecibo AND
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
}
