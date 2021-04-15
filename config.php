<?php

use app\Config;

use Doctrine\Common\ClassLoader;

$ip = filter_input(INPUT_SERVER, 'SERVER_ADDR');

date_default_timezone_set('America/Managua');

define('BASE', '/');

$root = $_SERVER['DOCUMENT_ROOT'] . BASE;
if ('172.20.23.81' == $ip) {
    $root = $_SERVER['DOCUMENT_ROOT'];
}

/**
 * Constanste de la ruta a la carpeta app
 */

defined("URL")
or define('URL', $root);

defined("APP")
or define('APP', URL . "app/");

defined("CORE")
or define('CORE', APP . "core/");

defined("HELPER")
or define('HELPER', APP . "helper/");

defined("LIBS")
or define('LIBS', CORE . "libs/");

defined("MOD")
or define('MOD', "./app/modules/");

defined("PUB")
or define('PUB', "./public/");

defined("MSG")
or define('MSG', MOD . "admin/template/message/");


//require_once APP.'autoload.php';
// autoload principal
// carga todos los demas cargadores
require_once LIBS . 'Doctrine/Common/ClassLoader.php';
$classLoader = new ClassLoader('Doctrine', LIBS);
$classLoader->register();

// carga del motor de plantilla
$tpl = new ClassLoader('Twig', LIBS);
$tpl->setNamespaceSeparator('_');
$tpl->register();
// carga jasperreport
$jas = new ClassLoader('Jaspersoft', LIBS);
$jas->register();

$_app = new ClassLoader('app', URL);
$_app->register();

/**
 * eof
 */
