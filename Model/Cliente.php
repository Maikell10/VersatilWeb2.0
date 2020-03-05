<?php

require_once dirname(__DIR__) . '\Model\Asesor.php';

class Cliente extends Asesor
{
    public function get_cliente()
    {
        $sql = "SELECT r_social, ci, nombre_t, apellido_t, id_titular
                FROM titular 
                ORDER BY id_titular ASC";
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

    public function get_polizas_t_cliente($id)
    {
        $sql = "SELECT COUNT(*) FROM poliza 
                    INNER JOIN titular WHERE 
                    poliza.id_titular = titular.id_titular AND 
                    titular.id_titular = '$id'";
        $query = mysqli_query($this->con, $sql);

        $reg = $query->fetch_row();

        return $reg;

        mysqli_close($this->con);
    }

    public function get_asesor_by_cod($tabla,$cod)
    {
        $sql = "SELECT * FROM $tabla 
                WHERE cod = '$cod'";
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
}
