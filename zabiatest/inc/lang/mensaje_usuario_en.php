<?php

    define("MENSAJE_EMAIL_RESETEO_CLAVE_BODY", "<div width=\"100%\" style=\"background: #f8f8f8; padding:0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;\"> <div style=\"max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px\"> <div style=\"padding: 40px; background: #fff;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;\"> <tbody> <tr> <td style=\"text-align:left;\" > <div style=\"font-size: 12px;color: #979797;\">Dear.<br/><br/> The verification code for resetting the password is <b>[CODIGO_VERIFICACION]</b> <br/> You have <b>[DURACION_CODIGO_HORAS]</b> hours to enter it and proceed to change your password. <br/><br/>Best regards. <br/> The Precision Wellness team.</div> <br /> [URL_LOGO] </td> </tr> </tbody> </table> </div> </div> </div>");

    define("MENSAJE_EMAIL_RESETEO_CLAVE_BODY_PLAINTEXT", "Dear.\n\n The verification code for resetting the password is [CODIGO_VERIFICACION] \n You have [DURACION_CODIGO_HORAS] hours to enter it and proceed to change your password. \n\n Best regards. \n The Precision Wellness team.");

    define("MENSAJE_EMAIL_RESETEO_CLAVE_SUBJECT", "Reset password - Verification code.");

    define("MENSAJE_EMAIL_RESETEO_CLAVE_ENVIADO", "Message has been sent To: [EMAIL]");

    define("MENSAJE_EMAIL_RESETEO_CLAVE_NOENVIADO_ERROR", "Message could not be sent.\n[ERROR]");

    define("MENSAJE_EMAIL_NO_EXISTE", "Email does not exist.");

    define("MENSAJE_USUARIO_CREDENCIAL_ERRADA", "Invalid credentials.");

    define("MENSAJE_USUARIO_AFILIADO_ERRADO", "Your credentials do not belong to this affiliate.");

    define("MENSAJE_USUARIO_NO_EXISTE", "User does not exist.");

    define("MENSAJE_EMAIL_EN_BLANCO", "Email is blank.");

    define("MENSAJE_USUARIO_REGISTRO_INVALIDO", "Invalid register.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_EXITOSO", "Password reset successful.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_VENCIO_TIEMPO_CODIGO", "The verification code timeout expired. Re-request another one.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_CODIGO_ERRADO", "Verification code is wrong.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_CODIGO_EXITOSO", "The verification code is correct.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_NO_SOLICITO_CODIGO", "You have not requested verification code.");

    define("MENSAJE_USUARIO_EMAIL_YA_EXISTE", "Register email already exists.");

    define("MENSAJE_USUARIO_DATO_OBGLIGATORIO", "Missing mandatory data.");

    define("MENSAJE_GRABACION_EXITOSA", "Update successful.");
    define("MENSAJE_ERROR_DESCONOCIDO", "Unknown error.");

?>