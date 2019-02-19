<?php
class DaoIntolerancia {

    function __construct() { }

    function crearIntolerancia($nombre, $nombreIngles, $nombreImagen, $recomendacion, $recomendacionIngles, $referencia, $usuario) {
        $idIntolerancia = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_INTOLERANCIA ('$nombre', '$nombreIngles', '$nombreImagen', '$recomendacion', '$recomendacionIngles', '$referencia', '$usuario', @p_id_intolerancia);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_intolerancia AS id_intolerancia");
        $sql->read();
        if ($sql->next()) {
            $idIntolerancia = $sql->field('id_intolerancia');
        }
        $cnx->close();
        $cnx = null;
        return $idIntolerancia;
    }

    function editarIntolerancia($idIntolerancia, $nombre, $nombreIngles, $nombreImagen, $recomendacion, $recomendacionIngles, $referencia, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_INTOLERANCIA ($idIntolerancia, '$nombre', '$nombreIngles', '$nombreImagen', '$recomendacion', '$recomendacionIngles', '$referencia', $estado, '$usuario');";
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

    function listarIntolerancia($estado = -1, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_INTOLERANCIA($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_intolerancia' => $sql->field('id_intolerancia'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'imagen' => $sql->field('imagen'), 'recomendacion' => $sql->field('recomendacion'), 'recomendacion_ing' => $sql->field('recomendacion_ing'), 'referencia' => $sql->field('referencia'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
	
    function obtenerIntolerancia($idIntolerancia) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_INTOLERANCIA($idIntolerancia)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if  ($sql->next()) {
            array_push($arreglo, array('id_intolerancia' => $sql->field('id_intolerancia'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'imagen' => $sql->field('imagen'), 'recomendacion' => $sql->field('recomendacion'), 'recomendacion_ing' => $sql->field('recomendacion_ing'), 'referencia' => $sql->field('referencia'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoAlimentosPorIntolerancia($idIntolerancia) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_ALIMENTO_INTOLERANCIA($idIntolerancia)";
//        echo $query;
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_alimento_intolerancia' => $sql->field('id_tipo_alimento_intolerancia'), 'tipo_alimento' => $sql->field('tipo_alimento'), 'id_tipo_alimento' => $sql->field('id_tipo_alimento'), 'accion' => $sql->field('accion')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
	function grabarIntoleranciaTipoAlimento($idIntolerancia, $idTipoAlimento, $accion, $prioridad, $usuario) {
        $resultado = true;
        $execute = "CALL USP_UPD_INTOLERANCIA_TIPO_ALIMENTO($idIntolerancia, $idTipoAlimento, '$accion', $prioridad, '$usuario')";
        $cnx = new MySQL();
        try {
            echo $execute;
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