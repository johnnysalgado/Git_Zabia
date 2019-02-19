<?php

    function grabarPreguntaQuestionnaireSet($daoCuestionario, $idPregunta, $systemRequired, $usuario) {
        if ($systemRequired == "1") {
            $arregloQuestionnaireSet = $daoCuestionario->listarQuestionnaireSet(LISTA_ACTIVO);
            foreach ($arregloQuestionnaireSet as $itemQS) {
                $idQuestionnaireSet = $itemQS["id_questionnaire_set"];
                $daoCuestionario->grabarPreguntaQuestionnaireSet($idPregunta, $idQuestionnaireSet, $usuario);
            }
        }
    }

    function grabarPreguntaQuestionnaireSetPorAfiliado($daoCuestionario, $idPregunta, $idAfiliado, $usuario) {
        $daoCuestionario->grabarPreguntaQuestionnaireSetPorAfiliado($idPregunta, $idAfiliado, $usuario);
    }

    function grabarSeccionCuestionarioQuestionnaireSet($daoCuestionario, $idSeccionCuestionario, $systemRequired, $usuario) {
        if ($systemRequired == "1") {
            $arregloQuestionnaireSet = $daoCuestionario->listarQuestionnaireSet(LISTA_ACTIVO);
            foreach ($arregloQuestionnaireSet as $itemQS) {
                $idQuestionnaireSet = $itemQS["id_questionnaire_set"];
                $daoCuestionario->grabarSeccionCuestionarioQuestionnaireSet($idSeccionCuestionario, $idQuestionnaireSet, $usuario);
            }
        }
    }

    function grabarSeccionCuestionarioQuestionnaireSetPorAfiliado($daoCuestionario, $idSeccionCuestionario, $idAfiliado, $usuario) {
        $daoCuestionario->grabarSeccionCuestionarioQuestionnaireSetPorAfiliado($idSeccionCuestionario, $idAfiliado, $usuario);
    }
?>