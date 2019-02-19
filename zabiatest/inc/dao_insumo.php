<?php
class DaoInsumo {

    function __construct() { }
	
	function obtenerInsumo($idInsumo) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_INSUMO($idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if  ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'codigo_externo' => $sql->field('codigo_externo'), 'id_tipo_alimento' => $sql->field('id_tipo_alimento'), 'imagen' => $sql->field('imagen'), 'estado' => $sql->field('estado')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerInsumoPorIntolerancia($idIntolerancia, $idInsumo) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_INSUMO_INTOLERANCIA($idIntolerancia, $idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if  ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'insumo' => $sql->field('insumo'), 'intolerancia' => $sql->field('intolerancia'), 'accion' => $sql->field('accion'), 'prioridad' => $sql->field('prioridad'), 'estado' => $sql->field('estado')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarInsumos() {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO()";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'insumo' => $sql->field('insumo'), 'nombre_ing' => $sql->field('nombre_ing'), 'tipo_alimento' => $sql->field('tipo_alimento')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarIntoleranciaPorInsumo($idInsumo) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_INTO_X_INSUMO($idInsumo)";
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
	
	function listarInsumosPorIntolerancia($idIntolerancia) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_INTO_X_INTOLERANCIA($idIntolerancia)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'insumo' => $sql->field('insumo'), 'intolerancia' => $sql->field('intolerancia'), 'accion' => $sql->field('accion'), 'prioridad' => $sql->field('prioridad'), 'estado' => $sql->field('estado')));
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

    function grabarIntoleranciaXInsumo($idIntolerancia, $idInsumo, $accion, $prioridad, $usuario) {
        $resultado = true;
        $execute = "CALL USP_UPD_INSUMO_INTOLERANCIA($idInsumo, $idIntolerancia, '$accion', $prioridad, '$usuario')";
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

    function listarTrastornoEstomacalPorInsumo($idInsumo) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_TRASTORNO_ESTOMACAL_X_INSUMO($idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_trastorno_estomacal' => $sql->field('id_trastorno_estomacal'), 'trastorno_estomacal' => $sql->field('trastorno_estomacal'), 'id_insumo_trastorno_estomacal' => $sql->field('id_insumo_trastorno_estomacal')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
	
	function listarInsumosPorTrastornoEstomacal($idTrastornoEstomacal) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_TRASTORNO_ESTOMACAL_X_TRASTORNO_ESTOMACAL($idTrastornoEstomacal)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'insumo' => $sql->field('insumo'), 'trastorno_estomacal' => $sql->field('trastorno_estomacal'), 'accion' => $sql->field('accion'), 'prioridad' => $sql->field('prioridad'), 'estado' => $sql->field('estado')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function inactivarTrastornoEstomacalPorInsumo($idInsumo, $usuario) {
        $resultado = true;
        $execute = "CALL USP_ELIM_INSUMO_TRASTORNO_ESTOMACAL($idInsumo, '$usuario')";
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

    function grabarTrastornoEstomacalPorInsumo($idTrastornoEstomacal, $idInsumo, $accion, $prioridad, $usuario) {
        $resultado = true;
        $execute = "CALL USP_UPD_INSUMO_TRASTORNO_ESTOMACAL($idInsumo, $idTrastornoEstomacal, '$accion', $prioridad, '$usuario')";
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

    function obtenerInsumoPorTrastornoEstomacal($idTrastornoEstomacal, $idInsumo) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_INSUMO_TRASTORNO_ESTOMACAL ($idTrastornoEstomacal, $idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if  ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'insumo' => $sql->field('insumo'), 'trastorno_estomacal' => $sql->field('trastorno_estomacal'), 'accion' => $sql->field('accion'), 'prioridad' => $sql->field('prioridad'), 'estado' => $sql->field('estado')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerInsumoSemaforoPorUsuario($idUsuario, $idInsumo) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_INSUMO_USUARIO_SEMA($idUsuario, $idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            $accion = $sql->field('accion');
            array_push($arreglo, array('action' => $sql->field('accion'), 'icon' => '', 'description' => ''));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarInsumoDetallePorUsuario($idUsuario, $idInsumo, $rutaIcono, $lenguaje) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_INSUMO_USUARIO_DETA($idUsuario, $idInsumo, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('userIngredientDetail' => $sql->field('intolerancia_precondicion'), 'icon' => $rutaIcono . $sql->field('icono')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarTipoAlimento($estado = -1, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_TIPO_ALIMENTO($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_tipo_alimento' => $sql->field('id_tipo_alimento'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'codigo_externo' => $sql->field('codigo_externo'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
    
    function obtenerTipoAlimento($idTipoAlimento) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_TIPO_ALIMENTO($idTipoAlimento)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_tipo_alimento' => $sql->field('id_tipo_alimento'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'codigo_externo' => $sql->field('codigo_externo'), 'flag_procesar' => $sql->field('flag_procesar'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearTipoAlimento($nombre, $nombreIngles, $flagProcesar, $usuario) {
        $idTipoAlimento = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_TIPO_ALIMENTO ('$nombre', '$nombreIngles', $flagProcesar, '$usuario', @p_id_tipo_alimento)";
        try {
            $cnx->execute($execute);
            $sql = $cnx->query("SELECT @p_id_tipo_alimento AS id_tipo_alimento");
            $sql->read();
            if ($sql->next()) {
                $idTipoAlimento = $sql->field('id_tipo_alimento');
            }
        } catch(Exception $e) {
        } finally {
            $cnx->close();
            $cnx = null;
        }
        return $idTipoAlimento;
    }

    function editarTipoAlimento($idTipoAlimento, $nombre, $nombreIngles, $flagProcesar, $estado, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_TIPO_ALIMENTO($idTipoAlimento, '$nombre', '$nombreIngles', $flagProcesar, $estado, '$usuario')";
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

    function listarBeneficio() {
        $arreglo = array();
        $query = "CALL USP_LIST_BENEFICIO()";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_beneficio' => $sql->field('id_beneficio'), 'nombre' => $sql->field('nombre'), 'nombre_ing' => $sql->field('nombre_ing'), 'descripcion' => $sql->field('descripcion'), 'flag_prevencion_enfermedad' => $sql->field('flag_prevencion_enfermedad'), 'imagen' => $sql->field('imagen'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarMejoresInsumo($idUsuario, $lenguaje, $pagina = 0, $registros = 0) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_MEJOR($idUsuario, '$lenguaje', $pagina, $registros)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'insumo' => $sql->field('insumo'), 'insumo_ing' => $sql->field('insumo_ing'), 'imagen' => $sql->field('imagen'), 'id_tipo_alimento' => $sql->field('id_tipo_alimento'), 'tipo_alimento' => $sql->field('tipo_alimento'), 'tipo_alimento_ing' => $sql->field('tipo_alimento_ing'), 'prioridad' => $sql->field('prioridad')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarPeoresInsumo($idUsuario, $lenguaje, $pagina = 0, $registros = 0) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_PEOR($idUsuario, '$lenguaje', $pagina, $registros)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'insumo' => $sql->field('insumo'), 'insumo_ing' => $sql->field('insumo_ing'), 'imagen' => $sql->field('imagen'), 'id_tipo_alimento' => $sql->field('id_tipo_alimento'), 'tipo_alimento' => $sql->field('tipo_alimento'), 'tipo_alimento_ing' => $sql->field('tipo_alimento_ing'), 'prioridad' => $sql->field('prioridad')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarInsumoSemaforoPorUsuario($idUsuario, $idTipoAlimentoSet, $busqueda, $lenguaje, $pagina, $registros) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_USUARIO_SEMAFORO($idUsuario, '$idTipoAlimentoSet', '$busqueda', '$lenguaje', $pagina, $registros)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'insumo' => $sql->field('insumo'), 'insumo_ing' => $sql->field('insumo_ing'), 'imagen' => $sql->field('imagen'), 'id_tipo_alimento' => $sql->field('id_tipo_alimento'), 'tipo_alimento' => $sql->field('tipo_alimento'), 'tipo_alimento_ing' => $sql->field('tipo_alimento_ing'), 'prioridad' => $sql->field("prioridad")));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarInsumoSemaforoPorUsuarioPorFoodGroup($idUsuario, $idFoodGroupSet, $busqueda, $lenguaje, $pagina, $registros) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_USUARIO_SEMAFORO_FOODGROUP($idUsuario, '$idFoodGroupSet', '$busqueda', '$lenguaje', $pagina, $registros)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'insumo' => $sql->field('insumo'), 'insumo_ing' => $sql->field('insumo_ing'), 'imagen' => $sql->field('imagen'), 'id_tipo_alimento' => $sql->field('id_foodgroup'), 'foodgroup' => $sql->field('foodgroup'), 'foodgroup_ing' => $sql->field('foodgroup_ing'), 'prioridad' => $sql->field("prioridad")));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerOracPorInsumo($idInsumo) {
        $arreglo = array();
        $query = "CALL USP_LIST_ORAC($idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'id_insumo_orac' => $sql->field('id_insumo_orac'), 'unidad' => $sql->field('unidad'), 'promedio' => $sql->field('promedio'), 'minimo' => $sql->field('minimo'), 'maximo' => $sql->field('maximo'), 'sem' => $sql->field('sem'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modificacion' => $sql->field("usuario_modificacion"), 'fecha_modificacion' => $sql->field("fecha_modificacion")));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerInsumoResumen($idInsumo) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_INSUMO_RESUMEN($idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'rs_code' => $sql->field('rs_code'), 'id_tipo_alimento' => $sql->field('id_tipo_alimento'), 'foodName' => $sql->field('foodName'), 'foodNameSPA' => $sql->field('foodNameSPA'), 'foodType' => $sql->field('foodType'), 'foodTypeSPA' => $sql->field('foodTypeSPA'), 'imagen' => $sql->field('imagen'), 'weight_unitySPA' => $sql->field("weight_unitySPA"), 'weight_unity' => $sql->field("weight_unity"), 'weight' => $sql->field("weight"), 'density' => $sql->field("density")));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerInsumoNutrienteResumen($idInsumo) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_NUTRIENTE_RESUMEN($idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'id_nutriente' => $sql->field('id_nutriente'), 'nutrient' => $sql->field('nutrient'), 'nutrientSPA' => $sql->field('nutrientSPA'), 'unity' => $sql->field('unity'), 'amount' => $sql->field('amount'), 'rdi' => $sql->field('rdi'), 'rdiRate' => $sql->field('rdiRate'), 'lowerLimit' => $sql->field('lowerLimit'), 'upperLimit' => $sql->field('upperLimit'), 'contribution' => $sql->field('contribution')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarInsumoMedida($idInsumo) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_MEDIDA($idInsumo)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo_medida' => $sql->field('id_insumo_medida'), 'id_insumo' => $sql->field('id_insumo'), 'secuencia' => $sql->field('secuencia'), 'unidad' => $sql->field('unidad'), 'unidad_ing' => $sql->field('unidad_ing'), 'cantidad' => $sql->field('cantidad'), 'gramo' => $sql->field('gramo'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenInsumoMedidaPorSecuencia($idInsumo, $secuencia) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_INSUMO_MEDIDA_X_SECUENCIA($idInsumo, $secuencia)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_insumo_medida' => $sql->field('id_insumo_medida'), 'id_insumo' => $sql->field('id_insumo'), 'secuencia' => $sql->field('secuencia'), 'unidad' => $sql->field('unidad'), 'unidad_ing' => $sql->field('unidad_ing'), 'cantidad' => $sql->field('cantidad'), 'gramo' => $sql->field('gramo'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function editarInsumoMedida($idInsumoMedida, $secuencia, $unidad, $unidadIngles, $usuario) {
        $resultado = true;
        $execute = "CALL USP_EDIT_INSUMO_MEDIDA ($idInsumoMedida, $secuencia, '$unidad', '$unidadIngles', '$usuario')";
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

    function listarInsumoNutritionLabel($idInsumo, $flagOrden, $lenguaje) {
        $arreglo = array();
        $query = "CALL USP_LIST_INSUMO_NUTRITION_LABEL($idInsumo, $flagOrden, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_insumo' => $sql->field('id_insumo'), 'id_nutriente' => $sql->field('id_nutriente'), 'food' => $sql->field('food'), 'foodSPA' => $sql->field('foodSPA'), 'nutrient' => $sql->field('nutrient'), 'nutrientSPA' => $sql->field('nutrientSPA'), 'nutrientUnit' => $sql->field('nutrientUnit'), 'dv2000' => $sql->field('dv2000'), 'dv2500' => $sql->field('dv2500'), 'dvRelation' => $sql->field('dvRelation'), 'dvRelationSPA' => $sql->field('dvRelationSPA'), 'nutrientAmount' => $sql->field('nutrientAmount'), 'nutrientServingAmount' => $sql->field('nutrientServingAmount'), 'dvPercent' => $sql->field('dvPercent'), 'servingSPA' => $sql->field('servingSPA'), 'serving' => $sql->field('serving'), 'servingAmount' => $sql->field('servingAmount'), 'servingGram' => $sql->field('servingGram'), 'calorieByGram' => $sql->field('calorieByGram'), 'labelIndent' => $sql->field('labelIndent'), 'isFat' => $sql->field('isFat'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function listarFoodgroup($estado = -1, $lenguaje = "es") {
        $arreglo = array();
        $query = "CALL USP_LIST_FOODGROUP($estado, '$lenguaje')";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_foodgroup' => $sql->field('id_foodgroup'), 'name' => $sql->field('name'), 'name_s' => $sql->field('name_s'), 'image' => $sql->field('image'), 'status' => $sql->field('status'), 'created_by' => $sql->field('created_by'), 'created' => $sql->field('created'), 'updated_by' => $sql->field('updated_by'), 'updated' => $sql->field('updated')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }
    
}

?>