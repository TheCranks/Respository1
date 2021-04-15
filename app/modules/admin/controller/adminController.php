<?php

namespace app\modules\admin\controller;


use app\helper\session;
use app\modules\admin\view\adminView;
use app\modules\admin\model\adminModel;
use app\modules\catalogo\model\usuariosModel;

class AdminController 
{ 

    private $adminView;
    private $adminModel;
    private $usuarioModel;
    private $pdf;

    public function __construct()
    {
        $this->adminView = new AdminView();
        //$this->pdf = new FPDF();
        $this->adminModel = new AdminModel();
        $this->usuarioModel = new UsuariosModel();
    }

    public function index(){

        $this->adminView->index();
    }
    public function home()
    {
        $nom_rol = Session::get('id_rol');
        if ($nom_rol ==1){
            $nom_rol = 'Administrador';
        }
        else
            $nom_rol='Usuario';
        $datos = array(
            'id_rol'=>Session::get('id_rol'),
            'nombres'=>Session::get('nombres'),
            'apellidos'=>Session::get('apellidos'),
            'nom_rol'=>$nom_rol
        );

        $this->adminView->home(array('datos'=>$datos));
    }

    
    

    public function generar(){
      $pdf = new FPDF($orientation='P',$unit='mm', array(45,350));
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',8);    //Letra Arial, negrita (Bold), tam. 20
        $textypos = 5;
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->Cell(5,$textypos,"Nombre de la empresa");
        $textypos+=5;
        $pdf->setX(3);
        $pdf->setY(3);
        $pdf->Cell(5,$textypos,"Otro Texto");
        $pdf->SetFont('Arial','',5);    //Letra Arial, negrita (Bold), tam. 20
        $textypos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$textypos,'-------------------------------------------------------------------');
        $textypos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$textypos,'CANT.  ARTICULO       PRECIO               TOTAL');
        $total =0;
        $off = $textypos+6;
        $producto = array(
        	"q"=>1,
        	"name"=>"Computadora Lenovo i5",
        	"price"=>120
        );
        $productos = array($producto, $producto, $producto, $producto, $producto );
        foreach($productos as $pro){
        $pdf->setX(2);
        $pdf->Cell(5,$off,$pro["q"]);
        $pdf->setX(6);
        $pdf->Cell(35,$off,  strtoupper(substr($pro["name"], 0,12)) );
        $pdf->setX(20);
        $pdf->Cell(11,$off,  "$".number_format($pro["price"],2,".",",") ,0,0,"R");
        $pdf->setX(32);
        $pdf->Cell(11,$off,  "$ ".number_format($pro["q"]*$pro["price"],2,".",",") ,0,0,"R");
        $total += $pro["q"]*$pro["price"];
        $off+=6;
        }
        $textypos=$off+6;
        $pdf->setX(2);
        $pdf->Cell(5,$textypos,"TOTAL: " );
        $pdf->setX(38);
        $pdf->Cell(5,$textypos,"$ ".number_format($total,2,".",","),0,0,"R");
        $pdf->setX(2);
        $pdf->Cell(5,$textypos+6,'GRACIAS POR TU COMPRA ');
        $pdf->output();
    }



}

?>