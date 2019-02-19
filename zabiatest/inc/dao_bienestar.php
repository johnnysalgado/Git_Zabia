<?php
class DaoBienestar {

    function __construct() { }

    function listarBienestar($estado = -1) {
        $arreglo = array();
        $query = "CALL USP_LIST_BIENESTAR($estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_bienestar' => $sql->field('id_bienestar'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'descripcion' => $sql->field('descripcion') , 'descripcion_ing' => $sql->field('descripcion_ing'), 'icono_clase' => $sql->field('icono_clase'), 'orden' => $sql->field('orden'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerBienestar($idBienestar) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_BIENESTAR($idBienestar)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_bienestar' => $sql->field('id_bienestar'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'icono_clase' => $sql->field('icono_clase'), 'orden' => $sql->field('orden'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearBienestar($nombre, $nombreIngles, $descripcion, $descripcionIngles, $iconoClase, $orden, $usuario) {
        $idBienestar = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_BIENESTAR ('$nombre', '$nombreIngles', '$descripcion', '$descripcionIngles', '$iconoClase', $orden, '$usuario', @p_id_bienestar);";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_bienestar AS id_bienestar");
            $sql->read();
            if ($sql->next()) {
                $idBienestar = $sql->field('id_bienestar');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idBienestar;
    }

    function editarBienestar($idBienestar, $nombre, $nombreIngles, $descripcion, $descripcionIngles, $iconoClase, $orden, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_BIENESTAR ($idBienestar, '$nombre', '$nombreIngles', '$descripcion', '$descripcionIngles', '$iconoClase', $orden, '$usuario');";
        try {
            $cnx->execute($execute);
        } catch(Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }

    function listarRespuestaBienestar($idBienestar) {
        $arreglo = array();
        $query = "CALL USP_LIST_RESPUESTA_BIENESTAR($idBienestar)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_respuesta_bienestar' => $sql->field('id_respuesta_bienestar'), 'id_respuesta' => $sql->field('id_respuesta'), 'id_bienestar' => $sql->field('id_bienestar'), 'valor' => $sql->field('valor'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarRespuestaBienestarPorRespuesta($idRespuesta) {
        $arreglo = array();
        $query = "CALL USP_LIST_RESPUESTA_BIENESTAR_X_RESPUESTA($idRespuesta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_bienestar' => $sql->field('id_bienestar'), 'bienestar' => $sql->field('bienestar'), 'id_respuesta_bienestar' => $sql->field('id_respuesta_bienestar'), 'id_respuesta' => $sql->field('id_respuesta'), 'valor' => $sql->field('valor')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarRespuestaBienestarTotal($idBienestar) {
        $arreglo = array();
        $query = "CALL USP_LIST_RESPUESTA_BIENESTAR_TOTAL($idBienestar)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_bienestar' => $sql->field('id_bienestar'), 'valor' => $sql->field('valor'), 'bienestar' => $sql->field('bienestar'), 'bienestar_ing' => $sql->field('bienestar_ing'), 'orden' => $sql->field('orden')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarUsuarioRespuestaBienestar($idUsuario, $idBienestar) {
        $arreglo = array();
        $query = "CALL USP_LIST_USUARIO_RESPUESTA_BIENESTAR($idUsuario, $idBienestar)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_bienestar' => $sql->field('id_bienestar'), 'valor' => $sql->field('valor')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerPorcentajeBienestar($idUsuario, $idBienestar) {
        $arregloRespuestaBienestarTotal = $this->listarRespuestaBienestarTotal($idBienestar);
        $arregloUsuarioRespuestaBienestar = $this->listarUsuarioRespuestaBienestar($idUsuario, $idBienestar);
        $valorTotal = 0;
        foreach($arregloRespuestaBienestarTotal as $item) {
            $valorTotal += $item['valor'];
        }
        $valorUsuario = 0;
        foreach($arregloUsuarioRespuestaBienestar as $item) {
            $valorUsuario += $item['valor'];
        }
        $porcentaje = Round((100 * ($valorUsuario / $valorTotal)), 2);
        return $porcentaje;
    }

    function obtenerPorcentajeBienestarPorBienestar($idUsuario, $idBienestar) {
        return $this->obtenerPorcentajeBienestar($idUsuario, $idBienestar);
    }

    function obtenerPorcentajeBienestarTotal($idUsuario) {
        return $this->obtenerPorcentajeBienestar($idUsuario, 0);
    }

    function eliminarRespuestaBienestar($idRespuesta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_RESPUESTA_BIENESTAR ($idRespuesta, '$usuario');";
        try {
            $cnx->execute($execute);
        } catch(Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }

    function grabarRespuestaBienestar($idRespuesta, $idBienestar, $valor, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_RESPUESTA_BIENESTAR ($idRespuesta, $idBienestar, $valor, '$usuario');";
        echo $execute;
        try {
            $cnx->execute($execute);
        } catch(Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }
}

?>