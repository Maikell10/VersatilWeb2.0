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
}
