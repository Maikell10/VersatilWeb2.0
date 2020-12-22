<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Model/Poliza.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">

        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <div class="ml-5 mr-5">
        </div>

        <br><br>

        <div class="ml-5 mr-5">
            <div class="col-md-auto col-md-offset-2 hover-collapse">
                <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">Cumpleaños</a></h2>
            </div>
            <br><br>

            <div class="collapse" id="collapse1">
                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                <div class="card-deck">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body hoverable">
                            <a href="birthday/birthday.php">
                                <h5 class="card-title text-white">Listado</h5>
                            </a>
                        </div>
                    </div>
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body hoverable">
                            <a href="crm/v_mensaje_prog.php">
                                <h5 class="card-title text-white">Ver Mensajes Programados</h5>
                            </a>
                        </div>
                    </div>
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body hoverable">
                            <a href="crm/b_mensaje.php">
                                <h5 class="card-title text-white">Programar Mensaje</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <br>

            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                <div class="col-md-auto col-md-offset-2 hover-collapse">
                    <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2">Promociones</a></h2>
                </div>
                <br><br>

                <div class="collapse" id="collapse2">
                    <div class="card-deck">
                        <div class="card text-white bg-info mb-3">
                            <a href="add/crear_comision.php" class="hoverable card-body">
                                <div class="">
                                    <h5 class="card-title text-white">Listado</h5>
                                </div>
                            </a>
                        </div>

                        <div class="card text-white bg-info mb-3">
                            <a href="gc/pago_gc_r.php" class="hoverable card-body">
                                <div>
                                    <h5 class="card-title text-white">Programar Promocion
                                    </h5>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                <br>

            <?php } ?>

            <br>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>
</body>

</html>