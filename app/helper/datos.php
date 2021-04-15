<?php

namespace app\helper;

class Datos
{
    public static function formatearDatos(&$value)
    {
        $res = self::sexo($value['sexo']);
        if ($res == -1) {
            return "sexo incorrecto";
        }
        $value['sexo'] = $res;
        $res = self::estadoCivil($value['estado']);
        if ($res == -1) {
            return "estado civil incorrecto";
        }
        $value['estado'] = $res;
        $res = self::tipoTelefono($value['tipoTelefono']);
        if ($res == -1) {
            return "Tipo telefono incorrecto";
        }
        $value['tipoTelefono'] = $res;

        if (strlen($value['tipoDocumento']) > 0 and strlen($value['documento']) > 0 ) {
            $res = self::tipoDocumento($value['tipoDocumento']);
            if ($res == -1) {
                return "Tipo documento incorrecto";
            }
            $value['tipoDocumento'] = $res;
            $value['documento'] = strtoupper(str_replace('-', '', $value['documento']));
        }
        $value['email'] = strtolower($value['email']);
        return 1;
    }

    public static function mes($value){
        $value = (int)$value;
        $meses = ['','Enero','Febrero','Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return $meses[$value];
    }

    private static function sexo($value)
    {
        switch (strtolower($value)) {
            case 'm':
                return 'M';
                break;
            case 'h':
                return 'H';
                break;
            case 'o':
                return 'O';
                break;
            case 'p':
                return 'P';
                break;
            default:
                return -1;
                break;            
        }
    }

    private static function estadoCivil($value)
    {
        switch (strtolower($value)) {
            case 's':
                return 'S';
                break;
            case 'c':
                return 'C';
                break;
            default:
                return -1;
                break;            
        }
    }

    private static function tipoTelefono($value)
    {
        switch (strtolower($value)) {
            case 'cel':
                return 'CEL';
                break;
            case 'conv':
                return 'CONV';
                break;
            default:
                return -1;
                break;            
        }
    }

    private static function tipoDocumento($value)
    {
        switch (strtolower($value)) {
            case 'ced':
                return 'CED';
                break;
            case 'cedrec':
                return 'CEDREC';
                break;
            default:
                return -1;
                break;            
        }
    }
}

