<?php
require_once ("../include_all.php");
session_start();
ob_start();

//return if user not logged in
if (!isset($_SESSION['u_id'])) 
    header("Location: /index.php");

// get logged in user informations    
$user_info = User::getUserInformations($_SESSION['u_id']);
if ($_SESSION['u_type'] == 1)
    $access = 'admin';
else if ($_SESSION['u_type'] == 2)
    $access = 'editor';
else 
    $access = 'registered';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>پیشخوان</title>
    <link rel="stylesheet" href="../styles/animate.bootstrap.owlCarousel.css">
    <link rel="stylesheet" href="../styles/font-awesome.min.css">
    <link rel="stylesheet" href="../styles/cpanel.css"/>
    <link rel="stylesheet" href="../styles/rtl.css"/>
    <script src="../scripts/jquery.popper.bootstrap.js"></script>
</head>
<body>



<!-- body ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<div id="body" class="container-fluid">



    <!-- top bar ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
    <div id="topbar">
        <p>
            خوش آمدید
            <?=$_SESSION['u_name']?>
        </p>|
        <p id="time"></p>
        
    </div>



    <!-- right && left bars -->
    <div id="wrapper">


        <!-- menu bar ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
        <div id="right">
            <div id="avatar-container"><img class="avatar-img" src="../includes/images/uploads/avatars/<?=$_SESSION['avatar']?>" alt="avatar" /></div>
            <ul class="list-group">

                <input type="hidden" name="active_ul" value="1" />

                <!-- common -->
                <a class="list-item"  href="./cpanel.php?action=dashboard" id="l1"><i class="fa fa-dashcube"></i>پیشخوان</a>
                <a class="list-item"  href="./cpanel.php?action=messages" id="l2"><i class="fa fa-envelope-open-o"></i>پیام ها</a>

                <?php

                // admin
                if ($_SESSION['u_type'] == 1) {
                ?>
                    <a class="list-item"  href="./cpanel.php?action=showposts" id="l3" ><i class="fa fa-list"></i>پست ها</a>
                    <a class="list-item"  href="./cpanel.php?action=newpost" id="l4" ><i class="fa fa-plus"></i>پست جدید</a>
                    <a class="list-item"  href="./cpanel.php?action=users" id="l5" ><i class="fa fa-users"></i>کاربران</a>
                    <a class="list-item"  href="./cpanel.php?action=multimedia" id="l6" ><i class="fa fa-picture-o"></i>چند رسانه ای</a>
                    <a class="list-item"  href="./cpanel.php?action=categories" id="l7" ><i class="fa fa-sitemap"></i>دسته بندی ها</a>
                    <a class="list-item"  href="./cpanel.php?action=comments" id="l8"> <i class="fa fa-comments-o"></i>نظرات</a>
                <?php
                }

                // editor
                else if ($_SESSION['u_type'] == 2) {
                ?>
                    <a class="list-item"  href="./cpanel.php?action=showposts" id="l4" ><i class="fa fa-list"></i>پست ها</a>
                    <a class="list-item"  href="./cpanel.php?action=newpost" id="l5" ><i class="fa fa-plus"></i>پست جدید</a>
                    <a class="list-item"  href="./cpanel.php?action=multimedia" id="l6" ><i class="fa fa-picture-o"></i>چند رسانه ای</a>
                    <a class="list-item"  href="./cpanel.php?action=categories" id="l7" ><i class="fa fa-object-group"></i>دسته بندی ها</a>
                <?php
                }
                ?>

                <!-- common -->
                <a class="list-item" href="./cpanel.php?action=signout" id="l9" ><i class="fa fa-sign-out"></i>خروج</a>
                <a class="list-item" href="../index.php" id="l10" ><i class="fa fa-arrow-left"></i>صفحه اصلی سایت</a>

            </ul>
        </div>


        <!-- left container -------------------------------------------------------------------------------------------------------------------------------------------------------------->
        <div id="main">
            <?php

            if (isset($_GET['action']) and $_GET['action'] == 'dashboard')
                include ("./controllers/dashboard/index.php");

            else if (isset($_GET['action']) and $_GET['action'] == 'messages')
                include("./views/message/index.php");

            else if (isset($_GET['action']) and $_GET['action'] == 'newpost')
                include("./views/post/create.php");

            else if (isset($_GET['action']) and $_GET['action'] == 'editpost')
                include ("./controllers/post/edit.php");

            else if (isset($_GET['action']) and $_GET['action'] == 'showposts')
                include("./controllers/post/index.php");

            else if (isset($_GET['action']) and $_GET['action'] == 'users')
                include ("./controllers/user/list.php");

            else if (isset($_GET['action']) and $_GET['action'] == 'multimedia')
                include ("./controllers/multimedia/index.php");

            else if (isset($_GET['action']) and $_GET['action'] == 'categories')
                include ("./controllers/post_meta/index.php");

            else if (isset($_GET['action']) and $_GET['action'] == 'comments')
                include ("./controllers/comment/index.php");

            else if (isset($_GET['action']) and $_GET['action'] == 'signout')
                include ("./controllers/signout/index.php");

            else
                include ("./views/dashboard/index.php");
            ?>

        </div>


    </div>



</div>



<!-- popup box -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
<div id="popup-box-c" class="border-lr-red">
    <div class="popup-right">
        <i id="popup-i" class="fa fa-2x"></i>
    </div>
    <div id="popup-msg" class="">
        ابتدا باید به حساب کاربری خود وارد شوید!
    </div>
</div>



<!-- scripts ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
<script>
    $(document).ready(function(){
        // show time
        function showTime() {
            var now = new Date();
            var h   = now.getHours() < 10 ? '0'+now.getHours() : now.getHours();
            var m   = now.getMinutes() < 10 ? '0'+now.getMinutes() : now.getMinutes();
            var s   = now.getSeconds() < 10 ? '0'+now.getSeconds() : now.getSeconds();
            $('#time').html( s + " : " + m + " : " + h );
            setTimeout(showTime, 1000);
        }showTime();
    });
</script>




</body>
</html>