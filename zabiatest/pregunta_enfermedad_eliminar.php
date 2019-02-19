<?php
    require('inc/sesion.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');
    require('inc/functions.php');
    require('inc/constante.php');
    require('inc/constante_cuestionario.php');
    require('inc/dao_cuestionario.php');

    $usuario = $_SESSION["U"];

    $ids = [];
    if (isset($_POST["chk_eliminar"])) {
        $ids = $_POST["chk_eliminar"];
    }
    $idPregunta = $_POST["id_pregunta_eliminar"];
    $estadoABuscar = $_POST['estado_buscar_eliminar'];
    $daoCuestionario = new DaoCuestionario();
    if ($estadoABuscar == LISTA_ACTIVO) {
        foreach ($ids as $id) {
            $daoCuestionario->eliminarPreguntaPrecondicion($id, $usuario);
        }
    } else {
        foreach ($ids as $id) {
            $daoCuestionario->activarPreguntaPrecondicion($id, $usuario);
        }
    }
    $daoCuestionario = null;
    header("Location: pregunta_enfermedad.php?id_pregunta=$idPregunta&e=$estadoABuscar");
    die();
?>
