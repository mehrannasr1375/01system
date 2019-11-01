<?php


/* *************************************************************************************************************************
 *  ACTION = MULTIMEDIA
 *  PART = X
 ***************************************************************************************************************************/



// check user access level
if (! (isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2) ){
    return;
}


// get user pics
$pics_count = Pic::getPicscountOfUser($_SESSION['u_id'])['_uploadedimages'];


$part = (isset($_GET['part'])) ? $_GET['part'] : 1;
$start = $part * MAX_PICS_GRID - MAX_PICS_GRID;


$pics = Pic::getPicsOfUser($_SESSION['u_id'], MAX_PICS_GRID, $start);


$total_sections = ceil((int)$pics_count/MAX_PICS_GRID);


require_once __DIR__ . "/../../views/multimedia/index.php";