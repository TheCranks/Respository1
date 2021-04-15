var detalleFactura=[];
$(document).ready(function(){
    cargarArticulos();
    $("#selIVA").change(function(){
        calculaFactura();
    });

    $("#btnGuardarCompra").click(function(){
        var id_proveedor =$("#selProveedor").val();

        if (detalleFactura.length>0){
            $.ajax({
                type: "POST",
                url: "compra/detalle",
                dataType: "JSON",
                data: {
                    'id_proveedor': id_proveedor,
                    'detalleFactura':detalleFactura
                },
                success: function (response) {
                    if (response.error){
                        alertError(response.mensaje);
                    }
                    else{
                        alertSuccess(response.mensaje);
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
    });
});


$(document).off('click','.btn_select_articulo').on('click', '.btn_select_articulo', function(){

    var id_factura =$("#txtIdFactura").val();
    var id_cliente = $("#selCliente").val();
    var id_articulo = $(this).parents('tr').find('td').eq(0).html();
    var nombre_articulo = $(this).parents('tr').find('td').eq(1).html();
    var presentacion = $(this).parents('tr').find('td').eq(2).html();
    var precio = $(this).parents('tr').find('td').eq(5).find('.precio_venta').val();
    var cantidad = $(this).parents('tr').find('td').eq(6).find('.cantVenta').val();
    var existencias = $(this).parents('tr').find('td').eq(4).html();
    var precio_total = parseInt(precio)*parseInt(cantidad);
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

});

function calculaFactura(){
    detalleFactura =[];

     var subtotal = 0;
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
                    element.precio_venta = "<td><input type='number' class='precio_venta' value='"+element.precio_venta+"'></td>"
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

