<?php
$world = trim($_GET['s']);
$world = strip_tags($_GET['s']);
$world = htmlspecialchars($world);

$posts = Post::searchPosts($world, true, true, true, 0, 10);



echo "<br>";
echo "<br>";
echo "<br>";
var_dump($posts);
echo "<br>";
echo "<br>";
echo "<br>";
echo $world;
echo "<br>";
echo "<br>";



