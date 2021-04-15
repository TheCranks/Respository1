var dtPersonal = "";
$(document).ready(function(){
    obtenerPersonal();

    $("#txtCelularPersonal").mask("00000000");
    $("#txtCorreoPersonal").blur(function(){
        if ($(this).val()!=""){
            if (!correoValido($(this).val()))
                alertError("El correo ingresado es incorrecto, asegúrese de escribirlo correctamente.");
        }

    });

    $("#btnGuardaPersonal").click(function(){
        if (validaCampos()){
            var idPersonal = $("#txtIdPersonal").val();
            var nombrePersonal = $("#txtNombresPersonal").val();
            var apellidoPersonal= $("#txtApellidosPersonal").val();
            var direccionPersonal= $("#txtDireccionPersonal").val();
            var celularPersonal= $("#txtCelularPersonal").val();
            var cedulaPersonal= $("#txtCedula").val();
            var correoPersonal= $("#txtCorreoPersonal").val();
            var sexoPersonal= $("#selSexoPersonal").val();

            $.ajax({
                type: "POST",
                url: "catalogo/personal/guardar",
                dataType: "JSON",
                data: {
                    'nombrePersonal': nombrePersonal,
                    'apellidoPersonal':apellidoPersonal,
                    'direccionPersonal':direccionPersonal,
                    'celularPersonal':celularPersonal,
                    'cedulaPersonal':cedulaPersonal,
                    'correoPersonal':correoPersonal,
                    'sexoPersonal':sexoPersonal,
                    'idPersonal':idPersonal

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
    });
});



function obtenerPersonal(){
    $('#tbl_personal').DataTable().destroy();
    dtPersonal = $('#tbl_personal').DataTable({
        "language": dtEs,
        "ajax": {
            "url": "catalogo/personal/listar",
            "type": "POST",
            "dataSrc": function (json) {
                return json;
            }
        },
        columnDefs:[{
            targets:[-2],
            visible:false
        },
            {
                targets: [-1],
                "data": null,
                "render": function (data, type, row) {
                    return "<td><button title='Seleccionar' class='btn btn-xs btn-primary btn_select_personal'><i class='fa fa-edit' aria-hidden='true'></i></button> </td>"
                }
            }
            ],
        "order": [[1, "asc"]]
    });
}

$(document).off('click','.btn_select_personal').on('click', '.btn_select_personal', function(){
    var data = dtPersonal.row($(this).parents("tr")).data();
    $("#txtIdPersonal").val(data[0]);
    $("#txtNombresPersonal").val(data[1]);
    $("#txtApellidosPersonal").val(data[2]);
    $("#txtCedula").val(data[3]);
    $("#txtDireccionPersonal").val(data[4]);
    $("#txtCelularPersonal").val(data[5]);
    $("#txtCorreoPersonal").val(data[6]);
    $("#selSexoPersonal").val(data[7]);
    alertSuccess('Proceda a editar este registro.');
});