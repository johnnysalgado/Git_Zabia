<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/constante.php');
require('../inc/constante_email.php');
require('../inc/constante_usuario.php');
require('../inc/dao_usuario.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require('../vendor/autoload.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $email = $request->email;
    $affiliateCode = $request->affiliateCode;
//    $nombreSistema = $request->nameFrom;
    $lenguaje = $request->language;

    if ($lenguaje == "") {
        $lenguaje = LENGUAJE_ESPANOL;
    }
    $constanteMensajeUsuario = "../inc/lang/mensaje_usuario_$lenguaje.php";
    if (file_exists($constanteMensajeUsuario)) {
        require($constanteMensajeUsuario);
    } else {
        require("../inc/lang/mensaje_usuario.php");
    }

    $result = "";
    $status = 1;

    if ($email == "") {
        $result = MENSAJE_EMAIL_EN_BLANCO;
        $status = 0;
    } else {

        $daoUsuario = new DaoUsuario();
        $arregloUsuario = $daoUsuario->obtenerUsuarioPorCorreo($email);
        if (count($arregloUsuario) > 0) {
            $idUsuario = $arregloUsuario[0]['id_usuario'];
            $idAfiliado = $arregloUsuario[0]['id_affiliates'];

            $codigoAfiliado = "";
            $daoAfiliado = new DaoAfiliado();
            $arregloAfiliado = $daoAfiliado->obtenerAfiliado($idAfiliado);
            if (count($arregloAfiliado) > 0) {
                $codigoAfiliado = $arregloAfiliado[0]["cod_affiliates"];
            }
            $daoAfiliado = null;

            if ($codigoAfiliado == $affiliateCode) {
                $mail = new PHPMailer(true);
                try {
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Host = EMAIL_HOST;
                    $mail->SMTPAuth = true;
                    $mail->Username = EMAIL_USERNAME;
                    $mail->Password = EMAIL_PASSWORD;
                    $mail->SMTPSecure = EMAIL_SECURE;
                    $mail->Port = EMAIL_PORT;

                    //Recipients
                    $mail->setFrom(EMAIL_FROM_MAIL, EMAIL_FROM_NAME);
                    $mail->addAddress($email);
                    $mail->isHTML(true);
        
                    //asunto
                    $subject = MENSAJE_EMAIL_RESETEO_CLAVE_SUBJECT;
                    $mail->Subject = $subject;

                    $duracion = strval(DURACION_RESETEO_CLAVE) / 60 ;
                    $bytes = openssl_random_pseudo_bytes(8);
                    $codigoVerificacion = strtoupper(bin2hex($bytes));

                    //body
                    $urlLogo = "<a href=\"" . URL_SITIO . "\"> <img src=\"" . BASE_REMOTE_IMAGE_LOGO_PATH . "logo_pw_150.png\" /> </a>";
                    $body = str_replace("[URL_LOGO]", $urlLogo, str_replace("[DURACION_CODIGO_HORAS]", $duracion, str_replace("[CODIGO_VERIFICACION]", $codigoVerificacion, MENSAJE_EMAIL_RESETEO_CLAVE_BODY)));
                    $mail->Body = $body;

                    //body alterno
                    $altBody = str_replace("[DURACION_CODIGO_HORAS]", $duracion, str_replace("[CODIGO_VERIFICACION]", $codigoVerificacion, MENSAJE_EMAIL_RESETEO_CLAVE_BODY_PLAINTEXT));
                    $email->AltBody = $altBody;

                    $mail->send();
                    
                    $resultado = $daoUsuario->crearReseteoClave($idUsuario, $codigoVerificacion, $duracion, $email);
                    if ($resultado) {
                        $status = 1;
                        $result = str_replace("[EMAIL]", $email, MENSAJE_EMAIL_RESETEO_CLAVE_ENVIADO);
                    } else {
                        $status = 0;
                        $result = str_replace("[ERROR]", "", MENSAJE_EMAIL_RESETEO_CLAVE_NOENVIADO_ERROR);
                    }
                } catch (Exception $e) {
                    $result = str_replace("[ERROR]", $e->ErrorInfo, MENSAJE_EMAIL_RESETEO_CLAVE_NOENVIADO_ERROR);
                    $status = 0;
                }
            } else {
                $result = MENSAJE_USUARIO_AFILIADO_ERRADO;
                $status = 0;
            }
        } else {
            $result = MENSAJE_EMAIL_NO_EXISTE;
            $status = 0;
        }
    }
    $daoUsuario = null;
    $output = array(
        'status' => $status
        , 'message' => $result);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
