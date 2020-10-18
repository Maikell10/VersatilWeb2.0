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

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'navigation.php'; ?>
    <br><br><br><br><br><br>


    <div class="card">
        <div class="card-header p-5">

            <?php
            $file_headers = @get_headers(constant('URL') . 'assets/img/perfil/' . $_SESSION['seudonimo'] . '.jpg');
            if ($file_headers[0] == 'HTTP/1.1 200 OK') { ?>
                <div class="text-center">
                    <img src="<?= constant('URL') . 'assets/img/perfil/' . $_SESSION['seudonimo'] . '.jpg'; ?>" class="rounded-circle z-depth-1 mt-n3" height="250" alt="avatar" />
                </div>
            <?php } else { ?>
                <div class="text-center">
                    <img src="<?= constant('URL') . 'assets/img/perfil/user.png'; ?>" class="rounded-circle z-depth-1 mt-n3" height="250" />
                </div>
            <?php } ?>

            <?php
            //solo par servidor versatil
            /*
            if ($user[0]['avatar'] == 1) { ?>
                <div class="text-center">
                    <img src="<?= constant('URL') . 'assets/img/perfil/' . $_SESSION['seudonimo'] . '.jpg'; ?>" class="rounded-circle z-depth-1 mt-n3" height="250" alt="avatar" />
                </div>
            <?php } else { ?>
                <div class="text-center">
                    <img src="<?= constant('URL') . 'assets/img/perfil/user.png'; ?>" class="rounded-circle z-depth-1 mt-n3" height="250" />
                </div>
            <?php }*/ ?>



            <h1 class="text-center font-weight-bold">
                <?= $user[0]['nombre_usuario'] . " " . $user[0]['apellido_usuario']; ?> <i class="fas fa-user pr-2 cyan-text"></i>
            </h1>
            <h5 class="text-center font-weight-bold">
                <?php
                if ($user[0]['id_permiso'] == 1) {
                    echo 'Administrador';
                }
                if ($user[0]['id_permiso'] == 2) {
                    echo 'Usuario';
                }
                if ($user[0]['id_permiso'] == 3) {
                    if ($user[0]['carga'] == 0) {
                        echo 'Asesor';
                    }
                    if ($user[0]['carga'] == 1) {
                        echo 'Asesor con Carga';
                    }
                }
                ?>
            </h5>

            <?php if ($user[0]['id_permiso'] == 3) { ?>
                <h5 class="text-center font-weight-bold text-success">Código Seleccionado: <?= $user[0]['cod_vend']; ?></h5>
            <?php } ?>

            <hr />

            <div class="text-center table-responsive-xl col-xl-6 mx-auto">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="blue-gradient text-white">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>ID</th>
                            <th>Seudónimo</th>
                            <th>Z Producc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="white">
                            <td><?= $user[0]['nombre_usuario']; ?></td>
                            <td><?= $user[0]['apellido_usuario']; ?></td>
                            <td><?= $user[0]['cedula_usuario']; ?></td>
                            <td><?= $user[0]['seudonimo']; ?></td>
                            <td><?= $user[0]['z_produccion']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if ($user[0]['id_permiso'] == 3) { ?>
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
                </div>
            <?php } ?>

            <hr />
            <br>

            <div class="description text-center">
                <form class="" enctype="multipart/form-data" action="" method="POST">
                    <label class="form-control h4 font-weight-bold col-md-5 mx-auto">Cargue o Actualice su Foto de Perfil</label>
                    <input name="uploadedfile" type="file" class="form-group btn btn-outline-info">
                    <div class="">
                        <input type="submit" value="Subir archivo" class="form-group btn blue-gradient">
                    </div>
                </form>
            </div>

            <br>

            <?php
            $uploadedfileload = "true";
            $uploadedfile_size = $_FILES['uploadedfile']['size'];
            ?>
            <h5 class="text-center"><?= $_FILES['uploadedfile']['name']; ?></h5>
            <?php

            if ($_FILES['uploadedfile']['size'] > 20000000) {
                $msg = $msg . "El archivo es mayor que 200KB, debes reduzcirlo antes de subirlo<BR>";
                $uploadedfileload = "false";
            }

            if (!($_FILES['uploadedfile']['type'] == "image/jpeg" or $_FILES['uploadedfile']['type'] == "image/jpeg" or $_FILES['uploadedfile']['type'] == "image/png")) {
                $msg = $msg . " Tu archivo tiene que ser JPG o PNG. Otros archivos no son permitidos.<BR> En lo posible ser imágen cuadrada (preferiblemente 400 x 400)";
                $uploadedfileload = "false";
            }

            $file_name = $user[0]['seudonimo'] . ".jpg";
            $add = "../assets/img/perfil/$file_name";
            if ($uploadedfileload == "true") {

                if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $add)) {
                    //solo para el servidor versatil
                    $obj->update_user_profile($_SESSION['id_usuario']);
            ?>
                    <h5 class="text-center"><?= " Ha sido subido satisfactoriamente"; ?></h5>
                <?php

                } else {
                ?>
                    <h5 class="text-center"><?= "Error al subir el archivo"; ?></h5>
                <?php
                }
            } else {
                ?>
                <h5 class="text-center"><?= $msg; ?></h5>
            <?php } ?>

            <hr>

            <?php if ($user[0]['cedula_usuario'] == '20127247') { ?>
                <div class="text-center">
                    <h3>Descargar Todos los PDF</h3>
                    <a href="downloadfull.php" class="btn cloudy-knoxville-gradient btn-rounded" target="_blank"><img src="../assets/img/pdf-logo.png" width="60" alt=""></a>
                </div>
            <?php } ?>



        </div>
    </div>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer_b.php'; ?>

    <?php require_once dirname(__DIR__) . DS . 'layout' . DS . 'footer.php'; ?>

    <script>
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
                                        location.reload();
                                    }else{
                                        location.reload();
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