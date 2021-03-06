<?php
    /*
     *  show post or show home or show searchPage
     *  show page depends on 'post=x' parameter on query string
     */
    require_once ("include_all.php");
    session_start();
    if ( isset($_COOKIE['remember']) ) {
        if ( $user = User::getUserByName($_COOKIE['u_name']) ) {
            if ( isset($_COOKIE['hash']) ) {
                $hash_1 = $_COOKIE['hash'];
                $hash_2 = User::getRandomHash($_COOKIE['u_name']);
                if ( $hash_1 == $hash_2 ) {
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
    <title>01System</title>
    <link rel="icon" type="image/png" href="./includes/images/2.png"/>
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139533490-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-139533490-1');
    </script>
    <link rel="stylesheet" href="styles/font-awesome.min.css">
    <link rel="stylesheet" href="styles/animate.bootstrap.owlCarousel.css">
    <link rel="stylesheet" href="styles/css.css">
    <link rel="stylesheet" href="styles/rtl.css">
    <link rel="stylesheet" href="styles/devicon.min.css">
    <script src="scripts/frameworks.js"></script>
</head>
<body id="home">



<!--  Spinner  --------------------------------------------------------------------------------------------------------------------------------------------------->
<?php if (DEVELOPING_MODE === false)  echo "<div id='spinner'></div>"; ?>



<!--  Body Parent  ----------------------------------------------------------------------------------------------------------------------------------------------->
<div id="body">



    <!--  Navbar  -->
    <?php require_once "index/navbar.php"; ?>



    <!--  home || postPage || searchPostsPage || activateUserPage  -->
    <div>
        <?php

        if ( isset($_GET['post']) && is_numeric($_GET['post']) )
            require_once __DIR__ ."/index/showPost.php";

        else if ( isset($_GET['action']) && $_GET['action'] == 'search' )
            require_once __DIR__ ."/index/searchPage.php";

        else if ( isset($_GET['action']) && $_GET['action'] == 'activate' ) {
            if ( isset($_GET['username']) && isset($_GET['code']) )
                require_once __DIR__ ."/index/activated_page.php";
        }

        else
            require_once __DIR__ ."/index/showHomePage.php";

        ?>
    </div>



    <!--  Footer  -->
    <?php require_once "index/footer.php"; ?>



    <!--  Login Form  -->
    <div id="sign-in-overlay">
        <div id="login-frame">
            <div>
                <div class="login-close">
                    <i class="fa fa-close fa-2x "></i>
                </div>
                <span class="fa-stack fa-3x login-top-i">
                    <i class="fa fa-circle fa-stack-2x text-white"></i>
                    <i class="fa fa-user-circle fa-stack-1x text-dark"></i>
                </span>
                <div class="login-title">
                    <p>ورود به حساب کاربری</p>
                </div>
            </div>
            <p id="response-signin"></p>
            <div>
                <div class="form-group m-0">
                    <label class="col-form-label text-vsm p-0">نام کاربری :</label>
                    <input type="text" class="" id="user" name="user" autocomplete="off" />
                </div>
                <div class="form-group mt-0">
                    <label class="col-form-label text-vsm p-0">رمز عبور :</label>
                    <input type="password" class="" id="pass" name="pass" autocomplete="off" />
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember" checked="checked"/>
                    <label class="custom-control-label mb-3 text-vvsm" for="remember"> مرا به خاطر بسپار </label>
                </div>
                <div class="text-center text-sm">
                    <input type="button" id="btn-sign-in" class="btn btn-outline-success-2" value="ورود"/><br/>
                    <a class="btn-signup" id="sign-up">ثبت نام</a>
                    |
                    <a class="btn-forget" id="btn-forget">فراموشی کلمه عبور</a>
                </div>
            </div>
        </div>
    </div>



    <!--  Sign Up Form  -->
    <div id="sign-up-overlay">
        <div id="sign-up-frame">
            <div>
                <div class="login-close">
                    <i class="fa fa-close fa-2x"></i>
                </div>
                <span class="fa-stack fa-3x login-top-i">
                    <i class="fa fa-circle fa-stack-2x text-white"></i>
                    <i class="fa fa-circle-o-notch fa-stack-1x text-dark"></i>
                </span>
                <div class="login-title">
                    <p>ایجاد حساب کاربری</p>
                </div>
            </div>
            <p id="signup-res"></p>
            <div class="login-body">
                <table>
                    <tr>
                        <td>
                            <input type="text" id="f_name" placeholder="نام" autocomplete="off"/>
                        </td>
                        <td>
                            <input type="text" id="l_name" placeholder="نام خانوادگی" autocomplete="off"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="username" placeholder="نام کاربری" autocomplete="off"/>
                        </td>
                        <td>
                            <input type="text" id="email" placeholder="ایمیل" autocomplete="off"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="password" id="pass_1" placeholder="رمز عبور" autocomplete="off"/>
                        </td>
                        <td>
                            <input type="password" id="pass_2" placeholder="تکرار رمز عبور" autocomplete="off"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select id="sex" style="width: 100%;height: 38px" name="sex">
                                <option value="man">مرد</option>
                                <option value="woman">زن</option>
                            </select>
                        </td>
                        <td>
                            <input id="age" type="text" id="age" placeholder="سن" autocomplete="off"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input id="description" multiple="multiple" style="width: 100%;height: 80px" type="text" name="bio" placeholder="بیوگرافی(450 کاراکتر)" autocomplete="off"/>
                        </td>
                    </tr>
                </table>
                <input type="button" id="btn-sign-up" class="btn btn-outline-success-2" value="ثبت نام"/>
            </div>
        </div>
    </div>



    <!--  Forget Password Form  -->
    <div id="forget-overlay">
        <div id="forget-frame">
            <div>
                <div class="login-close">
                    <i class="fa fa-close fa-2x"></i>
                </div>
                <span class="fa-stack fa-3x login-top-i">
                    <i class="fa fa-circle fa-stack-2x text-white"></i>
                    <i class="fa fa-question fa-stack-1x text-dark-50"></i>
                </span>
                <div class="login-title">
                    <p>فراموشی رمز عبور</p>
                </div>
            </div>
            <p id="res-failure-forget" class="mt-3 text-center text-light bg-danger"></p>
            <p id="res-success-forget" class="mt-3 text-center text-light bg-success"></p>
            <div class="login-body text-center">
                <input type="text" id="forgetemail" class="my-4" style="width: 100%" placeholder="جهت بازیابی رمز عبور آدرس ایمیل خود را وارد نمایید:"/>
                <input type="button" id="btn-forget-mail" name="btn-forget-mail" class="btn btn-outline-success-2" value="بازیابی رمز عبور"/>
            </div>
        </div>
    </div>


    
    <!--  Go Top  -->
    <a href="#home" id="go-to-top"><i class="fa fa-chevron-up"></i></a>



    <!--  Popup Box  -->
    <div id="popup-box" class="border-lr-red">
        <div class="popup-right">
            <i id="popup-i" class="fa fa-2x"></i>
        </div>
        <div id="popup-msg" class="">
            ابتدا باید به حساب کاربری خود وارد شوید!
        </div>
    </div>



</div>



<!--  Scripts  --------------------------------------------------------------------------------------------------------------------------------------------------->
<script src="scripts/js.js"></script>



</body>
</html>