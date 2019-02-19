<?php
class DaoPerfilSalud {

    function __construct() { }

/*
    function listarIntoleranciaPorInsumo($idInsumo) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_INTOLERANCIA($idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_intolerancia' => $sql->field('id_intolerancia'), 'intolerancia' => $sql->field('intolerancia'), 'id_insumo_intolerancia' => $sql->field('id_insumo_intolerancia')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function inactivarIntoleranciaPorInsumo($idInsumo, $usuario) {
        $resultado = true;
        $execute = "CALL USP_ELIM_INSUMO_INTOLERANCIA($idInsumo, '$usuario')";
        $cnx = new MySQL();
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

    function grabarIntoleranciaInsumo($idInsumo, $idIntolerancia, $usuario) {
        $resultado = true;
        $execute = "CALL USP_UPD_INSUMO_INTOLERANCIA($idInsumo, $idIntolerancia, '$usuario')";
        $cnx = new MySQL();
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
*/
}

?>