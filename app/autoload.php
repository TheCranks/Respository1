<?php
/*
 * Archivo          : autoload.php
 * Nombre lógico    : Autoload
 * Producto         : core
 * Fecha de creación: 2016_05_10
 * Autor(es)        : Gunter Torrez
 * **************************************************************************
 * Propósito : Carga de manera automatica las librerias necesarias para el 
 * funcionamiento del core.
 * **************************************************************************
 * CONTROL DE CAMBIOS
 * Fecha       No. Tarea (HD)       Autor(es)            Descripción
 */

spl_autoload_register(function($class) {

    $class    = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $location = $class.'.php';
    if (!is_readable($location)) return;
    require_once $location;
});
