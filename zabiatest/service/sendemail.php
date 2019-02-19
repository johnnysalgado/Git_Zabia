<?php
date_default_timezone_set("America/Lima");
$fecha_actual = date('Y-m-d h:m:s');

/* =============================================================== */
/* CONEXIÓNES REMOTAS
================================================================ */
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

require('../inc/configuracion.php');
require('../inc/mysql.php');
require('../inc/constante_email.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require('../vendor/autoload.php');

error_reporting(E_ERROR);

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    $email = $request->email;
    $name = $request->name;
    $subject = $request->subject;
    $body = $request->body;
    $isHtml = $request->isHtml;
    $templateCode = $request->templateCode;

    $result = "";
    $status = 1;

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
        if ($nameTo != "") {
            $mail->addAddress($email, $name);
        } else {
            $mail->addAddress($email);
        }
        //$mail->addAddress('contact@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        if ($isHtml == 1) {
            $mail->isHTML(true);
        } else {
            $mail->isHTML(false);
        }
        $mail->Subject = $subject;

        if ($templateCode == TEMPLATE_CODE_CUESTIONARIO) {
            $body = "<div width=\"100%\" style=\"background: #f8f8f8; padding:0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;\"><div style=\"max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;\"><tbody><tr><td style=\"vertical-align: top;\" align=\"center\"><img src=\"https://www.southtech.pe/zabiatest/imagen/icono/Mail.jpg\" width=\"700\" style=\"border:none;width: 700px;\"/></td></tr></tbody></table><div style=\"padding: 40px; background: #fff;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;\"><tbody><tr><td style=\"text-align:center;\" ><p style=\"font-size: 15px;color:#f39303\"><b>&iexcl;BIENVENIDO(A) A ZABIA!</b></p><div style=\"font-size: 12px;color: #979797;\">Somos un asesor de nutrici&oacute;n y bienestar que te</div><div style=\"font-size: 12px;color: #979797;\">ayudar&aacute; a sentirte mejor de adentro hacia afuera.</div><div style=\"font-size: 12px;color: #979797; margin-top:20px;\">Es momento de encontrar lo que realmente te alimenta</div><div style=\"font-size: 12px;color: #979797;\">&iexcl;Empieza ya!</div></td></tr><tr><td style=\"vertical-align: top; padding-bottom:30px;\" align=\"center\"><img src=\"https://www.southtech.pe/zabiatest/imagen/icono/crop-person-eating-salad-with-fried-egg_23-2147778350.jpg\" style=\"border:none\"></td></tr><tr><td style=\"text-align:center;\" ><div style=\"font-size: 12px;color: #979797;\">Como regalo, te enviamos una gu&iacute;a que facilitar&aacute; tu d&iacute;a a d&iacute;a</div><div style=\"font-size: 12px;color: #979797; margin-top:20px;\">Descarga en el siguiente enlace, una gu&iacute;a de compras saludable</div><div style=\"font-size: 12px;color: #979797;\">para el supermercado. Empieza a alimentarte de manera inteligente.</div><div style=\"font-size: 14px;margin-top:20px;\"><a style=\"color: #f39303;font-weight: 700;text-decoration: underline;font-style: oblique;\" href=\"http://www.myzabia.com/teracell/pdf/E-book_Zabia.pdf\">www.myzabia.com/teracell/pdf/E-book_Zabia.pdf</a></div></td></tr><tr><td style=\"vertical-align: top; padding-bottom:30px;\" align=\"center\"><img src=\"https://www.southtech.pe/zabiatest/imagen/icono/beautiful-young-woman-with-vegetables-in-grocery-bag-at-home_1301-7672.jpg\" style=\"border:none\"></td></tr><tr><td style=\"text-align:center;\"><div style=\"font-size: 12px;color: #979797;\">Tu reporte de salud estar&aacute; listo en 15 d&iacute;as</div><div style=\"font-size: 12px;color: #979797; margin-top:20px;\">Cuando hayamos terminado de analizar tu perfil, te estaremos enviando un link para</div><div style=\"font-size: 12px;color: #979797;\">que descargues el documento con recomendaciones especialmente</div><div style=\"font-size: 12px;color: #979797;\">para tu estilo de vida.</div></td></tr><tr><td style=\"vertical-align: top; padding-bottom:30px;\" align=\"center\"><img src=\"https://www.southtech.pe/zabiatest/imagen/icono/bottom_zabiaemail.jpg\" width=\"625px\" style=\"border:none;width: 625px;\"></td></tr></tbody></table></div></div></div>";
        }

        $mail->Body = $body;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        $result = 'Message has been sent. From: ' . $email;
    } catch (Exception $e) {
        $result = 'Message could not be sent.\nMailer Error: ' . $mail->ErrorInfo;
        $status = 0;
    }

    $output = array(
        'status' => $status
        , 'message' => $result);
    $respuesta = json_encode($output);
    die ($respuesta);
}
?>
