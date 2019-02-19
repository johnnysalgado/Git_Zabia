<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

require('../../inc/configuracion.php');
require('../../inc/mysql.php');
require('../../inc/functions.php');
require('../../inc/constante.php');
require('../../inc/constante_cuestionario.php');
require('../../inc/dao_cuestionario.php');

error_reporting(E_ERROR);

function obtenerControlTexto($idControl, $respuestaUsuario, $requerido, $clasePrimerControl, $placeholder) {
    $htmlControl = "<input id=\"$idControl\" name=\"$idControl\" type=\"text\" class=\"form-control $clasePrimerControl\" value=\"$respuestaUsuario\" $requerido placeholder=\"$placeholder\" />";
    return $htmlControl;
}

function obtenerControlFecha($idControl, $respuestaUsuario, $requerido, $clasePrimerControl, $placeholder) {
    $htmlControl = "<input id=\"$idControl\" name=\"$idControl\" type=\"date\" class=\"form-control $clasePrimerControl\" value=\"$respuestaUsuario\" $requerido placeholder=\"$placeholder\" />";
    return $htmlControl;
}

function obtenerControlEntero($idControl, $respuestaUsuario, $requerido, $valorMinimo, $valorMaximo, $clasePrimerControl, $placeholder) {
    $valorMinimo = round($valorMinimo, 0);
    $valorMaximo = round($valorMaximo, 0);
    $htmlControl = "<input id=\"$idControl\" name=\"$idControl\" type=\"number\" class=\"form-control $clasePrimerControl\" step=\"1\" min=\"$valorMinimo\" value=\"$respuestaUsuario\" $requerido ";
    if ($valorMaximo > 0) {
        $htmlControl .= " max=\"$valorMaximo\"";
    }
    $htmlControl .= " placeholder=\"$placeholder\" />";
    return $htmlControl;
}

function obtenerControlDecimal($idControl, $respuestaUsuario, $requerido, $valorMinimo, $valorMaximo, $clasePrimerControl, $placeholder) {
    $valorMinimo = round($valorMinimo, 2);
    $valorMaximo = round($valorMaximo, 2);
    $htmlControl = "<input id=\"$idControl\" name=\"$idControl\" type=\"number\" class=\"form-control $clasePrimerControl\" step=\"0.01\" min=\"$valorMinimo\" value=\"$respuestaUsuario\" $requerido ";
    if ($valorMaximo > 0) {
        $htmlControl .= " max=\"$valorMaximo\"";
    }
    $htmlControl .= " placeholder=\"$placeholder\" />";
    return $htmlControl;
}

