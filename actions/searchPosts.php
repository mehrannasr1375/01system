<?php
include "../include_all.php";

if (isset ($_POST['text'])) {
    $text = strip_tags(trim($_POST['text']));
    $text = htmlspecialchars($_POST['text'],ENT_QUOTES);
    $posts = Post::searchPosts($text,true,true,true,10);
    $result_count = count((array)($posts));






}