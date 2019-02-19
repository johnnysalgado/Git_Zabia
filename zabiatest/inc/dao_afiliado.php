<?php
class DaoAfiliado {

    function __construct() { }

    function listarAfiliado($estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_AFFILIATES ($estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_affiliates' => $sql->field('id_affiliates'), 'id_questionnaire_set' => $sql->field('id_questionnaire_set'), 'name' => $sql->field('name'), 'address1' => $sql->field('address1'), 'address2' => $sql->field('address2'), 'contact_name' => $sql->field('contact_name'), 'city' => $sql->field('city'), 'state' => $sql->field('state'), 'zipcode' => $sql->field('zipcode'), 'phone' => $sql->field('phone'), 'fax' => $sql->field('fax'), 'url' => $sql->field('url'), 'system_url' => $sql->field('system_url'), 'facebook_url' => $sql->field('facebook_url'), 'email' => $sql->field('email'), 'logo_1' => $sql->field('logo_1'), 'logo_2' => $sql->field('logo_2'), 'theme' => $sql->field('theme'), 'status' => $sql->field('status'), 'created_by' => $sql->field('created_by'), 'created' => $sql->field('created'), 'updated_by' => $sql->field('updated_by'), 'updated' => $sql->field('updated')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerAfiliado($idAfiliado) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_AFFILIATES ($idAfiliado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_affiliates' => $sql->field('id_affiliates'), 'id_questionnaire_set' => $sql->field('id_questionnaire_set'), 'name' => $sql->field('name'), 'cod_affiliates' => $sql->field('cod_affiliates'), 'address1' => $sql->field('address1'), 'address2' => $sql->field('address2'), 'contact_name' => $sql->field('contact_name'), 'city' => $sql->field('city'), 'state' => $sql->field('state'), 'zipcode' => $sql->field('zipcode'), 'phone' => $sql->field('phone'), 'fax' => $sql->field('fax'), 'url' => $sql->field('url'), 'system_url' => $sql->field('system_url'), 'facebook_url' => $sql->field('facebook_url'), 'email' => $sql->field('email'), 'logo_1' => $sql->field('logo_1'), 'logo_2' => $sql->field('logo_2'), 'theme' => $sql->field('theme'), 'status' => $sql->field('status'), 'created_by' => $sql->field('created_by'), 'created' => $sql->field('created'), 'updated_by' => $sql->field('updated_by'), 'updated' => $sql->field('updated')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

}
?>