<?php
class DaoTrastornoEstomacal {

    function __construct() { }

    function crearTrastornoEstomacal($nombre, $nombreIngles, $nombreImagen, $recomendacion, $recomendacionIngles, $referencia, $usuario) {
        $idTrastornoEstomacal = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TRASTORNO_ESTOMACAL ('$nombre', '$nombreIngles', '$nombreImagen', '$recomendacion', '$recomendacionIngles', '$referencia', '$usuario', @p_id_trastorno_estomacal);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_trastorno_estomacal AS id_trastorno_estomacal");
        $sql->read();
        if ($sql->next()) {
            $idTrastornoEstomacal = $sql->field('id_trastorno_estomacal');
        }
        $cnx->close();
        $cnx = null;
        return $idTrastornoEstomacal;
    }

    function editarTrastornoEstomacal($idTrastornoEstomacal, $nombre, $nombreIngles, $nombreImagen, $recomendacion, $recomendacionIngles, $referencia, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_TRASTORNO_ESTOMACAL ($idTrastornoEstomacal, '$nombre', '$nombreIngles', '$nombreImagen', '$recomendacion', '$recomendacionIngles', '$referencia', $estado, '$usuario');";
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

    function listarTrastornoEstomacal($estado = -1, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_TRASTORNO_ESTOMACAL ($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_trastorno_estomacal' => $sql->field('id_trastorno_estomacal'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'imagen' => $sql->field('imagen'), 'recomendacion' => $sql->field('recomendacion'), 'recomendacion_ing' => $sql->field('recomendacion_ing'), 'referencia' => $sql->field('referencia'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
	
    function obtenerTrastornoEstomacal($idTrastornoEstomacal) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TRASTORNO_ESTOMACAL ($idTrastornoEstomacal)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if  ($sql->next()) {
            array_push($arreglo, array('id_trastorno_estomacal' => $sql->field('id_trastorno_estomacal'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'imagen' => $sql->field('imagen'), 'recomendacion' => $sql->field('recomendacion'), 'recomendacion_ing' => $sql->field('recomendacion_ing'), 'referencia' => $sql->field('referencia'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoAlimentosPorTrastornoEstomacal($idTrastornoEstomacal) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_ALIMENTO_TRASTORNO_ESTOMACAL($idTrastornoEstomacal)";
//        echo $query;
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_alimento_trastorno_estomacal' => $sql->field('id_tipo_alimento_trastorno_estomacal'), 'tipo_alimento' => $sql->field('tipo_alimento'), 'id_tipo_alimento' => $sql->field('id_tipo_alimento'), 'accion' => $sql->field('accion')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function grabarTrastornoEstomacalTipoAlimento($idTrastornoEstomacal, $idTipoAlimento, $accion, $prioridad, $usuario) {
        $resultado = true;
        $execute = "CALL USP_UPD_TRASTORNO_ESTOMACAL_TIPO_ALIMENTO($idTrastornoEstomacal, $idTipoAlimento, '$accion', $prioridad, '$usuario')";
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