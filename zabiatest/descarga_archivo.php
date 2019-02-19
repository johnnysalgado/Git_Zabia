<?php
date_default_timezone_set("America/Lima");

include_once 'vendor/autoload.php';
include_once "inc/templates/base.php";
include_once "inc/functions.php";

putenv('GOOGLE_APPLICATION_CREDENTIALS=oauth-service.json');
$client = new Google_Client();
$client->addScope(Google_Service_Drive::DRIVE);
$client->useApplicationDefaultCredentials();
$service = new Google_Service_Drive($client);

$fileId = "172d34xFXUbargYDfBVYhEFvyZm128QFT"; // Google File ID
$content = $service->files->get($fileId, array("alt" => "media"));
$nombreArchivo = "";

var_dump($content);
// Open file handle for output.
echo("============<br/>");
var_dump($content->getHeaders());
echo("============<br/>");
var_dump($content->getHeader("Content-Type")[0]);
if (isset($content->getHeader("Content-Type")[0])) {
    $nombreArchivo = obtieneNombreImagenDesdeMime($content->getHeader("Content-Type")[0], "T01");
}
$outHandle = fopen("imagen/tip/" . $nombreArchivo, "w+");

while (!$content->getBody()->eof()) {
        fwrite($outHandle, $content->getBody()->read(1024));
}

// Close output file handle.

fclose($outHandle);
echo "Done.\n"
?>