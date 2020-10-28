<?php
require_once 'Model/User.php';
$obj = new User();

$asesor = $obj->get_element_by_id('ena', 'id', $_POST['cedula']);

if ($asesor[0]['cod']) {
    $usuario = $obj->agregarUsuario($_POST['nombre'], $_POST['apellido'], $_POST['cedula'], 'PANAMA', $_POST['seudonimo'], $_POST['password'], 3, $asesor[0]['cod']);

    $from = "info-noreply@versatilseguros.com";
    $to = "maikell.ods10@gmail.com,gerenciageneralversatil@gmail.com";
    $subject = "Nuevo usuario registrado en Versatil";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Versatil Seguros <" . $from . ">";

    $mensaje = "<html>
    <body tyle='margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
    background-color: white;'>
    
            <div style='background-color: #f4f4f4'>
            <br><br><br>
                <div style='width: 90%;margin: 0 auto;background-color: white'>
                <div style='padding: 30px'>
                <img src='https://versatilseguros.com/Aplicacion/assets/img/logoBr.jpg' alt='logo-versatil' style='width:150px;float: right;vertical-align: middle;border-style: none'>
                <br><br><br>
                <center><h1 class='title' style='background-color: #4285F4;color: white;'>Nuevo usuario registrado en Versatil</h1></center>
            

                <p>
                <h3>Se registró un nuevo usuario Asesor en el sistema de versatil.</h3>

                <h3>Debe verificar y activar el usuario para que pueda ingresar al sistema.</h3>


                <h5>Enviado desde el sistema de Versatil Seguros.</h5>

                </p>

                

                <br><br>
                <hr style='box-sizing: content-box;
                            height: 0;
                            overflow: visible;'>

                <p>
                <span style='float: left;width: 50%'>
                    <a href='https://twitter.com/versatilseguros' style='width: 9%'><img src='https://img.icons8.com/nolan/64/twitter.png' style='vertical-align: middle;border-style: none'/>@versatilseguros</a>
                    <br>
                    <a href='https://www.facebook.com/Versatil-Seguros-1047377925309464' style='width: 9%'><img src='https://img.icons8.com/nolan/64/facebook.png' style='vertical-align: middle;border-style: none'/>Versatil Corretaje de Seguros</a>
                    <br>
                    <a href='https://www.instagram.com/versatilseguros' style='width: 9%'><img src='https://img.icons8.com/nolan/64/instagram-new.png' style='vertical-align: middle;border-style: none'/>@versatilseguros</a>
                </span>

                <span style='width: 50%;float: right;'>
                    <br>
                    <center><a href='https://www.versatilseguros.com'><h3>Versatil Seguros</h3></a></center>
                    <p style='text-align: right'>
                        <h4>Boulevard Costa del Este, Edificio Financial Park, Piso 8, Oficina 8-A, Ciudad de Panamá, Telf.: +5073876800-01</h4>
                    </p>
                </span>
                </p>

                <br><br><br>

                </div>
                <br><br><br><br><br>
            </div>
            <br><br><br>
        </div>
    </body>
    </html>";

    mail($to, $subject, $mensaje, $headers);


    header("Location: login.php?m=4");
} else {
    header("Location: login.php?m=5");
}
