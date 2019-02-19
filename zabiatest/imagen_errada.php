<?php

    require('inc/configuracion.php');
    require('inc/mysql.php');
    ini_set('max_execution_time', 1500);

    $cnx = new MySQL();
    $query =  "SELECT id_insumo, imagen FROM insumo WHERE estado = 1 AND imagen<>''";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $idInsumo = $sql->field("id_insumo");
        $imagen = $sql->field("imagen");
		$rutaImagen = "imagen/insumo/" . $imagen;
		if (file_exists($rutaImagen)) {
            try {
                $tamano = getimagesize($rutaImagen);
                //if ($tamano <= 1024) {
                if (!@imagecreatefromjpeg($rutaImagen)) {
                    $exec = "UPDATE insumo SET flag_imagen_errada = 1 WHERE id_insumo = $idInsumo";
                    $cnx->execute($exec);
                    echo "ID: $idInsumo - imagen: $imagen - tamaño: $tamano <br/>";
                }
            } catch (Exception $e) {
                $exec = "UPDATE insumo SET flag_imagen_errada = 1 WHERE id_insumo = $idInsumo";
                $cnx->execute($exec);
                echo "ID: $idInsumo - imagen: $imagen - tamaño: $tamano <br/>";
            }
        } else {
            $exec = "UPDATE insumo SET flag_imagen_errada = 1 WHERE id_insumo = $idInsumo";
            $cnx->execute($exec);
                echo "ID: $idInsumo - imagen: $imagen <br/>";
		}
    }
    $cnx->close();
    $cnx = null;

?>