function obtenerControlUnico($esSelect, $idControl, $respuestaArreglo, $requerido, $esPreguntaRequerida, $controlPresentacion, $clasePrimerControl, $ocultaOpcion, $lenguaje) {
    $htmlControl = "";
    if ($esSelect) {
        $htmlControl = "<select id=\"$idControl\" name=\"$idControl\" class=\"form-control $clasePrimerControl\" data-dropup-auto=\"false\" $requerido data-hide-option=\"$ocultaOpcion\" >";
        if ($lenguaje == LENGUAJE_INGLES) {
            $htmlControl .= " <option value=\"\">[Choose]</option>";
        } else {
            $htmlControl .= " <option value=\"\">[Seleccionar]</option>";
        }
        foreach ($respuestaArreglo as $itemRespuesta) {
            $idOpcion = $itemRespuesta['answerID'];
            $opcion = $itemRespuesta['description'];
            $userSelected = $itemRespuesta['userSelected'];
            $styleOcultaOpcion = "";
            if ($userSelected == "1") {
                if ($ocultaOpcion == "1") {
                    $styleOcultaOpcion = "style=\"display:none;\" ";
                    $selected = "";
                } else {
                    $selected = " selected=\"selected\"";
                }
            } else {
                $selected = "";
            }
            $sectionShow = $itemRespuesta['sectionShow'];
            $sectionSkip = $itemRespuesta['sectionSkip'];
            $questionShow = $itemRespuesta['questionShow'];
            $questionSkip = $itemRespuesta['questionSkip'];
            $answerNone = $itemRespuesta['answerNone'];
            $htmlControl .= "<option value=\"$idOpcion\" $selected data-section-show=\"$sectionShow\" data-section-hide=\"$sectionSkip\" data-question-show=\"$questionShow\" data-question-hide=\"$questionSkip\" data-none=\"$answerNone\" $styleOcultaOpcion >$opcion</option> ";
        }
        $htmlControl .= "</select> ";
    } else {
        $htmlControl = "<div data-field-is-required=\"$esPreguntaRequerida\">";
        if ($controlPresentacion == CONTROL_PRESENTACION_HORIZONTAL) {
            $htmlControl .= " <div class=\"radio radio-primary\" >";
        }
        foreach ($respuestaArreglo as $itemRespuesta) {
            $idOpcion = $itemRespuesta['answerID'];
            $idControlOpcion = $itemRespuesta['answerControlID'];
            $opcion = $itemRespuesta['description'];
            $userSelected = $itemRespuesta['userSelected'];
            if ($userSelected == "1") {
                $checked = " checked=\"checked\"";
            } else {
                $checked = "";
            }
            $sectionShow = $itemRespuesta['sectionShow'];
            $sectionSkip = $itemRespuesta['sectionSkip'];
            $questionShow = $itemRespuesta['questionShow'];
            $questionSkip = $itemRespuesta['questionSkip'];
            $answerNone = $itemRespuesta['answerNone'];
            if ($controlPresentacion == CONTROL_PRESENTACION_VERTICAL) {
                $htmlControl .= " <div class=\"radio radio-primary\" >";
            }
            $htmlControl .= "<label> <input  type=\"radio\" id=\"$idControlOpcion\" name=\"$idControl\" value=\"$idOpcion\" $checked data-section-show=\"$sectionShow\" data-section-hide=\"$sectionSkip\" data-question-show=\"$questionShow\" data-question-hide=\"$questionSkip\" data-none=\"$answerNone\" /> <span class=\"circle\"> </span> <span class=\"check\"> </span> $opcion </label>";
            if ($controlPresentacion == CONTROL_PRESENTACION_VERTICAL) {
                $htmlControl .= " </div> ";
            }
        }
        if ($controlPresentacion == CONTROL_PRESENTACION_HORIZONTAL) {
            $htmlControl .= " </div> ";
        }
        $htmlControl .= " </div>";
    }
    return $htmlControl;
}

