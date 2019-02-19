<?php
class DaoReceta {

    function __construct() { }

    function crearReceta($nombre, $nombreIngles, $nombreImagen, $imagen, $porcion, $preparacion, $preparacionIngles, $autor, $tiempo, $top, $codigoPais, $idRegion, $codigoDificultad, $referencia, $tag, $usuario) {
        $idReceta = 0;
        if ($idRegion == 0) {
            $idRegion = "NULL";
        }
        $execute = "CALL USP_CREA_PLATO ('$nombre', '$nombreIngles', '$nombreImagen', $porcion, '$preparacion', '$preparacionIngles', '$autor', '$tiempo', $top";
        if (trim($codigoPais) == "") {
            $execute .= ", NULL";
        } else {
            $execute .= ", '$codigoPais'";
        }
        $execute .= ", $idRegion";
        if (trim($codigoDificultad) == "") {
            $execute .= ", NULL";
        } else {
            $execute .= ", '$codigoDificultad'";
        }
        $execute .= ", '$referencia', '$tag', '$usuario', @p_id_plato);";
        $cnx = new MySQL();
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_plato AS id_plato");
        $sql->read();
        if ($sql->next()) {
            $idPlato = $sql->field('id_plato');
        }
        $cnx->close();
        $cnx = null;
        return $idPlato;
    }

    function editarReceta($idReceta, $nombre, $nombreIngles, $nombreImagen, $porcion, $preparacion, $preparacionIngles, $autor, $tiempo, $top, $codigoPais, $idRegion, $codigoDificultad, $referencia, $tag, $estado, $usuario) {
        $resultado = true;
        if ($idRegion == 0) {
            $idRegion = "NULL";
        }
        $execute = "CALL USP_EDIT_PLATO ($idReceta, '$nombre', '$nombreIngles', '$nombreImagen', $porcion, '$preparacion', '$preparacionIngles', '$autor', '$tiempo', $top";
        if (trim($codigoPais) == "") {
            $execute .= ", NULL";
        } else {
            $execute .= ", '$codigoPais'";
        }
        $execute .= ", $idRegion";
        if (trim($codigoDificultad) == "") {
            $execute .= ", NULL";
        } else {
            $execute .= ", '$codigoDificultad'";
        }
        $execute .= ", '$referencia', '$tag', $estado, '$usuario');";
        $cnx = new MySQL();
        try {
            $cnx->execute($execute);
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }

    function obtenerReceta($idReceta) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_PLATO ($idReceta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_plato' => $sql->field('id_plato'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'imagen' => $sql->field('imagen'), 'porcion' => $sql->field('porcion'), 'preparacion' => $sql->field('preparacion'), 'preparacion_ing' => $sql->field('preparacion_ing'), 'autor' => $sql->field('autor'), 'tiempo' => $sql->field('tiempo'), 'megusta' => $sql->field('megusta'), 'top' => $sql->field('top'), 'kcal' => $sql->field('kcal'), 'precio' => $sql->field('precio'), 'grasa' => $sql->field('grasa'), 'cod_pais' => $sql->field('cod_pais'), 'pais' => $sql->field('pais'), 'id_region' => $sql->field('id_region'), 'region' => $sql->field('region'), 'cod_dificultad' => $sql->field('cod_dificultad'), 'dificultad' => $sql->field('dificultad'), 'dificultad_ing' => $sql->field('dificultad_ing'), 'referencia' => $sql->field('referencia'), 'tag' => $sql->field('tag'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    /*
    function listarEnfermedad($estado = -1, $lenguaje = "es", $idTipoCategoriaPrecondicion = 0) {
        $arreglo = array();
        $query = "CALL USP_LIST_ENFERMEDAD($estado, '$lenguaje', $idTipoCategoriaPrecondicion)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_enfermedad' => $sql->field('id_enfermedad'), 'id_tipo_categoria_precondicion' => $sql->field('id_tipo_categoria_precondicion'), 'tipo_categoria_precondicion' => $sql->field('tipo_categoria_precondicion'), 'tipo_categoria_precondicion_ing' => $sql->field('tipo_categoria_precondicion_ing'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'recomendacion' => $sql->field('recomendacion'), 'recomendacion_ing' => $sql->field('recomendacion_ing'), 'referencia' => $sql->field('referencia'), 'estado' => $sql->field('estado'), 'imagen' => $sql->field('imagen'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
    */
    function listarDificultad($estado = -1) {
        $arreglo = array();
        $query = "CALL USP_LIST_DIFICULTAD ($estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('cod_dificultad' => $sql->field('cod_dificultad'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'estado' => $sql->field('estado'), 'imagen' => $sql->field('imagen'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

}

?>