var detalleFactura=[];
$(document).ready(function(){
    cargarArticulos();
    $("#selIVA").change(function(){
        calculaFactura();
    });

    $("#btnImprimirFactura").click(function(){
        var id_cliente =$("#selCliente").val();
        var id_factura = $("#txtIdFactura").val();
        var iva = $("#selIVA").val();

        var total = $("#txtTotal").html();
        var temp = total.replace(",",'');
        var totalFactura = temp.slice(3);
        var pagaCon = $("#txtPagaCon").val();
        if(pagaCon==""){
            pagaCon=0;
        }
        if (parseFloat(totalFactura)>parseFloat(pagaCon)){
            alertError("El monto con el que desea pagar debe ser mayor que el total de la factura.");
            $("#txtVuelto").html('C$ 00.00');
        }
        else{
            pagaCon = parseFloat(pagaCon);
            if (detalleFactura.length>0){
                $.ajax({
                    type: "POST",
                    url: "venta/factura/detalle",
                    dataType: "JSON",
                    data: {
                        'id_cliente': id_cliente,
                        'id_factura': id_factura,
                        'detalleFactura':detalleFactura,
                        'iva':iva,
                        'pagaCon':pagaCon
                    },
                    success: function (response) {
                        if (response.error){
                            alertError(response.mensaje);
                        }
                        else{
                            alertSuccess(response.mensaje);
                            bntPrint(response.id_factura);
                            setTimeout(function () {
                                location.reload(true);
                            }, 1000);

                        }


                    },
                    error: function () {
                        alertError("No se puedo comunicar con el servidor");
                    }
                })
            }
            else{
                alertError("Debe agregar un articulo a la factura.");
            }
        }
    });

    $("#txtPagaCon").blur(function(){
        var total = $("#txtTotal").html();
        var temp = total.replace(",",'');
        var totalFactura = temp.slice(3);
        var pagaCon = $(this).val();
        if (parseFloat(totalFactura)>parseFloat(pagaCon)){
            alertError("El monto con el que desea pagar debe ser mayor que el total de la factura.");
            $("#txtVuelto").html('C$ 00.00');
        }
        else{
            vuelto = parseFloat(pagaCon)-parseFloat(totalFactura);
            formatTotal =formatCurrency(vuelto);
            $("#txtVuelto").html('C$ '+formatTotal);
        }
    });
});


function bntPrint(factura) {
    $.ajax({
        type: "POST",
        url: "venta/factura/imprimir",
        data: {"factura": factura},
        xhrFields: {
            responseType: 'blob'
        },
        success: function (response) {
            openBlob(response);
        },
        error: function (event) {
            console.log(event);
        }
    });
}

$(document).off('click','.btn_select_articulo').on('click', '.btn_select_articulo', function(){

    var id_factura =$("#txtIdFactura").val();
    var id_cliente = $("#selCliente").val();
    var id_articulo = $(this).parents('tr').find('td').eq(0).html();
    var nombre_articulo = $(this).parents('tr').find('td').eq(1).html();
    var presentacion = $(this).parents('tr').find('td').eq(2).html();
    var precio = $(this).parents('tr').find('td').eq(5).html();
    var cantidad = $(this).parents('tr').find('td').eq(6).find('.cantVenta').val();
    var existencias = $(this).parents('tr').find('td').eq(4).html();
    var precio_total = parseInt(precio)*parseInt(cantidad);
    if (parseInt(cantidad)<parseInt(existencias)){
        var tr = "<tr> " +
            "<td>"+id_articulo+"</td>" +
            "<td>"+cantidad+"</td> " +
            "<td>"+nombre_articulo+" "+presentacion+"</td>" +
            "<td>"+precio+"</td><td>"+precio_total+"</td>" +
            "<td><button class='btn btn-danger btnEliminaDetalleFactura'><i class='fa fa-trash'></i></button></td>" +
            "</tr>";
        $("#bodyFactura").prepend(tr);
        calculaFactura();
        //cargarArticulos()
        alertSuccess(nombre_articulo+" Agregado Correctamente.");
    }
    else
        alertError("Solicita cantidad mayor a la que hay en existencias.");

});

function calculaFactura(){
    detalleFactura =[];

     var subtotal = 0;
     var iva = $("#selIVA").val();
     var total = 0;
     var formatTotal = 0.0;
     var formatSubTotal = 0.0;
     var id_articulo;
     var cantidad;
     var precio;

    $("#tbl_detalle_factura tbody tr").each(function(index){
        var detalle ={};
       $(this).children('td').each(function(index2){
           switch (index2) {
               case 0:
                   id_articulo = $(this).text();
                   break;
               case 1:
                   cantidad =$(this).text();
                   break;
               case 3:
                   precio =$(this).text();
                   break;

               case 4:
                   subtotal = subtotal+(parseFloat($(this).text()));
                   break;
           }
       });
       detalle = {"id_articulo":id_articulo, "cantidad":cantidad, "precio":precio};
       detalleFactura.push(detalle);
    });

    total = parseFloat(iva)*parseFloat(subtotal);
    formatTotal =formatCurrency(total);
    $("#txtTotal").html('C$ '+formatTotal);
    formatSubTotal = formatCurrency(subtotal);
    $("#txtSubTotal").html('C$ '+formatSubTotal);
}


function cargarArticulos() {
    $('#tbl_articulos').DataTable().destroy();
    dtArticulos = $('#tbl_articulos').DataTable({
        "language": dtEs,
        "ajax": {
            "url": "catalogo/articulos/listar",
            "type": "POST",
            "dataSrc": function (json) {
                json.forEach(function (element) {
                    element.cantidad = "<td><input type='number' class='cantVenta' value='1'></td>"
                }, this);
                return json;
            }
        },

        columns:[
            {"data":"id_articulo"},
            {"data":"nombre_articulo"},
            {"data":"presentacion"},
            {"data":"unidad_medida"},
            {"data":"existencia"},
            {"data":"precio_venta"},
            {"data":"cantidad"},
            {"data":1}
        ]
        ,
        columnDefs: [{
        },
            {
                targets: [-1],
                "data": null,
                "render": function (data, type, row) {
                    return "<td><button title='Seleccionar' class='btn btn-xs btn-primary btn_select_articulo'><i class='fa fa-edit' aria-hidden='true'></i></button> </td>"
                }
            }
        ],
        "order": [[1, "asc"]]
    });
}

$(document).off('click', '.btnEliminaDetalleFactura').on('click', '.btnEliminaDetalleFactura', function(){
    $(this).parents('tr').remove();
    calculaFactura();
});

