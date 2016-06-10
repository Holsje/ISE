<?php

function handleFile($targetFileDir, $inputName, $fileName){
    if(isset($_FILES[$inputName]['error'])){
        
        if($_FILES[$inputName]['error'] == UPLOAD_ERR_OK){
            $uploadOk = 1;
            $temp_name = $_FILES[$inputName]['tmp_name'];
            $name = $_FILES[$inputName]['name'];
            $extension = pathinfo(basename($_FILES[$inputName]['name']),PATHINFO_EXTENSION);
            
            $targetFile = $targetFileDir . $fileName . '.png'  ;
            
            if(uploadTheFile($temp_name,'../' . $targetFile)){
                return '' . $targetFile;
            }else{
                return false;
            }
        }
    }
}

function uploadTheFile($fileToUpload, $targetFile){
    if(getimagesize($fileToUpload)){
        return imagepng(imagecreatefromstring(file_get_contents($fileToUpload)),$targetFile);
    }
    else if (move_uploaded_file($fileToUpload,$targetFile)) {
		return true;
    } 
    else {
        return false;
    }
}

function Delete($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            Delete(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}

?>