<?php

/*
 * Archivo          : Config.php
 * Nombre lógico    : config
 * Producto         : core
 * Fecha de creación: 2016_05_10
 * Autor(es)        : Gunter Torrez
 * **************************************************************************
 * Propósito : Clase encagada de establecer las configuraciones del nucleo del sistema
 * **************************************************************************
 * CONTROL DE CAMBIOS
 * Fecha       No. Tarea (HD)       Autor(es)            Descripción
 */

namespace app;

/**
 * Contiene todas las configuraciones del Framework
 * Se define en una clase final para evitar posibles modificaciones a la varible
 *
 */
class Config
{
    public static $mysql = array(
        'config' => array('driver' => 'mysql', 'host' => 'localhost', 'dbname' => 'id7510847_main_db'),
        'user' => 'id7510847_main_db',
        'pass' => '0008965_com'
    );

    public static $pgsql = array(
        'config' => array('driver' => 'pgsql', 'host' => '172.20.23.7', 'dbname' => 'inatec'),
        'user' => 'testsys',
        'pass' => '!n@t3c2k13'
    );

    public static $sqlsrv = array(
        'config' => array('driver' => 'sqlsrv', 'server' => '172.20.23.39\MSSQLTEST', 'database' => 'servicios'),
        'user' => 'testsql',
        'pass' => 't3sts3rv3er'
    );

    public static $sqlsrv_2 = array(
        'config' => array('driver' => 'sqlsrv', 'server' => '172.20.23.33\MSSQLDES', 'database' => 'servicios'),
        'user' => 'sa',
        'pass' => 'SqlDes2017'
    );

    public static $roles = array(
        'adm' => 'Administrador',
        'usr'=>'Usuario'
    );

    public static $modulos = array(
        'admin' => 'Administracion',
        'vtas' => 'Ventas',
        'inv' => 'Inventario',
        'cat' => 'Catalogo'
    );
}