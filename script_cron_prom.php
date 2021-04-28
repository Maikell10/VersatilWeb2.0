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
    require_once './Controller/Poliza.php';

    $cartaNewData = $obj->getDataCarta('carta_new_data');

    $desde = date('Y') . "-" . date('m') . "-" . date('d');
    $hasta = date('Y') . "-" . (date('m') + 1) . "-" . date('d');

    $cia = '';
    $asesor = '';

    $totalsuma = 0;
    $totalprima = 0;
    $totalpoliza = 0;

    $distinct_a = $obj->get_poliza_total_by_filtro_renov_distinct_a($desde, $hasta, $cia);
    if ($distinct_a == 0) {
        exit();
    }

    $fhoy = date("Y-m-d");

    //$correos = [];
    $correos = '';
    $cantCorreo = ($distinct_a != 0) ? sizeof($distinct_a) : 0;

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $from = "info-noreply@versatilseguros.com";
    //$to = $correos[0];
    $subject = "Polizas Proximas a Vencer";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From:" . $from;


    for ($a = 0; $a < $cantCorreo; $a++) {
        if ($distinct_a[$a]['email'] != '-' && $distinct_a[$a]['act'] == 1) {
            $correos = $distinct_a[$a]['email'];
            //$correos = 'maikell.ods10@gmail.com';


            $message = "
        <html>
            <body >
                <div class='section'>
                    <div class='container'>
                        <div class='col-md-auto col-md-offset-2' id='tablaLoad1'>
                        <center><h2 class='title'>Polizas proximas a vencer a partir de hoy a dentro de un mes</h2></center>  
                        </div>";

            //for ($a = 0; $a < sizeof($distinct_a); $a++) {

            $poliza = $obj->get_poliza_total_by_filtro_renov_a($desde, $hasta, $cia, $distinct_a[$a]['codvend']);

            $nombre = $distinct_a[$a]['nombre'];

            // if ($distinct_a[$a]['act'] == 1 && $distinct_a[$a]['email'] != '-') {

            $message .= "<center>
                
                                <h3>" . $nombre . "</h3>
                                <table class='table table-striped ' border=1 cellspacing=0 cellpadding=2 style='font-family: Arial, Helvetica, sans-serif;'>
                                    <thead style='background-color: #4285F4;color: white; font-weight: bold;'>
                                        <tr>
                                            <th>N° Póliza</th>
                                            <th>Nombre Titular</th>
                                            <th>Cía</th>
                                            <th>Ramo</th>
                                            <th>F Desde Seguro</th>
                                            <th>F Hasta Seguro</th>
                                            <th>PDF</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>";


            for ($i = 0; $i < sizeof($poliza); $i++) {
                $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                $totalprima = $totalprima + $poliza[$i]['prima'];

                $newDesde = date("d/m/Y", strtotime($poliza[$i]['f_desdepoliza']));
                $newHasta = date('d/m/Y', strtotime($poliza[$i]['f_hastapoliza']));


                $message .= "   <tr>
                                        <td style='color: #2B9E34;font-weight: bold'>" . $poliza[$i]['cod_poliza'] . "</td>

                                        <td nowrap>" . ($poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t']) . "</td>

                                        <td nowrap>" . $poliza[$i]['nomcia'] . "</td>
                                        <td nowrap>" . $poliza[$i]['nramo'] . "</td>
                                        <td>" . $newDesde . "</td>
                                        <td>" . $newHasta . "</td>";



                $message .= "   </tr>";
            }

            $message .= "   <tr>
                                                        <td colspan='7' style='background-color: #F53333;color: white;font-weight: bold'>Total: <font size=4 color='aqua'>" . sizeof($poliza) . "</font></td>
                                                    </tr>";

            $totalpoliza = $totalpoliza + sizeof($poliza);

            $message .= " </tbody>
                                    <tfoot style='background-color: #4285F4;color: white; font-weight: bold;'>
                                        <tr>
                                            <th>N° Póliza</th>
                                            <th>Nombre Titular</th>
                                            <th>Cía</th>
                                            <th>Ramo</th>
                                            <th>F Desde Seguro</th>
                                            <th>F Hasta Seguro</th>
                                            <th>PDF</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </center>";

            //}
            //}

            $message .= " </div>
                </div>
            </body>
        
        </html>";

            mail($correos, $subject, $message, $headers);
        }
    }




    /*
    for ($i = 0; $i < sizeof($correos); $i++) {
        mail($correos[$i], $subject, $message, $headers);
    }*/

    echo "The email message was sent.";





    ?>





    <div style='background-color: #f4f4f4;'>
        <br><br><br>
        <div style='width: 90%;margin: 0 auto;background-color: white'>
            <div style='padding: 30px'>

                <center>
                    <div>
                        <div class='title' style='background-color: #0f4296;color: white;width: 90%;font-size: 2vw'>Estimado Asegurado: <br>" . $poliza_correo[0]['nombre_t'] . " " . $poliza_correo[0]['apellido_t'] . "<br> Póliza No.: " . $poliza_correo[0]['cod_poliza'] . "</div>

                        <img src='https://versatilseguros.com/Aplicacion/assets/img/header_carta.jpg' alt='firma-versatil' style='width: 90%;vertical-align: middle;border-style: none'>

                        <h4 style='width: 85%;font-size: 1.6vw; text-align: justify;margin-top: 13px;font-style: italic;color: #6c757d'><?= nl2br($cartaNewData[0]['body']); ?></h4>
                                                        
                        <img src='https://versatilseguros.com/Aplicacion/assets/img/carta_firma.jpg' alt='firma-versatil' style='width: 90%;vertical-align: middle;border-style: none; margin-top: 15px'>
                    </div>
                </center>

                <br>

                <h3 style='width: 90%;margin-left: 9%;font-size: 2vw'><?= $cartaNewData[0]['escribame']; ?></h3>

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

                    <center><a href='https://versatilseguros.com/Aplicacion/view/download.php?id_poliza=" . $poliza . "'>
                            <h3 style='width: 90%;font-size: 1.7vw'>Click aquí para ver su póliza pdf</h3>
                        </a></center>

                    <center><img src='https://versatilseguros.com/Aplicacion/assets/img/footerV.jpg' alt='firma-versatil' style='width: 90%;'></center>

                    </p>
                </center>

            </div>

            <br>


        </div>
        <br><br><br>
    </div>


</body>

</html>