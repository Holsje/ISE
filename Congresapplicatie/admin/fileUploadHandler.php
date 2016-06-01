<?php

function handleFile($targetFileDir, $inputName, $fileName){
	error_reporting(0);

    if(isset($_FILES[$inputName]['error'])){
        if($_FILES[$inputName]['error'] == UPLOAD_ERR_OK){
            $uploadOk = 1;
            $temp_name = $_FILES[$inputName]['tmp_name'];
            $name = $_FILES[$inputName]['name'];
            $extension = pathinfo(basename($_FILES[$inputName]['name']),PATHINFO_EXTENSION);
            $targetFile = $targetFileDir . $fileName . '.' .  $extension;
			
            if(uploadTheFile($temp_name,'../' . $targetFile)){
                return '' . $targetFile;
            }else{
                return false;
            }
        }
    }
}

function uploadTheFile($fileToUpload, $targetFile){
    if (move_uploaded_file($fileToUpload,$targetFile)) {
		return true;
    } 
    else {
        return false;
    }
}

?>