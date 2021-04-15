<?php
/*
*********************************************************************
Archivo :
Nombre lógico :
Producto :
Fecha de creación:
Autor(es) :
*********************************************************************
Propósito :
Encargado de renderizar las vistas
*********************************************************************
CONTROL DE CAMBIOS
Fecha      No. Tarea(HD)  Autor(es)            Descripción
========== ============== ==================== ====================

*********************************************************************
*/

namespace app\modules\loteria\view;

use app\core\view\View;

class LoteriaView extends View
{

    public function __construct()
    {
        $this->cargador(MOD);
        $this->crearEntorno();
    }


    public function index($valores = array())
    {
        $view = $this->render('loteria/template/ventaLoteria.twig', $valores);
        echo $view;
    }

}

?>