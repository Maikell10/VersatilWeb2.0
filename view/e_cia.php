<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Poliza.php';

$id_cia = $_GET['id_cia'];
$cia = $obj->get_element_by_id('dcia', 'idcia', $id_cia);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold">Cía: <?= ($cia[0]['nomcia']); ?></h1>
                                <h2 class="title">RUC/Rif: <?= $cia[0]['rif']; ?></h2>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" action="e_cia_n.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Nombre Compañía</th>
                                                    <th>RUC/Rif</th>
                                                    <th>%Comisión</th>
                                                    <th hidden>id</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="nombre_cia" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" value="<?= ($cia[0]['nomcia']); ?>" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="rif" value="<?= $cia[0]['rif']; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" name="per_com" value="<?= $cia[0]['per_com']; ?>">
                                                        </div>
                                                    </td>
                                                    <td hidden><input type="text" class="form-control" name="id_cia" value="<?= $cia[0]['idcia']; ?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Nombre del Contacto</th>
                                                    <th>Cargo</th>
                                                    <th>Telf</th>
                                                    <th>Celular</th>
                                                    <th>e-mail</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $contacto_cia = $obj->get_element_by_id('contacto_cia', 'id_cia', $cia[0]['idcia']);
                                                for ($i = 0; $i < sizeof($contacto_cia); $i++) {
                                                ?>
                                                    <tr style="background-color: white">
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control" name="<?= 'nombre' . ($i + 1); ?>" value="<?= ($contacto_cia[$i]['nombre']); ?>" onkeyup="mayus(this);">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control" name="<?= 'cargo' . ($i + 1); ?>" value="<?= utf8_decode($contacto_cia[$i]['cargo']); ?>" onkeyup="mayus(this);">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control" name="<?= 'tel' . ($i + 1); ?>" value="<?= $contacto_cia[$i]['tel']; ?>">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control" name="<?= 'cel' . ($i + 1); ?>" value="<?= $contacto_cia[$i]['cel']; ?>">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group md-form my-n1">
                                                                <input type="text" class="form-control" name="<?= 'email' . ($i + 1); ?>" value="<?= $contacto_cia[$i]['email']; ?>">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                if (sizeof($contacto_cia) < 5) {
                                                    $dif = 5 - sizeof($contacto_cia);
                                                    for ($a = $i; $a < 5; $a++) {
                                                    ?>
                                                        <tr style="background-color: white">
                                                            <td>
                                                                <div class="input-group md-form my-n1">
                                                                    <input type="text" class="form-control" name="<?= 'nombre' . ($a + 1); ?>" onkeyup="mayus(this);">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group md-form my-n1">
                                                                    <input type="text" class="form-control" name="<?= 'cargo' . ($a + 1); ?>" onkeyup="mayus(this);">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group md-form my-n1">
                                                                    <input type="text" class="form-control" name="<?= 'tel' . ($a + 1); ?>">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group md-form my-n1">
                                                                    <input type="text" class="form-control" name="<?= 'cel' . ($a + 1); ?>">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group md-form my-n1">
                                                                    <input type="text" class="form-control" name="<?= 'email' . ($a + 1); ?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <hr>
                                    <button type="submit" style="width: 100%" data-toggle="tooltip" data-placement="bottom" title="Previsualizar" class="btn dusty-grass-gradient btn-lg" value="">Previsualizar Edición &nbsp;<i class="fa fa-check" aria-hidden="true"></i></button>
                                    <hr>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

        <script>
            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>