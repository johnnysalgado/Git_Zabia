<?php

class ImageResize {

    function __construct () {
    }

    function resize ($sourceFile, $width, $height) {
        $newFile = "";
        //echo "sourceFile: $sourceFile<br/>";
        try {
            $lastPost = strrpos ($sourceFile, "/");
            //echo "lastpost: $lastPost<br/>";
            $fileName = substr($sourceFile, $lastPost + 1, strlen($sourceFile));
            //echo ("fileName: $fileName<br/>");
            $sufix = "$width" . "x" . "$height";
            $newFile = $sufix . "\\" . $sufix . "_" . $fileName;
            //echo ("newFile: $newFile<br/>");
            $destinationFile = str_replace($fileName, $newFile, $sourceFile);
            $sourceFile = str_replace("/", "\\", $sourceFile);
            $destinationFile = str_replace("/", "\\", $destinationFile);
            $shell = "i_view32.exe " . $sourceFile . " /jpgq=75 /resize=(" . $width . "," . $height . ") /resample /convert=" . $destinationFile;
            $output = exec("$shell");
            //echo "$shell<br/>";
            //var_dump($output);
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
//        die();
        return $newFile;
    }

}


?>