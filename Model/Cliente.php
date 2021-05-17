<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . DS . 'Model' . DS . 'Asesor.php';

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

    public function get_cliente_asesor($cod)
    {
        $sql = "SELECT DISTINCT(titular.id_titular), r_social, ci, nombre_t, apellido_t
                FROM titular, poliza
                WHERE
                poliza.id_titular = titular.id_titular AND
                poliza.codvend = '$cod'
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
        $sql = "SELECT  id_poliza, f_hastapoliza, prima FROM poliza 
                    INNER JOIN titular WHERE 
                    poliza.id_titular = titular.id_titular AND 
                    titular.id_titular = '$id'";
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

    public function get_poliza_by_cliente($id)
    {
        $sql = "SELECT poliza.id_poliza, f_desdepoliza,f_hastapoliza, poliza.currency, cod_poliza, nramo, idnom AS nombre, nomcia, prima, pdf, id_cia, codvend
                    FROM 
                    poliza
                    INNER JOIN  dramo, dcia, ena, titular
                    WHERE 
                    poliza.id_titular = titular.id_titular AND 
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = ena.cod AND
                    titular.id_titular = '$id'
                    
                    UNION ALL

                SELECT poliza.id_poliza, f_desdepoliza,f_hastapoliza, poliza.currency, cod_poliza, nramo, nombre, nomcia, prima, pdf, id_cia, codvend
                    FROM 
                    poliza
                    INNER JOIN  dramo, dcia, enr, titular
                    WHERE 
                    poliza.id_titular = titular.id_titular AND 
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enr.cod AND
                    titular.id_titular = '$id'

                    UNION ALL

                SELECT poliza.id_poliza, f_desdepoliza,f_hastapoliza, poliza.currency, cod_poliza, nramo, nombre, nomcia, prima, pdf, id_cia, codvend
                    FROM 
                    poliza
                    INNER JOIN  dramo, dcia, enp, titular
                    WHERE 
                    poliza.id_titular = titular.id_titular AND 
                    poliza.id_cod_ramo = dramo.cod_ramo AND
                    poliza.id_cia = dcia.idcia AND
                    poliza.codvend = enp.cod AND
                    titular.id_titular = '$id'
                    ORDER BY id_poliza ASC";
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

    public function get_birthdays_no_date()
    {
        $sql = "SELECT * FROM `titular` 
                WHERE
                f_nac <= '1900-01-01' AND
                r_social = 'PN-'
                ORDER BY `titular`.`f_nac`  ASC ";

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

    public function get_birthdays_month($mes)
    {
        $fhoy = date("Y-m-").'01';

        $sql = "SELECT DISTINCT(titular.id_titular), titular.ci, titular.f_nac, titular.r_social, titular.nombre_t, titular.apellido_t, titular.email, poliza.codvend
                        FROM titular, poliza
                        WHERE
                        titular.id_titular = poliza.id_titular AND
                        MONTH(f_nac) = $mes AND
                        poliza.f_hastapoliza >= '$fhoy' AND 
                        f_nac > '1900-01-01' AND
                        r_social = 'PN-'
                        ORDER BY `titular`.`f_nac`  ASC ";

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

    public function get_birthdays_day($day, $mes)
    {
        $fhoy = date("Y-m-").'01';

        $sql = "SELECT COUNT(DISTINCT(titular.id_titular)) AS count FROM titular , poliza 
                WHERE
                titular.id_titular = poliza.id_titular AND
                DAY(f_nac) = $day AND
                MONTH(f_nac) = $mes AND
                poliza.f_hastapoliza >= '$fhoy' AND 
                f_nac > '1900-01-01' AND
                r_social = 'PN-'
                ORDER BY `titular`.`f_nac`  ASC ";

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

    public function get_correo($cond)
    {
        $sql = "SELECT email
                FROM usuarios 
                WHERE 
                email != '-' AND
                $cond = 1 ";
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

    public function get_ejecutivo_by_cod($cod_vend)
    {
        $sql = "SELECT idnom AS nombre, cod FROM ena WHERE cod = '$cod_vend'
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

    //------------------------------EDITAR-------------------------------------
    public function editarCliente($id_titular, $nombre, $apellido, $ci, $f_nac, $cel, $telf, $email, $direcc, $r_social)
    {
        $sql = "UPDATE titular set 	nombre_t='$nombre',
								 	apellido_t='$apellido',
									ci='$ci',
									cell='$cel',
									telf='$telf',
									f_nac='$f_nac',
									direcc='$direcc',
									email='$email',
                                    r_social='$r_social'

					where id_titular= '$id_titular'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    //------------------------------ELIMINAR-------------------------------------
    public function eliminarCliente($id_titular)
    {
        $sql = "DELETE from titular where id_titular='$id_titular'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }
}
