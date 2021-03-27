<?php require_once '../../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'crm/b_mensaje';

require_once '../../../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
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
                                <h1 class="font-weight-bold ">Carta de Bienvenida Pólizas Nuevas</h1>
                            </div>
                </div>

                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                    <!-- Grid row -->
                    <div class="row">

                        <!-- Grid column -->
                        <div class="col-md-10 m-auto">

                            <ul class="nav md-pills nav-justified pills-rounded pills-blue-gradient">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#panel100" role="tab">Filtro Activo</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel101" role="tab">Programar Filtros</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel102" role="tab">Carta de Bienvenida</a>
                                </li>
                            </ul>

                            <!-- Tab panels -->
                            <div class="tab-content card mt-2">

                                <!--Panel 1-->
                                <div class="tab-pane fade in show active" id="panel100" role="tabpanel">

                                    <div class="container">
                                        <p class="note note-primary">
                                            <strong>Asesores Restringidos:</strong>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia dolore quisquam quidem laboriosam at, cumque sit corrupti itaque quibusdam optio vel consectetur. Doloremque maxime sapiente nobis placeat ab accusamus cupiditate?
                                        </p>

                                        <p class="note note-light">
                                            <strong>Cías Restringidas:</strong>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia dolore quisquam quidem laboriosam at, cumque sit corrupti itaque quibusdam optio vel consectetur. Doloremque maxime sapiente nobis placeat ab accusamus cupiditate?
                                        </p>

                                        <p class="note note-info">
                                            <strong>Ramos Restringidos:</strong>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia dolore quisquam quidem laboriosam at, cumque sit corrupti itaque quibusdam optio vel consectetur. Doloremque maxime sapiente nobis placeat ab accusamus cupiditate?
                                        </p>
                                    </div>

                                </div>
                                <!--/.Panel 1-->

                                <!--Panel 2-->
                                <div class="tab-pane fade" id="panel101" role="tabpanel">



                                </div>
                                <!--/.Panel 2-->

                                <!--Panel 3-->
                                <div class="tab-pane fade" id="panel102" role="tabpanel">

                                    <div style='background-color: #f4f4f4;'>
                                        <br><br><br>
                                        <div style='width: 90%;margin: 0 auto;background-color: white'>
                                            <div style='padding: 30px'>

                                                <center>
                                                    <div>
                                                        <div class='title' style='background-color: #0f4296;color: white;width: 90%;font-size: 2vw'>Estimado Asegurado: <br>
                                                            'Nombre del Asegurado'
                                                            <br> Póliza No.: 'Nº Póliza'
                                                        </div>

                                                        <img src='https://versatilseguros.com/Aplicacion/assets/img/carta.jpg' alt='firma-versatil' style='width: 90%;vertical-align: middle;border-style: none'>
                                                    </div>
                                                </center>

                                                <br>

                                                <h3 style='width: 90%;margin-left: 9%;font-size: 2vw'>Escríbame a: fnavasn@outlook.com</h3>

                                                <br>
                                                <hr style='box-sizing: content-box;
										height: 0;
										overflow: visible;width: 90%'>

                                                <center>
                                                    <p>

                                                    <div style='background-color: #0f4296;color: white;width: 90%'>
                                                        <br>
                                                        <center><a href='https://www.versatilseguros.com'>
                                                                <h3 style='color:white'>www.versatilseguros.com</h3>
                                                            </a></center>
                                                        <center>
                                                            <h4 style='width: 90%'>Boulevard Costa del Este, Edificio Financial Park, Piso 8, Oficina 8-A, Ciudad de Panamá, Telf.: +5073876800-01</h4>
                                                        </center>
                                                        <br>
                                                    </div>

                                                    <br>

                                                    <center><a href='#'>
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



                                </div>
                                <!--/.Panel 3-->

                            </div>

                        </div>
                        <!-- Grid column -->

                    </div>
                    <!-- Grid row -->

                </div>

            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../../assets/view/b_poliza.js"></script>
</body>

</html>