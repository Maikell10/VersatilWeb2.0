<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <link rel="stylesheet" href="assets/css/material-kit.css?v=2.0.1">
    <!-- Documentation extras -->
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="assets/assets-for-demo/demo.css" rel="stylesheet" />
    <link href="assets/assets-for-demo/vertical-nav.css" rel="stylesheet" />

    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>

</head>

<body>
    <?php
    ///usr/local/bin/
    require_once './Controller/Cliente.php';
    $obj1 = new Cliente();

    require_once './Controller/Poliza.php';

    $cartaNewData = $obj->getDataCarta('carta_new_data');

    $fhoy = date("Y-m-d");

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $from = "info-noreply@versatilseguros.com";
    $subject = "Feliz Cumpleaños le desea Versatil Seguros";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From:" . $from;

    $correos = [];

    $correo = $obj1->get_correo('cc');
    $cantCorreo = ($correo != 0) ? sizeof($correo) : 0;

    for ($i = 0; $i < $cantCorreo; $i++) {
        $correos[] = $correo[$i]['email'];
    }

    $titular = $obj->getTitular_cumpl();
    $cantTitular = ($titular == 0) ? 0 : sizeof($titular);
    
    if ($cantTitular != 0) {
        for ($i=0; $i < $cantTitular; $i++) { 
            $mensaje_c = $obj->getTitular_cumpl_mensaje($titular[$i]['id_titular']);
            if($mensaje_c != 0) {
                
                $correos[] = $titular[$i]['email'];
    
                /*if($mensaje_c[0]['copiaAsesor'] == 1) {
                    $asesor = $obj->getAsesor_prom_send($titular[$i]['id_titular']);
    
                    if ($asesor != 0) {
                        for ($a=0; $a < sizeof($asesor); $a++) { 
                            $correos[] = $asesor[$a]['email'];
                        }
                    }
                }*/
    
                $message = "
                <html>
                    <body style='margin: 0;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
                    font-size: 1rem;
                    font-weight: 400;
                    line-height: 1.5;
                    color: #212529;
                    text-align: left;
                    background-color: white;'>
    
                        <div style='background-color: #f4f4f4;'>
                        <br><br><br>
                            <div style='width: 90%;margin: 0 auto;background-color: white'>
                                <div style='padding: 30px'>
    
                                    <center>
                                        <div>
                                            <div class='title' style='background-color: #0f4296;color: white;width: 90%;font-size: 2vw'>Estimado Asegurado: <br>" . $titular[$i]['nombre_t'] . " " . $titular[$i]['apellido_t'] . "</div>
    
                                            <img src='https://versatilseguros.com/Aplicacion/assets/img/crm/" . $mensaje_c[0]['id_mensaje_c1'] . ".jpg' alt='tarjeta-promocion-versatil' style='width: 90%;vertical-align: middle;border-style: none'>
                                        </div>
                                    </center>
                                    
                                    <br>
                                    <hr style='box-sizing: content-box;
                                                height: 0;
                                                overflow: visible;width: 90%'>
    
                                    <center><p>
    
                                    <div style='background-color: #0f4296;color: white;width: 90%'>
                                        <br>
                                        <center><a href='https://www.versatilseguros.com'><h3 style='color:white;font-size: 2vw'>www.versatilseguros.com</h3></a></center>
                                        <center><h4 style='width: 90%;font-size: 2vw'>" . $cartaNewData[0]['direccion'] . "</h4></center>
                                        <br>
                                    </div>
    
                                    <br>
    
                                    <center><img src='https://versatilseguros.com/Aplicacion/assets/img/footerV.jpg' alt='firma-versatil' style='width: 90%;'></center>
                                    
                                    </p></center>
    
                                    </div>
                                    
                                    <br>
                            </div>
                            <br><br><br>
                        </div>
                    </body>
                </html>";
    
            } else {
    
                $correos[] = $titular[$i]['email'];
    
                $message = "
                <html>
                    <body style='margin: 0;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
                    font-size: 1rem;
                    font-weight: 400;
                    line-height: 1.5;
                    color: #212529;
                    text-align: left;
                    background-color: white;'>
    
                        <div style='background-color: #f4f4f4;'>
                        <br><br><br>
                            <div style='width: 90%;margin: 0 auto;background-color: white'>
                                <div style='padding: 30px'>
    
                                    <center>
                                        <div>
                                            <div class='title' style='background-color: #0f4296;color: white;width: 90%;font-size: 2vw'>Estimado Asegurado: <br>" . $titular[$i]['nombre_t'] . " " . $titular[$i]['apellido_t'] . "</div>
    
                                            <img src='https://versatilseguros.com/Aplicacion/assets/img/tarjeta_birthday.png' alt='tarjeta-promocion-versatil' style='width: 90%;vertical-align: middle;border-style: none'>
                                        </div>
                                    </center>
                                    
                                    <br>
                                    <hr style='box-sizing: content-box;
                                                height: 0;
                                                overflow: visible;width: 90%'>
    
                                    <center><p>
    
                                    <div style='background-color: #0f4296;color: white;width: 90%'>
                                        <br>
                                        <center><a href='https://www.versatilseguros.com'><h3 style='color:white;font-size: 2vw'>www.versatilseguros.com</h3></a></center>
                                        <center><h4 style='width: 90%;font-size: 2vw'>" . $cartaNewData[0]['direccion'] . "</h4></center>
                                        <br>
                                    </div>
    
                                    <br>
    
                                    <center><img src='https://versatilseguros.com/Aplicacion/assets/img/footerV.jpg' alt='firma-versatil' style='width: 90%;'></center>
                                    
                                    </p></center>
    
                                    </div>
                                    
                                    <br>
                            </div>
                            <br><br><br>
                        </div>
                    </body>
                </html>";
                
            }
        }
    
        for ($i=0; $i < sizeof($correos); $i++) { 
            mail($correos[$i], $subject, $message, $headers);
        }
    }
    

    //echo "The email message was sent.";

    $titular = $obj->getTitular_cumpl();
    $cantTitular = ($titular == 0) ? 0 : sizeof($titular) ;
    for ($i=0; $i < $cantTitular; $i++) { 
        $mensaje_c = $obj->getTitular_cumpl_mensaje($titular[$i]['id_titular']);
        if($mensaje_c != 0) {
    ?>

        <div style='background-color: #f4f4f4;'>
            <br><br><br>
            <div style='width: 90%;margin: 0 auto;background-color: white'>
                <div style='padding: 30px'>

                    <center>
                        <div>
                            <div class='title' style='background-color: #0f4296;color: white;width: 90%;font-size: 2vw'>Estimado Asegurado: <br><?= $titular[$i]['nombre_t'] . " " . $titular[$i]['apellido_t']; ?></div>

                            <img src='https://versatilseguros.com/Aplicacion/assets/img/crm/<?= $mensaje_c[0]['id_mensaje_c1']; ?>.jpg' alt='tarjeta-promocion-versatil' style='width: 90%;vertical-align: middle;border-style: none'>
                        </div>
                    </center>

                    <br>
                    <hr style='box-sizing: content-box;
                            height: 0;
                            overflow: visible;width: 90%'>

                    <center>
                        <p>

                        <div style='background-color: #0f4296;color: white;width: 90%'>
                            <br>
                            <center><a href='https://www.versatilseguros.com'>
                                    <h3 style='color:white;font-size: 2vw'>www.versatilseguros.com</h3>
                                </a></center>
                            <center>
                                <h4 style='width: 90%;font-size: 2vw'><?= $cartaNewData[0]['direccion']; ?></h4>
                            </center>
                            <br>
                        </div>

                        <br>

                        <center><img src='https://versatilseguros.com/Aplicacion/assets/img/footerV.jpg' alt='firma-versatil' style='width: 90%;'></center>

                        </p>
                    </center>

                </div>
                <br>
            </div>
            <br><br><br>
        </div>

    <?php
        } else {
            // No tiene programado mensaje personalizado
    ?>
        <div style='background-color: #f4f4f4;'>
            <br><br><br>
            <div style='width: 90%;margin: 0 auto;background-color: white'>
                <div style='padding: 30px'>

                    <center>
                        <div>
                            <div class='title' style='background-color: #0f4296;color: white;width: 90%;font-size: 2vw'>Estimado Asegurado: <br><?= $titular[$i]['nombre_t'] . " " . $titular[$i]['apellido_t']; ?></div>

                            <img src='https://versatilseguros.com/Aplicacion/assets/img/tarjeta_birthday.png' alt='tarjeta-promocion-versatil' style='width: 90%;vertical-align: middle;border-style: none'>
                        </div>
                    </center>

                    <br>
                    <hr style='box-sizing: content-box;
                            height: 0;
                            overflow: visible;width: 90%'>

                    <center>
                        <p>

                        <div style='background-color: #0f4296;color: white;width: 90%'>
                            <br>
                            <center><a href='https://www.versatilseguros.com'>
                                    <h3 style='color:white;font-size: 2vw'>www.versatilseguros.com</h3>
                                </a></center>
                            <center>
                                <h4 style='width: 90%;font-size: 2vw'><?= $cartaNewData[0]['direccion']; ?></h4>
                            </center>
                            <br>
                        </div>

                        <br>

                        <center><img src='https://versatilseguros.com/Aplicacion/assets/img/footerV.jpg' alt='firma-versatil' style='width: 90%;'></center>

                        </p>
                    </center>

                </div>
                <br>
            </div>
            <br><br><br>
        </div>
    <?php
        }
    }
    ?>


</body>
</html>