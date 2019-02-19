<?php
    define('IMC_MEN25', 'imc_men25');
    define('IMC_MAY25', 'imc_may25');
    define('IMC_MAY30', 'imc_may30');
    define('CODIGO_PREGUNTA_PESO', 'PESO');
    define('CODIGO_PREGUNTA_TALLA', 'TALLA');
    define('CODIGO_PREGUNTA_PAIS', 'PAIS');
    define('CODIGO_PRECONDICION_SALUD', 'PRECOND');
    define('CODIGO_INTOLERANCIA_ALERGIA', 'INTOALE');
    define('PDF_SHORT_PATH', 'pdf/');
    define("TIPO_DATO_PERSONAL", "Datos personales");
    define("TIPO_DATO_LIFE_STYLE", "Life style");
    define("TIPO_DATO_NUTRITION_BEVERAGE", "Nutrition beverage");
    define("TIPO_DATO_NUTRITION_HABIT", "Nutrition habits");
    define("TIPO_DATO_SUPPLEMENT", "Supplements");
    define("TIPO_DATO_MIND_MOOD", "Mind & mood");
    define("TIPO_DATO_PERMISO_DATA", "Permiso data");
    define("TIPO_DATO_DIETA", "Diet");
    define("TIPO_RESPUESTA_FECHA", "D");
    define("TIPO_RESPUESTA_TEXTO", "T");
    define("TIPO_RESPUESTA_NUMERO", "N");
    define("TIPO_RESPUESTA_DECIMAL", "E");
    define("TIPO_RESPUESTA_MULTIPLE", "M");
    define("TIPO_RESPUESTA_UNICA", "U");
    define('TIPO_PREGUNTA_DIETA', 'DIETA');
    define('DATOESPECIAL_INTOLERANCIA_ALERGIA', 'intoalergia');
    define('DATOESPECIAL_PAIS', 'pais');
    define('DATOESPECIAL_PRECONDICION', 'enfermedad');
    define('DATOESPECIAL_TIPO_DIETA', 'tipodieta');
    define('DATOESPECIAL_TIPO_GENERO', 'tipogenero');
    define('DATOESPECIAL_TRASTORNO_ESTOMACAL', 'trasestomacal');
    define('DATOESPECIAL_TIPO_ACTIVIDAD', 'tipoactividad');
    define('COLUMNA_TERCIO', '0.3');
    define('COLUMNA_MEDIO', '0.5');
    define('COLUMNA_COMPLETO', '1');
    define('PREFIJO_CONTROL_PREGUNTA', 'ctrl');
    define('PREFIJO_CONTROL_RESPUESTA', 'opc');
    define('PREFIJO_CONTROL_SUBPREGUNTA', 'subp');
    define('PREFIJO_CONTROL_SUBRESPUESTA', 'subopc');
    define('CONTROL_PRESENTACION_HORIZONTAL', 'horizontal');
    define('CONTROL_PRESENTACION_VERTICAL', 'vertical');
    define('CONTROL_PRESENTACION_ENLINEA', 'enlinea');
    //arreglo de dato especial
    $arregloDatoEspecial = array();
    array_push($arregloDatoEspecial, array('codigo' => DATOESPECIAL_PRECONDICION, 'descripcion' => 'Enfermedades'));
    array_push($arregloDatoEspecial, array('codigo' => DATOESPECIAL_TIPO_GENERO, 'descripcion' => 'Género'));
    array_push($arregloDatoEspecial, array('codigo' => DATOESPECIAL_INTOLERANCIA_ALERGIA, 'descripcion' => 'Intolerancia / Alergías'));
    array_push($arregloDatoEspecial, array('codigo' => DATOESPECIAL_PAIS, 'descripcion' => 'País'));
    array_push($arregloDatoEspecial, array('codigo' => DATOESPECIAL_TIPO_DIETA, 'descripcion' => 'Tipo dieta'));
    array_push($arregloDatoEspecial, array('codigo' => DATOESPECIAL_TIPO_ACTIVIDAD, 'descripcion' => 'Tipo actividad'));
    array_push($arregloDatoEspecial, array('codigo' => DATOESPECIAL_TRASTORNO_ESTOMACAL, 'descripcion' => 'Trastorno estomacal'));
    //arreglo de columnas
    $arregloColumnaPresentacion = array();
    array_push($arregloColumnaPresentacion, array('codigo' => COLUMNA_TERCIO, 'descripcion' => 'Un tercio de fila'));
    array_push($arregloColumnaPresentacion, array('codigo' => COLUMNA_MEDIO, 'descripcion' => 'Mitad de fila'));
    array_push($arregloColumnaPresentacion, array('codigo' => COLUMNA_COMPLETO, 'descripcion' => 'Fila completa'));
    //arreglo de presentación del control múltiple
    $arregloControlPresentacion = array();
    array_push($arregloControlPresentacion, array('codigo' => CONTROL_PRESENTACION_HORIZONTAL, 'descripcion' => 'Horizontal'));
    array_push($arregloControlPresentacion, array('codigo' => CONTROL_PRESENTACION_ENLINEA, 'descripcion' => 'En línea'));
    array_push($arregloControlPresentacion, array('codigo' => CONTROL_PRESENTACION_VERTICAL, 'descripcion' => 'Vertical'));
    //arreglo de ícono de secciones
    $arregloSeccionIconoClase = array();
    array_push($arregloSeccionIconoClase, array('codigo' => 'fa fa-user', 'descripcion' => 'fa fa-user'));
    array_push($arregloSeccionIconoClase, array('codigo' => 'fa fa-female', 'descripcion' => 'fa fa-female'));
    array_push($arregloSeccionIconoClase, array('codigo' => 'fa fa-bicycle', 'descripcion' => 'fa fa-bicycle'));
    array_push($arregloSeccionIconoClase, array('codigo' => 'fa fa-heart', 'descripcion' => 'fa fa-heart'));
    array_push($arregloSeccionIconoClase, array('codigo' => 'fa fa-smile-o', 'descripcion' => 'fa fa-smile-o'));
    array_push($arregloSeccionIconoClase, array('codigo' => 'fa fa-smile', 'descripcion' => 'fa fa-smile'));
    array_push($arregloSeccionIconoClase, array('codigo' => 'fa fa-stethoscope', 'descripcion' => 'fa fa-stethoscope'));
    array_push($arregloSeccionIconoClase, array('codigo' => 'fa fa-check-circle', 'descripcion' => 'fa fa-check-circle'));
?>

