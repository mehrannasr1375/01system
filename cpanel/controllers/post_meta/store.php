<?php

require_once __DIR__ . "/../../../include_all.php";


if (!isset($_GET['action']))
    header('location:../../cpanel.php?action=categories');

   
// create category
if ($_GET['action'] == 'create_cat')
    PostMeta::create('category', $_POST['cat_title'], $_POST['cat_parent']);

   
// create tag
if ($_GET['action'] == 'create_tag')
    PostMeta::create('tag', $_POST['tag_title'], 0);


header('location:../../cpanel.php?action=categories');
