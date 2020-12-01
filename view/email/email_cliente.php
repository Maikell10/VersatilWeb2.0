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

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold ">Enviar Correo</h1>
                            </div>
                </div>


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">

                    <form class="col-md-8 m-auto">

                        <!-- Material form login -->
                        <div class="card">

                            <h5 class="card-header info-color white-text text-center py-4">
                                <strong>Enviar Correo</strong>
                            </h5>

                            <!--Card content-->
                            <div class="card-body px-lg-5 pt-0">

                                <!-- Form -->
                                <form class="text-center" style="color: #757575;" action="#!">

                                    <!-- Asunto -->
                                    <div class="md-form">
                                        <input type="text" id="materialLoginFormAsunto" class="form-control" required>
                                        <label for="materialLoginFormAsunto">Asunto</label>
                                    </div>

                                    <!-- Mensaje -->
                                    <div class="md-form">
                                        <textarea id="form7" class="md-textarea form-control" rows="3" required></textarea>
                                        <label for="form7">Mensaje</label>
                                    </div>


                                    <!-- Sign in button -->
                                    <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Enviar</button>

                                    

                                </form>
                                <!-- Form -->

                            </div>

                        </div>
                        <!-- Material form login -->
                    </form>

                </div>

            <?php } ?>


        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_cliente.js"></script>
</body>

</html>