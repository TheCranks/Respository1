<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- BOOTSTRAP -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
	integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" 
	crossorigin="anonymous">

    <title>Título página</title>

</head>

<body>

<?php

	require_once "mpdf/mpdf.php";


        $cabecera = "<span><img src='ruta_imagen' width='100px' height='50px'/><b>Informe PDF</b></span>";
        $pie = "<span>Descripción pie</span>";
        $mpdf=new mPDF();
        $mpdf->SetHTMLHeader($cabecera);
        $mpdf->SetHTMLFooter($pie);

        $mpdf->WriteHTML('<table class="table-hover table-responsive table-striped">
            <tr>
                <th>CABECERA 1</th>
                <th>CABECERA 2</th>
                <th>CABECERA 3</th>
                <th>CABECERA 4</th>
            </tr>',2);

            $mpdf->WriteHTML('
            	<tr>
                    <td>texto</td>
                    <td>texto2</td>
                    <td>texto3</td>
                    <td>texto4</td>
                </tr>
                ', 2);

            $mpdf->WriteHTML('
            	<tr>
                    <td>texto</td>
                    <td>texto2</td>
                    <td>texto3</td>
                    <td>texto4</td>
                </tr>
                ', 2);
        $mpdf->WriteHTML('</table>',2);
        $mpdf->Output('archivo.pdf','I');
        exit;
?>

</body>

</html>
