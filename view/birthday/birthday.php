<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'birthday';

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

            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Lista de Cumpleañeros del mes de <?= $mes_arr[date("m") - 1]; ?></h1>
                            </div>
                </div>


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                    <!-- Grid row -->
                    <div class="row">

                        <!-- Grid column -->
                        <div class="col-md-10 m-auto">

                            <ul class="nav md-pills nav-justified pills-rounded pills-blue-gradient">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#panel100" role="tab">Cumpleañeros del Mes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel101" role="tab">Tarjeta de Feliz Cumpleaños</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel102" role="tab">Cumpleañeros del Próximo Mes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel103" role="tab">Clientes sin Fecha Nac</a>
                                </li>
                            </ul>

                            <!-- Tab panels -->
                            <div class="tab-content card mt-2">

                                <!--Panel 1-->
                                <div class="tab-pane fade in show active" id="panel100" role="tabpanel">

                                    <div class="table-responsive-xl">
                                        <table class="table table-hover table-striped table-bordered" id="table_cliente_b" width="100%">
                                            <thead class="blue-gradient text-white text-center">
                                                <tr>
                                                    <th hidden>id</th>
                                                    <th hidden>ci</th>
                                                    <th>Cédula</th>
                                                    <th>Nombre</th>
                                                    <th>Ejecutivo</th>
                                                    <th style="background-color: #E54848;">Día de Cumpleaños</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($birthdays_month as $birthday) {
                                                    $ejecutivo = $obj->get_ejecutivo_by_cod($birthday['codvend']);
                                                ?>
                                                    <tr style="cursor: pointer">
                                                        <td hidden><?= $birthday['id_titular']; ?></td>
                                                        <td hidden><?= $birthday['ci']; ?></td>

                                                        <?php if(date("d") <= date("d", strtotime($birthday['f_nac']))) { ?>
                                                            <td class="font-weight-bold text-success"><?= $birthday['r_social'] . '' .$birthday['ci']; ?></td>
                                                            <td class="font-weight-bold text-success">
                                                                <?= $birthday['nombre_t'] . ' ' . $birthday['apellido_t']; ?>
                                                                <?php if ($birthday['email'] != '-') { ?>
                                                                    <span class="badge badge-pill badge-info">Email</span>
                                                                <?php } ?>
                                                            </td>
                                                            <td><?= $ejecutivo[0]['nombre']; ?></td>
                                                            <td class="text-center font-weight-bold text-success"><?= date("d", strtotime($birthday['f_nac'])); ?></td>
                                                        <?php } else { ?>
                                                            <td><?= $birthday['r_social'] . '' .$birthday['ci']; ?></td>
                                                            <td>
                                                                <?= $birthday['nombre_t'] . ' ' . $birthday['apellido_t']; ?>
                                                                <?php if ($birthday['email'] != '-') { ?>
                                                                    <span class="badge badge-pill badge-info">Email</span>
                                                                <?php } ?>
                                                            </td>
                                                            <td><?= $ejecutivo[0]['nombre']; ?></td>
                                                            <td class="text-center"><?= date("d", strtotime($birthday['f_nac'])); ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>

                                            <tfoot class="text-center">
                                                <tr>
                                                    <th hidden>id</th>
                                                    <th hidden>ci</th>
                                                    <th>Cédula</th>
                                                    <th>Nombre</th>
                                                    <th>Ejecutivo</th>
                                                    <th style="background-color: #E54848; color: white">Día de Cumpleaños</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                                <!--/.Panel 1-->

                                <!--Panel 2-->
                                <div class="tab-pane fade" id="panel101" role="tabpanel">

                                    <div class="text-center">
                                        <img src="<?= constant('URL') . 'assets/img/tarjeta_birthday.png'; ?>" class="z-depth-1" alt="tarjeta_cumpleaños" style='width: 70%;vertical-align: middle;border-style: none'/>
                                    </div>

                                    <hr />
                                    <br>

                                    <div class="description text-center">
                                        <form class="" enctype="multipart/form-data" action="" method="POST">
                                            <label class="form-control h4 font-weight-bold col-md-5 mx-auto">Actualice la Tarjeta de Felicitación</label>
                                            <input name="uploadedfile" type="file" class="form-group btn btn-outline-info">
                                            <div class="">
                                                <input type="submit" value="Subir archivo" class="form-group btn blue-gradient">
                                            </div>
                                        </form>
                                    </div>

                                    <?php
                                    $uploadedfileload = "true";
                                    $uploadedfile_size = $_FILES['uploadedfile']['size'];
                                    ?>
                                    <h5 class="text-center"><?= $_FILES['uploadedfile']['name']; ?></h5>
                                    <?php

                                    if ($_FILES['uploadedfile']['size'] > 20000000) {
                                        $msg = $msg . "El archivo es mayor que 200KB, debes reduzcirlo antes de subirlo<BR>";
                                        $uploadedfileload = "false";
                                    }

                                    if (!($_FILES['uploadedfile']['type'] == "image/jpeg" or $_FILES['uploadedfile']['type'] == "image/jpeg" or $_FILES['uploadedfile']['type'] == "image/png")) {
                                        $msg = $msg . " Tu archivo tiene que ser JPG o PNG. Otros archivos no son permitidos.<BR> En lo posible ser imágen cuadrada (preferiblemente 800 x 800)";
                                        $uploadedfileload = "false";
                                    }

                                    $file_name = "tarjeta_birthday.png";
                                    $add = "../../assets/img/$file_name";
                                    if ($uploadedfileload == "true") {

                                        if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $add)) {
                                            //solo para el servidor versatil
                                            //$obj->update_user_profile($_SESSION['id_usuario']);
                                    ?>
                                            <h5 class="text-center"><?= " Ha sido subido satisfactoriamente"; ?></h5>
                                        <?php

                                        } else {
                                        ?>
                                            <h5 class="text-center"><?= "Error al subir el archivo"; ?></h5>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <h5 class="text-center"><?= $msg; ?></h5>
                                    <?php } ?>

                                </div>
                                <!--/.Panel 2-->

                                <!--Panel 3-->
                                <div class="tab-pane fade" id="panel102" role="tabpanel">

                                    <div class="table-responsive-xl">
                                        <table class="table table-hover table-striped table-bordered" id="table_cliente_pb" width="100%">
                                            <thead class="blue-gradient text-white text-center">
                                                <tr>
                                                    <th hidden>id</th>
                                                    <th hidden>ci</th>
                                                    <th>Cédula</th>
                                                    <th>Nombre</th>
                                                    <th style="background-color: #E54848;">Día de Cumpleaños</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($birthdays_next_month as $birthday) { ?>
                                                    <tr style="cursor: pointer">
                                                        <td hidden><?= $birthday['id_titular']; ?></td>
                                                        <td hidden><?= $birthday['ci']; ?></td>
                                                        <td><?= $birthday['r_social'] . '' .$birthday['ci']; ?></td>
                                                        <td><?= $birthday['nombre_t'] . ' ' . $birthday['apellido_t']; ?></td>
                                                        <td class="text-center"><?= date("d", strtotime($birthday['f_nac'])); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>

                                            <tfoot class="text-center">
                                                <tr>
                                                    <th hidden>id</th>
                                                    <th hidden>ci</th>
                                                    <th>Cédula</th>
                                                    <th>Nombre</th>
                                                    <th style="background-color: #E54848; color: white">Día de Cumpleaños</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                                <!--/.Panel 3-->

                                <!--Panel 4-->
                                <div class="tab-pane fade" id="panel103" role="tabpanel">

                                    <div class="table-responsive-xl">
                                        <table class="table table-hover table-striped table-bordered" id="table_cliente_nb" width="100%">
                                            <thead class="blue-gradient text-white text-center">
                                                <tr>
                                                    <th hidden>id</th>
                                                    <th hidden>ci</th>
                                                    <th>Cédula</th>
                                                    <th>Nombre</th>
                                                    <th style="background-color: #E54848;">Fecha Nacimiento</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($birthdays_no_date as $bnd) { ?>
                                                    <tr style="cursor: pointer">
                                                        <td hidden><?= $bnd['id_titular']; ?></td>
                                                        <td hidden><?= $bnd['ci']; ?></td>
                                                        <td><?= $bnd['r_social'] . '' .$bnd['ci']; ?></td>
                                                        <td><?= $bnd['nombre_t'] . ' ' . $bnd['apellido_t']; ?></td>
                                                        <td><?= date("Y/m/d", strtotime($bnd['f_nac'])); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>

                                            <tfoot class="text-center">
                                                <tr>
                                                    <th hidden>id</th>
                                                    <th hidden>ci</th>
                                                    <th>Cédula</th>
                                                    <th>Nombre</th>
                                                    <th style="background-color: #E54848; color: white">Fecha Nacimiento</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                                <!--/.Panel 4-->

                            </div>

                        </div>
                        <!-- Grid column -->

                    </div>
                    <!-- Grid row -->

                </div>

            <?php } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_cliente.js"></script>
</body>

</html>