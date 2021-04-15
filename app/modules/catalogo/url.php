<?php
use app\Config;
use app\helper\errors;
use app\helper\Roles;
use app\helper\session;
use app\modules\catalogo\controller\articulosController;
use app\modules\catalogo\controller\catalogoPersonalController;
use app\modules\catalogo\controller\catalogoUsuariosController;
use app\modules\catalogo\controller\presentacionController;
use app\modules\catalogo\controller\unidadMedidaController;

$router->map('GET', 'catalogo/usuarios/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new CatalogoUsuariosController();
        $class->usuarios();

    }else {
        Errors::render('login');
    }
});
$router->map('GET', 'catalogo/personal/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new CatalogoPersonalController();
        $class->personal();

    }else {
        Errors::render('login');
    }
});


$router->map('POST', 'catalogo/personal/guardar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new CatalogoPersonalController();
        $class->guardaPersonal();

    }else {
        Errors::render('login');
    }
});

$router->map('POST', 'catalogo/personal/listar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new CatalogoPersonalController();
        $class->listarPersonal();

    }else {
        Errors::render('login');
    }
});

$router->map('POST', 'catalogo/usuario/guardar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new CatalogoUsuariosController();
        $class->guardaUsuario();

    }else {
        Errors::render('login');
    }
});
$router->map('POST', 'catalogo/usuarios/listar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new CatalogoUsuariosController();
        $class->listarUsuarios();

    }else {
        Errors::render('login');
    }
});

$router->map('GET', 'catalogo/unidadmedida/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new UnidadMedidaController();
        $class->unidadMedida();

    }else {
        Errors::render('login');
    }
});
$router->map('POST', 'catalogo/unidadmedida/guardar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new UnidadMedidaController();
        $class->guardaUnidadMedida();

    }else {
        Errors::render('login');
    }
});
$router->map('POST', 'catalogo/unidadesmedida/listar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new UnidadMedidaController();
        $class->listarUnidadesMedidas();

    }else {
        Errors::render('login');
    }
});
$router->map('GET', 'catalogo/presentacion/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new PresentacionController();
        $class->presentacion();

    }else {
        Errors::render('login');
    }
});


$router->map('POST', 'catalogo/presentacion/listar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new PresentacionController();
        $class->ListarPresentacion();

    }else {
        Errors::render('login');
    }
});


$router->map('POST', 'catalogo/presentacion/guardar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new PresentacionController();
        $class->guardaPresentacion();

    }else {
        Errors::render('login');
    }
});
$router->map('GET', 'catalogo/articulos/?', function(){

    $session = new Session();
    if ($session->get('id_usuario')){

        $class = new ArticulosController();
        $class->articulos();

    }else {
        Errors::render('login');
    }
});
$router->map('POST', 'catalogo/articulos/listar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new ArticulosController();
        $class->listarArticulos();

    }else {
        Errors::render('login');
    }
});

$router->map('POST', 'catalogo/articulos/guardar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new ArticulosController();
        $class->guardarArticulo();

    }else {
        Errors::render('login');
    }
});

$router->map('POST', 'catalogo/articulos/eliminar/?', function(){
    $session = new Session();
    if ($session->get('id_usuario')){
        $class = new ArticulosController();
        $class->eliminarArticulo();

    }else {
        Errors::render('login');
    }
});







?>