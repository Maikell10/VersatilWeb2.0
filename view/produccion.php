<?php require_once '../constants.php';
session_start();
if (isset($_SESSION['seudonimo'])) {
} else {
    header("Location: ../login.php");
    exit();
}
require_once '../Model/Poliza.php';

$obj = new Poliza();
$fhoy = date("Y-m-d");
$tarjeta = $obj->get_tarjeta_venc($fhoy);
$contN = sizeof($tarjeta);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . '\layout\header.php'; ?>
</head>

<body>

    <?php require_once dirname(__DIR__) . '\layout\navigation.php'; ?>
    <br><br><br><br><br><br>

    <div class="card">

        <div class="card-header p-5 animated bounceInDown">
            <a href="javascript:history.back(-1);" data-toggle="tooltip" data-placement="right" title="Ir la página anterior" class="btn blue-gradient btn-rounded ml-5">
                <- Regresar</a> <div class="ml-5 mr-5">
                    <?php
                    if ($contN != 0) {
                    ?>
                        <span data-toggle="modal" data-target="#tarjetaV">
                            <a data-toggle="tooltip" data-placement="top" title="Ver Tarjeta de Crédito/Débido vencida" class="btn peach-gradient btn-rounded text-white float-right" data-toggle="modal" data-target="#tarjetaV">
                                <p class="h5"><i class="fa fa-bell" aria-hidden="true"></i> <?= $contN; ?></p>
                            </a>
                        </span>
                    <?php
                    }
                    ?>
        </div>

        <br><br>

        <div class="ml-5 mr-5">
            <div class="col-md-auto col-md-offset-2 hover-collapse">
                <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">Producción (Listados)</a></h2>
            </div>
            <br><br>

            <div class="collapse" id="collapse1">
                <div class="card-deck">
                    <div class="card bg-info mb-3">
                        <a href="b_poliza.php" class="hoverable">
                            <div class="card-body">
                                <h5 class="card-title text-white">Pólizas</h5>
                            </div>
                        </a>
                    </div>

                    <div class="card bg-info mb-3">
                        <a href="b_pendientes.php" class="hoverable">
                            <div class="card-body">
                                <h5 class="card-title text-white">Pólizas Pendientes</h5>
                            </div>
                        </a>
                    </div>
                </div>

                <?php if ($_SESSION['id_permiso'] != 3) { ?>
                    <div class="card-deck">
                        <div class="card bg-info mb-3">
                            <a href="b_comp.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Compañias</h5>
                                </div>
                            </a>
                        </div>
                        <div class="card bg-info mb-3">
                            <a href="estructura_n.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Estructura de Negocios</h5>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card-deck">
                        <div class="card bg-info mb-3">
                            <a href="b_f_product.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Pólizas Fecha Producción</h5>
                                </div>
                            </a>
                        </div>
                        <div class="card bg-info mb-3">
                            <a href="b_cliente.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Clientes</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <br>

            <?php if ($_SESSION['id_permiso'] != 3) { ?>
                <div class="col-md-auto col-md-offset-2 hover-collapse">
                    <h2 class="font-weight-bold"><a class="dropdown-toggle text-black" data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2">Producción (Carga)</a></h2>
                </div>
                <br><br>

                <div class="collapse" id="collapse2">
                    <div class="card-deck">
                        <div class="card text-white bg-info mb-3">
                            <a href="add/crear_poliza.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Póliza Nueva</h5>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card-deck">
                        <div class="card text-white bg-info mb-3">
                            <a href="add/crear_asesor.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Asesor, Ejecutivo, Vendedor o Líder de Proyecto</h5>
                                </div>
                            </a>
                        </div>
                        <div class="card text-white bg-info mb-3">
                            <a href="add/crear_compania.php" class="hoverable">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Compañía Nueva</h5>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            <?php } ?>

            <br>
        </div>

    </div>



    <?php require_once dirname(__DIR__) . '\layout\footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . '\layout\footer.php'; ?>

    <!-- Modal TARJETA -->
    <div class="modal fade" id="tarjetaV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Tarjeta(s) de Crédito / Débito vencida(s)</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-auto col-md-offset-2">
                        <h3 class="title text-warning">La(s) siguientes tarjetas se encuentran vencidas o próximas a vencer</h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="blue-gradient text-white">
                                <tr>
                                    <th hidden>id</th>
                                    <th>Nº Tarjeta</th>
                                    <th>CVV</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Nombre titular</th>
                                    <th>Banco</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($tarjeta); $i++) {
                                    $fechaV = date("d/m/Y", strtotime($tarjeta[$i]['fechaV']));
                                ?>
                                    <tr style="cursor:pointer">
                                        <td hidden><?= $tarjeta[$i]['idrecibo']; ?></td>
                                        <td><?= $tarjeta[$i]['n_tarjeta']; ?></td>
                                        <td><?= $tarjeta[$i]['cvv']; ?></td>
                                        <td><?= $fechaV; ?></td>
                                        <td><?= $tarjeta[$i]['nombre_titular']; ?></td>
                                        <td><?= $tarjeta[$i]['banco']; ?></td>
                                        <td class="text-center"><a href="b_polizaT.php?id_tarjeta=<?= $tarjeta[$i]['id_tarjeta']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver Pólizas" class="btn blue-gradient btn-sm"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>