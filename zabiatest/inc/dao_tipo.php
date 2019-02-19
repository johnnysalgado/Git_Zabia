<?php
class DaoTipo {

    function __construct() { }

    function listarTipoPlato ($estado = -1, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_PLATO ($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_plato' => $sql->field('id_tipo_plato'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'imagen' => $sql->field('imagen'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerTipoPlato ($idTipoPlato) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_PLATO ($idTipoPlato)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_plato' => $sql->field('id_tipo_plato'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'imagen' => $sql->field('imagen'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearTipoPlato ($nombre, $nombreIngles, $imagen, $usuario) {
        $idTipoCocina = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_PLATO ('$nombre', '$nombreIngles', '$imagen', '$usuario', @p_id_tipo_plato)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_plato AS id_plato");
            $sql->read();
            if ($sql->next()) {
                $idTipoPlato = $sql->field('id_plato');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoCocina;
    }

    function editarTipoPlato ($idTipoPlato, $nombre, $nombreIngles, $imagen, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_PLATO ($idTipoPlato, '$nombre', '$nombreIngles', '$imagen', $estado, '$usuario')";
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

    function listarTipoCocina($estado = -1, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_COCINA ($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_cocina' => $sql->field('id_tipo_cocina'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'imagen' => $sql->field('imagen'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerTipoCocina($idTipoCocina) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_COCINA ($idTipoCocina)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_cocina' => $sql->field('id_tipo_cocina'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'imagen' => $sql->field('imagen'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearTipoCocina ($nombre, $nombreIngles, $imagen, $usuario) {
        $idTipoCocina = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_COCINA ('$nombre', '$nombreIngles', '$imagen', '$usuario', @p_id_tipo_cocina)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_cocina AS id_cocina");
            $sql->read();
            if ($sql->next()) {
                $idTipoCocina = $sql->field('id_tipo_cocina');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoCocina;
    }

    function editarTipoCocina ($idTipoCocina, $nombre, $nombreIngles, $imagen, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_COCINA ($idTipoCocina, '$nombre', '$nombreIngles', '$imagen', $estado, '$usuario')";
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

    function listarTipoActividad($estado = -1, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_ACTIVIDAD ($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_actividad' => $sql->field('id_tipo_actividad'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'pal_factor' => $sql->field('pal_factor'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

}
?>