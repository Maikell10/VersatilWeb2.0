<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../../Controller/Poliza.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'header.php'; ?>
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
                                <?php if (isset($_GET['cond'])) { ?>
                                    <h1 class="font-weight-bold text-center"><i class="fas fa-check-square text-success" aria-hidden="true"></i>&nbsp;Agregada con Éxito</h1>
                                <?php } ?>
                                <h1 class="font-weight-bold text-center"><i class="fas fa-briefcase" aria-hidden="true"></i>&nbsp;Añadir Nueva Compañía</h1>
                            </div>
                            <br>

                            <div class="col-md-8 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" autocomplete="off" action="cia.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="blue-gradient text-white">
                                                <tr>
                                                    <th>Nombre de Cía *</th>
                                                    <th>RUC/RIF</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="nombre_cia" name="nombre_cia" required data-toggle="tooltip" data-placement="bottom" title="Campo Obligatorio" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="rif" name="rif">
                                                        </div>
                                                    </td>
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
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="nombre1" name="nombre1" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cargo1" name="cargo1" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="tel1" name="tel1">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cel1" name="cel1">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="email" class="form-control" id="email1" name="email1">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="nombre2" name="nombre2" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cargo2" name="cargo2" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="tel2" name="tel2">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cel2" name="cel2">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="email" class="form-control" id="email2" name="email2">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="nombre3" name="nombre3" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cargo3" name="cargo3" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="tel3" name="tel3">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cel3" name="cel3">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="email" class="form-control" id="email3" name="email3">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="nombre4" name="nombre4" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cargo4" name="cargo4" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="tel4" name="tel4">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cel4" name="cel4">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="email" class="form-control" id="email4" name="email4">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="nombre5" name="nombre5" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cargo5" name="cargo5" onkeyup="mayus(this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="tel5" name="tel5">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="text" class="form-control" id="cel5" name="cel5">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1">
                                                            <input type="email" class="form-control" id="email5" name="email5">
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





        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

        <script>
            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
        </script>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>