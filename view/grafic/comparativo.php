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
                        <h1 class="font-weight-bold ">Gráficos de Comparativo</h1>
                    </div>

                    <br><br>

                    <div class="m-auto col-md-10">


                        <div class="col-md-auto col-md-offset-2 hover-collapse">
                            <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">Primas Suscritas</a></h2>
                        </div>
                        <br><br>

                        <div class="collapse" id="collapse1">
                            <div class="card-deck">
                                <div class="card text-white bg-info mb-3">
                                    <a href="Comparativo/ps/b_ramo.php" class="hoverable">
                                        <div class="card-body">
                                            <h5 class="card-title text-white">Por Ramo</h5>
                                        </div>
                                    </a>
                                </div>

                                <div class="card text-white bg-info mb-3">
                                    <a href="Comparativo/ps/b_cia.php" class="hoverable">
                                        <div class="card-body">
                                            <h5 class="card-title text-white">Por Cía</h5>
                                        </div>
                                    </a>
                                </div>

                                <div class="card text-white bg-info mb-3">
                                    <a href="Comparativo/ps/b_ejecutivo.php" class="hoverable">
                                        <div class="card-body">
                                            <h5 class="card-title text-white">Por Ejecutivo</h5>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-auto col-md-offset-2 hover-collapse">
                            <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2">Primas Cobradas</a></h2>
                        </div>
                        <br><br>

                        <div class="collapse" id="collapse2">
                            <div class="card-deck">
                                <div class="card text-white bg-info mb-3">
                                    <a href="Comparativo/pc/b_ramo.php" class="hoverable">
                                        <div class="card-body">
                                            <h5 class="card-title text-white">Por Ramo</h5>
                                        </div>
                                    </a>
                                </div>

                                <div class="card text-white bg-info mb-3">
                                    <a href="Comparativo/pc/b_cia.php" class="hoverable">
                                        <div class="card-body">
                                            <h5 class="card-title text-white">Por Cía</h5>
                                        </div>
                                    </a>
                                </div>

                                <div class="card text-white bg-info mb-3">
                                    <a href="Comparativo/pc/b_ejecutivo.php" class="hoverable">
                                        <div class="card-body">
                                            <h5 class="card-title text-white">Por Ejecutivo</h5>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-auto col-md-offset-2 hover-collapse">
                            <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3">Resúmen Mes a Mes</a></h2>
                        </div>
                        <br><br>

                        <div class="collapse" id="collapse3">
                            <div class="card-deck">
                                <div class="card text-white bg-info mb-3">
                                    <a href="Comparativo/b_mm_ramo.php" class="hoverable">
                                        <div class="card-body">
                                            <h5 class="card-title text-white">Ver</h5>
                                        </div>
                                    </a>
                                </div>
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