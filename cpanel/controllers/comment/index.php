<?php


/* *************************************************************************************************************************
 *  ACTION = COMMENTS
 *  PART = X
 *  DO = PUBLISH
 *  DO = DESTROY
 ***************************************************************************************************************************/


require_once __DIR__ . "/../../../include_all.php";


// publish comment
if (isset($_GET['do']) && isset($_GET['id'])){

    if ($_GET['do'] == 'publish'){
        $comment = Comment::getCommentById($_GET['id']);
        $comment->enabled = true;
    }
    
    else if ($_GET['do'] == 'destroy'){
        Comment::deleteCommentById($_GET['id']);
    }

}


// pagination
$part =  isset($_GET['part']) ? (int)$_GET['part'] : 1 ;
$start = ($part-1) * 8;


// get comments depends on 'part' on URL
$comments = Comment::all($start,8);


// get all comments count
$comments_count = Comment::count();


require_once __DIR__ . "/../../views/comment/index.php";

