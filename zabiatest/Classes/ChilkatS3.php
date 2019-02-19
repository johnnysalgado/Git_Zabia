<?php

class ChilkatS3 {
    var $awsHost = "";
	var $rest = NULL;
	var $authAws = NULL;

    function __construct ($awsHost = AWS_HOST, $awsAccessKey = AWS_ACCESS_KEY_ID, $awsSecretKey = AWS_SECRET_ACCESS_KEY, $awsRegion = AWS_REGION) {
        $this->awsHost = $awsHost;
        //security
//        chdir("C:\PHP_7.2.10");
        $glob = new COM("Chilkat_9_5_0.Global");
//        $success = $glob->UnlockBundle('Anything for 30-day trial'); //trial
//        $success = $glob->UnlockBundle('TNYNSC.CB1012020_EmzcqmDxk26p'); //anterior
        $success = $glob->UnlockBundle('PRCWLL.CB1012020_HJ4nYdcR59mg'); //nuevo
        if ($success != 1) {
            die($glob->LastErrorText);
        }
        
        // connect to aws
        $this->authAws = new COM("Chilkat_9_5_0.AuthAws");
        $this->authAws->AccessKey = $awsAccessKey;
        $this->authAws->SecretKey = $awsSecretKey;
        $this->authAws->Region = $awsRegion;
        $this->authAws->ServiceName = 's3';
        $this->connectS3();
    }

    function connectS3() {
        $bTls = 1;
        $port = 443;
        $bAutoReconnect = 1;
        $this->rest = new COM("Chilkat_9_5_0.Rest");
        try {
            $success = $this->rest->Connect($this->awsHost, $port, $bTls, $bAutoReconnect);
            if ($success != 1) {
                $this->authAws = null;
                $this->rest = null;
                die("Connect failed\n");
            }
            $success = $this->rest->SetAuthAws($this->authAws);
            if ($success != 1) {
                $this->authAws = null;
                $this->rest = null;
                die("Auth failed\n");
            }
            $this->rest->AddHeader("x-amz-acl", "public-read");
        } catch (Exception $e) {
            die($e->getMessage() . "\n");
        }
    }

    function setBucketHost($awsBucketHost) {
        if ($this->rest == null) {
            $this->authAws = null;
            die("Connection is closed\n");
        }
        $this->rest->Host = $awsBucketHost;
    }

    function loadFile($sourceFile, $destinationFile) {
        if ($this->rest == null) {
            $this->authAws = null;
            die("Connection is closed\n");
        }
        $pngData = new COM("Chilkat_9_5_0.BinData");
        $success = $pngData->LoadFile($sourceFile);
        if ($success != 1) {
            $this->authAws = null;
            $this->rest = null;
            die("Failed to load file from local filesystem.\n");
        }
        //  Indicate the Content-Type of our upload.  (This is optional)   
        $imageFileType = pathinfo($sourceFile, PATHINFO_EXTENSION);    
        $this->rest->AddHeader('Content-Type','image/' . $imageFileType);
//        echo $imageFileType;
        //  Upload the file to Amazon S3.
        $sbResponse = new COM("Chilkat_9_5_0.StringBuilder");
        $success = $this->rest->FullRequestBd('PUT', $destinationFile, $pngData, $sbResponse);
        if ($success != 1) {
            $errorMessage = $this->rest->LastErrorText . "\n";
            $pngData = null;
            $sbResponse = null;
            $this->authAws = null;
            $this->rest = null;
            die ($errorMessage);
        }
        //  Did we get a 200 response indicating success?
        $statusCode = $this->rest->ResponseStatusCode;
        if ($statusCode != 200) {
            $errorMessage = $sbResponse->getAsString() . "\n";
            $pngData = null;
            $sbResponse = null;
            $this->authAws = null;
            $this->rest = null;
            die ($errorMessage);
        }
        $sbResponse = null;
        return "Upload succesful";
    }

}
?>