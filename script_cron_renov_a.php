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

                                        if ($poliza[$i]['pdf'] == 1) {
                                            $message .= "<td class='text-center'><a href='https://versatilseguros.com/Aplicacion/view/download.php?id_poliza=" . $poliza[$i]['id_poliza'] . "' class='btn btn-white btn-rounded btn-sm' target='_blank'><img src='https://versatilseguros.com/Aplicacion/assets/img/pdf-logo.png' width='25' id='pdf'></a></td>";
                                        } else {
                                            if ($poliza[$i]['nramo'] == 'Vida') {
                                                $vRenov = $obj->verRenov3($poliza[$i]['id_poliza']);
                                                if ($vRenov != 0) {
                                                    if ($vRenov[0]['pdf'] != 0) {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']);
                                                        $message .= "<td class='text-center'><a href='https://versatilseguros.com/Aplicacion/view/download.php?id_poliza=" . $poliza_pdf_vida[0]['id_poliza'] . "' class='btn btn-white btn-rounded btn-sm' target='_blank'><img src='https://versatilseguros.com/Aplicacion/assets/img/pdf-logo.png' width='25' id='pdf'></a></td>";
                                                    } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza[$i]['id_cia'], $poliza[$i]['f_hastapoliza']);
                                                        if ($poliza_pdf_vida[0]['pdf'] == 1) {
                                                            $message .= "<td class='text-center'><a href='https://versatilseguros.com/Aplicacion/view/download.php?id_poliza=" . $poliza_pdf_vida[0]['id_poliza'] . "' class='btn btn-white btn-rounded btn-sm' target='_blank'><img src='https://versatilseguros.com/Aplicacion/assets/img/pdf-logo.png' width='25' id='pdf'></a></td>";
                                                        } else {
                                                            $message .= "<td></td>";
                                                        }
                                                    }
                                                } else {
                                                    $poliza_pdf_vida = $obj->get_pdf_vida($poliza[$i]['cod_poliza'], $poliza[$i]['id_cia'], $poliza[$i]['f_hastapoliza']);
                                                    if ($poliza_pdf_vida[0]['pdf'] == 1) {
                                                        $message .= "<td class='text-center'><a href='https://versatilseguros.com/Aplicacion/view/download.php?id_poliza=" . $poliza_pdf_vida[0]['id_poliza'] . "' class='btn btn-white btn-rounded btn-sm' target='_blank'><img src='https://versatilseguros.com/Aplicacion/assets/img/pdf-logo.png' width='25' id='pdf'></a></td>";
                                                    } else {
                                                        $message .= "<td></td>";
                                                    }
                                                }
                                            } else {
                                                $message .= "<td></td>";
                                            }
                                        }

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





    <div class='section'>
        <div class='container'>
            <div class='col-md-auto col-md-offset-2' id='tablaLoad1'>
                <center>
                    <h2 class='title'>Polizas proximas a vencer a partir de hoy a dentro de un mes</h2>
                </center>
            </div>

            <center>

            <?php for ($a = 0; $a < sizeof($distinct_a); $a++) {
                if ($distinct_a[$a]['act'] == 1 && $distinct_a[$a]['email'] != '-') {
                    $nombre = $distinct_a[$a]['nombre'];
            ?>
                <h3><?= $nombre; ?></h3>
                <table class='table table-striped' border=1 cellspacing=0 cellpadding=2 style="font-family: Arial, Helvetica, sans-serif;">
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

                    <tbody>


                        <?php

                            $poliza = $obj->get_poliza_total_by_filtro_renov_a($desde, $hasta, $cia, $distinct_a[$a]['codvend']);
                        ?>

                                <?php
                                for ($i = 0; $i < sizeof($poliza); $i++) {
                                    $totalsuma = $totalsuma + $poliza[$i]['sumaasegurada'];
                                    $totalprima = $totalprima + $poliza[$i]['prima'];

                                    $newDesde = date("d/m/Y", strtotime($poliza[$i]['f_desdepoliza']));
                                    $newHasta = date('d/m/Y', strtotime($poliza[$i]['f_hastapoliza']));

                                ?>
                                    <tr>
                                        <td style='color: #2B9E34;font-weight: bold'><?php echo $poliza[$i]['cod_poliza']; ?></td>

                                        <td nowrap><?= ($poliza[$i]['nombre_t'] . " " . $poliza[$i]['apellido_t']); ?></td>
                                        <td nowrap><?= ($poliza[$i]['nomcia']); ?></td>
                                        <td nowrap><?= ($poliza[$i]['nramo']); ?></td>

                                        <td><?php echo $newDesde; ?></td>
                                        <td><?php echo $newHasta; ?></td>


                                        <?php if ($poliza[$i]['pdf'] == 1) { ?>
                                            <td class="text-center"><a href="./view/download.php?id_poliza=<?= $poliza[$i]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src='https://versatilseguros.com/Aplicacion/assets/img/pdf-logo.png' width='25' id='pdf'></a></td>
                                            <?php } else {
                                            if ($poliza[$i]['nramo'] == 'Vida') {
                                                $vRenov = $obj->verRenov3($poliza[$i]['id_poliza']);
                                                if ($vRenov != 0) {
                                                    if ($vRenov[0]['pdf'] != 0) {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                        <td class="text-center"><a href="./view/download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src='https://versatilseguros.com/Aplicacion/assets/img/pdf-logo.png' width='25' id='pdf'></a></td>
                                                        <?php } else {
                                                        $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza[$i]['id_cia'], $poliza[$i]['f_hastapoliza']);
                                                        if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                            <td class="text-center"><a href="./view/download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src='https://versatilseguros.com/Aplicacion/assets/img/pdf-logo.png' width='25' id='pdf'></a></td>
                                                        <?php } else { ?>
                                                            <td></td>
                                                        <?php }
                                                    }
                                                } else {
                                                    $poliza_pdf_vida = $obj->get_pdf_vida($poliza[$i]['cod_poliza'], $poliza[$i]['id_cia'], $poliza[$i]['f_hastapoliza']);
                                                    if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                        <td class="text-center"><a href="./view/download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src='https://versatilseguros.com/Aplicacion/assets/img/pdf-logo.png' width='25' id='pdf'></a></td>
                                                    <?php } else { ?>
                                                        <td></td>
                                                <?php }
                                                }
                                            } else { ?>
                                                <td></td>
                                            <?php } ?>
                                        <?php } ?>


                                    </tr>

                                <?php  } ?>

                                <tr>
                                    <td colspan='7' style='background-color: #F53333;color: white;font-weight: bold'>Total: <font size=4 color='aqua'><?php echo sizeof($poliza); ?></font>
                                    </td>
                                </tr>

                        <?php $totalpoliza = $totalpoliza + sizeof($poliza); ?>

                    </tbody>


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

            <?php } } ?>

            </center>



        </div>

    </div>


</body>

</html>