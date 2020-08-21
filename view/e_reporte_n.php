<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Controller/Poliza.php';

$id_rep_com = $_POST['id_rep_com'];
$f_rep = $_POST['f_rep'];
$f_pago = $_POST['f_pago'];
$primat_com = $_POST['primat_com'];
$comt = $_POST['comt'];

$id_cia = $_POST['cia'];
$comentario_rep = $_POST['comentario_rep'];

$nomcia = $obj->get_element_by_id('dcia', 'idcia', $id_cia);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="card-header p-5 animated bounceInDown">
                    <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                        <- Regresar</a> <br><br>
                            <div class="ml-5 mr-5">
                                <h1 class="font-weight-bold text-center"><i class="fas fa-user" aria-hidden="true"></i>&nbsp;Previsualizar Edición de Fechas del Reporte de Comisión</h1>
                            </div>
                            <br>

                            <div class="col-md-10 mx-auto">
                                <form class="form-horizontal" id="frmnuevo" action="e_reporte_n.php" method="POST">
                                    <div class="table-responsive-xl">
                                        <table class="table" width="100%">
                                            <thead class="heavy-rain-gradient">
                                                <tr>
                                                    <th class="text-black font-weight-bold">Fecha Hasta Reporte</th>
                                                    <th class="text-black font-weight-bold">Fecha Pago GC</th>
                                                    <th class="text-black font-weight-bold">Prima Sujeta a Comisión Total</th>
                                                    <th class="text-black font-weight-bold">Comisión Total</th>
                                                    <th hidden>id reporte</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="f_rep" readonly="readonly" value="<?= $f_rep; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="f_pago" readonly="readonly" value="<?= $f_pago; ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="primat_com" readonly="readonly" value="<?= number_format($primat_com, 2); ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="comt" readonly="readonly" value="<?= number_format($comt, 2); ?>">
                                                        </div>
                                                    </td>
                                                    <td hidden><input type="text" class="form-control" name="id_rep_com" value="<?= $id_rep_com; ?>"></td>
                                                </tr>

                                                <tr class="heavy-rain-gradient">
                                                    <th class="text-black font-weight-bold">Cía</th>
                                                    <th colspan="3" class="text-black font-weight-bold">Comentarios</th>
                                                </tr>
                                                <tr style="background-color: white">
                                                    <td>
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="idcia" readonly="readonly" value="<?= $nomcia[0]['nomcia']; ?>">
                                                        </div>
                                                    </td>
                                                    <td colspan="3">
                                                        <div class="input-group md-form my-n1 grey lighten-2">
                                                            <input type="text" class="form-control" name="comentario_rep" readonly="readonly" value="<?= $comentario_rep; ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                    <center>
                                        <a name="enlace" href="e_reporte_nn.php?id_rep_com=<?= $id_rep_com; ?>&f_rep=<?= $f_rep; ?>&f_pago=<?= $f_pago; ?>&primat_com=<?= $primat_com; ?>&comt=<?= $comt; ?>&id_cia=<?= $id_cia; ?>&comentario_rep=<?= $comentario_rep; ?>" class="btn blue-gradient btn-lg btn-rounded">Confirmar</a>
                                    </center>
                                </form>
                            </div>

                            <br><br><br>
                </div>
            <?php } ?>
        </div>





        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

        <script>

        </script>

        <script src="../assets/view/b_poliza.js"></script>
</body>

</html>