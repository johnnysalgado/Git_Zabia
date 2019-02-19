<?php
    define("CLIENT_WEATHER_API", "0ea74650bbb1e70bb2265d2ce0d5984f");
    define("WEATHER_URL_CLIMA", "http://api.openweathermap.org/data/2.5/weather?q=%s&units=metric&lang=es&appid=" . CLIENT_WEATHER_API);
    define("WEATHER_URL_UV", "http://api.openweathermap.org/data/2.5/uvi/forecast?lat=%s&lon=%s&appid=" . CLIENT_WEATHER_API);
    define("WEATHER_URL_POLUCION", "http://api.openweathermap.org/pollution/v1/co/%s,%s/%sZ.json?appid=" . CLIENT_WEATHER_API);
?>
