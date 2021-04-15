<?php
use app\helper\errors;
use app\helper\session;
use app\modules\loteria\controller\loteriaController;
use app\modules\admin\controller\adminController;

$router->map('GET', 'loteria', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new AdminController();
        $class->index();

    }else {
        Errors::render('login');
    }
});
$router->map('GET', 'loteria/venta', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new LoteriaController();
        $class->vwVenta();

    }else {
        Errors::render('login');
    }
});

?>