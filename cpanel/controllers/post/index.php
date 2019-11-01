<?php


/* **********************************************************************************************************************************************
 *       ACTION = SHOWPOSTS -> ALWAYS EXISTS ON THIS SCRIPT
 *       DO = delete                  ||
 *       DO = publish                 ||
 *       DO = restoredelete           ||
 *       DO = permanentdelete         ||
 *       DO = unpublish               \/
 *       ID = X               (USE WITH TOP ITEM)
 *       (edit has been reffered to 'newpost.php')
 * **********************************************************************************************************************************************/


// check user access level
if (! (isset($_SESSION['u_id']) or $_SESSION['u_type']==1 or $_SESSION['u_type']==2) ){
    return;
}


// delete post
else if (isset($_GET['do']) and $_GET['do']=='delete' and isset($_GET['id'])){
    $res = Post::deletePostById($_GET['id'], false);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}


// restore deleted post
else if (isset($_GET['do']) and $_GET['do']=='restoredelete' and isset($_GET['id'])){
    $res = Post::restorePost($_GET['id']);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}


// permanent delete post
else if (isset($_GET['do']) and $_GET['do']=='permanentdelete' and isset($_GET['id'])){
    $res = Post::deletePostById($_GET['id'], true);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}


// publish post
else if (isset($_GET['do']) and $_GET['do']=='publish' and isset($_GET['id'])){
    $res = Post::publishPost($_GET['id'],1);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}


// unpublish post
else if (isset($_GET['do']) and $_GET['do']=='unpublish' and isset($_GET['id'])){
    $res = Post::publishPost($_GET['id'],0);
    if ($res)
        header("Location: ./cpanel.php?action=showposts");
}


// default action on this page (show table)
else {

    // get posts count
    $post_counts       =  Post::getPostscounts();
    $publishedcount    =  $post_counts['published'];
    $unpublishedcount  =  $post_counts['unpublished'];
    $deletedcount      =  $post_counts['deleted'];

    // pagination
    $part = isset($_GET['part']) ? (int)$_GET['part'] : 1;
    $start = ($part - 1) * MAX_POSTS_TABLE;

    // get posts
    $recycle_flag = false;
    if (!isset($_GET['publish'])){
        $posts = Post::getAllPosts(1, 0, MAX_POSTS_TABLE, $start);
        $total_sections = ceil((int) $publishedcount / MAX_POSTS_TABLE);
        $published_status = 'published';
    }
    else if ($_GET['publish'] == 'published'){
        $posts = Post::getAllPosts(1, 0, MAX_POSTS_TABLE, $start);
        $total_sections = ceil((int) $publishedcount / MAX_POSTS_TABLE);
        $published_status = 'published';
    }
    else if ($_GET['publish'] == 'unpublished'){
        $posts = Post::getAllPosts(0, 0, MAX_POSTS_TABLE, $start);
        $total_sections = ceil((int) $unpublishedcount / MAX_POSTS_TABLE);
        $published_status = 'unpublished';
    }
    else if ($_GET['publish'] == 'deleted'){
        $posts = Post::getAllPosts(1, 1, MAX_POSTS_TABLE, $start);
        $total_sections = ceil((int) $deletedcount / MAX_POSTS_TABLE);
        $published_status = 'deleted';
    }

    require_once __DIR__ . "/../../views/post/index.php";

}


?>

