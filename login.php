<?php require_once dirname(__DIR__) . '\versatil\constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
    header("Location: view/");
}
DEFINE('DS', DIRECTORY_SEPARATOR);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'versatil' . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>
    <?php require_once dirname(__DIR__) . DS . 'versatil' . DS . 'layout' . DS . 'navigation.php'; ?>

    <br><br><br><br><br><br>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-8 mx-auto">
                <?php
                if (isset($_GET['m'])) {
                    if ($_GET['m'] == '1') { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Clave Incorrecta</strong>
                        </div>
                    <?php }
                    if ($_GET['m'] == '2') { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Usuario Incorrecto</strong>
                        </div>
                    <?php }
                    if ($_GET['m'] == '3') { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Aún no posee usuario activado</strong>
                        </div>
                    <?php }
                    if ($_GET['m'] == '4') { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Registrado exitosamente, debe esperar un período de 24h para activación</strong>
                        </div>
                    <?php }
                    if ($_GET['m'] == '5') { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>No Posee un Asesor Asociado a la Cédula que ingresó</strong>
                        </div>
                <?php }
                } ?>
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title text-center font-weight-bold">Ingrese al Sistema de Versatil Seguros</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <form action="<?= constant('URL') . 'Controller/Login.php'; ?>">
                                    <div class="md-form">
                                        <div class="input-group">
                                            <i class="fas fa-user fa-2x cyan-text pr-3"></i>
                                            <input type="text" class="form-control" placeholder="Usuario..." name="username" required autofocus />
                                        </div>
                                    </div>

                                    <div class="md-form">
                                        <div class="input-group">
                                            <i class="fas fa-lock fa-2x cyan-text pr-3"></i>
                                            <input type="password" placeholder="Clave..." class="form-control" name="password" required />
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn blue-gradient btn-block my-4  rounded-pill">Login</button>
                                    </div>
                                </form>

                                <h5 class="text-center">No tienes una cuenta? <a href="<?= constant('URL') . 'singup.php'; ?>" class="text-danger font-weight-bold">Registrate</a></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <?php require_once dirname(__DIR__) . DS . 'versatil' . DS . 'layout' . DS . 'footer.php'; ?>
</body>

</html>