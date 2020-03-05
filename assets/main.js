$(document).ready(function () {

    const tablaLoad = document.getElementById("tablaLoad");
    const carga = document.getElementById("carga");
    //const tablaP = document.getElementById("tablaP");

    setTimeout(() => {
        carga.className = 'd-none';
        tablaLoad.removeAttribute("hidden");
        //tablaP.removeAttribute("hidden");
    }, 1500);


    $('.alert').alert();
    $('.dropdown-toggle').dropdown();

    $('#table').DataTable({
        "order": [
            [0, "desc"]
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "Todos"]
        ],
        columnDefs: [{
            targets: [5,6],
            render: $.fn.dataTable.render.moment('YYYY/MM/DD', 'DD-MM-YYYY'),
        }]
    });
    $('.dataTables_length').addClass('bs-select');

});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

$('#logout').click(function (e) {
    //Cancela el evento, en este caso la acción de redireccionar
    e.preventDefault();
    //Redireccionamos la página
    $(location).attr('href', '../logout.php');
});
