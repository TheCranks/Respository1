var dtEs = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ning&uacute;n dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "&Uacute;ltimo",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};
$(document).ready(function(){

    $(".input-number").keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true ||
            e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }

        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });



});

function openBlob(blob) {
    if (window.navigator && window.navigator.msSaveOrOpenBlob)
        window.navigator.msSaveOrOpenBlob(blob);
    else
        window.open(window.URL.createObjectURL(blob));
}

function alertError(msj){
    toastr.error(msj, 'Error!',{positionClass:'toast-bottom-right'});
}

function alertSuccess(msj){
    toastr.success(msj, 'Exitoso',
        {positionClass:'toast-bottom-right'});
}

function correoValido(correo){
    emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
    return emailRegex.test(correo);
}


function validaCampos(){
    var band = true;
    $('.requerido').each(function(){
        if ($(this).val()==''){
            band = false;
        }
    });
    if (!band){
        alertError('Complete los campos que son requeridos.');
    }
    return band;
}

function formatNumber(num) {
    var result = "";
    $.each(num.split(""), function (index, caracter) {
        if (!(caracter == "C" || caracter == "$" || caracter == ",")) {
            result += caracter;
        }
    })
    return result;
}



//Formatea un valor numerico con 2 decimales
function formatCurrency(num)
{
    num = num.toString().replace(/$|,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + ',' +
            num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + num + '.' + cents);
}

//redirecciona al index y carga el menu correspondiente a cada sistema
function irSistema(sistema){
    location.href = sistema;
}

