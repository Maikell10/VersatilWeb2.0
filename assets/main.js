$(document).ready(function () {

    $('.modal').modal('hide');
    $('.alert').alert();
    $('.dropdown-toggle').dropdown();
    $('.mdb-select').materialSelect();

    $('.first-button').on('click', function () {

        $('.animated-icon1').toggleClass('open');
    });

    const headerload = document.getElementById("headerload");
    const tablaLoad = document.getElementById("tablaLoad");
    const carga = document.getElementById("carga");
    if (carga != null) {
        setTimeout(() => {
            carga.className = 'd-none';
            headerload.removeAttribute("hidden");
            tablaLoad.removeAttribute("hidden");
            window.scroll(0,0)
        }, 1000);
    }

    $('form').on('submit',function() {
        document.body.style.cursor = 'wait';
        $('#load').removeAttr('hidden');
        $('#load1').removeAttr('hidden');
    });

});

$('#logout').click(function (e) {
    //Cancela el evento, en este caso la acción de redireccionar
    e.preventDefault();
    //Redireccionamos la página
    $(location).attr('href', '../logout.php');
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

$('.datepicker').pickadate({
    // Strings and translations
    monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Augosto', 'Septiembre', 'Octubre',
        'Noviembre', 'Diciembre'],
    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
    weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado'],
    weekdaysShort: ['Dom', 'Lun', 'Mart', 'Mierc', 'Jue', 'Vie', 'Sab'],
    showMonthsShort: undefined,
    showWeekdaysFull: undefined,

    // Buttons
    today: 'Hoy',
    clear: 'Borrar',
    close: 'Cerrar',

    // Accessibility labels
    labelMonthNext: 'Próximo Mes',
    labelMonthPrev: 'Mes Anterior',
    labelMonthSelect: 'Seleccione un Mes',
    labelYearSelect: 'Seleccione un Año',

    // Formats
    dateFormat: 'dd-mm-yyyy',
    format: 'dd-mm-yyyy',
    formatSubmit: 'yyyy-mm-dd',

    // Editable input
    //editable: true,

    // Dropdown selectors
    selectYears: true,
    selectMonths: true,
});
