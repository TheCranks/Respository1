<?php
/*
 * Archivo          : View.php
 * Nombre lógico    : View
 * Producto         : core
 * Fecha de creación: 2016_05_10
 * Autor(es)        : Gunter Torrez
 * **************************************************************************
 * Propósito : Encargado de poder a disposición de los modulos del sistemas los
 * metodos necesarios para el uso del motor de plantillas twig
 * **************************************************************************
 * CONTROL DE CAMBIOS
 * Fecha       No. Tarea (HD)       Autor(es)            Descripción
 */

namespace app\core\view;

use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 * Clase Vista Principal del Framework
 * <br /><br />
 * Esta clase utiliza el motor de plantillas Twig
 */
class View
{
    /**
     * Carga plantillas desde el sistema de archivos
     * @var Twig_Loader_Filesystem
     */
    private $loader;

    /**
     * Ruta hacia los archivos de plantillas en el sistema de archivos
     * @var string
     */
    private $src = '';

    /**
     * Se utiliza para almacenar la configuración y extensiones, y se utiliza
     * para cargar plantillas desde el sistema de archivos. <br />
     * La mayoría de las aplicaciones deben crear un objeto Twig_Environment al
     * iniciar la aplicación y usarlo para cargar plantillas.
     * @var Twig_Environment
     */
    public $twig;

    /*
      public function __construct()
      {
      $this->loader = new Twig_Loader_Filesystem('/path/to/templates');
      $$this->twig = new Twig_Environment($$this->loader,
      array(
      'cache' => '/path/to/compilation_cache',
      ));

      }

      public function __destruct()
      {

      } */

    /**
     * Define la ruta a las plantillas a utilizar y crea el objeto que las carga
     * @param string $ruta Ruta en el sistema de archivos
     * @return boolean Verdadero en caso de exito <br />
     * Falso en caso de error
     */
    public function cargador($ruta)
    {
        if (!empty($ruta)) {
            $this->src = $ruta;
            $this->loader = new Twig_Loader_Filesystem($ruta);
            return true;
        }

        return false;
    }

    /**
     * Crea un entorno de plantillas con la configuración predeterminada,
     * el cargador ya debe estar definido para poder utilizarlo
     * @param Array $opcion Opciones del entorno
     * @return boolean Verdadero en exito <br />
     * Falso si no se ha creado el objeto cargador
     */
    public function crearEntorno($opcion = array())
    {
        if (is_object($this->loader)) {
            $this->twig = new Twig_Environment($this->loader, $opcion);
            return true;
        }
        return false;
    }

    /**
     * Obtiene una plantilla y la reproduce, es decir, reemplaza las variables que
     * la plantilla tiene definida por los valores que recibe como parametros
     * @param String $plantilla Nombre de la plantilla a reproducir
     * @param Array $valores Conjunto de variables que van a ser reemplazados
     * @return string Plantilla con los valores que le pasamos o <br />
     * Vacio en caso de error
     */
    public function render($plantilla, $valores = array())
    {
        if (is_object($this->twig) and ! empty($plantilla)) {

            return $this->twig->render($plantilla, $valores);
        }

        return '';
    }
}
/**
 * eof
 */