<?php
class DaoComercio {

    function __construct() { }

    function listarTipoComercioExisteEnBD() {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_COMERCIO_EXISTE()";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('tipo_comercio' => $sql->field('tipo_comercio')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
    
}

?>