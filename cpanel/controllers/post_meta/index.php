<?php


if (!(isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2)) {
    return;
}


// show all cats (default action on this script)
require_once __DIR__ . "/../../views/post_meta/index.php";

