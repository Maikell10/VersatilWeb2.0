<?php 
session_start();
if(isset($_SESSION['seudonimo'])) {

  }
    else {
        header("Location: login.php");
        exit();
      }

    require_once "../class/clases.php";

    $fhoy=date("Y-m-d");
    $nuevafecha = strtotime ( '+1 year' , strtotime ( $fhoy ) ) ;
    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );

    

    $f_hasta = date("Y-m-d", strtotime($_POST['f_desde_se']));
    $f_hasta_n = date("Y-m-d", strtotime($_POST['f_hasta_se']));


    $ob100= new Trabajo();
    $usuario = $ob100->get_element_by_id('usuarios','seudonimo',$_SESSION['seudonimo']);

    $ob1= new Trabajo();
    $poliza = $ob1->get_poliza_pre_carga($_POST['idpolizaE']);
    $nombre_t= $poliza[0]['nombre_t'].' - '.$poliza[0]['apellido_t'];
    
    if ($poliza[0]['id_cod_ramo'] == 2 || $poliza[0]['id_cod_ramo'] == 25) {
        $obj5= new Trabajo();
        $veh = $obj5->agregarVehiculo($poliza[0]['placa'],$poliza[0]['tveh'],$poliza[0]['marca'],$poliza[0]['mveh'],$poliza[0]['f_veh'],$poliza[0]['serial'],$poliza[0]['cveh'],$poliza[0]['catveh'],$_POST['num_polizaE']);
    } else {
        $obj5= new Trabajo();
        $veh = $obj5->agregarVehiculo('-','-','-','-','-','-','-','-',$_POST['num_polizaE']);
    }
    

    $obj6= new Trabajo();
    $recibo = $obj6->agregarRecibo($_POST['num_polizaE'],$f_hasta,$f_hasta_n,$poliza[0]['prima'],
    $poliza[0]['fpago'],$poliza[0]['ncuotas'],$poliza[0]['montocuotas'],0,0,$_POST['num_polizaE'],$poliza[0]['forma_pago'],$poliza[0]['id_tarjeta']);

    
    $z_produc='';
    if (utf8_encode($usuario[0]['z_produccion'])=='PANAMÁ') {
        $z_produc=1;
    }
    if (utf8_encode($usuario[0]['z_produccion'])=='CARACAS') {
        $z_produc=2;
    }
    
    
    $obj= new Trabajo();
    $fhoy=date("Y-m-d");
    $datos=array(
        $_POST['num_polizaE'],
        $poliza[0]['id_cia'],
        $fhoy,
        $z_produc,
        $usuario[0]['id_usuario'],
        $f_hasta_n,
        $poliza[0]['tcobertura'],
        $poliza[0]['currency'],
        2,
        $poliza[0]['sumaasegurada'],
        $poliza[0]['codvend'],
        $poliza[0]['id_cod_ramo'],
        0,
        $poliza[0]['t_cuenta'],
        $f_hasta);

    echo $obj->agregarPrePolizaE($datos);
    
    $obj3= new Trabajo();
    $ultimo_id_p = $obj3->get_last_element('poliza','id_poliza');
    $u_id_p=$ultimo_id_p[0]['id_poliza'];

    $obj4= new Trabajo();
    $asegurado = $obj4->agregarAsegurado($nombre_t,$u_id_p,$poliza[0]['ci']);

 ?>