<?php
session_start();


require_once __DIR__ . "/../../../include_all.php";


// check user access level
if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2))
    return;


// delete picture
if (isset($_POST['action']) && $_POST['action'] == 'destroy' && isset($_POST['name'])) {
    $pic_name = $_POST['name'];

    // remove file name from DB
    $res = Pic::deletePicByName($pic_name);

    // remove file from server
    if ($res){
        if (file_exists("../../../includes/images/uploads/multimedia/4x3/$pic_name"))
            unlink("../../../includes/images/uploads/multimedia/4x3/$pic_name");
        if (file_exists("../../../includes/images/uploads/multimedia/16x9/$pic_name"))
            unlink("../../../includes/images/uploads/multimedia/16x9/$pic_name");
        if (file_exists("../../../includes/images/uploads/multimedia/thumbnail/$pic_name"))
            unlink("../../../includes/images/uploads/multimedia/thumbnail/$pic_name");
        echo 'true';
    }
    else
        echo 'false';
} 





