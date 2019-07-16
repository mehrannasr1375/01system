<?php
session_start();
require_once ("../../include_all.php");
if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2))
    return;


// upload photo from new post
if(isset($_FILES['p_image']) && count($_FILES['p_image'])>0) {
    $tmp_field_name = 'p_image';
    $upload_object = new Upload('p_image');
    if ($upload_object->checkImg(2000000)[0] == true) {
        if ($upload_object->resizeAndSaveImg(1024,500,"../../includes/images/uploads/posts/1024x500/",POST_IMG_QAL_1024X500)) {
            if ($upload_object->resizeAndSaveImg(260,260,"../../includes/images/uploads/posts/260x260/",POST_IMG_QAL_260X260)) {
                $pic_name = $upload_object->fileNameNew;
                if (Pic::insertPic($pic_name, $_SESSION['u_id']))
                    die($pic_name);
                else
                    die("error_on_insert_to_database");
            }
        }
    }
    else
        die("large");
}


// upload photo from multimedia
elseif (isset($_FILES['pic']) && count($_FILES['pic'])>0) {
$tmp_field_name = 'pic';
$upload_object = new Upload('pic');
if ($upload_object->checkImg(2000000)[0] == true) {
    if ($upload_object->resizeAndSaveImg(1024,768,"../../includes/images/uploads/multimedia/4x3/",MEDIA_IMG_QAL_4X3)) {
        if ($upload_object->resizeAndSaveImg(1024,576,"../../includes/images/uploads/multimedia/16x9/",MEDIA_IMG_QAL_16X9)) {
            if ($upload_object->resizeAndSaveImg(140,140,"../../includes/images/uploads/multimedia/thumbnail/",MEDIA_IMG_QAL_2X2)) {
                $pic_name = $upload_object->fileNameNew;
                if (Pic::insertPic($pic_name, $_SESSION['u_id']))
                    die($pic_name);
                else
                    die("error_on_insert_to_database");
            }
        }
    }
}
else
    die("large");
}


// delete photo from multimedia
