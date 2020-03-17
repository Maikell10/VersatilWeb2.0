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

    public function get_asesor_by_cod($tabla, $cod)
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

    //------------------------------GET-------------------------------------
    public function obtenTitular($id)
    {

        $sql = "SELECT nombre_t, apellido_t, ci FROM titular WHERE ci = '$id'";
        $query = mysqli_query($this->con, $sql);

        $ver = mysqli_fetch_row($query);

        $datos = array(
            'ci' => $ver[2],
            'apellido_t' => $ver[1],
            'nombre_t' => $ver[0]
        );
        return $datos;

        mysqli_close($this->con);
    }

    //------------------------------AGREGAR-------------------------------------
    public function agregarCliente($datos)
    {
        if ($datos[0] == "") {
            exit;
        }
        if ($datos[10] == "") {
            exit;
        }

        $f_nac = $datos[9];
        if ($f_nac == "") {
            $f_nac = '1900-01-01';
        } else {
            $f_nac = date("Y-m-d", strtotime($f_nac));
        }

        $sql = "INSERT into titular (r_social,ci,nombre_t, apellido_t, cell,
										telf, telf1, id_sexo, id_ecivil, f_nac, direcc,
										direcc1, email, email1, ocupacion, ingreso)
									values ('$datos[0]',
											'$datos[1]',
											'$datos[2]',
											'$datos[3]',
											'$datos[4]',
											'$datos[5]',
											'$datos[6]',
											'1',
											'1',
											'$f_nac',
											'$datos[10]',
											'$datos[11]',
											'$datos[12]',
											'$datos[13]',
											'$datos[14]',
											'$datos[15]')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }
}
