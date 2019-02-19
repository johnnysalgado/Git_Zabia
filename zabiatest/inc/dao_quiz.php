<?php
class DaoQuiz {

    function __construct() { }

    function crearQuiz($nombre, $imagen, $usuario) {
        $idQuiz = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_QUIZ ('$nombre', '$imagen', '$usuario', @p_id_quiz);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_quiz AS id_quiz");
        $sql->read();
        if ($sql->next()) {
            $idQuiz = $sql->field('id_quiz');
        }
        $cnx->close();
        $cnx = null;
        return $idQuiz;
    }

    function editarQuiz($idQuiz, $nombre, $nombreImagen, $estado, $usuario) {
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_QUIZ ($idQuiz, '$nombre', '$nombreImagen', $estado, '$usuario');"; 
        $cnx->execute($execute);
        $cnx->close();
        $cnx = null;
        return true;
    }

    function actualizarImagenQuiz($idQuiz, $nombreImagen) {
        if ($nombreImagen != "") {
            $cnx = new MySQL();
            $execute = "UPDATE quiz SET imagen = '$nombreImagen' WHERE id_quiz =  $idQuiz";
            $cnx->execute($execute);
            $cnx->close();
            $cnx = null;
        }
        return true;
    }

    function crearQuestion($idQuiz, $descripcion, $orden, $tipoRespuesta, $explicacion, $usuario) {
        $idQuestion = 0;
        $cnx = new MySQL();
        $execute = "CALL USP_CREA_QUESTION ($idQuiz, '$descripcion', $orden, '$tipoRespuesta', '$explicacion', '$usuario', @p_id_question);";
        $cnx->execute($execute);
        $sql = $cnx->query("SELECT @p_id_question AS id_question");
        $sql->read();
        if ($sql->next()) {
            $idQuestion = $sql->field('id_question');
        }
        $cnx->close();
        $cnx = null;
        return $idQuestion;
    }

    function editarQuestion($idQuestion, $descripcion, $orden, $tipoRespuesta, $explicacion, $usuario) {
        $cnx = new MySQL();
        $execute = "CALL USP_EDIT_QUESTION ($idQuestion, '$descripcion', $orden, '$tipoRespuesta', '$explicacion', '$usuario');";
        $cnx->execute($execute);
        $cnx->close();
        $cnx = null;
        return true;
    }

    function listarQuestion($idQuiz) {
        $arreglo = array();
        $query = "CALL USP_LIST_QUESTION($idQuiz)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        while ($sql->next()) {
            array_push($arreglo, array('id_question' => $sql->field('id_question'), 'id_quiz' => $sql->field('id_quiz'), 'descripcion' => $sql->field('descripcion'), 'orden' => $sql->field('orden'), 'cod_tipo_respuesta' => $sql->field('cod_tipo_respuesta'), 'explicacion' => $sql->field('explicacion'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function obtenerQuestion($idQuestion) {
        $arreglo = array();
        $query = "CALL USP_OBTEN_QUESTION($idPregunta)";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            array_push($arreglo, array('id_pregunta' => $sql->field('id_pregunta'), 'id_seccion_cuestionario' => $sql->field('id_seccion_cuestionario'), 'descripcion' => $sql->field('descripcion'), 'descripcion_ing' => $sql->field('descripcion_ing'), 'alternativo' => $sql->field('alternativo'), 'tipo_respuesta' => $sql->field('tipo_respuesta'), 'codigo' => $sql->field('codigo'), 'orden' => $sql->field('orden'), 'dato_especial' => $sql->field('dato_especial'), 'estado' => $sql->field('estado'), 'usuario_registro' => $sql->field('usuario_registro'), 'fecha_registro' => $sql->field('fecha_registro'), 'usuario_modifica' => $sql->field('usuario_modifica'), 'fecha_modifica' => $sql->field('fecha_modifica'), 'seccion' => $sql->field('seccion'), 'seccion_ing' => $sql->field('seccion_ing')));
        }
        $cnx->close();
        $cnx = null;
        return $arreglo;
    }

    function crearActualizarOption($idQuestion, $descripcion, $correcta, $estado, $orden, $usuario) {
        $query = "SELECT id_question_option, orden FROM question_option WHERE id_question = $idQuestion AND descripcion = '$descripcion'";
        $cnx = new MySQL();
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->next()) {
            $idQuestionOption = $sql->field('id_question_option');
            if ($orden == -1) { //si orden -1 entonces mantiene su orden.
                $orden = $sql->field('orden');
            }
            $execute = "CALL USP_EDIT_QUESTION_OPTION ($idQuestionOption, '$descripcion', '$correcta', $orden, '$estado', '$usuario')";
        } else {
            $execute = "CALL USP_CREA_QUESTION_OPTION ($idQuestion, '$descripcion', '$correcta', $orden, '$usuario', @p_id_question_option)";
        }
        $cnx->close();
        $cnx = new MySQL();
        $cnx->execute($execute);
        $cnx->close();
        $cnx = null;
        return true;
    }
}

?>