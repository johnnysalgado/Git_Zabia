<?php
    define('NUTRIENTE_ESENCIAL', 'ESENCIAL');
    define('NUTRIENTE_NO_ESENCIAL_FAVORABLE', 'NOESENCIALFAV');
    define('NUTRIENTE_NO_ESENCIAL_DESFAVORABLE', 'NOESENCIALDES');
    define('NUTRIENTE_NO_APLICA_FORMULA', 'NOAPLICA');
    //arreglo de nutriente_esencial para búsqueda
    $arregloNutrienteEsencialBusqueda = array();
    array_push($arregloNutrienteEsencialBusqueda, array('codigo' => NUTRIENTE_ESENCIAL, 'descripcion' => 'Esencial'));
    array_push($arregloNutrienteEsencialBusqueda, array('codigo' => NUTRIENTE_NO_ESENCIAL_FAVORABLE, 'descripcion' => 'No esencial favorable'));
    array_push($arregloNutrienteEsencialBusqueda, array('codigo' => NUTRIENTE_NO_ESENCIAL_DESFAVORABLE, 'descripcion' => 'No esencial desfavorable'));
    array_push($arregloNutrienteEsencialBusqueda, array('codigo' => NUTRIENTE_NO_APLICA_FORMULA, 'descripcion' => 'Resto de nutrientes'));
    //arreglo de nutriente_esencial para grabar
    $arregloNutrienteEsencialGrabar = array();
    array_push($arregloNutrienteEsencialGrabar, array('codigo' => NUTRIENTE_ESENCIAL, 'descripcion' => 'Esencial'));
    array_push($arregloNutrienteEsencialGrabar, array('codigo' => NUTRIENTE_NO_ESENCIAL_FAVORABLE, 'descripcion' => 'No esencial favorable'));
    array_push($arregloNutrienteEsencialGrabar, array('codigo' => NUTRIENTE_NO_ESENCIAL_DESFAVORABLE, 'descripcion' => 'No esencial desfavorable'));
    array_push($arregloNutrienteEsencialGrabar, array('codigo' => NUTRIENTE_NO_APLICA_FORMULA, 'descripcion' => 'No aplica a fórmula densidad'));
?>
