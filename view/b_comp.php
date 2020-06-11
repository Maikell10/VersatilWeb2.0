<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

require_once '../Model/Poliza.php';

$obj = new Poliza();
$cia = $obj->get_element('dcia', 'nomcia');
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
                    <div class="row ml-5 mr-5">
                        <h1 class="font-weight-bold ">Lista Compañías</h1>
                        <?php if ($_SESSION['id_permiso'] == 1) { ?>
                            <a href="add/crear_compania.php" class="btn blue-gradient ml-auto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nueva Compañía</a>
                        <?php } ?>
                    </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" id="tableA" width="100%">
                    <thead class="blue-gradient text-white text-center">
                        <tr>
                            <th>Nombre</th>
                            <th hidden>id</th>
                            <th>Preferencial</th>
                            <th>F Desde Preferencial (Última)</th>
                            <th>F Hasta Preferencial (Última)</th>
                            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                <th>Preferencial</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < sizeof($cia); $i++) {

                            $f_cia_pref = $obj->get_f_cia_pref($cia[$i]['idcia']);

                            $desde_prefn = date("d/m/Y", strtotime($f_cia_pref[0]['f_desde_pref']));
                            $hasta_prefn = date("d/m/Y", strtotime($f_cia_pref[0]['f_hasta_pref']));

                            if ($desde_prefn == '01/01/1970') {
                                $desde_prefn = null;
                                $hasta_prefn = null;
                            }

                        ?>
                            <tr style="cursor:pointer">
                                <td><?= ($cia[$i]['nomcia']); ?></td>
                                <td hidden><?= $cia[$i]['idcia']; ?></td>
                                <td><?php if ($f_cia_pref[0]['f_desde_pref'] == 0) {
                                        echo "No";
                                    } else {
                                        echo "Sí";
                                    }
                                    ?></td>
                                <td><?= $desde_prefn; ?></td>
                                <td><?= $hasta_prefn; ?></td>
                                <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                    <td style="text-align: center;">
                                        <a data-toggle="tooltip" data-placement="top" title="Añadir Preferencial" href="comp_pref.php?nomcia=<?= $cia[$i]['nomcia']; ?>" class="btn blue-gradient btn-sm btn-rounded"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot class="text-center">
                        <tr>
                            <th>Nombre</th>
                            <th hidden>id</th>
                            <th>Preferencial</th>
                            <th>F Desde Preferencial (Última)</th>
                            <th>F Hasta Preferencial (Última)</th>
                            <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                <th>Preferencial</th>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/b_comp.js"></script>
</body>

</html>