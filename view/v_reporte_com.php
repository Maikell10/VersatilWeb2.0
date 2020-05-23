<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}

$pag = 'v_reporte_com';

require_once '../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <?php if (isset($_GET['m']) == 2) { ?>
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    <strong>Reporte Subido correctamente en .pdf!</strong>
                    <button style="cursor: pointer" type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <div class="ml-5 mr-5">


                <?php
                
                $id_rep_com_p = $id_rep_com . "rep.pdf";
                $archivo = './' . $id_rep_com_p;

                //190.140.224.69                    
                $ftp_server = "186.75.241.90";
                $port = 21;
                $ftp_usuario = "usuario";
                $ftp_pass = "20127247";
                $con_id = @ftp_connect($ftp_server, $port) or die("Unable to connect to server.");
                $lr = ftp_login($con_id, $ftp_usuario, $ftp_pass);

                //ftp_pasv($con_id, true);

                if ((!$con_id) || (!$lr)) {
                    echo "no se pudo conectar";
                } else {
                    # Cambiamos al directorio especificado
                    if (ftp_chdir($con_id, '')) {

                        // Obtener los archivos contenidos en el directorio actual
                        $contents = ftp_nlist($con_id, ".");

                        if (in_array($archivo, $contents)) {
                            //echo "<br>";
                            //echo "I found ".$archivo." in directory";
                ?>
                            <a href="download.php?id_rep_com=<?= $id_rep_com; ?>" class="btn cloudy-knoxville-gradient btn-rounded float-right" target="_blank"><img src="../assets/img/pdf-logo.png" width="60" alt=""></a>
                            <br>
                        <?php } ?>
                        <center>
                            <form class="md-form col-md-4" action="save_r.php" method="post" enctype="multipart/form-data">
                                <h5 class="text-center">Seleccione el Reporte pdf a cargar</h5>
                                <br>

                                <div class="file-field big">
                                    <a class="btn-floating btn-lg red lighten-1 mt-0 float-left">
                                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                                        <input type="file" id="archivo" name="archivo" accept="application/pdf" required>
                                    </a>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" placeholder="Eliga un archivo PDF" disabled>
                                    </div>
                                </div>

                                <button class="btn dusty-grass-gradient font-weight-bold btn-rounded">Subir Archivo <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></button>
                                <input type="text" name="id_rep_com" value="<?= $id_rep_com; ?>" hidden>

                            </form>
                        </center>
                <?php ftp_close($con_id);
                    }
                } ?>

                <h1 class="font-weight-bold">Compañía: <?= utf8_encode($cia[0]['nomcia']); ?></h1>
                <hr>
                <center>
                    <a href="add/c_comision.php?id_rep=<?= $id_rep_com; ?>&f_hasta=<?= $f_hasta_rep; ?>&cant_poliza=1&f_pagoGc=<?= $f_pago_gc; ?>&primat_com=<?= $rep_com[0]['primat_com']; ?>&comt=<?= $rep_com[0]['comt']; ?>&cia=<?= $rep_com[0]['id_cia']; ?>&exx=1" data-toggle="tooltip" data-placement="top" title="Añadir Comisión" class="btn blue-gradient btn-lg">Añadir Comisión &nbsp;<i class="fas fa-plus" aria-hidden="true"></i></a>

                    <a href="e_reporte.php?id_rep_com=<?= $id_rep_com; ?>" data-toggle="tooltip" data-placement="top" title="Editar Fechas y Montos Totales" class="btn dusty-grass-gradient btn-lg">Editar Reporte &nbsp;<i class="fas fa-edit" aria-hidden="true"></i></a>


                    <?php if ($_SESSION['id_permiso'] == 1) { ?>
                        <button onclick="eliminarReporte('<?= $id_rep_com; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient btn-lg text-white">Eliminar Reporte &nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                    <?php } ?>
                </center>
                <hr>
            </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            
        <h2 class="text-danger text-center font-weight-bold">Hay una diferencia de $ con lo cargado en el reporte y la sumatoria de las comisiones</h2>

            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>Fecha Hasta Reporte</th>
                        <th>Fecha Pago GC</th>
                        <th>Prima Sujeta a Comisión Total</th>
                        <th>Comisión Total</th>
                        <th hidden>id reporte</th>
                        <th hidden>cia</th>
                        <th hidden>cant_poliza</th>
                    </thead>
                    <tbody>
                        <td><?= $f_hasta_rep; ?></td>
                        <td><?= $f_pago_gc; ?></td>
                        <td class="text-right"><?= number_format($rep_com[0]['primat_com'], 2); ?></td>
                        <td class="text-right"><?= number_format($rep_com[0]['comt'], 2); ?></td>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%" id="tableVRepCom">
                    <thead class="blue-gradient text-white">
                        <th hidden>id</th>
                        <th>N° de Póliza</th>
                        <th nowrap>Asegurado</th>
                        <th>Fecha de Pago de la Prima</th>
                        <th style="background-color: #E54848;">Prima Sujeta a Comisión</th>
                        <th>Comisión</th>
                        <th>% Comisión</th>
                        <th>Asesor - Ejecutivo</th>
                        <th></th>
                    </thead>

                    <tbody>
                        <?php
                        for ($i = 0; $i < sizeof($comision); $i++) {
                            $totalPrimaCom = $totalPrimaCom + $comision[$i]['prima_com'];
                            $totalCom = $totalCom + $comision[$i]['comision'];

                            $titu = $obj->get_titulat_by_polizaid($comision[$i]['id_poliza']);

                            $f_pago_prima = date("d-m-Y", strtotime($comision[$i]['f_pago_prima']));
                            $f_pago_prima = date("Y/m/d", strtotime($comision[$i]['f_pago_prima']));

                            $nombre = $titu[0]['nombre_t'] . " " . $titu[0]['apellido_t'];
                            if ($titu[0]['id_titular'] == 0) {
                                $tituprep = $obj->get_element_by_id('titular_pre_poliza', 'id_poliza', $comision[$i]['id_poliza']);
                                $nombre = $tituprep[0]['asegurado'];
                            }
                        ?>
                            <tr style="cursor: pointer;">
                                <td hidden><?= $comision[$i]['id_poliza']; ?></td>
                                <td><?= $comision[$i]['num_poliza']; ?></td>
                                <td nowrap><?= ($nombre); ?></td>
                                <td><?= $f_pago_prima; ?></td>
                                <td align="right"><?= "$ " . number_format($comision[$i]['prima_com'], 2); ?></td>
                                <td align="right"><?= "$ " . number_format($comision[$i]['comision'], 2); ?></td>
                                <td align="center"><?= number_format(($comision[$i]['comision'] * 100) / $comision[$i]['prima_com'], 2) . " %"; ?></td>
                                <td><?= $comision[$i]['cod_vend']; ?></td>
                                <td class="text-center"><button onclick="eliminarComision('<?= $comision[$i]['id_comision']; ?>','<?= $_SESSION['id_usuario']; ?>','<?= $comision[$i]['num_poliza']; ?>','<?= $f_hasta_rep; ?>','<?= $cia[0]['nomcia']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-danger btn-sm">&nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></button></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                    <tfoot>
                        <tr class="blue-gradient text-white">
                            <th hidden>id</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="right">
                                <font size=4><?= "$ " . number_format($totalPrimaCom, 2); ?></font>
                            </td>
                            <td align="right">
                                <font size=4><?= "$ " . number_format($totalCom, 2); ?></font>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th hidden>id</th>
                            <th>N° de Póliza</th>
                            <th>Asegurado</th>
                            <th>Fecha de Pago de la Prima</th>
                            <th>Prima Sujeta a Comisión</th>
                            <th>Comisión</th>
                            <th>% Comisión</th>
                            <th>Asesor - Ejecutivo</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>


    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <script src="../assets/view/b_poliza.js"></script>
    <script src="../assets/view/modalE.js"></script>

</body>

</html>