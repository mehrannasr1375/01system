<?php
include "../include_all.php";
session_start();
if (!isset($_SESSION['u_id']))
    die('login');


//send comment
if (isset($_POST['action']) && $_POST['action'] == 'send-comment')
{
    $parent_id = ($_POST['parent'] != 0) ? (int)$_POST['parent'] : 0;

    $email   = $_POST['email'];
    $site    = $_POST['site'];
    $comment = $_POST['comment'];
    $post_id = $_POST['post'];

    if ($comment == "")
        die("false");
    else{
        $res = Comment::insertComment($email, $site, $comment, $post_id, $parent_id, $_SESSION['u_id']);
        if ($res)
            die("true");
        else
            die("false");
    }
}


//like
else if (isset($_POST['action']) && $_POST['action'] == 'like')
{
    $u_id = $_SESSION['u_id'];
    $post_id = $_POST['post_id'];
    $res = Like::likePost($u_id, $post_id, 1);
    if        ($res==1)    die('1');
    else if   ($res==3)    die('3');
    else if   ($res==4)    die('4');
    else                   die('error');
}


//dislike
else if (isset($_POST['action']) && $_POST['action'] == 'dislike')
{
    $u_id = $_SESSION['u_id'];
    $post_id = $_POST['post_id'];
    $res = Like::likePost($u_id, $post_id, 0);
    if        ($res==2)    die('2');
    else if   ($res==5)    die('5');
    else if   ($res==0)    die('0');
    else                   die('error');
}


//follow
else if (isset($_POST['action']) && $_POST['action'] == 'follow')
{
    $post_id     = $_POST['post_id'];
    $sender_u_id = $_SESSION['u_id'];
    $target_u_id = Post::getPostById($post_id)->u_id;
    $res = Friendship::sendFollowRequest($sender_u_id, $target_u_id);
    if ($res)
        die('1');
    else
        die('error');
}




