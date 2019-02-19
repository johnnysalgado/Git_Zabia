<?php
    require('../inc/sesion.php');
    require('../inc/configuracion.php');
    require('../inc/mysql.php');

    $arreglo = array();
    
    array_push($arreglo, array('mensaje' => 'llega'));
    if ($_POST) {
        array_push($arreglo, array('mensaje' => 'entra a post'));
        $conn = new MySQL();
        saveList($conn, $_POST['list'], $arreglo, $_SESSION["U"]);
        $conn->close();
        $conn = null;
    }

    $output = array(
        'status' => '1'
        , 'arreglo' => $arreglo);
    $respuesta = json_encode($output);
    die ($respuesta);

    function saveList($conn, $list, $arreglo, $usuario) {
        $m_order = 0;
        array_push($arreglo, array('mensaje' => 'entra a savelist'));
        foreach($list as $item) {
            $m_order ++;
            array_push($arreglo, array('mensaje' => 'orden: ' . $m_order));
            $id = $item["id"];
            $execute = "CALL USP_EDIT_QUESTION_OPTION_ORDEN ($id, $m_order, '$usuario')";
            array_push($arreglo, array('mensaje' => 'query: ' . $execute));
            $conn->execute($execute);
        }
    }

?>