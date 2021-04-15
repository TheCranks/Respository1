<?php
use app\libs\altoRouter;
use app\helper\session;
use app\helper\errors;
use app\modules\admin\controller\AdminController;

Session::init();

$router = new AltoRouter();
$router->setBasePath(BASE);

require_once(MOD.'admin/url.php');
require_once(MOD.'catalogo/url.php');
require_once(MOD.'facturacion/url.php');
require_once(MOD.'compras/url.php');
require_once(MOD.'loteria/url.php');

$match = $router->match();
if( $match && is_callable( $match['target'] ) ) {

    call_user_func_array( $match['target'], $match['params'] );
} else {
    exit(var_dump($match['target']));
}
