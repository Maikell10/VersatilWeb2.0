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

    <br><br><br><br><br>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title text-center font-weight-bold">Registrate</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <form class="form" method="POST" action="singup1.php">
                                    <div class="md-form">
                                        <div class="input-group">
                                            <i class="fas fa-tag fa-2x cyan-text pr-3"></i>
                                            <input type="text" class="form-control" placeholder="Nombre" name="nombre" autofocus required  onkeyup="mayus(this);"/>
                                        </div>
                                    </div>

                                    <div class="md-form">
                                        <div class="input-group">
                                            <i class="fas fa-tag fa-2x cyan-text pr-3"></i>
                                            <input type="text" class="form-control" placeholder="Apellido" name="apellido" required onkeyup="mayus(this);"/>
                                        </div>
                                    </div>

                                    <div class="md-form">
                                        <div class="input-group">
                                            <i class="fas fa-tag fa-2x cyan-text pr-3"></i>
                                            <input type="text" class="form-control" placeholder="Cedula" name="cedula" required />
                                        </div>
                                    </div>

                                    <div class="md-form">
                                        <div class="input-group">
                                            <i class="fas fa-user fa-2x cyan-text pr-3"></i>
                                            <input type="text" class="form-control" placeholder="Seudónimo" name="seudonimo" required />
                                        </div>
                                    </div>

                                    <div class="md-form">
                                        <div class="input-group">
                                            <i class="fas fa-lock fa-2x cyan-text pr-3"></i>
                                            <input type="password" placeholder="Contraseña" class="form-control" name="password" required />
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn blue-gradient btn-block my-4  rounded-pill">Confirmar</button>
                                    </div>
                                </form>

                                <p class="text-center font-weight-bold">Luego de registrarse debe esperar un período de 24h para activación de usuario</p>
                                <h5 class="text-center">Ya tienes una cuenta? <a href="<?= constant('URL') . 'login.php'; ?>" class="text-danger font-weight-bold"><i class="material-icons" style="font-size: 30px">label_important</i>Ingresa</a></h5>
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

    <?php require_once dirname(__DIR__) . DS . 'versatil' . DS . 'layout' . DS . 'footer.php'; ?>

    <script>
        function mayus(e) {
            e.value = e.value.toUpperCase();
        }
    </script>
</body>

</html>