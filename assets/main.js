$(document).ready(function () {

    $('.alert').alert();
    $('.dropdown-toggle').dropdown();

    const headerload = document.getElementById("headerload");
    const tablaLoad = document.getElementById("tablaLoad");
    const carga = document.getElementById("carga");
    if (carga != null) {
        setTimeout(() => {
            carga.className = 'd-none';
            headerload.removeAttribute("hidden");
            tablaLoad.removeAttribute("hidden");
        }, 1500);
    }

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
