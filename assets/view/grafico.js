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
                [13, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: 50,
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#PorRamo").length > 0) {
        $('#PorRamo').DataTable({
            "order": [
                [1, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: -1,
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#PorCia").length > 0) {
        $('#PorCia').DataTable({
            "order": [
                [1, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: -1,
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#PorEje").length > 0) {
        $('#PorEje').DataTable({
            "order": [
                [1, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: -1,
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#ComisionCobr").length > 0) {
        $('#ComisionCobr').DataTable({
            "order": [
                [4, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: -1,
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#ResumenMes").length > 0) {
        $('#ResumenMes').DataTable({
            "order": [
                [4, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: -1,
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#ResumenMes").length > 0) {
        $('#ResumenMes').DataTable({
            "order": [
                [4, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: -1,
        });
        $('.dataTables_length').addClass('bs-select');
    }

    if ($("#ResumenEjec").length > 0) {
        $('#ResumenEjec').DataTable({
            "order": [
                [4, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            pageLength: -1,
        });
        $('.dataTables_length').addClass('bs-select');
    }


    $("#ComparativoG tbody tr").dblclick(function () {
        var mes = $(this).find("td").eq(8).html();
        var anio = $(this).find("td").eq(9).html();
        var ramo = $(this).find("td").eq(10).html();
        var cia = $(this).find("td").eq(11).html();
        var tipo_cuenta = $(this).find("td").eq(12).html();
    
        window.open("../Listados/poliza_uv.php?mes=" + mes + "&anio=" + anio + "&ramo=" + ramo + "&cia=" + cia + "&tipo_cuenta=" + tipo_cuenta , '_blank');
    });

    $("#ComparativoGOld tbody tr").dblclick(function () {
        var mes = $(this).find("td").eq(8).html();
        var anio = $(this).find("td").eq(9).html();
        var ramo = $(this).find("td").eq(10).html();
        var cia = $(this).find("td").eq(11).html();
        var tipo_cuenta = $(this).find("td").eq(12).html();
    
        window.open("../Listados/poliza_uv.php?mes=" + mes + "&anio=" + anio + "&ramo=" + ramo + "&cia=" + cia + "&tipo_cuenta=" + tipo_cuenta , '_blank');
    });

});