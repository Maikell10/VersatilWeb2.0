<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold text-center"><i class="fas fa-search-plus" aria-hidden="true"></i> Seleccione una Estructura de Negocios para ver su Listado</h1>
                            </div>
                            <br><br><br>

                            <div class="col-md-8 mx-auto">
                                <form action="f_product.php" class="form-horizontal" method="POST">
                                    <div class="form-row  col-md-8 m-auto">
                                        <label>Seleccione Estructura de Negocios:</label>
                                        <select class="form-control selectpicker" name="estructura_neg" id="estructura_neg" data-style="btn-white" required onchange="document.location.href=this.value">
                                            <option value="">Seleccione Estructura de Negocios</option>
                                            <option value="en/b_asesor.php">Asesor Independiente [A]</option>
                                            <option value="">Ejecutivo de Cuentas [E]</option>
                                            <option value="">Concesionario [C]</option>
                                            <option value="">Negocio Versatil [NV]</option>
                                            <option value="">Negocio Propio [NP]</option>
                                            <option value="en/b_proyecto.php">Proyecto [P]</option>
                                            <option value="en/b_referidor.php">Referidor [R]</option>
                                        </select>
                                    </div>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>