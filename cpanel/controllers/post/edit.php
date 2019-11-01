<?php


/*** AJAX ******************************************************************************************************************************************************
 * edit post
 * returns 'inserted id' or 'false'
 ***************************************************************************************************************************************************************/


require_once __DIR__ . '/../../../include_all.php';


// show post fields for edit
if (isset($_GET['id'])){

    $post = Post::getPostById($_GET['id']);

    $p_id               =  $post->id;
    $p_title            =  $post->p_title;
    $p_content          =  $post->p_content;
    $p_image_name       =  $post->p_image;
    $p_image_thumbnail  =  './../includes/images/uploads/posts/260x260/' . $post->p_image;
    $p_image_wide       =  './../includes/images/uploads/posts/1024x500/' . $post->p_image;
    $u_id               =  $post->u_id;
    $published          =  $post->published ? 'checked':'unchecked';
    $allow_comments     =  $post->allow_comments ? 'checked':'unchecked';
    $creation_time      =  $post->creation_time;
    $last_modify        =  $post->last_modify;
    $like_count         =  $post->like_count;
    $dislike_count      =  $post->dislike_count;
    $u_name             =  $post->u_name;
    $f_name             =  $post->f_name;
    $l_name             =  $post->l_name;
    $access_level       =  $post->access_level;
    $cats               =  $post->cats;
    $tags               =  $post->tags;

    require_once __DIR__ . "/../../views/post/edit.php";
}

