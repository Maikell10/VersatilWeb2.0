<?php require_once '../../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../../login.php");
    exit();
}
$id_poliza = $_GET['id_poliza'];
$cond = $_GET['cond'];
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
                <div class="ml-5 mr-5 text-center">
                    <h1 class="font-weight-bold"><i class="fas fa-plus" aria-hidden="true"></i> Subir PDF de la P&oacute;liza</h1>
                </div>
                <br>

                <div class="col-md-10 mx-auto">

                    <center>
                        <form class="md-form col-md-4" action="../save.php" method="post" enctype="multipart/form-data">
                            <div class="file-field big">
                                <a class="btn-floating btn-lg red lighten-1 mt-0 float-left">
                                    <i class="fas fa-paperclip" aria-hidden="true"></i>
                                    <input type="file" id="archivo" name="archivo" accept="application/pdf" required>
                                </a>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Eliga un archivo PDF" disabled>
                                </div>
                            </div>

                            <button class="btn dusty-grass-gradient font-weight-bold btn-rounded">Subir Archivo <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></button>
                            <input type="text" name="id_poliza" value="<?= $id_poliza; ?>" hidden>
                            <input type="text" class="form-control" name="cond" value="<?= $cond; ?>" hidden>
                        </form>
                    </center>

                </div>
                <br><br><br>
            </div>

        </div>





        <?php require_once dirname(__DIR__) . '\..\layout\footer_b.php'; ?>

        <?php require_once dirname(__DIR__) . '\..\layout\footer.php'; ?>

        <script src="../../assets/view/b_poliza.js"></script>
</body>

</html>