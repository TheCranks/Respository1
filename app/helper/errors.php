<?php 

namespace app\helper;

use app\core\view\View;

class Errors
{
    public static function render($value, $params = array())
    {
        $view = new View();
        $view->cargador(MOD.'template');
        $view->crearEntorno();
        $html = $view->render($value.'.twig', $params);
        echo $html;
    }
}
