<?php
use app\helper\errors;
use app\helper\session;
use app\modules\compras\controller\ComprasController;

$router->map('GET', 'compra/nueva/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new ComprasController();
        $class->nueva();

    }else {
        Errors::render('login');
    }
});
$router->map('POST', 'compra/detalle/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new ComprasController();
        $class->agregaDetalle();

    }else {
        Errors::render('login');
    }
});

$router->map('GET', 'compra/listar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new ComprasController();
        $class->listarFacturas();

    }else {
        Errors::render('login');
    }
});

$router->map('POST', 'compras/listar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new ComprasController();
        $class->obtenerCompras();

    }else {
        Errors::render('login');
    }
});

$router->map('POST', 'compras/imprimir/?', function(){
    $class = new ComprasController;
    $class->imprimir();
});

?>