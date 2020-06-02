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

    public function get_element_desc($tabla, $campo)
    {
        $sql = "SELECT * FROM $tabla ORDER BY $campo DESC";
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
        $sql = "SELECT id_poliza, f_hastapoliza, prima  FROM 
                poliza
                WHERE 
                codvend = '$id' ";
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

    public function get_ultimo_asesor()
    {
        $sql = "SELECT * FROM ena order by idena DESC";

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

    public function get_ultimo_a_proyecto($id)
    {
        $sql = "SELECT * FROM enp WHERE id_proyecto = $id order by cod DESC";

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

    //------------------------------AGREGAR-------------------------------------
    public function agregarAsesor($datos)
    {
        $sql = "INSERT into ena (idnom,cod,id,banco,tipo_cuenta,
									num_cuenta,email,cel,obs,nopre1_renov,nopre1,gc_viajes,gc_viajes_renov,f_nac_a)
									values ('$datos[0]',
											'$datos[1]',
											'$datos[2]',
											'$datos[3]',
											'$datos[4]',
											'$datos[5]',
											'$datos[6]',
											'$datos[7]',
											'$datos[8]',
											'$datos[9]',
											'$datos[10]',
											'$datos[11]',
											'$datos[12]',
                                            '$datos[13]')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarProyecto($datos)
    {


        $sql = "INSERT into lider_enp (cod_proyecto,lider,pago,ref_cuenta)
									values ('$datos[0]',
											'$datos[1]',
											'$datos[2]',
											'$datos[3]')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarAsesorProyecto($datos)
    {


        $sql = "INSERT into enp (id_proyecto,cod,nombre,num_cuenta,banco,
									tipo_cuenta,email,id,cel,obs,currency,monto)
									values ('$datos[0]',
											'$datos[1]',
											'$datos[2]',
											'$datos[3]',
											'$datos[4]',
											'$datos[5]',
											'$datos[6]',
											'$datos[7]',
											'$datos[8]',
											'$datos[9]',
											'$datos[10]',
											'$datos[11]')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function agregarReferidor($datos)
    {


        $sql = "INSERT into enr (cod,nombre,num_cuenta,banco,
									tipo_cuenta,email,id,cel,obs,pago,ref_cuenta,currency,monto, f_pago)
									values ('$datos[0]',
											'$datos[1]',
											'$datos[2]',
											'$datos[3]',
											'$datos[4]',
											'$datos[5]',
											'$datos[6]',
											'$datos[7]',
											'$datos[8]',
											'$datos[9]',
											'$datos[10]',
											'$datos[11]',
											'$datos[12]',
											'$datos[13]')";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    //------------------------------EDITAR-------------------------------------
    public function editarAsesorA($id_asesor, $id, $nombre, $cel, $email, $banco, $tipo_cuenta, $num_cuenta, $obs, $act, $nopre1, $nopre1_renov, $gc_viajes, $gc_viajes_renov,$f_nac_a)
    {

        $sql = "UPDATE ena set 	id='$id',
								 	idnom='$nombre',
									cel='$cel',
								 	email='$email',
									banco='$banco',
									tipo_cuenta='$tipo_cuenta',
									num_cuenta='$num_cuenta',
									obs='$obs',
									act='$act',
									nopre1='$nopre1',
									nopre1_renov='$nopre1_renov',
									gc_viajes='$gc_viajes',
									gc_viajes_renov='$gc_viajes_renov',
                                    f_nac_a='$f_nac_a'

					where idena= '$id_asesor'";
        return mysqli_query($this->con, $sql);

        mysqli_close($this->con);
    }

    public function editarAsesor($id_asesor, $a, $id, $nombre, $cel, $email, $banco, $tipo_cuenta, $num_cuenta, $obs, $act, $pago, $f_pago, $monto,$f_nac_a)
    {
        if ($a == 1) {
            $sql = "UPDATE ena set 	id='$id',
								 	idnom='$nombre',
									cel='$cel',
								 	email='$email',
									banco='$banco',
									tipo_cuenta='$tipo_cuenta',
									num_cuenta='$num_cuenta',
									obs='$obs',
									act='$act',
                                    f_nac_a='$f_nac_a'

					where idena= '$id_asesor'";
            return mysqli_query($this->con, $sql);

            mysqli_close($this->con);
        }
        if ($a == 2) {
            $sql = "UPDATE enp set 	id='$id',
								 	nombre='$nombre',
									cel='$cel',
								 	email='$email',
									banco='$banco',
									tipo_cuenta='$tipo_cuenta',
									num_cuenta='$num_cuenta',
									obs='$obs',
									act='$act',
                                    monto='$monto',
                                    f_nac_a='$f_nac_a'

					where id_enp= '$id_asesor'";
            return mysqli_query($this->con, $sql);

            mysqli_close($this->con);
        }
        if ($a == 3) {
            $sql = "UPDATE enr set 	id='$id',
								 	nombre='$nombre',
									cel='$cel',
								 	email='$email',
									banco='$banco',
									tipo_cuenta='$tipo_cuenta',
									num_cuenta='$num_cuenta',
									obs='$obs',
									act='$act',
									pago='$pago',
									f_pago='$f_pago',
									monto='$monto',
                                    f_nac_a='$f_nac_a'

					where id_enr= '$id_asesor'";
            return mysqli_query($this->con, $sql);

            mysqli_close($this->con);
        }
    }
}
