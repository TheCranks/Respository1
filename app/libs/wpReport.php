<?php

namespace app\libs;

use Jaspersoft\Client\Client;

/**
 * @link http://community.jaspersoft.com/wiki/php-client-wordpress-example
 * Como utilizar esta clase
 */
class WPReport
{

    public $client;
    private $mime_types = [
        'html' => 'text/html',
        'pdf' => 'application/pdf',
        'xls' => 'application/vnd.ms-excel',
        'csv' => 'text/csv',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'rtf' => 'text/rtf',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'xlsx' => 'application/vnd.ms-excel'
    ];

    public function __construct()
    {
        /*
          $this->client = new Jasper\JasperClient('localhost', 8080,
          'jasperadmin', 'jasperadmin', '/jasperserver-pro', 'organization_1');
         */
        $this->client = new Client(
            "http://localhost:8080/jasperserver", "jasperadmin", "jasperadmin"
        );
    }

    /**
     * run() is to be called via a GET parameter. Using run() will run a
      report specified by URI and FORMAT get calls.
     * Example:
      thisfile.php?func=run&uri=/reports/samples/AllAccounts&format=pdf
     * Calling the file in this manner will return the binary of the
      specified report, in PDF format
     */
    public function run()
    {
        if (isset($_GET['uri']) && isset($_GET['format'])) {
            $report_data = $this->client->runReport($_GET['uri'], $_GET['format']);
            if ($_GET['format'] !== 'html') {
                echo $this->prepareForDownload($report_data, $_GET['format']);
            } else {
                echo $report_data;
            }
        }
    }

    /**
     * This function prepares a page with the proper headers to initiate
      a download dialog in modern browsers
     * by using this function we can supply the report binary and users
      can download the file <br>
     * Se cambia de privada a protected
     */
    protected function prepareForDownload($data, $format)
    {
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Description: File Transfer');
        //header('Content-Disposition: attachment; filename=report.' . $format);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($data));
        if (isset($this->mime_types[$format])) {
            header('Content-Type: ' . $this->mime_types[$format]);
        } else {
            header('Content-Type: application/octet-stream');
        }
        echo $data;
    }

    /**
     * This function returns the reports vailable at the position 'uri'
     * the data is echoed in JSON format so it can be used by a jQuery
      function
     * to populate a dropdown select HTML element
     * example: thisfile.php?func=getReports&uri=/reports/samples
     */
    public function getReports()
    {
        if (isset($_GET['uri'])) {
            $result = array();
            $repo = $this->client->getRepository($_GET['uri']);
            foreach ($repo as $r) {
                $result[] = array('name' => $r->getName(), 'uri' =>
                    $r->getUriString());
            }
            echo json_encode($result);
        }
    }

    /**
     * This function simply json-ifys the array above to populate a
      drop-down menu
     * select HTML element. This way it is easy to change the formats
      available
     */
    public function getTypes()
    {
        $result = array();
        foreach ($this->mime_types as $key => $val) {
            $result[] = array('name' => $key, 'value' => $val);
        }
        echo json_encode($result);
    }
}

// WPReport

/* If the function exists in our class, and it is requested, then run it */

if (isset($_GET['func']) && method_exists('WPReport', $_GET['func'])) {
    $r = new WPReport();
    $r->$_GET['func']();
}

