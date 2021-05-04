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

$id_titular = $_POST['id_titular'];

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$r_social = $_POST['r_social'];
$ci = $_POST['ci'];
$f_nac = $_POST['f_nac'];
$cel = $_POST['cel'];
$telf = $_POST['telf'];
$email = $_POST['email'];
$direcc = $_POST['direcc'];

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
                        <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Previsualizar Edición del Cliente
                        </h1>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <form class="form-horizontal" id="frmnuevo">
                <div class="table-responsive-xl">
                    <table class="table table-hover table-striped table-bordered" width="100%">
                        <thead class="heavy-rain-gradient">
                            <th class="text-black font-weight-bold">Razón Social</th>
                            <th class="text-black font-weight-bold">Cédula</th>
                            <th class="text-black font-weight-bold">Nombre</th>
                            <th class="text-black font-weight-bold">Apellido</th>
                            <th class="text-black font-weight-bold">Fecha Nacimiento</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="r_social" readonly value="<?= $r_social; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="ci" readonly value="<?= $ci; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="nombre" readonly value="<?= $nombre; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="apellido" readonly value="<?= $apellido; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="f_nac" readonly value="<?= $f_nac; ?>">
                                    </div>
                                </td>
                            </tr>

                            <tr class="heavy-rain-gradient">
                                <th class="text-black font-weight-bold">Celular</th>
                                <th class="text-black font-weight-bold">Teléfono</th>
                                <th colspan="3" class="text-black font-weight-bold">email</th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="cel" readonly value="<?= $cel; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="telf" readonly value="<?= $telf; ?>">
                                    </div>
                                </td>
                                <td colspan="3">
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="email" readonly value="<?= $email; ?>">
                                    </div>
                                </td>
                            </tr>

                            <tr class="heavy-rain-gradient">
                                <th colspan="5" class="text-black font-weight-bold">Dirección</th>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <div class="input-group md-form my-n1 grey lighten-2">
                                        <input type="text" class="form-control" name="direcc" readonly="readonly" value="<?= $direcc; ?>">
                                    </div>
                                </td>
                            </tr>


                        </tbody>
                    </table>
                </div>
                <hr>

                <center>
                    <a name="enlace" href="e_cliente_nn.php?id_titular=<?= $id_titular; ?>&nombre=<?= $nombre; ?>&apellido=<?= $apellido; ?>&ci=<?= $ci; ?>&cel=<?= $cel; ?>&telf=<?= $telf; ?>&email=<?= $email; ?>&f_nac=<?= $f_nac; ?>&direcc=<?= $direcc; ?>&r_social=<?= $r_social; ?>" class="btn blue-gradient btn-lg btn-rounded">Confirmar</a>
                </center>
                <hr>
            </form>
        </div>


    </div>



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/b_cliente.js"></script>

</body>

</html>