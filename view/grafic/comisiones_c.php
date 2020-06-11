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
                        <h1 class="font-weight-bold ">Gráficos de Comisiones Cobradas</h1>
                    </div>

                    <br><br>

                    <div class="m-auto col-md-10">
                        <div class="card-deck">
                            <div class="card bg-info mb-6">
                                <a href="Comisiones_Cobradas/busqueda_ramo.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Por Ramo</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <div class="card bg-info mb-6">
                                <a href="Comisiones_Cobradas/busqueda_prima_mes.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Por Mes</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <br>

                        <div class="card-deck">
                            <div class="card bg-info mb-3">
                                <a href="Comisiones_Cobradas/busqueda_cia.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Por Cía</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <div class="card bg-info mb-3 hoverable">
                                <a href="Comisiones_Cobradas/busqueda_tipo_poliza.php">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Por Tipo de Póliza</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                <div class="card bg-info mb-3 hoverable">
                                    <a href="Comisiones_Cobradas/busqueda_ejecutivo.php">
                                        <div class="card-body">
                                            <center>
                                                <h5 class="card-title text-white">Por Ejecutivo</h5>
                                            </center>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="card-deck">
                            <div class="card bg-info mb-12 hoverable">
                                <a href="Comisiones_Cobradas/busqueda_fpago.php">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Por Forma de Pago</h5>
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