<?php
class DaoNutriente {

    function __construct() { }

    function listarNutriente($idTipoNutriente = 0, $idTipoClase = 0, $idTipoCategoria = 0, $idTipoFamilia = 0, $idTipoSubfamilia = 0, $flagEsencial = "", $estado = -1) {
        $arreglo = array();
        $query = "CALL USP_LIST_NUTRIENTE($idTipoNutriente, $idTipoClase, $idTipoCategoria, $idTipoFamilia, $idTipoSubfamilia, '$flagEsencial', $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_nutriente' => $sql->field('id_nutriente'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'unidad' => $sql->field('unidad'), 'cota_inferior' => $sql->field('cota_inferior'), 'cota_superior' => $sql->field('cota_superior'), 'aporte' => $sql->field('aporte'), 'rdi' => $sql->field('rdi'), 'rda' => $sql->field('rda'), 'ea' => $sql->field('ea'), 'id_tipo_nutriente' => $sql->field('id_tipo_nutriente'), 'tipo_nutriente' => $sql->field('tipo_nutriente'), 'id_tipo_clase' => $sql->field('id_tipo_clase'), 'tipo_clase' => $sql->field('tipo_clase'), 'id_tipo_categoria' => $sql->field('id_tipo_categoria'), 'tipo_categoria' => $sql->field('tipo_categoria'), 'id_tipo_familia' => $sql->field('id_tipo_familia'), 'tipo_familia' => $sql->field('tipo_familia'), 'id_tipo_subfamilia' => $sql->field('id_tipo_subfamilia'), 'tipo_subfamilia' => $sql->field('tipo_subfamilia'), 'codigo_externo' => $sql->field('codigo_externo'), 'flag_esencial' => $sql->field('flag_esencial'), 'recomendacion' => $sql->field('recomendacion'), 'recomendacion_ing' => $sql->field('recomendacion_ing'), 'referencia' => $sql->field('referencia'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoNutriente() {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_NUTRIENTE()";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_nutriente' => $sql->field('id_tipo_nutriente'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarClaseNutriente($idTipoNutriente) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_CLASE($idTipoNutriente)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_clase' => $sql->field('id_tipo_clase'), 'id_tipo_nutriente' => $sql->field('id_tipo_nutriente'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarCategoriaNutriente($idTipoClase) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_CATEGORIA($idTipoClase)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_categoria' => $sql->field('id_tipo_categoria'), 'id_tipo_clase' => $sql->field('id_tipo_clase'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarFamiliaNutriente($idTipoCategoria) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_FAMILIA($idTipoCategoria)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_familia' => $sql->field('id_tipo_familia'), 'id_tipo_categoria' => $sql->field('id_tipo_categoria'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarSubfamiliaNutriente($idTipoFamilia) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_SUBFAMILIA($idTipoFamilia)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_subfamilia' => $sql->field('id_tipo_subfamilia'), 'id_tipo_familia' => $sql->field('id_tipo_familia'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerNutriente($idNutriente) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_NUTRIENTE($idNutriente)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_nutriente' => $sql->field('id_nutriente'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'unidad' => $sql->field('unidad'), 'cota_inferior' => $sql->field('cota_inferior'), 'cota_superior' => $sql->field('cota_superior'), 'aporte' => $sql->field('aporte'), 'rdi' => $sql->field('rdi'), 'rda' => $sql->field('rda'), 'ea' => $sql->field('ea'), 'id_tipo_nutriente' => $sql->field('id_tipo_nutriente'), 'tipo_nutriente' => $sql->field('tipo_nutriente'), 'id_tipo_clase' => $sql->field('id_tipo_clase'), 'tipo_clase' => $sql->field('tipo_clase'), 'id_tipo_categoria' => $sql->field('id_tipo_categoria'), 'tipo_categoria' => $sql->field('tipo_categoria'), 'id_tipo_familia' => $sql->field('id_tipo_familia'), 'tipo_familia' => $sql->field('tipo_familia'), 'id_tipo_subfamilia' => $sql->field('id_tipo_subfamilia'), 'tipo_subfamilia' => $sql->field('tipo_subfamilia'), 'codigo_externo' => $sql->field('codigo_externo'), 'flag_esencial' => $sql->field('flag_esencial'), 'recomendacion' => $sql->field('recomendacion'), 'recomendacion_ing' => $sql->field('recomendacion_ing'), 'referencia' => $sql->field('referencia'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function editarNutriente($idNutriente, $nombre, $nombreIngles, $unidad, $cotaInferior, $cotaSuperior, $aporte, $rdi, $rda, $ea, $idTipoNutriente, $idTipoClase, $idTipoCategoria, $idTipoFamilia, $idTipoSubfamilia, $codigoExterno, $flagEsencial, $recomendacion, $recomendacionIngles, $referencia, $estado, $usuario) {
        $resultado = true;
        if ($codigoExterno == 0) {
            $codigoExterno = "NULL";
        }
        $execute = "CALL USP_EDIT_NUTRIENTE($idNutriente, '$nombre', '$nombreIngles', '$unidad', $cotaInferior, $cotaSuperior, '$aporte', $rdi, $rda, $ea, $idTipoNutriente, $idTipoClase, $idTipoCategoria, $idTipoFamilia, $idTipoSubfamilia, $codigoExterno, '$flagEsencial', '$recomendacion', '$recomendacionIngles', '$referencia', $estado, '$usuario')";
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

    function crearNutriente($nombre, $nombreIngles, $unidad, $cotaInferior, $cotaSuperior, $aporte, $rdi, $rda, $ea, $idTipoNutriente, $idTipoClase, $idTipoCategoria, $idTipoFamilia, $idTipoSubfamilia, $codigoExterno, $flagEsencial, $recomendacion, $recomendacionIngles, $referencia, $usuario) {
        $idNutriente = 0;
        if ($codigoExterno == 0) {
            $codigoExterno = "NULL";
        }
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_NUTRIENTE ('$nombre', '$nombreIngles', '$unidad', $cotaInferior, $cotaSuperior, '$aporte', $rdi, $rda, $ea, $idTipoNutriente, $idTipoClase, $idTipoCategoria, $idTipoFamilia, $idTipoSubfamilia, $codigoExterno, '$flagEsencial', '$recomendacion', '$recomendacionIngles', '$referencia', '$usuario', @p_id_nutriente)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_nutriente AS id_nutriente");
            $sql->read();
            if ($sql->next()) {
                $idNutriente = $sql->field('id_nutriente');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idNutriente;
    }

    function obtenerTipoNutriente($idTipoNutriente) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_NUTRIENTE($idTipoNutriente)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_tipo_nutriente' => $sql->field('id_tipo_nutriente'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerClaseNutriente($idTipoNutriente) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_CLASE($idTipoNutriente)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_clase' => $sql->field('id_tipo_clase'), 'id_tipo_nutriente' => $sql->field('id_tipo_nutriente'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerCategoriaNutriente($idTipoCategoria) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_CATEGORIA($idTipoCategoria)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_categoria' => $sql->field('id_tipo_categoria'), 'id_tipo_clase' => $sql->field('id_tipo_clase'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerFamiliaNutriente($idTipoFamilia) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_FAMILIA($idTipoFamilia)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_familia' => $sql->field('id_tipo_familia'), 'id_tipo_categoria' => $sql->field('id_tipo_categoria'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerSubfamiliaNutriente($idTipoFamilia) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_SUBFAMILIA($idTipoFamilia)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_subfamilia' => $sql->field('id_tipo_subfamilia'), 'id_tipo_familia' => $sql->field('id_tipo_familia'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearTipoNutriente($nombre, $nombreIngles, $usuario) {
        $idTipoNutriente = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_NUTRIENTE ('$nombre', '$nombreIngles', '$usuario', @p_id_tipo_nutriente)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_nutriente AS id_tipo_nutriente");
            $sql->read();
            if ($sql->next()) {
                $idTipoNutriente = $sql->field('id_tipo_nutriente');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoNutriente;
    }

    function crearTipoClase($idTipoNutriente, $nombre, $nombreIngles, $usuario) {
        $idTipoClase = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_CLASE ($idTipoNutriente, '$nombre', '$nombreIngles', '$usuario', @p_id_tipo_clase)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_clase AS id_tipo_clase");
            $sql->read();
            if ($sql->next()) {
                $idTipoClase = $sql->field('id_tipo_clase');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoClase;
    }

    function crearTipoCategoria($idTipoClase, $nombre, $nombreIngles, $usuario) {
        $idTipoCategoria = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_CATEGORIA ($idTipoClase, '$nombre', '$nombreIngles', '$usuario', @p_id_tipo_categoria)";
        echo $execute;
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_categoria AS id_tipo_categoria");
            $sql->read();
            if ($sql->next()) {
                $idTipoCategoria = $sql->field('id_tipo_categoria');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoCategoria;
    }

    function crearTipoFamilia($idTipoCategoria, $nombre, $nombreIngles, $usuario) {
        $idTipoFamilia = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_FAMILIA ($idTipoCategoria, '$nombre', '$nombreIngles', '$usuario', @p_id_tipo_familia)";

        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_familia AS id_tipo_familia");
            $sql->read();
            if ($sql->next()) {
                $idTipoFamilia = $sql->field('id_tipo_familia');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoFamilia;
    }

    function crearTipoSubfamilia($idTipoFamilia, $nombre, $nombreIngles, $usuario) {
        $idTipoSubfamilia = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_SUBFAMILIA ($idTipoFamilia, '$nombre', '$nombreIngles','$usuario', @p_id_tipo_subfamilia)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_subfamilia AS id_tipo_subfamilia");
            $sql->read();
            if ($sql->next()) {
                $idTipoSubfamilia = $sql->field('id_tipo_subfamilia');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoSubfamilia;
    }

    function editarTipoNutriente($idTipoNutriente, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_NUTRIENTE($idTipoNutriente, '$nombre', '$nombreIngles', $estado, '$usuario')";
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

    function editarTipoClase($idTipoClase, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_CLASE($idTipoClase, '$nombre', '$nombreIngles', $estado, '$usuario')";
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

    function editarTipoCategoria($idTipoCategoria, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_CATEGORIA($idTipoCategoria, '$nombre', '$nombreIngles', $estado, '$usuario')";
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

    function editarTipoFamilia($idTipoFamilia, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_FAMILIA($idTipoFamilia, '$nombre', '$nombreIngles', $estado, '$usuario')";
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

    function editarTipoSubfamilia($idTipoSubfamilia, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_SUBFAMILIA($idTipoSubfamilia, '$nombre', '$nombreIngles', $estado, '$usuario')";
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

    function listarUnidad($estado = -1, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_UNIDAD($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('unidad' => $sql->field('unidad'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

}
?>