<?php
    require('inc/sesion.php');
    require('inc/constante.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

    if (isset($_POST["lenguaje"])) {

        $mensajeUsuarioNoExiste = $_POST["usuario_no_existe"];
        $mensajeEmailNoExiste = $_POST["email_no_existe"];
        $mensajeEmailEnBlanco = $_POST["email_en_blanco"];
        $mensajeUsuarioCredencialErrada = $_POST["credencial_errada"];
        $mensajeUsuarioRegistroInvalido = $_POST["registro_invalido"];
        $mensajeUsuarioEmailYaExiste = $_POST["email_ya_existe"];
        $mensajeUsuarioDatoObligatorio = $_POST["dato_obligatorio"];
        $mensajeEmailReseteoClaveSubject = $_POST["reseteo_clave_subject"];
        $mensajeEmailReseteoClaveBody = $_POST["reseteo_clave_body"];
        $mensajeEmailReseteoClaveBodyPlainText = $_POST["reseteo_clave_body_plaintext"];
        $mensajeEmailReseteoClaveEnviado = $_POST["reseteo_clave_enviada"];
        $mensajeEmailReseteoClaveNoEnviadoError = $_POST["reseteo_clave_no_enviada"];
        $mensajeUsuarioReseteoClaveExitoso = $_POST["reseteo_clave_exitosa"];
        $mensajeUsuarioReseteoClaveVencioTiempoCodigo = $_POST["reseteo_clave_vencio_tiempo_codigo"];
        $mensajeUsuarioReseteoClaveCodigoErrado = $_POST["reseteo_clave_codigo_errado"];
        $mensajeUsuarioReseteoClaveCodigoExitoso = $_POST["reseteo_clave_codigo_exitoso"];
        $mensajeUsuarioReseteoClaveNoSolicitoCodigo = $_POST["reseteo_clave_codigo_no_solicitado"];
        $mensajeUsuarioReseteoClaveGrabacionExitosa = $_POST["reseteo_clave_grabacion_exitosa"];
        $mensajeUsuarioReseteoClaveGrabacionErrada = $_POST["reseteo_clave_grabacion_errada"];
        $lenguaje = $_POST["lenguaje"];

        header("Location: param_mensaje_usuario_$lenguaje.php");
        die();
    
    } else {

        if (isset($_GET["lang"])) {
            $lenguaje = $_GET["lang"];
        } else {
            $lenguaje = LENGUAJE_ESPANOL;
        }

        $constanteMensajeUsuario = "inc/lang/mensaje_usuario_$lenguaje.php";
        if (file_exists($constanteMensajeUsuario)) {
            require($constanteMensajeUsuario);
        }

        $mensajeUsuarioNoExiste = defined('MENSAJE_USUARIO_NO_EXISTE') ? MENSAJE_USUARIO_NO_EXISTE : "";
        $mensajeEmailNoExiste = defined('MENSAJE_EMAIL_NO_EXISTE') ? MENSAJE_EMAIL_NO_EXISTE : "";
        $mensajeEmailEnBlanco = defined('MENSAJE_EMAIL_EN_BLANCO') ? MENSAJE_EMAIL_EN_BLANCO : "";
        $mensajeUsuarioCredencialErrada = defined('MENSAJE_USUARIO_CREDENCIAL_ERRADA') ? MENSAJE_USUARIO_CREDENCIAL_ERRADA : "";
        $mensajeUsuarioRegistroInvalido = defined('MENSAJE_USUARIO_REGISTRO_INVALIDO') ? MENSAJE_USUARIO_REGISTRO_INVALIDO : "";
        $mensajeUsuarioEmailYaExiste = defined('MENSAJE_USUARIO_EMAIL_YA_EXISTE') ? MENSAJE_USUARIO_EMAIL_YA_EXISTE : "";
        $mensajeUsuarioDatoObligatorio = defined('MENSAJE_USUARIO_DATO_OBGLIGATORIO') ?  MENSAJE_USUARIO_DATO_OBGLIGATORIO : "";

        $mensajeEmailReseteoClaveSubject = defined('MENSAJE_EMAIL_RESETEO_CLAVE_SUBJECT') ? MENSAJE_EMAIL_RESETEO_CLAVE_SUBJECT : "";
        $mensajeEmailReseteoClaveBody = defined('MENSAJE_EMAIL_RESETEO_CLAVE_BODY') ? MENSAJE_EMAIL_RESETEO_CLAVE_BODY : "";
        $mensajeEmailReseteoClaveBodyPlainText = defined('MENSAJE_EMAIL_RESETEO_CLAVE_BODY_PLAINTEXT') ? MENSAJE_EMAIL_RESETEO_CLAVE_BODY_PLAINTEXT : "";
        $mensajeEmailReseteoClaveEnviado = defined('MENSAJE_EMAIL_RESETEO_CLAVE_ENVIADO') ? MENSAJE_EMAIL_RESETEO_CLAVE_ENVIADO : "";
        $mensajeEmailReseteoClaveNoEnviadoError = defined('MENSAJE_EMAIL_RESETEO_CLAVE_NOENVIADO_ERROR') ? MENSAJE_EMAIL_RESETEO_CLAVE_NOENVIADO_ERROR : "";

        $mensajeUsuarioReseteoClaveExitoso = defined('MENSAJE_USUARIO_RESETEO_CLAVE_EXITOSO') ? MENSAJE_USUARIO_RESETEO_CLAVE_EXITOSO : "";
        $mensajeUsuarioReseteoClaveVencioTiempoCodigo = defined('MENSAJE_USUARIO_RESETEO_CLAVE_VENCIO_TIEMPO_CODIGO') ? MENSAJE_USUARIO_RESETEO_CLAVE_VENCIO_TIEMPO_CODIGO : "";
        $mensajeUsuarioReseteoClaveCodigoErrado = defined('MENSAJE_USUARIO_RESETEO_CLAVE_CODIGO_ERRADO') ? MENSAJE_USUARIO_RESETEO_CLAVE_CODIGO_ERRADO : "";
        $mensajeUsuarioReseteoClaveCodigoExitoso = defined('MENSAJE_USUARIO_RESETEO_CLAVE_CODIGO_EXITOSO') ? MENSAJE_USUARIO_RESETEO_CLAVE_CODIGO_EXITOSO : "";
        $mensajeUsuarioReseteoClaveNoSolicitoCodigo = defined('MENSAJE_USUARIO_RESETEO_CLAVE_NO_SOLICITO_CODIGO') ? MENSAJE_USUARIO_RESETEO_CLAVE_NO_SOLICITO_CODIGO : "";
        $mensajeUsuarioReseteoClaveGrabacionExitosa = defined('MENSAJE_GRABACION_EXITOSA') ?  MENSAJE_GRABACION_EXITOSA : "";
        $mensajeUsuarioReseteoClaveGrabacionErrada = defined('MENSAJE_ERROR_DESCONOCIDO') ? MENSAJE_ERROR_DESCONOCIDO : "";

    }

?>
<!DOCTYPE html>
<html lang="es">
    <?php  require('inc/head.php'); ?>
    <body>
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>
        <div id="wrapper">
            <!-- Navigation -->
            <?php  require('inc/nav_horizontal.php'); ?>
            <!-- Left navbar-header -->
            <?php  require('inc/nav_vertical.php'); ?>
            <!-- Left navbar-header end -->
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row bg-title">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 class="page-title">Mensajes para usuario (<?php echo $lenguaje; ?>)</h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form action="param_mensaje_usuario_en.php" method="post" id="forma">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Usuario No existe</label>
                                            <input type="text" name="usuario_no_existe" id="usuario_no_existe" class="form-control" value="<?php echo $mensajeUsuarioNoExiste; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo No existe</label>
                                            <input type="text" name="email_no_existe" id="email_no_existe" class="form-control" value="<?php echo $mensajeEmailNoExiste; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email en blanco</label>
                                            <input type="text" name="email_en_blanco" id="email_en_blanco" class="form-control" value="<?php echo $mensajeEmailEnBlanco; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Credenciales inv&aacute;lidas</label>
                                            <input type="text" name="credencial_errada" id="credencial_errada" class="form-control" value="<?php echo $mensajeUsuarioCredencialErrada; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Registro inv&aacute;lido</label>
                                            <input type="text" name="registro_invalido" id="registro_invalido" class="form-control" value="<?php echo $mensajeUsuarioRegistroInvalido; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email ya existe</label>
                                            <input type="text" name="email_ya_existe" id="email_ya_existe" class="form-control" value="<?php echo $mensajeUsuarioEmailYaExiste; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Datos obligatorios</label>
                                            <input type="text" name="dato_obligatorio" id="dato_obligatorio" class="form-control" value="<?php echo $mensajeUsuarioDatoObligatorio; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Asunto correo reseteo contrase&ntilde;a</label>
                                            <input type="text" name="reseteo_clave_subject" id="reseteo_clave_subject" class="form-control" value="<?php echo $mensajeEmailReseteoClaveSubject; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 nopadding text-left">
                                        <div class="form-group">
                                            <label>Cuerpo correo reseteo contrase&ntilde;a</label>
                                            <div id="reseteo_clave_body_editor"></div>
                                            <input type="hidden" name="reseteo_clave_body" id="reseteo_clave_body" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Cuerpo correo reseteo contrase&ntilde;a (plan texto) </label>
                                            <textarea name="reseteo_clave_body_plaintext" id="reseteo_clave_body_plaintext" class="form-control"  rows="4"><?php echo $mensajeEmailReseteoClaveBodyPlainText; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo reseteo contrase&ntilde;a Clave enviada</label>
                                            <input type="text" name="reseteo_clave_enviada" id="reseteo_clave_enviada" class="form-control" value="<?php echo $mensajeEmailReseteoClaveEnviado; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo reseteo contrase&ntilde;a Clave No enviada</label>
                                            <input type="text" name="reseteo_clave_no_enviada" id="reseteo_clave_no_enviada" class="form-control" value="<?php echo $mensajeEmailReseteoClaveNoEnviadoError; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo reseteo contrase&ntilde;a Clave exitosa</label>
                                            <input type="text" name="reseteo_clave_exitosa" id="reseteo_clave_exitosa" class="form-control" value="<?php echo $mensajeUsuarioReseteoClaveExitoso; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo reseteo contrase&ntilde;a Venci&oacute; tiempo del c&oacute;digo</label>
                                            <input type="text" name="reseteo_clave_vencio_tiempo_codigo" id="reseteo_clave_vencio_tiempo_codigo" class="form-control" value="<?php echo $mensajeUsuarioReseteoClaveVencioTiempoCodigo; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo reseteo contrase&ntilde;a C&oacute;digo errado </label>
                                            <input type="text" name="reseteo_clave_codigo_errado" id="reseteo_clave_codigo_errado" class="form-control" value="<?php echo $mensajeUsuarioReseteoClaveCodigoErrado; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo reseteo contrase&ntilde;a C&oacute;digo exitoso</label>
                                            <input type="text" name="reseteo_clave_codigo_exitoso" id="reseteo_clave_codigo_exitoso" class="form-control" value="<?php echo $mensajeUsuarioReseteoClaveCodigoExitoso; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo reseteo contrase&ntilde;a C&oacute;digo No solicitado </label>
                                            <input type="text" name="reseteo_clave_codigo_no_solicitado" id="reseteo_clave_codigo_no_solicitado" class="form-control" value="<?php echo $mensajeUsuarioReseteoClaveNoSolicitoCodigo; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo reseteo contrase&ntilde;a Grabaci&oacute;n exitosa </label>
                                            <input type="text" name="reseteo_clave_grabacion_exitosa" id="reseteo_clave_grabacion_exitosa" class="form-control" value="<?php echo $mensajeUsuarioReseteoClaveGrabacionExitosa; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo reseteo contrase&ntilde;a GrabaciC&oacute;n errada</label>
                                            <input type="text" name="reseteo_clave_grabacion_errada" id="reseteo_clave_grabacion_errada" class="form-control" value="<?php echo $mensajeUsuarioReseteoClaveGrabacionErrada; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div id="divError" class="row alert alert-danger"></div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <input type="button" id="nuevo" value="Grabar" class="btn btn-success" />
                                    </div>
                                </div>
                                <input type="hidden" name="lenguaje" id="lenguaje" value="<?php echo $lenguaje; ?>" />
                            </form>
                        </div>
                    </div>
                </div>
                <?php  require('inc/footer.php'); ?>
            </div>
        </div>
        <script type="text/javascript">
        $(document).ready(function () {
            $('#divError').hide();
            $('#nav-tabla').addClass('active');
            $('#nav-param').addClass('active');

            $("#reseteo_clave_body_editor").Editor();
            $("#reseteo_clave_body_editor").Editor("setText", "<?php echo str_replace('"', '\"', $mensajeEmailReseteoClaveBody); ?>");
        });

        $("#nuevo").click(function() {
            $('#divError').hide();
            $('#reseteo_clave_body').val($("#reseteo_clave_body_editor").Editor("getText"));
            console.log($('#reseteo_clave_body').val());
            $('#forma').submit();
        });

        </script>
    </body>
</html>