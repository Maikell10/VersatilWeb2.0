$(document).ready(function () {
    alertify.defaults.theme.ok = "btn blue-gradient";
    alertify.defaults.theme.cancel = "btn young-passion-gradient text-white";
    alertify.defaults.theme.input = "form-control";

    var today = new Date();
    $('#anio').val(today.getFullYear());
    $('#anio').change();

    $('#anioC').val(today.getFullYear()-1);
    $('#anioC').change();


    if ($("#tableGPC").length > 0) {
        $('#tableGPC').DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: 50,
        });
        $('.dataTables_length').addClass('bs-select');
    }

});