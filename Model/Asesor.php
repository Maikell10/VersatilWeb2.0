<?php
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . DS . 'Model' . DS . 'Poliza.php';

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
        $sql = "SELECT idena AS id_asesor, id, cod, idnom AS nombre,  act, nopre1 AS currency FROM ena WHERE cod='$cod'
                UNION
                SELECT id_enp AS id_asesor, id, cod, nombre, act, currency FROM enp WHERE cod='$cod'
                UNION
                SELECT id_enr AS id_asesor, id, cod, nombre, act, currency FROM enr WHERE cod='$cod' ";
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

    public function get_distinct_asesor_por_gc()
    {
        $sql = "SELECT DISTINCT(idnom) AS nombre, cod, act FROM gc_h_comision, comision, poliza, ena WHERE
                gc_h_comision.id_comision = comision.id_comision AND
                poliza.id_poliza = comision.id_poliza AND
                poliza.codvend = ena.cod 

                UNION
                
                SELECT DISTINCT(nombre), cod, act FROM gc_h_r, comision, poliza, enr WHERE
                gc_h_r.id_poliza = comision.id_poliza AND
                poliza.id_poliza = comision.id_poliza AND
                poliza.codvend = enr.cod AND
                status_c = 1

                UNION

                SELECT DISTINCT(nombre), cod, act FROM gc_h_p, comision, poliza, enp WHERE
                gc_h_p.id_poliza = comision.id_poliza AND
                poliza.id_poliza = comision.id_poliza AND
                poliza.codvend = enp.cod AND
                status_c = 1

                ORDER BY nombre ASC ";
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

    public function get_gc_pago_por_asesor($cod)
    {
        $sql = "SELECT idnom AS nombre, poliza.per_gc, comision, prima_com, comision.id_comision, poliza.sumaasegurada, poliza.prima, poliza.f_desdepoliza, poliza.f_hastapoliza, poliza.id_poliza, poliza.currency, poliza.id_titular, poliza.cod_poliza, f_pago_prima, f_pago_gc, nomcia, nombre_t, apellido_t, id_tpoliza
                FROM gc_h_comision, rep_com, comision, poliza, ena, dcia, titular WHERE
                gc_h_comision.id_comision = comision.id_comision AND
                rep_com.id_rep_com = comision.id_rep_com AND
                poliza.id_poliza = comision.id_poliza AND
                poliza.codvend = ena.cod AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_titular = titular.id_titular AND
                poliza.codvend = '$cod'
                ORDER BY id_poliza ASC  ";
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

        return $reg;

        mysqli_close($this->con);
    }

    public function get_gc_pago_por_asesor_by_busq($cod, $desde, $hasta)
    {
        $sql = "SELECT idnom AS nombre, poliza.per_gc, comision, prima_com, comision.id_comision, poliza.sumaasegurada, poliza.prima, poliza.f_desdepoliza, poliza.f_hastapoliza, poliza.id_poliza, poliza.currency, poliza.id_titular, poliza.cod_poliza, f_pago_prima, f_pago_gc, nomcia, nombre_t, apellido_t, id_tpoliza
                FROM gc_h_comision, rep_com, comision, poliza, ena, dcia, titular WHERE
                gc_h_comision.id_comision = comision.id_comision AND
                rep_com.id_rep_com = comision.id_rep_com AND
                poliza.id_poliza = comision.id_poliza AND
                poliza.codvend = ena.cod AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_titular = titular.id_titular AND
                f_pago_gc >= '$desde' AND
                f_pago_gc <= '$hasta' AND
                poliza.codvend = '$cod'
                ORDER BY id_poliza ASC  ";
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

        return $reg;

        mysqli_close($this->con);
    }

    public function get_gc_pago_por_proyecto($cod)
    {
        $sql = "SELECT (gc_h_p.id_poliza), nombre, poliza.per_gc, poliza.sumaasegurada, poliza.prima, poliza.f_desdepoliza, poliza.f_hastapoliza, poliza.currency, poliza.id_titular, poliza.cod_poliza, nomcia, nombre_t, apellido_t, enp.currency, enp.monto, monto_p, id_tpoliza, f_pago_prima, prima_com, comision
                FROM gc_h_p, poliza, enp, dcia, titular, comision WHERE
                comision.id_poliza = poliza.id_poliza AND
                gc_h_p.id_poliza = poliza.id_poliza AND
                poliza.codvend = enp.cod AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_titular = titular.id_titular AND
                poliza.codvend = '$cod' 
                UNION
                
                SELECT (gc_h_r.id_poliza), nombre, poliza.per_gc, poliza.sumaasegurada, poliza.prima, poliza.f_desdepoliza, poliza.f_hastapoliza, poliza.currency, poliza.id_titular, poliza.cod_poliza, nomcia, nombre_t, apellido_t, enr.currency, enr.monto, monto_p, id_tpoliza, f_pago_prima, prima_com, comision
                FROM gc_h_r, poliza, enr, dcia, titular, comision WHERE
                comision.id_poliza = poliza.id_poliza AND
                gc_h_r.id_poliza = poliza.id_poliza AND
                poliza.codvend = enr.cod AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_titular = titular.id_titular AND
                poliza.codvend = '$cod'
                ORDER BY id_poliza ASC ";
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

        return $reg;

        mysqli_close($this->con);
    }

    public function get_distinct_poliza_gc_pago_por_proyecto($cod)
    {
        $sql = "SELECT DISTINCT(gc_h_p.id_poliza)
                FROM gc_h_p, poliza, enp, dcia, titular, comision WHERE
                comision.id_poliza = poliza.id_poliza AND
                gc_h_p.id_poliza = poliza.id_poliza AND
                poliza.codvend = enp.cod AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_titular = titular.id_titular AND
                poliza.codvend = '$cod' 
                UNION
                
                SELECT DISTINCT(gc_h_r.id_poliza)
                FROM gc_h_r, poliza, enr, dcia, titular, comision WHERE
                comision.id_poliza = poliza.id_poliza AND
                gc_h_r.id_poliza = poliza.id_poliza AND
                poliza.codvend = enr.cod AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_titular = titular.id_titular AND
                poliza.codvend = '$cod'
                ORDER BY id_poliza ASC ";
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

        return $reg;

        mysqli_close($this->con);
    }

    public function get_gc_pago_por_proyecto_by_poliza($id_poliza)
    {
        $sql = "SELECT (gc_h_p.id_poliza), nombre, poliza.per_gc, poliza.sumaasegurada, poliza.prima, poliza.f_desdepoliza, poliza.f_hastapoliza, poliza.currency, poliza.id_titular, poliza.cod_poliza, nomcia, nombre_t, apellido_t, enp.currency, enp.monto, monto_p, id_tpoliza, f_pago_prima, prima_com, comision
                FROM gc_h_p, poliza, enp, dcia, titular, comision WHERE
                comision.id_poliza = poliza.id_poliza AND
                gc_h_p.id_poliza = poliza.id_poliza AND
                poliza.codvend = enp.cod AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_titular = titular.id_titular AND
                poliza.id_poliza = '$id_poliza' 
                UNION
                
                SELECT (gc_h_r.id_poliza), nombre, poliza.per_gc, poliza.sumaasegurada, poliza.prima, poliza.f_desdepoliza, poliza.f_hastapoliza, poliza.currency, poliza.id_titular, poliza.cod_poliza, nomcia, nombre_t, apellido_t, enr.currency, enr.monto, monto_p, id_tpoliza, f_pago_prima, prima_com, comision
                FROM gc_h_r, poliza, enr, dcia, titular, comision WHERE
                comision.id_poliza = poliza.id_poliza AND
                gc_h_r.id_poliza = poliza.id_poliza AND
                poliza.codvend = enr.cod AND
                poliza.id_cia = dcia.idcia AND
                poliza.id_titular = titular.id_titular AND
                poliza.id_poliza = '$id_poliza'
                ORDER BY f_pago_prima DESC ";
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

        return $reg;

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
									tipo_cuenta,email,id,cel,obs,currency,monto,act,f_nac_a)
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
                                            '0',
                                            '1970-01-01')";
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
