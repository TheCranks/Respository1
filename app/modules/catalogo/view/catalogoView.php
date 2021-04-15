<?php

namespace app\modules\catalogo\view;

use app\core\view\View;

class CatalogoView extends View
{

    public function __construct()
    {
        $this->cargador(MOD);
        $this->crearEntorno();
    }

    public function usuarios($valores = array())
    {
        $view = $this->render('catalogo/template/usuarios.twig', $valores);
        echo $view;
    }

    public function personal($valores = array())
    {
        $view = $this->render('catalogo/template/personal.twig', $valores);
        echo $view;
    }


    public function unidadMedida($valores = array())
    {
        $view = $this->render('catalogo/template/unidadMedida.twig', $valores);
        echo $view;
    }

    public function presentacion($valores = array())
    {
        $view = $this->render('catalogo/template/presentacion.twig', $valores);
        echo $view;
    }

    public function articulos($valores = array())
    {
        $view = $this->render('catalogo/template/articulos.twig', $valores);
        echo $view;
    }
}

?>