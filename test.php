<?php
require_once ("include_all.php");


//$post_id          =   53;
//$cats             =   PostMetaRelation::getPostCategories($post_id);


$cats = Pic::getPicscountOfUser(1);
var_dump($cats);


























?>

