<?php

namespace app\modules\catalogo\model;

use app\Config;
use app\core\model\Model;

class UnidadMedidaModel extends Model
{

    public function __construct()
    {
        parent::__construct(Config::$mysql);
    }

    public function compruebaUM($descripcion, $id_um){
        $param = '';
        if ($id_um!="")
            $param = " and id_unidad_medida <> $id_um";
        $query = "select * from tb_unidades_medidas where nombre = upper('$descripcion') $param";
        $this->prepare($query);
        $this->execute();
        return $this->fetch();
    }

    public function guardaUM($descripcion, $id_usuario, $abreviatura){
        $query = "insert into tb_unidades_medidas (nombre, fecha_grabacion, usuario_grabacion, abreviatura) values (upper(?),now(),?, upper(?))";
        $this->prepare($query);
        $this->bind([1=>$descripcion, $id_usuario, $abreviatura]);
        $this->execute();
        return $this->numRow();
    }

    public function editaUM($descripcion,  $id_um, $id_usuario, $abreviatura){
        $query = "update tb_unidades_medidas set nombre = upper(?), fecha_modificacion = now(), 
              usuario_modificacion = ?, abreviatura = upper(?) where id_unidad_medida = ?";
        $this->prepare($query);
        $this->bind([1=>$descripcion, $id_usuario,$abreviatura, $id_um]);
        $this->execute();
        return $this->numRow();
    }

    public function listarUnidadMedida(){
        $query ="select id_unidad_medida, nombre, abreviatura from tb_unidades_medidas where estado = 1";
        $this->prepare($query);
        $this->execute();
        return $this->fetchAll();
    }


}
