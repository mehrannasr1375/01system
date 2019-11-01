<?php
session_start();


/*** AJAX ******************************************************************************************************************************************************
 * create or update post
 * returns 'inserted id' or 'false'
 ***************************************************************************************************************************************************************/


require_once __DIR__ . '/../../../include_all.php';


// save post
if (isset($_POST['action']) && $_POST['action'] == 'create'){
    $res = Post::create(
        $_POST['p_title'],
        $_POST['p_content'],
        (isset($_POST['post-img-name']) && $_POST['post-img-name']!=0) ? $_POST['post-img-name'] : POST_IMG_DEFAULT,
        $_SESSION['u_id'],
        $_POST['published'] == 'true' ? 1:0,
        $_POST['allow_comments'] == 'true' ? 1 : 0,
        isset($_POST['cats']) ? $_POST['cats'] : [],
        isset($_POST['tags']) ? $_POST['tags'] : [],
        (int)$_POST['access_level']
    );
    if ($res)
        echo $res;
    else
        echo 'false';
}


// edit post
else if (isset($_POST['action']) && $_POST['action'] == 'edit'){
    $res = Post::update(
        $_POST['p_id'],
        $_POST['p_title'],
        $_POST['p_content'],
        (isset($_POST['post-img-name']) && $_POST['post-img-name']!=0) ? $_POST['post-img-name'] : POST_IMG_DEFAULT,
        $_SESSION['u_id'],
        $_POST['published'] == 'true' ? 1:0,
        $_POST['allow_comments'] == 'true' ? 1 : 0,
        isset($_POST['cats']) ? $_POST['cats'] : [],
        isset($_POST['tags']) ? $_POST['tags'] : [],
        $_POST['access_level']
    );
    if ($res)
        echo $res;
    else
        echo 'false';
}


// save new category
else if (isset($_POST['new_cat_title'])){
    if (PostMeta::exists('category', $_POST['new_cat_title'], $_POST['new_cat_parent_id']))
        return;

    if ($res = PostMeta::create('category', $_POST['new_cat_title'], $_POST['new_cat_parent_id']))
        echo '{"id":' . $res . ',"title":"' . $_POST['new_cat_title'] . '","parent_id":' . $_POST['new_cat_parent_id'] . '}';

    return;
}


// save new tag
else if (isset($_POST['new_tag_title'])){
    if (PostMeta::exists('tag', $_POST['new_tag_title'], 0))
        return;

    if ($res = PostMeta::create('tag', $_POST['new_tag_title']))
        echo '{"id":' . $res . ',"title":"' . $_POST['new_tag_title'] . '"}';

    return;
}