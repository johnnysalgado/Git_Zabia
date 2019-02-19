<?php
require('../inc/configuracion.php');
require('../inc/mysql.php');

if ($_POST) {
    $conn = new MySQL();
    saveList($conn, $_POST['list']);
    $conn = null;
    $output = array(
        'status' => '1'
        , 'mensaje' => 'Grabado correctamente');
    $respuesta = json_encode($output);
    die ($respuesta);
}

function saveList($conn, $list, $parent_id = 0, &$m_order = 0) {
    foreach($list as $item) {
        $m_order++;
        $sql = 'UPDATE flujochat SET id_padre = ' . $parent_id . ', orden = ' . $m_order . ' WHERE id_flujochat = ' . $item["id"];
        $conn->execute($sql);
        if (array_key_exists("children", $item)) {
            saveList($conn, $item["children"], $item["id"], $m_order);
        }
    }
}

?>