<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Model/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">

        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold ">Gráficos</h1>
                    </div>

                    <br><br>

                    <div class="ml-5 mr-5">
                        <div class="card-deck">
                            <div class="card bg-info mb-3">
                                <a href="grafic/porcentaje.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Porcentaje</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <div class="card bg-info mb-3">
                                <a href="grafic/primas_s.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Primas Suscritas</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <div class="card bg-info mb-3">
                                <a href="grafic/primas_c.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Primas Cobradas</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="card-deck">
                            <div class="card bg-info mb-6">
                                <a href="grafic/comisiones_c.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Comisiones Cobradas</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>

                            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                                <div class="card bg-info mb-6">
                                    <a href="grafic/resumen.php" class="hoverable">
                                        <div class="card-body">
                                            <center>
                                                <h5 class="card-title text-white">Resúmenes</h5>
                                            </center>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>

                            <div class="card bg-info mb-6">
                                <a href="grafic/comparativo.php" class="hoverable">
                                    <div class="card-body">
                                        <center>
                                            <h5 class="card-title text-white">Comparativo</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <br><br>
        </div>



        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>
</body>

</html>