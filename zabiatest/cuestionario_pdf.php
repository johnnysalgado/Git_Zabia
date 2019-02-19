<?php
//    require('inc/sesion.php');
    include('inc/html2pdf.class.php');

    if (isset($_GET['url_html'])) {
        $urlHtml = $_GET['url_html'];
        $html = file_get_contents($urlHtml);
        $h2pdf = new html2pdf();
        $h2pdf->setParam('document_html', $html);
        $h2pdf->convertHTML();
        $h2pdf->downloadCapture('reporte.pdf');
        //$h2pdf->displayCapture();
    }
?>