<?php
require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/constante.php');
require('../inc/constante_cuestionario.php');
require('../inc/constante_informe.php');

    $user = "";
    $porDescarga = "0";
    $porCorreo = "0";
    $urlFinalHtml = "";

    if (isset($_GET["user"])) {
        $user = $_GET["user"];
    }
    if (isset($_GET["download"])) {
        $porDescarga = $_GET["download"];
    }
    if (isset($_GET["email"])) {
        $porCorreo = $_GET["email"];
    }

    if ($user != "") {
  
        $cnx = new MySQL();

        $user = str_replace("'", "", $user);

        //Si no ha elegido enviar por correo, por defecto se descargará.
        if ($porCorreo == "0" || $porCorreo == "") {
            $porDescarga = "1";
        }

        //traer imc;
        $imc = 0;
        $consulta = "SELECT COALESCE(SUM(a.peso)/ (SUM(talla) * SUM(talla)), 0) AS imc FROM ( SELECT CASE WHEN b.codigo = 'PESO' THEN a.respuesta END AS peso, CASE WHEN b.codigo = 'TALLA' THEN a.respuesta END AS talla FROM usuario_pregunta_respuesta a INNER JOIN pregunta b ON a.id_pregunta = b.id_pregunta WHERE a.estado = 1 AND b.estado = 1 AND (b.codigo = 'PESO' OR b.codigo = 'TALLA') AND a.id_usuario = " . $user . ") a";

        $sql = $cnx->query($consulta);
        $sql->read();
        if ($sql->next()) {
            $imc = $sql->field('imc');
        }

        //pdf
        $urlHtml = '';
        $query = "SELECT DISTINCT a.id_notainforme, a.titulo AS titulo_seccion, a.parrafo AS parrafo_seccion FROM notainforme a INNER JOIN notainforme b ON a.id_notainforme = b.id_notainforme_padre WHERE a.estado = 1 AND b.estado = 1 AND b.id_notainforme IN ( SELECT DISTINCT id_notainforme FROM notainforme_cuestionario WHERE id_respuesta IN ( SELECT b.id_respuesta FROM pregunta a INNER JOIN usuario_pregunta_respuesta b ON a.id_pregunta = b.id_pregunta WHERE (a.estado = 1) AND (b.estado = 1) AND (b.id_usuario = " . $user . ") AND (a.tipo_respuesta = '" . TIPO_RESPUESTA_MULTIPLE . "' OR a.tipo_respuesta = '" . TIPO_RESPUESTA_UNICA . "') )";
        if ($imc > 0) {
            $query .= " OR ( codigo_especial = '";
            if ($imc > 30) {
                $query .= IMC_MAY30;
            } else if ($imc > 25) {
                $query .= IMC_MAY25;
            } else {
                $query .= IMC_MEN25;
            }
            $query .= "' )";
        }
        $query .= " ) ORDER BY a.orden";

        $htmlInforme = '<!DOCTYPE html>';
        $htmlInforme .= ' <html lang="es">';
        $htmlInforme .= ' <head> <title>PDF - Zabia Report</title> ';
        $htmlInforme .= ' <style> ';
        $htmlInforme .= ' img {display: block; margin-left: auto; margin-right: auto; width: 40%; border: solid 2px green; }';
        $htmlInforme .= ' span {font-family: verdana; font-size: 14px; }';
        $htmlInforme .= ' .imagen {padding: 10px 5px 5px 5px; }';
        $htmlInforme .= ' </style> ';
        $htmlInforme .= ' </head>';
        $htmlInforme .= ' <body>';
        $htmlInforme .= ' <center> <h1 style="font-family:verdana;"> Reporte de prueba </h1> </center>';
        $sql = $cnx->query($query);
        $sql->read();
        while($sql->next()) {
            $idSeccion = $sql->field('id_notainforme');
            $tituloSeccion = $sql->field('titulo_seccion');
            $parrafoSeccion = $sql->field('parrafo_seccion');
            $htmlInforme .= ' <h2 style="font-family:verdana;">' . $tituloSeccion . '</h2>';
            if ($parrafoSeccion != '') {
                $htmlInforme .= ' <div> <span>' . $parrafoSeccion . '</span> </div>';
            }
            $query = "SELECT DISTINCT a.id_notainforme, a.titulo AS titulo_nota, a.parrafo AS parrafo_nota FROM notainforme a WHERE a.estado = 1 AND a.id_notainforme_padre = " . $idSeccion . " AND a.id_notainforme IN ( SELECT DISTINCT id_notainforme FROM notainforme_cuestionario WHERE id_respuesta IN ( SELECT b.id_respuesta FROM pregunta a INNER JOIN usuario_pregunta_respuesta b ON a.id_pregunta = b.id_pregunta WHERE (a.estado = 1) AND (b.estado = 1) AND (b.id_usuario = " . $user . ") AND (a.tipo_respuesta = '" . TIPO_RESPUESTA_MULTIPLE . "' OR a.tipo_respuesta = '" . TIPO_RESPUESTA_UNICA . "') )";
            if ($imc > 0) {
                $query .= " OR ( codigo_especial = '";
                if ($imc > 30) {
                    $query .= IMC_MAY30;
                } else if ($imc > 25) {
                    $query .= IMC_MAY25;
                } else {
                    $query .= IMC_MEN25;
                }
                $query .= "' )";
            }
            $query .= " ) ORDER BY a.orden";
            $sql2 = $cnx->query($query);
            $sql2->read();
            while($sql2->next()) {
                $idNota = $sql2->field('id_notainforme');
                $tituloNota = $sql2->field('titulo_nota');
                $parrafoNota = $sql2->field('parrafo_nota');
                $htmlInforme .= ' <h3 style="font-family: verdana;">' . $tituloNota . '</h3>';
                if ($parrafoNota != '') {
                    $htmlInforme .= ' <div> <span>' . $parrafoNota . '</span> </div>';
                }
                $query = "SELECT a.nombre FROM notainforme_imagen a WHERE a.estado = 1 AND a.id_notainforme = " . $idNota;
                $sql3 = $cnx->query($query);
                $sql3->read();
                while($sql3->next()) {
                    $imagen = BASE_PATH . REPORT_IMAGE_SHORT_PATH . $sql3->field('nombre');
                    $htmlInforme .= ' <div class="imagen"> <img src="' . $imagen . '" alt="" /> </div>';
                }
            }
        }
        $htmlInforme .= ' </body>';
        $htmlInforme .= ' </html>';

        //crea el archivo html
        $mt = microtime(true);
        $mt =  $mt*1000;
        $ticks = (string)$mt*10;
        $urlHtml = '../' . PDF_SHORT_PATH . 'informe' . $ticks . '.html';
    //    $urlFinalHtml = '../cuestionario_pdf.php?url_html=' . PDF_SHORT_PATH . 'informe' . $ticks . '.html'; //localhost
        $urlFinalHtml = BASE_PATH . 'cuestionario_pdf.php?url_html=' . BASE_PATH . PDF_SHORT_PATH . 'informe' . $ticks . '.html';

        $archivo = fopen($urlHtml, 'a');
        fputs($archivo, $htmlInforme);
        fclose($archivo);

        if ($porCorreo == "1") {
            $correoUsuario = "";
            $nombreUsuario = "";
            $consulta = "SELECT email, nombre FROM usuario WHERE id_usuario = " . $user;
            $sql = $cnx->query($consulta);
            $sql->read();
            if ($sql->next()) {
                $correoUsuario = $sql->field('email');
                $nombreUsuario = $sql->field('nombre');
            }
            $asunto = "Reporte de Salud";
            $mensaje = "Sr(ta) " . $nombreUsuario;
            $mensaje .= "\n\n Descargue por favor el ";
            $mensaje .= " <a href=\"" . $urlFinalHtml . "\">reporte de salud</a>";
            $mensaje .= "\n\n Atentamente.";
            $mensaje .= "\n Grupo Zabia.";
            $headers = "From: webmaster@zabia.com";

            //mail($correoUsuario, $asunto, $mensaje, $headers);
        }

        $cnx = null;

        if ($porDescarga == "1") {
            header("Location: " . $urlFinalHtml);
            die();
        }
    }
?>
