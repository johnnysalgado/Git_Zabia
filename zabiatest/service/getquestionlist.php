<?php

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
require('../inc/dao_enfermedad.php');
require('../inc/dao_intolerancia.php');
require('../inc/dao_trastorno_estomacal.php');
require('../inc/dao_tipo.php');
require('../inc/dao_usuario.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $user = $request->user;
    $lenguaje = $request->language;


    //tipo género
    $daoUsuario = new DaoUsuario();
    $arregloUsuario = $daoUsuario->listarTipoGenero(LISTA_ACTIVO, $lenguaje);
    $cantidadTipoGenero = count($arregloUsuario);
    $daoUsuario = null;

    //intolerancias
    $daoIntolerancia = new DaoIntolerancia();
    $arregloIntolerancia = $daoIntolerancia->listarIntolerancia(LISTA_ACTIVO, $lenguaje);
    $cantidadIntolerancia = count($arregloIntolerancia);
    $daoIntolerancia = null;

    //Precondiciones
    $daoPrecondicion = new DaoEnfermedad();
    $arregloPrecondicion = $daoPrecondicion->listarEnfermedad(LISTA_ACTIVO, $lenguaje);
    $cantidadPrecondicion = count($arregloPrecondicion);
    $daoPrecondicion = null;

    //Trastorno estomacal
    $daoTrastornoEstomacal = new DaoTrastornoEstomacal();
    $arregloTrastornoEstomacal = $daoTrastornoEstomacal->listarTrastornoEstomacal(LISTA_ACTIVO, $lenguaje);
    $cantidadTrastornoEstomacal = count($arregloTrastornoEstomacal);
    $daoTrastornoEstomacal = null;

    //Tipo actividad
    $daoTipo = new DaoTipo();
    $arregloTipoActividad = $daoTipo->listarTipoActividad(LISTA_ACTIVO, $lenguaje);
    $cantidadTipoActividad = count($arregloTipoActividad);
    $daoTipo = null;

    $daoCuestionario = new DaoCuestionario();
    //tipo dieta
    $arregloTipoDieta = $daoCuestionario->listarTipoDieta();
    $cantidadTipoDieta = count($arregloTipoDieta);

    $arregloData = array();
    $contadorSeccion = 0;
    $ordenSeccionx = 0;
    $idPreguntax = 0;
    $contadorArreglo = 0;
    $arregloCuestionario = $daoCuestionario->listarPreguntaSubPregunta($user);
    $cantidadArreglo = count($arregloCuestionario);
    while ($contadorArreglo < $cantidadArreglo) {
        $item = $arregloCuestionario[$contadorArreglo];
        $idPregunta = $item['id_pregunta'];
        if ($lenguaje == LENGUAJE_INGLES) {
            $descripcion = $item['descripcion_ing'];
        } else {
            $descripcion = $item['descripcion'];
        }
        $tipoRespuesta = $item['tipo_respuesta'];
        $idSeccion = $item['id_seccion_cuestionario'];
        $cantidadRespuesta = $item['cantidad_respuesta'];
        $datoEspecial = $item['dato_especial'];
        if ($datoEspecial == DATOESPECIAL_PRECONDICION) {
            $cantidadRespuesta = $cantidadPrecondicion;
        } else if ($datoEspecial == DATOESPECIAL_INTOLERANCIA_ALERGIA) {
            $cantidadRespuesta = $cantidadIntolerancia;
        } else if ($datoEspecial == DATOESPECIAL_TRASTORNO_ESTOMACAL) {
            $cantidadRespuesta = $cantidadTrastornoEstomacal;
        } else if ($datoEspecial == DATOESPECIAL_TIPO_DIETA) {
            $cantidadRespuesta = $cantidadTipoDieta;
        } else if ($datoEspecial == DATOESPECIAL_TIPO_ACTIVIDAD) {
            $cantidadRespuesta = $cantidadTipoActividad;
        } else if ($datoEspecial == DATOESPECIAL_TIPO_GENERO) {
            $cantidadRespuesta = $cantidadTipoGenero;
        }
        $ordenSeccion = $item["orden_seccion"];
        if ($ordenSeccion != $ordenSeccionx) {
            $contadorSeccion++;
        }
        $ordenSeccionx = $ordenSeccion;
        $idPreguntax = $idPregunta;
        $arregloSubpregunta = array();
        $contadorSubpregunta = 0;
        while ($contadorArreglo < $cantidadArreglo && $idPregunta == $idPreguntax) {
            $idSubpregunta = $item["id_subpregunta"];
            if ($idSubpregunta != null) {
                if ($lenguaje == LENGUAJE_INGLES) {
                    $descripcionSubpregunta = $item['subpregunta_ing'];
                } else {
                    $descripcionSubpregunta = $item['subpregunta'];
                }
                $tipoRespuestaSubpregunta = $item['subpregunta_tipo_respuesta'];
                $contadorSubpregunta++;
                array_push($arregloSubpregunta, array('subQuestionID' => $idSubpregunta, 'description' => $descripcionSubpregunta, 'order' => $contadorSubpregunta, 'answerType' => $tipoRespuestaSubpregunta));
            }
            $contadorArreglo++;
            $item = $arregloCuestionario[$contadorArreglo];
            $idPregunta = $item['id_pregunta'];
        }
        array_push($arregloData, array('questionID' => $idPreguntax, 'description' => $descripcion, 'sectionID' => $idSeccion, 'sectionOrder' => "$contadorSeccion", 'answerType' => $tipoRespuesta, 'answerAmount' => $cantidadRespuesta, 'subQuestion' => $arregloSubpregunta));
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
