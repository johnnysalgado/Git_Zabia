<?php
require('inc/configuracion.php');
require('inc/mysql.php');
require('inc/functions.php');
require('inc/constante.php');
require('inc/constante_usuario.php');
require('inc/dao_usuario.php');


$daoUsuario = new DaoUsuario();
$arregloUsuario = $daoUsuario->listarUsuario(0, -1);
$daoUsuario = null;

$cnx = new MySQL();
foreach($arregloUsuario as $item) {
    $idUsuario = $item['id_usuario'];
    $oldPassword = $item['password_old'];
    $origen = $item['origen'];
    if ($origen == TIPO_LOGIN_PROPIO) {
        $salt = guidv4();
        $hash = securePassword($oldPassword, $salt, true);
        $update = "UPDATE usuario SET password='$hash', salt='$salt' WHERE id_usuario = $idUsuario";
        $cnx->execute($update);
    }
}
$cnx->close();
$cnx = null;


?>