<?php
/********** constantes */
//define("LABEL_IMAGE_PATH", "http://localhost:90/zabiaadmin/imagen/label/");
//define("LABEL_IMAGE_PATH", "https://www.southtech.pe/zabiatest/imagen/label/");
define("LABEL_IMAGE_PATH", "http://54.152.38.95/zabiabo/imagen/label/");
define("LABEL_IMAGE_PATH_FISICO", "../imagen/label/");
//define("URL_API_ZABIA1_BASE", "http://localhost:90/zabiaadmin");
//define("URL_API_ZABIA1_BASE", "https://www.southtech.pe/zabiatest");
define("URL_API_ZABIA1_BASE", "http://54.152.38.95/zabiabo");
define("URL_API_LABEL_LISTA_IMAGEN", URL_API_ZABIA1_BASE . "/service/getlabellisttodatatable.php");
define("URL_API_LABEL_GRABA_IMAGEN", URL_API_ZABIA1_BASE . "/service/savelabel.php");
define("URL_API_LABEL_ELIMINA_IMAGEN", URL_API_ZABIA1_BASE . "/service/deletelabel.php");
define("URL_API_LABEL_OBTENER", URL_API_ZABIA1_BASE . "/service/getlabelbyid.php");
define("CONCEPTO_PRINCIPAL", "Principal");
define("CONCEPTO_INGREDIENTE", "Ingredientes");
define("CONCEPTO_VALOR_NUTRICIONAL", "Valor nutricional");
define("CONCEPTO_CODIGO_BARRA", "Código de barras");
define("ESTATUS_PENDIENTE", "Pendiente");
define("ESTATUS_EN_PROCESO", "En proceso");
define("ESTATUS_CERRADO", "Cerrado");
define("ESTATUS_ANULADO", "Anulado");

$arrayStatusLabel = array(ESTATUS_ANULADO, ESTATUS_CERRADO, ESTATUS_EN_PROCESO, ESTATUS_PENDIENTE);
?>