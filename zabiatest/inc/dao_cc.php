<?php
class DaoCC {

    function __construct() { }

    function listarToken($where) {
        $arreglo = array();
        $query = "CALL USP_LIST_CC_TOKEN('$where')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_cc_token' => $sql->field('id_cc_token'), 'id_usuario' => $sql->field('id_usuario'), 'estado' => $sql->field('estado'), 'token' => $sql->field('token'), 'last4' => $sql->field('last4'), 'lastused' => $sql->field('lastused'), 'nameoncard' => $sql->field('nameoncard'), 'expiration' => $sql->field('expiration'), 'cardtype' => $sql->field('cardtype'), 'isdefault' => $sql->field('isdefault'), 'islastused' => $sql->field('islastused'), 'created_by' => $sql->field('created_by'), 'created' => $sql->field('created'), 'updated_by' => $sql->field('updated_by'), 'updated' => $sql->field('updated')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerToken($idCCToken) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_CC_TOKEN($idCCToken)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_cc_token' => $sql->field('id_cc_token'), 'id_usuario' => $sql->field('id_usuario'), 'estado' => $sql->field('estado'), 'token' => $sql->field('token'), 'last4' => $sql->field('last4'), 'lastused' => $sql->field('lastused'), 'nameoncard' => $sql->field('nameoncard'), 'expiration' => $sql->field('expiration'), 'cardtype' => $sql->field('cardtype'), 'isdefault' => $sql->field('isdefault'), 'islastused' => $sql->field('islastused'), 'created_by' => $sql->field('created_by'), 'created' => $sql->field('created'), 'updated_by' => $sql->field('updated_by'), 'updated' => $sql->field('updated')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearCCToken($idUsuario, $token, $last4, $nameoncard, $expiration, $cardtype, $isDefault, $usuario) {
        $idToken = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_CC_TOKEN ($idUsuario, '$token', '$last4', '$nameoncard', '$expiration', '$cardtype', $isDefault, '$usuario', @p_id_cctoken);";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_cctoken AS id_cctoken");
            $sql->read();
            if ($sql->next()) {
                $idToken = $sql->field('id_cctoken');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idToken;
    }

    function editarCCToken($idUsuario, $token, $isDefault, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_CC_TOKEN ($idUsuario, '$token', $isDefault, '$usuario');";
        try {
            $cnx->execute($execute);
        } catch(Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }

    function eliminarCCToken($idUsuario, $token, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_CC_TOKEN ($idUsuario, '$token', '$usuario');";
        try {
            $cnx->execute($execute);
        } catch(Exception $e) {
            $resultado = false;
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $resultado;
    }
}

?>