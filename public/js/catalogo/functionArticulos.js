var dtArticulos = "";
$(document).ready(function () {

    cargarArticulos();

    $("#txtExistenciaMinima,#txtPrecioCosto, #txtPrecioVenta, #txtExistencias").blur(function(){
        if ($(this).val()!=""){
            $(this).val(formatCurrency($(this).val()));
        }
    });



    $("#btnEliminarArticulo").click(function () {
        swal({
            title: "Esta seguro?",
            text: "Esta seguro de eliminar este registro?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar!",
            closeOnConfirm: false
        }, function () {
            var idArticulo = $("#txtIdArticulo").val();
            $.ajax({
                type: "POST",
                url: "catalogo/articulos/eliminar",
                dataType: "JSON",
                data: {
                    'id_articulo': idArticulo

                },
                success: function (response) {
                    if (response.error) {
                        alertError(response.mensaje);
                    }
                    else {
                        swal("Eliminado!", response.mensaje, "success");
                        setTimeout(function () {
                            location.reload(true);
                        }, 1000);

                    }


                },
                error: function () {
                    alertError("No se puedo comunicar con el servidor");
                }
            });

        });

    });

    $("#btnGuardaArticulo").click(function () {
        var descripcion = $("#txtNombreArticulo").val();
        var preciocosto = $("#txtPrecioCosto").val();
        var precioVenta = $("#txtPrecioVenta").val();
        var existenciaMinima = $("#txtExistenciaMinima").val();
        var unidadMedida = $("#selUnidadMedida").val();
        var presentacion = $("#selPresentacion").val();
        var existencia = $("#txtExistencias").val();
        var idArticulo = $("#txtIdArticulo").val();

        if (descripcion != "") {
            swal({
                title: "Esta seguro?",
                text: "Esta seguro de grabar este registro?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si, Guardar!",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: "POST",
                    url: "catalogo/articulos/guardar",
                    dataType: "JSON",
                    data: {
                        'descripcion': descripcion,
                        'preciocosto': preciocosto,
                        'precioVenta': precioVenta,
                        'existenciaMinima': existenciaMinima,
                        'unidadMedida': unidadMedida,
                        'presentacion': presentacion,
                        'existencia': existencia,
                        'idArticulo': idArticulo

                    },
                    success: function (response) {
                        if (response.error) {
                            alertError(response.mensaje);
                        }
                        else {
                            swal("Guardado!", response.mensaje, "success");
                            setTimeout(function () {
                                location.reload(true);
                            }, 1000);

                        }


                    },
                    error: function () {
                        alertError("No se puedo comunicar con el servidor");
                    }
                });
            });
        }
        else {
            alertError("Debe ingresar un nombre de articulo");
        }
    });
});

function cargarArticulos() {
    $('#tbl_articulos').DataTable().destroy();
    dtArticulos = $('#tbl_articulos').DataTable({
        "language": dtEs,
        "ajax": {
            "url": "catalogo/articulos/listar",
            "type": "POST",
            "dataSrc": function (json) {
                return json;
            }
        },
        columnDefs: [{
            targets:[8,9],
            visible:false
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

$(document).off('click', '.btn_select_articulo').on('click', '.btn_select_articulo', function () {
    var data = dtArticulos.row($(this).parents("tr")).data();
    $("#txtNombreArticulo").val(data[1]);
    $("#txtPrecioCosto").val(data[6]);
    $("#txtPrecioVenta").val(data[5]);
    $("#txtExistenciaMinima").val(data[7]);
    $("#selUnidadMedida").val(data[8]);
    $("#selPresentacion").val(data[9]);
    $("#txtExistencias").val(data[4]);
    $("#txtIdArticulo").val(data[0]);

    alertSuccess('Proceda a editar este registro.');
});