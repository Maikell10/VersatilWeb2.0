<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);
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
                                <h1 class="font-weight-bold text-center"><i class="fas fa-search-plus" aria-hidden="true"></i> Seleccione una Estructura de Negocios</h1>
                            </div>
                            <br><br>

                            <div class="col-md-8 mx-auto">
                                <form class="form-horizontal" method="POST" id="frmnuevo">
                                    <div class="form-row  col-md-8 m-auto">
                                        <label>Seleccione Estructura de Negocios:</label>
                                        <select class="form-control selectpicker" name="estructura_neg" id="estructura_neg" data-style="btn-white" required>
                                            <option value="">Seleccione Estructura de Negocios</option>
                                            <option value="c_asesor.php?en=1">Asesor Independiente [A]</option>
                                            <option value="">Ejecutivo de Cuentas [E]</option>
                                            <option value="">Concesionario [C]</option>
                                            <option value="">Negocio Versatil [NV]</option>
                                            <option value="">Negocio Propio [NP]</option>
                                            <option value="c_proyecto.php?en=7">Proyecto [P]</option>
                                            <option value="c_referidor.php">Referidor [R]</option>
                                        </select>
                                    </div>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script>
            $('#estructura_neg').on('change', function() {
                if (this.value == '') {
                    alertify.error("Módulo en creación");
                }else{
                    document.location.href=this.value
                }
            });

            $(document).ready(function() {
                $('#estructura_neg option:first').prop('selected', true);
            });
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>