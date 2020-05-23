<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Controller/Poliza.php';

$nramo = $_POST['nramo'];
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
                                <h1 class="font-weight-bold text-center"><i class="fas fa-box" aria-hidden="true"></i>&nbsp;Previsualizar Nuevo Ramo</h1>
                            </div>
                            <br>

                            <div class="col-md-6 mx-auto">
                                <form class="form-horizontal" id="frmnuevo">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="heavy-rain-gradient">
                                                <tr>
                                                    <th class="text-black font-weight-bold">Nombre del Ramo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="nramo" readonly value="<?= $nramo; ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <center>
                                        <a name="enlace" href="ramo_n.php?nramo=<?= $nramo; ?>" class="btn blue-gradient btn-lg btn-rounded">Confirmar</a>
                                    </center>
                                </form>
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