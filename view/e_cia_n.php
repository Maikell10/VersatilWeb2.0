<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Controller/Poliza.php';

$id_cia = $_POST['id_cia'];

$nombre_cia = $_POST['nombre_cia'];
$rif = $_POST['rif'];
$per_com = $_POST['per_com'];

$nombre1 = $_POST['nombre1'];
$cargo1 = $_POST['cargo1'];
$tel1 = $_POST['tel1'];
$cel1 = $_POST['cel1'];
$email1 = $_POST['email1'];

$nombre2 = $_POST['nombre2'];
$cargo2 = $_POST['cargo2'];
$tel2 = $_POST['tel2'];
$cel2 = $_POST['cel2'];
$email2 = $_POST['email2'];

$nombre3 = $_POST['nombre3'];
$cargo3 = $_POST['cargo3'];
$tel3 = $_POST['tel3'];
$cel3 = $_POST['cel3'];
$email3 = $_POST['email3'];

$nombre4 = $_POST['nombre4'];
$cargo4 = $_POST['cargo4'];
$tel4 = $_POST['tel4'];
$cel4 = $_POST['cel4'];
$email4 = $_POST['email4'];

$nombre5 = $_POST['nombre5'];
$cargo5 = $_POST['cargo5'];
$tel5 = $_POST['tel5'];
$cel5 = $_POST['cel5'];
$email5 = $_POST['email5'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
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
                                <h1 class="font-weight-bold text-center"><i class="fa fa-briefcase" aria-hidden="true"></i>&nbsp;Previsualizar Edición de Compañía</h1>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" action="e_cia_n.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="heavy-rain-gradient">
                                                <tr>
                                                    <th colspan="2" class="text-black font-weight-bold">Nombre de Cía</th>
                                                    <th colspan="2" class="text-black font-weight-bold">RUC/RIF</th>
                                                    <th class="text-black font-weight-bold">%Comisión</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="nombre_cia" readonly="readonly" value="<?= $nombre_cia; ?>">
                                                        </div>
                                                    </td>
                                                    <td colspan="2">
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="rif" readonly="readonly" value="<?= $rif; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="per_com" readonly="readonly" value="<?= $per_com; ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="heavy-rain-gradient">
                                                <tr>
                                                    <th class="text-black font-weight-bold">Nombre del Contacto</th>
                                                    <th class="text-black font-weight-bold">Cargo</th>
                                                    <th class="text-black font-weight-bold">Telf</th>
                                                    <th class="text-black font-weight-bold">Celular</th>
                                                    <th class="text-black font-weight-bold">e-mail</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="nombre1" readonly="readonly" value="<?= $nombre1; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cargo1" readonly="readonly" value="<?= $cargo1; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="tel1" readonly="readonly" value="<?= $tel1; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cel1" readonly="readonly" value="<?= $cel1; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="email1" readonly="readonly" value="<?= $email1; ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="nombre2" readonly="readonly" value="<?= $nombre2; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cargo2" readonly="readonly" value="<?= $cargo2; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="tel2" readonly="readonly" value="<?= $tel2; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cel2" readonly="readonly" value="<?= $cel2; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="email2" readonly="readonly" value="<?= $email2; ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="nombre3" readonly="readonly" value="<?= $nombre3; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cargo3" readonly="readonly" value="<?= $cargo3; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="tel3" readonly="readonly" value="<?= $tel3; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cel3" readonly="readonly" value="<?= $cel3; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="email3" readonly="readonly" value="<?= $email3; ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="nombre4" readonly="readonly" value="<?= $nombre4; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cargo4" readonly="readonly" value="<?= $cargo4; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="tel4" readonly="readonly" value="<?= $tel4; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cel4" readonly="readonly" value="<?= $cel4; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="email4" readonly="readonly" value="<?= $email4; ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="nombre5" readonly="readonly" value="<?= $nombre5; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cargo5" readonly="readonly" value="<?= $cargo5; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="tel5" readonly="readonly" value="<?= $tel5; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="cel5" readonly="readonly" value="<?= $cel5; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="email5" readonly="readonly" value="<?= $email5; ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <center>
                                        <a name="enlace" href="e_cia_nn.php?id_cia=<?= $id_cia; ?>&nombre_cia=<?= $nombre_cia; ?>&rif=<?= $rif; ?>&per_com=<?= $per_com; ?>&nombre1=<?= $nombre1; ?>&cargo1=<?= $cargo1; ?>&tel1=<?= $tel1; ?>&cel1=<?= $cel1; ?>&email1=<?= $email1; ?>&nombre2=<?= $nombre2; ?>&cargo2=<?= $cargo2; ?>&tel2=<?= $tel2; ?>&cel2=<?= $cel2; ?>&email2=<?= $email2; ?>&nombre3=<?= $nombre3; ?>&cargo3=<?= $cargo3; ?>&tel3=<?= $tel3; ?>&cel3=<?= $cel3; ?>&email3=<?= $email3; ?>&nombre4=<?= $nombre4; ?>&cargo4=<?= $cargo4; ?>&tel4=<?= $tel4; ?>&cel4=<?= $cel4; ?>&email4=<?= $email4; ?>&nombre5=<?= $nombre5; ?>&cargo5=<?= $cargo5; ?>&tel5=<?= $tel5; ?>&cel5=<?= $cel5; ?>&email5=<?= $email5; ?>" class="btn blue-gradient btn-lg btn-rounded">Confirmar</a>
                                    </center>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

        <script>
            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>