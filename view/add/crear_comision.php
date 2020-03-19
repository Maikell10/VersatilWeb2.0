<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
require_once '../../Controller/Poliza.php';

$cia = $obj->get_element('dcia', 'nomcia');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\..\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\..\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div>
        <div class="card">


            <div class="card-header p-5 animated bounceInDown">
                <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                    <- Regresar</a> <br><br>
                        <div class="ml-5 mr-5 text-center">
                            <h1 class="font-weight-bold"><i class="fas fa-search-plus" aria-hidden="true"></i> Seleccione Compañía Emisora</h1>
                        </div>
                        <br>

                        <div class="col-md-8 mx-auto">
                            <form action="f_product.php" class="form-horizontal" method="POST">
                                <div class="form-row  col-md-8 m-auto">
                                    <label>Seleccione una Cía:</label>
                                    <select class="form-control selectpicker" name="estructura_neg" id="estructura_neg" data-style="btn-white" required onchange="document.location.href=this.value" data-live-search="true">
                                        <option value="">Seleccione la Compañía</option>
                                        <?php
                                        for ($i = 0; $i < sizeof($cia); $i++) {
                                        ?>
                                            <option value="<?= 'cant_poliza.php?cia=' . $cia[$i]["idcia"]; ?>"><?= ($cia[$i]["nomcia"]); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>