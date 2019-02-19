<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/constante_insumo.php');
    require('inc/functions.php');
    require('inc/mysql.php');

    $mensajeError = '';

    set_time_limit(0);
    require_once 'Classes/PHPExcel/IOFactory.php';

    $usuario = $_SESSION["U"];
    $uploadOk = 1;
    $mensajeError = '';
    $nombreArchivo = '';
    $rutaArchivo = '';

    /*
    $rutaExcel = "excel/insumo/";
    $files = scandir($rutaExcel);

    $files = array_diff(scandir($rutaExcel), array('.', '..'));

    foreach ($files as $excel) {
        $rutaArchivo = $rutaExcel . $excel;
        $objPHPExcel = PHPExcel_IOFactory::load($rutaArchivo);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $totalItems = count($sheetData);
        $correctos = 0;
        echo "Archivo: $excel <br/>";
        $cnx = new MySQL();
        foreach ($sheetData as $item) {
            $id_ = $item["A"];
            $id_ = trim($id_);
            $imagen = $item["G"];
            $urlImagen = $item["G"];
            $posSlash = strrpos($imagen, '/');
            $nombreImagen = substr($imagen, $posSlash + 1, strlen($imagen) - $posSlash + 1);
            if ($nombreImagen != '') {
//                $contenido = getRemoteFile($imagen);
//                if ($contenido) {
//                    $nombreImagen = obtieneNombreImagen($nombreImagen, $id_);
//                    $rutaImagen = INSUMO_IMAGE_SHORT_PATH . $nombreImagen;
//                    file_put_contents($rutaImagen, $contenido);
//                } else {
//                    $correctos++;
                    echo "-Imagen: $nombreImagen<br/>";
//                    $nombreImagen = "";
                    $execute = "INSERT INTO insumo_imagen_tmp (id_insumo, url_imagen) VALUES ($id_, '$urlImagen');";
                    $cnx->execute($execute);
//                }
                //$execute = "UPDATE insumo SET imagen = '" . $nombreImagen . "', fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario ."' WHERE id_insumo=" . $id_;
//                $cnx = new MySQL();
//                $cnx->execute($execute);
//                $cnx->close();
//                $cnx = null;
            }
        }
        $cnx->close();
        $cnx = null;
        echo "Procesados: $totalItems - Incorrectos: $correctos <br/> <hr/>";
    }
    */
    $correctos = 0;
    $cnx = new MySQL();
    $query = "SELECT a.id_insumo, b.url_imagen FROM insumo a INNER JOIN insumo_imagen_tmp b ON a.id_insumo = b.id_insumo WHERE a.estado = 1 AND a.flag_imagen_errada = 1;";
    $sql = $cnx->query($query);
    $sql->read();
    $totalItems = $sql->count();
    while($sql->next()) {
        $id_ = $sql->field('id_insumo');
        $urlImagen = $sql->field('url_imagen');
        $posSlash = strrpos($urlImagen, '/');
        $nombreImagen = substr($urlImagen, $posSlash + 1, strlen($urlImagen) - $posSlash + 1);
        if ($nombreImagen != '') {
            $contenido = getRemoteFile($urlImagen);
            if ($contenido) {
                $correctos++;
                $nombreImagen = obtieneNombreImagen($nombreImagen, $id_);
                $rutaImagen = INSUMO_IMAGE_SHORT_PATH . $nombreImagen;
                file_put_contents($rutaImagen, $contenido);
                echo "-Imagen correcta: $nombreImagen<br/>";
                $execute = "UPDATE insumo SET flag_imagen_errada = 0 WHERE id_insumo = $id_";
                $cnx->execute($execute);
            } else {
                echo "-Imagen errada: $nombreImagen<br/>";
                $nombreImagen = "";
                $execute = "UPDATE insumo SET flag_imagen_errada = 2 WHERE id_insumo = $id_";
                $cnx->execute($execute);
            }
            $execute = "UPDATE insumo SET imagen = '" . $nombreImagen . "', fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $usuario ."' WHERE id_insumo=" . $id_;
            $cnx->execute($execute);
        }
    }
    echo "Procesados: $totalItems - Correctos: $correctos <br/> <hr/>";
    $cnx->close();
    $cnx = null;
?>
