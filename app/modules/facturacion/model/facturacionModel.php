<?php

namespace app\modules\facturacion\model;

use app\Config;
use app\core\model\Model;

class FacturacionModel extends Model
{

    public function __construct()
    {
        parent::__construct(Config::$mysql);
    }

    public function obtenerFacturas(){
        $query ="SELECT
                    factura.id_factura,
                    concat(personas.nombres, ' ', personas.apellidos)  as vendedor,
                    factura.id_cliente,
                   	sum( detalle.cantidad * detalle.precio ) AS total,
                    factura.fecha_grabacion as fecha
                FROM
                    tb_facturas factura
                    INNER JOIN tb_detalles_facturas detalle ON detalle.id_factura = factura.id_factura 
                    inner join tb_usuarios usuarios on usuarios.id_usuario = detalle.usuario_grabacion
                    inner join tb_personas personas on personas.id_persona = usuarios.id_persona
                    where detalle.estado = 1
                    group by factura.id_factura
                    having sum( detalle.cantidad * detalle.precio ) is not null 
                    ";
        $this->prepare($query);
        $this->execute();
        return $this->fetchAll();
    }

    public function guardaDetalleFactura($id_usuario,$iva,$id_cliente, $detalleFactura,$pagaCon){
        $fecha = date('d/m/Y');
        $id_factura = 0;
        $error = false;
        $mensaje = 'Error al ingresar el articulo: ';
        $this->beginTransaction();
        $query = "insert into tb_facturas (id_cliente, iva, monto_recibido, fecha_grabacion, usuario_grabacion ) values(?,?,?,now(), ?);";
        $this->prepare($query);
        $this->bind([1=>$id_cliente, $iva,$pagaCon,$id_usuario]);
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
            $id_factura = $result1[0];
            for ($i=0; $i<count($detalleFactura); $i++){
                $query = "  INSERT INTO tb_detalles_facturas ( id_articulo, cantidad, precio, id_factura, usuario_grabacion, fecha_grabacion )
                            VALUES
                                ( ?,?,?,?,?, now() )";
                $this->prepare($query);
                $this->bind([1=>$detalleFactura[$i]['id_articulo'], $detalleFactura[$i]['cantidad'], $detalleFactura[$i]['precio'],
                $id_factura, $id_usuario]);
                $this->execute();
                $result = $this->numRow();
                if (!$result){
                    $error = true;
                    $mensaje.=$detalleFactura[$i]['id_articulo'];
                }
                /*else{
                    //reducir cantidad existencia de articulos
                    // $cantidad = $detalleFactura[$i]['cantidad'];
                    //$query= "update tb_articulos set existencia = existencia-$cantidad";
                    //$this->prepare($query);
                    //$this->execute();
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
