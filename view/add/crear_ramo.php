<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Controller/Poliza.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <?php if (isset($_GET['cond'])) { ?>
                                    <h1 class="font-weight-bold text-center"><i class="fas fa-check-square text-success" aria-hidden="true"></i>&nbsp;Agregado con Éxito</h1>
                                <?php } ?>
                                <h1 class="font-weight-bold text-center"><i class="fas fa-box" aria-hidden="true"></i>&nbsp;Añadir Nuevo Ramo</h1>
                            </div>
                            <br>

                            <div class="col-md-6 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" autocomplete="off" action="ramo.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white text-center">
                                                <tr>
                                                    <th>Nombre del Ramo *</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="nramo" name="nramo" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    

                                    <center>
                                        <button type="submit" id="btnForm" class="btn blue-gradient btn-lg btn-rounded">Previsualizar</button>
                                    </center>
                                </form>

                                <div id="load" class="d-flex justify-content-center align-items-center" hidden>
                                    <div class="spinner-grow text-info" style="width: 9rem; height: 9rem;" id="load1" hidden></div>
                                </div>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script>
            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>