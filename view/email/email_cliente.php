<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'email_cliente';

require_once '../../Controller/Cliente.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Enviar Correo para:</h1>
                                <h2 class="font-weight-bold">Cliente: <?= ($datos_c[0]['nombre_t']) . " " . ($datos_c[0]['apellido_t']); ?></h2>
                                <h2 class="title">Nº ID: <?= $datos_c[0]['ci']; ?></h2>
                            </div>
                </div>


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                    <div class="col-md-8 m-auto">

                        <!-- Material form login -->
                        <div class="card">

                            <h5 class="card-header info-color white-text text-center py-4">
                                <strong>Enviar Correo</strong>
                            </h5>

                            <!--Card content-->
                            <div class="card-body px-lg-5 pt-0">

                                <!-- Form -->
                                <form class="text-center" style="color: #757575;" action="email_send.php" method="POST">

                                    <input type="hidden" id="id_titu" name="id_titu" class="form-control" value="<?= $_GET['id_titu']; ?>" required>

                                    <!-- Para -->
                                    <div class="md-form">
                                        <input type="email" id="Para" name="Para" class="form-control" value="<?= $datos_c[0]['email']; ?>" required>
                                        <label for="Para">Para</label>
                                    </div>

                                    <!-- Copia -->
                                    <div class="md-form">
                                        <input type="email" id="Copia" name="Copia" class="form-control">
                                        <label for="Copia">Copia</label>
                                    </div>

                                    <!-- Asunto -->
                                    <div class="md-form">
                                        <input type="text" id="Asunto" name="Asunto" class="form-control" value="Pólizas Activas" required>
                                        <label for="Asunto">Asunto</label>
                                    </div>

                                    <!-- Mensaje -->
                                    <div class="md-form">
                                        <textarea id="Message" name="Message" class="md-textarea form-control" rows="3"></textarea>
                                        <label for="Message">Mensaje</label>
                                    </div>


                                    <!-- Sign in button -->
                                    <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Enviar</button>



                                </form>
                                <!-- Form -->

                            </div>

                        </div>
                        <!-- Material form login -->
                    </div>


                    <?php
                    if ($cliente != 0) {
                        if ($contAct > 0) { ?>
                            <h4 class="text-center mt-5 font-weight-bold">Adicional se Adjunta la siguiente tabla de Pólizas Activas del Cliente</h4>
                            <div class="table-responsive-xl">
                                <table class="table table-hover table-striped table-bordered" id="tableClienteEmail">
                                    <thead class="blue-gradient text-white text-center">
                                        <tr style="background-color: #2B9E34">
                                            <th colspan="10" class="font-weight-bold text-center h2">Activas</th>
                                        </tr>
                                        <tr>
                                            <th>N° de Póliza</th>
                                            <th>Ramo</th>
                                            <th>Cía</th>
                                            <th>Nombre Asesor</th>
                                            <th>Fecha Desde Póliza</th>
                                            <th>Fecha Hasta Póliza</th>
                                            <th>Prima Suscrita</th>
                                            <th>Prima Cobrada</th>
                                            <th style="background-color: #E54848;">Prima Pendiente</th>
                                            <th>PDF</th>
                                            <th hidden>id poliza</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $totalPS = 0;
                                        $totalPC = 0;
                                        $totalPP = 0;
                                        for ($i = 0; $i < sizeof($cliente); $i++) {
                                            $no_renov = $obj->verRenov1($cliente[$i]['id_poliza']);
                                            if ($cliente[$i]['f_hastapoliza'] >= date("Y-m-d") && $no_renov[0]['no_renov'] != 1) {
                                                $currency = ($cliente[$i]['currency'] == 1) ? "$ " : "Bs ";
                                                $newDesde = date("d-m-Y", strtotime($cliente[$i]["f_desdepoliza"]));
                                                $newHasta = date("d-m-Y", strtotime($cliente[$i]["f_hastapoliza"]));

                                                $primac = $obj->obetnComisiones($cliente[$i]['id_poliza']);
                                                $ppendiente = $cliente[$i]['prima'] - $primac[0]['SUM(prima_com)'];
                                                if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                                    $ppendiente = 0;
                                                }

                                                $totalPS = $totalPS + $cliente[$i]['prima'];
                                                $totalPC = $totalPC + $primac[0]['SUM(prima_com)'];
                                                $totalPP = $totalPP + $ppendiente;
                                        ?>
                                                <tr style="cursor: pointer">
                                                    <td><?= $cliente[$i]['cod_poliza']; ?></td>
                                                    <td><?= ($cliente[$i]['nramo']); ?></td>
                                                    <td><?= ($cliente[$i]['nomcia']); ?></td>
                                                    <td><?= $cliente[$i]['nombre'] . ' (' . $cliente[$i]['codvend'] . ')'; ?></td>
                                                    <td nowrap><?= $newDesde; ?></td>
                                                    <td nowrap><?= $newHasta; ?></td>
                                                    <td nowrap class="text-right"><?= $currency . number_format($cliente[$i]['prima'], 2); ?></td>

                                                    <td class="text-right"><?= $currency . number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                                    <?php if ($ppendiente > 0) { ?>
                                                        <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                                    <?php }
                                                    if ($ppendiente == 0) { ?>
                                                        <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;"><?= $currency . number_format($ppendiente, 2); ?></td>
                                                    <?php }
                                                    if ($ppendiente < 0) { ?>
                                                        <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px"><?= $currency . number_format($ppendiente, 2); ?></td>
                                                    <?php } ?>


                                                    <?php if ($cliente[$i]['pdf'] == 1) { ?>
                                                        <td class="text-center"><a href="../download.php?id_poliza=<?= $cliente[$i]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                        <?php } else {
                                                        if ($cliente[$i]['nramo'] == 'Vida') {
                                                            $vRenov = $obj->verRenov3($cliente[$i]['id_poliza']);
                                                            if ($vRenov != 0) {
                                                                if ($vRenov[0]['pdf'] != 0) {
                                                                    $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                                    <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                                    <?php } else {
                                                                    $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $cliente[$i]['id_cia'], $cliente[$i]['f_hastapoliza']);
                                                                    if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                                        <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                                    <?php } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                }
                                                            } else {
                                                                $poliza_pdf_vida = $obj->get_pdf_vida($cliente[$i]['cod_poliza'], $cliente[$i]['id_cia'], $cliente[$i]['f_hastapoliza']);
                                                                if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                                    <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="25" id="pdf"></a></td>
                                                                <?php } else { ?>
                                                                    <td></td>
                                                            <?php }
                                                            }
                                                        } else { ?>
                                                            <td></td>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <td hidden><?= $cliente[$i]['id_poliza']; ?></td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>N° de Póliza</th>
                                            <th>Ramo</th>
                                            <th>Cía</th>
                                            <th>Nombre Asesor</th>
                                            <th>Fecha Desde Póliza</th>
                                            <th>Fecha Hasta Póliza</th>
                                            <th>Prima Suscrita</th>
                                            <th>Prima Cobrada</th>
                                            <th style="background-color: #E54848;color: white">Prima Pendiente</th>
                                            <th>PDF</th>
                                            <th hidden>id poliza</th>
                                        </tr>
                                        <tr>
                                            <td colspan="6">Total Pólizas: <font class="text-danger font-weight-bold h5"><?= $contAct; ?></font>
                                            </td>
                                            <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPS, 2); ?></td>
                                            <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPC, 2); ?></td>
                                            <td class="text-right text-danger font-weight-bold h5"><?= '$ ' . number_format($totalPP, 2); ?></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php } ?>
                    <?php } ?>

                </div>

            <?php } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_cliente.js"></script>
</body>

</html>