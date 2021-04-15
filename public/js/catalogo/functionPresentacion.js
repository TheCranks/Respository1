var dtPresentacion= "";
$(document).ready(function(){
    cargarPresentaciones();

    $("#btnGuardaPresentacion").click(function(){
        var descripcion = $("#txtNombrePresentacion").val();
        var id_presentacion = $("#txtIdPresentacion").val();
        if (descripcion!=""){
            $.ajax({
                type: "POST",
                url: "catalogo/presentacion/guardar",
                dataType: "JSON",
                data: {
                    'descripcion': descripcion,
                    'id_presentacion':id_presentacion

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
            });
        }
        else
            alertError("Debe ingresar una descripcion")
    });


});

$(document).off('click','.btn_sel_presentacion').on('click', '.btn_sel_presentacion', function(){
    var data = dtPresentacion.row($(this).parents("tr")).data();
    $("#txtIdPresentacion").val(data[0]);
    $("#txtNombrePresentacion").val(data[1]);
    alertSuccess('Proceda a editar este registro.');
});


function cargarPresentaciones(){
    $('#tbl_presentacion').DataTable().destroy();
    dtPresentacion = $('#tbl_presentacion').DataTable({
        "language": dtEs,
        "ajax": {
            "url": "catalogo/presentacion/listar",
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
                    return "<td><button title='Seleccionar' class='btn btn-xs btn-primary btn_sel_presentacion'><i class='fa fa-edit' aria-hidden='true'></i></button> </td>"
                }
            }
        ],
        "order": [[1, "asc"]]
    });
}