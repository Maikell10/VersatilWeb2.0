<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
DEFINE('DS', DIRECTORY_SEPARATOR);

$pag = 'v_cia';

require_once '../Controller/Poliza.php';

$id_cia = $_GET['id_cia'];

$cia = $obj->get_element_by_id('dcia', 'idcia', $id_cia);
$contacto_cia = $obj->get_element_by_id('contacto_cia', 'id_cia', $cia[0]['idcia']);

//Si es preferencial

$f_cia_pref = $obj->get_f_cia_pref($cia[0]['idcia']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'header.php'; ?>
    <style>
        .alertify .ajs-header {
            background-color: red;
        }
    </style>
</head>

<body>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">


        <div class="card-header p-5 animated bounceInDown">
            <div class="ml-5 mr-5">
                <h1 class="font-weight-bold">Cía: <?= ($cia[0]['nomcia']); ?></h1>
                <h2 class="font-weight-bold">RUC/Rif: <?= $cia[0]['rif']; ?></h2>
            </div>
        </div>

        <div class="card-body p-5 animated bounceInUp" id="tablaLoad">
            <div class="table-responsive-xl">
                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>Nombre Compañía</th>
                        <th>RUC/Rif</th>
                        <th>%Comisión</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= ($cia[0]['nomcia']); ?></td>
                            <td><?= $cia[0]['rif']; ?></td>
                            <td><?= number_format($cia[0]['per_com'], 2) . ' %'; ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-hover table-striped table-bordered" width="100%">
                    <thead class="blue-gradient text-white">
                        <th>Nombre Contacto</th>
                        <th>Cargo</th>
                        <th>Tel</th>
                        <th>Cel</th>
                        <th>E-Mail</th>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < sizeof($contacto_cia); $i++) { ?>
                            <tr>
                                <td><?= $contacto_cia[$i]['nombre']; ?></td>
                                <td><?= utf8_encode($contacto_cia[$i]['cargo']); ?></td>
                                <td><?= $contacto_cia[$i]['tel']; ?></td>
                                <td><?= $contacto_cia[$i]['cel']; ?></td>
                                <td><?= $contacto_cia[$i]['email']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
            <hr>

            <center>
                <a href="b_poliza3.php?id_cia=<?= $cia[0]['idcia']; ?>" data-toggle="tooltip" data-placement="top" title="Ver" class="btn blue-gradient btn-lg">Ver Pólizas Cía &nbsp;<i class="fas fa-eye" aria-hidden="true"></i></a>

                <a href="e_cia.php?id_cia=<?= $cia[0]['idcia']; ?>" data-toggle="tooltip" data-placement="top" title="Editar" class="btn dusty-grass-gradient btn-lg text-center">Editar Cía &nbsp;<i class="fas fa-edit" aria-hidden="true"></i></a>
            </center>

            <hr>

            <?php if ($f_cia_pref[0]['f_desde_pref'] != 0) { ?>
                <div class="col-md-auto col-md-offset-2">
                    <h2 class="title">Fechas en que es preferencial (Mayor a Menor)</h2>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>Fecha Desde Preferencial</th>
                                <th>Fecha Hasta Preferencial</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < sizeof($f_cia_pref); $i++) {
                                $desde_prefn = date("d/m/Y", strtotime($f_cia_pref[$i]['f_desde_pref']));
                                $hasta_prefn = date("d/m/Y", strtotime($f_cia_pref[$i]['f_hasta_pref']));
                            ?>
                                <tr>
                                    <td><?= $desde_prefn; ?></td>
                                    <td><?= $hasta_prefn; ?></td>
                                    <td style="text-align: center;">
                                        <a data-toggle="tooltip" data-placement="top" title="Ver Preferencial" href="v_pref.php?id_cia=<?= $cia[0]['idcia']; ?>&f_desde=<?= $f_cia_pref[$i]['f_desde_pref']; ?>&f_hasta=<?= $f_cia_pref[$i]['f_hasta_pref']; ?>" class="btn dusty-grass-gradient btn-sm btn-rounded"><i class="fas fa-check-circle" aria-hidden="true"></i></a>
                                        <?php if ($_SESSION['id_permiso'] == 1) { ?>
                                            <a onclick="eliminarCiaPref('<?= $cia[0]['idcia']; ?>','<?= $f_cia_pref[$i]['f_desde_pref']; ?>','<?= $f_cia_pref[$i]['f_hasta_pref']; ?>')" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn young-passion-gradient btn-sm btn-rounded text-white"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            <?php } ?>
        </div>


    </div>



    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) .DS. 'layout'.DS.'footer.php'; ?>

    <script src="../assets/view/cia.js"></script>

</body>

</html>