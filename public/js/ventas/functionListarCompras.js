var dtFacturas = "";
$(document).ready(function(){
    //cargar datatable\
    listarFacturas();
});

function listarFacturas(){

    $('#tblCompras').DataTable().destroy();
    dtFacturas = $('#tblCompras').DataTable({
        "language": dtEs,
        "ajax": {
            "url": "compras/listar",
            "type": "POST",
            "dataSrc": function (json) {
                return json;
            }
        },
        "order": [[1, "asc"]]
    });
}