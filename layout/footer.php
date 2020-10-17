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