<?php 
    session_start();
    require('inc/constante_fitbit.php');
    require('inc/configuracion.php');
    require('inc/mysql.php');

?>
<?php
    if (isset($_GET['code'])) {
        $code = $_GET["code"];
        $auth_header = array(
            "Authorization: Basic " . base64_encode(CLIENT_ID_APP . ":" . CLIENT_SECRET_APP)
            , "Content-Type: application/x-www-form-urlencoded"
        );
        $url = FITBIT_URL_TOKEN;
        $access_token_setttings = array(
                "code" =>  $code,
                "grant_type" => "authorization_code",
                "client_id" =>  CLIENT_ID_APP,
                "expires_in" => EXPIRES_IN,
                "redirect_uri" => REDIRECT_URI_APP
            );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $auth_header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($access_token_setttings));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);

        $results = json_decode($result, true);
        //var_dump($result);
        //echo '<br>';
        //echo 'Code: ' . $code . '<br>';
        //die();

        $acces_token = $results["access_token"];
        $refresh_token = $results["refresh_token"];
        $scope = $results["scope"];
        $token_type = $results["token_type"];
        $user_id = $results["user_id"];

        //grabar datos del perfil
        $user = $_SESSION["user_app"];
        $urlRetorno = $_SESSION["url_retorno_app"];

        $cnx = new MySQL();
        $query = "SELECT id_usuario FROM usuario_app_externa WHERE id_usuario = '" . $user . "' AND app_externa = '" . APP_EXTERNA_FITBIT . "'";
        $sql = $cnx->query($query);
        $sql->read();
        if ($sql->count() > 0) {
            $query = "UPDATE usuario_app_externa SET access_token = '" . $acces_token . "', refresh_token = '" . $refresh_token . "', token_type = '" . $token_type . "', user_id = '" . $user_id . "', fecha_modifica = CURRENT_TIMESTAMP, usuario_modifica = '" . $user . "' WHERE id_usuario = '"  . $user . "' AND app_externa = '" . APP_EXTERNA_FITBIT . "'";
        } else {
            $query = "INSERT INTO usuario_app_externa (id_usuario, app_externa, access_token, refresh_token, token_type, user_id, usuario_registro) VALUES ('" . $user . "', '" . APP_EXTERNA_FITBIT . "', '" . $acces_token . "', '" . $refresh_token . "', '" . $token_type . "', '" . $user_id . "', '" . $user . "')";
        }
        $cnx->execute($query);

        $cnx = null;
?>
    <html>
        <head>
            <title>Zabia</title>
            <META HTTP-EQUIV="REFRESH" CONTENT="1;URL=<?php echo $urlRetorno; ?>">
        </head>
    </html>
<?php
    } else {

        if (isset($_GET["user"])) {
            $_SESSION["user_app"] = $_GET["user"];
        }
        if (isset($_GET["url_retorno"])) {
            $_SESSION["url_retorno_app"] = $_GET["url_retorno"];
        }
?>
    <html>
        <head>
            <title>Zabia</title>
            <META HTTP-EQUIV="REFRESH" CONTENT="1;URL=<?php echo FITBIT_URL_OAUTH_APP; ?>">
        </head>
    </html>
<?php
    }
?>
