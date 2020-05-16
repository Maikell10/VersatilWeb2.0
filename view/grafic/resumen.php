<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Model/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">

        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="m-auto col-md-10">
                        <h1 class="font-weight-bold ">Gráficos de Resúmenes</h1>
                    </div>

                    <br><br>

                    <div class="m-auto col-md-10">
                        <div class="card-deck">
                            <div class="card bg-info mb-3">
                                <a href="Comparativo/b_mm_ramo.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Utilidad en Ventas</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <div class="card bg-info mb-3">
                                <a href="resumen/busqueda_resumen_ramo.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Resúmen Por Ramo</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <div class="card bg-info mb-3">
                                <a href="resumen/busqueda_resumen.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Resúmen General</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <br>

                    </div>
                    <br><br>
        </div>



        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>
</body>

</html>