var dtUsuarios ='';
var dtPersonal = '';
$(document).ready(function() {
    $("#btn_buscaPersona").click(function(){
        obtenerPersonal();
    });

    obtenerUsuarios();

    $("#btnGuardaUsuario").click(function(){
        var id_persona = $("#txtIdPersonal").val();
        var usuario = $("#txtUsuario").val();
        var pass = $("#txtPass").val();
        var id_rol = $("#selRoles").val();
        var activo = 1;
        var id_usuario = $("#txtIdUsuario").val();

        if($("#ckActivo").is(':checked')) {
            activo = 1;
        } else {
            activo =0;
        }

        if (validaCampos()) {
            if (!rePassIguales()){
                alertError("Contraseñas no coinciden");
            }
            else{
                $.ajax({
                    type: "POST",
                    url: "catalogo/usuario/guardar",
                    dataType: "JSON",
                    data: {
                        'id_personal': id_persona,
                        'usuario':usuario,
                        'pass':pass,
                        'id_usuario':id_usuario,
                        'id_rol':id_rol,
                        'activo':activo

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

        }
    });

});

function rePassIguales(){
    var pass = $("#txtPass").val();
    var repass = $("#txtRePass").val();

    return (pass==repass);
}

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
    var nombres = data[1]+' ' +data[2];
    var id_persona = data[0];
    $("#txtIdPersonal").val(id_persona);
    $("#txtNombres").val(nombres);

    $('#modalPersonal').modal('hide');
});



function obtenerUsuarios(){
    $('#tbl_usuarios').DataTable().destroy();
    dtUsuarios = $('#tbl_usuarios').DataTable({
        "language": dtEs,
        "ajax": {
            "url": "catalogo/usuarios/listar",
            "type": "POST",
            "dataSrc": function (json) {
                return json;
            }
        },
        columnDefs:[{
            targets:[5],
            visible:false
        },
            {
                targets:[4],
                "render": function (data, type, row) {
                    if (data=='1'){
                        return "<td><label for='' class='label label-success'>Administrador</label></td>"
                    }
                    else
                        return "<td><label for='' class='label label-warning'>Usuario</label></td>"
                }
            },
            {
                targets:[6],
                "render": function (data, type, row) {
                    if (data=='1'){
                        return "<td><label for='' class='label label-primary'>Activo</label></td>"
                    }
                    else
                        return "<td><label for='' class='label label-danger'>Inactivo</label></td>"
                }
            },
            {
                targets: [-1],
                "data": null,
                "render": function (data, type, row) {
                    return "<td><button title='Seleccionar' class='btn btn-xs btn-primary btn_select_usuario'><i class='fa fa-edit' aria-hidden='true'></i></button> </td>"
                }
            }
        ],
        "order": [[1, "asc"]]
    });
}

$(document).off('click', '.btn_select_usuario').on('click', '.btn_select_usuario', function(){
    var data = dtUsuarios.row($(this).parents("tr")).data();
    $("#txtIdUsuario").val(data[0]);
    $("#txtNombres").val(data[1]);
    $('#txtUsuario').val(data[2]);
    $("#selRoles").val(data[4]);
    $("#txtIdPersonal").val(data[5]);
    if (data[6]==1){
        $("#ckActivo").parent().addClass('checked');
    }
    else{
        $("#ckActivo").parent().removeClass('checked');
    }

});