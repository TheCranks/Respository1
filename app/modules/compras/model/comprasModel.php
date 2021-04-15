<?php

namespace app\modules\compras\model;

use app\Config;
use app\core\model\Model;

class ComprasModel extends Model
{

    public function __construct()
    {
        parent::__construct(Config::$mysql);
    }

    public function obtenerCompras(){
        $query ="SELECT
                    entradas.id_entrada,
                    concat( personas.nombres, ' ', personas.apellidos ) AS vendedor,
                    entradas.id_proveedor,
                    sum( ed.cantidad * ed.precio ) AS total,
                    entradas.fecha_grabacion AS fecha 
                FROM
                tb_entradas entradas
                inner join tb_entradas_detalles ed on ed.id_entrada = entradas.id_entrada
                    INNER JOIN tb_usuarios usuarios ON usuarios.id_usuario = ed.usuario_grabacion
                    INNER JOIN tb_personas personas ON personas.id_persona = usuarios.id_persona 
                
                GROUP BY
                    entradas.id_entrada 
                HAVING
                    sum( ed.cantidad * ed.precio ) IS NOT NULL
                    ";
        $this->prepare($query);
        $this->execute();
        return $this->fetchAll();
    }

    public function guardaDetalleFactura($id_usuario,$id_proveedor, $detalleFactura){
        //$fecha = date('d/m/Y');
        $id_factura = 0;
        $error = false;
        $mensaje = 'Error al ingresar el articulo: ';
        $this->beginTransaction();
        $query = "insert into tb_entradas (id_proveedor, fecha_grabacion, usuario_grabacion ) values(?,now(), ?);";
        $this->prepare($query);
        $this->bind([1=>$id_proveedor,$id_usuario]);
        $this->execute();
        $result1 = $this->numRow();
        if (!$result1){
            $error = true;
        }
        else{
            $query = 'select LAST_INSERT_ID();';
            $this->prepare($query);
            $this->execute();
            $result1 = $this->fetch();
            $id_entrada = $result1[0];
            for ($i=0; $i<count($detalleFactura); $i++){
                $query = "  INSERT INTO tb_entradas_detalles ( id_articulo, cantidad, precio, id_entrada, 
                usuario_grabacion, fecha_grabacion )
                            VALUES
                                ( ?,?,?,?,?, now() )";
                $this->prepare($query);
                $this->bind([1=>$detalleFactura[$i]['id_articulo'], $detalleFactura[$i]['cantidad'], $detalleFactura[$i]['precio'],
                    $id_entrada, $id_usuario]);
                $this->execute();

                $result = $this->numRow();
                if (!$result){
                    $error = true;
                    $mensaje.=$detalleFactura[$i]['id_articulo'];
                }
                /*else{
                    //reducir cantidad existencia de articulos
                    $cantidad = $detalleFactura[$i]['cantidad'];
                    $query= "update tb_articulos set existencia = existencia-$cantidad";
                    $this->prepare($query);
                    $this->execute();
                }*/
            }
        }
        if (!$error){
            $this->commit();
        }
        else
            $this->rollBack();

        return array("mensaje"=>$mensaje, 'error'=>$error, "id_factura"=>$id_factura);

    }

}
