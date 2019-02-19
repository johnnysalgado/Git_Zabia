<?php
class DaoPais {

    function __construct() { }

    function listarPais($estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_PAIS($estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('cod_pais' => $sql->field('cod_pais'), 'nombre' => $sql->field('nombre'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modificacion'), 'fecha_modifica' => $sql->field('fecha_modificacion')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarRegion($codigoPais, $estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_REGION('$codigoPais', $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_region' => $sql->field('id_region'), 'cod_pais' => $sql->field('cod_pais'), 'nombre' => $sql->field('nombre'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modificacion'), 'fecha_modifica' => $sql->field('fecha_modificacion')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    /*
    function crearUsuario($email, $password, $nombre, $apellido, $codigoPais, $idRegion, $telefono, $origen, $foto, $salt, $idTipoEmpleo, $idTipoIngresoEconomico, $usuario) {
        $idUsuario = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_USUARIO ('$email', '$password', '$nombre', '$apellido', ";
        if ($codigoPais == "") {
            $exceute .= "NULL";
        } else {
            $execute .= "'$codigoPais'";
        }
        $execute .= ", ";
        if ($idRegion == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idRegion";
        }
        $execute .= ", '$telefono', '$origen', '$foto', '$salt', ";
        if ($idTipoEmpleo == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idTipoEmpleo";
        }
        $execute .= ", ";
        if ($idTipoIngresoEconomico == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idTipoIngresoEconomico";
        }
        $execute .= ", '$usuario', @p_id_usuario);";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_usuario AS id_usuario");
            $sql->read();
            if ($sql->next()) {
                $idUsuario = $sql->field('id_usuario');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idUsuario;
    }

    function editarUsuario($idUsuario, $nombre, $apellido, $codigoPais, $idRegion, $telefono, $foto, $idTipoEmpleo, $idTipoIngresoEconomico, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_USUARIO ($idUsuario, '$nombre', '$apellido', ";
        if ($codigoPais == "") {
            $exceute .= "NULL";
        } else {
            $execute .= "'$codigoPais'";
        }
        $execute .= ", ";
        if ($idRegion == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idRegion";
        }
        $execute .= ", '$telefono', '$foto', ";
        if ($idTipoEmpleo == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idTipoEmpleo";
        }
        $execute .= ", ";
        if ($idTipoIngresoEconomico == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idTipoIngresoEconomico";
        }
        $execute .= ", '$usuario');";
        try {
            echo $execute;
            $cnx->execute($execute);
        } catch(Exception $e) {
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