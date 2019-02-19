<?php
class DaoCuestionario {

    function __construct() { }

    function crearSeccionCuestionario ($descripcion, $descripcionIngles, $orden, $iconoClase, $systemRequired, $usuario) {
        $idSeccion = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_SECCION_CUESTIONARIO ('$descripcion', '$descripcionIngles', $orden, '$iconoClase', $systemRequired, '$usuario', @p_id_seccion_usuario);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_seccion_usuario AS id_seccion_usuario");
        $sql->read();
        if ($sql->next()) {
            $idSeccion = $sql->field('id_seccion_usuario');
        }
        $cnx->close();
        $cnx = null;
        return $idSeccion;
    }

    function editarSeccionCuestionario($idSeccion, $descripcion, $descripcionIngles, $orden, $iconoClase, $systemRequired, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        try {
            $execute = "CALL USP_EDIT_SECCION_CUESTIONARIO ($idSeccion, '$descripcion', '$descripcionIngles', $orden, '$iconoClase', $systemRequired, $estado, '$usuario');";
            $cnx->execute($execute);
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }

    function listarSeccionCuestionario($idAfiliado = 0, $estado = -1) {
        $arreglo = array();
        $query = "CALL USP_LIST_SECCION_CUESTIONARIO ($idAfiliado, $estado)";
        //echo $query;
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_seccion_cuestionario' => $sql->field('id_seccion_cuestionario'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'orden' => $sql->field('orden'), 'icono_clase' => $sql->field('icono_clase'), 'system_required' => $sql->field('system_required'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerSeccionCuestionario($idSeccion) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_SECCION_CUESTIONARIO($idSeccion)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_seccion_cuestionario' => $sql->field('id_seccion_cuestionario'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'orden' => $sql->field('orden'), 'icono_clase' => $sql->field('icono_clase'), 'system_required' => $sql->field('system_required'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarSeccionCuestionarioTienePregunta($idUsuario) {
        $arreglo = array();
        $query = "CALL USP_LIST_SECCION_CUESTIONARIO_TIENE_PREG($idUsuario)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_seccion_cuestionario' => $sql->field('id_seccion_cuestionario'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'orden' => $sql->field('orden'), 'icono_clase' => $sql->field('icono_clase'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerSeccionCuestionarioPorSeccion($seccion) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_SECCION_CUESTIONARIO_X_SECCION('$seccion')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_seccion_cuestionario' => $sql->field('id_seccion_cuestionario'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'orden' => $sql->field('orden'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearPregunta($idSeccionCuestionario, $descripcion, $descripcionIngles, $alternativo, $alternativoIngles, $tipoRespuesta, $codigo, $orden, $datoEspecial, $columnaPresentacion, $esRequerido, $valorMinimo, $valorMaximo, $presentacion, $ocultaOpcion, $riskEngine, $tagEspecial, $systemRequired, $usuario) {
        $idPregunta = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_PREGUNTA ($idSeccionCuestionario, '$descripcion', '$descripcionIngles', '$alternativo', '$alternativoIngles', '$tipoRespuesta', '$codigo', $orden, '$datoEspecial', '$columnaPresentacion', $esRequerido, $valorMinimo, $valorMaximo, '$presentacion', $ocultaOpcion, $riskEngine, '$tagEspecial', $systemRequired, '$usuario', @p_id_pregunta);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_pregunta AS id_pregunta");
        $sql->read();
        if ($sql->next()) {
            $idPregunta = $sql->field('id_pregunta');
        }
        $cnx->close();
        $cnx = null;
        return $idPregunta;
    }

    function editarPregunta($idPregunta, $idSeccionCuestionario, $descripcion, $descripcionIngles, $alternativo, $alternativoIngles, $tipoRespuesta, $codigo, $orden, $datoEspecial, $columnaPresentacion, $esRequerido, $valorMinimo, $valorMaximo, $presentacion, $ocultaOpcion, $riskEngine, $tagEspecial, $systemRequired, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        try {
            $execute = "CALL USP_EDIT_PREGUNTA ($idPregunta, $idSeccionCuestionario, '$descripcion', '$descripcionIngles', '$alternativo', '$alternativoIngles', '$tipoRespuesta', '$codigo', $orden, '$datoEspecial', '$columnaPresentacion', $esRequerido, $valorMinimo, $valorMaximo, '$presentacion', $ocultaOpcion, $riskEngine, '$tagEspecial', $systemRequired, $estado, '$usuario');";
            $cnx->execute($execute);
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }

    function listarPregunta($estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA($estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta' => $sql->field('id_pregunta'), 'id_seccion_cuestionario' => $sql->field('id_seccion_cuestionario'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'alternativo' => $sql->field('alternativo'), 'alternativo_ing' => $sql->field('alternativo_ing'), 'tipo_respuesta' => $sql->field('tipo_respuesta'), 'codigo' => $sql->field('codigo'), 'orden' => $sql->field('orden'), 'dato_especial' => $sql->field('dato_especial'), 'presentacion_col' => $sql->field('presentacion_col'), 'es_requerido' => $sql->field('es_requerido'), 'valor_minimo' => $sql->field('valor_minimo'), 'valor_maximo' => $sql->field('valor_maximo'), 'presentacion' => $sql->field('presentacion'), 'oculta_opcion' => $sql->field('oculta_opcion'), 'procesar_risk_engine' => $sql->field('procesar_risk_engine'), 'special_tag' => $sql->field('special_tag'), 'system_required' => $sql->field('system_required'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica'), 'seccion' => $sql->field('seccion'), 'seccion_ing' => $sql->field('seccion_ing'), 'cantidad_respuesta' => $sql->field('cantidad_respuesta'), 'orden_seccion' => $sql->field('orden_seccion')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerPregunta($idPregunta) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_PREGUNTA($idPregunta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_pregunta' => $sql->field('id_pregunta'), 'id_seccion_cuestionario' => $sql->field('id_seccion_cuestionario'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'alternativo' => $sql->field('alternativo'), 'alternativo_ing' => $sql->field('alternativo_ing'), 'tipo_respuesta' => $sql->field('tipo_respuesta'), 'codigo' => $sql->field('codigo'), 'orden' => $sql->field('orden'), 'dato_especial' => $sql->field('dato_especial'), 'presentacion_col' => $sql->field('presentacion_col'), 'es_requerido' => $sql->field('es_requerido'), 'valor_minimo' => $sql->field('valor_minimo'), 'valor_maximo' => $sql->field('valor_maximo'), 'presentacion' => $sql->field('presentacion'), 'oculta_opcion' => $sql->field('oculta_opcion'), 'procesar_risk_engine' => $sql->field('procesar_risk_engine'), 'special_tag' => $sql->field('special_tag'), 'system_required' => $sql->field('system_required'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica'), 'seccion' => $sql->field('seccion'), 'seccion_ing' => $sql->field('seccion_ing')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarPreguntaPorSeccion($estado, $idSeccion, $idQuestionnaireSet = 0) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_X_SECCION($estado, $idSeccion, $idQuestionnaireSet)";
        //echo $query;
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta' => $sql->field('id_pregunta'), 'id_seccion_cuestionario' => $sql->field('id_seccion_cuestionario'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'alternativo' => $sql->field('alternativo'), 'alternativo_ing' => $sql->field('alternativo_ing'), 'tipo_respuesta' => $sql->field('tipo_respuesta'), 'codigo' => $sql->field('codigo'), 'orden' => $sql->field('orden'), 'dato_especial' => $sql->field('dato_especial'), 'presentacion_col' => $sql->field('presentacion_col'), 'es_requerido' => $sql->field('es_requerido'), 'valor_minimo' => $sql->field('valor_minimo'), 'valor_maximo' => $sql->field('valor_maximo'), 'presentacion' => $sql->field('presentacion'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica'), 'seccion' => $sql->field('seccion'), 'seccion_ing' => $sql->field('seccion_ing'), 'orden_seccion' => $sql->field('orden_seccion')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarRespuesta($idPregunta) {
        $arreglo = array();
        $query = "CALL USP_LIST_RESPUESTA($idPregunta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_respuesta' => $sql->field('id_respuesta'), 'id_pregunta' => $sql->field('id_pregunta'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerRespuesta($idRespuesta) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_RESPUESTA($idRespuesta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_respuesta' => $sql->field('id_respuesta'), 'id_pregunta' => $sql->field('id_pregunta'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearActualizarRespuesta($idPregunta, $idRespuesta, $descripcion, $descripcionIngles, $orden, $none, $estado, $usuario) {
        $cnx = new MySQL();
        try {
            if ($idRespuesta > 0) {
                $execute = "CALL USP_EDIT_RESPUESTA ($idRespuesta, '$descripcion', '$descripcionIngles', $orden, $none, '$estado', '$usuario')";
                $cnx->execute($execute);
            } else {
                $execute = "CALL USP_CREA_RESPUESTA ($idPregunta, '$descripcion', '$descripcionIngles', $orden, $none, '$usuario', @p_id_respuesta)";
                $cnx->execute($execute);
                $sql = $cnx->query("SELECT @p_id_respuesta AS id_respuesta");
                $sql->read();
                if ($sql->next()) {
                    $idRespuesta = $sql->field('id_respuesta');
                }
            }
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idRespuesta;
    }

    function grabarOrdenRespuesta($idRespuesta, $orden, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_RESPUESTA_ORDEN ($idRespuesta, $orden, '$usuario')";
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

    function listarTipoRespuesta() {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_RESPUESTA()";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('cod_tipo_respuesta' => $sql->field('cod_tipo_respuesta'), 'descripcion' => $sql->field('descripcion'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarPreguntaRespuesta($datoEspecialPrecondicion, $datoEspecialIntolerancia, $datoEspecialTipoDieta, $idPreguntaNoConsiderar = 0) {
        //lista enfermedad
        $arregloPrecondicion = $this->listarPrecondicion();
        //lista intolerancia
        $arregloIntolerancia = $this->listarIntolerancia();
        //tipo dieta
        $arregloTipoDieta = $this->listarTipoDieta();

        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_RESPUESTA()";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $idPregunta = $sql->field('id_pregunta');
            if ($idPregunta != $idPreguntaNoConsiderar) {
                $datoEspecial = $sql->field('dato_especial');
                $idSeccionCuestionario = $sql->field('id_seccion_cuestionario');
                $seccion = $sql->field('seccion');
                $pregunta = $sql->field('pregunta');
                if ($datoEspecial == $datoEspecialPrecondicion) {
                    foreach ($arregloPrecondicion as $item) {
                        $idRespuesta = $item['id_enfermedad'];
                        $respuesta = $item['nombre'];
                        array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta));
                    }
                } else if ($datoEspecial == $datoEspecialIntolerancia) {
                    foreach ($arregloIntolerancia as $item) {
                        $idRespuesta = $item['id_intolerancia'];
                        $respuesta = $item['nombre'];
                        array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta));
                    }
                } else if ($datoEspecial == $datoEspecialTipoDieta) {
                    foreach ($arregloTipoDieta as $item) {
                        $idRespuesta = $item['id_tipo_dieta'];
                        $respuesta = $item['nombre'];
                        array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta));
                    }
                } else {
                    $idRespuesta = $sql->field('id_respuesta');
                    $respuesta = $sql->field('respuesta');
                    array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta));
                }
            }
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarPreguntaRespuestaCompleta($idSeccionCuestionario, $datoEspecialPrecondicion, $datoEspecialIntolerancia, $datoEspecialTipoDieta) {
        //lista enfermedad
        $arregloPrecondicion = $this->listarPrecondicion();
        //lista intolerancia
        $arregloIntolerancia = $this->listarIntolerancia();
        //tipo dieta
        $arregloTipoDieta = $this->listarTipoDieta();

        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_RESPUESTA_COMPLETA($idSeccionCuestionario)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $idPregunta = $sql->field('id_pregunta');
            $idSeccionCuestionario = $sql->field('id_seccion_cuestionario');
            $seccion = $sql->field('seccion');
            $seccionIngles = $sql->field('seccion_ing');
            $datoEspecial = $sql->field('dato_especial');
            $pregunta = $sql->field('pregunta');
            $tipoRespuesta = $sql->field('tipo_respuesta');
            $preguntaIngles = $sql->field('pregunta_ing');
            $preguntaAlternativo = $sql->field('pregunta_alternativo');
            $preguntaAlternativoIngles = $sql->field('pregunta_alternativo_ing');
            $codigo = $sql->field('codigo');
            $columnaPresentacion = $sql->field('presentacion_col');
            $esPreguntaCondicionada = $sql->field('es_condicionada');
            $esRequerido = $sql->field('es_requerido');
            $valorMinimo = $sql->field('valor_minimo');
            $valorMaximo = $sql->field('valor_maximo');
            $presentacion = $sql->field('presentacion');
            if ($datoEspecial == $datoEspecialPrecondicion) {
                foreach ($arregloPrecondicion as $item) {
                    $idRespuesta = $item['id_enfermedad'];
                    $respuesta = $item['nombre'];
                    $respuestaIngles = $item['nombre_ing'];
                    array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'seccion_ing' => $seccionIngles, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'pregunta_ing' => $preguntaIngles, 'pregunta_alternativo' => $preguntaAlternativo, 'pregunta_alternativo_ing' => $preguntaAlternativoIngles, 'tipo_respuesta' => $tipoRespuesta, 'codigo' => $codigo, 'dato_especial' => $datoEspecial, 'presentacion_col' => $columnaPresentacion, 'es_requerido' => $esRequerido, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'presentacion' => $presentacion, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'es_condicionada' => $esPreguntaCondicionada));
                }
            } else if ($datoEspecial == $datoEspecialIntolerancia) {
                foreach ($arregloIntolerancia as $item) {
                    $idRespuesta = $item['id_intolerancia'];
                    $respuesta = $item['nombre'];
                    $respuestaIngles = $item['nombre_ing'];
                    array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'seccion_ing' => $seccionIngles, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'pregunta_ing' => $preguntaIngles, 'pregunta_alternativo' => $preguntaAlternativo, 'pregunta_alternativo_ing' => $preguntaAlternativoIngles, 'tipo_respuesta' => $tipoRespuesta, 'codigo' => $codigo, 'dato_especial' => $datoEspecial, 'presentacion_col' => $columnaPresentacion, 'es_requerido' => $esRequerido, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'presentacion' => $presentacion, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'es_condicionada' => $esPreguntaCondicionada));
                }
            } else if ($datoEspecial == $datoEspecialTipoDieta) {
                foreach ($arregloTipoDieta as $item) {
                    $idRespuesta = $item['id_tipo_dieta'];
                    $respuesta = $item['nombre'];
                    $respuestaIngles = $item['nombre_ing'];
                    array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'seccion_ing' => $seccionIngles, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'pregunta_ing' => $preguntaIngles, 'pregunta_alternativo' => $preguntaAlternativo, 'pregunta_alternativo_ing' => $preguntaAlternativoIngles, 'tipo_respuesta' => $tipoRespuesta, 'codigo' => $codigo, 'dato_especial' => $datoEspecial, 'presentacion_col' => $columnaPresentacion, 'es_requerido' => $esRequerido, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'presentacion' => $presentacion, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'es_condicionada' => $esPreguntaCondicionada));
                }
            } else {
                $idRespuesta = $sql->field('id_respuesta');
                $respuesta = $sql->field('respuesta');
                $respuestaIngles = $sql->field('respuesta_ing');
                array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'seccion_ing' => $seccionIngles, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'pregunta_ing' => $preguntaIngles, 'pregunta_alternativo' => $preguntaAlternativo, 'pregunta_alternativo_ing' => $preguntaAlternativoIngles, 'tipo_respuesta' => $tipoRespuesta, 'codigo' => $codigo, 'dato_especial' => $datoEspecial, 'presentacion_col' => $columnaPresentacion, 'es_requerido' => $esRequerido, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'presentacion' => $presentacion, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'es_condicionada' => $esPreguntaCondicionada));
            }
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenUsuarioPreguntaRespuesta($idUsuario, $idPregunta, $codigoPregunta, $datoEspecialPrecondicion, $datoEspecialIntolerancia, $datoEspecialTipoDieta) {
        //lista respuestas
        $arregloRespuesta = $this->listarUsuarioRespuestaOpcion($idUsuario);
        //lista enfermedad
        $arregloPrecondicion = $this->listarPrecondicion();
        //lista intolerancia
        $arregloIntolerancia = $this->listarIntolerancia();
        //tipo dieta
        $arregloTipoDieta = $this->listarTipoDieta();

        $arreglo = array();
        $query = "CALL USP_OBTEN_USUARIO_PREGUNTA_RESPUESTA($idUsuario, $idPregunta, '$codigoPregunta')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $idPregunta = $sql->field('id_pregunta');
            $idSeccionCuestionario = $sql->field('id_seccion_cuestionario');
            $seccion = $sql->field('seccion');
            $seccionIngles = $sql->field('seccion_ing');
            $datoEspecial = $sql->field('dato_especial');
            $pregunta = $sql->field('pregunta');
            $tipoRespuesta = $sql->field('tipo_respuesta');
            $preguntaIngles = $sql->field('pregunta_ing');
            $preguntaAlternativo = $sql->field('pregunta_alternativo');
            $preguntaAlternativoIngles = $sql->field('pregunta_alternativo_ing');
            $codigo = $sql->field('codigo');
            $columnaPresentacion = $sql->field('presentacion_col');
            $esRequerido = $sql->field('es_requerido');
            $valorMinimo = $sql->field('valor_minimo');
            $valorMaximo = $sql->field('valor_maximo');
            $presentacion = $sql->field('presentacion');
            $ocultaOpcion = $sql->field('oculta_opcion');
            $idRespuestaUsuario = $sql->field('id_respuesta_usuario');
            $respuestaUsuario = $sql->field('respuesta_usuario');
            if ($datoEspecial == $datoEspecialPrecondicion) {
                foreach ($arregloPrecondicion as $item) {
                    $idRespuesta = $item['id_enfermedad'];
                    $respuesta = $item['nombre'];
                    $respuestaIngles = $item['nombre_ing'];
                    $respuestaNone = 0;
                    if ($this->existeRespuesta($arregloRespuesta, $idPregunta, $idRespuesta)) {
                        $idRespuestaUsuario = $idRespuesta;
                    } else {
                        $idRespuestaUsuario = NULL;
                    }
                    array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'seccion_ing' => $seccionIngles, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'pregunta_ing' => $preguntaIngles, 'pregunta_alternativo' => $preguntaAlternativo, 'pregunta_alternativo_ing' => $preguntaAlternativoIngles, 'tipo_respuesta' => $tipoRespuesta, 'codigo' => $codigo, 'dato_especial' => $datoEspecial, 'presentacion_col' => $columnaPresentacion, 'es_requerido' => $esRequerido, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'presentacion' => $presentacion, 'oculta_opcion' => $ocultaOpcion, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'id_respuesta_usuario' => $idRespuestaUsuario, 'respuesta_usuario' => $respuestaUsuario, 'respuesta_none' => $respuestaNone));
                }
            } else if ($datoEspecial == $datoEspecialIntolerancia) {
                foreach ($arregloIntolerancia as $item) {
                    $idRespuesta = $item['id_intolerancia'];
                    $respuesta = $item['nombre'];
                    $respuestaIngles = $item['nombre_ing'];
                    $respuestaNone = 0;
                    if ($this->existeRespuesta($arregloRespuesta, $idPregunta, $idRespuesta)) {
                        $idRespuestaUsuario = $idRespuesta;
                    } else {
                        $idRespuestaUsuario = NULL;
                    }
                    array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'seccion_ing' => $seccionIngles, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'pregunta_ing' => $preguntaIngles, 'pregunta_alternativo' => $preguntaAlternativo, 'pregunta_alternativo_ing' => $preguntaAlternativoIngles, 'tipo_respuesta' => $tipoRespuesta, 'codigo' => $codigo, 'dato_especial' => $datoEspecial, 'presentacion_col' => $columnaPresentacion, 'es_requerido' => $esRequerido, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'presentacion' => $presentacion, 'oculta_opcion' => $ocultaOpcion, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'id_respuesta_usuario' => $idRespuestaUsuario, 'respuesta_usuario' => $respuestaUsuario, 'respuesta_none' => $respuestaNone));
                }
            } else if ($datoEspecial == $datoEspecialTipoDieta) {
                foreach ($arregloTipoDieta as $item) {
                    $idRespuesta = $item['id_tipo_dieta'];
                    $respuesta = $item['nombre'];
                    $respuestaIngles = $item['nombre_ing'];
                    $respuestaNone = 0;
                    if ($this->existeRespuesta($arregloRespuesta, $idPregunta, $idRespuesta)) {
                        $idRespuestaUsuario = $idRespuesta;
                    } else {
                        $idRespuestaUsuario = NULL;
                    }
                    array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'seccion_ing' => $seccionIngles, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'pregunta_ing' => $preguntaIngles, 'pregunta_alternativo' => $preguntaAlternativo, 'pregunta_alternativo_ing' => $preguntaAlternativoIngles, 'tipo_respuesta' => $tipoRespuesta, 'codigo' => $codigo, 'dato_especial' => $datoEspecial, 'presentacion_col' => $columnaPresentacion, 'es_requerido' => $esRequerido, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'presentacion' => $presentacion, 'oculta_opcion' => $ocultaOpcion, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'id_respuesta_usuario' => $idRespuestaUsuario, 'respuesta_usuario' => $respuestaUsuario, 'respuesta_none' => $respuestaNone));
                }
            } else {
                $idRespuesta = $sql->field('id_respuesta');
                $respuesta = $sql->field('respuesta');
                $respuestaIngles = $sql->field('respuesta_ing');
                $respuestaNone = $sql->field('respuesta_none');
                array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'seccion_ing' => $seccionIngles, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'pregunta_ing' => $preguntaIngles, 'pregunta_alternativo' => $preguntaAlternativo, 'pregunta_alternativo_ing' => $preguntaAlternativoIngles, 'tipo_respuesta' => $tipoRespuesta, 'codigo' => $codigo, 'dato_especial' => $datoEspecial, 'presentacion_col' => $columnaPresentacion, 'es_requerido' => $esRequerido, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'presentacion' => $presentacion, 'oculta_opcion' => $ocultaOpcion, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'id_respuesta_usuario' => $idRespuestaUsuario, 'respuesta_usuario' => $respuestaUsuario, 'respuesta_none' => $respuestaNone));
            }
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarUsuarioPreguntaRespuesta($idUsuario, $idSeccion, $idAfiliado) {

        $arreglo = array();
        $query = "CALL USP_LIST_USUARIO_PREGUNTA_RESPUESTA($idUsuario, $idSeccion, $idAfiliado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $idPregunta = $sql->field('id_pregunta');
            $idSeccionCuestionario = $sql->field('id_seccion_cuestionario');
            $seccion = $sql->field('seccion');
            $seccionIngles = $sql->field('seccion_ing');
            $seccionIcono = $sql->field('seccion_icono');
            $datoEspecial = $sql->field('dato_especial');
            $pregunta = $sql->field('pregunta');
            $tipoRespuesta = $sql->field('tipo_respuesta');
            $preguntaIngles = $sql->field('pregunta_ing');
            $preguntaAlternativo = $sql->field('pregunta_alternativo');
            $preguntaAlternativoIngles = $sql->field('pregunta_alternativo_ing');
            $codigo = $sql->field('codigo');
            $columnaPresentacion = $sql->field('presentacion_col');
            $esRequerido = $sql->field('es_requerido');
            $valorMinimo = $sql->field('valor_minimo');
            $valorMaximo = $sql->field('valor_maximo');
            $presentacion = $sql->field('presentacion');
            $ocultaOpcion = $sql->field('oculta_opcion');
            $idRespuestaUsuario = $sql->field('id_respuesta_usuario');
            $respuestaUsuario = $sql->field('respuesta_usuario');
            $tieneSubpregunta = $sql->field('tiene_subpregunta');
            $idRespuesta = $sql->field('id_respuesta');
            $respuesta = $sql->field('respuesta');
            $respuestaIngles = $sql->field('respuesta_ing');
            $respuestaNone = $sql->field('respuesta_none');
            array_push($arreglo, array('id_seccion_cuestionario' => $idSeccionCuestionario, 'seccion' => $seccion, 'seccion_ing' => $seccionIngles, 'seccion_icono' => $seccionIcono, 'id_pregunta' => $idPregunta, 'pregunta' => $pregunta, 'pregunta_ing' => $preguntaIngles, 'pregunta_alternativo' => $preguntaAlternativo, 'pregunta_alternativo_ing' => $preguntaAlternativoIngles, 'tipo_respuesta' => $tipoRespuesta, 'codigo' => $codigo, 'dato_especial' => $datoEspecial, 'presentacion_col' => $columnaPresentacion, 'es_requerido' => $esRequerido, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'presentacion' => $presentacion, 'oculta_opcion' => $ocultaOpcion, 'tiene_subpregunta' => $tieneSubpregunta, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'id_respuesta_usuario' => $idRespuestaUsuario, 'respuesta_usuario' => $respuestaUsuario, 'respuesta_none' => $respuestaNone));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function actualizarSeccionCuestionarioMostrar($idSeccionCuestionario, $arregloPreguntaRespuestaAfecta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_SECCION_CUESTIONARIO_ENLAZADA_MOSTRAR($idSeccionCuestionario, '$usuario')";
        try {
            $cnx->execute($execute);
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }

        foreach ($arregloPreguntaRespuestaAfecta as $item) {
            $idPreguntaAfecta = $item['id_pregunta_afecta'];
            $idRespuestaAfecta = $item['id_respuesta_afecta'];
            $cnx = new MySQL();
            $execute = "CALL USP_UPD_SECCION_CUESTIONARIO_ENLAZADA_MOSTRAR($idSeccionCuestionario, $idPreguntaAfecta, $idRespuestaAfecta, '$usuario')";
            try {
                $cnx->execute($execute);
            } catch (Exception $e) {
                $resultado = false;
            } finally {
                $cnx->close();
                $cnx = null;
            }
        }

        return $resultado;
    }

    function actualizarSeccionCuestionarioOmitir($idSeccionCuestionario, $arregloPreguntaRespuestaAfecta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_SECCION_CUESTIONARIO_ENLAZADA_OMITIR($idSeccionCuestionario, '$usuario')";
        try {
            $cnx->execute($execute);
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }

        foreach ($arregloPreguntaRespuestaAfecta as $item) {
            $idPreguntaAfecta = $item['id_pregunta_afecta'];
            $idRespuestaAfecta = $item['id_respuesta_afecta'];
            $cnx = new MySQL();
            $execute = "CALL USP_UPD_SECCION_CUESTIONARIO_ENLAZADA_OMITIR($idSeccionCuestionario, $idPreguntaAfecta, $idRespuestaAfecta, '$usuario')";
            try {
                $cnx->execute($execute);
            } catch (Exception $e) {
                $resultado = false;
            } finally {
                $cnx->close();
                $cnx = null;
            }
        }

        return $resultado;
    }

    function listarSeccionCuestionarioEnlazadaMostrar($idSeccionCuestionarioAfectada) {
        $arreglo = array();
        $query = "CALL USP_LIST_SECCION_CUESTIONARIO_ENLAZADA_MOSTRAR($idSeccionCuestionarioAfectada)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_seccion_cuestionario_enlazada' => $sql->field('id_seccion_cuestionario_enlazada'), 'id_seccion_cuestionario_afectada' => $sql->field('id_seccion_cuestionario_afectada'), 'id_pregunta_afecta' => $sql->field('id_pregunta_afecta'), 'id_respuesta_afecta' => $sql->field('id_respuesta_afecta'), 'omitir' => $sql->field('omitir'), 'mostrar' => $sql->field('mostrar'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarSeccionCuestionarioEnlazadaOmitir($idSeccionCuestionarioAfectada) {
        $arreglo = array();
        $query = "CALL USP_LIST_SECCION_CUESTIONARIO_ENLAZADA_OMITIR($idSeccionCuestionarioAfectada)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_seccion_cuestionario_enlazada' => $sql->field('id_seccion_cuestionario_enlazada'), 'id_seccion_cuestionario_afectada' => $sql->field('id_seccion_cuestionario_afectada'), 'id_pregunta_afecta' => $sql->field('id_pregunta_afecta'), 'id_respuesta_afecta' => $sql->field('id_respuesta_afecta'), 'omitir' => $sql->field('omitir'), 'mostrar' => $sql->field('mostrar'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function actualizarPreguntaMostrar($idPregunta, $arregloPreguntaRespuestaAfecta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_PREGUNTA_ENLAZADA_MOSTRAR($idPregunta, '$usuario')";
        try {
            $cnx->execute($execute);
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }

        foreach ($arregloPreguntaRespuestaAfecta as $item) {
            $idPreguntaAfecta = $item['id_pregunta_afecta'];
            $idRespuestaAfecta = $item['id_respuesta_afecta'];
            $cnx = new MySQL();
            $execute = "CALL USP_UPD_PREGUNTA_ENLAZADA_MOSTRAR($idPregunta, $idPreguntaAfecta, $idRespuestaAfecta, '$usuario')";
            try {
                $cnx->execute($execute);
            } catch (Exception $e) {
                $resultado = false;
            } finally {
                $cnx->close();
                $cnx = null;
            }
        }

        return $resultado;
    }

    function actualizarPreguntaOmitir($idPregunta, $arregloPreguntaRespuestaAfecta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_PREGUNTA_ENLAZADA_OMITIR($idPregunta, '$usuario')";
        try {
            $cnx->execute($execute);
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }

        foreach ($arregloPreguntaRespuestaAfecta as $item) {
            $idPreguntaAfecta = $item['id_pregunta_afecta'];
            $idRespuestaAfecta = $item['id_respuesta_afecta'];
            $cnx = new MySQL();
            $execute = "CALL USP_UPD_PREGUNTA_ENLAZADA_OMITIR($idPregunta, $idPreguntaAfecta, $idRespuestaAfecta, '$usuario')";
            try {
                $cnx->execute($execute);
            } catch (Exception $e) {
                $resultado = false;
            } finally {
                $cnx->close();
                $cnx = null;
            }
        }

        return $resultado;
    }

    function listarPreguntaEnlazadaMostrar($idPreguntaAfectada) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_ENLAZADA_MOSTRAR($idPreguntaAfectada)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta_enlazada' => $sql->field('id_pregunta_enlazada'), 'id_pregunta_afectada' => $sql->field('id_pregunta_afectada'), 'id_pregunta_afecta' => $sql->field('id_pregunta_afecta'), 'id_respuesta_afecta' => $sql->field('id_respuesta_afecta'), 'omitir' => $sql->field('omitir'), 'mostrar' => $sql->field('mostrar'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarPreguntaEnlazadaOmitir($idPreguntaAfectada) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_ENLAZADA_OMITIR($idPreguntaAfectada)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta_enlazada' => $sql->field('id_pregunta_enlazada'), 'id_pregunta_afectada' => $sql->field('id_pregunta_afectada'), 'id_pregunta_afecta' => $sql->field('id_pregunta_afecta'), 'id_respuesta_afecta' => $sql->field('id_respuesta_afecta'), 'omitir' => $sql->field('omitir'), 'mostrar' => $sql->field('mostrar'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoDieta() {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_DIETA()";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_dieta' => $sql->field('id_tipo_dieta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarPrecondicion() {
        //lista enfermedad
        $arreglo = array();
        $query = "CALL USP_LIST_ENFERMEDAD(1, 'en', 0)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_enfermedad' => $sql->field('id_enfermedad'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarIntolerancia() {
        $arreglo = array();
        $query = "CALL USP_LIST_INTOLERANCIA(1, 'en')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_intolerancia' => $sql->field('id_intolerancia'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarPreguntaEnlazada() {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_ENLAZADA()";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta_enlazada' => $sql->field('id_pregunta_enlazada'), 'id_pregunta_afectada' => $sql->field('id_pregunta_afectada'), 'id_pregunta_afecta' => $sql->field('id_pregunta_afecta'), 'id_respuesta_afecta' => $sql->field('id_respuesta_afecta'), 'omitir' => $sql->field('omitir'), 'mostrar' => $sql->field('mostrar')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarSeccionEnlazada() {
        $arreglo = array();
        $query = "CALL USP_LIST_SECCION_CUESTIONARIO_ENLAZADA()";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_seccion_cuestionario_enlazada' => $sql->field('id_seccion_cuestionario_enlazada'), 'id_seccion_cuestionario_afectada' => $sql->field('id_seccion_cuestionario_afectada'), 'id_pregunta_afecta' => $sql->field('id_pregunta_afecta'), 'id_respuesta_afecta' => $sql->field('id_respuesta_afecta'), 'omitir' => $sql->field('omitir'), 'mostrar' => $sql->field('mostrar')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearSubpregunta($idPregunta, $descripcion, $descripcionIngles, $tipoRespuesta, $orden, $datoEspecial, $valorMinimo, $valorMaximo, $usuario) {
        $idSubpregunta = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_SUBPREGUNTA ($idPregunta, '$descripcion', '$descripcionIngles', '$tipoRespuesta', $orden, '$datoEspecial', $valorMinimo, $valorMaximo, '$usuario', @p_id_subpregunta);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_subpregunta AS id_subpregunta");
        $sql->read();
        if ($sql->next()) {
            $idSubpregunta = $sql->field('id_subpregunta');
        }
        $cnx->close();
        $cnx = null;
        return $idSubpregunta;
    }

    function editarSubpregunta($idSubpregunta, $descripcion, $descripcionIngles, $tipoRespuesta, $orden, $datoEspecial, $valorMinimo, $valorMaximo, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        try {
            $execute = "CALL USP_EDIT_SUBPREGUNTA ($idSubpregunta, '$descripcion', '$descripcionIngles', '$tipoRespuesta', $orden, '$datoEspecial', $valorMinimo, $valorMaximo, $estado, '$usuario');";
            $cnx->execute($execute);
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }

    function listarSubpregunta($idPregunta, $estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_SUBPREGUNTA($idPregunta, $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_subpregunta' => $sql->field('id_subpregunta'), 'id_pregunta' => $sql->field('id_pregunta'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'tipo_respuesta' => $sql->field('tipo_respuesta'), 'orden' => $sql->field('orden'), 'dato_especial' => $sql->field('dato_especial'), 'valor_minimo' => $sql->field('valor_minimo'), 'valor_maximo' => $sql->field('valor_maximo'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerSubpregunta($idSubpregunta) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_SUBPREGUNTA($idSubpregunta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_subpregunta' => $sql->field('id_subpregunta'), 'id_pregunta' => $sql->field('id_pregunta'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'tipo_respuesta' => $sql->field('tipo_respuesta'), 'orden' => $sql->field('orden'), 'dato_especial' => $sql->field('dato_especial'), 'valor_minimo' => $sql->field('valor_minimo'), 'valor_maximo' => $sql->field('valor_maximo'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarSubrespuesta($idSubpregunta) {
        $arreglo = array();
        $query = "CALL USP_LIST_SUBRESPUESTA($idSubpregunta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_subrespuesta' => $sql->field('id_subrespuesta'), 'id_subpregunta' => $sql->field('id_subpregunta'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'orden' => $sql->field('orden'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerSubrespuesta($idSubrespuesta) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_SUBRESPUESTA($idSubrespuesta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_subrespuesta' => $sql->field('id_subrespuesta'), 'id_subpregunta' => $sql->field('id_subpregunta'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'orden' => $sql->field('orden'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearActualizarSubrespuesta($idSubpregunta, $idSubrespuesta, $descripcion, $descripcionIngles, $orden, $estado, $usuario) {
        $cnx = new MySQL();
        try {
            if ($idSubrespuesta > 0) {
                $execute = "CALL USP_EDIT_SUBRESPUESTA ($idSubrespuesta, '$descripcion', '$descripcionIngles', $orden, '$estado', '$usuario')";
                $cnx->execute($execute);
            } else {
                $execute = "CALL USP_CREA_SUBRESPUESTA ($idSubpregunta, '$descripcion', '$descripcionIngles', $orden, '$usuario', @p_id_subrespuesta)";
                $cnx->execute($execute);
                $sql = $cnx->query("SELECT @p_id_subrespuesta AS id_subrespuesta");
                $sql->read();
                if ($sql->next()) {
                    $idSubrespuesta = $sql->field('id_subrespuesta');
                }
            }
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idSubrespuesta;
    }

    function grabarOrdenSubrespuesta($idSubrespuesta, $orden, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_SUBRESPUESTA_ORDEN ($idSubrespuesta, $orden, '$usuario')";
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

    function eliminarUsuarioSubpreguntaSubrespuesta($idUsuario, $idPregunta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_USUARIO_SUBPREGUNTA_SUBRESPUESTA ($idUsuario, $idPregunta, '$usuario')";
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

    function grabarUsuarioSubpreguntaSubrespuesta($idUsuario, $idPregunta, $idRespuesta, $idSubpregunta, $idSubrespuesta, $usuario) {
        $resultado = true;
        if ($idRespuesta == 0) {
            $idRespuesta = "NULL";
        }
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_USUARIO_SUBPREGUNTA_SUBRESPUESTA ($idUsuario, $idPregunta, $idRespuesta, $idSubpregunta, $idSubrespuesta, '$usuario')";
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

    function grabarUsuarioSubpreguntaSubrespuestaTextual($idUsuario, $idPregunta, $idRespuesta, $idSubpregunta, $subrespuesta, $usuario) {
        $resultado = true;
        if ($idRespuesta == 0) {
            $idRespuesta = "NULL";
        }
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_USUARIO_SUBPREGUNTA_SUBRESPUESTA_TEXT ($idUsuario, $idPregunta, $idRespuesta, $idSubpregunta, '$subrespuesta', '$usuario')";
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

    function listarSubpreguntaSubrespuesta($idPregunta, $datoEspecialPrecondicion, $datoEspecialIntolerancia, $datoEspecialTipoDieta) {
        //lista enfermedad
        $arregloPrecondicion = $this->listarPrecondicion();
        //lista intolerancia
        $arregloIntolerancia = $this->listarIntolerancia();
        //tipo dieta
        $arregloTipoDieta = $this->listarTipoDieta();

        $arreglo = array();
        $query = "CALL USP_LIST_SUBPREGUNTA_SUBRESPUESTA($idPregunta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $idPregunta = $sql->field('id_pregunta');
            $idSubpregunta = $sql->field('id_subpregunta');
            $subpregunta = $sql->field('subpregunta');
            $subpreguntaIngles = $sql->field('subpregunta_ing');
            $tipoRespuesta = $sql->field('tipo_respuesta');
            $datoEspecial = $sql->field('dato_especial');
            $valorMinimo = $sql->field('valor_minimo');
            $valorMaximo = $sql->field('valor_maximo');

            if ($datoEspecial == $datoEspecialPrecondicion) {
                foreach ($arregloPrecondicion as $item) {
                    $idSubrespuesta = $item['id_enfermedad'];
                    $subrespuesta = $item['nombre'];
                    $subrespuestaIngles = $item['nombre_ing'];
                    array_push($arreglo, array('id_pregunta' => $idPregunta, 'id_subpregunta' => $idSubpregunta, 'subpregunta' => $subpregunta, 'subpregunta_ing' => $subpreguntaIngles, 'tipo_respuesta' => $tipoRespuesta, 'dato_especial' => $datoEspecial, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'id_subrespuesta' => $idSubrespuesta, 'subrespuesta' => $subrespuesta, 'subrespuesta_ing' => $subrespuestaIngles));
                }
            } else if ($datoEspecial == $datoEspecialIntolerancia) {
                foreach ($arregloIntolerancia as $item) {
                    $idSubrespuesta = $item['id_intolerancia'];
                    $subrespuesta = $item['nombre'];
                    $subrespuestaIngles = $item['nombre_ing'];
                    array_push($arreglo, array('id_pregunta' => $idPregunta, 'id_subpregunta' => $idSubpregunta, 'subpregunta' => $subpregunta, 'subpregunta_ing' => $subpreguntaIngles, 'tipo_respuesta' => $tipoRespuesta, 'dato_especial' => $datoEspecial, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'id_subrespuesta' => $idSubrespuesta, 'subrespuesta' => $subrespuesta, 'subrespuesta_ing' => $subrespuestaIngles));
                }
            } else if ($datoEspecial == $datoEspecialTipoDieta) {
                foreach ($arregloTipoDieta as $item) {
                    $idSubrespuesta = $item['id_tipo_dieta'];
                    $subrespuesta = $item['nombre'];
                    $subrespuestaIngles = $item['nombre_ing'];
                    array_push($arreglo, array('id_pregunta' => $idPregunta, 'id_subpregunta' => $idSubpregunta, 'subpregunta' => $subpregunta, 'subpregunta_ing' => $subpreguntaIngles, 'tipo_respuesta' => $tipoRespuesta, 'dato_especial' => $datoEspecial, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'id_subrespuesta' => $idSubrespuesta, 'subrespuesta' => $subrespuesta, 'subrespuesta_ing' => $subrespuestaIngles));
                }
            } else {
                $idSubrespuesta = $sql->field('id_subrespuesta');
                $subrespuesta = $sql->field('subrespuesta');
                $subrespuestaIngles = $sql->field('subrespuesta_ing');
                array_push($arreglo, array('id_pregunta' => $idPregunta, 'id_subpregunta' => $idSubpregunta, 'subpregunta' => $subpregunta, 'subpregunta_ing' => $subpreguntaIngles, 'tipo_respuesta' => $tipoRespuesta, 'dato_especial' => $datoEspecial, 'valor_minimo' => $valorMinimo, 'valor_maximo' => $valorMaximo, 'id_subrespuesta' => $idSubrespuesta, 'subrespuesta' => $subrespuesta, 'subrespuesta_ing' => $subrespuestaIngles));
            }
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function eliminarUsuarioPreguntaRespuesta($idUsuario, $idPregunta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_USUARIO_PREGUNTA_RESPUESTA ($idUsuario, $idPregunta, '$usuario')";
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

    function grabarUsuarioPreguntaRespuesta($idUsuario, $idPregunta, $idRespuesta, $usuario) {
        $idUsuarioPreguntaRespuesta = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_USUARIO_PREGUNTA_RESPUESTA ($idUsuario, $idPregunta, $idRespuesta, '$usuario', @p_id_usuario_pregunta_respuesta);";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_usuario_pregunta_respuesta AS id_usuario_pregunta_respuesta");
            $sql->read();
            if ($sql->next()) {
                $idUsuarioPreguntaRespuesta = $sql->field('id_usuario_pregunta_respuesta');
            }
        } catch (Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idUsuarioPreguntaRespuesta;
    }

    function grabarUsuarioPreguntaRespuestaTextual($idUsuario, $idPregunta, $respuesta, $usuario) {
        $idUsuarioPreguntaRespuesta = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_USUARIO_PREGUNTA_RESPUESTA_TEXT ($idUsuario, $idPregunta, '$respuesta', '$usuario', @p_id_usuario_pregunta_respuesta);";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_usuario_pregunta_respuesta AS id_usuario_pregunta_respuesta");
            $sql->read();
            if ($sql->next()) {
                $idUsuarioPreguntaRespuesta = $sql->field('id_usuario_pregunta_respuesta');
            }
        } catch (Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idUsuarioPreguntaRespuesta;
    }

    function listarUsuarioRespuestaOpcion($idUsuario) {
        //lista usuario respuesta
        $arreglo = array();
        $query = "CALL USP_LIST_USUARIO_RESPUESTA_OPCION($idUsuario)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta' => $sql->field('id_pregunta'), 'id_respuesta' => $sql->field('id_respuesta')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarPreguntaSubPregunta($idUsuario) {
        //lista usuario respuesta
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_SUBPREGUNTA($idUsuario)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta' => $sql->field('id_pregunta'), 'id_seccion_cuestionario' => $sql->field('id_seccion_cuestionario'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'alternativo' => $sql->field('alternativo'), 'alternativo_ing' => $sql->field('alternativo_ing'), 'tipo_respuesta' => $sql->field('tipo_respuesta'), 'codigo' => $sql->field('codigo'), 'orden' => $sql->field('orden'), 'dato_especial' => $sql->field('dato_especial'), 'presentacion_col' => $sql->field('presentacion_col'), 'es_requerido' => $sql->field('es_requerido'), 'valor_minimo' => $sql->field('valor_minimo'), 'valor_maximo' => $sql->field('valor_maximo'), 'presentacion' => $sql->field('presentacion'), 'seccion' => $sql->field('seccion'), 'seccion_ing' => $sql->field('seccion_ing'), 'cantidad_respuesta' => $sql->field('cantidad_respuesta'), 'orden_seccion' => $sql->field('orden_seccion'), 'id_subpregunta' => $sql->field('id_subpregunta'), 'subpregunta' => $sql->field('subpregunta'), 'subpregunta_ing' => $sql->field('subpregunta_ing'), 'subpregunta_tipo_respuesta' => $sql->field('subpregunta_tipo_respuesta')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function eliminarUsuarioPreguntaSubpregunta($idUsuario, $idPregunta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_USUARIO_PREGUNTA_SUBPREGUNTA ($idUsuario, $idPregunta, '$usuario')";
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

    function grabarUsuarioPreguntaSubpregunta($idUsuario, $idUsuarioPreguntaSubpregunta, $idPregunta, $idRespuesta, $respuesta, $idSubpregunta1, $idSubrespuesta1, $subrespuesta1, $idSubpregunta2, $idSubrespuesta2, $subrespuesta2, $usuario) {
        $resultado = true;
        if ($idRespuesta == 0) {
            $idRespuesta = "NULL";
        }
        if ($idSubrespuesta1 == 0) {
            $idSubrespuesta1 = "NULL";
        }
        if ($idSubrespuesta2 == 0) {
            $idSubrespuesta2 = "NULL";
        }
        if ($idSubpregunta1 == 0) {
            $idSubpregunta1 = "NULL";
        }
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_USUARIO_PREGUNTA_SUBPREGUNTA ($idUsuario, $idUsuarioPreguntaSubpregunta, $idPregunta, $idRespuesta, '$respuesta', $idSubpregunta1, $idSubrespuesta1, '$subrespuesta1', $idSubpregunta2, $idSubrespuesta2, '$subrespuesta2', '$usuario')";
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

    function listarUsuarioPreguntaSubPregunta($idUsuario, $idPregunta, $datoEspecialPrecondicion, $datoEspecialIntolerancia, $datoEspecialTipoDieta) {
        //lista enfermedad
        $arregloPrecondicion = $this->listarPrecondicion();
        //lista intolerancia
        $arregloIntolerancia = $this->listarIntolerancia();
        //tipo dieta
        $arregloTipoDieta = $this->listarTipoDieta();
        //lista usuario respuesta
        $arreglo = array();
        $query = "CALL USP_LIST_USUARIO_PREGUNTA_SUBPREGUNTA($idUsuario, $idPregunta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $idUsuarioPreguntaSubpregunta = $sql->field('id_usuario_pregunta_subpregunta');
            $idPregunta = $sql->field('id_pregunta');
            $datoEspecialPregunta = $sql->field('dato_especial_pregunta');
            $idRespuesta = $sql->field('id_respuesta');
            $respuesta = $sql->field('respuesta');
            $respuestaIngles =  $sql->field('respuesta_ing');
            $respuestaUsuario = $sql->field('respuesta_usuario');
            $respuestaNone = $sql->field('respuesta_none');
            $idSubpregunta1 = $sql->field('id_subpregunta_1');
            $datoEspecialSubprepregunta1 =  $sql->field('dato_especial_subpregunta_1');
            $idSubrespuesta1 = $sql->field('id_subrespuesta_1');
            $subrespuesta1 = $sql->field('subrespuesta_1');
            $subrespuesta1Ingles = $sql->field('subrespuesta_1_ing');
            $subrespuesta1Usuario = $sql->field('subrespuesta_1_usuario');
            $idSubpregunta2 = $sql->field('id_subpregunta_2');
            $datoEspecialSubprepregunta2 =  $sql->field('dato_especial_subpregunta_2');
            $idSubrespuesta2 = $sql->field('id_subrespuesta_2');
            $subrespuesta2 = $sql->field('subrespuesta_2');
            $subrespuesta2Ingles = $sql->field('subrespuesta_2_ing');
            $subrespuesta2Usuario = $sql->field('subrespuesta_2_usuario');
            //la respuesta como dato especial
            if ($datoEspecialPregunta == $datoEspecialPrecondicion) {
                foreach ($arregloPrecondicion as $item) {
                    if ($idRespuesta == $item['id_enfermedad']) {
                        $respuesta = $item['nombre'];
                        $respuestaIngles = $item['nombre_ing'];
                        break;
                    }
                }
            } else if ($datoEspecialPregunta == $datoEspecialIntolerancia) {
                foreach ($arregloIntolerancia as $item) {
                    if ($idRespuesta == $item['id_intolerancia']) {
                        $respuesta = $item['nombre'];
                        $respuestaIngles = $item['nombre_ing'];
                        break;
                    }
                }
            } else if ($datoEspecialPregunta == $datoEspecialTipoDieta) {
                foreach ($arregloTipoDieta as $item) {
                    if ($idRespuesta == $item['id_tipo_dieta']) {
                        $respuesta = $item['nombre'];
                        $respuestaIngles = $item['nombre_ing'];
                        break;
                    }
                }
            }
            //la respuesta 1 como dato especial
            if ($datoEspecialSubprepregunta1 == $datoEspecialPrecondicion) {
                foreach ($arregloPrecondicion as $item) {
                    if ($idSubrespuesta1 == $item['id_enfermedad']) {
                        $subrespuesta1 = $item['nombre'];
                        $subrespuesta1Ingles = $item['nombre_ing'];
                        break;
                    }
                }
            } else if ($datoEspecialSubprepregunta1 == $datoEspecialIntolerancia) {
                foreach ($arregloIntolerancia as $item) {
                    if ($idSubrespuesta1 == $item['id_intolerancia']) {
                        $subrespuesta1 = $item['nombre'];
                        $subrespuesta1Ingles = $item['nombre_ing'];
                        break;
                    }
                }
            } else if ($datoEspecialSubprepregunta1 == $datoEspecialTipoDieta) {
                foreach ($arregloTipoDieta as $item) {
                    if ($idSubrespuesta1 == $item['id_tipo_dieta']) {
                        $subrespuesta1 = $item['nombre'];
                        $subrespuesta1Ingles = $item['nombre_ing'];
                        break;
                    }
                }
            }
            //la respuesta 2 como dato especial
            if ($datoEspecialSubprepregunta2 == $datoEspecialPrecondicion) {
                foreach ($arregloPrecondicion as $item) {
                    if ($idSubrespuesta2 == $item['id_enfermedad']) {
                        $subrespuesta2 = $item['nombre'];
                        $subrespuesta2Ingles = $item['nombre_ing'];
                        break;
                    }
                }
            } else if ($datoEspecialSubprepregunta2 == $datoEspecialIntolerancia) {
                foreach ($arregloIntolerancia as $item) {
                    if ($idSubrespuesta2 == $item['id_intolerancia']) {
                        $subrespuesta2 = $item['nombre'];
                        $subrespuesta2Ingles = $item['nombre_ing'];
                        break;
                    }
                }
            } else if ($datoEspecialSubprepregunta2 == $datoEspecialTipoDieta) {
                foreach ($arregloTipoDieta as $item) {
                    if ($idSubrespuesta2 == $item['id_tipo_dieta']) {
                        $subrespuesta2 = $item['nombre'];
                        $subrespuesta2Ingles = $item['nombre_ing'];
                        break;
                    }
                }
            }

            array_push($arreglo, array('id_usuario_pregunta_subpregunta' => $idUsuarioPreguntaSubpregunta, 'id_pregunta' => $idPregunta, 'id_respuesta' => $idRespuesta, 'respuesta' => $respuesta, 'respuesta_ing' => $respuestaIngles, 'respuesta_usuario' => $respuestaUsuario, 'respuesta_none' => $respuestaNone, 'id_subpregunta_1' => $idSubpregunta1, 'id_subrespuesta_1' => $idSubrespuesta1, 'subrespuesta_1' => $subrespuesta1, 'subrespuesta_1_ing' => $subrespuesta1Ingles, 'subrespuesta_1_usuario' => $subrespuesta1Usuario, 'id_subpregunta_2' => $idSubpregunta2, 'id_subrespuesta_2' => $idSubrespuesta2, 'subrespuesta_2' => $subrespuesta2, 'subrespuesta_2_ing' => $subrespuesta2Ingles, 'subrespuesta_2_usuario' => $subrespuesta2Usuario));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarPreguntaPrecondicion($idPregunta, $estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_ENFERMEDAD($idPregunta, $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta_enfermedad' => $sql->field('id_pregunta_enfermedad'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerPreguntaPrecondicion($idPreguntaPrecondicion) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_PREGUNTA_ENFERMEDAD($idPreguntaPrecondicion)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_pregunta_enfermedad' => $sql->field('id_pregunta_enfermedad'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function editarPreguntaPrecondicion($idPreguntaPrecondicion, $orden, $none, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_ENFERMEDAD ($idPreguntaPrecondicion, $orden, $none, '$estado', '$usuario')";
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

    function grabarOrdenPreguntaPrecondicion($idPreguntaPrecondicion, $orden, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_ENFERMEDAD_ORDEN ($idPreguntaPrecondicion, $orden, '$usuario')";
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

    function poblarPreguntaPrecondicion($idPregunta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_LOAD_PREGUNTA_ENFERMEDAD ($idPregunta, '$usuario')";
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

    function eliminarPreguntaPrecondicion($idPreguntaPrecondicion, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_PREGUNTA_ENFERMEDAD ($idPreguntaPrecondicion, '$usuario')";
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

    function activarPreguntaPrecondicion($idPreguntaPrecondicion, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ACTI_PREGUNTA_ENFERMEDAD ($idPreguntaPrecondicion, '$usuario')";
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

    function listarPreguntaIntolerancia($idPregunta, $estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_INTOLERANCIA($idPregunta, $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta_intolerancia' => $sql->field('id_pregunta_intolerancia'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerPreguntaIntolerancia($idPreguntaIntolerancia) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_PREGUNTA_INTOLERANCIA($idPreguntaIntolerancia)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_pregunta_intolerancia' => $sql->field('id_pregunta_intolerancia'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function editarPreguntaIntolerancia($idPreguntaIntolerancia, $orden, $none, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_INTOLERANCIA ($idPreguntaIntolerancia, $orden, $none, '$estado', '$usuario')";
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

    function grabarOrdenPreguntaIntolerancia($idPreguntaIntolerancia, $orden, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_INTOLERANCIA_ORDEN ($idPreguntaIntolerancia, $orden, '$usuario')";
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

    function poblarPreguntaIntolerancia($idPregunta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_LOAD_PREGUNTA_INTOLERANCIA ($idPregunta, '$usuario')";
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

    function eliminarPreguntaIntolerancia($idPreguntaIntolerancia, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_PREGUNTA_INTOLERANCIA ($idPreguntaIntolerancia, '$usuario')";
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

    function activarPreguntaIntolerancia($idPreguntaIntolerancia, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ACTI_PREGUNTA_INTOLERANCIA ($idPreguntaIntolerancia, '$usuario')";
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

    function listarPreguntaTrastornoEstomacal($idPregunta, $estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_TRASTORNO_ESTOMACAL ($idPregunta, $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta_trastorno_estomacal' => $sql->field('id_pregunta_trastorno_estomacal'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerPreguntaTrastornoEstomacal($idPreguntaTrastornoEstomacal) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_PREGUNTA_TRASTORNO_ESTOMACAL ($idPreguntaTrastornoEstomacal)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_pregunta_trastorno_estomacal' => $sql->field('id_pregunta_trastorno_estomacal'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function editarPreguntaTrastornoEstomacal($idPreguntaTrastornoEstomacal, $orden, $none, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_TRASTORNO_ESTOMACAL ($idPreguntaTrastornoEstomacal, $orden, $none, '$estado', '$usuario')";
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

    function grabarOrdenPreguntaTrastornoEstomacal($idPreguntaTrastornoEstomacal, $orden, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_TRASTORNO_ESTOMACAL_ORDEN ($idPreguntaTrastornoEstomacal, $orden, '$usuario')";
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

    function poblarPreguntaTrastornoEstomacal($idPregunta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_LOAD_PREGUNTA_TRASTORNO_ESTOMACAL ($idPregunta, '$usuario')";
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

    function eliminarPreguntaTrastornoEstomacal($idPreguntaTrastornoEstomacal, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_PREGUNTA_TRASTORNO_ESTOMACAL ($idPreguntaTrastornoEstomacal, '$usuario')";
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

    function activarPreguntaTrastornoEstomacal($idPreguntaTrastornoEstomacal, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ACTI_PREGUNTA_TRASTORNO_ESTOMACAL ($idPreguntaTrastornoEstomacal, '$usuario')";
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

    function listarPreguntaTipoDieta($idPregunta, $estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_TIPO_DIETA($idPregunta, $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta_tipo_dieta' => $sql->field('id_pregunta_tipo_dieta'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerPreguntaTipoDieta($idPreguntaTipoDieta) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_PREGUNTA_TIPO_DIETA($idPreguntaTipoDieta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_pregunta_tipo_dieta' => $sql->field('id_pregunta_tipo_dieta'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function editarPreguntaTipoDieta($idPreguntaTipoDieta, $orden, $none, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_TIPO_DIETA ($idPreguntaTipoDieta, $orden, $none, '$estado', '$usuario')";
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

    function grabarOrdenPreguntaTipoDieta($idPreguntaTipoDieta, $orden, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_TIPO_DIETA_ORDEN ($idPreguntaTipoDieta, $orden, '$usuario')";
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

    function poblarPreguntaTipoDieta($idPregunta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_LOAD_PREGUNTA_TIPO_DIETA ($idPregunta, '$usuario')";
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

    function eliminarPreguntaTipoDieta($idPreguntaTipoDieta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_PREGUNTA_TIPO_DIETA ($idPreguntaTipoDieta, '$usuario')";
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

    function activarPreguntaTipoDieta($idPreguntaTipoDieta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ACTI_PREGUNTA_TIPO_DIETA ($idPreguntaTipoDieta, '$usuario')";
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

    function listarPreguntaTipoGenero($idPregunta, $estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_TIPO_GENERO($idPregunta, $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta_tipo_genero' => $sql->field('id_pregunta_tipo_genero'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerPreguntaTipoGenero($idPreguntaTipoGenero) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_PREGUNTA_TIPO_GENERO($idPreguntaTipoGenero)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_pregunta_tipo_genero' => $sql->field('id_pregunta_tipo_genero'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function editarPreguntaTipoGenero($idPreguntaTipoGenero, $orden, $none, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_TIPO_GENERO ($idPreguntaTipoGenero, $orden, $none, '$estado', '$usuario')";
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

    function grabarOrdenPreguntaTipoGenero($idPreguntaTipoGenero, $orden, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_TIPO_GENERO_ORDEN ($idPreguntaTipoGenero, $orden, '$usuario')";
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

    function poblarPreguntaTipoGenero($idPregunta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_LOAD_PREGUNTA_TIPO_GENERO ($idPregunta, '$usuario')";
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

    function eliminarPreguntaTipoGenero($idPreguntaTipoGenero, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_PREGUNTA_TIPO_GENERO ($idPreguntaTipoGenero, '$usuario')";
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

    function activarPreguntaTipoGenero($idPreguntaTipoGenero, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ACTI_PREGUNTA_TIPO_GENERO ($idPreguntaTipoGenero, '$usuario')";
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

    function crearMiembroFamiliar ($nombre, $nombreIngles, $usuario) {
        $idSeccion = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_MIEMBRO_FAMILIAR ('$nombre', '$nombreIngles', '$usuario', @p_id_miembro_familiar);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_miembro_familiar AS id_miembro_familiar");
        $sql->read();
        if ($sql->next()) {
            $idSeccion = $sql->field('id_miembro_familiar');
        }
        $cnx->close();
        $cnx = null;
        return $idSeccion;
    }

    function editarMiembroFamiliar($idMiembroFamiliar, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        try {
            $execute = "CALL USP_EDIT_MIEMBRO_FAMILIAR ($idMiembroFamiliar, '$nombre', '$nombreIngles', $estado, '$usuario');";
            $cnx->execute($execute);
        } catch (Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }

    function listarMiembroFamiliar($estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_MIEMBRO_FAMILIAR($estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_miembro_familiar' => $sql->field('id_miembro_familiar'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerMiembroFamiliar($idMiembroFamiliar) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_MIEMBRO_FAMILIAR($idMiembroFamiliar)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_miembro_familiar' => $sql->field('id_miembro_familiar'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
    
    function listarPreguntaTipoActividad($idPregunta, $estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_PREGUNTA_TIPO_ACTIVIDAD ($idPregunta, $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_pregunta_tipo_actividad' => $sql->field('id_pregunta_tipo_actividad'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerPreguntaTipoActividad($idPreguntaTipoActividad) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_PREGUNTA_TIPO_ACTIVIDAD ($idPreguntaTipoActividad)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_pregunta_tipo_actividad' => $sql->field('id_pregunta_tipo_actividad'), 'id_pregunta' => $sql->field('id_pregunta'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'orden' => $sql->field('orden'), 'none' => $sql->field('none'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function editarPreguntaTipoActividad($idPreguntaTipoActividad, $orden, $none, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_TIPO_ACTIVIDAD ($idPreguntaTipoActividad, $orden, $none, '$estado', '$usuario')";
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

    function grabarOrdenPreguntaTipoActividad($idPreguntaTipoActividad, $orden, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_PREGUNTA_TIPO_ACTIVIDAD_ORDEN ($idPreguntaTipoActividad, $orden, '$usuario')";
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

    function poblarPreguntaTipoActividad($idPregunta, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_LOAD_PREGUNTA_TIPO_ACTIVIDAD ($idPregunta, '$usuario')";
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

    function eliminarPreguntaTipoActividad($idPreguntaTipoActividad, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_PREGUNTA_TIPO_ACTIVIDAD ($idPreguntaTipoActividad, '$usuario')";
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

    function activarPreguntaTipoActividad($idPreguntaTipoActividad, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ACTI_PREGUNTA_TIPO_ACTIVIDAD ($idPreguntaTipoActividad, '$usuario')";
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

    function listarQuestionnaireSet($estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_QUESTIONNAIRE_SET ($estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_questionnaire_set' => $sql->field('id_questionnaire_set'), 'name' => $sql->field('name'), 'note' => $sql->field('note'), 'status' => $sql->field('status'), 'created_by' => $sql->field('created_by'), 'created' => $sql->field('created'), 'updated_by' => $sql->field('updated_by'), 'updated' => $sql->field('updated')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function grabarPreguntaQuestionnaireSet($idPregunta, $idQuestionnaireSet, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_PREGUNTA_QUESTIONNAIRE_SET ($idPregunta, $idQuestionnaireSet, '$usuario')";
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

    function grabarPreguntaQuestionnaireSetPorAfiliado($idPregunta, $idAfiliado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_PREGUNTA_QUESTIONNAIRE_SET_X_AFILIADO ($idPregunta, $idAfiliado, '$usuario')";
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

    function grabarSeccionCuestionarioQuestionnaireSet($idSeccionCuestionario, $idQuestionnaireSet, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_SECCION_CUESTIONARIO_QUESTIONNAIRE_SET ($idSeccionCuestionario, $idQuestionnaireSet, '$usuario')";
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

    function grabarSeccionCuestionarioQuestionnaireSetPorAfiliado($idSeccionCuestionario, $idAfiliado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_UPD_SECCION_CUESTIONARIO_QUESTIONNAIRE_SET_X_AFILIADO ($idSeccionCuestionario, $idAfiliado, '$usuario')";
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

    //privados
    function existeRespuesta($arreglo, $idPregunta, $idRespuesta) {
        $result = false;
        foreach($arreglo as $item) {
            if ($item['id_pregunta'] == $idPregunta && $item['id_respuesta'] == $idRespuesta) {
                $result = true;
                break;
            }
        }
        return $result;
    }

}

?>