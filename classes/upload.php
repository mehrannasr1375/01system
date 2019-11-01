<?php
class  Upload
{
    private $fileName;     //construct
    private $fileType;     //construct
    private $fileTmpName;  //construct
    private $fileError;    //construct
    private $fileSize;     //construct
    private $fileExt;
    private $fileNameNew;
    private $destination;


    public function __construct($uploadFieldName)
    {
        $this -> fileName    =  $_FILES[$uploadFieldName]['name'];
        $this -> fileType    =  $_FILES[$uploadFieldName]['type'];
        $this -> fileTmpName =  $_FILES[$uploadFieldName]['tmp_name'];
        $this -> fileError   =  $_FILES[$uploadFieldName]['error'];
        $this -> fileSize    =  $_FILES[$uploadFieldName]['size'];
    }
    public function __get($property)
    {
        if(in_array($property, ['fileName','fileType','fileTmpName','fileError','fileSize','fileExt','fileNameNew','destination']))
            return $this -> $property;
        else
            return 'try to get an invalid or inaccessible property : '."$property";
    }


    public function checkImg($maxSize=1024, $acceptable_types=['jpg', 'jpeg', 'png', 'gif'])
    {
        // return false if no image uploaded
        if ($this->fileSize == 0)
            return [false, "there is no file for upload"];

        // set new name and extension
        $extTmp            = explode(".", $this -> fileName);
        $this->fileExt     = strtolower(end($extTmp));
        $this->fileNameNew = time().".".rand().".".$this -> fileExt;

        // check extension && size && errors while upload
        if (in_array($this->fileExt, $acceptable_types)) {
            if ($this->fileError === 0) {
                if ($this->fileSize < $maxSize)
                    return [true, "success"];
                else
                    return [false, "big"];
            } else
                return [false, "uploaderror"];
        } else
            return [false, "notimage"];
    }

    public function resizeAndSaveImg($newWidth, $newHeight, $new_path='../includes/images/uploads/posts/', $quality=100)
    {
        if(!$this -> fileType)
            return false;

        switch ($this -> fileType) {
            case "image/png":
                $img = imagecreatefrompng($this->fileTmpName);
                break;
            case "image/gif":
                $img = imagecreatefromgif($this->fileTmpName);
                break;
            default:
                $img = imagecreatefromjpeg($this->fileTmpName);
                break;
        }

        list($w, $h) = getimagesize($this->fileTmpName);
        $tmpImg = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmpImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $w, $h);
        $newImgPath = $new_path.$this->fileNameNew;
        if (imagejpeg($tmpImg, $newImgPath, $quality)) {
            $this->destination = $newImgPath;
            imagedestroy($tmpImg);
            imagedestroy($img);
            return true;
        } else
            return false;
    }


}