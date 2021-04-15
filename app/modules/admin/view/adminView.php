<?php
/*
*********************************************************************
Archivo : ventasView.php
Nombre lógico :
Producto :
Fecha de creación: 2017-06-21
Autor(es) : Fanor Rodriguez
*********************************************************************
Propósito :
Encargado de renderizar las vistas
*********************************************************************
CONTROL DE CAMBIOS
Fecha      No. Tarea(HD)  Autor(es)            Descripción
========== ============== ==================== ====================

*********************************************************************
*/

namespace app\modules\admin\view;

use app\core\view\View;

class AdminView extends View
{

    public function __construct()
    {
        $this->cargador(MOD);
        $this->crearEntorno();
    }

    public function index($valores = array())
    {
        $view = $this->render('template/index.twig', $valores);
        echo $view;
    }
    public function home($valores = array())
    {
        $view = $this->render('admin/template/home.twig', $valores);
        echo $view;
    }
}

?>