function obtenerControlMultiple($idControl, $respuestaArreglo, $esPreguntaRequerida, $controlPresentacion, $esSelectParaLineal, $clasePrimerControl, $ocultaOpcion, $lenguaje) {
    $htmlControl = "";
    if ($esSelectParaLineal) {
        $htmlControl = "<select id=\"$idControl\" name=\"$idControl\" class=\"form-control $clasePrimerControl\" $requerido data-hide-option=\"$ocultaOpcion\" >";
        if ($lenguaje == LENGUAJE_INGLES) {
            $htmlControl .= " <option value=\"\">[Choose]</option> ";
        } else {
            $htmlControl .= " <option value=\"\">[Seleccionar]</option> ";
        }
        foreach ($respuestaArreglo as $itemRespuesta) {
            $idOpcion = $itemRespuesta['answerID'];
            $opcion = $itemRespuesta['description'];
            $userSelected = $itemRespuesta['userSelected'];
            $sectionShow = $itemRespuesta['sectionShow'];
            $sectionSkip = $itemRespuesta['sectionSkip'];
            $questionShow = $itemRespuesta['questionShow'];
            $questionSkip = $itemRespuesta['questionSkip'];
            $answerNone = $itemRespuesta['answerNone'];
            $styleOcultaOpcion = "";
            if ($userSelected == "1") {
                if ($ocultaOpcion == "1") {
                    $styleOcultaOpcion = "style=\"display:none;\" ";
                }
            }

            $htmlControl .= "<option value=\"$idOpcion\" data-section-show=\"$sectionShow\" data-section-hide=\"$sectionSkip\" data-question-show=\"$questionShow\" data-question-hide=\"$questionSkip\" data-none=\"$answerNone\" $styleOcultaOpcion >$opcion</option> ";
        }
        $htmlControl .= "</select> ";
    } else {
        if ($controlPresentacion == CONTROL_PRESENTACION_VERTICAL) {
            $htmlControl = "<div class=\"d-flex flex-column checkbox\" style=\"margin-left:10px;\"  data-field-is-required=\"$esPreguntaRequerida\" >";
            foreach ($respuestaArreglo as $itemRespuesta) {
                $idOpcion = $itemRespuesta['answerID'];
                $idControlOpcion = $itemRespuesta['answerControlID'];
                $opcion = $itemRespuesta['description'];
                $userSelected = $itemRespuesta['userSelected'];
                if ($userSelected == "1") {
                    $checked = " checked=\"checked\"";
                } else {
                    $checked = "";
                }
                $sectionShow = $itemRespuesta['sectionShow'];
                $sectionSkip = $itemRespuesta['sectionSkip'];
                $questionShow = $itemRespuesta['questionShow'];
                $questionSkip = $itemRespuesta['questionSkip'];
                $answerNone = $itemRespuesta['answerNone'];
                $htmlControl .= " <label> <input  type=\"checkbox\" id=\"$idControlOpcion\" name=\"$idControl\" value=\"$idOpcion\" $checked data-section-show=\"$sectionShow\" data-section-hide=\"$sectionSkip\" data-question-show=\"$questionShow\" data-question-hide=\"$questionSkip\" data-none=\"$answerNone\"/> <span class=\"checkbox-material\"> <span class=\"check\"> </span> </span> $opcion </label> ";
            }
            $htmlControl .= " </div>";
        } else {
            $htmlOpcionMulti = "";
            $htmlControl = " <div class=\"mb-1\"> <select id=\"$idControl\" name=\"$idControl\" class=\"form-control select-table $clasePrimerControl\">";
            if ($lenguaje == LENGUAJE_INGLES) {
                $htmlControl .= " <option value=\"\">[Choose]</option>";
            } else {
                $htmlControl .= " <option value=\"\">[Seleccionar]</option>";
            }
            foreach ($respuestaArreglo as $itemRespuesta) {
                $idOpcion = $itemRespuesta['answerID'];
                $idControlOpcion = $itemRespuesta['answerControlID'];
                $opcion = $itemRespuesta['description'];
                $userSelected = $itemRespuesta['userSelected'];
                if ($userSelected == "1") {
                    $style = " style=\"display:none\"";
                    $htmlOpcionMulti .= "<tr> <td> $opcion </td> <td class=\"text-right\"> <a data-value=\"$idOpcion\" href=\"javascript:;\" class=\"btn-remove btn-circle btn-circle-xs btn-circle-raised btn-circle-danger\"> <i class=\"fa fa-trash\"></i> </a> </td> </tr> ";
                } else {
                    $style = "";
                }
                $sectionShow = $itemRespuesta['sectionShow'];
                $sectionSkip = $itemRespuesta['sectionSkip'];
                $questionShow = $itemRespuesta['questionShow'];
                $questionSkip = $itemRespuesta['questionSkip'];
                $answerNone = $itemRespuesta['answerNone'];
                $htmlControl .= "<option value=\"$idOpcion\" $style data-section-show=\"$sectionShow\" data-section-hide=\"$sectionSkip\" data-question-show=\"$questionShow\" data-question-hide=\"$questionSkip\" data-none=\"$answerNone\">$opcion</option> ";
            }
            $htmlControl .= " </select> </div> ";
            $htmlControl .= " <table id=\"table_$idControl\" class=\"table\"> <tbody> $htmlOpcionMulti </tbody> </table> ";
        }
    }
    return $htmlControl;
}

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $idSeccion = $request->sectionID;
    $idAfiliado = $request->affiliateID;
    $lenguaje = $request->language;
    
    $arregloData = array();

    if ( ($user == '') || (!is_numeric($user)) ) {
        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "User error";
        } else {
            $mensaje = "Error en usuario";
        }
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
            , 'data' => '');
        $respuesta = json_encode($output);
        die ($respuesta);
    }
    if ($idSeccion == '') {
        $idSeccion = 0;
    } else if (!is_numeric($idSeccion)) {
        if ($lenguaje == LENGUAJE_INGLES) {
            $mensaje = "Section error";
        } else {
            $mensaje = "Error en sección";
        }
        $output = array(
            'status' => '0'
            , 'message' => $mensaje
            , 'data' => '');
        $respuesta = json_encode($output);
        die ($respuesta);
    }

    $daoCuestionario = new DaoCuestionario();
    $arreglo = $daoCuestionario->listarUsuarioPreguntaRespuesta($user, $idSeccion, $idAfiliado);
    $arregloSeccionEnlazada = $daoCuestionario->listarSeccionEnlazada();
    $arregloPreguntaEnlazada = $daoCuestionario->listarPreguntaEnlazada();

    $idSeccionBD = 0;
    $idSeccionBDx = 0;
    $contadorSeccion = 0;
    $contadorArreglo = 0;

    $cantidadArreglo = count($arreglo);
    while ($contadorArreglo < $cantidadArreglo) {
        $item = $arreglo[$contadorArreglo];
        if ($lenguaje == LENGUAJE_INGLES) {
            $descripcionSeccion = $item['seccion_ing'];
        }  else {
            $descripcionSeccion = $item['seccion'];
        }
        $idSeccionBD = $item['id_seccion_cuestionario'];
        $seccionIcono = $item['seccion_icono'];
        $idSeccionBDx = $idSeccionBD;
        $contadorSeccion ++;

        $htmlPregunta = "";
        $idPregunta = 0;
        $idPreguntax = 0;
        while ($contadorArreglo < $cantidadArreglo && $idSeccionBD == $idSeccionBDx) {
            $idPregunta = $item['id_pregunta'];
            $descripcionPregunta = $item['pregunta'];
            $descripcionInglesPregunta = $item['pregunta_ing'];
            $alternativoPregunta = $item['pregunta_alternativo'];
            $alternativoPreguntaIngles = $item['pregunta_alternativo_ing'];
            $tipoPregunta = $item['seccion'];
            $tipoRespuesta = $item['tipo_respuesta'];
            $codigo = $item['codigo'];
            $datoEspecial = $item['dato_especial'];
            $columnaPresentacion = $item['presentacion_col'];
            $esPreguntaCondicionada = $item['es_condicionada'];
            $esPreguntaRequerida = $item['es_requerido'];
            if ($esPreguntaRequerida == 1) {
                $requerido = "required=\"required\"";
            } else {
                $requerido = "";
            }
            $valorMinimo = $item['valor_minimo'];
            $valorMaximo = $item['valor_maximo'];
            $controlPresentacion = $item['presentacion'];
            $ocultaOpcion = $item['oculta_opcion'];
            $tieneSubpregunta = $item['tiene_subpregunta'];
            if ($valorMinimo == null) {
                $valorMinimo = 0;
            }
            if ($lenguaje == LENGUAJE_INGLES) {
                $tituloPregunta = $descripcionInglesPregunta;
            } else {
                $tituloPregunta = $descripcionPregunta;
            }
            $tituloPregunta = str_replace("|", "<br />", $tituloPregunta);
            if ($lenguaje == LENGUAJE_INGLES) {
                $placeholder = $alternativoPreguntaIngles;
            } else {
                $placeholder = $alternativoPregunta;
            }
            if ($columnaPresentacion == "" || $columnaPresentacion == "0") {
                $columnaPresentacion = COLUMNA_TERCIO;
            }
            $cols = round(12.0 * floatval($columnaPresentacion), 0);
            $claseColumna = "col-md-" . $cols;
            $idControlPregunta = PREFIJO_CONTROL_PREGUNTA . "_" . $idPregunta;
            $respuestaArray = array();
            $htmlRespuesta = "";

            $idPreguntax = $idPregunta;
            while ($contadorArreglo < $cantidadArreglo && $idSeccionBD == $idSeccionBDx && $idPregunta == $idPreguntax) {

                if ($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE || $tipoRespuesta == TIPO_RESPUESTA_UNICA) {
                    $idRespuesta = $item['id_respuesta'];
                    $idRespuestaUsuario = $item['id_respuesta_usuario'];
                    $respuestaNone = $item['respuesta_none'];
                    if ($idRespuesta == $idRespuestaUsuario) {
                        $userSelected = "1";
                    } else {
                        $userSelected = "0";
                    }
                    if ($lenguaje == LENGUAJE_INGLES) {
                        $descripcionRespuesta = $item['respuesta_ing'];
                    } else {
                        $descripcionRespuesta = $item['respuesta'];
                    }
                    //sección mostrar
                    $seccionMostrar = obtenerSeccionEnlazada($arregloSeccionEnlazada, $idPregunta, $idRespuesta, 'mostrar');
                    //sección omitir
                    $seccionOmitir = obtenerSeccionEnlazada($arregloSeccionEnlazada, $idPregunta, $idRespuesta, 'omitir');
                    //pregunta mostrar
                    $preguntaMostrar = obtenerPreguntaEnlazada($arregloPreguntaEnlazada, $idPregunta, $idRespuesta, 'mostrar');
                    //pregunta omitir
                    $preguntaOmitir = obtenerPreguntaEnlazada($arregloPreguntaEnlazada, $idPregunta, $idRespuesta, 'omitir');
                    $idControlRespuesta = PREFIJO_CONTROL_PREGUNTA . "_" . $idPreguntax . "-" . PREFIJO_CONTROL_RESPUESTA . "_" . $idRespuesta;
                    array_push($respuestaArray, array('answerID' => $idRespuesta, 'answerControlID' => $idControlRespuesta, 'description' => $descripcionRespuesta, 'userSelected' => $userSelected, 'userAnswer' => '', 'sectionShow' => $seccionMostrar, 'sectionSkip' => $seccionOmitir, 'questionShow' => $preguntaMostrar, 'questionSkip' => $preguntaOmitir, 'answerNone' => $respuestaNone));
                } else {
                    $respuestaUsuario = $item['respuesta_usuario'];
                    $idControlRespuesta = PREFIJO_CONTROL_PREGUNTA . "_" . $idPreguntax;
                    array_push($respuestaArray, array('answerID' => 0, 'answerControlID' => $idControlRespuesta, 'description' => '', 'userSelected' => '0', 'userAnswer' => $respuestaUsuario, 'sectionShow' => '', 'sectionSkip' => '', 'questionShow' => '', 'questionSkip' => '', 'answerNone' => 0));
                }
                $contadorArreglo++;
                $item = $arreglo[$contadorArreglo];
                $idSeccionBD = $item['id_seccion_cuestionario'];
                $idPregunta = $item['id_pregunta'];
            }

            //arma el html de las respuestas
            if ($tieneSubpregunta == 1) {
                $htmlPregunta .= " <div id=\"divQuestion_$idPreguntax\" class=\"col-md-12 mb-1 border rounded\" > <div class=\"row align-items-end\"> <div class=\"[CLASE_COL]\"> <label class=\"control-label\" > $tituloPregunta </label> ";
                $clasePrimerControl = " select-table-group";
                switch ($tipoRespuesta) {
                    case TIPO_RESPUESTA_FECHA:
                        $itemRespuesta = $respuestaArray[0];
                        $htmlRespuesta = obtenerControlFecha($idControlPregunta, $itemRespuesta['userAnswer'], $requerido, "", $placeholder);
                        break;
                    case TIPO_RESPUESTA_TEXTO:
                        $itemRespuesta = $respuestaArray[0];
                        $htmlRespuesta = obtenerControlTexto($idControlPregunta, $itemRespuesta['userAnswer'], $requerido, "", $placeholder);
                        break;
                    case TIPO_RESPUESTA_NUMERO:
                        $itemRespuesta = $respuestaArray[0];
                        $htmlRespuesta = obtenerControlEntero($idControlPregunta, $itemRespuesta['userAnswer'], $requerido, $valorMinimo, $valorMaximo, "", $placeholder);
                        break;
                    case TIPO_RESPUESTA_DECIMAL:
                        $itemRespuesta = $respuestaArray[0];
                        $htmlRespuesta = obtenerControlDecimal($idControlPregunta, $itemRespuesta['userAnswer'], $requerido, $valorMinimo, $valorMaximo, "", $placeholder);
                        break;
                    case TIPO_RESPUESTA_UNICA:
                        $esSelect = true;
                        $htmlRespuesta = obtenerControlUnico($esSelect, $idControlPregunta, $respuestaArray, $requerido, $esPreguntaRequerida, $controlPresentacion, $clasePrimerControl, $ocultaOpcion, $lenguaje);
                        break;
                    case TIPO_RESPUESTA_MULTIPLE:
                        $esSelectParaLineal = true;
                        $htmlRespuesta = obtenerControlMultiple($idControlPregunta, $respuestaArray, $esPreguntaRequerida, $controlPresentacion, $esSelectParaLineal, $clasePrimerControl, $ocultaOpcion, $lenguaje);
                        break;
                }
                $htmlPregunta .= $htmlRespuesta ." </div>";

                $subpreguntaArreglo = $daoCuestionario->listarSubpreguntaSubrespuesta ($idPreguntax, DATOESPECIAL_PRECONDICION, DATOESPECIAL_INTOLERANCIA_ALERGIA, DATOESPECIAL_TIPO_DIETA);
                $idSubpreguntax = 0;
                $contadorSubpreguntaIndice = 0;
                $contadorSubpregunta = 0;
                $cantidadSubpregunta = count($subpreguntaArreglo);
                while ($contadorSubpregunta < $cantidadSubpregunta) {
                    $subrespuestaArray = array();
                    $itemSubpregunta = $subpreguntaArreglo[$contadorSubpregunta];
                    $idSubpregunta = $itemSubpregunta['id_subpregunta'];
                    $descripcionSubpregunta = $itemSubpregunta['subpregunta'];
                    $descripcionInglesSubpregunta = $itemSubpregunta['subpregunta_ing'];
                    if ($lenguaje == LENGUAJE_INGLES) {
                        $tituloSubpregunta = $descripcionInglesSubpregunta;
                    } else {
                        $tituloSubpregunta = $descripcionSubpregunta;
                    }
                    $tituloSubpregunta = str_replace("|", "<br />", $tituloSubpregunta);
                    $tipoSubrespuesta = $itemSubpregunta['tipo_respuesta'];
                    $valorMinimoSubpregunta = $itemSubpregunta['valor_minimo'];
                    $valorMaximoSubpregunta = $itemSubpregunta['valor_maximo'];
                    $contadorSubpreguntaIndice++;
                    $idControlSubpregunta = PREFIJO_CONTROL_PREGUNTA . "_" . $idPreguntax . "_" . PREFIJO_CONTROL_SUBPREGUNTA . "_" . $contadorSubpreguntaIndice;
                    $idSubpreguntax = $idSubpregunta;
                    while ($contadorSubpregunta < $cantidadSubpregunta && $idSubpregunta == $idSubpreguntax) {
                        if ($tipoSubrespuesta == TIPO_RESPUESTA_UNICA) {
                            $idSubrespuesta = $itemSubpregunta['id_subrespuesta'];
                            if ($lenguaje == LENGUAJE_INGLES) {
                                $descripcionSubrespuesta = $itemSubpregunta['subrespuesta_ing'];
                            } else {
                                $descripcionSubrespuesta = $itemSubpregunta['subrespuesta'];
                            }
                            $idControlSubrespuesta = PREFIJO_CONTROL_PREGUNTA . "_" . $idPreguntax . "_" . PREFIJO_CONTROL_SUBPREGUNTA . "_" . $contadorSubpreguntaIndice . "-" . PREFIJO_CONTROL_SUBRESPUESTA . "_" . $idSubrespuesta;
                            array_push($subrespuestaArray, array('answerID' => $idSubrespuesta, 'answerControlID' => $idControlSubrespuesta, 'description' => $descripcionSubrespuesta, 'userSelected' => '0', 'userAnswer' => '', 'sectionShow' => '', 'sectionSkip' => '', 'questionShow' => '', 'questionSkip' => ''));
                        } else {
                            $idControlSubrespuesta = PREFIJO_CONTROL_PREGUNTA . "_" . $idPreguntax . "_" . PREFIJO_CONTROL_SUBPREGUNTA . "_" . $contadorSubpreguntaIndice;
                            array_push($subrespuestaArray, array('answerID' => 0, 'answerControlID' => $idControlSubrespuesta, 'description' => '', 'userSelected' => '0', 'userAnswer' => '', 'sectionShow' => '', 'sectionSkip' => '', 'questionShow' => '', 'questionSkip' => ''));
                        }
                        $contadorSubpregunta++;
                        $itemSubpregunta = $subpreguntaArreglo[$contadorSubpregunta];
                        $idSubpregunta = $itemSubpregunta['id_subpregunta'];
                    }
                    $htmlPregunta .= "<div class=\"[CLASE_COL]\"> <label class=\"control-label\" >$tituloSubpregunta </label>";
                    switch ($tipoSubrespuesta) {
                        case TIPO_RESPUESTA_FECHA:
                            $itemSubrespuesta = $subrespuestaArray[0];
                            $htmlPregunta .= obtenerControlFecha($idControlSubpregunta, $itemSubrespuesta['userAnswer'], "", "", "");
                            break;
                        case TIPO_RESPUESTA_TEXTO:
                            $itemSubrespuesta = $subrespuestaArray[0];
                            $htmlPregunta .= obtenerControlTexto($idControlSubpregunta, $itemSubrespuesta['userAnswer'], "", "", "");
                            break;
                        case TIPO_RESPUESTA_NUMERO:
                            $itemSubrespuesta = $subrespuestaArray[0];
                            $htmlPregunta .= obtenerControlEntero($idControlSubpregunta, $itemSubrespuesta['userAnswer'], "", $valorMinimoSubpregunta, $valorMaximoSubpregunta, "", "");
                            break;
                        case TIPO_RESPUESTA_DECIMAL:
                            $itemSubrespuesta = $subrespuestaArray[0];
                            $htmlPregunta .= obtenerControlDecimal($idControlSubpregunta, $itemSubrespuesta['userAnswer'], "", $valorMinimoSubpregunta, $valorMaximoSubpregunta, "");
                            break;
                        case TIPO_RESPUESTA_UNICA:
                            $esSelect = true;
                            $htmlPregunta .= obtenerControlUnico($esSelect, $idControlSubpregunta, $subrespuestaArray, "", "", "", "", "0", $lenguaje);
                            break;
                    }
                    $htmlPregunta .= "</div> ";
                }
                if ($contadorSubpreguntaIndice == 2) {
                    $colMd = "col-md-3";
                    $colTd = "w-25";
                } else {
                    $colMd = "col-md-4";
                    $colTd = "w-50";
                }
                $htmlPregunta = str_replace("[CLASE_COL]", $colMd, $htmlPregunta);
                $htmlPregunta .= " <div class=\"$colMd text-right\"> <a href=\"javascript:void(0)\" id=\"" . PREFIJO_CONTROL_PREGUNTA . "_" . $idPreguntax . "_btn\" class=\"btn-add btn btn-raised btn-primary \"> <i class=\"zmdi zmdi-plus\"></i>ADD</a> </div> </div> <table id=\"table_$idControlPregunta" . "\" class=\"table\"> <tbody>";
                $usuarioSubpreguntaArreglo = $daoCuestionario->listarUsuarioPreguntaSubpregunta($user, $idPreguntax, DATOESPECIAL_PRECONDICION, DATOESPECIAL_INTOLERANCIA_ALERGIA, DATOESPECIAL_TIPO_DIETA);
                foreach ($usuarioSubpreguntaArreglo as $itemUsuario) {
                    $xidUsuarioPreguntaSubpregunta = $itemUsuario['id_usuario_pregunta_subpregunta'];
                    $xidPregunta = $itemUsuario['id_pregunta'];
                    $xidRespuesta = $itemUsuario['id_respuesta'];
                    $xrespuesta = $itemUsuario['respuesta'];
                    $xrespuestaIngles = $itemUsuario['respuesta_ing'];
                    $xrespuestaUsuario = $itemUsuario['respuesta_usuario'];
                    $xrespuestaNone = $itemUsuario['respuesta_none'];
                    $xidSubpregunta1 = $itemUsuario['id_subpregunta_1'];
                    $xidSubrespuesta1 = $itemUsuario['id_subrespuesta_1'];
                    $xsubrespuesta1 = $itemUsuario['subrespuesta_1'];
                    $xsubrespuestaIngles1 = $itemUsuario['subrespuesta_1_ing'];
                    $xsubrespuestaUsuario1 = $itemUsuario['subrespuesta_1_usuario'];
                    $xidSubpregunta2 = $itemUsuario['id_subpregunta_2'];
                    $xidSubrespuesta2 = $itemUsuario['id_subrespuesta_2'];
                    $xsubrespuesta2 = $itemUsuario['subrespuesta_2'];
                    $xsubrespuestaIngles2 = $itemUsuario['subrespuesta_2_ing'];
                    $xsubrespuestaUsuario2 = $itemUsuario['subrespuesta_2_usuario'];
                    $xrespuestaValor = "";
                    $xsubrespuestaValor1 = "";
                    $xsubrespuestaValor2 = "";
                    if ($xidRespuesta != null) {
                        if ($lenguaje == LENGUAJE_INGLES) {
                            $xrespuestaUsuario = $xrespuestaIngles; 
                        } else {
                            $xrespuestaUsuario = $xrespuesta; 
                        }
                        $xrespuestaValor = $xidRespuesta;
                    } else {
                        $xrespuestaValor = $xrespuestaUsuario;
                    }
                    if ($xidSubrespuesta1 != null) {
                        if ($lenguaje == LENGUAJE_INGLES) {
                            $xsubrespuestaUsuario1 = $xsubrespuestaIngles1; 
                        } else {
                            $xsubrespuestaUsuario1 = $xsubrespuesta1; 
                        }
                        $xsubrespuestaValor1 = $xidSubrespuesta1;
                    } else {
                        $xsubrespuestaValor1 = $xsubrespuestaUsuario1;
                    }
                    if ($xidSubrespuesta2 != null) {
                        if ($lenguaje == LENGUAJE_INGLES) {
                            $xsubrespuestaUsuario2 = $xsubrespuestaIngles2; 
                        } else {
                            $xsubrespuestaUsuario2 = $xsubrespuesta2; 
                        }
                        $xsubrespuestaValor2 = $xidSubrespuesta2;
                    } else {
                        $xsubrespuestaValor2 = $xsubrespuestaUsuario2;
                    }
                    $htmlPregunta .= "<tr data-unique=\"$xrespuestaNone\" data-show=\"$xrespuestaValor\"> <td class=\"$colTd\">$xrespuestaUsuario</td>";
                    $htmlPregunta .= "<td class=\"$colTd\"> $xsubrespuestaUsuario1 </td>";

                    if ($contadorSubpreguntaIndice == 2) {
                        $htmlPregunta .= "<td class=\"$colTd\"> $xsubrespuestaUsuario2 </td>";
                    } else {
                        $colTd = "";
                    }
                    $valuesp = "$xrespuestaValor|$xsubrespuestaValor1|$xsubrespuestaValor2";
                    $htmlPregunta .= "<td class=\"$colTd text-right\"> <a data-value=\"$valuesp\" data-id=\"$xidUsuarioPreguntaSubpregunta\" href=\"javascript:;\" class=\"btn-remove btn-circle btn-circle-xs btn-circle-raised btn-circle-danger\"> <i class=\"fa fa-trash\"></i> </a> </td> </tr>";
                }
                $htmlPregunta .= " </tbody> </table> </div>";
            } else {
                switch ($tipoRespuesta) {
                    case TIPO_RESPUESTA_FECHA:
                        $itemRespuesta = $respuestaArray[0];
                        $htmlRespuesta = obtenerControlFecha($idControlPregunta, $itemRespuesta['userAnswer'], $requerido, "", $placeholder);
                        break;
                    case TIPO_RESPUESTA_TEXTO:
                        $itemRespuesta = $respuestaArray[0];
                        $htmlRespuesta = obtenerControlTexto($idControlPregunta, $itemRespuesta['userAnswer'], $requerido, "", $placeholder);
                        break;
                    case TIPO_RESPUESTA_NUMERO:
                        $itemRespuesta = $respuestaArray[0];
                        $htmlRespuesta = obtenerControlEntero($idControlPregunta, $itemRespuesta['userAnswer'], $requerido, $valorMinimo, $valorMaximo, "", $placeholder);
                        break;
                    case TIPO_RESPUESTA_DECIMAL:
                        $itemRespuesta = $respuestaArray[0];
                        $htmlRespuesta = obtenerControlDecimal($idControlPregunta, $itemRespuesta['userAnswer'], $requerido, $valorMinimo, $valorMaximo, "", $placeholder);
                        break;
                    case TIPO_RESPUESTA_UNICA:
                        $esSelect = (count($respuestaArray) > 2 ) ? true : false;
                        $clasePrimary = "select-primary";
                        $htmlRespuesta = obtenerControlUnico($esSelect, $idControlPregunta, $respuestaArray, $requerido, $esPreguntaRequerida, $controlPresentacion, $clasePrimary, "0", $lenguaje);
                        break;
                    case TIPO_RESPUESTA_MULTIPLE:
                        $esSelectParaLineal = false;
                        $htmlRespuesta = obtenerControlMultiple($idControlPregunta, $respuestaArray, $esPreguntaRequerida, $controlPresentacion, $esSelectParaLineal, "", "0", $lenguaje);
                        break;
                }
                $htmlPregunta .= " <div id=\"divQuestion_$idPreguntax\" class=\"col-md-$cols \" > <label class=\"control-label\">$tituloPregunta</label> $htmlRespuesta </div> ";
            }
        }
        array_push($arregloData, array("id" => $idSeccionBDx, "title" => $descripcionSeccion, "order" => $contadorSeccion, "image" => $seccionIcono, "html" => $htmlPregunta));
    }

    $daoCuestionario = null;

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}

?>
