<?php
    define("CLIENT_SECRET", "42bf6ecdbd8df5d35d1bf2ba53fd0d0e");
    define("CLIENT_ID", "22CPKJ");
    define("RESPONSE_TYPE", "code");
    define("SCOPE", "activity heartrate location nutrition profile settings sleep social weight");
//    define("REDIRECT_URI", "http://localhost:8080/zabiaadmin/callback_fitbit.php");
    define("REDIRECT_URI", "https://www.southtech.pe/zabiaadmin/callback_fitbit.php");
    define("EXPIRES_IN", "2592000");
    define("FITBIT_URL_TOKEN", "https://api.fitbit.com/oauth2/token");
    define("FITBIT_URL_PROFILE", "https://api.fitbit.com/1/user/-/profile.json");
    define("FITBIT_URL_ACTIVITY", "https://api.fitbit.com/1/user/%s/activities/date/%s.json");
    define("FITBIT_URL_SLEEP", "https://api.fitbit.com/1.2/user/%s/sleep/date/%s.json");
    define("CODE_ACTIVITY_TOTAL", "total");
    define("CODE_ACTIVITY_TRACKER", "tracker");
    define("CODE_ACTIVITY_LOGGEDACTIVITIES", "loggedActivities");
    define("CODE_ACTIVITY_VERYACTIVE", "veryActive");
    define("CODE_ACTIVITY_MODERATELYACTIVE", "moderatelyActive");
    define("CODE_ACTIVITY_LIGHTLYACTIVE", "lightlyActive");
    define("CODE_ACTIVITY_SEDENTARYACTIVE", "sedentaryActive");
    define("FITBIT_URL_OAUTH", "https://www.fitbit.com/oauth2/authorize?response_type=code&client_id=" . CLIENT_ID . "&scope=" . SCOPE);

    //Fitbit para el APP
    define("CLIENT_SECRET_APP", "9fb5e4ec580ce1a4d0860182cab7cacd");
    define("CLIENT_ID_APP", "22CM2J");
    define("REDIRECT_URI_APP", "https://www.southtech.pe/zabiaadmin/callback_fitbit_app.php");
    //define("REDIRECT_URI_APP", "http://localhost:8080/zabiaadmin/callback_fitbit_app.php");
    define("FITBIT_URL_OAUTH_APP", "https://www.fitbit.com/oauth2/authorize?response_type=code&client_id=" . CLIENT_ID_APP . "&scope=" . SCOPE);
    define("APP_EXTERNA_FITBIT", "fitbit");
?>
