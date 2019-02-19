<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

/* =============================================================== */
/* CONEXIÓNES REMOTAS
================================================================ */
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

/* =============================================================== */
/* CONEXIÓN A LA BASE DE DATOS
================================================================ */
require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/functions.php');
require('../inc/constante_cuestionario.php');
require('../inc/dao_cuestionario.php');

error_reporting(E_ERROR);

/* =============================================================== */
/* REQUEST + SQL QUERYS
================================================================ */

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $questionType = $request->questionType;
    $idSeccion = $request->sectionID;
    
    $arregloData = array();

    $daoCuestionario = new DaoCuestionario();
    if ( $idSeccion == 0 || trim($idSeccion) == '' ) {
        if ( trim($questionType) != '') {
            $questionType = str_replace("'", "''", $questionType);
            $arregloSeccion = $daoCuestionario->obtenerSeccionCuestionarioPorSeccion($questionType);
            if ( count($arregloSeccion) > 0 ) {
                $idSeccion = $arregloSeccion[0]['id_seccion_cuestionario'];
            } else {
                $idSeccion = -1;
            }
        }
    }
    $arreglo = $daoCuestionario->listarPreguntaRespuestaCompleta($idSeccion, DATOESPECIAL_PRECONDICION, DATOESPECIAL_INTOLERANCIA_ALERGIA, DATOESPECIAL_TIPO_DIETA);
    $daoCuestionario = null;
    $cnx = new MySQL();
    $respuestaArray = array();
    $respuestaUsuarioArray = array();
    $preguntaArray = array();
    $idPregunta = 0;
    $idPreguntax = 0;
    $contadorArreglo = 0;
    $cantidadArreglo = count($arreglo);
    while ($contadorArreglo < $cantidadArreglo) {
        $item = $arreglo[$contadorArreglo];
        $idPregunta = $item['id_pregunta'];
        $descripcionPregunta = $item['pregunta'];
        $descripcionInglesPregunta = $item['pregunta_ing'];
        $alternativoPregunta = $item['pregunta_alternativo'];
        $tipoPregunta = $item['seccion'];
        $tipoRespuesta = $item['tipo_respuesta'];
        $codigo = $item['codigo'];
        $datoEspecial = $item['dato_especial'];
        $respuestaArray = array();
        $respuestaUsuarioArray = array();
        $preguntaArray = array();

        if ($datoEspecial == DATOESPECIAL_PRECONDICION) {
            $query3 = "SELECT a.id_usuario_pregunta_respuesta, a.id_respuesta, b.nombre as respuesta, b.nombre_ing AS respuesta_ing FROM usuario_pregunta_respuesta a INNER JOIN enfermedad b ON a.id_respuesta = b.id_enfermedad WHERE a.estado = 1 AND a.id_pregunta = $idPregunta AND a.id_usuario = $user";
            $sql3 = $cnx->query($query3);
            $sql3->read();
            while($sql3->next()) {
                $idRespuestaUsuario = $sql3->field('id_usuario_pregunta_respuesta');
                $idRespuesta = $sql3->field('id_respuesta');
                $respuestaUsuario = $sql3->field('respuesta');
                $respuestaComplemento = $sql3->field('respuesta_ing');
                array_push($respuestaUsuarioArray, array('id_user_answer' => $idRespuestaUsuario, 'id_answer' => $idRespuesta, 'user_answer' => $respuestaUsuario, 'user_answer_complement' => $respuestaComplemento));
            }
        } else if ($datoEspecial == DATOESPECIAL_INTOLERANCIA_ALERGIA) {
            $query3 = "SELECT a.id_usuario_pregunta_respuesta, a.id_respuesta, b.nombre as respuesta FROM usuario_pregunta_respuesta a INNER JOIN intolerancia b ON a.id_respuesta = b.id_intolerancia WHERE a.estado = 1 AND a.id_pregunta = $idPregunta AND a.id_usuario = $user";
            $sql3 = $cnx->query($query3);
            $sql3->read();
            while($sql3->next()) {
                $idRespuestaUsuario = $sql3->field('id_usuario_pregunta_respuesta');
                $idRespuesta = $sql3->field('id_respuesta');
                $respuestaUsuario = $sql3->field('respuesta');
                $respuestaComplemento = $sql3->field('respuesta');
                array_push($respuestaUsuarioArray, array('id_user_answer' => $idRespuestaUsuario, 'id_answer' => $idRespuesta, 'user_answer' => $respuestaUsuario, 'user_answer_complement' => $respuestaComplemento));
            }
        } else if ($datoEspecial == DATOESPECIAL_TIPO_DIETA) {
            $query3 = "SELECT a.id_usuario_pregunta_respuesta, a.id_respuesta, b.nombre as respuesta, b.nombre_ing AS respuesta_ing FROM usuario_pregunta_respuesta a INNER JOIN tipo_dieta b ON a.id_respuesta = b.id_tipo_dieta WHERE a.estado = 1 AND a.id_pregunta = $idPregunta AND a.id_usuario = $user";
            $sql3 = $cnx->query($query3);
            $sql3->read();
            while($sql3->next()) {
                $idRespuestaUsuario = $sql3->field('id_usuario_pregunta_respuesta');
                $idRespuesta = $sql3->field('id_respuesta');
                $respuestaUsuario = $sql3->field('respuesta');
                $respuestaComplemento = $sql3->field('respuesta_ing');
                array_push($respuestaUsuarioArray, array('id_user_answer' => $idRespuestaUsuario, 'id_answer' => $idRespuesta, 'user_answer' => $respuestaUsuario, 'user_answer_complement' => $respuestaComplemento));
            }
        } else {
            $query3 = "SELECT a.id_usuario_pregunta_respuesta, a.id_respuesta, a.respuesta, b.descripcion AS respuesta_opcion, b.descripcion_ing AS respuesta_ing FROM usuario_pregunta_respuesta a LEFT OUTER JOIN respuesta b ON a.id_pregunta = b.id_pregunta AND a.id_respuesta = b.id_respuesta WHERE a.estado = 1 AND a.id_pregunta = $idPregunta AND a.id_usuario = $user";
            $sql3 = $cnx->query($query3);
            $sql3->read();
            while($sql3->next()) {
                $muestra = true;
                $idRespuestaUsuario = $sql3->field('id_usuario_pregunta_respuesta');
                $idRespuesta = $sql3->field('id_respuesta');
                $respuestaDeOpcion = $sql3->field('respuesta_opcion');
                $respuestaUsuario = $sql3->field('respuesta');
                $respuestaComplemento = $sql3->field('respuesta_ing');
                if ($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE || $tipoRespuesta == TIPO_RESPUESTA_UNICA) {
                    $respuestaUsuario = $respuestaDeOpcion;
                    if ($idRespuestaUsuario == null) {
                        $muestra = false;
                    }
                }
                if ($muestra) {
                    array_push($respuestaUsuarioArray, array('id_user_answer' => $idRespuestaUsuario, 'id_answer' => $idRespuesta, 'user_answer' => $respuestaUsuario, 'user_answer_complement' => $respuestaComplemento));
                }
            }
        }
        /*
        if (count($respuestaUsuarioArray)==0) {
            array_push($respuestaUsuarioArray, array('id_user_answer' => -1, 'id_answer' => -1, 'user_answer' => null, 'user_answer_complement' => null));
        }
*/
        $idPreguntax = $idPregunta;
        while ($contadorArreglo < $cantidadArreglo && $idPregunta == $idPreguntax) {
            if ($tipoRespuesta == TIPO_RESPUESTA_MULTIPLE || $tipoRespuesta == TIPO_RESPUESTA_UNICA) {
                $seccionMostrarArray = array();
                $preguntaOmitirArray = array();
                $preguntaMostrarArray = array();
                $idRespuesta = $item['id_respuesta'];
                //sección mostrar
                $query3 = "SELECT DISTINCT b.id_seccion_cuestionario, b.descripcion AS seccion FROM seccion_cuestionario_enlazada a INNER JOIN seccion_cuestionario b ON a.id_seccion_cuestionario_afectada = b.id_seccion_cuestionario WHERE a.id_pregunta_afecta = $idPregunta AND a.id_respuesta_afecta = $idRespuesta AND a.mostrar = 1 AND a.estado = 1";
                $sql3 = $cnx->query($query3);
                $sql3->read();
                while($sql3->next()) {
                    $idSeccion = $sql3->field('id_seccion_cuestionario');
                    $seccion = $sql3->field('seccion');
                    array_push($seccionMostrarArray, array('sectionID' => $idSeccion, 'section' => $seccion));
                }
                //pregunta mostrar
                $query3 = "SELECT DISTINCT b.id_pregunta, b.descripcion AS pregunta FROM pregunta_enlazada a INNER JOIN pregunta b ON a.id_pregunta_afectada = b.id_pregunta WHERE a.id_pregunta_afecta = $idPregunta AND a.id_respuesta_afecta = $idRespuesta AND a.mostrar = 1 AND a.estado = 1";
//                echo $query3."<br/>";
                $sql3 = $cnx->query($query3);
                $sql3->read();
                while($sql3->next()) {
                    $idPregunta_ = $sql3->field('id_pregunta');
                    $pregunta_ = $sql3->field('pregunta');
                    array_push($preguntaMostrarArray, array('questionID' => $idPregunta_, 'question' => $pregunta_));
                }
                //pregunta omitir
                $query3 = "SELECT DISTINCT b.id_pregunta, b.descripcion AS pregunta FROM pregunta_enlazada a INNER JOIN pregunta b ON a.id_pregunta_afectada = b.id_pregunta WHERE a.id_pregunta_afecta = $idPregunta AND a.id_respuesta_afecta = $idRespuesta AND a.omitir = 1 AND a.estado = 1";
                $sql3 = $cnx->query($query3);
                $sql3->read();
                while($sql3->next()) {
                    $idPregunta_ = $sql3->field('id_pregunta');
                    $pregunta_ = $sql3->field('pregunta');
                    array_push($preguntaOmitirArray, array('questionID' => $idPregunta_, 'question' => $pregunta_));
                }
                array_push($respuestaArray, array('id_answer' => $idRespuesta, 'description' => $item['respuesta'], 'description_english' => $item['respuesta_ing'], 'sectionShow' => $seccionMostrarArray, 'questionShow' => $preguntaMostrarArray, 'questionSkip' => $preguntaOmitirArray));
            }
            $contadorArreglo++;
            $item = $arreglo[$contadorArreglo];
            $idPregunta = $item['id_pregunta'];
        }

        $preguntaArray = array('id_question' => $idPreguntax, 'question_description' => $descripcionPregunta, 'question_description_english' => $descripcionInglesPregunta, 'question_description_alternative' => $alternativoPregunta, 'question_type' => $tipoPregunta, 'answer_type' => $tipoRespuesta, 'question_code' => $codigo, 'answers' => $respuestaArray, 'user_answer' => $respuestaUsuarioArray);
        array_push($arregloData, $preguntaArray);
    }

    $cnx->close();
    $cnx= null;

    $output = array(
        'status' => '1'
        , 'message' => ''
        , 'data' => $arregloData);
    $respuesta = json_encode($output);
    die ($respuesta);
}


