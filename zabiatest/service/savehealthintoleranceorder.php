<?php
    require('../inc/sesion.php');
    require('../inc/configuracion.php');
    require('../inc/mysql.php');
    require('../inc/dao_cuestionario.php');

    $arreglo = array();
    array_push($arreglo, array('mensaje' => 'llega'));
    if ($_POST) {
        array_push($arreglo, array('mensaje' => 'entra a post'));
        saveList($_POST['list'], $arreglo, $_SESSION["U"]);
    }

    $output = array(
        'status' => '1'
        , 'arreglo' => $arreglo);
    $respuesta = json_encode($output);
    die ($respuesta);

    function saveList($list, $arreglo, $usuario) {
        $m_order = 0;
        array_push($arreglo, array('mensaje' => 'entra a savelist'));
        $daoCuestionario = new DaoCuestionario();
        foreach($list as $item) {
            $m_order ++;
            array_push($arreglo, array('mensaje' => 'orden: ' . $m_order));
            $id = $item["id"];
            $daoCuestionario->grabarOrdenPreguntaIntolerancia($id, $m_order, $usuario);
            array_push($arreglo, array('mensaje' => 'pasó la grabación'));
        }
        $daoCuestionario = null;
    }

?>