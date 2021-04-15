var dtUM= "";
$(document).ready(function(){
    cargarUnidadMedidas();

    $("#btnGuardaUnidadMedida").click(function(){
        var descripcion = $("#txtNombreUM").val();
        var id_um = $("#txtIdUnidadMedida").val();
        var abreviatura = $("#txtAbreviaturaUM").val();
        if (descripcion!=""){
            $.ajax({
                type: "POST",
                url: "catalogo/unidadmedida/guardar",
                dataType: "JSON",
                data: {
                    'descripcion': descripcion,
                    'id_um':id_um,
                    'abreviatura':abreviatura

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

$(document).off('click','.btn_sel_um').on('click', '.btn_sel_um', function(){
    var data = dtUM.row($(this).parents("tr")).data();
    $("#txtIdUnidadMedida").val(data[0]);
    $("#txtNombreUM").val(data[1]);
    $("#txtAbreviaturaUM").val(data[2]);
    alertSuccess('Proceda a editar este registro.');
});


function cargarUnidadMedidas(){
    $('#tbl_unid_medidas').DataTable().destroy();
    dtUM = $('#tbl_unid_medidas').DataTable({
        "language": dtEs,
        "ajax": {
            "url": "catalogo/unidadesmedida/listar",
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
                    return "<td><button title='Seleccionar' class='btn btn-xs btn-primary btn_sel_um'><i class='fa fa-edit' aria-hidden='true'></i></button> </td>"
                }
            }
        ],
        "order": [[1, "asc"]]
    });
}