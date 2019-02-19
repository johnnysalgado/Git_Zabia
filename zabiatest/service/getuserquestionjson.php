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

require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/functions.php');
require('../inc/constante.php');
require('../inc/constante_cuestionario.php');
require('../inc/dao_cuestionario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $idSeccion = $request->sectionID;
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

    $daoUsuario = new DaoUsuario();
    $arregloUsuario = $daoUsuario->obtenerUsuario($user);
    $daoUsuario = null;
    if (count($arregloUsuario) > 0) {
        $item = $arregloUsuario[0];
        $idAfiliado = $item['id_affiliates'];

        $daoCuestionario = new DaoCuestionario();
        $arreglo = $daoCuestionario->listarUsuarioPreguntaRespuesta($user, $idSeccion, $idAfiliado);
        $arregloSeccionEnlazada = $daoCuestionario->listarSeccionEnlazada();
        $arregloPreguntaEnlazada = $daoCuestionario->listarPreguntaEnlazada();
        $daoCuestionario = null;

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
            $idSeccionBDx = $idSeccionBD;
            $contadorSeccion ++;
            $preguntaArray = array();

            $idPregunta = 0;
            $idPreguntax = 0;
            while ($contadorArreglo < $cantidadArreglo && $idSeccionBD == $idSeccionBDx) {

                $idPregunta = $item['id_pregunta'];
                $descripcionPregunta = $item['pregunta'];
                $descripcionInglesPregunta = $item['pregunta_ing'];
                $alternativoPregunta = $item['pregunta_alternativo'];
                $tipoPregunta = $item['seccion'];
                $tipoRespuesta = $item['tipo_respuesta'];
                $codigo = $item['codigo'];
                $datoEspecial = $item['dato_especial'];
                $columnaPresentacion = $item['presentacion_col'];
                $esPreguntaRequerida = $item['es_requerido'];
                $valorMinimo = $item['valor_minimo'];
                $valorMaximo = $item['valor_maximo'];
                $controlPresentacion = $item['presentacion'];
                if ($valorMinimo == null) {
                    $valorMinimo = 0;
                }
                if ($valorMaximo == null) {
                    $valorMaximo = 0;
                }
                if ($lenguaje == LENGUAJE_INGLES) {
                    $tituloPregunta = $descripcionInglesPregunta;
                } else {
                    $tituloPregunta = $descripcionPregunta;
                }
                if ($columnaPresentacion == "" || $columnaPresentacion == "0") {
                    $columnaPresentacion = COLUMNA_TERCIO;
                }
                $cols = round(12.0 * floatval($columnaPresentacion), 0);
                $claseColumna = "col-md-" . $cols;
                $idControlPregunta = PREFIJO_CONTROL_PREGUNTA . "_" . $idPregunta;
                $step = "";
                if ($tipoRespuesta == TIPO_RESPUESTA_DECIMAL) {
                    $valorMinimo = round($valorMinimo, 2);
                    $valorMaximo = round($valorMaximo, 2);
                    $step = "0.01";
                } else if ($tipoRespuesta == TIPO_RESPUESTA_NUMERO) {
                    $valorMinimo = round($valorMinimo, 0);
                    $valorMaximo = round($valorMaximo, 0);
                    $step = "1";
                }
                $idDivPregunta = "divQuestion_$idPregunta";

                $respuestaArray = array();

                $idPreguntax = $idPregunta;
                while ($contadorArreglo < $cantidadArreglo && $idSeccionBD == $idSeccionBDx && $idPregunta == $idPreguntax) {

                    if ($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE || $tipoRespuesta == TIPO_RESPUESTA_UNICA) {

                        $idRespuesta = $item['id_respuesta'];
                        $idRespuestaUsuario = $item['id_respuesta_usuario'];
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
                        array_push($respuestaArray, array('answerID' => $idRespuesta, 'answerControlID' => $idControlRespuesta, 'description' => $descripcionRespuesta, 'userSelected' => $userSelected, 'userAnswer' => '', 'sectionShow' => $seccionMostrar, 'sectionSkip' => $seccionOmitir, 'questionShow' => $preguntaMostrar, 'questionSkip' => $preguntaOmitir));

                    } else {

                        $respuestaUsuario = $item['respuesta_usuario'];
                        $idControlRespuesta = PREFIJO_CONTROL_PREGUNTA . "_" . $idPreguntax;
                        array_push($respuestaArray, array('answerID' => 0, 'answerControlID' => $idControlRespuesta, 'description' => '', 'userSelected' => '0', 'userAnswer' => $respuestaUsuario, 'sectionShow' => '', 'sectionSkip' => '', 'questionShow' => '', 'questionSkip' => ''));

                    }

                    $contadorArreglo++;
                    $item = $arreglo[$contadorArreglo];
                    $idSeccionBD = $item['id_seccion_cuestionario'];
                    $idPregunta = $item['id_pregunta'];

                }
                if ( ($valorMaximo > 0) && ($tipoRespuesta == TIPO_RESPUESTA_NUMERO || $tipoRespuesta == TIPO_RESPUESTA_DECIMAL) ) {
                    array_push($preguntaArray, array('questionID' => $idPreguntax, 'questionControlID' => $idControlPregunta, 'questionDivID' => $idDivPregunta, 'description' => $tituloPregunta, 'alternativeDescription' => $alternativoPregunta, 'questionCode' => $codigo, 'isRequired' => $esPreguntaRequerida, 'answerType' => $tipoRespuesta, 'columnClass' => $claseColumna, 'controlPresentation' => $controlPresentacion, 'minValue' => $valorMinimo, 'maxValue' => $valorMaximo, 'step' => $step, 'answers' => $respuestaArray));
                } else if ( ($valorMinimo > 0 && $valorMaximo == 0) && ($tipoRespuesta == TIPO_RESPUESTA_NUMERO || $tipoRespuesta == TIPO_RESPUESTA_DECIMAL) ) {
                    array_push($preguntaArray, array('questionID' => $idPreguntax, 'questionControlID' => $idControlPregunta, 'questionDivID' => $idDivPregunta, 'description' => $tituloPregunta, 'alternativeDescription' => $alternativoPregunta, 'questionCode' => $codigo, 'isRequired' => $esPreguntaRequerida, 'answerType' => $tipoRespuesta, 'columnClass' => $claseColumna, 'controlPresentation' => $controlPresentacion, 'minValue' => $valorMinimo, 'step' => $step, 'answers' => $respuestaArray));
                } else {
                    array_push($preguntaArray, array('questionID' => $idPreguntax, 'questionControlID' => $idControlPregunta, 'questionDivID' => $idDivPregunta, 'description' => $tituloPregunta, 'alternativeDescription' => $alternativoPregunta, 'questionCode' => $codigo, 'isRequired' => $esPreguntaRequerida, 'answerType' => $tipoRespuesta, 'columnClass' => $claseColumna, 'controlPresentation' => $controlPresentacion, 'answers' => $respuestaArray));
                } 

            }

            array_push($arregloData, array("id" => $idSeccionBDx, "title" => $descripcionSeccion, "order" => $contadorSeccion, "image" => "", "questions" => $preguntaArray));
        }

        $output = array(
            'status' => '1'
            , 'message' => ''
            , 'data' => $arregloData);
        $respuesta = json_encode($output);
        die ($respuesta);
    } else {
        if ($lenguaje == LENGUAJE_INGLES ) {
            $mensaje = "User does not exists";
        } else {
            $mensaje = "Usuario no existe";
        }
        $output = array(
            'status' => '0'
            , 'message' => $mensaje);
        $respuesta = json_encode($output);
        die ($respuesta);        
    }
}

?>
