<?php

    function setImageIngredient ($targetFile, $imageName) {
//        echo "user current:<br/>";
//        var_dump(get_current_user());
//        echo "<br/>";
        $pathTargetFile = VIRTUAL_DIRECTORY_PATH . "/$targetFile";
//        echo "pathTargetFile: $pathTargetFile<br/>";
        //crear las imágenes de diferentes tamaños
        $imageResize = new ImageResize();
        $imageName50x50 = $imageResize->resize ($pathTargetFile, 50, 50);
//        echo "imageName50x50: $imageName50x50<br/>";
        $imageName170x120 = $imageResize->resize ($pathTargetFile, 170, 120);
//        echo "imageName170x120: $imageName170x120<br/>";
        $imageName200x150 = $imageResize->resize ($pathTargetFile, 200, 150);
//        echo "imageName200x150: $imageName200x150<br/>";
        $imageName230x180 = $imageResize->resize ($pathTargetFile, 230, 180);
//        echo "imageName230x180: $imageName230x180<br/>";
        $imageResize = null;
        //nombrar los archivos a subir
        $pathTargetFile50x50 = VIRTUAL_DIRECTORY_PATH . "\\" . INSUMO_IMAGE_SHORT_PATH . "$imageName50x50";
        $remoteFile50x50 = AWS_BUCKET_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imageName50x50;
//        echo "pathTargetFile50x50: $pathTargetFile50x50<br/>";
//        echo "remoteFile50x50: $remoteFile50x50<br/>";

        $pathTargetFile170x120 = VIRTUAL_DIRECTORY_PATH . "\\" . INSUMO_IMAGE_SHORT_PATH . "$imageName170x120";
        $remoteFile170x120 = AWS_BUCKET_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imageName170x120;
//        echo "pathTargetFile170x120: $pathTargetFile170x120<br/>";
//        echo "remoteFile170x120: $remoteFile170x120<br/>";

        $pathTargetFile200x150 = VIRTUAL_DIRECTORY_PATH . "\\" . INSUMO_IMAGE_SHORT_PATH . "$imageName200x150";
        $remoteFile200x150 = AWS_BUCKET_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imageName200x150;
//        echo "pathTargetFile200x150: $pathTargetFile200x150<br/>";
//        echo "remoteFile200x150: $remoteFile200x150<br/>";

        $pathTargetFile230x180 = VIRTUAL_DIRECTORY_PATH . "\\" . INSUMO_IMAGE_SHORT_PATH . "$imageName230x180";
        $remoteFile230x180 = AWS_BUCKET_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imageName230x180;
//        echo "pathTargetFile230x180: $pathTargetFile230x180<br/>";
//        echo "remoteFile230x180: $remoteFile230x180<br/>";

        //luego pasarlo a AWS S3
        $chilKatS3 = new ChilkatS3();
        $chilKatS3->setBucketHost(AWS_BUCKET_HOST_ZABIA);

        $pathTargetFile = str_replace("/", "\\", $pathTargetFile);
        $remoteFile = str_replace("\\", "/",  AWS_BUCKET_IMAGE_PATH . INSUMO_IMAGE_REMOTE_PATH . $imageName);
        $output = $chilKatS3->loadFile($pathTargetFile, $remoteFile);

        $pathTargetFile50x50 = str_replace("/", "\\", $pathTargetFile50x50);
        $remoteFile50x50 = str_replace("\\", "/", $remoteFile50x50);
        $output = $chilKatS3->loadFile($pathTargetFile50x50, $remoteFile50x50);

        $pathTargetFile170x120 = str_replace("/", "\\", $pathTargetFile170x120);
        $remoteFile170x120 = str_replace("\\", "/", $remoteFile170x120);
        $output = $chilKatS3->loadFile($pathTargetFile170x120, $remoteFile170x120);

        $pathTargetFile200x150 = str_replace("/", "\\", $pathTargetFile200x150);
        $remoteFile200x150 = str_replace("\\", "/", $remoteFile200x150);
        $output = $chilKatS3->loadFile($pathTargetFile200x150, $remoteFile200x150);

        $pathTargetFile230x180 = str_replace("/", "\\", $pathTargetFile230x180);
        $remoteFile230x180 = str_replace("\\", "/", $remoteFile230x180);
        $output = $chilKatS3->loadFile($pathTargetFile230x180, $remoteFile230x180);
        $chilKatS3 = null;
    }

    function setImageRecipe ($targetFile, $imageName) {
        $pathTargetFile = VIRTUAL_DIRECTORY_PATH . "/$targetFile";
        //crear las imágenes de diferentes tamaños
        $imageResize = new ImageResize();
        $imageName170x120 = $imageResize->resize ($pathTargetFile, 170, 120);
        $imageName200x150 = $imageResize->resize ($pathTargetFile, 200, 150);
        $imageName230x180 = $imageResize->resize ($pathTargetFile, 230, 180);
        $imageResize = null;
        //nombrar los archivos a subir
        $pathTargetFile170x120 = VIRTUAL_DIRECTORY_PATH . "\\" . RECIPE_IMAGE_SHORT_PATH . "$imageName170x120";
        $remoteFile170x120 = AWS_BUCKET_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . $imageName170x120;

        $pathTargetFile200x150 = VIRTUAL_DIRECTORY_PATH . "\\" . RECIPE_IMAGE_SHORT_PATH . "$imageName200x150";
        $remoteFile200x150 = AWS_BUCKET_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . $imageName200x150;

        $pathTargetFile230x180 = VIRTUAL_DIRECTORY_PATH . "\\" . RECIPE_IMAGE_SHORT_PATH . "$imageName230x180";
        $remoteFile230x180 = AWS_BUCKET_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . $imageName230x180;

        //luego pasarlo a AWS S3
        $chilKatS3 = new ChilkatS3();
        $chilKatS3->setBucketHost(AWS_BUCKET_HOST_ZABIA);

        $pathTargetFile = str_replace("/", "\\", $pathTargetFile);
        $remoteFile = str_replace("\\", "/",  AWS_BUCKET_IMAGE_PATH . RECIPE_IMAGE_REMOTE_PATH . $imageName);
        $output = $chilKatS3->loadFile($pathTargetFile, $remoteFile);

        $pathTargetFile170x120 = str_replace("/", "\\", $pathTargetFile170x120);
        $remoteFile170x120 = str_replace("\\", "/", $remoteFile170x120);
        $output = $chilKatS3->loadFile($pathTargetFile170x120, $remoteFile170x120);

        $pathTargetFile200x150 = str_replace("/", "\\", $pathTargetFile200x150);
        $remoteFile200x150 = str_replace("\\", "/", $remoteFile200x150);
        $output = $chilKatS3->loadFile($pathTargetFile200x150, $remoteFile200x150);

        $pathTargetFile230x180 = str_replace("/", "\\", $pathTargetFile230x180);
        $remoteFile230x180 = str_replace("\\", "/", $remoteFile230x180);
        $output = $chilKatS3->loadFile($pathTargetFile230x180, $remoteFile230x180);
        $chilKatS3 = null;
    }

    function setImageIcon ($targetFile, $imageName) {
        $pathTargetFile = VIRTUAL_DIRECTORY_PATH . "/$targetFile";

        //crear las imágenes de ícono 40x40
        $imageResize = new ImageResize();
        $imageName40x40 = $imageResize->resize ($pathTargetFile, 40, 40);
        $imageResize = null;
        //nombrar los archivos a subir
        $pathTargetFile40x40 = VIRTUAL_DIRECTORY_PATH . "\\" . ICON_SHORT_PATH . "$imageName40x40";
        $remoteFile40x40 = AWS_BUCKET_IMAGE_PATH . ICON_REMOTE_PATH . $imageName40x40;

        //luego pasarlo a AWS S3
        $chilKatS3 = new ChilkatS3();
        $chilKatS3->setBucketHost(AWS_BUCKET_HOST_ZABIA);
        $pathTargetFile40x40 = str_replace("/", "\\", $pathTargetFile40x40);
        $remoteFile40x40 = str_replace("\\", "/", $remoteFile40x40);
        $output = $chilKatS3->loadFile($pathTargetFile40x40, $remoteFile40x40);
        $chilKatS3 = null;
    }

    function setImageCuisineType ($targetFile, $imageName) {
                $pathTargetFile = VIRTUAL_DIRECTORY_PATH . "/$targetFile";
                $imageResize = new ImageResize();
                $imageName170x120 = $imageResize->resize ($pathTargetFile, 170, 120);
                $imageName200x150 = $imageResize->resize ($pathTargetFile, 200, 150);
                $imageName230x180 = $imageResize->resize ($pathTargetFile, 230, 180);
                $imageResize = null;
        
                $pathTargetFile170x120 = VIRTUAL_DIRECTORY_PATH . "\\" . TYPE_IMAGE_SHORT_PATH . "$imageName170x120";
                $remoteFile170x120 = AWS_BUCKET_IMAGE_PATH . CUISINE_TYPE_IMAGE_REMOTE_PATH . $imageName170x120;
        
                $pathTargetFile200x150 = VIRTUAL_DIRECTORY_PATH . "\\" . TYPE_IMAGE_SHORT_PATH . "$imageName200x150";
                $remoteFile200x150 = AWS_BUCKET_IMAGE_PATH . CUISINE_TYPE_IMAGE_REMOTE_PATH . $imageName200x150;
        
                $pathTargetFile230x180 = VIRTUAL_DIRECTORY_PATH . "\\" . TYPE_IMAGE_SHORT_PATH . "$imageName230x180";
                $remoteFile230x180 = AWS_BUCKET_IMAGE_PATH . CUISINE_TYPE_IMAGE_REMOTE_PATH . $imageName230x180;
        
                //luego pasarlo a AWS S3
                $chilKatS3 = new ChilkatS3();
                $chilKatS3->setBucketHost(AWS_BUCKET_HOST_ZABIA);
        
                $pathTargetFile = str_replace("/", "\\", $pathTargetFile);
                $remoteFile = str_replace("\\", "/",  AWS_BUCKET_IMAGE_PATH . CUISINE_TYPE_IMAGE_REMOTE_PATH . $imageName);
                $output = $chilKatS3->loadFile($pathTargetFile, $remoteFile);
        
                $pathTargetFile170x120 = str_replace("/", "\\", $pathTargetFile170x120);
                $remoteFile170x120 = str_replace("\\", "/", $remoteFile170x120);
                $output = $chilKatS3->loadFile($pathTargetFile170x120, $remoteFile170x120);
        
                $pathTargetFile200x150 = str_replace("/", "\\", $pathTargetFile200x150);
                $remoteFile200x150 = str_replace("\\", "/", $remoteFile200x150);
                $output = $chilKatS3->loadFile($pathTargetFile200x150, $remoteFile200x150);
        
                $pathTargetFile230x180 = str_replace("/", "\\", $pathTargetFile230x180);
                $remoteFile230x180 = str_replace("\\", "/", $remoteFile230x180);
                $output = $chilKatS3->loadFile($pathTargetFile230x180, $remoteFile230x180);
                $chilKatS3 = null;
            }
        
            function setImageDishType ($targetFile, $imageName) {
                $pathTargetFile = VIRTUAL_DIRECTORY_PATH . "/$targetFile";
                $imageResize = new ImageResize();
                $imageName170x120 = $imageResize->resize ($pathTargetFile, 170, 120);
                $imageName200x150 = $imageResize->resize ($pathTargetFile, 200, 150);
                $imageName230x180 = $imageResize->resize ($pathTargetFile, 230, 180);
                $imageResize = null;
        
                $pathTargetFile170x120 = VIRTUAL_DIRECTORY_PATH . "\\" . TYPE_IMAGE_SHORT_PATH . "$imageName170x120";
                $remoteFile170x120 = AWS_BUCKET_IMAGE_PATH . DISH_TYPE_IMAGE_REMOTE_PATH . $imageName170x120;
        
                $pathTargetFile200x150 = VIRTUAL_DIRECTORY_PATH . "\\" . TYPE_IMAGE_SHORT_PATH . "$imageName200x150";
                $remoteFile200x150 = AWS_BUCKET_IMAGE_PATH . DISH_TYPE_IMAGE_REMOTE_PATH . $imageName200x150;
        
                $pathTargetFile230x180 = VIRTUAL_DIRECTORY_PATH . "\\" . TYPE_IMAGE_SHORT_PATH . "$imageName230x180";
                $remoteFile230x180 = AWS_BUCKET_IMAGE_PATH . DISH_TYPE_IMAGE_REMOTE_PATH . $imageName230x180;
        
                //luego pasarlo a AWS S3
                $chilKatS3 = new ChilkatS3();
                $chilKatS3->setBucketHost(AWS_BUCKET_HOST_ZABIA);
        
                $pathTargetFile = str_replace("/", "\\", $pathTargetFile);
                $remoteFile = str_replace("\\", "/",  AWS_BUCKET_IMAGE_PATH . DISH_TYPE_IMAGE_REMOTE_PATH . $imageName);
                $output = $chilKatS3->loadFile($pathTargetFile, $remoteFile);
        
                $pathTargetFile170x120 = str_replace("/", "\\", $pathTargetFile170x120);
                $remoteFile170x120 = str_replace("\\", "/", $remoteFile170x120);
                $output = $chilKatS3->loadFile($pathTargetFile170x120, $remoteFile170x120);
        
                $pathTargetFile200x150 = str_replace("/", "\\", $pathTargetFile200x150);
                $remoteFile200x150 = str_replace("\\", "/", $remoteFile200x150);
                $output = $chilKatS3->loadFile($pathTargetFile200x150, $remoteFile200x150);
        
                $pathTargetFile230x180 = str_replace("/", "\\", $pathTargetFile230x180);
                $remoteFile230x180 = str_replace("\\", "/", $remoteFile230x180);
                $output = $chilKatS3->loadFile($pathTargetFile230x180, $remoteFile230x180);
                $chilKatS3 = null;
            }

?>