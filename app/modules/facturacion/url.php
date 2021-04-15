<?php
use app\helper\errors;
use app\helper\session;
use app\modules\facturacion\controller\facturacionController;
use app\modules\admin\controller\adminController;




$router->map('GET', 'venta/nueva/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new FacturacionController();
        $class->nueva();

    }else {
        Errors::render('login');
    }
});



$router->map('POST', 'venta/factura/detalle/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new FacturacionController();
        $class->agregaDetalle();

    }else {
        Errors::render('login');
    }
});

$router->map('GET', 'venta/listar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new FacturacionController();
        $class->listarFacturas();

    }else {
        Errors::render('login');
    }
});

$router->map('POST', 'ventas/facturas/listar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new FacturacionController();
        $class->obtenerFacturas();

    }else {
        Errors::render('login');
    }
});

$router->map('POST', 'venta/factura/imprimir/?', function(){
    $class = new FacturacionController;
    $class->imprimir();
});

?>