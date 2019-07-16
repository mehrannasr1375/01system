<?php
require_once ("../include_all.php");
session_start();
ob_start();
if(!isset($_SESSION['u_id'])) //return to home page if user not logged in
    header("Location: /index.php");
$user_info = User::getUserInformations($_SESSION['u_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>پیشخوان</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/font-awesome.min.css">
    <link rel="stylesheet" href="../styles/cpanel.css"/>
    <link rel="stylesheet" href="../styles/rtl.css"/>
</head>
<body>

<div id="spinner"></div>

<div id="body"> <!--body-->
    <div id="topbar"><p>خوش آمدید <?=$_SESSION['u_name']?></p>|<p id="time"></p>|<a href="../index.php" class="btn-3"><i class="fa fa-sign-out p-1"></i>صفحه اصلی</a></div>
    <div id="wrapper" class="row">
        <div id="right">
            <div id="avatar-container"><img class="avatar-img" src="../includes/images/uploads/avatars/<?=$_SESSION['avatar']?>" alt="avatar" /></div>
            <ul class="list-group">
                <input type="hidden" name="active_ul" value="1" />
                <a class="list-item"  href="./cpanel.php?action=dashboard" id="l1">
                    <i class="fa fa-dashboard"></i>
                    پیشخوان
                </a>

                <?php if ($_SESSION['u_type'] == 1) { ?>
                    <a class="list-item"  href="./cpanel.php?action=showposts"  id="l2" >
                        <i class="fa fa-list"></i>
                        پست ها
                        <span class="badge"><?=(int)Post::getPostsCounts()['published']?></span>
                    </a>
                    <a class="list-item"  href="./cpanel.php?action=newpost"    id="l3" >
                        <i class="fa fa-plus"></i>
                        پست جدید
                    </a>
                    <a class="list-item"  href="./cpanel.php?action=users"      id="l4" >
                        <i class="fa fa-users"></i>
                        کاربران
                    </a>
                    <a class="list-item"  href="./cpanel.php?action=multimedia" id="l5" >
                        <i class="fa fa-picture-o"></i>
                        چند رسانه ای
                        <span class="badge"><?=$user_info['images_count']?></span>
                    </a>
                    <a class="list-item"  href="./cpanel.php?action=categories" id="l6" >
                        <i class="fa fa-object-group"></i>
                        گروه ها
                    </a>
                <?php } elseif ($_SESSION['u_type'] == 2) { ?>
                    <a class="list-item"  href="./cpanel.php?action=showposts"  id="l2" >
                        <i class="fa fa-list"></i>
                        پست ها
                        <span class="badge"><?=(int)Post::getPostsCounts()['published']?></span>
                    </a>
                    <a class="list-item"  href="./cpanel.php?action=newpost"    id="l3" >
                        <i class="fa fa-plus"></i>
                        پست جدید
                    </a>
                    <a class="list-item"  href="./cpanel.php?action=multimedia" id="l5" >
                        <i class="fa fa-picture-o"></i>
                        چند رسانه ای
                    </a>
                    <a class="list-item"  href="./cpanel.php?action=categories" id="l6" >
                        <i class="fa fa-object-group"></i>
                        گروه ها
                    </a>
                <?php } ?>

                <a class="list-item"  href="./cpanel.php?action=signout" id="l7" >
                    <i class="fa fa-sign-out"></i>
                    خروج
                </a>
            </ul>
        </div>
        <div id="left" style="display:none;">
            <?php
            if     (isset($_GET['action']) and $_GET['action']=='newpost')
                include ("./action/newpost.php");
            elseif (isset($_GET['action']) and $_GET['action']=='showposts')
                include ("./action/showposts.php");
            elseif (isset($_GET['action']) and $_GET['action']=='users')
                include ("./action/users.php");
            elseif (isset($_GET['action']) and $_GET['action']=='multimedia')
                include ("./action/multimedia.php");
            elseif (isset($_GET['action']) and $_GET['action']=='categories')
                include ("./action/categories.php");
            elseif (isset($_GET['action']) and $_GET['action']=='signout')
                include ("./action/signout.php");
            elseif (isset($_GET['action']) and $_GET['action']=='dashboard')
                include ("./action/dashboard.php");
            else
                include ("./action/dashboard.php");
            ?>
        </div>
    </div>
</div> <!--body-->

<!-- popup box -->
<div id="popup-box-c" class="border-lr-red">
    <div class="popup-right">
        <i id="popup-i" class="fa fa-2x"></i>
    </div>
    <div id="popup-msg" class="">
        ابتدا باید به حساب کاربری خود وارد شوید!
    </div>
</div>

<!--scripts-->
<script src="../scripts/jquery-3.3.1.js"></script>
<script src="../scripts/popper.min.js"></script>
<script src="../scripts/bootstrap.min.js"></script>
<script src="../scripts/cpanel.js"></script>
<script> var dt = new Date();var time = dt.getHours()+":"+dt.getMinutes();$("p#time").html('ساعت   '+time);setInterval(function(){$("p#time").html('ساعت   '+time);},  1000);</script>
<script src="./ckeditor/ckeditor.js"></script>
<script src="./ckeditor/lang/fa.js"></script>
<!--<script> ClassicEditor.create(document.querySelector('#area'), {language:'fa'});</script>-->
<script>
    CKEDITOR.replace( 'area', {
    language: 'fa'
});
</script>
</body>
</html>