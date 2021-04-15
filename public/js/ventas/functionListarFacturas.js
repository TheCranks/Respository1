var dtFacturas = "";
$(document).ready(function(){
    //cargar datatable\
    listarFacturas();
});

function listarFacturas(){

    $('#tblFacturas').DataTable().destroy();
    dtFacturas = $('#tblFacturas').DataTable({
        "language": dtEs,
        "ajax": {
            "url": "ventas/facturas/listar",
            "type": "POST",
            "dataSrc": function (json) {
                return json;
            }
        },
        columnDefs:[
            {
                targets: [-1],
                "data": null,
                "render": function (data, type, row) {
                    return "<td><button title='Seleccionar' class='btn btn-xs btn-primary btn_imprimir_factura'><i class='fa fa-edit' aria-hidden='true'></i></button> </td>"
                }
            }
        ],
        "order": [[1, "asc"]]
    });
}

$(document).off('click','.btn_imprimir_factura').on('click','.btn_imprimir_factura', function(){
    var data = dtFacturas.row($(this).parents("tr")).data();
    id_factura = data[0];
    bntPrint(id_factura)

});



function bntPrint(factura) {
    $('#iboxfacturas').children('.ibox-content').toggleClass('sk-loading');
    $.ajax({
        type: "POST",
        url: "venta/factura/imprimir",
        data: {"factura": factura},
        xhrFields: {
            responseType: 'blob'
        },
        success: function (response) {
            $('#iboxfacturas').children('.ibox-content').toggleClass('sk-loading');
            openBlob(response);
        },
        error: function (event) {
            $('#iboxfacturas').children('.ibox-content').toggleClass('sk-loading');
            console.log(event);
        }
    });
}