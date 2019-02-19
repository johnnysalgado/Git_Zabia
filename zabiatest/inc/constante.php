<?php
    define('ONZA', 'onza');
    define('LIBRA', 'libra');
    define('GRAMO', 'gramo');
    define('KILOGRAMO', 'kilogramo');
    
/********** constantes */
    define('ACCION_INSERT', 'i');
    define('ACCION_DELETE', 'd');
    define('PAGINA_PRINCIPAL', 'principal.php');
    define('PAGINACION_DEFECTO', '25');

    define('ID_BENEFICIO_ANTIOXIDANTE', '101');
    define('ID_BENEFICIO_ANTIINFLAMATORIO', '102');
    define('ID_BENEFICIO_PREVENCION_ENFERMEDAD', '103');
    define('ID_BENEFICIO_BELLEZA', '104');
    define('ID_BENEFICIO_ESTADO_ANIMICO', '105');
    define('ID_BENEFICIO_MEMORIA', '106');
    define('ID_BENEFICIO_FUERZA', '107');
    define('ID_BENEFICIO_PERDIDA_PESO', '108');

    define("URL_API_ZABIA_BASE", "http://54.152.38.95/zabiabo");
//    define("URL_API_ZABIA_BASE", "https://trial.precisionwellnes.com/pwbo");
    define("URL_API_ZABIA_CUESTIONARIO_LISTA", URL_API_ZABIA_BASE . "/service/getuserquestion.php");

/** imágenes íconos */
    define("ICONO_IMAGE_PATH", "https://d3f7rtrzi3lnw3.cloudfront.net/assets/images/logos/");
    define("ICONO_IMAGE_PATH_FISICO", "imagen/icono/");

    define('CLASE_ACTIVO', 'Activo');
    define('CLASE_INACTIVO', 'Inactivo');

    define("BASE_REMOTE_IMAGE_PATH", "https://d3f7rtrzi3lnw3.cloudfront.net/assets/images/");
    define("BASE_REMOTE_IMAGE_LOGO_PATH", "https://d3f7rtrzi3lnw3.cloudfront.net/assets/images/logos/");

    define("LENGUAJE_ESPANOL", "es");
    define("LENGUAJE_INGLES", "en");

    define ("LISTA_ACTIVO", "1");
    define ("LISTA_TODO", "NULL");
    define ("LISTA_INACTIVO", "0");
    define ("PREFIJO_PREGUNTA_CONTROL", "ctrl");

    define ("URL_SITIO", "https://trial.precisionwellness.com/"); //pasar a parámetros

    define ("PRIORIDAD_ELIMINAR", -2);
    define ("PRIORIDAD_RESTRINGIR", -1);
    define ("PRIORIDAD_NORMAL", 1);
    define ("PRIORIDAD_AUMENTAR", 2);

    define ("ICON_SHORT_PATH", "imagen/icono/");
    define ("ICON_REMOTE_PATH", "logos/icon/");
    define ("ICON_PREFIX_PATH", "40x40/40x40_");

    define ("PREFIJO_AFILIADO", "af");
?>