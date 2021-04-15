<?php

namespace app\modules\catalogo\model;

use app\config;
use app\core\model\Model;

class ArticulosModel extends Model
{

    public function __construct()
    {
        parent::__construct(Config::$mysql);
    }

    public function obtieneUnidadMedidas(){
        $query = "select *  from tb_unidades_medidas where estado =1";

        $this->prepare($query);
        $this->execute();
        return $this->fetchAll();
    }
    public function obtienePresentaciones(){
        $query = "select *  from tb_presentaciones where estado =1";
        $this->prepare($query);
        $this->execute();
        return $this->fetchAll();
    }


    public function listarArticulos(){
        $query = "SELECT
	LPAD(articulos.id_articulo,3,'0') as id_articulo,
	articulos.nombre AS nombre_articulo,
	presentacion.nombre AS presentacion,
	um.nombre AS unidad_medida ,
	(ed.cantidad -(COALESCE(detalle.cantidad,0))) as existencia ,
	articulos.precio_venta,
	articulos.precio_costo,
	articulos.existencia_minima, 
	articulos.id_unidad_medida, 
	articulos.id_presentacion
FROM
	tb_articulos articulos
	LEFT JOIN tb_presentaciones presentacion ON presentacion.id_presentacion = articulos.id_presentacion
	LEFT JOIN tb_unidades_medidas um ON um.id_unidad_medida = articulos.id_unidad_medida
	inner join tb_entradas_detalles ed on ed.id_articulo = articulos.id_articulo
	left outer join tb_detalles_facturas detalle on detalle.id_articulo = articulos.id_articulo
	 	where articulos.estado = 1
	group by id_articulo";
        $this->prepare($query);
        $this->execute();
        return $this->fetchAll();
    }

    public function verificaArticulo($descripcion, $unidadMedida, $presentacion, $id_articulo){
        $param = "";
        if ($id_articulo!=""){
            $param = " and id_articulo <> $id_articulo";
        }
        $query = "select * from tb_articulos where nombre = upper(?) and id_unidad_medida = ? and id_presentacion = ? $param";
        $this->prepare($query);
        $this->bind([1=>$descripcion, $unidadMedida, $presentacion]);
        $this->execute();
        return $this->fetchAll();
    }

    public function guardaArticulo($descripcion, $unidadMedida, $presentacion, $preciocosto, $precioVenta,
                                   $existencia, $existenciaMinima, $id_usuario){
        $preciocosto = empty(!$preciocosto) ? implode(explode(",",$preciocosto)) : "null";
        $precioVenta  = empty(!$precioVenta) ? implode(explode(",",$precioVenta)) : "null";
        $existencia  = empty(!$existencia) ? implode(explode(",",$existencia))   : "null";
        $existenciaMinima  = empty(!$existenciaMinima) ? $existenciaMinima  : "null";
        $unidadMedida  = empty(!$unidadMedida) ? $unidadMedida  : "null";
        $presentacion  = empty(!$presentacion) ? $presentacion  : "null";


        $query = "insert into tb_articulos (nombre,precio_costo, precio_venta, existencia_minima, existencia, 
                id_unidad_medida, id_presentacion, fecha_grabacion, usuario_grabacion ) values (upper(?),?, 
                ?, ?, ?, 
                ?, ?, now(), ?)";
        $this->prepare($query);
        $this->bind([1=>$descripcion, $preciocosto, $precioVenta, $existenciaMinima, $existencia,$unidadMedida,
            $presentacion, $id_usuario]);
        $this->execute();
        return $this->numRow();

    }


    public function actualizarArticulo($descripcion, $unidadMedida, $presentacion, $preciocosto, $precioVenta,
                                       $existencia, $existenciaMinima, $id_usuario, $id_articulo){
        $preciocosto = empty(!$preciocosto) ? implode(explode(",",$preciocosto)) : "null";
        $precioVenta  = empty(!$precioVenta) ? implode(explode(",",$precioVenta)) : "null";
        $existencia  = empty(!$existencia) ? implode(explode(",",$existencia))   : "null";
        $existenciaMinima  = empty(!$existenciaMinima) ? $existenciaMinima  : "null";
        $unidadMedida  = empty(!$unidadMedida) ? $unidadMedida  : "null";
        $presentacion  = empty(!$presentacion) ? $presentacion  : "null";

        $query = "update tb_articulos set nombre = upper('$descripcion'), precio_costo = $preciocosto, 
            precio_venta = $precioVenta, existencia_minima = $existenciaMinima , existencia = $existencia, 
            id_unidad_medida = $unidadMedida, id_presentacion = $presentacion, fecha_modificacion = now(), 
            usuario_modificacion = $id_usuario where id_articulo = $id_articulo";
        $this->prepare($query);
        $this->execute();
        return $this->numRow();

    }

    public function eliminaArticulo($id_articulo, $id_usuario){
        $query = "update tb_articulos set estado = -1, fecha_modificacion = now(), usuario_modificacion= ?
          where id_articulo = ?";
        $this->prepare($query);
        $this->bind([1=>$id_usuario, $id_articulo]);
        $this->execute();
        return $this->numRow();
    }

}
