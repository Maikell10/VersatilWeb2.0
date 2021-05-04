<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'e_cliente';
require_once '../Controller/Cliente.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <br><br>
                    <div class="ml-5 mr-5">
                        <h1 class="font-weight-bold">Cliente: <?= $cliente[0]['nombre_t'] . " " . $cliente[0]['apellido_t']; ?></h1>
                        <h2 class="title">Nº ID: <?= $cliente[0]['ci']; ?></h2>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <form class="form-horizontal" id="frmnuevo" action="e_cliente_n.php" method="POST" autocomplete="off">
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" width="100%">
                        <thead class="blue-gradient text-white">
                            <th>Razón Social</th>
                            <th>Nº Id</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Fecha Nacimiento</th>
                            <th hidden>id</th>
                        </thead>
                        <tbody>
                            <tr style="background-color: white">
                                    <td><select class="mdb-select md-form colorful-select dropdown-primary my-n2" id="r_social" name="r_social" required data-toggle="tooltip" data-placement="bottom" title="Seleccione un elemento de la lista" searchable="Búsqueda rápida">
                                                <option value="">Seleccione</option>
                                                <option value="PN-">PN-</option>
                                                <option value="J-">J-</option>
                                            </select>
                                        </td>
                                    <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="number" step="0.01" class="form-control" name="ci" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" value="<?= $cliente[0]['ci']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="nombre" value="<?= $cliente[0]['nombre_t']; ?>" onkeyup="mayus(this);">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="apellido" value="<?= $cliente[0]['apellido_t']; ?>" onkeyup="mayus(this);">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control datepicker" id="f_nac" name="f_nac" value="<?= $newFPP; ?>">
                                    </div>
                                </td>
                                <td hidden><input type="text" class="form-control" name="id_titular" value="<?= $cliente[0]['id_titular']; ?>"></td>

                                <td hidden><input type="text" class="form-control" id="r_social_e" name="r_social_e" value="<?= ($cliente[0]['r_social']); ?>"></td>
                            </tr>

                            <tr class="blue-gradient text-white">
                                <th>Celular</th>
                                <th>Teléfono</th>
                                <th colspan="3">email</th>
                            </tr>
                            <tr style="background-color: white">
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="cel" required value="<?= $cliente[0]['cell']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="telf" value="<?= $cliente[0]['telf']; ?>">
                                    </div>
                                </td>
                                <td colspan="3">
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="email" value="<?= $cliente[0]['email']; ?>">
                                    </div>
                                </td>
                            </tr>

                            <tr class="blue-gradient text-white">
                                <th colspan="5">Dirección</th>
                            </tr>
                            <tr style="background-color: white">
                                <td colspan="5">
                                    <div class="input-group md-form my-n1">
                                        <input type="text" class="form-control" name="direcc" value="<?= $cliente[0]['direcc']; ?>" onkeyup="mayus(this);">
                                    </div>
                                </td>
                            </tr>


                        </tbody>
                    </table>
                </div>
                <hr>
                <center>
                    <button type="submit" style="width: 100%" data-toggle="tooltip" title="Previsualizar" class="btn dusty-grass-gradient btn-lg btn-block">Previsualizar Edición &nbsp;<i class="fas fa-check" aria-hidden="true"></i></button>
                </center>
                <hr>
            </form>
        </div>


    </div>



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/b_cliente.js"></script>

    <script>
    $(document).ready(function() {
        $("#r_social option[value=" + $('#r_social_e').val() + "]").attr("selected", true);
    });
    </script>

</body>

</html>