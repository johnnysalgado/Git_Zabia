<?php
class DaoUpload {

    function __construct() { }

    function crearUpload($nombreArchivo, $tipo, $total, $correctos, $usuario) {
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_UPLOAD ('$nombreArchivo', '$tipo', $total, $correctos, '$usuario');";
        $cnx->execute($execute);
        $cnx->close();
        $cnx = null;
        return true;
    }

    function listarUpload($tipo) {
        $arreglo = array();
        $query = "CALL USP_LIST_UPLOAD('$tipo')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while($sql->next()) {
            array_push($arreglo, array('nombre_archivo' => $sql->field('nombre_archivo'), 'tipo' => $sql->field('tipo'), 'total' => $sql->field('total'), 'correcto' => $sql->field('correcto'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_registro' => $sql->field('usuario_registro')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

}

?>