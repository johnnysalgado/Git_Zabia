<?php
    require 'vendor/autoload.php';
    require 'inc/constante.php';

    use Aws\S3\S3Client;

    $credentials = new Aws\Credentials\Credentials(AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY);

    $sharedConfig = [
        'region'  => 'us-west-2',
        'version' => 'latest',
        'credentials' => $credentials
    ];

    // Create an SDK class used to share configuration across clients.
    $sdk = new Aws\Sdk($sharedConfig);

    // Create an Amazon S3 client using the shared configuration data.
    $s3Client = $sdk->createS3();

    // Send a PutObject request and get the result object.
    $result = $s3Client->putObject([
        'Bucket' => 'my-bucket',
        'Key'    => AWS_ACCESS_KEY_ID,
        'Body'   => 'this is the body!'
    ]);

    // Download the contents of the object.
    $result = $s3Client->getObject([
        'Bucket' => 'my-bucket',
        'Key'    => 'my-key'
    ]);

    // Print the body of the result by indexing into the result object.
    echo $result['Body'];




    /*
    set_time_limit(2000);

    $cnx = new MySQL();
    $query = "SELECT id, id_padre FROM receta1 ORDER BY id LIMIT 3000 ";
    $sql = $cnx->query($query);
    $sql->read();
    $idPadrex = 0;
    while($sql->next()) {
        $id = $sql->field('id');
        $idPadre = $sql->field('id_padre');
        if ($idPadre > 0) {
            $idPadrex = $idPadre;
        }
        if ($idPadre == 0) {
            $update = "UPDATE receta1 set id_padre = " . $idPadrex . " WHERE id = " . $id;
            echo $update . "<br/>";
            $cnx->execute($update);
        }
    }

    $query = "SELECT distinct imagen FROM comercio2 where imagen <> '' and tipo_comercio='Terapia alternativa' LIMIT 500";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $imagen = $sql->field('imagen');
        echo "á: " . strpos($imagen, "á") . "<br/>"; 
        echo "é: " . strpos($imagen, "é") . "<br/>"; 
        echo "í: " . strpos($imagen, "í") . "<br/>"; 
        echo "ó: " . strpos($imagen, "ó") . "<br/>"; 
        echo "ú: " . strpos($imagen, "ú") . "<br/>"; 
        echo "ñ: " . strpos($imagen, "ñ") . "<br/>"; 
        echo "Á: " . strpos($imagen, "Á") . "<br/>"; 
       
        if (strpos($imagen, "á") > -1 || strpos($imagen, "é") > -1 || strpos($imagen, "í") > -1
        || strpos($imagen, "ó") > -1 || strpos($imagen, "ú") > -1 || strpos($imagen, "Á") > -1
        || strpos($imagen, "É") > -1 || strpos($imagen, "Í") > -1 || strpos($imagen, "Ó") > -1
        || strpos($imagen, "Ú") > -1 || strpos($imagen, "ñ") > -1 || strpos($imagen, "Ñ") > -1) {
            echo "start chrome " . str_replace(" ", "+", $imagen) . "<br/>";
 
            echo "wget --keep-session-cookies --cookies=on --no-check-certificate --restrict-file-names=nocontrol --convert-links " . str_replace(" ", "+", $imagen) . "<br/>";
            echo "wget --keep-session-cookies --cookies=on --no-check-certificate --restrict-file-names=nocontrol --convert-links " . str_replace("%3A", ":", str_replace("%2F", "/", urlencode($imagen))) . "<br/>";
        //}
    }


    $query = "SELECT imagen_marker FROM comercio2 where imagen_marker <> '' and tipo_comercio = 'Terapia alternativa' LIMIT 1000";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $imagen = $sql->field('imagen_marker');
        echo "wget --no-check-certificate " . str_replace("%3A", ":", str_replace("%2F", "/", urlencode($imagen))) . "<br/>";
    }
      
    $query = "SELECT id_insumo, imagen FROM insumo where imagen <> ''";
    $sql = $cnx->query($query);
    $sql->read();
    while($sql->next()) {
        $idInsumo = $sql->field('id_insumo');
        $imagen = $sql->field('imagen');
        $rutaActual = INSUMO_IMAGE_SHORT_PATH . $imagen;
        $imagen = $idInsumo . "_" . $imagen;
        $rutaNueva = INSUMO_IMAGE_SHORT_PATH . $imagen;
        $update = "UPDATE insumo SET imagen = '" . $imagen . "' WHERE id_insumo = " . $idInsumo;
        $cnx->execute($update);
        rename($rutaActual, $rutaNueva);
    }
    $cnx = null;
*/
?>