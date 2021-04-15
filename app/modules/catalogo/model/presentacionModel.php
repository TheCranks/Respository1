<?php

namespace app\modules\catalogo\model;

use app\Config;
use app\core\model\Model;

class PresentacionModel extends Model
{

    public function __construct()
    {
        parent::__construct(Config::$mysql);
    }

    public function compruebaPresentacion($descripcion, $id_presentacion){
        $param = '';
        if ($id_presentacion!="")
            $param = " and id_presentacion <> $id_presentacion";
        $query = "select * from tb_presentaciones where nombre = upper('$descripcion') $param";
        $this->prepare($query);
        $this->execute();
        return $this->fetch();
    }

    public function guardaPresentacion($descripcion, $id_usuario){
        $query = "insert into tb_presentaciones (nombre, fecha_grabacion, usuario_grabacion) values (upper(?),now(),?)";
        $this->prepare($query);
        $this->bind([1=>$descripcion, $id_usuario]);
        $this->execute();
        return $this->numRow();
    }

    public function editaPresentacion($descripcion, $id_usuario, $id_presentacion){
        $query = "update tb_presentaciones set nombre = upper(?), fecha_modificacion = now(), 
              usuario_modificacion = ? where id_presentacion = ?";
        $this->prepare($query);
        $this->bind([1=>$descripcion, $id_usuario, $id_presentacion]);
        $this->execute();
        return $this->numRow();
    }

    public function listarPresentacion(){
        $query ="select id_presentacion, nombre from tb_presentaciones where estado = 1";
        $this->prepare($query);
        $this->execute();
        return $this->fetchAll();
    }

}
