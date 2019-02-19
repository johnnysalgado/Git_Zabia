<?php
class DaoUsuario {

    function __construct() { }

    function obtenerUsuario($idUsuario) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_USUARIO($idUsuario)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_usuario' => $sql->field('id_usuario'), 'id_affiliates' => $sql->field('id_affiliates'), 'affiliates_name' => $sql->field('affiliates_name'), 'email' => $sql->field('email'), 'password' => $sql->field('password'), 'nombre' => $sql->field('nombre'), 'apellido' => $sql->field('apellido'), 'cod_pais' => $sql->field('cod_pais'), 'pais' => $sql->field('pais'), 'id_region' => $sql->field('id_region'), 'region' => $sql->field('region'), 'telefono' => $sql->field('telefono'), 'origen' => $sql->field('origen'), 'foto' => $sql->field('foto'), 'salt' => $sql->field('salt'), 'id_tipo_empleo' => $sql->field('id_tipo_empleo'), 'tipo_empleo' => $sql->field('tipo_empleo'), 'tipo_empleo_ing' => $sql->field('tipo_empleo_ing'), 'id_tipo_ingreso_economico' => $sql->field('id_tipo_ingreso_economico'), 'tipo_ingreso_economico' => $sql->field('tipo_ingreso_economico'), 'id_tipo_educacion' => $sql->field('id_tipo_educacion'), 'tipo_educacion' => $sql->field('tipo_educacion'), 'tipo_educacion_ing' => $sql->field('tipo_educacion_ing'), 'id_tipo_genero' => $sql->field('id_tipo_genero'), 'tipo_genero' => $sql->field('tipo_genero'), 'tipo_genero_ing' => $sql->field('tipo_genero_ing'), 'primer_log' => $sql->field('primer_log'), 'consentimiento' => $sql->field('consentimiento'), 'cuestionario' => $sql->field('cuestionario'), 'reporte' => $sql->field('reporte'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modificacion'), 'fecha_modifica' => $sql->field('fecha_modificacion')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerUsuarioPorCorreo($email) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_USUARIO_X_CORREO('$email')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_usuario' => $sql->field('id_usuario'), 'id_affiliates' => $sql->field('id_affiliates'), 'affiliates_name' => $sql->field('affiliates_name'), 'email' => $sql->field('email'), 'password' => $sql->field('password'), 'nombre' => $sql->field('nombre'), 'apellido' => $sql->field('apellido'), 'cod_pais' => $sql->field('cod_pais'), 'pais' => $sql->field('pais'), 'id_region' => $sql->field('id_region'), 'region' => $sql->field('region'), 'telefono' => $sql->field('telefono'), 'origen' => $sql->field('origen'), 'foto' => $sql->field('foto'), 'salt' => $sql->field('salt'), 'id_tipo_empleo' => $sql->field('id_tipo_empleo'), 'tipo_empleo' => $sql->field('tipo_empleo'), 'tipo_empleo_ing' => $sql->field('tipo_empleo_ing'), 'id_tipo_ingreso_economico' => $sql->field('id_tipo_ingreso_economico'), 'tipo_ingreso_economico' => $sql->field('tipo_ingreso_economico'), 'id_tipo_educacion' => $sql->field('id_tipo_educacion'), 'tipo_educacion' => $sql->field('tipo_educacion'), 'tipo_educacion_ing' => $sql->field('tipo_educacion_ing'), 'id_tipo_genero' => $sql->field('id_tipo_genero'), 'tipo_genero' => $sql->field('tipo_genero'), 'tipo_genero_ing' => $sql->field('tipo_genero_ing'), 'primer_log' => $sql->field('primer_log'), 'consentimiento' => $sql->field('consentimiento'), 'cuestionario' => $sql->field('cuestionario'), 'reporte' => $sql->field('reporte'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modificacion'), 'fecha_modifica' => $sql->field('fecha_modificacion')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
    
    function listarUsuario($idAfiliado = 0, $estado = -1) {
        $arreglo = array();
        $query = "CALL USP_LIST_USUARIO($idAfiliado, $estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_usuario' => $sql->field('id_usuario'), 'id_affiliates' => $sql->field('id_affiliates'), 'affiliates_name' => $sql->field('affiliates_name'), 'email' => $sql->field('email'), 'password' => $sql->field('password'), 'nombre' => $sql->field('nombre'), 'apellido' => $sql->field('apellido'), 'cod_pais' => $sql->field('cod_pais'), 'pais' => $sql->field('pais'), 'id_region' => $sql->field('id_region'), 'region' => $sql->field('region'), 'telefono' => $sql->field('telefono'), 'origen' => $sql->field('origen'), 'foto' => $sql->field('foto'), 'salt' => $sql->field('salt'), 'id_tipo_empleo' => $sql->field('id_tipo_empleo'), 'tipo_empleo' => $sql->field('tipo_empleo'), 'tipo_empleo_ing' => $sql->field('tipo_empleo_ing'), 'id_tipo_ingreso_economico' => $sql->field('id_tipo_ingreso_economico'), 'tipo_ingreso_economico' => $sql->field('tipo_ingreso_economico'), 'id_tipo_educacion' => $sql->field('id_tipo_educacion'), 'tipo_educacion' => $sql->field('tipo_educacion'), 'tipo_educacion_ing' => $sql->field('tipo_educacion_ing'), 'id_tipo_genero' => $sql->field('id_tipo_genero'), 'tipo_genero' => $sql->field('tipo_genero'), 'tipo_genero_ing' => $sql->field('tipo_genero_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modificacion'), 'fecha_modifica' => $sql->field('fecha_modificacion')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
    
    function crearUsuario($email, $password, $nombre, $apellido, $codigoPais, $idRegion, $telefono, $origen, $foto, $salt, $idTipoEmpleo, $idTipoIngresoEconomico, $idTipoEducacion, $idTipoGenero, $codigoAfiliado, $usuario) {
        $idUsuario = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_USUARIO ('$email', '$password', '$nombre', '$apellido', ";
        if ($codigoPais == "") {
            $execute .= "NULL";
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
        $execute .= ", ";
        if ($idTipoEducacion == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idTipoEducacion";
        }
        $execute .= ", ";
        if ($idTipoGenero == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idTipoGenero";
        }
        $execute .= ", '$codigoAfiliado', '$usuario', @p_id_usuario);";
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

    function editarUsuario($idUsuario, $nombre, $apellido, $codigoPais, $idRegion, $telefono, $foto, $idTipoEmpleo, $idTipoIngresoEconomico, $idTipoEducacion, $idTipoGenero, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_USUARIO ($idUsuario, '$nombre', '$apellido', ";
        if ($codigoPais == "") {
            $execute .= "NULL";
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
        $execute .= ", ";
        if ($idTipoEducacion == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idTipoEducacion";
        }
        $execute .= ", ";
        if ($idTipoGenero == 0) {
            $execute .= "NULL";
        } else {
            $execute .= "$idTipoGenero";
        }
        $execute .= ", '$usuario');";
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

    function cambiarContrasena($idUsuario, $contrasena, $salt, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_USUARIO_CONTRASENA ($idUsuario, '$contrasena', '$salt', '$usuario');";
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

    function crearReseteoClave($idUsuario, $token, $duracion, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_USUARIO_RESETEO_CLAVE ($idUsuario, '$token', $duracion, '$usuario');";
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

    function obtenerVerificacionReseteoClave($idUsuario) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_USUARIO_RESETEO_CLAVE($idUsuario)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_usuario_reseteo_clave' => $sql->field('id_usuario_reseteo_clave'), 'token' => $sql->field('token'), 'duracion' => $sql->field('duracion'), 'tiempo_transcurrido' => $sql->field('tiempo_transcurrido')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function eliminarReseteoClave($idUsuario, $token, $estado, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_ELIM_USUARIO_RESETEO_CLAVE ($idUsuario, '$token', $estado, '$usuario');";
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

    function crearTipoEmpleo($nombre, $nombreIngles, $usuario) {
        $idTipoEmpleo = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_EMPLEO ('$nombre', '$nombreIngles', '$usuario', @p_id_tipo_empleo)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_empleo AS id_tipo_empleo");
            $sql->read();
            if ($sql->next()) {
                $idTipoEmpleo = $sql->field('id_tipo_empleo');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoEmpleo;
    }

    function editarTipoEmpleo($idTipoEmpleo, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_EMPLEO($idTipoEmpleo, '$nombre', '$nombreIngles', $estado, '$usuario')";
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

    function obtenerTipoEmpleo($idTipoEmpleo) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_EMPLEO($idTipoEmpleo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_tipo_empleo' => $sql->field('id_tipo_empleo'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoEmpleo($estado, $lenguaje) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_EMPLEO($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_empleo' => $sql->field('id_tipo_empleo'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearTipoIngresoEconomico($descripcion, $orden, $usuario) {
        $idTipoIngresoEconomico = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_INGRESO_ECONOMICO ('$descripcion', $orden, '$usuario', @p_id_tipo_ingreso_economico)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_ingreso_economico AS id_tipo_ingreso_economico");
            $sql->read();
            if ($sql->next()) {
                $idTipoIngresoEconomico = $sql->field('id_tipo_ingreso_economico');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoIngresoEconomico;
    }

    function editarTipoIngresoEconomico($idTipoIngresoEconomico, $descripcion, $orden, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_INGRESO_ECONOMICO ($idTipoIngresoEconomico, '$descripcion', $orden, $estado, '$usuario')";
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

    function obtenerTipoIngresoEconomico($idTipoIngresoEconomico) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_INGRESO_ECONOMICO ($idTipoIngresoEconomico)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_tipo_ingreso_economico' => $sql->field('id_tipo_ingreso_economico'), 'descripcion' => $sql->field('descripcion'), 'orden' => $sql->field('orden'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoIngresoEconomico($estado) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_INGRESO_ECONOMICO($estado)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_ingreso_economico' => $sql->field('id_tipo_ingreso_economico'), 'descripcion' => $sql->field('descripcion'), 'orden' => $sql->field('orden'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearTipoEducacion($nombre, $nombreIngles, $usuario) {
        $idTipoEducacion = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_EDUCACION ('$nombre', '$nombreIngles', '$usuario', @p_id_tipo_educacion)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_educacion AS id_tipo_educacion");
            $sql->read();
            if ($sql->next()) {
                $idTipoEducacion = $sql->field('id_tipo_educacion');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoEducacion;
    }

    function editarTipoEducacion($idTipoEducacion, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_EDUCACION($idTipoEducacion, '$nombre', '$nombreIngles', $estado, '$usuario')";
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

    function obtenerTipoEducacion($idTipoEducacion) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_EDUCACION($idTipoEducacion)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_tipo_educacion' => $sql->field('id_tipo_educacion'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoEducacion($estado, $lenguaje) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_EDUCACION($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_educacion' => $sql->field('id_tipo_educacion'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearTipoGenero($nombre, $nombreIngles, $usuario) {
        $idTipoGenero = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_GENERO ('$nombre', '$nombreIngles', '$usuario', @p_id_tipo_genero)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_genero AS id_tipo_genero");
            $sql->read();
            if ($sql->next()) {
                $idTipoGenero = $sql->field('id_tipo_genero');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoGenero;
    }

    function editarTipoGenero($idTipoGenero, $nombre, $nombreIngles, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_GENERO($idTipoGenero, '$nombre', '$nombreIngles', $estado, '$usuario')";
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

    function obtenerTipoGenero($idTipoGenero) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_GENERO($idTipoGenero)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_tipo_genero' => $sql->field('id_tipo_genero'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoGenero($estado, $lenguaje) {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_GENERO($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_genero' => $sql->field('id_tipo_genero'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function grabarPrimerLogin($idUsuario, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_USUARIO_PRIMER_LOG ($idUsuario, '$usuario');";
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

    function grabarConsentimiento($idUsuario, $fecha) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_USUARIO_CONSENTIMIENTO ('$idUsuario', '$fecha', '$correo');";
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

    function grabarCuestionario($idUsuario, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_USUARIO_CUESTIONARIO ($idUsuario, '$usuario');";
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

    function grabarReporte($idUsuario, $usuario) {
        $resultado = true;
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_USUARIO_REPORTE ($idUsuario, '$usuario');";
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