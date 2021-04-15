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

namespace app\modules\facturacion\view;

use app\core\view\View;

class FacturacionView extends View
{

    public function __construct()
    {
        $this->cargador(MOD);
        $this->crearEntorno();
    }

    public function nueva($valores = array())
    {
        $view = $this->render('facturacion/template/nuevaFactura.twig', $valores);
        echo $view;
    }


    public function listar($valores = array())
    {
        $view = $this->render('facturacion/template/listarFacturas.twig', $valores);
        echo $view;
    }
}

?>