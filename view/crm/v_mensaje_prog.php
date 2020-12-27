<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../../Controller/Poliza.php';

$mensaje_c1 = $obj->get_element('mensaje_c1','created_at');
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

            <div id="carga" class="d-flex justify-content-center align-items-center">
                <div class="spinner-grow text-info" style="width: 7rem; height: 7rem;"></div>
            </div>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown" id="headerload" hidden="true">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="row ml-5 mr-5">
                                <h1 class="font-weight-bold ">Mensajes Programados de Cumpleaños a Clientes</h1>
                            </div>
                </div>
                <hr />


                <div class="card-body p-5 animated bounceInUp" id="tablaLoad" hidden="true">


                    <?php if ($mensaje_c1 != 0) { ?>

                        <div class="table-responsive col-md-12">
                            <table class="table table-hover table-striped table-bordered" id="tableVB" width="100%">
                                <thead class="blue-gradient text-white text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha Creación del Mensaje</th>
                                        <th>Imagen</th>
                                        <th hidden>created_At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < sizeof($mensaje_c1); $i++) { 
                                        $newCreated = date("Y/m/d", strtotime($mensaje_c1[$i]['created_at']));    
                                    ?> 
                                        <tr style="cursor: pointer">
                                            <td class="text-center align-middle"><?= $mensaje_c1[$i]['id_mensaje_c1']; ?></td>
                                            <td class="text-center align-middle"><?= $newCreated; ?></td>
                                            <td class="text-center">
                                                <img src="<?= constant('URL') . 'assets/img/crm/'. $mensaje_c1[$i]['id_mensaje_c1'] . '.jpg'; ?>" class="z-depth-1 hover-shadow" alt="tarjeta_cumpleaños" style='width: 130px;height: 100px;vertical-align: middle;border-style: none' />
                                            </td>
                                            <td hidden><?= $mensaje_c1[$i]['created_at']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                                <tfoot class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha Creación de GC</th>
                                        <th>Cantidad Pagos</th>
                                        <th hidden>created_At</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    
                    <?php } else { ?>
                        <div class="col-md-auto col-md-offset-2 text-center">
                            <h2 class="title text-danger">No se encuentran Mensajes Programados de Cumpleaños a Clientes</h2>
                        </div>
                    <?php } ?>


                </div>

                <!--   TABLA PARA USUARIOS QUE SON ASESORES   -->
            <?php }
            if ($_SESSION['id_permiso'] == 3) {
            } ?>


        </div>





    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . '..' . DS . 'layout' . DS . 'footer.php'; ?>

    <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>