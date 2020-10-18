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

$user = $obj->get_element_by_id('usuarios', 'id_usuario', $_SESSION['id_usuario']);
//print_r($user);

if ($user[0]['id_permiso'] == 3) {
    $cods_asesor = $obj->get_cod_a_by_user($user[0]['cedula_usuario']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'header.php'; ?>
</head>

<body>

    <br><br><br><br><br><br>

    <div class="card">
        <div class="card-header p-5">
            <h1 class="text-center font-weight-bold">
                Bienvenido <?= $_SESSION['seudonimo']; ?> <i class="fas fa-user pr-2 cyan-text"></i></h1>
            <hr />

            <?php if ($cods_asesor != 0) { ?>
                <h5 class="text-center font-weight-bold text-success">Código Seleccionado: <?= $user[0]['cod_vend']; ?></h5>
                <div class="text-center table-responsive-xl col-xl-6 mx-auto">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="blue-gradient text-white">
                            <tr>
                                <th>Código de Asesor Asociado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cods_asesor as $cods_asesor) { ?>
                                <tr class="white">
                                    <td class="font-weight-bold"><?= $cods_asesor['cod']; ?></td>
                                    <td class="p-0 m-0"><button class="btn dusty-grass-gradient" onclick="selectCod('<?= $cods_asesor['cod']; ?>')"> Seleccionar</button></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <a href="./" class="btn blue-gradient btn-floating" data-toggle="tooltip" data-placement="bottom" title="Continuar"><i class="fas fa-home fa-2x"></i></a>
                </div>
            <?php } ?>
        </div>
    </div>
    


    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>
    
    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'info',
                title: 'Atención',
                text: 'Ud tiene más de un Código de asesor asociado a su perfil. Puede cambiar el Código activo ahora o más adelante en su perfil!',
            })
        });

        function selectCod(cod) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Esta Seguro?',
                text: "Va a cambiar el código de asesor a continuación!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        data: "cod_vend=" + cod,
                        url: "../procesos/editarUsuario.php?id_user=" + <?= $user[0]['id_usuario'];?>,
                        success: function(r) {
                            datos = jQuery.parseJSON(r);
                            if (!datos) {
                                swalWithBootstrapButtons.fire(
                                    'Error!',
                                    'No se ha cambiado su código de asesor',
                                    'error'
                                )
                            } else {
                                swalWithBootstrapButtons.fire(
                                    'Éxito!',
                                    'Ha cambiado su código de asesor correctamente',
                                    'success'
                                ).then((result) => {
                                    /* Read more about isConfirmed, isDenied below */
                                    if (result.isConfirmed) {
                                        window.location.replace("./");
                                    }else{
                                        window.location.replace("./");
                                    }
                                })
                                
                            }
                        }
                    });
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelado',
                        '',
                        'error'
                    )
                }
            })
        }
    </script>
</body>

</html>