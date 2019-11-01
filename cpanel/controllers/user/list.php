<?php
require_once __DIR__ . '/../../../include_all.php';


/**********************************************************************************************************************************************************
 *       ACTION = USERS    (ALWAYS EXISTS ON THIS SCRIPT)
 *       DO = upgrade
 *       DO = dowgrade
 *       DO = delete
 *       ID = X             (USE WITH TOP ITEM)
 **********************************************************************************************************************************************************/


// check user access level
if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 )){
    return;
}


// upgrade user
if (isset($_GET['do']) and $_GET['do']=='upgrade' and isset($_GET['id'])){
    $user = (int)User::getUserTypeById($_GET['id']);
    if ($user<3)
        User::changeUserTypeById($_GET['id'], $user+1);
}


// downgrade user
else if (isset($_GET['do']) and $_GET['do']=='downgrade' and isset($_GET['id'])){
    $user = (int)User::getUserTypeById($_GET['id']);
    if ($user>1)
        User::changeUserTypeById($_GET['id'], $user-1);
}


// delete user
else if (isset($_GET['do']) and $_GET['do']=='delete' and isset($_GET['id'])){
    $res = User::delete($_GET['id'],0);
}


// default action
require_once __DIR__ . '/../../views/user/list.php';
