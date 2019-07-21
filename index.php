<?php
    /*
     *  show post or show home
     *  show page depends on 'post=x' parameter on query string
     */
    require_once ("include_all.php");
    session_start();
    if (isset($_COOKIE['remember'])) {
        if ($user = User::getUserByName($_COOKIE['u_name'])) {
            if (isset($_COOKIE['hash'])) {
                $hash_1 = $_COOKIE['hash'];
                $hash_2 = User::getRandomHash($_COOKIE['u_name']);
                if ($hash_1 == $hash_2) {
                    $_SESSION['u_id']    =  $user ->  id;
                    $_SESSION['u_name']  =  $user ->  u_name;
                    $_SESSION['u_type']  =  $user ->  u_type;
                    $_SESSION['f_name']  =  $user ->  f_name;
                    $_SESSION['l_name']  =  $user ->  l_name;
                    $_SESSION['avatar']  =  $user ->  avatar;
                    setcookie("remember", $user->u_name, time() + REMEMBER_TIME, null, null, null, true);
                }
            }
        }
    }
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>صفحه اصلی</title>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139533490-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-139533490-1');
    </script>
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/font-awesome.min.css">
    <link rel="stylesheet" href="styles/animate.css">
    <link rel="stylesheet" href="styles/owl.carousel.min.css" />
    <link rel="stylesheet" href="styles/css.css">
    <link rel="stylesheet" href="styles/rtl.css">
    <link rel="stylesheet" href="styles/devicon.min.css">
</head>
<body id="home">



<!-- spinner -->
<?php if (DEVELOPING_MODE === false)  echo "<div id='spinner'></div>"; ?>



<!-- html body parent -->
<div id="body">



    <!-- navbar -->
    <?php require_once "index/navbar.php"; ?>



    <!-- home or postPage  -->
    <?php
        if (isset($_GET['post']) and is_numeric($_GET['post']))
            require_once "index/showPost.php";
        else
            require_once "index/showHomePage.php";
    ?>



    <!-- footer -->
    <?php require_once "index/footer.php"; ?>




    <!-- go top -->
    <a href="#home" id="go-to-top"><i class="fa fa-chevron-up"></i></a>



    <!-- popup box -->
    <div id="popup-box" class="border-lr-red">
        <div class="popup-right">
            <i id="popup-i" class="fa fa-2x"></i>
        </div>
        <div id="popup-msg" class="">
            ابتدا باید به حساب کاربری خود وارد شوید!
        </div>
    </div>



    <!-- scripts -->
    <script src="scripts/jquery-3.3.1.js"></script>
    <script src="scripts/popper.min.js"></script>
    <script src="scripts/bootstrap.min.js"></script>
    <script src="scripts/owl.carousel.min.js"></script>
    <script src="scripts/wow.min.js"></script>
    <script src="scripts/smooth-scroll.min.js"></script>
    <script src="scripts/js.js"></script>
    <script src="scripts/show-post.js"></script>
    <script src="scripts/login.js"></script>
    <script src="scripts/changeAnswer.js"></script>
</div>
</body>
</html>