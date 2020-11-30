<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'renov/b_renov_t';

require_once '../../Controller/Poliza.php';

if ($_SESSION['id_permiso'] == 3) {
    $user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
    $polizas = $obj->renovar_asesor($user[0]['cod_vend']);
} else {
    $polizas = $obj->renovar();
}

$cant_p = sizeof($polizas);
foreach ($polizas as $poliza) {
    $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
    if (sizeof($poliza_renov) != 0) {
        $cant_p = $cant_p - 1;
    }
}

$no_renov = $obj->get_element('no_renov', 'no_renov_n');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5">
                            <h1 class="font-weight-bold ">Lista Pólizas Pendientes a Renovar a la Fecha</h1>
                        </div>
            </div>

            <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">
                <center><a class="btn dusty-grass-gradient" onclick="tableToExcel('tableRenovE', 'Listado de Pólizas a Renovar')" data-toggle="tooltip" data-placement="right" title="Exportar a Excel"><img src="../../assets/img/excel.png" width="60" alt=""></a></center>

                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" id="tableRenov" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th>-</th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th hidden>Cía</th>
                                <th hidden>Ramo</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita</th>
                                <th>Prima Cobrada</th>
                                <th style="background-color: #E54848;">Prima Pendiente</th>
                                <th>Nombre Titular</th>
                                <th>PDF</th>
                                <?php if ($_SESSION['id_permiso'] != 3 || $user[0]['carga'] == 1) { ?>
                                <th>Acciones</th>
                                <?php } ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $prima_t = 0;
                            foreach ($polizas as $poliza) {
                                $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
                                if (sizeof($poliza_renov) == 0) {
                                    $prima_t = $prima_t + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $polizap = $obj->get_comision_rep_com_by_id($poliza['id_poliza']);
                                    $no_renova = $obj->verRenov1($poliza['id_poliza']);
                                    $vRenov = $obj->verRenov($poliza['id_poliza']);

                                    $tool = 'Cía: ' . $poliza['nomcia'] . ' | Ramo: ' . $poliza['nramo'];

                                    $primac = $obj->obetnComisiones($poliza['id_poliza']);
                                    $primacT = $primacT + $primac[0]['SUM(prima_com)'];

                                    $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                                    $ppendiente = number_format($ppendiente, 2);
                                    if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                        $ppendiente = 0;
                                    }
                            ?>
                                    <tr style="cursor: pointer;">
                                        <td hidden><?= $poliza['f_hastapoliza']; ?></td>
                                        <td hidden><?= $poliza['id_poliza']; ?></td>

                                        <?php if ($poliza['id_tpoliza'] == 1) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                                        <?php } if ($poliza['id_tpoliza'] == 2) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                                        <?php } if ($poliza['id_tpoliza'] == 3) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                                        <?php } ?>
                                        
                                        <td style="color: #E54848;font-weight: bold" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $poliza['cod_poliza']; ?></td>
                                        <td nowrap data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $poliza['nombre'].' ('.$poliza['codvend'].')'; ?></td>
                                        <td hidden><?= $poliza['nomcia']; ?></td>
                                        <td hidden><?= $poliza['nramo']; ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $newDesde; ?></td>
                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= $newHasta; ?></td>

                                        <td align="right" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                        <td style="text-align: right" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>">$ <?= number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                        <?php if ($ppendiente > 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>">$ <?= $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente == 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>">$ <?= $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente < 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px" data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>">$ <?= $ppendiente; ?></td>
                                        <?php } ?>

                                        
                                        <td data-toggle="tooltip" data-placement="top" title="<?= $tool; ?>"><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>

                                        <?php if ($poliza['pdf'] == 1) { ?>
                                        <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                        <?php } else {
                                        if ($poliza['nramo'] == 'Vida') {
                                            $vRenov = $obj->verRenov3($poliza['id_poliza']);
                                            if ($vRenov != 0) {
                                                if ($vRenov[0]['pdf'] != 0) {
                                                    $poliza_pdf_vida = $obj->get_pdf_vida_id($vRenov[0]['id_poliza']); ?>
                                                    <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                    <?php } else {
                                                    $poliza_pdf_vida = $obj->get_pdf_vida($vRenov[0]['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                    if ($poliza_pdf_vida[0]['pdf'] == 1) {  ?>
                                                        <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                    <?php } else { ?>
                                                        <td></td>
                                                    <?php }
                                                }
                                            } else {
                                                $poliza_pdf_vida = $obj->get_pdf_vida($poliza['cod_poliza'], $poliza['id_cia'], $poliza['f_hastapoliza']);
                                                if ($poliza_pdf_vida[0]['pdf'] == 1) { ?>
                                                    <td class="text-center"><a href="../download.php?id_poliza=<?= $poliza_pdf_vida[0]['id_poliza']; ?>" class="btn btn-white btn-rounded btn-sm" target="_blank"><img src="../../assets/img/pdf-logo.png" width="20" id="pdf"></a></td>
                                                <?php } else { ?>
                                                    <td></td>
                                            <?php }
                                            }
                                        } else { ?>
                                            <td></td>
                                        <?php } } if ($_SESSION['id_permiso'] != 3 || $user[0]['carga'] == 1) { ?>

                                        <td nowrap class="text-center">
                                            <a onclick="crearSeguimiento(<?= $poliza['id_poliza']; ?>)" data-toggle="tooltip" data-placement="top" title="Añadir Seguimiento" class="btn blue-gradient btn-rounded btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>

                                            <?php if($polizap != 0) { ?>
                                                <a href="crear_renov.php?id_poliza=<?= $poliza['id_poliza']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Renovar" class="btn dusty-grass-gradient btn-rounded btn-sm"><i class="fa fa-check-circle" aria-hidden="true"></i></a>
                                            <?php } ?>

                                            <?php if($polizap == 0 && $no_renov[0]['no_renov'] != 1 && $vRenov == 0) { ?>
                                                <a onclick="noRenovar1(<?= $poliza['id_poliza']; ?>,'<?= $poliza['f_hastapoliza']; ?>')" data-toggle="tooltip" data-placement="top" title="No Renovar" class="btn young-passion-gradient btn-rounded btn-sm"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                                            <?php } else { ?>
                                                <a onclick="noRenovar(<?= $poliza['id_poliza']; ?>,'<?= $poliza['f_hastapoliza']; ?>')" data-toggle="tooltip" data-placement="top" title="No Renovar" class="btn young-passion-gradient btn-rounded btn-sm"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                                            <?php } ?>

                                        </td>
                                        <?php } ?>
                                    </tr>
                            <?php } else {
                                    //$cant_p = $cant_p - 1;
                                }
                            } ?>
                        </tbody>

                        <tfoot class="text-center">
                            <tr>
                                <th hidden>f_hastapoliza</th>
                                <th hidden>id</th>
                                <th></th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th hidden>Cía</th>
                                <th hidden>Ramo</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita $<?= number_format($prima_t, 2); ?></th>
                                <th>Prima Cobrada $<?= number_format($primacT, 2); ?></th>
                                <th>Prima Pendiente $<?= number_format($prima_t - $primacT, 2); ?></th>
                                <th>Nombre Titular</th>
                                <th>PDF</th>
                                <?php if ($_SESSION['id_permiso'] != 3 || $user[0]['carga'] == 1) { ?>
                                <th>Acciones</th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="table-responsive-xl" hidden>
                    <table class="table table-hover table-striped table-bordered" id="tableRenovE" width="100%">
                        <thead class="blue-gradient text-white text-center">
                            <tr>
                                <th style="background-color: #4285F4; color: white">-</th>
                                <th style="background-color: #4285F4; color: white">N° Póliza</th>
                                <th style="background-color: #4285F4; color: white">Nombre Asesor</th>
                                <th style="background-color: #4285F4; color: white">Cía</th>
                                <th style="background-color: #4285F4; color: white">Ramo</th>
                                <th style="background-color: #4285F4; color: white">F Desde Seguro</th>
                                <th style="background-color: #4285F4; color: white">F Hasta Seguro</th>
                                <th style="background-color: #4285F4; color: white">Prima Suscrita</th>
                                <th style="background-color: #4285F4; color: white">Prima Cobrada</th>
                                <th style="background-color: #E54848; color: white">Prima Pendiente</th>
                                <th style="background-color: #4285F4; color: white">Nombre Titular</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $prima_t = 0;
                            $primacT = 0 ;
                            foreach ($polizas as $poliza) {
                                $poliza_renov = $obj->comprobar_poliza($poliza['cod_poliza'], $poliza['id_cia']);
                                if (sizeof($poliza_renov) == 0) {
                                    $prima_t = $prima_t + $poliza['prima'];

                                    $newDesde = date("Y/m/d", strtotime($poliza['f_desdepoliza']));
                                    $newHasta = date("Y/m/d", strtotime($poliza['f_hastapoliza']));

                                    $primac = $obj->obetnComisiones($poliza['id_poliza']);
                                    $primacT = $primacT + $primac[0]['SUM(prima_com)'];

                                    $ppendiente = $poliza['prima'] - $primac[0]['SUM(prima_com)'];
                                    $ppendiente = number_format($ppendiente, 2);
                                    if ($ppendiente >= -0.10 && $ppendiente <= 0.10) {
                                        $ppendiente = 0;
                                    }
                            ?>
                                    <tr style="cursor: pointer;">

                                        <?php if ($poliza['id_tpoliza'] == 1) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Nueva">N<span hidden>ueva</span></td>
                                        <?php } if ($poliza['id_tpoliza'] == 2) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Renovación">R<span hidden>enovacion</span></td>
                                        <?php } if ($poliza['id_tpoliza'] == 3) { ?>
                                            <td style="text-align: center;font-weight: bold" data-toggle="tooltip" data-placement="top" title="Traspaso de Cartera">T<span hidden>raspaso de Cartera</span></td>
                                        <?php } ?>
                                        
                                        <td style="color: #E54848;font-weight: bold"><?= $poliza['cod_poliza']; ?></td>
                                        <td nowrap><?= $poliza['nombre'].' ('.$poliza['codvend'].')'; ?></td>
                                        <td><?= $poliza['nomcia']; ?></td>
                                        <td><?= $poliza['nramo']; ?></td>
                                        <td><?= $newDesde; ?></td>
                                        <td><?= $newHasta; ?></td>
                                        <td align="right"><?= '$ ' . number_format($poliza['prima'], 2); ?></td>
                                        <td style="text-align: right">$ <?= number_format($primac[0]['SUM(prima_com)'], 2); ?></td>

                                        <?php if ($ppendiente > 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#F53333;font-size: 16px">$ <?= $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente == 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:black;text-align: right;font-weight: bold;">$ <?= $ppendiente; ?></td>
                                        <?php }
                                        if ($ppendiente < 0) { ?>
                                            <td style="background-color: #D9D9D9 ;color:white;text-align: right;font-weight: bold;color:#2B9E34;font-size: 16px">$ <?= $ppendiente; ?></td>
                                        <?php } ?>

                                        <td><?= ($poliza['nombre_t'] . ' ' . $poliza['apellido_t']); ?></td>
                                        
                                    </tr>
                            <?php } else {
                                    //$cant_p = $cant_p - 1;
                                }
                            } ?>
                        </tbody>

                        <tfoot class="text-center">
                            <tr>
                                <th>-</th>
                                <th>N° Póliza</th>
                                <th>Nombre Asesor</th>
                                <th>Cía</th>
                                <th>Ramo</th>
                                <th>F Desde Seguro</th>
                                <th>F Hasta Seguro</th>
                                <th>Prima Suscrita $<?= number_format($prima_t, 2); ?></th>
                                <th>Prima Cobrada $<?= number_format($primacT, 2); ?></th>
                                <th>Prima Pendiente $<?= number_format($prima_t - $primacT, 2); ?></th>
                                <th>Nombre Titular</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <h1 class="title text-center">Total de Prima Suscrita</h1>
                <h1 class="title text-danger text-center">$ <?= number_format($prima_t, 2); ?></h1>

                <h1 class="title text-center">Total de Pólizas</h1>
                <h1 class="title text-danger text-center"><?= $cant_p; ?></h1>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <!-- Modal SEGUIMIENTO RENOV-->
        <div class="modal fade" id="seguimientoRenov" tabindex="-1" role="dialog" aria-labelledby="seguimientoRenov" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="seguimientoRenov">Crear Comentario para Seguimiento de la Póliza</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoS" class="md-form">
                            <input type="text" class="form-control" id="id_polizaS" name="id_polizaS" hidden>
                            <input type="text" class="form-control" id="id_usuarioS" name="id_usuarioS" value="<?= $_SESSION['id_usuario']; ?>" hidden>

                            <label for="comentarioS">Ingrese Comentario</label>
                            <textarea class="form-control md-textarea" id="comentarioS" name="comentarioS" required onKeyDown="valida_longitud()" onKeyUp="mayus(this);valida_longitud()" maxlength="300"></textarea>

                            <input type="text" id="caracteres" class="form-control text-danger" disabled value="Caracteres restantes: 300">

                            <br>

                            <span data-toggle="tooltip" data-placement="top" title="Al seleccionar un Comentario rápido, el comentario normal queda sin validez para la carga actual">
                                <select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="comentarioSs" name="comentarioSs" searchable="Búsqueda rápida" title="Seleccione Comentario Rápido">
                                    <option value="0">Seleccione Comentario Rápido</option>
                                    <option value="SE SOLICITO LA POLIZA A LA CIA">SE SOLICITO LA POLIZA A LA CIA</option>
                                    <option value="SE ENVIO LA POLIZA AL ASEGURADO">SE ENVIO LA POLIZA AL ASEGURADO</option>
                                    <option value="SE ENVIO LA POLIZA AL ASEGURADO POR SEGUNDA VEZ">SE ENVIO LA POLIZA AL ASEGURADO POR SEGUNDA VEZ</option>
                                    <option value="SE ENVIO LA POLIZA AL CORREDOR PARA SU TRAMITACION">SE ENVIO LA POLIZA AL CORREDOR PARA SU TRAMITACION</option>
                                    <option value="SE LLAMO AL ASEGURADO Y SE LE OFRECIO LA POLIZA">SE LLAMO AL ASEGURADO Y SE LE OFRECIO LA POLIZA</option>
                                    <option value="A LA ESPERA DE RESPUESTA DEL ASEGURADO">A LA ESPERA DE RESPUESTA DEL ASEGURADO</option>
                                    <option value="SE MANDO A MODIFICAR LA POLIZA A LA CIA DE SEGUROS">SE MANDO A MODIFICAR LA POLIZA A LA CIA DE SEGUROS</option>
                                </select>
                            </span>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn dusty-grass-gradient" id="btnSeguimientoR">Crear</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal NO RENOV-->
        <div class="modal fade" id="noRenov" tabindex="-1" role="dialog" aria-labelledby="noRenov" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noRenov">No Renovar Póliza</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoNR" class="md-form">
                            <input type="text" class="form-control" id="id_polizaNR" name="id_polizaNR" hidden>
                            <input type="text" class="form-control" id="id_usuarioNR" name="id_usuarioNR" value="<?= $_SESSION['id_usuario']; ?>" hidden>
                            <input type="text" class="form-control" id="f_hastaNR" name="f_hastaNR" hidden>

                            <select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="no_renov" name="no_renov" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un Motivo" searchable="Búsqueda rápida">
                                <option value="">Seleccione el Motivo</option>
                                <?php
                                for ($i = 0; $i < sizeof($no_renov); $i++) {
                                ?>
                                    <option value="<?= $no_renov[$i]["id_no_renov"]; ?>"><?= utf8_encode($no_renov[$i]["no_renov_n"]); ?></option>
                                <?php } ?>
                            </select>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn dusty-grass-gradient" id="btnNoRenov">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal NO RENOV 1-->
        <div class="modal fade" id="noRenov1" tabindex="-1" role="dialog" aria-labelledby="noRenov1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noRenov1">Anular Póliza Pre-Renovada</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmnuevoNR1" class="md-form">
                            <input type="text" class="form-control" id="id_polizaNR1" name="id_polizaNR1" hidden>
                            <input type="text" class="form-control" id="id_usuarioNR1" name="id_usuarioNR1" value="<?= $_SESSION['id_usuario']; ?>" hidden>
                            <input type="text" class="form-control" id="f_hastaNR1" name="f_hastaNR1" hidden>

                            <select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="no_renov1" name="no_renov1" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un Motivo" searchable="Búsqueda rápida">
                                <option value="">Seleccione el Motivo</option>
                                <?php
                                for ($i = 0; $i < sizeof($no_renov); $i++) {
                                ?>
                                    <option value="<?= $no_renov[$i]["id_no_renov"]; ?>"><?= utf8_encode($no_renov[$i]["no_renov_n"]); ?></option>
                                <?php } ?>
                            </select>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn young-passion-gradient text-white" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn dusty-grass-gradient" id="btnNoRenov1">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/view/b_poliza.js"></script>

        <script>
            $("#tableRenov tbody tr").dblclick(function() {
                if (customerId == null) {
                    var customerId = $(this).find("td").eq(1).html();
                }

                window.open("../v_poliza.php?id_poliza=" + customerId, '_blank');
            });
        </script>
</body>

</html>