<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../../Controller/Asesor.php';

$estructura = $_GET['en'];

if ($estructura == 7) {
    $lider_proyecto = $obj->get_element('lider_enp', 'id_proyecto');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold text-center"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;Crear Proyecto</h1>
                                <center>
                                    <a href="n_proyecto.php?cod_proyecto=<?= 1; ?>" class="btn btn-danger btn-lg btn-rounded">Crear Proyecto</a>
                                </center>
                                <br>

                                <h1 class="font-weight-bold text-center"><i class="fas fa-eye" aria-hidden="true"></i>&nbsp;Seleccione Proyecto Existente</h1>
                            </div>
                            <br><br>

                            <div class="col-md-6 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" autocomplete="off" action="n_proyecto.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Seleccione Proyecto Existente</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <select class="mdb-select md-form colorful-select dropdown-primary my-n2" name="cod_proyecto" required>
                                                            <option value="">Seleccione Proyecto Existente</option>
                                                            <?php
                                                            for ($i = 0; $i < sizeof($lider_proyecto); $i++) {
                                                            ?>
                                                                <option value="<?= $lider_proyecto[$i]["cod_proyecto"]; ?>"><?= utf8_encode($lider_proyecto[$i]["cod_proyecto"]) . " -> " . $lider_proyecto[$i]["lider"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <center>
                                        <button type="submit" id="" class="btn blue-gradient btn-lg btn-rounded">Confirmar</button>
                                    </center>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>