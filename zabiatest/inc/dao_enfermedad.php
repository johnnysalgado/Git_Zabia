<?php
class DaoEnfermedad {

    function __construct() { }

    function listarNutrientePorEnfermedad($idEnfermedad, $tipoNutriente = 0, $clase = 0, $categoria = 0, $familia = 0, $subfamilia = 0, $flagEsencial = "", $nombreNutriente = "", $flagAplica = 1) {
        $arreglo = array();
        $query = "CALL USP_LIST_ENFERMEDAD_NUTRIENTE($idEnfermedad, $tipoNutriente, $clase, $categoria, $familia, $subfamilia, '$flagEsencial', '$nombreNutriente', $flagAplica)";
//        echo $query;
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_nutriente' => $sql->field('id_nutriente'), 'nutriente' => $sql->field('nutriente'), 'tipo_nutriente' => $sql->field('tipo_nutriente'), 'tipo_nutriente_ing' => $sql->field('tipo_nutriente_ing'), 'tipo_clase' => $sql->field('tipo_clase'), 'tipo_clase_ing' => $sql->field('tipo_clase_ing'), 'id_enfermedad_nutriente' => $sql->field('id_enfermedad_nutriente'), 'unidad' => $sql->field('unidad'), 'flag_restringir' => $sql->field('flag_restringir'), 'valor_restringir' => $sql->field('valor_restringir'), 'flag_eliminar' => $sql->field('flag_eliminar'), 'valor_eliminar' => $sql->field('valor_eliminar'), 'flag_aumentar' => $sql->field('flag_aumentar'), 'flag_normal' => $sql->field('flag_normal'), 'flag_esencial' => $sql->field('flag_esencial'), 'rdi' => $sql->field('rdi'), 'rda' => $sql->field('rda'), 'ea' => $sql->field('ea'), 'flag_rdi' => $sql->field('flag_rdi')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function grabarEnfermedadNutriente($idEnfermedad, $idNutriente, $unidad, $flagRestringir, $valorRestringir, $flagEliminar, $valorEliminar, $flagAumentar, $flagNormal, $usuario) {
        $resultado = true;
        $execute = "CALL USP_UPD_ENFERMEDAD_NUTRIENTE($idEnfermedad, $idNutriente, ";
        if ($unidad == "") {
            $execute .= "NULL, ";
        } else {
            $execute .= "'$unidad', ";
        }
        $execute .= " $flagRestringir, $valorRestringir, $flagEliminar, $valorEliminar, $flagAumentar, $flagNormal, '$usuario')";
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

    function crearEnfermedad($idTipoCategoriaPrecondicion, $nombre, $nombreIngles, $nombreImagen, $recomendacion, $recomendacionIngles, $referencia, $usuario) {
        $idEnfermedad = 0;
        if ($idTipoCategoriaPrecondicion == 0) {
            $idTipoCategoriaPrecondicion = "NULL";
        }
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_ENFERMEDAD ($idTipoCategoriaPrecondicion, '$nombre', '$nombreIngles', '$nombreImagen', '$recomendacion', '$recomendacionIngles', '$referencia', '$usuario', @p_id_enfermedad);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_enfermedad AS id_enfermedad");
        $sql->read();
        if ($sql->next()) {
            $idEnfermedad = $sql->field('id_enfermedad');
        }
        $cnx->close();
        $cnx = null;
        return $idEnfermedad;
    }

    function editarEnfermedad($idEnfermedad, $idTipoCategoriaPrecondicion, $nombre, $nombreIngles, $nombreImagen, $recomendacion, $recomendacionIngles, $referencia, $estado, $usuario) {
        $resultado = true;
        if ($idTipoCategoriaPrecondicion == 0) {
            $idTipoCategoriaPrecondicion = "NULL";
        }
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_ENFERMEDAD ($idEnfermedad, $idTipoCategoriaPrecondicion, '$nombre', '$nombreIngles', '$nombreImagen', '$recomendacion', '$recomendacionIngles', '$referencia', $estado, '$usuario');";
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

    function obtenerEnfermedad($idEnfermedad) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_ENFERMEDAD($idEnfermedad)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_enfermedad' => $sql->field('id_enfermedad'), 'id_tipo_categoria_precondicion' => $sql->field('id_tipo_categoria_precondicion'), 'tipo_categoria_precondicion' => $sql->field('tipo_categoria_precondicion'), 'tipo_categoria_precondicion_ing' => $sql->field('tipo_categoria_precondicion_ing'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'recomendacion' => $sql->field('recomendacion'), 'recomendacion_ing' => $sql->field('recomendacion_ing'), 'referencia' => $sql->field('referencia'), 'estado' => $sql->field('estado'), 'imagen' => $sql->field('imagen'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

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
    
    function crearTipoCategoriaPrecondicion($nombre, $nombreIngles, $usuario) {
        $idTipoCategoriaPrecondicion = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_CATEGORIA_PRECONDICION ('$nombre', '$nombreIngles', '$usuario', @p_id_tipo_categoria_precondicion)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_categoria_precondicion AS id_tipo_categoria_precondicion");
            $sql->read();
            if ($sql->next()) {
                $idTipoCategoriaPrecondicion = $sql->field('id_tipo_categoria_precondicion');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoCategoriaPrecondicion;
    }

    function editarTipoCategoriaPrecondicion($idTipoCategoriaPrecondicion, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_CATEGORIA_PRECONDICION ($idTipoCategoriaPrecondicion, '$nombre', '$nombreIngles', $estado, '$usuario')";
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

    function obtenerTipoCategoriaPrecondicion($idTipoCategoriaPrecondicion) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_CATEGORIA_PRECONDICION ($idTipoCategoriaPrecondicion)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_tipo_categoria_precondicion' => $sql->field('id_tipo_categoria_precondicion'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoCategoriaPrecondicion($estado = -1, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_CATEGORIA_PRECONDICION($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_categoria_precondicion' => $sql->field('id_tipo_categoria_precondicion'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
    
    function listarTipoDietaPorPrecondicion($idEnfermedad, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_DIETA_X_ENFERMEDAD ($idEnfermedad, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_dieta' => $sql->field('id_tipo_dieta'), 'tipo_dieta' => $sql->field('tipo_dieta'), 'tipo_dieta_ing' => $sql->field('tipo_dieta_ing'), 'id_enfermedad_tipo_dieta' => $sql->field('id_enfermedad_tipo_dieta'), 'id_enfermedad' => $sql->field('id_enfermedad'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function editarPrecondicionTipoDieta($idEnfermedad, $idtTipoDieta, $usuario) {
        $resultado = true;
        $execute = "CALL USP_UPD_ENFERMEDAD_TIPO_DIETA ($idEnfermedad, $idtTipoDieta, '$usuario')";
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

    function eliminarPrecondicionTipoDieta($idEnfermedad, $usuario) {
        $resultado = true;
        $execute = "CALL USP_ELIM_ENFERMEDAD_TIPO_DIETA ($idEnfermedad, '$usuario')";
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

    function actualizarTablaPlanaInsumoNutrientePrecondicion($idEnfermedad, $usuario) {
        $resultado = true;
        $execute = "CALL USP_CALC_TABLA_INSUMO_NUTRIENTE_PRECONDICION_X_PRECONDICION ($idEnfermedad, '$usuario')";
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
}

?>