<?php
use app\helper\errors;
use app\helper\session;
use app\modules\admin\controller\adminController;
use app\modules\catalogo\controller\catalogoUsuariosController;

$router->map('GET', '', function(){
     $session = new Session();
    $_SESSION['sistema']='';
    if ($session->get('id_usuario')){
        $class = new AdminController();
        $class->home();
    }else {

        Errors::render('login');
    }
});

$router->map('GET', 'facturacion', function(){
    $session = new Session();
    //si tiene permiso de entrar a este modulo

    if ($session->get('id_usuario')){
        $_SESSION['sistema']='fonda';
        $class = new AdminController();
        $class->index();

    }else {
        Errors::render('login');
    }
});
$router->map('GET', 'loteria', function(){

    $session = new Session();
    if ($session->get('id_usuario')){
        $_SESSION['sistema']='loteria';
        $class = new AdminController();
        $class->index('loteria');

    }else {
        Errors::render('login');
    }
});


$router->map('GET', 'inicio/?', function(){

    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new AdminController();
        $class->home();
    }else {

        Errors::render('login');
    }
});

$router->map('POST', 'admin/login/?', function(){
    $class = new CatalogoUsuariosController();
    $class->iniciaSession();
});

$router->map('GET', 'admin/salir/?', function(){
    $class = new CatalogoUsuariosController();
    $class->cerrarSession();
});


//trash
$router->map('GET', 'generarpdf/?', function(){
    $class = new AdminController();
    $class->generar();
});



?>