<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../../Model/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">

        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="m-auto col-md-10">
                        <h1 class="font-weight-bold ">Gráficos de Porcentaje</h1>
                    </div>

                    <br><br>

                    <div class="m-auto col-md-10">
                        <div class="card-deck">
                            <div class="card bg-info mb-3">
                                <a href="Porcentaje/busqueda_ramo.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Distribución de la Cartera por Ramo</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <div class="card bg-info mb-3">
                                <a href="Porcentaje/busqueda_tipo_poliza.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Distribución de la Cartera por Tipo Póliza</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <div class="card bg-info mb-3">
                                <a href="Porcentaje/busqueda_cia.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Distribución de la Cartera por CIA</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="card-deck">
                            <div class="card bg-info mb-3">
                                <a href="Porcentaje/busqueda_fpago.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Distribución de la Cartera por Forma de Pago</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                <div class="card bg-info mb-3 hoverable">
                                    <a href="Porcentaje/busqueda_ejecutivo.php">
                                        <div class="card-body">
                                            <center>
                                                <h5 class="card-title text-white">Distribución de la Cartera por Ejecutivo</h5>
                                            </center>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>

                            <div class="card bg-info mb-3 hoverable">
                                <a href="Porcentaje/busqueda_ramo_promedio.php">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Prima Promedio por Ramo</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <br><br>
        </div>



        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>
</body>

</html>