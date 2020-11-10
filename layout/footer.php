<?php
DEFINE('DS', DIRECTORY_SEPARATOR);
require_once dirname(__DIR__) . DS . 'constants.php'; ?>
<div class="align-items-end">
    <footer class="page-footer">
        <div class="footer-copyright text-center py-3"><?= date('Y') ?>,
            Versatil Seguros S.A.
            <a href="https://www.versatilseguros.com"> Versatil Panamá</a>
        </div>
    </footer>
</div>



<!--Modal Form Login with Avatar Demo-->
<div class="modal fade" id="modalLoginAvatarDemo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
        <!--Content-->
        <div class="modal-content">

            <!--Header-->
            <div class="modal-header">
                <?php
                $file_headers = @get_headers(constant('URL') . 'assets/img/perfil/' . $_SESSION['seudonimo'] . '.jpg');
                if ($file_headers[0] == 'HTTP/1.1 200 OK') { ?>
                    <img src="<?= constant('URL') . 'assets/img/perfil/' . $_SESSION['seudonimo'] . '.jpg'; ?>" class="rounded-circle z-depth-0" height="35" alt="avatar" />
                <?php } else { ?>
                    <img src="<?= constant('URL') . 'assets/img/perfil/user.png'; ?>" class="rounded-circle z-depth-0" />
                <?php } ?>
                <?php
                //solo par servidor versatil
                /*
                if ($user[0]['avatar'] == 1) { ?>
                    <img src="<?= constant('URL') . 'assets/img/perfil/' . $_SESSION['seudonimo'] . '.jpg'; ?>" class="rounded-circle z-depth-0" height="35" alt="avatar" />
                <?php } else { ?>
                    <img src="<?= constant('URL') . 'assets/img/perfil/user.png'; ?>" class="rounded-circle z-depth-0" />
                <?php }*/ ?>
            </div>
            <!--Body-->
            <div class="modal-body text-center mb-1">

                <h5 class="mt-1 mb-2"><?= $user[0]['nombre_usuario'] . " " . $user[0]['apellido_usuario']; ?></h5>

                <?php if ($user[0]['id_permiso'] == 3) {
                    $cods_asesor = $obj->get_cod_a_by_user($user[0]['cedula_usuario']);
                ?>

                    <p class="text-muted">Seleccionar el código asociado</p>

                    <?php foreach ($cods_asesor as $cods_asesor) { ?>
                        <?php if ($user[0]['cod_vend'] == $cods_asesor['cod']) { ?>
                            <a class="btn blue-gradient btn-block"><?= $cods_asesor['cod']; ?> <i class="fas fa-check pr-2"></i></a>
                        <?php } else { ?>
                            <a class="btn blue-gradient btn-block" href="#" data-toggle="modal" data-target="#modalLoginAvatarDemo" onclick="selectCod('<?= $cods_asesor['cod']; ?>')"><?= $cods_asesor['cod']; ?></a>
                    <?php }
                    } ?>


                <?php } ?>
            </div>

        </div>
        <!--/.Content-->
    </div>
</div>
<!--Modal Form Login with Avatar Demo-->



<!--  SCRIPTS  -->
<!-- JQuery -->
<script src="<?= constant('URL') . 'assets/js/jquery-3.4.1.min.js'; ?>"></script>
<!-- DataTables JS -->
<script src="<?= constant('URL') . 'assets/js/addons/datatables.js'; ?>"></script>
<!-- DataTables Select JS -->
<script src="<?= constant('URL') . 'assets/js/addons/datatables-select.js'; ?>"></script>
<!-- Bootstrap tooltips -->
<script src="<?= constant('URL') . 'assets/js/popper.min.js'; ?>"></script>
<!-- Bootstrap core JavaScript -->
<script src="<?= constant('URL') . 'assets/js/bootstrap.js'; ?>"></script>
<!-- Bootstrap Select JavaScript -->
<script src="<?= constant('URL') . 'assets/js/bootstrap-select.js'; ?>"></script>
<!-- MDB core JavaScript -->
<script src="<?= constant('URL') . 'assets/js/mdb.js'; ?>"></script>
<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<!-- Personalize JavaScript -->
<script src="<?= constant('URL') . 'assets/main.js'; ?>"></script>

<!-- Buttons Datatable -->
<script src="<?= constant('URL') . 'assets/js/Buttons-1.5.6/js/dataTables.buttons.min.js'; ?>"></script>
<script src="<?= constant('URL') . 'assets/js/JSZip-2.5.0/jszip.min.js'; ?>"></script>
<script src="<?= constant('URL') . 'assets/js/pdfmake-0.1.36/pdfmake.min.js'; ?>"></script>
<script src="<?= constant('URL') . 'assets/js/pdfmake-0.1.36/vfs_fonts.js'; ?>"></script>
<script src="<?= constant('URL') . 'assets/js/Buttons-1.5.6/js/buttons.html5.min.js'; ?>"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/dataRender/datetime.js"></script>

<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


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
                    url: "<?= constant('URL') . 'procesos/editarUsuario.php?id_user=' . $user[0]['id_usuario']; ?>",
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
                                } else {
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