/*



        //respuesta usuario
        if ($codigo == CODIGO_PREGUNTA_PAIS) {
            $query3 = "SELECT a.id_usuario_pregunta_respuesta, a.id_respuesta, a.respuesta, b.nombre as pais FROM usuario_pregunta_respuesta a LEFT OUTER JOIN pais b ON a.respuesta = b.cod_pais WHERE a.estado = 1 AND a.id_pregunta = " . $idPregunta . " AND a.id_usuario = " . $user;
            $sql3 = $cnx->query($query3);
            $sql3->read();
            if ($sql3->next()) {
                $idRespuestaUsuario = $sql3->field('id_usuario_pregunta_respuesta');
                $idRespuesta = $sql3->field('id_respuesta');
                $respuestaUsuario = $sql3->field('respuesta');
                $respuestaComplemento = $sql3->field('pais');
                array_push($respuestaUsuarioArray, array('id_user_answer' => $idRespuestaUsuario, 'id_answer' => $idRespuesta, 'user_answer' => $respuestaUsuario, 'user_answer_complement' => $respuestaComplemento));
            }
        } else if ($codigo == CODIGO_PRECONDICION_SALUD) {
            $query3 = "SELECT a.id_usuario_pregunta_respuesta, a.id_respuesta, b.nombre as respuesta FROM usuario_pregunta_respuesta a INNER JOIN enfermedad b ON a.id_respuesta = b.id_enfermedad WHERE a.estado = 1 AND a.id_pregunta = $idPregunta AND a.id_usuario = $user";
            $sql3 = $cnx->query($query3);
            $sql3->read();
            while($sql3->next()) {
                $idRespuestaUsuario = $sql3->field('id_usuario_pregunta_respuesta');
                $idRespuesta = $sql3->field('id_respuesta');
                $respuestaUsuario = $sql3->field('respuesta');
                $respuestaComplemento = $sql3->field('respuesta');
                array_push($respuestaUsuarioArray, array('id_user_answer' => $idRespuestaUsuario, 'id_answer' => $idRespuesta, 'user_answer' => $respuestaUsuario, 'user_answer_complement' => $respuestaComplemento));
            }
        } else if ($codigo == CODIGO_INTOLERANCIA_ALERGIA) {
            $query3 = "SELECT a.id_usuario_pregunta_respuesta, a.id_respuesta, b.nombre as respuesta FROM usuario_pregunta_respuesta a INNER JOIN intolerancia b ON a.id_respuesta = b.id_intolerancia WHERE a.estado = 1 AND a.id_pregunta = $idPregunta AND a.id_usuario = $user";
            $sql3 = $cnx->query($query3);
            $sql3->read();
            while($sql3->next()) {
                $idRespuestaUsuario = $sql3->field('id_usuario_pregunta_respuesta');
                $idRespuesta = $sql3->field('id_respuesta');
                $respuestaUsuario = $sql3->field('respuesta');
                $respuestaComplemento = $sql3->field('respuesta');
                array_push($respuestaUsuarioArray, array('id_user_answer' => $idRespuestaUsuario, 'id_answer' => $idRespuesta, 'user_answer' => $respuestaUsuario, 'user_answer_complement' => $respuestaComplemento));
            }
        } else {
            $query3 = "SELECT a.id_usuario_pregunta_respuesta, a.id_respuesta, a.respuesta, b.descripcion_ing as respuesta_ing FROM usuario_pregunta_respuesta a LEFT OUTER JOIN respuesta b ON a.id_pregunta = b.id_pregunta AND a.id_respuesta = b.id_respuesta WHERE a.estado = 1 AND a.id_pregunta = " . $idPregunta . " AND a.id_usuario = " . $user;
            $sql3 = $cnx->query($query3);
            $sql3->read();
            while($sql3->next()) {
                $idRespuestaUsuario = $sql3->field('id_usuario_pregunta_respuesta');
                $idRespuesta = $sql3->field('id_respuesta');
                $respuestaUsuario = $sql3->field('respuesta');
                $respuestaComplemento = $sql3->field('respuesta_ing');
                array_push($respuestaUsuarioArray, array('id_user_answer' => $idRespuestaUsuario, 'id_answer' => $idRespuesta, 'user_answer' => $respuestaUsuario, 'user_answer_complement' => $respuestaComplemento));
            }
        }
*/

?>
