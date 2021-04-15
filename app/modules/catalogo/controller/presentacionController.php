<?php

namespace app\modules\catalogo\controller;

use app\helper\session;
use app\modules\catalogo\model\presentacionModel;
use app\modules\catalogo\model\unidadMedidaModel;
use app\modules\catalogo\view\catalogoView;

class PresentacionController{

    private $presentacionModel;
    private $catalogoView;
    public function __construct()
    {
        $this->presentacionModel = new  PresentacionModel();
        $this->catalogoView = new CatalogoView();

    }

    public  function presentacion(){
        $this->catalogoView->presentacion();
    }

    public function guardaPresentacion(){
        $id_presentacion = $_POST['id_presentacion'];
        $descripcion= $_POST['descripcion'];
        $error = false;
        $mensaje = 'Operacion realizada correctamente.';
        $id_usuario = Session::get('id_usuario');
        $existe = $this->presentacionModel->compruebaPresentacion($descripcion, $id_presentacion);
        if ($existe){
            $error = true;
            $mensaje = "Ya existe una presentacion con esta descripcion";
        }
        else{
            if ($id_presentacion){
                $resultado = $this->presentacionModel->editaPresentacion($descripcion, $id_usuario,$id_presentacion);
                if (!$resultado){
                    $error = true;
                    $mensaje = "Hubo un error al actualizar el registro";
                }
            }
            else{
                $resultado = $this->presentacionModel->guardaPresentacion($descripcion, $id_usuario);
                if (!$resultado){
                    $error = true;
                    $mensaje = "Hubo un error al grabar el registro";
                }
            }

        }

        echo json_encode(array("error"=>$error, "mensaje"=>$mensaje));
    }

    public function ListarPresentacion(){
        echo json_encode($this->presentacionModel->ListarPresentacion());
    }




}
