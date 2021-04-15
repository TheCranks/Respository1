$(document).ready(function(){


    $("#txtPassword, #txtUsuario").keypress(function(e) {
        //no recuerdo la fuente pero lo recomiendan para
        //mayor compatibilidad entre navegadores.
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
            login();
        }
    });

    $("#btn_inicia_sesion").click(function(){
        login();
    });
});

function login(){

    var usuario = $("#txtUsuario").val();
    var password = $("#txtPassword").val();
    if (usuario!="" && password!=""){
        $('#ibox').children('.ibox-content').toggleClass('sk-loading');
        $.ajax({
            type: "POST",
            url: "admin/login",
            data: {
                "usuario": usuario,
                "pass": password

            },
            dataType: "JSON",
            success: function (response) {

                if (response == 1) {
                    alertSuccess('Inicio de sesion correcto');
                    setTimeout(function () {
                        location.reload(true);
                    }, 1500);
                    $('#ibox').children('.ibox-content').toggleClass('sk-loading');

                } else if (response == -1 || response == 0) {

                    alertError('Usuario o contraseña incorrecta, verifique e intente nuevamente.');
                    $('#ibox').children('.ibox-content').toggleClass('sk-loading');
                }
            },
            error: function () {
                $('#ibox').children('.ibox-content').toggleClass('sk-loading');
                alertError('Error al comunicarse con el servidor')
            }
        });
    }
    else{
        alertError('Ingrese usuario y contraseña');
    }
}