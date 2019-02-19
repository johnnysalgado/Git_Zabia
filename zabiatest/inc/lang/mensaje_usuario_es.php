<?php

    define("MENSAJE_EMAIL_RESETEO_CLAVE_BODY", "<div width=\"100%\" style=\"background: #f8f8f8; padding:0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;\"> <div style=\"max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px\"> <div style=\"padding: 40px; background: #fff;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;\"> <tbody> <tr> <td style=\"text-align:right;\" > <div style=\"font-size: 12px;color: #979797;\">Estimado (a).<br/><br/> El código de verificación para el reseteo de la contraseña es <b>[CODIGO_VERIFICACION]</b> <br/> Tiene <b>[DURACION_CODIGO_HORAS]</b> horas para ingresarlo y proceder a cambiar su contraseña. <br/><br/>Atentamente. <br/> El equipo de Precision Wellness.</div> <br /> [URL_LOGO]</td> </tr> </tbody> </table> </div> </div> </div>");

    define("MENSAJE_EMAIL_RESETEO_CLAVE_BODY_PLAINTEXT", "Estimado (a).\n\n El código de verificación para el reseteo de la contraseña es [CODIGO_VERIFICACION] \n Tiene [CODIGO_VERIFICACION] horas para ingresarlo y proceder a cambiar su contraseña. \n\n Atentamente. \n El equipo.");

    define("MENSAJE_EMAIL_RESETEO_CLAVE_SUBJECT", "Reseteo de contraseña - Código de verificación.");

    define("MENSAJE_EMAIL_RESETEO_CLAVE_ENVIADO", "Mensaje ha sido enviado a: [EMAIL]");

    define("MENSAJE_EMAIL_RESETEO_CLAVE_NOENVIADO_ERROR", "Mensaje no ha sido enviado.\n[ERROR].");

    define("MENSAJE_EMAIL_NO_EXISTE", "Correo no existe.");

    define("MENSAJE_USUARIO_CREDENCIAL_ERRADA", "Credenciales inválidas.");

    define("MENSAJE_USUARIO_AFILIADO_ERRADO", "Sus credenciales no pertenecen a este afiliado.");

    define("MENSAJE_USUARIO_NO_EXISTE", "Usuario no existe.");

    define("MENSAJE_EMAIL_EN_BLANCO", "Correo está en blanco.");

    define("MENSAJE_USUARIO_REGISTRO_INVALIDO", "Registro inválido.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_EXITOSO", "Contraseña cambiada correctamente.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_VENCIO_TIEMPO_CODIGO", "Se venció el tiempo de espera del código de verificación. Vuelva a solicitar otro.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_CODIGO_ERRADO", "Código de verificación está errado.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_CODIGO_EXITOSO", "El código de verificación está correcto.");

    define("MENSAJE_USUARIO_RESETEO_CLAVE_NO_SOLICITO_CODIGO", "No ha solicitado código de verificación.");

    define("MENSAJE_USUARIO_EMAIL_YA_EXISTE", "Correo ya está registrado.");

    define("MENSAJE_USUARIO_DATO_OBGLIGATORIO", "Falta ingresar los datos obligatorios.");

    define("MENSAJE_GRABACION_EXITOSA", "Actualización exitosa.");
    define("MENSAJE_ERROR_DESCONOCIDO", "Error no conocido.");

?>